<?php
if (!isset($_SESSION)) {
    session_start();
}
session_destroy();
header("location: ../pagineWeb/login.php?message=logout fatto");
exit();
