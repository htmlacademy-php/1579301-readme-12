<?php

$config = require 'config.php';

$connection = dbConnect($config['db']);

const APP_DIR = __DIR__;