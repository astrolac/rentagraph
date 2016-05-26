<div class="innermenu">
    <?php
    foreach ($innermenu as $key => $value) {
        echo "<a href=\"".$value."\"><button class=\"innermenubutton\">".$key."</button></a> ";
    }
    ?>
</div>
<div class="dform">
  <?php echo form_open('authoz/scopesmaintain'); ?>
  <table class="tform">
    <tbody>
      <tr>
        <td class="label">Имя области видимости</td>
        <td class="input"><input type="text" name="scopename" value="" size="50" maxlength="128" /></td>
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
            <td>UID</td>
            <td>Область видимости</td>
            <td>Действия</td>
        </tr>
      </thead>
      <tbody><?php
          foreach ($scopes as $row) {
              echo "<tr>";
                  echo "<td>".$row['uid']."</td>";
                  echo "<td>".$row['title']."</td>";
                  echo "<td>";
                  echo "[<a href=\"$editref".$row['uid']."\">Изменить</a>]";
                  echo "[<a href=\"$delref".$row['uid']."\">Удалить</a>]";
                  echo "</td>";
              echo "</tr>";
          }
      ?></tbody>
  </table>
</div>
