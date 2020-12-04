import {Card} from './card.js';
import anime from './lib/anime.es.js';

export class ProposedMovieCard extends Card {
  constructor(movieInfo)
  {
    super(); // always make a new element
    this.movieInfo = movieInfo;
    this.answers.push({answer: 'reject', evaluateFunc: () => this.location.x < - 300, throwTarget: {x: - 1000, y: 0}});
    this.answers.push({answer: 'accept', evaluateFunc: () => this.location.x > 300, throwTarget: {x: 1000, y: 0}});

    this.linkWithElement(null);
  }

  createElement()
  {
    const $element = document.createElement('article');
    $element.classList.add('card', 'card--movie');

    const queryString = window.location.search;
    $element.innerHTML = `
    <h3 class="card__title">${this.movieInfo['title']}</h3>
    <img class="movie-card__poster" src="https://m.media-amazon.com/images/M/MV5BZWFlYmY2MGEtZjVkYS00YzU4LTg0YjQtYzY1ZGE3NTA5NGQxXkEyXkFqcGdeQXVyMTQxNzMzNDI@._V1_UY1200_CR80,0,630,1200_AL_.jpg" alt="Poster of movie">
    <form method="POST" action="index.php${queryString}">
      <input type="hidden" name="action" value="proposeResponse">
      <input type="hidden" name="answer" value="you didnt update this in js...">
    </form> `;

    const $futureParent = document.querySelector('.card-stack');
    $futureParent.append($element);
    this.setupElement($element, $futureParent);
  }
}
