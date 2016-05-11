<!-- /*
    Представление для выбора отеля при оформлении брони.
    Планируется:
      Отображает перечень отелей с отметками о текущих активных бронях.
      Имя каждого отеля - ссылка на форму добавления брони. В ссылке в качестве
      параметра передаем UID отеля.
*/ -->
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
              <td class="theader" colspan="2">Наименование</td>
          </tr>
      </thead>
      <tbody>
          <?php
          foreach($hnames as $huid => $hndata) {
              echo "<tr>";
                echo "<td colspan=\"2\">";
                    if(!count($hndata['chotels'])) {
                        echo "<a href=\"".$href.$huid."\">".$hndata['hname']."</a>";
                    } else {
                        echo $hndata['hname'];
                    }
                echo "</td>";
              echo "</tr>";
              if(count($hndata['chotels'])) {
                  foreach($hndata['chotels'] as $huid => $hname) {
                    echo "<tr>";
                      echo "<td style=\"width: 20px;\"></td>";
                      echo "<td><a href=\"".$href.$huid."\">".$hname."</a></td>";
                    echo "</tr>";
                  }
              }
          }
          ?>
      </tbody>
    </table>
</div>
