<section class="divider">
  <h2 class="hidden">First Step</h2>
  <span>I'm planning a movie night for <b><?php echo $stepOne['nightType']['display']?></b>.</span>
  <span>During that night we want to <b><?php echo $stepOne['movieOptionOne']['display']?></b></span>
  <span>but <b><?php echo $stepOne['movieOptionTwo']['display']?></b> is also something we want to do.</span>
</section>

<section class="filtered center-align-margin divider">
  <h2 class="hidden">Filtered movies</h2>
  <div class="filtered__overview">
    <?php for($i = 0; $i < 56; $i++):?>
    <div class="filtered__movie"></div>
    <?php endfor;?>
  </div>
  <span><b class="filtered__movies-left"><?php echo $nbMoviesFound; ?></b> Possible movies left!</span>
  <span>Answer below to specify your pick</span>
</section>

<section>
  <h2 class="hidden">Specify questions</h2>
  <ul class="cards-wrapper">
    <li>
      <article class="question-card">
          <h3 class="question__title">Do you like supernatural horror?</h3>
          <form class="question__form" action="index.php?page=extraQuestions<?php echo '&nightType=' . $_GET['nightType'] . '&movieOptionOne=' . $_GET['movieOptionOne'] . '&movieOptionTwo=' . $_GET['movieOptionTwo']?>" method="post">
            <input type="hidden" name="action" value="filter">
            <input type="hidden" name="filterType" value="supernatural">
            <input class="question__radio" type="radio" name="filterSupernatural" value="false" id="filter_supernatural_false" required>
            <label class="question__label" for="filter_supernatural_false">No 😒</label>

            <input class="question__radio" type="radio" name="filterSupernatural" value="skip" id="filter_supernatural_skip" required>
            <label class="question__label" for="filter_supernatural_skip">Doesn't matter 🤷</label>

            <input class="question__radio" type="radio" name="filterSupernatural" value="true" id="filter_supernatural_true" required>
            <label class="question__label" for="filter_supernatural_true">Yes 😨</label>

            <input class="question__next" type="submit" value="next question >">
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
    </li>

    <li>
      <article class="question-card question-card--inactive">
          <h3 class="question__title">Are you a gore or psychological-person?</h3>
          <form class="question__form" action="index.php" method="post">
            <input type="hidden" name="action" value="filter">
            <input type="hidden" name="filterType" value="gorePsychological">
            <input class="question__radio" type="radio" name="filterGorePsychological" value="gore" id="filter_gore_psychological_false" required>
            <label class="question__label" for="filter_gore_psychological_false">Gore 😒</label>

            <input class="question__radio" type="radio" name="filterGorePsychological" value="skip" id="filter_gore_psychological_skip" required>
            <label class="question__label" for="filter_gore_psychological_skip">Doesn't matter 🤷</label>

            <input class="question__radio" type="radio" name="filterGorePsychological" value="true" id="filter_gore_psychological_psychological" required>
            <label class="question__label" for="filter_gore_psychological_psychological">Psychological 😨</label>
          </form>

          <div class="question__step-wrapper">
            <?php $currentStep = 1; $totalSteps = 5;?>
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
    </li>

   <!--  <li>
      <article class="question-card">
        <span class="picked__title-like">Our pick:</span>
        <img class="picked__img" src="http://image.tmdb.org/t/p/w342/gycdE1ARByGQcK4fYR2mgpU6OO.jpg" alt="movie picture">
        <h3 class="question__title">Movie title</h3>
        <div class="picked__buttons">

          <form action="index.php?page=extraQuestions<?php echo '&nightType=' . $_GET['nightType'] . '&movieOptionOne=' . $_GET['movieOptionOne'] . '&movieOptionTwo=' . $_GET['movieOptionTwo']?>" method="post">
            <input type="hidden" name="action" value="declinePick">
            <input class="picked__button picked__button--redo" type="submit" value="Already seen ♻️">
          </form>

          <form action="index.php?page=extraQuestions<?php echo '&nightType=' . $_GET['nightType'] . '&movieOptionOne=' . $_GET['movieOptionOne'] . '&movieOptionTwo=' . $_GET['movieOptionTwo']?>" method="post">
            <input type="hidden" name="action" value="confirmPick">
            <input type="hidden" name="pickedId" value="856">
            <input class="picked__button picked__button--next" type="submit" value="Plan my night! >">
          </form>
        </div>


      </article>
    </li> -->
  </ul>
</section>


