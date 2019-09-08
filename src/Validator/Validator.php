<?php


namespace OsImportTags\Validator;


class Validator
{
    /**
     * @param string $url
     * @return bool
     */
    public static function isURL(string $url ) : bool
    {
        if(!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }
        $headers = @get_headers($url);

        if(!strpos($headers[0],'200')) {
            return false;
        }

        return true;
    }

    /**
     * @param string $string
     * @return bool
     */
    public static function isString(string $string) : bool
    {
        if(!is_string($string)) {
            return false;
        }

        return true;
    }

}