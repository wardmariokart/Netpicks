
import CardsInteractionManager from './cardsInteractionManager.js';
import {QuestionCard} from './questionCard.js';
import {ProposedMovieCard} from './proposedMovieCard.js';
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

  manager = new CardsInteractionManager();

  setOverlayHidden(true, true);

  const settings = document.querySelectorAll('.setting');
  settings.forEach($settingForm =>
  {
    $settingForm.addEventListener('click', e => e.currentTarget.requestSubmit());
    $settingForm.addEventListener('submit', handleSettingSubmit);
  });
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

const handleSettingSubmit = async event =>
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
    console.log({phpResponse});
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
    card.addSubmitListener(handleSubmitCard);
  }
};

const handleSubmitCard = async event =>
{
  event.preventDefault();
  const $form = event.currentTarget;
  const url = $form.getAttribute('action');
  const formData = formDataToJson($form);
  const phpResponse = await postToPHP(formData, url);


  if ('proposeMovie' in phpResponse)
  {
    const proposeCard = new ProposedMovieCard(phpResponse.proposeMovie);
    proposeCard.addSubmitListener(handleSubmitCard);
    manager.registerCard(proposeCard);
  }

  if ('movieUpdated' in phpResponse)
  {
    window.location.reload();
    console.log('movie Updated');
  }

};

