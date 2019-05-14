<?php

class ConfigParser
{
    /**
     * @var
     */
    public static $parsedData;

    /**
     *
     */
    public static function parseData()
    {
        $parsConfig = file('/var/www/html/DataBaseConnector/src/configDB', FILE_IGNORE_NEW_LINES);

        foreach ($parsConfig as $key => $value) {
            $parsElement = explode(': ', $value);

            self::$parsedData[$parsElement[0]] = $parsElement[1];
        }

        return;
    }
}

//ConfigParser::parseData();
//var_dump(ConfigParser::$parsedData);

