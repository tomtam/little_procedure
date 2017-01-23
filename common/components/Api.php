<?php

namespace common\components;

use Yii;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Api {

    const EXCL_HEAD = TRUE; //不含http头
    const INCL_HEAD = TRUE;  //含http头
    const CURL_TIMEOUT = 20;

    static function postCurl($target, $data_array = "") {
        return self::http($target, $method = "POST", $data_array, self::EXCL_HEAD);
    }

    static function http($target, $method, $data_array, $flag, $is_login = false) {

        $client_ip = XUtils::getClientIP();
        $for_ip = getenv("HTTP_X_FORWARDED_FOR");

        $ch = curl_init();
        $query_string = '';

        if (is_array($data_array)) {
            $query_string = http_build_query($data_array);
        }
        # HEAD method configuration
        if ($method == 'HEAD') {
            curl_setopt($ch, CURLOPT_HEADER, TRUE);                           // No http head
            curl_setopt($ch, CURLOPT_NOBODY, TRUE);                           // Return body， 关闭body
        } else {
            # GET method configuration
            if ($method == 'GET') {
                if (isset($query_string))
                    $target = $target . "?" . $query_string;
                curl_setopt($ch, CURLOPT_HTTPGET, TRUE);
                curl_setopt($ch, CURLOPT_POST, FALSE);
            }
            # POST method configuration
            if ($method == 'POST') {
                if (isset($query_string))
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $query_string);
                curl_setopt($ch, CURLOPT_POST, TRUE);
                curl_setopt($ch, CURLOPT_HTTPGET, FALSE);
            }
        }

        $ip_arr = array("X-FORWARDED-FOR:" . $for_ip, "CLIENT-IP:" . $client_ip);

        curl_setopt($ch, CURLOPT_AUTOREFERER, 0);
        //curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
        //curl_setopt($ch, CURLOPT_COOKIEJAR,  $cookie_file_path);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $ip_arr); //IP
//        curl_setopt($ch, CURLOPT_COOKIE, $cookie_core_sys);
        curl_setopt($ch, CURLOPT_HEADER, TRUE);                               // Include head as needed
        curl_setopt($ch, CURLOPT_NOBODY, FALSE);                              // Return body
        curl_setopt($ch, CURLOPT_TIMEOUT, self::CURL_TIMEOUT);                // Timeout
        curl_setopt($ch, CURLOPT_URL, $target);                               // Target site
        curl_setopt($ch, CURLOPT_VERBOSE, FALSE);                             // Minimize logs
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);                      // No certificate
        //if (ini_get('open_basedir') == '' && ini_get('safe_mode' == 'Off')){
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);                    // 使用自动跳转
        //}
        curl_setopt($ch, CURLOPT_MAXREDIRS, 4);                               // Limit redirections to four
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);                       // Return in string

        curl_setopt($ch, CURLINFO_HEADER_OUT, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Length: ' . strlen($query_string)));
        # Create return array
        $data = curl_exec($ch);
        $info = curl_getinfo($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        if ($info['http_code'] == 200) {
            $data = substr($data, $header_size);
        } else {
            $data = json_encode($info);
        }

        curl_close($ch);
        return $data;
    }

}
