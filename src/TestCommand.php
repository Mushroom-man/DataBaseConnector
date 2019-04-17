<?php
require_once 'DataBaseManager.php';

$dataBaseManager = new DataBaseManager();
//$data = $dataBaseManager->select('name')->from('user', 'u')->order_by('u.id');//->getResult();
$data = $dataBaseManager->insert('user', 'id, name')->values("8, 'new_user'")->exec();

var_dump($data);





