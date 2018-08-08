<?php

function api_call_post($url, $data) {
    // use key 'http' even if you send the request to https://...
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
        )
    );
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    if ($result === FALSE) { 
        /* Handle error */ 
        throw new Exception('API call error!');
    }
    return $result;
}

function call_api_using_curl($msisdn, $msg) {
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => "http://api.infobip.com/sms/1/text/single",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "{ \"from\":\"01111111111\", \"to\":\"$msisdn\", \"text\":\"$msg\" }",
      CURLOPT_HTTPHEADER => array(
        "accept: application/json",
        "authorization: Basic AUTHSTRING",
        "content-type: application/json"
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
      var_dump($response);
    }
}

?>