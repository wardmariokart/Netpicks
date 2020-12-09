import CardsInteractionManager from './cardsInteractionManager.js';
import {QuestionCard} from './questionCard.js';
import {ProposedMovieCard} from './proposedMovieCard.js';
import {NoMoviesFoundCard} from './noMoviesFoundCard.js';
import {formDataToJson, postToPHP} from './helpers.js';
import anime from './lib/anime.es.js';

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
  console.log(phpResponse);

  const $moviesOverview = document.querySelector('.filtered__overview');

  const showNewMovies = newPosters =>
  {
    const max = 56;
    const height = 50;
    const method = 1;

    if (method === 0)
    {

      $moviesOverview.innerHTML = newPosters.map((poster, index) =>
      {
        if (index > max) return '';

        return `<img class="filtered__movie filtered__movie--flipped" src="http://image.tmdb.org/t/p/h${height}${poster['poster']}" alt="movie poster">`;
      }).join('');
    }
    else if (method === 1)
    {
      for (let i = 0;i < max;i ++)
      {
        const poster = newPosters[i];
        if (!poster) return;
        const $img = document.createElement('img');
        $img.classList.add('filtered__movie', 'filtered__movie--flipped');
        $img.setAttribute('src', `http://image.tmdb.org/t/p/h${height}${poster['poster']}`);
        $img.setAttribute('alt', 'movie poster');
        $img.addEventListener('load', e => handleOnImageLoaded(e, false));
        $img.addEventListener('error', e => handleOnImageLoaded(e, true));
        $moviesOverview.appendChild($img);
      }
    }
  };

  anime({
    targets: '.filtered__overview .filtered__movie',
    duration: 300,
    rotateY: 90,
    easing: 'easeInCubic',
    delay: anime.stagger(10),
    complete: function(anim) {
      $moviesOverview.innerHTML = '';
      showNewMovies(phpResponse['updateMoviesLeft']['posters']);
    }
  });
};


const handleOnImageLoaded = (event, bError) =>
{
  const $img = event.currentTarget;
  if (bError)
  {
    $img.parentElement.removeChild($img);
  }
  else
  {

    anime({
      targets: $img,
      rotateY: 90,
      duration: 0,
      easing: 'easeInExpo'
    });

    anime({
      targets: $img,
      rotateY: 0,
      duration: 500,
      delay: anime.random(0, 300),
      easing: 'easeInExpo'
    });
  }
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

