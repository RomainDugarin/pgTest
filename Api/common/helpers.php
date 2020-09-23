<?php
namespace PaygreenApiClient;

class helpers 
{
    /**
    * Return method and url by function name
    *
    * @param string $function
    * @param array $datas
    * @return object page
    */
    function requestApi($options, $datas = null)
    {
        $options['http'] = "Authorization: Bearer ".$options['CP'];
        $content         = (!isset($datas['content'])) ? json_encode($datas['content']) : '' ;

        if (extension_loaded('curl')) {
            $page = $this->request_api_curl($options, $content);
        } elseif (ini_get('allow_url_fopen')) {
            $page = $this->request_api_fopen($options, $content);
        } else {
            return ((object)array('error' => 0));
        }

        if ($page === false) {
            return ((object)array('error' => 1));
        }
        return json_decode($page);
    }

    function request_api_curl($options, $content)
    {
        $ch = curl_init();
        curl_setopt_array($ch, array(
            // CURLOPT_SSL_VERIFYPEER => false,
            // CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_URL => $options['URL'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $options['METHOD'],
            CURLOPT_POSTFIELDS => $content,
            CURLOPT_HTTPHEADER => array(
                "accept: application/json",
                $options['HTTP'],
                "cache-control: no-cache",
                "content-type: application/json",
                ),
        ));
        $page = curl_exec($ch);
        curl_close($ch);
        return ($page);
    }

    function request_api_fopen($options, $content)
    {
        $opts = array(
            'http' => array(
                'method'    =>  $options['METHOD'],
                'header'    =>  "Accept: application/json\r\n" .
                "Content-Type: application/json\r\n".
                $options['HTTP'],
                'content'   =>  $content
            )
        );
        $context = stream_context_create($opts);
        $page = @file_get_contents($options['URL'], false, $context);
        return ($page);
    }
}
?>