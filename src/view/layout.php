<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <?php /* NEW */ ?>
    <?php echo $css;?>
  </head>
  <body>
    <nav>
      <a href="index.php">🍿HOME🍿</a>
      <?php if(!isset($_SESSION['user'])): ?>
        <span>
          <a href="index.php?page=signIn">Sign In</a>
          /
          <a href="index.php?page=signUp">Sign Up</a>
        </span>
      <?php else: ?>
        <div>
          <span>Signed in as: <?php echo $_SESSION['user']['email'];?></span>
          <a href="index.php?page=signOut">Sign out</a>
          <?php endif; ?>
        </div>
    </nav>
    <main>
      <?php
        if(!empty($_SESSION['error'])) {
          echo '<div class="error box">' . $_SESSION['error'] . '</div>';
        }
        if(!empty($_SESSION['info'])) {
          echo '<div class="info box">' . $_SESSION['info'] . '</div>';
        }
      ?>
      <header><h1><?php echo $title; ?></h1></header>
      <?php echo $content;?>
    </main>
    <?php echo $js; ?>
  </body>
</html>
