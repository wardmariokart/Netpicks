import CardsInteractionManager from './cardsInteractionManager.js';
import {QuestionCard} from './questionCard.js';
import {ProposedMovieCard} from './proposedMovieCard.js';
import {NoMoviesFoundCard} from './noMoviesFoundCard.js';
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


 /*  setTimeout(() => {
    const card = manager.cards[0];
    manager.cards[4].throwOut(card.answers[1], true, true);
  }, 1000); */
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
    handlePhpUpdateMoviesLeft(phpResponse);
  }

  if ('proposeMovie' in phpResponse)
  {
    handlePhpProposeMovie(phpResponse);
  }

  if ('noMoviesFound' in phpResponse)
  {
    handlePhpNoMoviesFound(phpResponse);
  }

  if ('redirect' in phpResponse)
  {
    handlePhpRedirect(phpResponse);
  }
};

const handlePhpUpdateMoviesLeft = phpResponse =>
{
  const $moviesLeft = document.querySelector('.filtered__movies-left');
  $moviesLeft.textContent = phpResponse['updateMoviesLeft']['count'];
};

const handlePhpProposeMovie = phpResponse =>
{
  const movieCard = new ProposedMovieCard(phpResponse['proposeMovie']);
  manager.registerCard(movieCard);
  movieCard.addSubmitListener(handleCardAnswer);
};

const handlePhpNoMoviesFound = phpResponse =>
{
  phpResponse;
  const autoThrowDelay = 350;

  const createNoMoviesFoundCard = () =>
  {
    const noMoviesFoundCard = new NoMoviesFoundCard();
    manager.registerCard(noMoviesFoundCard);
    noMoviesFoundCard.addSubmitListener(handleCardAnswer);
  };


  // Throw away all other cards => don't answer
  const cardsToThrowOut = manager.cards;


  setTimeout(() => {
    createNoMoviesFoundCard();
  }, autoThrowDelay * (cardsToThrowOut.length + 1));


  cardsToThrowOut.forEach((card, index, array) =>
  {
    console.log('throw');
    if (card.$element.classList.contains('card--question'))
    {
      setTimeout(() => {
        card.throwOut(card.answers[2], false, true);
      }, autoThrowDelay * (array.length - 1 - index));
    }
  });


};

const handlePhpRedirect = phpResponse =>
{
  const currentUrl = window.location.href;
  const noQueryString = currentUrl.slice(0, currentUrl.indexOf('?'));
  const url = `${noQueryString}${phpResponse['redirect']['url']}`;
  window.location.replace(url);
};

