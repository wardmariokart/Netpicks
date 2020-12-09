<section class="plan-one">
  <picture class="plan-one__picture">
    <source media="(min-width: 800px)" srcset="./assets/images/moonriseKingdom-XL.jpg 1582w,
            ./assets/images/moonriseKingdom-L.jpg 1216w,
            ./assets/images/moonriseKingdom-M.jpg 946w,
            ./assets/images/moonriseKingdom-S.jpg 718w,
            ./assets/images/moonriseKingdom-XS.jpg 526w" 
            sizes="(max-width: 924px) 40vw,
            (min-width: 925px) 55vw,
            (min-width: 1000px) 75vw,
            (min-width: 1100px) 85vw"/>
    <source srcset="./assets/images/isleOfDogs.jpg">
    <img src="./assets/images/moonriseKingdom.jpg" alt="Wes Anderson Movie still">
  </picture>

  <h2 class="plan-one__title divider--top-bottom">Plan my night</h2>

  <form class="plan-one__form" action="index.php" method="GET">
    <input type="hidden" name="action" value="planMovieNight">

    <div class="plan-one__block">
      <span>I'm planning a movie night for</span>
      <select name="nightType" id="nightType" class="plan-one__drop-down">
        <?php foreach($nightTypes as $night): ?>
          <option value="<?php echo $night['value'];?>"><?php echo $night['display'] ?></option>
        <? endforeach; ?>
      </select>
    </div>

    <div class="plan-one__block">
      <span>During the movie we want to </span>
      <select name="movieOptionOne" id="movieOptionOne" class="plan-one__drop-down">
        <?php foreach($stepOneOptions as $movieOption): ?>
          <?php if ($movieOption['choise_index'] === 0): ?>
            <option value="<?php echo $movieOption['value'];?>"><?php echo $movieOption['display'] ?></option>
          <?php endif; ?>
        <? endforeach; ?>
      </select>
    </div>

    <div class="plan-one__block">
      <span>but </span>
      <select name="movieOptionTwo" id="movieOptionTwo" class="plan-one__drop-down">
        <?php foreach($stepOneOptions as $movieOption): ?>
          <?php if ($movieOption['choise_index'] === 1): ?>
            <option value="<?php echo $movieOption['value'];?>"><?php echo $movieOption['display'] ?></option>
          <?php endif?>
          <? endforeach; ?>
      </select>
      <span>is something </span>
      <span>we can't do without.</span>
    </div>
    <div class="plan-one__submit-wrapper divider--top-bottom">
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
              <h3 class="movie-night__title"><?php echo $movieNight['title']?></h3>
              <p class="movie-night__adress">@ <?php echo $_SESSION['user']['username']?>’s  Houz</p>
            </div>
            <img class="movie-night__poster" src="http://image.tmdb.org/t/p/w342/<?php echo $movieNight['poster'] ?>" alt="Movie night poster">
          </article>
          <div class="divider"></div>
        </a>
      <?php endforeach;?>
    <?php else: echo '<span>No movie nights planned yet</span>'; ?>
    <?php endif; ?>
  </section>
<?php endif; ?>

