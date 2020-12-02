/* const { EnvironmentPlugin } = require('webpack');
 */
require('./style.css');
import {extraQuestionsInit} from './js/extraQuestions.js';

{


  const updateMoviesLeft = nbMoviesLeft =>
  {
    document.querySelector('.filtered__movies-left').textContent = nbMoviesLeft;
  };




  const insertPickedCardElement = (url, pickData) =>
  {
    // 1. Deactivate current active questions
    // 2. Dynamically insert confirm card
    // 3. Listen for submit
    const $confirmCard = document.createElement('article');
    $confirmCard.classList.add('question-card', 'picked-card');
    $confirmCard.innerHTML = `<span class="picked__title-like">Our pick:</span>
    <img class="picked__img" src="http://image.tmdb.org/t/p/w342/${pickData['poster']}" alt="${pickData['title']}">
    <h3 class="question__title">${pickData['title']}</h3>
    <div class="picked__buttons">
      <form class="picked__redo-form" action="${url}" method="post">
        <input type="hidden" name="action" value="pickOtherMovie">
        <input class="picked__button picked__button--redo" type="submit" value="Already seen ♻️">
      </form>

      <form class="picked__confirm-form" action="${url}" method="post">
        <input type="hidden" name="action" value="confirmPick">
        <input type="hidden" name="pickedId" value="${pickData['id']}">
        <input class="picked__button picked__button--next" type="submit" value="Plan my night! >">
      </form>
    </div>`;

    document.querySelector('.cards-wrapper').appendChild($confirmCard);

    // Event listeners
    $confirmCard.querySelector('.picked__redo-form').addEventListener('submit', handleRedoPick);
    $confirmCard.querySelector('.picked__confirm-form').addEventListener('submit', handleConfirmPick);
  };

  const handleRedoPick = event =>
  {
    event.preventDefault();
    // delete current picked card
    const $currentPickedCard = document.querySelector('.picked-card');
    $currentPickedCard.parentElement.removeChild($currentPickedCard);

    /* postToPHP()

    insertPickedCardElement(event.getAttribute('action'), ) */

    // insert new one with updated pick

  };

  const handleConfirmPick = async event =>
  {
    event;
    return;
    // No need to do this in js because we need a page refresh anyway.
  /*   event.preventDefault();

    const formData = formDataToJson(event.currentTarget);
    const url = event.currentTarget.getAttribute('action');
    console.log({formData, url});
    const result = await postToPHP(formData, url);

    console.log(result); */
  };

  // TODO create movie night
  // TOmorrow morning:



  const init = () => {
    extraQuestionsInit();
  };

  init();
}
