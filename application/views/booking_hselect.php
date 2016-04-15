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
<div class="auth">
    <h2><?php echo $title; ?></h2>
</div>
<div id="hotels">
    <table class="hotels">
      <thead>
          <tr>
              <td class="theader">UID</td>
              <td class="theader">Наименование</td>
              <td class="theader">Текущие брони</td>
          </tr>
      </thead>
      <tbody>
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
