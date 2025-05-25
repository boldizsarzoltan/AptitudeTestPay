<?php
require_once('vendor/autoload.php');
$container = new Paysera\CommissionTask\Container();
$container->runOperations("./input.csv");