<div class="innermenu">
    <?php
    foreach ($innermenu as $key => $value) {
        echo "<a href=\"".$value."\"><button class=\"innermenubutton\">".$key."</button></a> ";
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
              <th>Номер брони</th>
              <th>Дата заезда</th>
              <th>Дата выезда</th>
              <th>Гость</th>
              <th>Контакты гостя</th>
              <th>Общая стоимость</th>
              <th>Сумма предоплаты</th>
              <th>Дата предоплаты</th>
              <th>Комментарии</th>
              <th>БВ</th>
              <th>Бронь установил</th>
              <th>Дата/время брони</th>
              <th>Действия</th>
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
                      echo "<td>".(($row['byowner'] == "on") ? "+": "")."</td>";
                      echo "<td>".$row['userlogin']."</td>";
                      echo "<td>".$row['bookingtimestamp']."</td>";
                      echo "<td>";
                      /*  Здесь проверяем вариант что пользователю разрешено редактировать только свои брони
                          и тогда только для его броней отображаем действия. */
                      if(($_SESSION['role']['ownbonly'] && $row['userlogin'] == $_SESSION['login']) || !$_SESSION['role']['ownbonly']) {
                          echo "[<a href=\"$hrefedit".$row['uid']."\">Изменить</a>]";
                          echo "[<a href=\"$hrefcancel".$row['uid']."\">Снять</a>]";
                      }
                      echo "</td>";
                  echo "</tr>";
              }
          ?>
        </tbody>
    </table>
</div>
