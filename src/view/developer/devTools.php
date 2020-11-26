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
