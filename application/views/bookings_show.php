<div class="innermenu">
    <?php $this->load->helper('url'); ?>
    <script src="<?php echo base_url(); ?>noCI_lib/datepicker/tcal.js" type="text/javascript"></script>
    <form id="period" action="<?php echo base_url(); ?>index.php/booking/bookings_by_period" method="post">
        <input type="text" name="datestart" class="tcal icalInput" value="<?php echo $period['datestart']; ?>" />
        <input type="text" name="dateend" class="tcal icalInput" value="<?php echo $period['dateend']; ?>" />
        <button class="innermenubutton" type="submit" form="period">Отобразить за период</button>
    </form>
    <?php
    foreach ($innermenu as $key => $value) {
        echo "<a href=\"".$value."\"><button class=\"innermenubutton\">".$key."</button></a> ";
    }
    ?>
</div>
<div id="bookings">
  <table class="bookings">
      <thead>
        <tr>
          <th style="width: 250px;" colspan="2">Наименование отеля</th>
          <?php foreach ($datesarray as $dateitem) { ?>
                <th style="min-width: 40px; height: 40px;"><?php
                    echo "<u>".substr($dateitem, -2)."/".substr($dateitem, 5, 2)."</u><br />".substr($dateitem, 0, 4);
                ?></th>
          <?php } ?>
        </tr>
      </thead>
      <tbody>

        <?php function showhdata($huid, $datesarray, $finish, $baseurl) {
          foreach ($datesarray as $dateitem) {
            echo "<td class=\"booking\" style=\"min-width: 40px; height: 40px;";
                  $resstr = "";
              /*  Проверим сколько параметров нам передано. Если один, то значит на эту дату только одна бронь начинается/заканчивается,
                  если 2, то в этот день одна бронь заканчивается, а другая начинается.*/
                switch(count($finish[$huid][$dateitem])) {
                    case 1:   echo "background-color: lime;\">";
                             /*  Сначала добавим начальный тег для ссылки отображения брони. */
                              $resstr .= "<a href=\"".$baseurl."index.php/booking/booking_edit/".$finish[$huid][$dateitem][0]['buid']."\">";                            /*  Теперь вставим или чистый номер брони, или с добавкой признака брони владельца. */
                              if($finish[$huid][$dateitem][0]['byowner'] == 'on') {
                                  $resstr .= $finish[$huid][$dateitem][0]['buid']."(БВ)";
                              } else {
                                  $resstr .= $finish[$huid][$dateitem][0]['buid'];
                              }
                            /*  Закрывающий тег ссылки. */
                              $resstr .= "</a>";
                              break;
                    case 2:   echo "background-color: lime;\">";
                             /*  Сначала добавим начальный тег для ссылки отображения брони. */
                              $resstr .= "<a href=\"".$baseurl."index.php/booking/booking_edit/".$finish[$huid][$dateitem][0]['buid']."\">";                            /*  Теперь вставим или чистый номер брони, или с добавкой признака брони владельца. */
                              if($finish[$huid][$dateitem][0]['byowner'] == 'on') {
                                  $resstr .= $finish[$huid][$dateitem][0]['buid']."(БВ)";
                              } else {
                                  $resstr .= $finish[$huid][$dateitem][0]['buid'];
                              }
                            /*  Закрывающий тэг для ссылки и перенос строки. */
                              $resstr .= "</a>";
                              $resstr .= "<hr class=\"inbooktab\">";
                            /*  Теперь откроем вторую ссылку. */
                              $resstr .= "<a href=\"".$baseurl."index.php/booking/booking_edit/".$finish[$huid][$dateitem][1]['buid']."\">";
                              if($finish[$huid][$dateitem][1]['byowner'] == 'on') {
                                  $resstr .= $finish[$huid][$dateitem][1]['buid']."(БВ)";
                              } else {
                                  $resstr .= $finish[$huid][$dateitem][1]['buid'];
                              }
                            /*  Закрывающий тег ссылки. */
                              $resstr .= "</a>";
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

          <?php
        /*  Для каждого переданного нам отеля проверим есть ли у него дочернии отели. Если нет, то просто обрамляем его в ссылку
            для отображения всех броней отеля. Если есть, то для родителя ссылку не делаем, но следом выводим все дочернии и уже
            дочернии обрамляем в ссылку.
            Для каждого отеля обрамлённого в ссылку запускаем функцию отображения его данных о бронях.
        */
          foreach ($hotelsname as $huid => $hnamedata) { ?>
          <tr>
              <?php if(count($hnamedata['chotels']) == 0) { ?>
                  <td style="min-width: 250px;" colspan="2">
                      <a href="<?php
                          echo $this->config->item('base_url')."index.php/booking/bookings_by_hotel/".$huid;
                          ?>"><?php echo $hnamedata['hname']; ?></a>
                  </td>
                  <?php showhdata($huid, $datesarray, $finish, $this->config->item('base_url'));
                  } else { ?>
                    <td style="min-width: 230px;" colspan="2">
                        <b><?php echo $hnamedata['hname']; ?></b>
                        <button id="<?php echo $huid; ?>"></button>
                    </td>
                  <?php } ?>
          </tr>
          <?php  if (count($hnamedata['chotels']) > 0) {
                  foreach ($hnamedata['chotels'] as $chuid => $chnamedata) { ?>
                    <tr name=<?php echo $huid; ?>>
                        <td style="width: 20px;"></td>
                        <td style="width: 230px;">
                            <a href="<?php
                                echo $this->config->item('base_url')."index.php/booking/bookings_by_hotel/".$chuid;
                                ?>"><?php echo $chnamedata; ?></a>
                        </td>
                        <?php showhdata($chuid, $datesarray, $finish, $this->config->item('base_url')); ?>
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
