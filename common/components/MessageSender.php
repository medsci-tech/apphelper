<?php
/**
 * Created by PhpStorm.
 * User: 觐松
 * Date: 2016/4/11
 * Time: 10:51
 */

namespace common\components;

class MessageSender {

    /**
     * @param int $start
     * @param int $end
     * @return string
     */
    public static function generateMessageVerify($start = 000000, $end = 999999)
    {
        return sprintf('%06d',random_int($start, $end));
    }

    /**
     * @param $phone
     * @param $verify
     * @return int
     */
    public static function sendMessageVerify($phone, $verify)
    {
        $SMS_KEY="79ea3abafb791ed89571c033bd0c42d1";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://sms-api.luosimao.com/v1/send.json");

        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, 'api:key-' . $SMS_KEY);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS,
            array('mobile' => $phone, 'message' => '您当前的验证码为：' . $verify . '【普安医师助手】'));

        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }
}