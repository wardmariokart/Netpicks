<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieNight</title>
    <?php /* NEW */ ?>
    <?php echo $css;?>
  </head>
  <body>
    <main>
      <?php
        if(!empty($_SESSION['error'])) {
          echo '<div class="error box">' . $_SESSION['error'] . '</div>';
        }
        if(!empty($_SESSION['info'])) {
          echo '<div class="info box">' . $_SESSION['info'] . '</div>';
        }
      ?>
      <?php echo $content;?>
    </main>
    <?php echo $js; ?>
  </body>
</html>
