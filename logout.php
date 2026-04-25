<?php
session_start();
session_destroy();
header("Location: MiServer.html");
exit();
?>