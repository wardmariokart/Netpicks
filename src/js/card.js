import anime from './lib/anime.es.js';
import {map} from './helpers.js';

const overDropOffClass = 'card--over-drop-off';
const markForDestroyClass = 'marked-for-destroy';

export class Card {

  constructor($element)
  {
    this.grabbedBy = null;
    this.origin = {x: 0, y: 0, z: 0, scale: 0, rotation: 0};
    this.stackOffsetPx = {y: 30, z: - 5};
    this.dropOffTreshold = 200;
    this.bDestroyed = false;
    this.onDestroyedCallbacks = [];
    this.onThrowOutCallbacks = [];

    // Setting up the element
    if ($element !== null)
    {
      this.setupElement($element);
    }
    else
    {
      this.createElement();
    }
  }

  get isOverDropOffTreshold()
  {
    return Math.abs(this.location.x) > this.dropOffTreshold;
  }

  get location()
  {
    const x = parseInt(this.getTransformProperty('translateX'));
    const y = parseInt(this.getTransformProperty('translateY'));
    const z = parseInt(this.getTransformProperty('translateZ'));

    return {x, y, z};
  }

  get bTopDeck()
  {
    if (this.bDestroyed)
    {
      return false;
    }
    else
    {
      const siblings = Array.from(this.$element.parentElement.children).filter($element => !$element.classList.contains(markForDestroyClass));
      const i = siblings.indexOf(this.$element);
      return i === siblings.length - 1;
    }
  }


  // returns false if failed to grab or this if successful grab
  // grabber must be of type Mouse
  grab(grabber)
  {
    this.stopCurrentAnime();
    let bSuccess = false;
    const bGrabbable = this.bTopDeck;
    if (bGrabbable && this.grabbedBy == null)
    {
      bSuccess = true;
      this.grabbedBy = grabber;
      this.$element.classList.add('grabbed');

    }
    return bSuccess;
  }

  stopCurrentAnime()
  {
    anime.running.forEach(runner => {
      runner.remove(this.$element);
    });
  }

  drop()
  {
    if (this.grabbedBy)
    {
      this.$element.classList.remove('grabbed');
      this.grabbedBy = null;

      if (this.isOverDropOffTreshold)
      {
        this.throwOut();
      }
      else
      {
        // Return to origin
        const target = {x: this.origin.x, y: this.origin.y};
        anime({
          targets: this.$element,
          duration: 400,
          translateX: target.x,
          translateY: target.y,
          rotate: 0,
          easing: 'spring(0.5, 100, 5, 15)'
        });
      }
    }
  }

  drag(offset)
  {


    offset = {x: - offset.x, y: - offset.y};
    this.translate(offset);

    // Rotate here
    if (this.isOverDropOffTreshold)
    {
      this.$element.classList.add(overDropOffClass);
    }
    else
    {
      this.$element.classList.remove(overDropOffClass);
    }
  }

  // internal use only
  throwOut()
  {
    this.bGrabbable = false;

    // 1. Which side?
    const side = this.location.x > 0 ? 'right' : 'left';

    // 2. Submit form with answer (as <input>)
    const answers = [];
    answers['right'] = 'include';
    answers['up'] = 'skip';
    answers['left'] = 'exclude';
    const $cardForm = this.$element.querySelector('form');
    if ($cardForm)
    {
      $cardForm.querySelectorAll('input').forEach($input => {if ($input.getAttribute('name') === 'answer') $input.setAttribute('value', answers['side']);});
      $cardForm.requestSubmit();
    }


    //this.onThrowOutCallbacks.forEach(func => func(this, answers[side]));

    // 3. Animate card flying to the side
    const targets = [];
    targets['right'] = {x: 1000, y: 0};
    targets['left'] = {x: - 1000, y: 0};
    const target = targets[side];
    const thisCard = this;
    anime({
      targets: this.$element,
      duration: 150,
      translateX: target.x,
      translateY: target.y,
      easing: 'linear',
      complete: function(anim) {
        thisCard.destroy();
      }
    });
  }

  addSubmitListener(func)
  {
    const $form = this.$element.querySelector('form');
    if ($form)
    {
      $form.addEventListener('submit', func);
    }
    else
    {
      console.log('Warning: no add event listener added for the card marked red');
      this.$element.style.backgroundColor = 'red';
    }
  }

