<div class="innermenu">
    <?php
    echo "| ";
    foreach ($innermenu as $key => $value) {
        echo "<a href=\"".$value."\">".$key."</a> | ";
    }
    ?>
</div>
<div id="hotels">
    <table class="hotels">
        <thead>
          <tr>
              <td>UID</td>
              <td>Наименование</td>
              <td>Тип</td>
              <td>Контактное лицо</td>
              <td>Телефон</td>
              <td>Адрес</td>
              <td>Комментарии</td>
              <td>Комиссия в %</td>
              <td>Фиксированная комиссия</td>
              <td>Цена за сутки</td>
          </tr>
        </thead>
        <tbody><?php
              foreach ($hotelsarray as $row) {
                  echo "<tr>";
                      echo "<td class=\"numeric\">".$row['uid']."</td>";
                      echo "<td>".$row['hname']."</td>";
                      echo "<td>".$row['htype']."</td>";
                      echo "<td>".$row['person']."</td>";
                      echo "<td>".$row['personphone']."</td>";
                      echo "<td>".$row['haddress']."</td>";
                      echo "<td>".$row['hcomments']."</td>";
                      echo "<td class=\"numeric\">".$row['percentfee']."</td>";
                      echo "<td class=\"numeric\">".$row['fixedfee']."</td>";
                      echo "<td class=\"numeric\">".$row['price']."</td>";
                  echo "</tr>";
              }
          ?>
        </tbody>
    </table>
</div>
