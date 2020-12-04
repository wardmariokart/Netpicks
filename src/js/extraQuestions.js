//import {Card} from './card.js';
import {QuestionCard} from './questionCard.js';
import {ProposedMovieCard} from './proposedMovieCard.js';
import Mouse from './mouse.js';
import {formDataToJson, postToPHP} from './helpers.js';

const cards = [];
let mouse = null;

const createCardForExisitingElements = () =>
{
  const $elements = document.querySelectorAll('.card');


  $elements.forEach($element =>
  {
    const cardClassToType = [{className: 'card--question', type: 'question'}, {className: 'card--movie', type: 'proposedMovie'}];
    let cardType = '';
    cardClassToType.forEach(obj => {if ($element.classList.contains(obj.className)) cardType = obj.type;});

    const card = new QuestionCard($element);
    addCard(card);
  });
};

const addCard = card =>
{
  card.addSubmitListener(event => handleCardAnswer(event, card));
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
    const movieCard = new ProposedMovieCard(phpResponse['proposeMovie']);
    addCard(movieCard);
  }

  if ('redirect' in phpResponse)
  {
    const currentUrl = window.location.href;
    const noQueryString = currentUrl.slice(0, currentUrl.indexOf('?'));
    const url = `${noQueryString}${phpResponse['redirect']['url']}`;
    window.location.replace(url);
  }
};

export const extraQuestionsInit = () =>
{
  mouse = new Mouse(cards);
  createCardForExisitingElements();
};