  // signature: func(card, answerString)
  addOnThrowOutCallback (func)
  {
    this.onThrowOutCallbacks.push(func);
  }

  // func(card)
  addOnDetroyedCallback (func)
  {
    this.onDestroyedCallbacks.push(func);
  }

  destroy()
  {
    this.$element.classList.add(markForDestroyClass);
    this.bDestroyed = true;
    this.onDestroyedCallbacks.forEach(func => func(this));
    this.$element.parentElement.removeChild(this.$element);
  }

  createElement()
  {
    const $element = document.createElement('div');
    const $futureParent = document.querySelector('.card-stack');
    // TODO add form element to this.$element
    //this.$element.innerHTML = `<form action="index`

    $element.classList.add('card', 'question-card');
    $futureParent.appendChild($element);
    this.setupElement($element, $futureParent);

  }

  setupElement($element, $futureParent = null)
  {
    this.$element = $element;
    const $parent = $futureParent === null ? $element.parentElement : $futureParent;
    $parent.addEventListener('DOMNodeInserted', e => this.onSiblingsUpdate());
    $parent.addEventListener('DOMNodeRemoved', e => this.onSiblingsUpdate());

    const $form = this.$element.querySelector('form');
    if ($form)
    {
      $form.addEventListener('submit', (e) =>
      {
        e.preventDefault();
        return false;
      });
    }

    this.$element.style.transform = 'translateX(0px) translateY(0px) translateZ(0px) scale(1) rotate(0deg)'; // making sure this.setTransformProperty will always work
    this.updateOrigin();
  }

  onSiblingsUpdate(event)
  {
    if (this.bTopDeck)
    {
      this.$element.classList.add('card--top-deck');
    }
    else
    {
      this.$element.classList.remove('card--top-deck');
    }

    this.updateOrigin(event);
  }

  updateOrigin()
  {
    if (this.bDestroyed)
    {
      return;
    }

    let siblings = this.$element.parentNode.children;
    siblings = Array.from(siblings).filter($element => !$element.classList.contains(markForDestroyClass)); // filter out elements marked for destroy
    const i = siblings.length - 1 - siblings.indexOf(this.$element);

    this.origin.y = i * this.stackOffsetPx.y;
    this.origin.z = i * this.stackOffsetPx.z;

    anime({
      targets: this.$element,
      translateX: this.origin.x,
      translateY: this.origin.y,
      translateZ: this.origin.z,
      duration: 1000,
      easing: 'spring(0.5, 50, 2, 20)'
    });
  }

  translate(offset)
  {
    const location = this.location;
    this.setTransformStyle(`${location.x + offset.x}px`, `${location.y + offset.y}px`, false);

    const sign = Math.sign(location.x);
    const newAngle = sign * map(0, 300, 0, 30, Math.abs(location.x), true);
    this.setTransformProperty('rotate', `${newAngle}deg`);
  }

  setTransformStyle(x = false, y = false, z = false)
  {
    const arr = [];
    const makeObj = (value, toReplace) => {return {bSet: value !== false, value, toReplace};};
    arr.push(makeObj(x, 'translateX'));
    arr.push(makeObj(y, 'translateY'));
    arr.push(makeObj(z, 'translateZ'));

    arr.forEach(element =>
    {
      if (element.bSet)
      {
        this.setTransformProperty(element.toReplace, element.value);
      }
    });
  }

  // newValue must be string with unit
  setTransformProperty(propertyName, newValue)
  {
    const toInsert = `${propertyName}(${newValue})`;

    let style = this.$element.style.transform;
    const toReplaceStart = style.indexOf(`${propertyName}(`);

    if (toReplaceStart === -1)
    {
      style += toInsert;
    }
    else
    {
      let toReplaceEnd = style.indexOf(' ', toReplaceStart);
      toReplaceEnd = toReplaceEnd === - 1 ? style.length : (toReplaceEnd); // if no more ' ' found. end should be last index of string.
      const toReplace = style.slice(toReplaceStart, toReplaceEnd);
      style = style.replace(toReplace, toInsert);
    }

    this.$element.style.transform = style;
  }

  getTransformProperty(property)
  {
    const origin = this.$element.style.transform.indexOf(property);
    const from = this.$element.style.transform.indexOf('(', origin) + 1;
    const to = this.$element.style.transform.indexOf('px', from);
    const value = this.$element.style.transform.slice(from, to);
    return value;
  }



}
