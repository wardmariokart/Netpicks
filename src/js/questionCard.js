import {Card} from './card.js';

export class QuestionCard extends Card {
  constructor($element)
  {
    super();
    this.linkWithElement($element);
    this.answers.push({answer: 'exclude', evaluateFunc: () => this.location.x < - 300, throwTarget: {x: - 1000, y: 0}});
    this.answers.push({answer: 'include', evaluateFunc: () => this.location.x > 300, throwTarget: {x: 1000, y: 0}});
    this.answers.push({answer: 'skip', evaluateFunc: () => this.location.y < - 300, throwTarget: {x: 0, y: - 1000}});
  }
}
