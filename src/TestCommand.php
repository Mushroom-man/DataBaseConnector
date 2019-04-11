<?php
require_once 'DataBaseManager.php';

$dataBaseManager = new DataBaseManager();
$data = $dataBaseManager->select('name')->from('user')->getResult();
var_dump($data);