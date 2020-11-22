
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


<?php if(!isset($_SESSION['user'])): ?>
  <span>
    <a href="index.php?page=signIn">Sign In</a>
    /
    <a href="index.php?page=signUp">Sign Up</a>
  </span>
<?php else: ?>
  <span>Signed in as: <?php echo $_SESSION['user']['email'];?></span>
  <a href="index.php?page=signOut">Sign out</a>
<?php endif; ?>
