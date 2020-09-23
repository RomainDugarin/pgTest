<?php

namespace PaygreenApiClient;

// Get configuration file
require_once '../conf/config.php';

// Includ api helpers functions
require_once 'common/helpers.php';

/**
 * Class Services
 * Contient les différents services de propriété à la plateforme Paygreen
 */
class Service extends helpers
{
    /**
     * Chemin vers le serveur
     */
    private $host;

    /**
     * @var array|null Object contenant la réponse du service demandé
     */
    private $response;

    /**
     * @var string
     */
    private $method = '';

    /**
     * @var array Object contenant la réponse du service demandé
     */
    private $options = array();
   
    /**
     * Constructeur de la classe initialisant la connexion et la réponse
     */
    public function __construct(array $options, $method) {

        // Init host data
        $host = $options['HOST'];
        $host = (isset($host)) ? $host : URL_ROOT.API_SUB;
        
        // Init response common data
        $this->response = null;
        $this->response['success'] = 0;
        $this->response['data'] = new \stdClass();
    }

    /**
     * Return options
     * 
     * @return string json datas
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Return options
     * 
     * @return string json datas
     */
    public function setEndPoint($host, $method)
    {
        $this->options['HOST'] = $host;
        $this->options['METHOD'] = $method;
    }

    /**
     * Check if error is defined in object
     * @param object $var
     * @return boolean
     */
    public function isContainsError($var)
    {
        if (isset($var->error)) {
            return true;
        }
        return false;
    }
}

?>