<?php

require_once 'functions/db.php';
require_once 'functions/template.php';
require_once 'functions/request.php';
require_once 'functions/filters.php';
require_once 'functions/validators/validate-form-registration.php';
require_once 'functions/actions.php';

$config = require 'config.php';

$connection = dbConnect($config['db']);

const APP_DIR = __DIR__;
