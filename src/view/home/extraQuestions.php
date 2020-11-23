<section>
  <h2 class="hidden">First Step</h2>
  <span>I'm planning a movie night for <b>*INSERT PHP*</b>.</span>
  <span>During that night we want to <b>*INSERT PHP*</b></span>
  <span>but <b>*INSERT PHP*</b> is also something we want to do.</span>
</section>

<section class="filtered center-align-margin">
  <h2>Filtered movies</h2>
  <div class="filtered__overview">
    <?php for($i = 0; $i < 30; $i++):?>
    <div class="filtered__movie">🎥</div>
    <?php endfor;?>
  </div>
</section>

<section class="question-card center-align-margin">
  <h2>Question Card</h2>
  <span>Does supernatural movies scare you?</span>
  <form class="question__form" method="POST">
    <input type="hidden" name="action" value="setupFinish">
    <input class="question__radio" type="radio" name="horrorOne" value="notSupernatural" id="not-supernatural" required>
    <label class="question__label" for="not-supernatural">No :)</label>

    <input class="question__radio" type="radio" name="horrorOne" value="supernatural" id="supernatural">
    <label class="question__label" for="supernatural">Yes :(</label>

    <input class="question__next" type="submit" value="Next question">
  </form>


</section>
