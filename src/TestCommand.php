<?php
require_once 'DataBaseManager.php';

$dataBaseManager = new DataBaseManager();
$data = $dataBaseManager->select('name')->from('user', 'u')->order_by('u.id');//->getResult();
var_dump($data);





