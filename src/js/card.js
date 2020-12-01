import anime from './lib/anime.es.js';

export class Card {

  constructor()
  {
    this.stackOffsetPx = {y: 10, z: - 2};
    this.origin = {x: 0, y: 0, z: 0, scale: 0, rotation: 0};
    this.grabbedBy = null;
    this.createElement(document.querySelector('.card-stack'));
  }

  createElement($parent)
  {
    this.$element = document.createElement('div');
    $parent.addEventListener('DOMNodeInserted', e => this.updateOrigin(e));
    this.$element.style.transform = 'translateX(0px) translateY(0px) translateZ(0px) scale(1) rotate(0deg)';

    this.$element.classList.add('card', 'question-card');
    $parent.appendChild(this.$element);

  }

  updateOrigin(event)
  {

    const siblings = this.$element.parentNode.children;
    const i = siblings.length - Array.prototype.indexOf.call(siblings, this.$element);


    this.origin.y = i * this.stackOffsetPx.y;
    this.origin.z = i * this.stackOffsetPx.z;
    this.setTransformStyle(false, `${this.origin.y}px`, `${this.origin.z}px`);

    /* this.$element.style.transform += `translateY(${this.origin.y}rem) translateZ(${this.origin.z})`; */
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

  get bTopDeck()
  {
    return this.$element.parentElement.lastChild === this.$element;
  }

  getTransformProperty(property)
  {
    const origin = this.$element.style.transform.indexOf(property);
    const from = this.$element.style.transform.indexOf('(', origin) + 1;
    const to = this.$element.style.transform.indexOf('px', from);
    const value = this.$element.style.transform.slice(from, to);
    return value;
  }


  get location()
  {
    const x = parseInt(this.getTransformProperty('translateX'));
    const y = parseInt(this.getTransformProperty('translateY'));
    const z = parseInt(this.getTransformProperty('translateZ'));

    return {x, y, z};
  }

  // returns false if failed to grab or this if successful grab
  // grabber must be of type Mouse
  grab(grabber)
  {
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

  drop()
  {
    if (this.grabbedBy)
    {
      this.$element.classList.remove('grabbed');
      this.grabbedBy = null;

      const target = {x: this.origin.x, y: this.origin.y};
      anime({
        targets: this.$element,
        duration: 400,
        translateX: target.x,
        translateY: target.y,
        rotate: 0,
        easing: 'easeOutCirc'
      });
    }
  }

  translate(offset)
  {
    const location = this.location;
    this.setTransformStyle(`${location.x + offset.x}px`, `${location.y + offset.y}px`, false);
  }

  drag(offset)
  {


    offset = {x: - offset.x, y: - offset.y};
    this.translate(offset);
    // Rotate here
  }
}
