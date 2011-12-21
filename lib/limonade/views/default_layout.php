<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Limonade, the fizzy PHP micro-framework</title>
    <link href="<?= url_for('/_lim_css/screen.css'); ?>" rel="stylesheet">
    
  </head>
  <body>
    <div id="header">
      <h1>Limonade</h1>
    </div>
    
    <div id="content">
      <?php echo error_notices_render(); ?>
      <div id="main">
        <?php echo $content;?>
        <hr class="space">
      </div>
    </div>
    
  </body>
</html>
