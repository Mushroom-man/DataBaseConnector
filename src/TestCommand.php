<?php
require_once 'DataBaseManager.php';

$dataBaseManager = new DataBaseManager();
//$data = $dataBaseManager->select('name')->from('user', 'u')->order_by('u.id');//->getResult();
//$data = $dataBaseManager->insert('user')->values("14, 'nub'")->exec();
//$data = $dataBaseManager->update('user')->set("name = 'wtf2'")->exec();
//$data = $dataBaseManager->update('user')->set("name = 'wtf3'")->orWhere('id = 4', 'id = 3')->exec();
//$data = $dataBaseManager->update('user')->set("name = 'shit'")->inWhere('id', '0, 1, 2' )->exec();

$data = $dataBaseManager->select('*')->andWhere("id >= 7")->order_by('u.id', 'DESC')->from('user', 'u')->andWhere("name = 'wtf2'")->prepare()->execute();//->getResult();

var_dump($data);
