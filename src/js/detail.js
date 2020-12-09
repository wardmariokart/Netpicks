
import CardsInteractionManager from './cardsInteractionManager.js';
import {QuestionCard} from './questionCard.js';
import {ProposedMovieCard} from './proposedMovieCard.js';
import {NoMoviesFoundCard} from './noMoviesFoundCard.js';
import {formDataToJson, postToPHP} from './helpers.js';
import anime from './lib/anime.es.js';

let manager = null;

export const setupDetailPage = () =>
{
  // Abort if not on detail page
  const bCorrectPage = document.querySelector('.page--detail') !== null;
  if (!bCorrectPage)
  {
    return;
  }
  setupCopyInvite();
  setOverlayHidden(true, true);

  manager = new CardsInteractionManager();


  const settings = document.querySelectorAll('.setting--owner');
  settings.forEach($settingForm =>
  {
    $settingForm.addEventListener('click', e => e.currentTarget.requestSubmit());
    $settingForm.addEventListener('submit', handleCardSubmit);
  });
};


const setupCopyInvite = () =>
{
  const $copyButton = document.querySelector('.invite-link__button');
  if (!$copyButton)
  {
    return;
  }

  $copyButton.addEventListener('click', copyInviteLink);

  const $urlField = document.querySelector('.invite-link__url');
  const url = window.location.href.split('?')[0];
  $urlField.value = `${url}${$urlField.value}`;
};

const copyInviteLink = () =>
{
  const $url = document.querySelector('.invite-link__url');
  $url.select();
  console.log($url);
  document.execCommand('copy');
  console.log('copy attempt');
  alert(`Invite link copied! 😁\n→ ${$url.value} `);

};

const setOverlayHidden = (bHidden, bInstant = false) =>
{
  const $overlay = document.querySelector('.update-overlay__background');

  const opacities = {hidden: 0, visible: 0.2};

  const animateObj = {
    targets: $overlay,
    opacity: bHidden ? opacities.hidden : opacities.visible,
    easing: 'easeInCubic',
    duration: 125 * bInstant ? 0 : 1
  };

  if (bHidden)
  {
    const completeHide = anim =>
    {
      $overlay.parentElement.classList.add('hidden');
    };
    animateObj.complete = completeHide;
  }
  else
  {
    $overlay.parentElement.classList.remove('hidden');
  }
  anime(animateObj);
};

const handleCardSubmit = async event =>
{
  event.preventDefault();
  const $form = event.currentTarget;
  const formData = formDataToJson($form);
  const url = $form.getAttribute('action');
  const phpResponse = await postToPHP(formData, url);

  if ('showQuestion' in phpResponse)
  {
    setOverlayHidden(false);
    const info = phpResponse['showQuestion'];
    const constructObj = {
      questionInfo: {
        displayQuestion: info['display_question'],
        filterCategoryId: info['filter_category_id'],
        answerId: info['answerId'],
        questionId: info['id']
      }
    };
    const card = new QuestionCard(constructObj);
    manager.registerCard(card);
    card.addSubmitListener(handleCardSubmit);
  }

  if ('noMoviesFound' in phpResponse)
  {
    const noMoviesFoundCard = new NoMoviesFoundCard();
    manager.registerCard(noMoviesFoundCard);
    noMoviesFoundCard.addSubmitListener(handleCardSubmit);
  }

  if ('proposeMovie' in phpResponse)
  {
    const proposeCard = new ProposedMovieCard(phpResponse.proposeMovie);
    proposeCard.addSubmitListener(handleCardSubmit);
    manager.registerCard(proposeCard);
  }

  if ('redirect' in phpResponse)
  {
    const currentUrl = window.location.href;
    const noQueryString = currentUrl.slice(0, currentUrl.indexOf('?'));
    const url = `${noQueryString}${phpResponse['redirect']['url']}`;
    window.location.replace(url);
  }

  if ('movieUpdated' in phpResponse)
  {
    window.location.reload();
  }
};
