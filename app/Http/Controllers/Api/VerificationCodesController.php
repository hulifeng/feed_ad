<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class VerificationCodesController extends Controller
{
    public function getAccessToken()
    {
        Cache::put('refresh_access_token', '219a80904629b4edc2bf123c086792517ffbb6be');
    }

    public function refreshAccessToken()
    {
        $url = 'https://ad.toutiao.com/open_api/oauth2/refresh_token/';

        $body = [
            'app_id' => env('AD_APP_ID'),
            'secret' => env('AD_APP_SECRET'),
            'grant_type' => 'refresh_token',
            'refresh_token' => Cache::get('refresh_access_token'),
        ];

        $res = $this->getRequest($url, $body, [], 'POST');

        $json = json_decode($res);

        Cache::put('refresh_access_token', $json->data->refresh_token);

        return response($res);
    }

    public function getRequest($url, $option = [], $header = [], $type = 'GET')
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 对证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); // 对证书中既爱奶茶SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0; Trident/4.0)');

        // 模拟用户使用的浏览器
        if (!empty($option)) {
            $option = json_encode($option);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $option); // POST 提交数据包
        }
        if (empty($header)) {
            $header = array('Content-Type: application/json');
        }
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header); // 设置请求头
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取文件信息以文件流形式返回
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $type);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }
}
