<div class="innermenu">
    <?php
    echo "| ";
    foreach ($innermenu as $key => $value) {
        echo "<a href=\"".$value."\">".$key."</a> | ";
    }
    ?>
</div>
<div id="bookings">
  <table class="hotels">
      <thead>
        <tr>
          <td>Наименование отеля</td>
          <?php foreach ($datesarray as $dateitem) { ?>
                <td><?php
                    echo "<u>".substr($dateitem, -2)."/".substr($dateitem, 5, 2)."</u><br />".substr($dateitem, 0, 4);
                ?></td>
          <?php } ?>
        </tr>
      </thead>
      <tbody>
          <?php foreach ($finish as $huid => $hoteldata) { ?>
          <tr>
              <td><?php echo $hotelsname[$huid]; ?></td>
              <?php foreach ($datesarray as $dateitem) { ?>
                  <td class="booking"<?php
                      $resstr = "";
                      switch(count($hoteldata[$dateitem])) {
                          case 1:   echo "style=\"background-color: lime;\">";
                                    if($hoteldata[$dateitem][0]['byowner'] == 'on') {
                                        $resstr = $hoteldata[$dateitem][0]['buid']."(БВ)";
                                    } else {
                                        $resstr = $hoteldata[$dateitem][0]['buid'];
                                    }
                                    break;
                          case 2:   echo "style=\"background-color: lime;\">";
                                    if($hoteldata[$dateitem][0]['byowner'] == 'on') {
                                        $resstr = "<u>".$hoteldata[$dateitem][0]['buid']."(БВ)</u>";
                                    } else {
                                        $resstr = "<u>".$hoteldata[$dateitem][0]['buid']."</u>";
                                    }
                                    $resstr .= "<br />";
                                    if($hoteldata[$dateitem][1]['byowner'] == 'on') {
                                        $resstr .= $hoteldata[$dateitem][1]['buid']."(БВ)";
                                    } else {
                                        $resstr .= $hoteldata[$dateitem][1]['buid'];
                                    }
                                    break;
                          case 0:   echo "style=\"border: 1px solid black;\">";
                                    break;
                          default:  echo ">err";
                                    break;
                      }
                      echo $resstr;
                  ?></td>
              <?php } ?>
          </tr>
          <?php } ?>
      </tbody>
  </table>
</div>
