<div class="innermenu">
    <?php
    foreach ($innermenu as $key => $value) {
        echo "<a href=\"".$value."\"><button class=\"innermenubutton\">".$key."</button></a> ";
    }
    ?>
</div>
<div class="dform">
  <?php echo form_open('authoz/usersmaintain'); ?>
  <table class="tform">
    <tbody>
      <tr>
        <td class="label">Логин</td>
        <td class="input"><input type="text" name="login" value="" size="20" maxlength="20" /></td>
      </tr>

      <tr>
        <td class="label">ФИО пользователя</td>
        <td class="input"><input type="text" name="username" value="" size="50" maxlength="128" /></td>
      </tr>

      <tr>
        <td class="label">Пароль (до 20-ти символов)</td>
        <td class="input"><input type="password" name="passfraze" value="" size="20" maxlength="20" /></td>
      </tr>

      <tr>
        <td colspan="2" class="button"><input type="submit" name="submit" value="Добавить" /></td>
      </tr>
    </tbody>
  </table>
</div>
<div id="hotels">
  <table class="hotels">
      <thead>
        <tr>
            <td>Логин</td>
            <td>ФИО пользователя</td>
            <td>Активный</td>
            <td>Действия</td>
        </tr>
      </thead>
      <tbody><?php
          foreach ($users as $row) {
              echo "<tr>";
                  echo "<td>".$row['login']."</td>";
                  echo "<td>".$row['username']."</td>";
                  echo "<td>".$row['isactive']."</td>";
                  echo "<td>";
                  echo "[<a href=\"$urefdel".$row['login']."/0\">Удалить</a>]";
                  echo "[<a href=\"$urefdel".$row['login']."/1\">Восстановить</a>]";
                  echo "</td>";
              echo "</tr>";
          }
      ?></tbody>
  </table>
</div>
