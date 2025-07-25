<?php
session_start();

// Set session untuk testing langsung ke selectlayout
$_SESSION['payment_start_time'] = time();
$_SESSION['payment_expired_time'] = time() + (15 * 60);
$_SESSION['payment_completed'] = true;
$_SESSION['session_type'] = 'layout_selection';

// Auto redirect ke selectlayout
header("Location: selectlayout.php");
exit();
?>
