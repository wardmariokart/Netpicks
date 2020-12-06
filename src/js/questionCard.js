import {Card} from './card.js';

export class QuestionCard extends Card {
  constructor(constructObj)
  {
    super();
    if ('$element' in constructObj)
    {
      this.linkWithElement(constructObj.$element);
    }
    else
    {
      if ('questionInfo' in constructObj)
      {
        this.questionInfo = constructObj.questionInfo;
      }
      this.linkWithElement(null);
    }
    this.answers.push({answer: 'exclude', evaluateFunc: () => this.location.x < - 300, throwTarget: {x: - 1000, y: 0}});
    this.answers.push({answer: 'include', evaluateFunc: () => this.location.x > 300, throwTarget: {x: 1000, y: 0}});
    this.answers.push({answer: 'skip', evaluateFunc: () => this.location.y < - 300, throwTarget: {x: 0, y: - 1000}});
  }

  createElement()
  {
    const $element = document.createElement('article');
    $element.classList.add('card', 'card--question');

    if ('questionInfo' in this)
    {
      this.buildElementWithInfo($element, this.questionInfo);
      if ($element.querySelector('form') === undefined)
      {
        console.log('FIX: $element not passed as reference');
      }
    }

    const $futureParent = document.querySelector('.card-stack');
    $futureParent.append($element);
    this.setupElement($element, $futureParent);
  }

  buildElementWithInfo($element, questionInfo)
  {
    const queryString = window.location.search;
    $element.innerHTML = `
    <h3>${questionInfo.displayQuestion}</h3>
    <form action="index.php${queryString}" method="POST">
      <input type="hidden" name="action" value="filter">
      <input type="hidden" name="filterType" value="${questionInfo.filterCategoryId}">
      <input type="hidden" name="answerId" value="${questionInfo.answerId}">
      <input type="hidden" name="answer" value="you didnt update this in js...">
      <input type="hidden" name="questionId" value="${questionInfo.questionId}">
    </form>`;
  }

}
