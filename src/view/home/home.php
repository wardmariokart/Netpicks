
<form action="index.php" method="GET">
  <select name="nightType" id="nightType">
    <?php foreach($nightTypes as $night): ?>
      <option value="<?php echo $night['value'];?>"><?php echo $night['displayName'] ?></opton>
    <? endforeach; ?>
  </select>


  <select name="movieOptionOne" id="movieOptionOne">
    <?php foreach($stepOneOptions as $moveOption): ?>
      <option value="<?php echo $moveOption['value'];?>"><?php echo $moveOption['displayName'] ?></opton>
    <? endforeach; ?>
  </select>


  <input type="submit" value="Plan my movie night!!">
</form>

