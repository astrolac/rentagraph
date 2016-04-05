<html>
    <head>
        <meta charset="utf-8" />
        <!-- <link rel="stylesheet" href="videotest.css" type="text/css"> -->
        <title>RentaGRAPH</title>
    </head>
    <body>
        <h2><?php echo $title; ?></h2>

        <?php /*echo validation_errors();*/ ?>

        <?php echo form_open('auth_test'); ?>

            <label for="login">Имя пользователя</label>
                <input type="text" name="login" value="" size="20" /><br />

            <label for="passfraze">Пароль</label>
                <input type="password" name="passfraze" value="" size="50"/><br />

            <input type="submit" name="submit" value="Войти" />

        </form>
    </body>
</html>
