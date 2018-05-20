<?php

session_start();
session_destroy();
setcookie(session_name(), '', time() -1);
header('Location: signin.php');
