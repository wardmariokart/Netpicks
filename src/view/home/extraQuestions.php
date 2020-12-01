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



<section class="card-stack">


</section>
<!--
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
  </ul>
</section> -->

<script src="./js/lib/anime.min.js"></script>


