
import {Card} from './card.js';
import Mouse from './mouse.js';

const cards = [];
let mouse = null;


export const extraQuestionsInit = () =>
{
  const nbCards = 5;
  mouse = new Mouse(cards);


  const createNewCard = () =>
  {
    const card = new Card();
    card.addOnDetroyedCallback(onCardDestroyed);
    cards.push(card);
  };

  const onCardDestroyed = inCard =>
  {
    const id = cards.findIndex(card => card.$element === inCard.$element);
    cards.splice(id, 1);
    console.log(`Card removed from array. ${cards.length} left.`);

    if (cards.length === 0)
    {
      createNewCard();
    }
  };

  const handleCardAnswer = (card, answerString) =>
  {
    if (answerString === 'include')
    {
      
    }
    else if (answerString === 'exclude')
    {

    }

  };


  for (let i = 0;i < nbCards;i++)
  {
    createNewCard();
  }


  //cards[nbCards - 1].$element.style.transform += `translateZ(${4 * 0.5}rem)`;
  //cards[nbCards - 1].$element.style.transform += `translateY(${4 * 0.5}rem)`;



};

