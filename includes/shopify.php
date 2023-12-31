<?php

class Shopify
{

    public $shop_url;
    public $access_token;


    public function set_url($url)
    {
        $this->shop_url = $url;
    }

    public function set_token($token)
    {

        $this->access_token = $token;
    }


    public function get_url()
    {

        return $this->shop_url;

    }
    public function get_token()
    {

        return $this->access_token;

    }


    public function rest_api($api_endpoint, $query = array(), $method = 'GET')
    {
        $url = "https://" . $this->shop_url . $api_endpoint;

        if (in_array($method, array("GET", "DELETE")) && !is_null($query)) {
            $url = $url . "?" . http_build_query($query);
        }


        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_MAXREDIRS, 3);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);

        $header[] = "";

        if (!is_null($this->access_token)) {
            $header[] = "X-Shopify-Access-Token:" . $this->access_token;

            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        }

        if ($method != "GET" && in_array($method, array("POST", "PUT"))) {
            if (is_array($query))
                $query = http_build_query($query);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $query);

        }

        $respoonse = curl_exec($curl);
        $error = curl_errno($curl);
        $error_msg = curl_error($curl);

        if ($error) {
            return $error_msg;

        } else {
            $respoonse = preg_split("/\r\n\r\n|\n\n|\r\r/", $respoonse, 2);


            $header = array();
            $header_content = explode("\n", $respoonse[0]);
            $headers['status'] = $header_content[0];

            array_shift($header_content);

            foreach ($header_content as $content) {
                $data = explode(":", $content);
                $header[trim($data[0])] = trim($data[1]);


            }
            return array("headers" => $headers, "body" => $respoonse[1]);

        }


    }


}