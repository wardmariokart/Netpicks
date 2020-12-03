
<section class="plan-one">
  <h2 class="hidden">Movie night step one</h2>

  <form action="index.php" method="GET" class="plan-one__form">
    <input type="hidden" name="action" value="planMovieNight">

    <div class="plan-one__questions">
      <div class="plan-one__block">
        <span>I'm planning a movie night for </span>
        <select name="nightType" id="nightType" class="plan-one__drop-down">
          <?php foreach($nightTypes as $night): ?>
            <option value="<?php echo $night['value'];?>"><?php echo $night['display'] ?></opton>
          <? endforeach; ?>
        </select><span>.</span>
      </div>

      <div class="plan-one__block">
        <span>During the movie we want to be </span>
        <select name="movieOptionOne" id="movieOptionOne" class="plan-one__drop-down">
          <?php foreach($stepOneOptions as $moveOption): ?>
            <option value="<?php echo $moveOption['value'];?>"><?php echo $moveOption['display'] ?></opton>
          <? endforeach; ?>
        </select>
        <span>,</span>
      </div>


      <div class="plan-one__block">
        <span>but </span>
        <select name="movieOptionTwo" id="movieOptionTwo" class="plan-one__drop-down">
          <?php foreach($stepOneOptions as $moveOption): ?>
            <option value="<?php echo $moveOption['value'];?>"><?php echo $moveOption['display'] ?></opton>
          <? endforeach; ?>
        </select>
        <span> is something we can't do without.</span>
      </div>
    </div>
    <div class="plan-one__submit-wrapper">
      <input class="plan-one__submit" type="submit" value="Plan my movie night!!">
    </div>
  </form>
</section>


<?php if (!empty($_SESSION['user'])): ?>
  <section class="movie-nights">
    <h2>My movie nights</h2>
    <?php if (!empty($myMovieNights)): ?>
      <?php foreach($myMovieNights as $movieNight): ?>
        <a class="movie-nights__item" href="index.php?page=detail&id=<?php echo $movieNight['id'];?>">
          <article>
            <h3><?php echo $movieNight['name']?></h3>
            <span>Next <b>tuesday</b> your are wachting <b>IT</b> with <b>the boys 🍻</b></span>
          </article>
        </a>
      <?php endforeach;?>
    <?php else: echo '<span>No movie nights planned yet</span>'; ?>
    <?php endif; ?>
  </section>
<?php endif; ?>
