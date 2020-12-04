<span>Possible categories</span>
<ul>
</ul>

<form action="index.php?page=devTools" method="POST">
  <input type="hidden" name="action" value="add-filter-keywords">
  <select name="filter-category" id="filter-category">
    <?php foreach($filterCategories as $filterCategory): ?>
      <option value="<?php echo $filterCategory['id']?>"><?php echo $filterCategory['category_name']?></option>
    <?php endforeach;?>
  </select>

  <label>
    Enter keywords as "keywordA,keywordB,keywordC..."
    <input type="text" name="filter-keywords">
  </label>
  <input type="submit" value="Add keywords to filter category">
</form>


<section>
  <h2>Update database with TMDB API</h2>

  <form action="index.php?page=devTools" method="POST">
    <input type="hidden" name="action" value="updateMoviePosters">
    <span>Number of movies to update.</span>
    <label class="dev__label" for="updateAmount">From: </label>
    <input type="number" id="updateFrom" name="updateFrom" min="-1" value="0">
    <label class="dev__label" for="updateAmount">To: </label>
    <input type="number" id="updateTo" name="updateTo" min="-1" value="500">
    <input class="dev__update-button" type="submit" value="! Update database !">
  </form>


  <?php if(isset($updatedMoviePaths)):?>
    <span class="dev__update-count"><?php echo count($updatedMoviePaths)?> Movies had outdated posters and were updated.</span>
    <div>
      <?php foreach($updatedMoviePaths as $updatedMovie): ?>
        <span class="dev__update"><div><span class="dev__update-title"><?php echo $updatedMovie['title']?></span></div><span class="dev__poster dev__poster--from"><?php echo $updatedMovie['outdatedPoster'];?></span><span class="dev__poster dev__poster--to"><?php echo $updatedMovie['updatedPoster']?></span></span>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

</section>
