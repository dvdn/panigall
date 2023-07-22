<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Panigall</title>
    <link rel="icon" href="panigall.png" type="image/png" sizes="96x96">
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
    <header><h1>Panigall</h1></header>

    <div id="content">
      <?php
          /* panigall explorer */
          include('panigall.php');
      ?>
    </div>

    <footer>
      <?php echo date('Y');?> . powered by <a href="https://github.com/dvdn/panigall" target="_blank"/>dvdn/panigall</a>
    </footer>
  </body>
</html>
