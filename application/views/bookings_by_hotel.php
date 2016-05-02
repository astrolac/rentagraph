<div class="innermenu">
    <?php
    echo "| ";
    foreach ($innermenu as $key => $value) {
        echo "<a href=\"".$value."\">".$key."</a> | ";
    }
    ?>
</div>
<div class="auth">
    <h2><?php echo $title; ?></h2>
</div>
<div id="hotels">
    <table class="hotels">
        <thead>
          <tr>
              <td>Номер брони</td>
              <td>Дата заезда</td>
              <td>Дата выезда</td>
              <td>Гость</td>
              <td>Контакты гостя</td>
              <td>Общая стоимость</td>
              <td>Сумма предоплаты</td>
              <td>Дата предоплаты</td>
              <td>Комментарии</td>
              <td>БВ</td>
              <td>Бронь установил</td>
              <td>Дата/время брони</td>
              <td>Действия</td>
          </tr>
        </thead>
        <tbody><?php
              foreach ($bookings as $row) {
                  echo "<tr>";
                      echo "<td class=\"numeric\">".$row['uid']."</td>";
                      echo "<td>".substr($row['datein'], -2)."-".substr($row['datein'], 5, 2)."-".substr($row['datein'], 0, 4)."</td>";
                      echo "<td>".substr($row['dateout'], -2)."-".substr($row['dateout'], 5, 2)."-".substr($row['dateout'], 0, 4)."</td>";
                      echo "<td>".$row['person']."</td>";
                      echo "<td>".$row['personphone']."</td>";
                      echo "<td class=\"numeric\">".$row['totalsum']."</td>";
                      echo "<td class=\"numeric\">".$row['beforepaysum']."</td>";
                      echo "<td>".substr($row['beforepaydate'], -2)."-".substr($row['beforepaydate'], 5, 2)."-".substr($row['beforepaydate'], 0, 4)."</td>";
                      echo "<td>".$row['comments']."</td>";
                      echo "<td>".$row['byowner']."</td>";
                      echo "<td>".$row['userlogin']."</td>";
                      echo "<td>".$row['bookingtimestamp']."</td>";
                      echo "<td>";
                      echo "[<a href=\"$hrefedit".$row['uid']."\">Изменить</a>]";
                      echo "[<a href=\"$hrefcancel".$row['uid']."\">Снять</a>]";
                      echo "</td>";
                  echo "</tr>";
              }
          ?>
        </tbody>
    </table>
</div>
