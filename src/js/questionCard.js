import {Card} from './card.js';
import {setInputValueByName} from './helpers.js';

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

    this.registerAnswer('exclude', () => this.location.x < - 250, {x: - 1000, y: 0}, 'left', 'no, thank you');
    this.registerAnswer('include', () => this.location.x > 250, {x: 1000, y: 0}, 'right', 'yes, please');
    this.registerAnswer('skip', () => this.location.y < - 250, {x: 0, y: - 1000}, 'up', 'skip question');
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

  throwOut(triggeredAnswer, bSubmit = true, bByComputer = false)
  {
    this.updateQuestionsLeftInput();
    super.throwOut(triggeredAnswer, bSubmit, bByComputer);
  }

  updateQuestionsLeftInput()
  {
    const $cardForm = this.$element.querySelector('form');
    if ($cardForm)
    {
      setInputValueByName($cardForm, 'nbQuestionsLeft', this.$element.parentElement.querySelectorAll('.card').length - 1); // -1 because don't count yourself


      // get all other cards under same parent
      let otherQuestions = this.$element.parentElement.querySelectorAll('.card--question');
      otherQuestions = Array.from(otherQuestions).filter($question => $question !== this.$element);
      const otherQuestionIds = [];
      otherQuestions.forEach($question => $question.querySelectorAll('input').forEach($input => {if ($input.getAttribute('name') === 'questionId') otherQuestionIds.push($input.getAttribute('value'));}));
      setInputValueByName($cardForm, 'questionsLeft', otherQuestionIds.join(','));
    }
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
      <input type="hidden" name="questionsLeft" value"you didn't update this in js...">
    </form>`;
  }

}
