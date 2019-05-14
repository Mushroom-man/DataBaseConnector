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
    public static function parsingData()
    {
        $parsedConfig = file('/var/www/html/DataBaseConnector/src/configDB', FILE_IGNORE_NEW_LINES);

        foreach ($parsedConfig as $key => $value) {
            $parsedElement = explode(' ', $value);

            self::$parsedData[$parsedElement[0]] = $parsedElement[1];
        }

        return;
    }
}

