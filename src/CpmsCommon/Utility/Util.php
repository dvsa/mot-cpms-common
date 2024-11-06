<?php

namespace CpmsCommon\Utility;

/**
 * Class Util
 *
 * @package CpmsCommon\Utility
 */
class Util
{
    /**
     * Method to append any additional data to the clientUrl
     *
     * @param string $url
     * @param array  $requiredParams
     * @param string $separator
     *
     * @return string
     */
    public static function appendQueryString($url, array $requiredParams = null, $separator = '&')
    {
        if (!empty($url) and stripos($url, 'http') !== 0) {
            $url = 'http://' . $url;
        }

        if (empty($requiredParams)) {
            return $url;
        }

        if (strpos($url, '?') === false) {
            return $url . '?' . http_build_query($requiredParams);
        } else {
            return $url . $separator . http_build_query($requiredParams);
        }
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    public static function deleteDir($path)
    {
        if (is_dir($path) === true) {
            $files = array_diff(scandir($path), array('.', '..'));

            foreach ($files as $file) {
                self::deleteDir(realpath($path) . '/' . $file);
            }

            return rmdir($path);
        } else {
            if (is_file($path) === true) {
                return unlink($path);
            }
        }

        return false;
    }
}
