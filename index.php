<?php

function myAutoLoad($className)
{
    $classPieces = explode("\\", $className);

    switch ($classPieces[0]) {
        case 'ApiBundle':
            include '/var/www/pfr/src/' . implode(DIRECTORY_SEPARATOR, $classPieces) . '.php';
            break;
    }
}

spl_autoload_register('myAutoLoad', 'wtf', true);

$request = ApiBundle\Routing\Singleton::getInstance();
$request->setRequestParams();
$rout = new ApiBundle\Routing\Router();
$response = $rout->handleRequest($request);
$response->sendResponse();





