<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Panigall</title>
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
    <div id="content">
      <h1>Panigall</h1>
      <?php //error_reporting(E_ERROR | E_PARSE);
          /* tmp Debug */
          error_reporting(E_ALL);
          ini_set('display_errors', 1);

          /* galleries */
          include('inc/panigall.php');
      ?>
    </div>

  </body>
</html>
