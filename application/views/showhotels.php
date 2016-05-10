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
              <td colspan="2">Наименование</td>
              <td>Тип</td>
              <td>Контактное лицо</td>
              <td>Телефон</td>
              <td>Адрес</td>
              <td>Комментарии</td>
              <td>Комиссия в %</td>
              <td>Фиксированная комиссия</td>
              <td>UID</td>
              <td>Действия</td>
          </tr>
        </thead>
        <tbody><?php
              foreach($hnames as $huid => $hndata) {
                echo "<tr>";
                  if($hotelsarray[$huid]['isactive'] == 1) {
                      echo "<td colspan=\"2\">".$hndata['hname']."</td>";
                  } else {
                      echo "<td colspan=\"2\"><del>".$hndata['hname']."</del></td>";
                  }
                  echo "<td>".$hotelsarray[$huid]['htype']."</td>";
                  echo "<td>".$hotelsarray[$huid]['person']."</td>";
                  echo "<td>".$hotelsarray[$huid]['personphone']."</td>";
                  echo "<td>".$hotelsarray[$huid]['haddress']."</td>";
                  echo "<td>".$hotelsarray[$huid]['hcomments']."</td>";
                  echo "<td class=\"numeric\">".$hotelsarray[$huid]['percentfee']."</td>";
                  echo "<td class=\"numeric\">".$hotelsarray[$huid]['fixedfee']."</td>";
                  echo "<td class=\"numeric\">".$huid."</td>";
                  echo "<td>";
                      /*foreach($href as $actdata) {*/
                          echo "[<a href=\"".$href['edit']['href'].$huid."\">".$href['edit']['text']."</a>]";
                          echo "[<a href=\"".$href['block']['href'].$huid."\">".$href['block']['text']."</a>]";
                          if($hotelsarray[$huid]['isactive'] == 0) {
                              echo "[<a href=\"".$href['rev']['href'].$huid."\">".$href['rev']['text']."</a>]";
                          }
                      /*}*/
                  echo "</td>";
                echo "</tr>";
                  if(count($hndata['chotels']) > 0) {
                      foreach($hndata['chotels'] as $huid => $hname) {
                        echo "<tr>";
                          echo "<td style=\"min-width: 20px;\"></td>";
                          if($hotelsarray[$huid]['isactive'] == 1) {
                              echo "<td>".$hname."</td>";
                          } else {
                              echo "<td><del>".$hname."</del></td>";
                          }
                          echo "<td>".$hotelsarray[$huid]['htype']."</td>";
                          echo "<td>".$hotelsarray[$huid]['person']."</td>";
                          echo "<td>".$hotelsarray[$huid]['personphone']."</td>";
                          echo "<td>".$hotelsarray[$huid]['haddress']."</td>";
                          echo "<td>".$hotelsarray[$huid]['hcomments']."</td>";
                          echo "<td class=\"numeric\">".$hotelsarray[$huid]['percentfee']."</td>";
                          echo "<td class=\"numeric\">".$hotelsarray[$huid]['fixedfee']."</td>";
                          echo "<td class=\"numeric\">".$huid."</td>";
                          echo "<td>";
                              /*foreach($href as $actdata) {*/
                                  echo "[<a href=\"".$href['edit']['href'].$huid."\">".$href['edit']['text']."</a>]";
                                  echo "[<a href=\"".$href['block']['href'].$huid."\">".$href['block']['text']."</a>]";
                                  if($hotelsarray[$huid]['isactive'] == 0) {
                                      echo "[<a href=\"".$href['rev']['href'].$huid."\">".$href['rev']['text']."</a>]";
                                  }
                              /*}*/
                          echo "</td>";
                        echo "</tr>";
                      }
                  }
              }
          ?>
        </tbody>
    </table>
</div>
