import {Card} from './card.js';
import Mouse from './mouse.js';
import {formDataToJson, postToPHP} from './helpers.js';

const cards = [];
let mouse = null;

const createCardForExisitingElements = () =>
{
  const $elements = document.querySelectorAll('.card');
  $elements.forEach($element => createNewCard($element));
};

const createNewCard = ($element) =>
{
  const card = new Card($element);
  card.addOnDetroyedCallback(onCardDestroyed);
  cards.push(card);
};

const onCardDestroyed = inCard =>
{
  const id = cards.findIndex(card => card.$element === inCard.$element);
  cards.splice(id, 1);
};

const handleCardAnswer = async (event, card) =>
{
  event.preventDefault();

  // Send request to php
  const $form = event.currentTarget;
  const url = $form.getAttribute('action');
  const formData = formDataToJson(event.currentTarget);
  //console.log({formData});
  const phpResponse = await postToPHP(formData, url);
  console.log(phpResponse);

  if ('updateMoviesLeft' in phpResponse)
  {
    console.log('update movies left');
    const $moviesLeft = document.querySelector('.filtered__movies-left');
    $moviesLeft.textContent = phpResponse['updateMoviesLeft'];
  }

  if ('proposeMovie' in phpResponse)
  {
    console.log(`This movie was proposed: ${phpResponse['proposeMovie']['title']}`);
    // Create new movie pick card
    createNewCard(null);
  }
};

export const extraQuestionsInit = () =>
{
  mouse = new Mouse(cards);
  createCardForExisitingElements();

  cards.forEach(card => card.addSubmitListener(event => handleCardAnswer(event, card)));
};
