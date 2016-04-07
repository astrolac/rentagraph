<html>
    <head>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="rentagraph.css" type="text/css">
        <title>RentaGRAPH</title>
    </head>
    <body>
        <div>
            <?php
                if(!isset($login)) { ?>
                    <a href="
                      <?php echo $this->config->item('base_url')."#$%"; ?>
                      idex.php/Authoz/authz">Войти</a>
             <?php   } else {
                    echo $username." [".$login."] "; ?>
                    <a href="
                      <?php echo $this->config->item('base_url')."#$%"; ?>
                      index.php/Authoz/authz">Выход</a>
             <?php
                } ?>
        </div>
