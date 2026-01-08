<?php
require_once '../Model/config.php';
require_once '../Model/auth.php';

logoutUser();

redirect('login.php');
?>