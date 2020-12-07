import {Card} from './card.js';

export class NoMoviesFoundCard extends Card {
  constructor()
  {
    super();
    this.answers.push({answer: 'tryAgain', evaluateFunc: () => this.location.x < - 300, throwTarget: {x: - 1000, y: 0}});
    this.answers.push({answer: 'closestMovie', evaluateFunc: () => this.location.x > 300, throwTarget: {x: 1000, y: 0}});

    this.linkWithElement(null);
  }

  createElement()
  {
    const $element = document.createElement('article');
    $element.classList.add('card', 'card--no-movie-found');

    const queryString = window.location.search;
    $element.innerHTML = `
    <h3 class="card__title">We couldn't find a movie that has everything you are looking for. What would you like to do next?</h3>
    <form method="POST" action="index.php${queryString}">
      <input type="hidden" name="action" value="noMovieFoundResponse">
      <input type="hidden" name="answer" value="you didn't update this in js...">
    </form>
    `;

    const $futureParent = document.querySelector('.card-stack');
    $futureParent.append($element);
    this.setupElement($element, $futureParent);
  }

}
