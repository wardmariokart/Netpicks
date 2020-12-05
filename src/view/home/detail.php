<section class="chosen__movie">
    <div class="col3">
        <img class="movie__poster" src="http://image.tmdb.org/t/p/w342/<?php echo $details['movie']['poster'] ?>" alt="<?php echo $details['movie']['title'] . ' poster.' ?>">
        <div class="movie__details">
            <h2 class="detail__movie-title"><?php echo $details['movie']['title'] ?></h2>
            <div class="movie__crew">
                <p><span class="bold">Director: </span><?php echo $details['movie']['title'] ?></p>
                <p><span class="bold">Cast:</span> Dane DeHaan, Alex Russell, Michael B. Jordan </p>
            </div>
        </div>
    </div>
    <p class="movie__summary"><?php echo $details['movie']['description'] ?></p>
    <div class="movie__path">
        <p class="movie__path-item">Gore</p>
        <p>></p>
        <p class="movie__path-item">Supernatural</p>
        <p>></p>
        <p class="movie__path-item">80% guns & explosions</p>
        <p>></p>
        <p class="movie__path-item">Superhero's</p>
    </div>
    <div class="divider"></div>
</section>
<section class="movie__extras">
    <article class="extras">
        <h3 class="extras__title">Suggested snacks:</h3>
        <ul class="extra__list">
            <ol class="extra"><img class="extra__icon" src="./assets/temporary/noun_chips_3614132.png" alt="snack"></ol>
            <ol class="extra"><img class="extra__icon" src="./assets/temporary/noun_Popcorn_3614154.png" alt="snack"></ol>
            <ol class="extra"><img class="extra__icon" src="./assets/temporary/noun_Milkshake_3614128.png" alt="snack"></ol>
        </ul>
    </article>
    <div class="divider-horizontal"></div>
    <article class="extras">
        <h3 class="extras__title">Suggested asseccoires:</h3>
        <ul class="extra__list">
            <ol class="extra"><img class="extra__icon" src="./assets/temporary/noun_chips_3614132.png" alt="asseccoires"></ol>
            <ol class="extra"><img class="extra__icon" src="./assets/temporary/noun_chips_3614132.png" alt="asseccoires"></ol>
            <ol class="extra"><img class="extra__icon" src="./assets/temporary/noun_chips_3614132.png" alt="asseccoires"></ol>
        </ul>
    </article>
</section>
<div class="divider"></div>
<section class="book__movie">
    <div class="book-this"><a href="index.php">Book movie night</a></div>
    <div class="book-other"><a href="index.php">Plan another night</a></div>
</section>