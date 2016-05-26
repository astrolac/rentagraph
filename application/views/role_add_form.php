<div class="dform">
  <?php echo form_open('authoz/role_add_job'); ?>

  <input type="hidden" name="isedit" value="<?php
      if(isset($roledata)) {
          echo "YES";
      } else {
          echo "NO";
      }
  ?>" />

  <?php if(isset($roledata)) { ?>
      <input type="hidden" name="roleid" value="<?php echo $roledata['uid']; ?>" />
  <?php } ?>

  <table class="tform">
    <tbody>
      <tr>
        <td class="label">Название роли</td>
        <td class="input"><input type="text" name="title" value="<?php
            if(isset($roledata)) {
                echo $roledata['title'];
            }
        ?>" size="50" maxlength="128" /></td>
      </tr>

      <tr>
        <td class="label">Управление ролями</td>
        <td class="input"><input type="checkbox" name="rсontrol" <?php
            if(isset($roledata) && $roledata['rcontrol']) {
                echo "checked";
            } ?> />
        </td>
      </tr>

      <tr>
        <td class="label">Управление отелями</td>
        <td class="input"><input type="checkbox" name="hсontrol" <?php
            if(isset($roledata) && $roledata['hcontrol']) {
                echo "checked";
            } ?> />
        </td>
      </tr>

      <tr>
        <td class="label">Управление пользователями</td>
        <td class="input"><input type="checkbox" name="uсontrol" <?php
            if(isset($roledata) && $roledata['ucontrol']) {
                echo "checked";
            } ?> />
        </td>
      </tr>

      <tr>
        <td class="label">Управление бронями</td>
        <td class="input"><input type="checkbox" name="bсontrol" <?php
            if(isset($roledata) && $roledata['bcontrol']) {
                echo "checked";
            } ?> />
        </td>
      </tr>

      <tr>
        <td class="label">Управление только своими бронями ("Владелец отеля")</td>
        <td class="input"><input type="checkbox" name="ownbonly" <?php
            if(isset($roledata) && $roledata['ownbonly']) {
                echo "checked";
            } ?> />
        </td>
      </tr>

      <tr>
        <td colspan="2" class="button"><input type="submit" name="submit" value="<?php
            if(isset($roledata)) {
                echo "Изменить";
            } else { echo "Добавить"; }
        ?>" /></td>
      </tr>
    </tbody>
  </table>
  </form>
</div>
