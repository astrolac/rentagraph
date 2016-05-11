<div class="innermenu">
    <?php
    foreach ($innermenu as $key => $value) {
        echo "<a href=\"".$value."\"><button class=\"innermenubutton\">".$key."</button></a> ";
    }
    ?>
</div>
<div class="dform">
  <?php echo form_open('base/htypes'); ?>
  <table class="tform">
    <tbody>
      <tr>
        <td class="label">Тип отеля</td>
        <td class="input"><input type="text" name="htype" value="" size="50" /></td>
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
            <td>Тип отеля</td>
            <td>Действия</td>
        </tr>
      </thead>
      <tbody><?php
          foreach ($htypes as $row) {
              echo "<tr>";
                  echo "<td>".$row['uid']."</td>";
                  echo "<td>".$row['htype']."</td>";
                  echo "<td>";
                  echo "[<a href=\"$hrefdel".$row['uid']."\">Удалить</a>]";
                  echo "</td>";
              echo "</tr>";
          }
      ?></tbody>
  </table>
</div>
