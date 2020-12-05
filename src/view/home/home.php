
<section class="plan-one">
  <h2 class="hidden">Movie night step one</h2>

  <form action="index.php" method="GET" class="plan-one__form">
    <input type="hidden" name="action" value="planMovieNight">

    <div class="plan-one__questions">
      <img class="movie__still" src="./assets/images/isleOfDogs.jpg" alt="movie still from isle of dogs">
      <h2 class="plan-one__title divider-topAndBottom">Plan my night</h2>
      <div class="plan-one__block">
        <span>I'm planning a movie night for</span>
        <select name="nightType" id="nightType" class="plan-one__drop-down">
          <?php foreach($nightTypes as $night): ?>
            <option value="<?php echo $night['value'];?>"><?php echo $night['display'] ?></opton>
          <? endforeach; ?>
        </select>
      </div>

      <div class="plan-one__block">
        <span>During the movie we want to be</span>
        <select name="movieOptionOne" id="movieOptionOne" class="plan-one__drop-down">
          <?php foreach($stepOneOptions as $moveOption): ?>
            <option value="<?php echo $moveOption['value'];?>"><?php echo $moveOption['display'] ?></opton>
          <? endforeach; ?>
        </select>
      </div>


      <div class="plan-one__block">
        <span>but </span>
        <select name="movieOptionTwo" id="movieOptionTwo" class="plan-one__drop-down">
          <?php foreach($stepOneOptions as $moveOption): ?>
            <option value="<?php echo $moveOption['value'];?>"><?php echo $moveOption['display'] ?></opton>
          <? endforeach; ?>
        </select>
        <span>is something we can't</span> 
        <span>do without.</span>
      </div>
    </div>
    <div class="plan-one__submit-wrapper divider-topAndBottom">
      <input class="plan-one__submit" type="submit" value="Start planning">
    </div>
  </form>
</section>


<?php if (!empty($_SESSION['user'])): ?>
  <section class="movie-nights">
    <h2 class="movie-nights__title divider">Planned<br>Movie Nights</h2>
    <?php if (!empty($myMovieNights)): ?>
      <?php foreach($myMovieNights as $movieNight): ?>
        <a class="movie-nights__item" href="index.php?page=detail&id=<?php echo $movieNight['id'];?>">
          <article class="movie-night">
            <div class="movie-night__info">
              <h3 class="movie-night__title"><?php echo $movieNight['name']?></h3>
              <p class="movie-night__adress">@ Wardje Bever’s  Houz</p>
              <p>14.12.20</p>
            </div>
            <img class="movie-night__poster" src="./assets/temporary/chronicle.jpg">
          </article>
        </a>
        <div class="divider"></div>
      <?php endforeach;?>
    <?php else: echo '<span>No movie nights planned yet</span>'; ?>
    <?php endif; ?>
  </section>
<?php endif; ?>
