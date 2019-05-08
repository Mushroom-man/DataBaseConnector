<?php
require_once 'DataBaseManager.php';

$dataBaseManager = new DataBaseManager();
//$data = $dataBaseManager->select('name')->from('user', 'u')->order_by('u.id');//->getResult();
//$data = $dataBaseManager->insert('user')->values("14, 'nub'")->exec();
//$data = $dataBaseManager->update('user')->set("name = 'wtf2'")->exec();
//$data = $dataBaseManager->update('user')->set("name = 'wtf3'")->orWhere('id = 4', 'id = 3')->exec();
//$data = $dataBaseManager->update('user')->set("name = 'shit'")->inWhere('id', '0, 1, 2' )->exec();

//$data = $dataBaseManager->select('name')->andWhere("id >= 7")->order_by('u.id', 'DESC')->from('user', 'u')->getQuery()->prepare()->execute();

//$data = $dataBaseManager->insert('user', 'name')->values("'Illidan'")->getQuery()->prepare()->execute();

//$data = $dataBaseManager->update('user')->set("name = 'Arthas'")->where('id <= 3')->getQuery()->prepare()->execute();

$data = $dataBaseManager->delete('user')->where('id > 8')->where("name = 'Illidan'")->getQuery()->prepare()->execute();

var_dump($data);
