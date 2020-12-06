import CardsInteractionManager from './cardsInteractionManager.js';
import {QuestionCard} from './questionCard.js';
import {ProposedMovieCard} from './proposedMovieCard.js';
import {formDataToJson, postToPHP} from './helpers.js';

let manager = null;

export const extraQuestionsInit = () =>
{
  // Abort if not on extra questions page
  const bCorrectPage = document.querySelector('.page--extra-questions') !== null;
  if (!bCorrectPage)
  {
    return;
  }

  manager = new CardsInteractionManager();

  createCardForExisitingElements();
};

const createCardForExisitingElements = () =>
{
  const $elements = document.querySelectorAll('.card--question');
  $elements.forEach($element =>
  {
    const constructObj = {$element};
    const card = new QuestionCard(constructObj);
    manager.registerCard(card);
    card.addSubmitListener(handleCardAnswer);
  });
};

const handleCardAnswer = async event =>
{
  event.preventDefault();

  const $form = event.currentTarget;
  const url = $form.getAttribute('action');
  const formData = formDataToJson(event.currentTarget);
  const phpResponse = await postToPHP(formData, url);

  if ('updateMoviesLeft' in phpResponse)
  {
    const $moviesLeft = document.querySelector('.filtered__movies-left');
    $moviesLeft.textContent = phpResponse['updateMoviesLeft'];
  }

  if ('proposeMovie' in phpResponse)
  {
    const movieCard = new ProposedMovieCard(phpResponse['proposeMovie']);
    manager.registerCard(movieCard);
    movieCard.addSubmitListener(handleCardAnswer);
  }

  if ('redirect' in phpResponse)
  {
    const currentUrl = window.location.href;
    const noQueryString = currentUrl.slice(0, currentUrl.indexOf('?'));
    const url = `${noQueryString}${phpResponse['redirect']['url']}`;
    window.location.replace(url);
  }
};


