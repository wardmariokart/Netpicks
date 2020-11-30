/* const { EnvironmentPlugin } = require('webpack');
 */
require('./style.css');
{

  const formDataToJson = $form =>
  {
    const data = new FormData($form);
    const obj = {};
    data.forEach((value, key) =>
    {
      obj[key] = value;
    });
    return obj;
  };

  const handleAnswerQuestion = async event =>
  {
    event.preventDefault();
    // Get form inputs
    // filterType, answer

    // Send request to php
    const $form = event.currentTarget;
    const url = $form.getAttribute('action');
    const formData = formDataToJson(event.currentTarget);
    const phpResponse = await postToPHP(formData, url);
    console.log({phpResponse});



    if (phpResponse['type'] === 'confirm pick')
    {
      console.log('hit');
      Array.from(document.querySelectorAll('.question-card')).forEach($card => $card.parentElement.removeChild($card));
      insertPickedCardElement(url, phpResponse['data']['pickData']);

      updateMoviesLeft(phpResponse['data']['nbMoviesLeft']);
    }
  };

  const updateMoviesLeft = nbMoviesLeft =>
  {
    document.querySelector('.filtered__movies-left').textContent = nbMoviesLeft;
  };


  const postToPHP = async (formData, url) =>
  {
    const fetchResult = await fetch(url, {
      method: 'POST',
      headers: new Headers({
        'Content-Type': 'application/json'
      }),
      body: JSON.stringify(formData)
    });

    const jsonResult = await fetchResult.json();
    return jsonResult;
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

  const setupQuestionListener = () =>
  {
    const questions = document.querySelectorAll('.question-card');
    const activeQuestions = Array.from(questions).filter($card => !$card.classList.contains('question__form--inactive'));
    if (activeQuestions.length > 0)
    {
      const $questionForm = activeQuestions[0].querySelector('.question__form');
      $questionForm.addEventListener('submit', handleAnswerQuestion);
    }
  };

  const init = () => {
    setupQuestionListener();


  };

  init();
}
