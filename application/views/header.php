<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="<?php  echo $this->config->item('base_url')."rentagraph.css"; ?>" type="text/css">
        <title>RentaGRAPH</title>
    </head>
    <body>
        <div id="header">
              <h1>RentaGRAPH</h1>
              <div class="title">
                  <?php /*echo $title;*/ ?>
              </div>
              <div class="info">
                <?php echo $rightmsg; ?>
                <a href="<?php echo $righthref; ?>"><?php echo $righthreftext; ?></a>
              </div>
        </div>
