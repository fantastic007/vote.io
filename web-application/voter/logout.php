<?php
session_start();
session_destroy();
header('location: /hk_project/index.php');
?>