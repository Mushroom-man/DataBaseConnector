<?php
require_once 'DataBaseManager.php';

$dataBaseManager = new DataBaseManager();
$data = $dataBaseManager->select('name')->from('user')->order_by('u.id', 'DESC');//->getResult();






