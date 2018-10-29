<?php
/**
 * Create a curl helper
 * where are cacErt ? @link http://unitstep.net/blog/2009/05/05/using-curl-in-php-to-access-https-ssltls-protected-sites/
 */

namespace App\Helper;

use GoMoney\ErrorException;

class ApiHandler
{
    /**
     * @param string $url
     * @param array $params
     * @param string $cacErt
     * @return mixed
     * @throws ErrorException
     */
    static function get(string $url, array $params = [], string $cacErt = '')
    {
        return self::_do_request($url, $params, $cacErt, false);
    }

    /**
     * @param string $url
     * @param array $params
     * @param string $cacErt
     * @return mixed
     * @throws ErrorException
     */
    static function post(string $url, array $params = [], string $cacErt = '')
    {
        return self::_do_request($url, $params, $cacErt, true, false);
    }

    /**
     * @param string $url
     * @param array $params
     * @param string $cacErt
     * @return mixed
     * @throws ErrorException
     */
    static function put(string $url, array $params, string $cacErt = '')
    {
        return self::_do_request($url, $params, $cacErt, false, true);
    }

    /**
     * @param string $url
     * @param array $params
     * @param string $cacErt
     * @param bool $is_post
     * @param bool $is_put
     * @return mixed
     * @throws ErrorException
     */
    private static function _do_request(string $url, array $params, string $cacErt = '', $is_post = false, $is_put = false)
    {
        if (!$is_post) {
            if ($params) {
                $p_str = '';
                $comma = '';
                foreach ($params as $k => $v) {
                    $p_str .= $comma . $k . '=' . $v;
                    $comma = '&';
                }

                $url = $url . '?' . $p_str;
            }
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0); // 过滤HTTP头
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);//SSL证书认证
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);//严格认证

        if ($cacErt) {
            curl_setopt($ch, CURLOPT_CAINFO, $cacErt);//证书地址    
        }

        if ($is_post) {
            curl_setopt($ch, CURLOPT_POST, true); // post传输数据
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));// post传输数据
        }

        if ($is_put) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        }

        $output = curl_exec($ch);
        if ($output === FALSE) {
            // error log
            throw new ErrorException("cURL Error: " . curl_error($ch), curl_errno($ch));
        }

        curl_close($ch);

        return $output;
    }
}
