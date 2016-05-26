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
            <td>Логин</td>
            <td>ФИО пользователя</td>
            <td>Роль</td>
            <td>Область<br />видимости</td>
            <td>Активный</td>
            <td>Действия</td>
        </tr>
      </thead>
      <tbody><?php
          foreach ($users as $row) {
              echo "<tr>";
                  echo "<td class=\"sign\">".$row['login']."</td>";
                  echo "<td>".$row['username']."</td>";
                  echo "<td class=\"sign\">".$row['rolename']."</td>";
                  echo "<td class=\"sign\">".$row['scopename']."</td>";
                  echo "<td class=\"sign\">".(($row['isactive']) ? "+" : "-")."</td>";
                  echo "<td>";
                  echo "[<a href=\"$ueditref".$row['login']."\">Изменить</a>]";
                  echo "[<a href=\"$udelref".$row['login']."/0\">Удалить</a>]";
                  echo "[<a href=\"$udelref".$row['login']."/1\">Восстановить</a>]";
                  echo "</td>";
              echo "</tr>";
          }
      ?></tbody>
  </table>
</div>
