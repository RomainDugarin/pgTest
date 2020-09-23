<?php
namespace PaygreenApiClient;

// Get configuration file
require_once '../conf/config.php';

// Includ api helpers functions
require_once 'common/helpers.php';

/**
 * Class ConnexionServices
 * Contient les différents services de connexion
 */
class ConnexionServices extends Service
{

    /**
    * Authentication to server paygreen
    *
    * @param string $email email of account paygreen
    * @param string $name name of shop
    * @param string $phone phone number, can be null
    * @param string $ipAdress ip Adress current, if null autodetect
    * @return string json datas
    *
    */
    function getOAuthServerAccess($email, $name, $phone = null, $ipAddress = null) 
    {
        // set Request Options (path, params,...)
        $options = $this->getOptions();

        // set Request Data
        $ipAddress = !isset($ipAddress) ?  $ipAddress = $_SERVER['ADDR'] : false ;
        $datas['content'] = array(
            "ipAddress" => $ipAddress,
            "email" => $email,
            "name" => $name
        );
        try {
            $this->response['data'] = $this->requestApi($options, $datas);
            $this->response['success'] = 1;
        } catch (\Throwable $th) {
            $this->response = new ErrorResponse("Error: Authentication to server paygreen");
        }
        return $this->response;
    }

    /**
    * return url of Authentication
    * 2
    * @return string url of Authentication
    */
    private function getOAuthDeclareEndpoint()
    {
        return $this->host;
    }

    /**
    * 3
    * return url of Authorization
    * @return string url of Authorization
    */
    public function getOAuthAutorizeEndpoint() 
    {
        return $this->host.URL_AUTHORIZATION;
    }
    
}

?>