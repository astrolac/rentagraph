<div class="dform">
  <?php echo form_open('authoz/user_add_job'); ?>

  <input type="hidden" name="isedit" value="<?php
      if(isset($userdata)) {
          echo "YES";
      } else {
          echo "NO";
      }
  ?>" />

  <table class="tform">
    <tbody>
      <tr>
        <td class="label">Логин</td>
        <td class="input"><input id="inlogin" type="text" name="login" value=<?php
            if(isset($userdata)) {
                echo "\"".$userdata['login']."\" readonly";
            } else { echo "\"\""; }
        ?> size="20" maxlength="20" autocomplete="off" /></td>
      </tr>

      <tr>
        <td class="label">ФИО пользователя</td>
        <td class="input"><input id="inusername" type="text" name="username" value="<?php
            if(isset($userdata)) {
                echo $userdata['username'];
            }
        ?>" size="50" maxlength="128" autocomplete="off"/></td>
      </tr>

      <tr>
        <td class="label">Пароль (до 20-ти символов)</td>
        <td class="input"><input id="inpassword" type="password" name="passfraze" value="" size="20" maxlength="20" autocomplete="off"/></td>
      </tr>

      <tr>
        <td class="label">Роль пользователя</td>
        <td class="input">
            <select name="roleid" size="1">
                <?php foreach ($uroles as $value) { ?>
                <option value="<?php echo $value['uid']; ?>"<?php
                    if(isset($userdata) && $userdata['roleid'] == $value['uid']) {
                        echo " selected";
                    }
                ?>><?php echo $value['title']; ?></option>
                <?php } ?>
            </select>
        </td>
      </tr>

      <tr>
        <td class="label">Область видимости</td>
        <td class="input">
            <select name="scopeid" size="1">
                <?php foreach ($scopes as $value) { ?>
                <option value="<?php echo $value['uid']; ?>"<?php
                    if(isset($userdata) && $userdata['scope'] == $value['uid']) {
                        echo " selected";
                    }
                ?>><?php echo $value['title']; ?></option>
                <?php } ?>
            </select>
        </td>
      </tr>

      <tr>
        <td colspan="2" class="button"><input type="submit" name="submit" value="<?php
            if(isset($userdata)) {
                echo "Изменить";
            } else { echo "Добавить"; }
        ?>" /></td>
      </tr>
    </tbody>
  </table>
</div>
