    <div>
        <h2><?php echo $title; ?></h2>

        <?php /*echo validation_errors();*/ ?>

        <?php echo form_open('authoz/auth_test'); ?>

            <label for="login">Имя пользователя</label>
                <input type="text" name="login" value="" size="20" /><br />

            <label for="passfraze">Пароль</label>
                <input type="password" name="passfraze" value="" size="50"/><br />

            <input type="submit" name="submit" value="Войти" />

        </form>
    </div>
         