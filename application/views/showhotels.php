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
      <tbody>
          <tr class="theader">
              <td class="theader">UID</td>
              <td class="theader">Наименование</td>
              <td class="theader">Тип</td>
              <td class="theader">Контактное лицо</td>
              <td class="theader">Телефон</td>
              <td class="theader">Адрес</td>
              <td class="theader">Комментарии</td>
              <td class="theader">Комиссия в %</td>
              <td class="theader">Фиксированная комиссия</td>
              <td class="theader">Цена за сутки</td>
          </tr>
          <?php
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
