<div class="innermenu">
    <?php
    foreach ($innermenu as $key => $value) {
        echo "<a href=\"".$value."\"><button class=\"innermenubutton\">".$key."</button></a> ";
    }
    ?>
</div>
<div id="hotels">
  <table class="hotels">
      <thead>
        <tr>
            <td>ID роли</td>
            <td>Название</td>
            <td>Управ.<br />ролями</td>
            <td>Управ.<br />отелями</td>
            <td>Управ.<br />польз.</td>
            <td>Управ.<br />бронями</td>
            <td>Только<br />свои брони</td>
            <td>Действия</td>
        </tr>
      </thead>
      <tbody><?php
          foreach ($roles as $row) {
              echo "<tr>";
                  echo "<td class=\"sign\">".$row['uid']."</td>";
                  echo "<td>".$row['title']."</td>";
                  echo "<td class=\"sign\">".(($row['rcontrol']) ? "+" : "-")."</td>";
                  echo "<td class=\"sign\">".(($row['hcontrol']) ? "+" : "-")."</td>";
                  echo "<td class=\"sign\">".(($row['ucontrol']) ? "+" : "-")."</td>";
                  echo "<td class=\"sign\">".(($row['bcontrol']) ? "+" : "-")."</td>";
                  echo "<td class=\"sign\">".(($row['ownbonly']) ? "+" : "-")."</td>";
                  echo "<td>";
                  echo "[<a href=\"$urefedit".$row['uid']."\">Параметры</a>]";
                  echo "[<a href=\"$urefdel".$row['uid']."\">Удалить</a>]";
                  echo "</td>";
              echo "</tr>";
          }
      ?></tbody>
  </table>
</div>
