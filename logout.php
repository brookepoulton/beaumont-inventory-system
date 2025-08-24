<?php

/*
    * Class: csci303sp23
    * User: bpoulton
    * Date: 10/24/2024
    * Time: 10:50 AM
 */
session_start();

session_unset();
session_destroy();
header("Location: confirm.php?state=1");