<section class="divider page--extra-questions">
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
  <span><b class="filtered__movies-left"><?php echo isset($nbMoviesFound) ? $nbMoviesFound : '0'; ?></b> Possible movies left!</span>
  <span>Answer below to specify your pick</span>
</section>



<section>
  <h2 class="hidden">Questions</h2>

  <div class="card-stack">
    <?php
    // reverse $questions because the first question should be inserted last in html
    $questions = array_reverse($questions);
    foreach($questions as $index => $question):
    ?>
    <?php $index = count($questions) - $index;?>
      <article class="card card--question">
        <span class="card__subtitle">Question <b><?php echo $question['id'];?></b></span>
        <h3><?php echo $question['display_question']?></h3>
        <form action="index.php?page=extraQuestions<?php echo '&nightType=' . $_GET['nightType'] . '&movieOptionOne=' . $_GET['movieOptionOne'] . '&movieOptionTwo=' . $_GET['movieOptionTwo']?>" method="post">
          <input type="hidden" name="action" value="filter">
          <input type="hidden" name="filterType" value="<?php echo $question['filter_category_id']; ?>">
          <input type="hidden" name="answer" value="you didnt update this in js...">
          <input type="hidden" name="questionNumber" value="<?php echo $index; ?>">
          <input type="hidden" name="questionId" value="<?php echo $question['id']?>">
          <input type="hidden" name="nbQuestionsLeft" value="you didnt update this in js..."> <!-- TODO replace "nbQuestionsLeft" with count(...) in controller.php -->
          <input type="hidden" name="questionsLeft" value="you didnt update this in js...">
        </form>
      </article>
    <?php endforeach; ?>
  </div>
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


