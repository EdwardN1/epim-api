<?php
function make_api_call($url)
{
    $method = get_option('epim_api_retrieval_method');
    if ($method == 'curl') {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        /*curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);*/

        /*$proxy = 'https://proxy.sgti.lbn.fr:4480';
        curl_setopt($ch, CURLOPT_PROXY, $proxy);*/

        $headers = array();
        $headers[] = "Ocp-Apim-Subscription-Key: ".get_option('epim_key');

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $apiCall = curl_exec($ch);

        curl_close($ch);

        return $apiCall;
    } else {
        $opts = array(
            'http' => array(
                'method' => "GET",
                'header' => "Ocp-Apim-Subscription-Key: ".get_option('epim_key')
            )
        );
        $context = stream_context_create($opts);
        $apiCall = file_get_contents($url, false, $context);

        return $apiCall;
    }

}