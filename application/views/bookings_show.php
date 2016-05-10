<div class="innermenu">
    <?php
    echo "| ";
    foreach ($innermenu as $key => $value) {
        echo "<a href=\"".$value."\">".$key."</a> | ";
    }
    ?>
</div>
<div id="bookings">
  <table class="bookings">
      <thead>
        <tr>
          <td style="width: 170px;" colspan="2">Наименование отеля</td>
          <?php foreach ($datesarray as $dateitem) { ?>
                <td style="min-width: 40px; height: 40px;"><?php
                    echo "<u>".substr($dateitem, -2)."/".substr($dateitem, 5, 2)."</u><br />".substr($dateitem, 0, 4);
                ?></td>
          <?php } ?>
        </tr>
      </thead>
      <tbody>

        <?php function showhdata($huid, $datesarray, $finish) {
          foreach ($datesarray as $dateitem) {
            echo "<td class=\"booking\" style=\"min-width: 40px; height: 40px;";
                $resstr = "";
                switch(count($finish[$huid][$dateitem])) {
                    case 1:   echo "background-color: lime;\">";
                              if($finish[$huid][$dateitem][0]['byowner'] == 'on') {
                                  $resstr = $finish[$huid][$dateitem][0]['buid']."(БВ)";
                              } else {
                                  $resstr = $finish[$huid][$dateitem][0]['buid'];
                              }
                              break;
                    case 2:   echo "background-color: lime;\">";
                              if($finish[$huid][$dateitem][0]['byowner'] == 'on') {
                                  $resstr = "<u>".$finish[$huid][$dateitem][0]['buid']."(БВ)</u>";
                              } else {
                                  $resstr = "<u>".$finish[$huid][$dateitem][0]['buid']."</u>";
                              }
                              $resstr .= "<br />";
                              if($finish[$huid][$dateitem][1]['byowner'] == 'on') {
                                  $resstr .= $finish[$huid][$dateitem][1]['buid']."(БВ)";
                              } else {
                                  $resstr .= $finish[$huid][$dateitem][1]['buid'];
                              }
                              break;
                    case 0:   echo "border: 1px solid black;\">";
                              break;
                    default:  echo ">err";
                              break;
                }
                echo $resstr;
            echo "</td>";
          }
        } ?>

          <?php foreach ($hotelsname as $huid => $hnamedata) { ?>
          <tr>
              <?php if(count($hnamedata['chotels']) == 0) { ?>
                  <td style="min-width: 170px;" colspan="2">
                      <a href="<?php
                          echo $this->config->item('base_url')."index.php/booking/bookings_by_hotel/".$huid;
                          ?>"><?php echo $hnamedata['hname']; ?></a>
                  </td>
                  <?php showhdata($huid, $datesarray, $finish);
                  } else { ?>
                    <td style="min-width: 170px;" colspan="2">
                        <b><?php echo $hnamedata['hname']; ?></b>
                        <button id="<?php echo $huid; ?>"></button>
                    </td>
                  <?php } ?>
          </tr>
          <?php  if (count($hnamedata['chotels']) > 0) {
                  foreach ($hnamedata['chotels'] as $chuid => $chnamedata) { ?>
                    <tr name=<?php echo $huid; ?>>
                        <td style="width: 20px;"></td>
                        <td style="width: 150px;">
                            <a href="<?php
                                echo $this->config->item('base_url')."index.php/booking/bookings_by_hotel/".$chuid;
                                ?>"><?php echo $chnamedata; ?></a>
                        </td>
                        <?php showhdata($chuid, $datesarray, $finish); ?>
                    </tr>
                  <?php } ?>
                <?php } ?>
              <?php } ?>

      </tbody>
  </table>

  <script type="text/javascript">
      var funcPool = [];
      var funcCounter = 0;
      <?php
          foreach ($hotelsname as $huid => $hnamedata) {
              if(count($hnamedata['chotels']) > 0) { ?>
                  funcPool[funcCounter] = function () {
                      childRows = document.getElementsByName("<?php echo $huid; ?>");
                      if (childRows[0].style.display == 'none') {
                          for (var ci = 0; ci < childRows.length; ci++) {
                              childRows[ci].style.display = '';
                          }
                      } else {
                          for (var ci = 0; ci < childRows.length; ci++) {
                              childRows[ci].style.display = 'none';
                          }
                      }
                  }
                  document.getElementById("<?php echo $huid; ?>").addEventListener('click', funcPool[funcCounter]);
                  funcCounter++;
              <?php  }
          }
      ?>
  </script>
</div>
