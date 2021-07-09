#!/usr/local/bin/php
<?php
    session_start();
    $_SESSION = array();//$_SESSION to empty array for good measure
    session_destroy();
?>