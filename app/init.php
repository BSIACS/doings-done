<?php

$DB_CONF = require_once './db-conf.php';

$link = mysqli_connect($DB_CONF['host'], $DB_CONF['user'], $DB_CONF['password'], $DB_CONF['database']);  // The first argument of mysqli_connect needs to be the hostname of your DB server (not port), which in docker-compose is the service name. So you need:
mysqli_set_charset($link, "utf8");