<?php
function get_bookings($huid, $datein = FALSE, $dateout = FALSE) {
    $querystr = "SELECT * from bookings WHERE huid=".$huid;
    if ($datein) {
        $querystr .=" AND (dateout BETWEEN '".$datein."' AND '2100-12-31')";
    }
    if ($dateout) {
        $querystr .=" AND (datein BETWEEN '1900-01-01' AND '".$dateout."')";
    }
    $querystr .=";";


}

get_bookings(1,'2016-05-01','2016-05-01');
