<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $title; ?></title>
  <?php echo $css; ?>
  <link rel="stylesheet" href="https://use.typekit.net/sha2dsc.css">
  <link rel="stylesheet" href="https://use.typekit.net/sha2dsc.css">
  <link rel="stylesheet" href="https://use.typekit.net/sha2dsc.css">
</head>

<body class="<?php echo 'page--' . $page ?>">
<?php if(true): ?>
  <nav class="divider">
    <a class="nav__title" href="index.php"><img src="./assets/images/netpicksLogo-smal.png" alt="netpicks"></a>
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
  <? endif?>
  <?php if(true): ?>
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
      <h1 class="hidden"><?php echo $title; ?></h1>
    </header>
    <?php echo $content; ?>
  </main>
  <?php endif ?>

  <footer>
    <img class="footer__netpicks-icon" src="./assets/images/kijkwijzerGeweld.png" alt="geweld icoon van kijkwijzer">
    <span class="footer__copyright">netpicks</span>
  </footer>
  <?php echo $js; ?>
  <script src="./js/lib/anime.min.js"></script>
</body>
</html>
