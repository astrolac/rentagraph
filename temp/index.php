<?php
session_start();
if (!isset($_SESSION['username'])) {
    echo "New session!";
    $_SESSION['username']="astrolac";
} else {
    echo "Session is ".$_SESSION['username'];
    foreach($_SESSION as $sesskey => $sessvalue) {
        echo "Ключ = ".$sesskey."\t Значение = ".$sessvalue."<br />";
    }
    unset($_SESSION['username']);
    session_destroy();
}
