<?php

namespace ApiBundle\Routing;

/**
 * Trait requestSingleton
 * @package ApiBundle\Routing
 */
trait requestSingleton {

    /**
     * @var null
     */
    private static $instance = NULL;

    /**
     * requestSingleton constructor.
     */
    private function __construct() {
        return self::$instance;
    }

    /**
     * @return requestSingleton|null
     */
    private function __clone() {
        return self::$instance;
    }

    /**
     * @return requestSingleton|null
     */
    private function __wakeup() {
        return self::$instance;
    }

    /**
     * @return requestSingleton|null
     */
    public static function getInstance() {
           if(self::$instance == NULL){
              return self::$instance = new static();
           }

           return self::$instance;
    }
}
