<section class="page--detail"> <!-- TODO REMOVE -->
  <header>
    <h2><?php echo $movieNight['title']?></h2>
  </header>
  <section class="chosen__movie">
      <div class="col3">
          <img class="movie__poster" src="http://image.tmdb.org/t/p/w342/<?php echo $movie['movie']['poster'] ?>" alt="<?php echo $movie['movie']['title'] . ' poster.' ?>">
          <div class="movie__details">
              <h2 class="detail__movie-title"><?php echo $movie['movie']['title'] ?></h2>
              <div class="movie__crew">
                  <!-- <p><span class="bold">Director: </span><?php echo $movie['movie']['title'] ?></p> -->
                  <p><span class="bold">Cast:</span> <?php echo implode(', ', array_column($movie['actors'], 'name'));?></p>
              </div>
          </div>
      </div>
      <p class="movie__summary"><?php echo $movie['movie']['description'] ?></p>
  </section>

  <section class="movie-night__settings divider--no-padding divider--top">
    <h3>This movie was pick based your answers: </h3>
    <ul class="settings">
      <?php foreach($movieNight['settings'] as $setting): ?>
      <li>
        <form class="setting" action="index.php?page=detail&id=<?php echo $_GET['id']?>" method="POST">
          <input type="hidden" name="action" value="updateSettingsRequest">
          <input type="hidden" name="questionId" value="<?php echo $setting['question_id']?>">
          <input type="hidden" name="answerId" value="<?php echo $setting['answer_id']?>">
          <span class="setting__title">
            <?php echo $setting['filter'];?>
          </span>
          <span class="setting__value <?php echo 'setting__value--' . $setting['answer'] ?>">
            <?php echo $setting['answer'];?>
          </span>
        </form>
      </li>
      <?php endforeach; ?>
    </ul>
  </section>
  <div class="update-overlay">
    <div class="update-overlay__background">
    </div>
    <div class="card-stack-wrapper">
      <div class="card-stack"></div>
    </div>
  </div>


  <section class="movie__extras">
      <article class="extras">
          <h3 class="extras__title">Suggested snacks:</h3>
          <ul class="extra__list">
          <?php foreach($snacks as $snack):?>
            <li class="extra"><img class="extra__icon" src="./assets/icons/<?php echo $snack['file_path'];?>" alt="<?php echo $snack['name']?>"></li>
          <?php endforeach?>
          </ul>
      </article>
      <div class="divider-horizontal"></div>
      <article class="extras">
        <h3 class="extras__title">Suggested asseccoires:</h3>
        <ul class="extra__list">
          <?php foreach($accessoires as $accessoire):?>
            <li class="extra"><img class="extra__icon" src="./assets/icons/<?php echo $accessoire['file_path'];?>" alt="<?php echo $accessoire['name']?>"></li>
          <?php endforeach?>
          </ul>
      </article>
  </section>
  <div class="divider"></div>
  <section class="book__movie">
      <div class="book-this"><a href="index.php">Book movie night</a></div>
      <div class="book-other"><a href="index.php">Plan another night</a></div>
  </section>
  <section>
    <h3 class="hidden">Movie night actions</h3>
    <?php if ($bOwnerless): ?>
      <form action="index.php?page=detail&id="<?php echo $_GET['id'];?> method="POST">
        <input type="hidden" name="action" value="claim">
        <input type="submit" value="Save this movie night">
      </form>
    <?php endif; ?>
    <div class="actions divider--top">
      <a href="index.php" class="action--home"></a>
      <?php if ($bIsOwner): ?>
      <form class="" action="index.php?page=detail&id=<?php echo $_GET['id']?>" method="POST">
        <input type="hidden" name="action" value="delete">
        <input class="action--delete" type="submit" value="🗑">
      </form>
      <?php endif;?>
    </div>
  </section>
</section>
