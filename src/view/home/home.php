
<section class="plan-one">
  <h2 class="hidden">Movie night step one</h2>

  <form action="index.php" method="GET" class="plan-one__form">
    <input type="hidden" name="action" value="planMovieNight">

    <div class="plan-one__questions">
      <div class="plan-one__block">
        <span>I'm planning a movie night for </span>
        <select name="nightType" id="nightType" class="plan-one__drop-down">
          <?php foreach($nightTypes as $night): ?>
            <option value="<?php echo $night['value'];?>"><?php echo $night['displayName'] ?></opton>
          <? endforeach; ?>
        </select><span>.</span>
      </div>

      <div class="plan-one__block">
        <span>During that night we want to </span>
        <select name="movieOptionOne" id="movieOptionOne" class="plan-one__drop-down">
          <?php foreach($stepOneOptions as $moveOption): ?>
            <option value="<?php echo $moveOption['value'];?>"><?php echo $moveOption['displayName'] ?></opton>
          <? endforeach; ?>
        </select>
      </div>


      <div class="plan-one__block">
        <span> but </span>
        <select name="movieOptionTwo" id="movieOptionTwo" class="plan-one__drop-down">
          <?php foreach($stepOneOptions as $moveOption): ?>
            <option value="<?php echo $moveOption['value'];?>"><?php echo $moveOption['displayName'] ?></opton>
          <? endforeach; ?>
        </select>
        <span> is also something we want to do.</span>
      </div>
    </div>
    <div class="plan-one__submit-wrapper">
      <input class="plan-one__submit" type="submit" value="Plan my movie night!!">
    </div>
  </form>
</section>
