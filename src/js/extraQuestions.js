
import {Card} from './card.js';
import Mouse from './mouse.js';

const cards = [];
let mouse = null;

export const extraQuestionsInit = () =>
{
  const nbCards = 5;
  mouse = new Mouse(cards);

  for (let i = 0;i < nbCards;i++)
  {
    cards.push(new Card());
  }


  //cards[nbCards - 1].$element.style.transform += `translateZ(${4 * 0.5}rem)`;
  //cards[nbCards - 1].$element.style.transform += `translateY(${4 * 0.5}rem)`;


  cards.forEach(card =>
  {
    if (card.bTopDeck)
    {
      card.$element.classList.add('top-deck');
    }
  });



};
