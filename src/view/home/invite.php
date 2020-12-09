<section>
    <h2 class="title--smaller">You’ve been invited by <?php echo $invitedBy ?></h2>

    <article class="ticket">
        <h3 class="hidden">Movie ticket</h3>
        <img class="ticket__tear" src="./assets/images/ticketTop-01.png" alt="ticket">
        <div class="horizontal__divider"></div>

        <div class="ticket__info">
            <div class="movie-night__info ticket-margin__top">
                <h3 class="movie-night__title ticket-margin ticket-night__title sub-title"><?php echo $movieNight['title'] ?></h3>
            </div>
            <p class="movie-ticket__adress divider--top-bottom"><span class="bold">Location:</span> @<?php echo $invitedBy ?>'s Houz</p>
            </div>

            <div class="col3 ticket-margin">
                <img class="movie__poster" src="http://image.tmdb.org/t/p/w342/<?php echo $movie['movie']['poster'] ?>" alt="<?php echo $movie['movie']['title'] . ' poster.' ?>">
                <div class="movie__details">
                    <h2 class="detail__movie-title ticket__movie-title"><?php echo $movie['movie']['title'] ?></h2>
                    <div class="movie__crew">
                        <?php if (!empty($movie['movie']['tagline'])) : ?>
                            <p><b><?php echo $movie['movie']['tagline']; ?></b></p>
                        <?php endif; ?>
                        <p><span class="bold">Cast:</span><?php echo implode(', ', array_column($movie['actors'], 'name')); ?></p>
                    </div>
                </div>
            </div>
            <p class="movie__summary ticket-margin ticket__summary">Three high school friends gain superpowers after making an incredible discovery underground. Soon they find their lives spinning out of control and their bond tested as they embrace their darker sides.</p>
            <p class="movie-ticket__adress divider--top-bottom">Be there or be square!</p>
        </div>
        <img class="ticket__tear upsidedown" src="./assets/images/ticketTop-01.png" alt="ticket">
    </article>

</section>
