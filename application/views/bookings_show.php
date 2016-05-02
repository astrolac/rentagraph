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
          <td style="min-width: 170px;">Наименование отеля</td>
          <?php foreach ($datesarray as $dateitem) { ?>
                <td style="min-width: 40px; height: 40px;"><?php
                    echo "<u>".substr($dateitem, -2)."/".substr($dateitem, 5, 2)."</u><br />".substr($dateitem, 0, 4);
                ?></td>
          <?php } ?>
        </tr>
      </thead>
      <tbody>
          <?php foreach ($finish as $huid => $hoteldata) { ?>
          <tr>
              <td style="min-width: 170px;">
                  <a href="<?php
                      echo $this->config->item('base_url')."index.php/booking/bookings_by_hotel/".$huid;
                      ?>"><?php echo $hotelsname[$huid]; ?></a>
              </td>
              <?php foreach ($datesarray as $dateitem) { ?>
                  <td class="booking" style="min-width: 40px; height: 40px; <?php
                      $resstr = "";
                      switch(count($hoteldata[$dateitem])) {
                          case 1:   echo "background-color: lime;\">";
                                    if($hoteldata[$dateitem][0]['byowner'] == 'on') {
                                        $resstr = $hoteldata[$dateitem][0]['buid']."(БВ)";
                                    } else {
                                        $resstr = $hoteldata[$dateitem][0]['buid'];
                                    }
                                    break;
                          case 2:   echo "background-color: lime;\">";
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
                          case 0:   echo "border: 1px solid black;\">";
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
