import Mouse from './mouse.js';

export default class CardsInteractionManager {
  // This class makes cards interactable with mouse and is responsible for clean up
  constructor()
  {
    this.cards = [];
    this.mouse = new Mouse(this.cards);
  }

  registerCard(card)
  {

    //card.addSubmitListener(event => handleCardAnswer(event, card));
    if ('addOnDetroyedCallback' in card)
    {
      card.addOnDetroyedCallback(card => this.onCardDestroyed(card)); // bind "onCardDestroyed" to this
      this.cards.push(card);
      return true;
    }
    return false;
  }

  onCardDestroyed(destroyedCard)
  {
    const id = this.cards.findIndex(card => destroyedCard.$element === card.$element);
    this.cards.splice(id, 1);
  }
}
