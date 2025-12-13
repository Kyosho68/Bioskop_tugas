<?php
session_start();
session_destroy();
header('Location: ../XHTML/log_in.xml');
exit();
?>