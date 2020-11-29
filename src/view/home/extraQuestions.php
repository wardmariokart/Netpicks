<section class="divider">
  <h2 class="hidden">First Step</h2>
  <span>I'm planning a movie night for <b>*INSERT PHP*</b>.</span>
  <span>During that night we want to <b>*INSERT PHP*</b></span>
  <span>but <b>*INSERT PHP*</b> is also something we want to do.</span>
</section>

<section class="filtered center-align-margin divider">
  <h2 class="hidden">Filtered movies</h2>
  <div class="filtered__overview">
    <?php for($i = 0; $i < 56; $i++):?>
    <div class="filtered__movie"></div>
    <?php endfor;?>
  </div>
</section>

<!-- <section class="question-card center-align-margin">
  <h2>Question Card</h2>
  <span>Does the supernatural scare you?</span>
  <form class="question__form" method="POST">
    <input type="hidden" name="action" value="setupFinish">
    <input class="question__radio" type="radio" name="horrorOne" value="notSupernatural" id="not-supernatural" required>
    <label class="question__label" for="not-supernatural">No :)</label>

    <input class="question__radio" type="radio" name="horrorOne" value="supernatural" id="supernatural">
    <label class="question__label" for="supernatural">Yes :(</label>

    <input class="question__next" type="submit" value="Next question">
  </form>
</section> -->

<section class="questions-wrapper">
  <h2 class="hidden">Specify questions</h2>
  <article class="question-card">
      <h3 class="question__title">Do you like supernatural horror?</h3>
      <form class="question__form" action="index.php" method="GET">
        <input type="hidden" name="action" value="filter">

        <input class="question__radio" type="radio" name="filterSupernatural" value="false" id="filter_supernatural_false" required>
        <label class="question__label" for="filter_supernatural_false">No 😒</label>

        <input class="question__radio" type="radio" name="filterSupernatural" value="skip" id="filter_supernatural_skip" required>
        <label class="question__label" for="filter_supernatural_skip">Doesn't matter 🤷</label>

        <input class="question__radio" type="radio" name="filterSupernatural" value="true" id="filter_supernatural_true" required>
        <label class="question__label" for="filter_supernatural_true">Yes 😨</label>
      </form>

      <div class="question__step-wrapper">
        <?php $currentStep = 2; $totalSteps = 2;?>
        <?php
          $toEcho = '';
          for($i = 1; $i <= $totalSteps; $i++)
          {
            if($i == $currentStep)
            {
              $toEcho .= '<span class="step-icon step-icon--filled"></span>';
            }
            else
            {
              $toEcho .= '<span class="step-icon"></span>';
            }
          }
          echo $toEcho;
        ?>
      </div>
  </article>

</section>


<span>(this is temp) Total 'gore' and 'supernatural' movies in genre 'horror': <b><?php echo $nbMoviesFound; ?></b></span>
