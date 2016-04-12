<!-- /*
    Представление для выбора отеля при оформлении брони.
    Планируется:
      Отображает перечень отелей с отметками о текущих активных бронях.
      Имя каждого отеля - ссылка на форму добавления брони. В ссылке в качестве
      параметра передаем UID отеля.
*/ -->
<div class="innermenu">
    <?php
    echo "| ";
    foreach ($innermenu as $key => $value) {
        echo "<a href=\"".$value."\">".$key."</a> | ";
    }
    ?>
</div>
<div id="hotels">
    <?php echo $title; ?>
    <table class="hotels">
      <tbody>
          <tr class="theader">
              <td class="theader">UID</td>
              <td class="theader">Наименование</td>
              <td class="theader">Текущие брони</td>
          </tr>
          <?php
              foreach ($hotelsarray as $row) {
                  echo "<tr>";
                      echo "<td class=\"numeric\">".$row['uid']."</td>";
                      echo "<td><a href=\"".$this->config->item('base_url')."index.php/booking/booking_add_form/".$row['uid']."\">".$row['hname']."</a></td>";
                      echo "<td>"."</td>";
                  echo "</tr>";
              }
          ?>
      </tbody>
    </table>
</div>
