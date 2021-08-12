<?php

namespace fl\cms\external\reg\base;

use fl\cms\external\base\ApiItemInterfaces;

abstract class Request implements ApiItemInterfaces
{
    public function send(array $params): array
    {
        $params['request_data'] = $this->setRequestData($params);
        $sig_params = $params['request_data'];
        sort($sig_params);
        $pkeyid = openssl_pkey_get_private("file:///home/developer/reg.ru/api.key");
        openssl_sign(implode(';', $sig_params), $sig, $pkeyid);

        $params['request_data']['sig'] = base64_encode($sig);
        $endpoint = $params['request_data'] ['endpoint'];
        $url = $endpoint . '?' . http_build_query($params['request_data']);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSLCERT, "/home/developer/reg.ru/api.crt");
        curl_setopt($curl, CURLOPT_SSLKEY, "/home/developer/reg.ru/api.key");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);

        $result = curl_exec($curl);
        curl_close($curl);
        $r = json_decode(urldecode($result), true);
        print_r($r);
        return  $r;
    }
}