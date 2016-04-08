    <div class="auth">
        <h2><?php echo $title; ?></h2>

        <?php /*echo validation_errors();*/ ?>

        <div class="dform">
        <?php echo form_open('authoz/auth_test'); ?>

            <table class="tform">
              <tbody>
                <tr>
                    <td class="label">Имя пользователя</td>
                    <td class="input"><input type="text" name="login" value="" size="20" /></td>
                </tr>
                <tr>
                    <td class="label">Пароль</td>
                    <td class="input"><input type="password" name="passfraze" value="" size="20"/></td>
                </tr>
                <tr>
                    <td colspan="2" class="button"><input type="submit" name="submit" value="Войти" /></td>
                </tr>
              </tbody>
            </table>
        </form>
        </div>
    </div>
