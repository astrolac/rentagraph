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
                <td><?php echo $dateitem; ?></td>
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
                                        $resstr = "БР (".$hoteldata[$dateitem][0]['buid'].")";
                                    } else {
                                        $resstr = $hoteldata[$dateitem][0]['buid'];
                                    }
                                    break;
                          case 2:   echo "style=\"background-color: lime;\">";
                                    if($hoteldata[$dateitem][0]['byowner'] == 'on') {
                                        $resstr = "БР (".$hoteldata[$dateitem][0]['buid'].")";
                                    } else {
                                        $resstr = $hoteldata[$dateitem][0]['buid'];
                                    }
                                    $resstr .= "/";
                                    if($hoteldata[$dateitem][1]['byowner'] == 'on') {
                                        $resstr .= "<br />БР (".$hoteldata[$dateitem][1]['buid'].")";
                                    } else {
                                        $resstr .= "<br />".$hoteldata[$dateitem][1]['buid'];
                                    }
                                    break;
                          case 0:   echo ">";
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
