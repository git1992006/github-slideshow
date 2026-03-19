<?php
require_once 'includes/functions.php';
session_unset();
session_destroy();
session_start();
setFlash('flash_success', 'You have been logged out successfully.');
header('Location: login.php');
exit;
