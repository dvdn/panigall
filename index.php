<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Panigall</title>
    <link rel="icon" href="inc/panigall.png" type="image/png" sizes="96x96">
    <link rel="stylesheet" href="inc/style.css">
    <link rel="stylesheet" href="inc/modal.css">
    <script src="inc/modal.js"></script>

  </head>
  <body>
    <header><h1>Panigall</h1></header>

    <div id="content">
      <?php
          /* Panigall images files explorer */
          include('inc/panigall.php');
          include('inc/modal.php')
      ?>
    </div>

    <footer>
      <?php echo date('Y');?> . powered by <a href="https://github.com/dvdn/panigall" target="_blank"/>dvdn/panigall</a>
    </footer>
  </body>
</html>
