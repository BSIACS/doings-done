<?php

require_once './helpers.php';


$DB_CONF = require_once './db-conf.php';

try {
    $link = mysqli_connect($DB_CONF['host'], $DB_CONF['user'], $DB_CONF['password'], $DB_CONF['database']);
    mysqli_set_charset($link, "utf8");
} catch (Throwable $throwable) {
    redirectToErrorPage500();
}
