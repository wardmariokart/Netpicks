import {Card} from './card.js';

export default class Mouse {
  constructor(victims)
  {
    this.victims = victims;
    this.location = {x: 0, y: 0};
    this.bTouchMode = false;
    this.grabbedCard = null;
    window.addEventListener('mousedown', e => this.handleGrabInput(e, 'mouse'));
    window.addEventListener('touchstart', e => this.handleGrabInput(e, 'touch'));

    window.addEventListener('mouseup', e => this.handleDropInput(e, 'mouse'));
    window.addEventListener('touchend', e => this.handleDropInput(e, 'touch'));
    window.addEventListener('touchcancel', e => this.handleDropInput(e, 'touch'));

    window.addEventListener('mousemove', e => this.handleMoveInput(e, 'mosue'));
    window.addEventListener('touchmove', e => this.handleMoveInput(e, 'touch'));
  }

  handleGrabInput (event, type)
  {
    if (type === 'touch')
    {
      this.bTouchMode = true;
    }
    if (this.bTouchMode && type === 'mouse')
    {
      return;
    }

    const $target = event.target;
    const victim = this.victims.find(victim => victim.$element === $target);

    if (victim !== undefined && victim.grab(this))
    {
      this.grabbedCard = victim;
      console.log(`grab by ${type}`);
    }
  }

  handleDropInput (event, type)
  {
    if (this.bTouchMode && type === 'mouse')
    {
      return;
    }

    if (this.grabbedCard !== null)
    {
      this.grabbedCard.drop();
      this.grabbedCard = null;
    }

    console.log(`drop by ${type}`);
  }


  handleMoveInput (event, type)
  {
    const newLocation = {x: event.clientX, y: event.clientY};
    const offset = {x: this.location.x - newLocation.x, y: this.location.y - newLocation.y};
    this.location = newLocation;

    if (this.bTouchMode && type === 'mouse')
    {
      return;
    }

    if (this.grabbedCard !== null)
    {
      this.grabbedCard.drag(offset);
    }
  }
}
