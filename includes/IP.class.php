<?php
/**
 * Created by PhpStorm.
 * User: su
 * Date: 2016/6/8
 * Time: 10:13
 */

class IP
{
    static private $instance = null;
    private function __construct()
    {

    }

    public static function getIPinstance()
    {
        if(is_null(self::$instance))
        {
            self::$instance = new self;
        }
        return self::$instance;
    }

    private function __clone()
    {
        // TODO: Implement __clone() method.
    }

    private function getIP()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            //如果变量是非空或非零的值，则 empty()返回 FALSE。
            $IP = explode(',',$_SERVER['HTTP_CLIENT_IP']);
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $IP = explode(',',$_SERVER['HTTP_X_FORWARDED_FOR']);
        }
        elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            $IP = explode(',',$_SERVER['REMOTE_ADDR']);
        }
        else {
            $IP[0] = 'None';
        }
        return $IP[0];
    }

    private function getIPAddress()
    {
        $ip = $this->getIP();
        $ch = curl_init();
        $url = 'http://apis.baidu.com/showapi_open_bus/ip/ip?ip='.$ip;
        $header = array(
            'apikey: 64fb3b66208e9a744231b2f985ad03ad',
        );
        // 添加apikey到header
        curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // 执行HTTP请求
        curl_setopt($ch , CURLOPT_URL , $url);
        $res = curl_exec($ch);
        $result = json_decode($res);
        return $result->showapi_res_body;

    }

    public function printfIPandAddress()
    {
        $result = $this->getIPAddress();
        if(!isset($result->ret_code))
        {
            return $result;
        }
        else
        {
            return '-1';
        }
    }

}