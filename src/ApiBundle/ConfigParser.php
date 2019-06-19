<?php

namespace ApiBundle;

class ConfigParser
{
    /** @var array */
    public static $parsedData;

    /**
     * @return mixed
     */
    public static function parseData()
    {
        $parsConfig = file('/var/www/html/PabloFramework/config/configDB', FILE_IGNORE_NEW_LINES);

        foreach ($parsConfig as $key => $value) {
            $parsElement = explode(': ', $value);

            self::$parsedData[$parsElement[0]] = $parsElement[1];
        }

        return ConfigParser::$parsedData;
    }
}

