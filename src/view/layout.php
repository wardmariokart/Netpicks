<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $title; ?></title>
  <?php /* NEW */ ?>
  <?php echo $css; ?>
</head>

<body>
  <nav class="divider">
    <!-- <a class="nav__title" href="index.php">netpicks</a> -->
    <a class="nav__title" href="index.php"><span class="hidden">netpicks</span></a>
    <img class="nav__title-logo" src="./assets/images/netpicksLogo-02.png" alt="netpicks">
    <div class="nav__user">
      <?php if (!isset($_SESSION['user'])) : ?>
        <span>
          <a href="index.php?page=signIn">Sign In</a>
          - or -
          <a href="index.php?page=signUp">Sign Up</a>
        </span>
      <?php else : ?>
        <span>Logged in 👍🏿</span>
        <a href="index.php?page=signOut">Sign out</a>
      <?php endif; ?>
    </div>
  </nav>
  <main>
    <?php
    if (!empty($_SESSION['error'])) {
      echo '<div class="error box">' . $_SESSION['error'] . '</div>';
    }
    if (!empty($_SESSION['info'])) {
      echo '<div class="info box">' . $_SESSION['info'] . '</div>';
    }
    ?>
    <header>
      <h1><?php echo $title; ?></h1>
    </header>
    <?php echo $content; ?>
  </main>

  <footer>
    <img class="footer__netpicks-icon" src="./assets/images/kijkwijzerGeweld.png" alt="geweld icoon van kijkwijzer">
    <span class="footer__copyright">netpicks</span>
  </footer>
</body>
<?php echo $js; ?>

</html>