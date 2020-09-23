<?php

namespace PaygreenApiClient;

// Get resources files
require_once 'common/error.php';
require_once 'connexion.php';
require_once 'property.php';

class Dispatcher extends ApiClient
{

    /**
     * @var string Nom de du service souhaité
     */
    private $action;

    /**
     * @var array Paramètres envoyés par l'utilisateur (reçu via la super global $_POST)
     */
    private $params;

    /**
     * @return array Tableau contenant les informations de l'api
     */
    private function getClientOptions() 
    {
        return array(
            "UI" => $this->UI,
            "CP" => $this->CP,
            "HOST" => $this->HOST,
            "PARAM" => $this->params
        );
    }

    /**
     * Fonction permettant de renvoyer une erreur
     * @param type|null $err_msg Message personnalisé
     * @return array Tableau contenant les informations de l'erreur
     */
    private function showError($err_msg = null)
    {

        // Format response
        $req_error = [];
        $req_error['success'] = 0;
        $req_error['data'] = new ErrorResponse();
        if ($err_msg !== null) {
            $req_error['data'] = new ErrorResponse($err_msg);
        }
        return json_encode($req_error);
    }

    /**
     * Function vérifiant la présences des paramètres requis
     * @param array $required_parameters Tableau contenant les paramètres obligatoire pour le bon execution d'un service (Ex : ['Params1', 'Params2', etc...])
     * @return boolean Renvoi vrai si tous les paramètres requis sont présents. 
     */
    private function checkParametersErrors($required_parameters)
    {

        // Init data
        $error_message = "";
        $valid = true;

        // Check required params validity
        foreach ($required_parameters as $key => $cur_param) {
            if (!array_key_exists($cur_param, $this->params)) {
                if ($error_message == "") {
                    $error_message = $cur_param;
                } else {
                    $error_message .= ", " . $cur_param;
                }
            }
        }

        // Display error if needed
        if ($error_message != "") {

            // Error occured
            $valid = false;
            $error_message = "Le(s) paramètre(s) suivant(s) manque(nt) : " . $error_message;
            echo $this->showError($error_message);
        }

        return $valid;
    }

    /**
     * Function chargée d'exécuter le service souhaité par le client 
     * @param type $action 
     * @param type $params 
     */
    public function getData($action, $params)
    {
        $this->action = $action;
        $this->params = $params;

        // Switch on targeted action
        switch ($this->action) {

                /**
             * Authentication to server paygreen
             */
            case 'getOAuthServerAccess':

                // Return service data
                $required_params = ['email', 'name'];

                // Check parameters validity
                if ($this->checkParametersErrors($required_params)) {
                    $services = new ConnexionServices($this->getClientOptions(), 'post');
                    return $services->getOAuthServerAccess(
                        $this->params['email'],
                        $this->params['name'],
                        $this->params['phone'],
                        $this->params['ipAddress']
                    );
                }
                break;

                /**
                 * return url of Authorization
                 * @return string url of Authorization
                 */
            case 'getOAuthAutorizeEndpoint':
                return URL_API . URL_AUTHORIZATION;
                break;

                /**
                 * return url of auth token
                 * @return string url of Authentication
                 */
            case 'getOAuthTokenEndpoint':
                return URL_API . URL_AUTH_TOKEN;
                break;

                /**
                 * return url of Authentication
                 * @return string url of Authentication
                 */
            case 'getOAuthDeclareEndpoint':
                return URL_API . URL_AUTHENTICATION;
                break;

                /**
                 * 
                 */
            case 'getTransactionInfo':

                // Return service data
                $required_params = ['pid'];

                // Check parameters validity
                if ($this->checkParametersErrors($required_params)) {
                    $services = new PropertyServices($this->getClientOptions(), 'get');
                    return $services->getTransactionInfo(
                        $this->params['pid']
                    );
                }
                break;


                /**
                 * Get Status of the shop
                 * @return string json datas
                 */
            case 'getStatusShop':
                $services = new PropertyServices($this->getClientOptions(), 'get');
                return $services->getStatusShop();
                break;

                /**
                 * Refund an order
                 *
                 * @param int $pid paygreen id of transaction
                 * @param float $amount amount of refund
                 * @return string json answer
                 */
            case 'getTransactionInfo':

                // Return service data
                $required_params = ['pid', 'amount'];

                // Check parameters validity
                if ($this->checkParametersErrors($required_params)) {
                    $services = new PropertyServices($this->getClientOptions(), 'get');
                    return $services->getTransactionInfo(
                        $this->params['pid']
                    );
                }
                break;

            case 'refundOrder':
                
                // Return service data
                $required_params = ['pid', 'amount'];
                // Check parameters validity
                if ($this->checkParametersErrors($required_params)) {
                    $services = new PropertyServices($this->getClientOptions(), 'delete');
                    return $services->refundOrder(
                        $this->params['pid'],
                        $this->params['amount']
                    );
                }
                break;

            case 'sendFingerprintDatas':
            
                // Return service data
                $required_params = ['data'];
                // Check parameters validity
                if ($this->checkParametersErrors($required_params)) {
                    $services = new PropertyServices($this->getClientOptions(), 'post');
                    return $services->sendFingerprintDatas(
                        $this->params['data']
                    );
                }
                break;

            case 'validateShop':
                // Return service data
                $required_params = ['activate'];
                // Check parameters validity
                if ($this->checkParametersErrors($required_params)) {
                    $services = new PropertyServices($this->getClientOptions(), 'patch');
                    return $services->validateShop(
                        $this->params['activate']
                    );
                }
                break;

                
            case 'validIdShop':
                $services = new PropertyServices($this->getClientOptions(), 'get');
                return $services->validIdShop();
                break;


            case 'getAccountInfos':
                $services = new PropertyServices($this->getClientOptions(), 'get');
                return $services->getAccountInfos();
                break;

            case 'getRoundingInfo':

                // Return service data
                $required_params = ['datas'];
                // Check parameters validity
                if ($this->checkParametersErrors($required_params)) {
                    $services = new PropertyServices($this->getClientOptions(), 'get');
                    return $services->getRoundingInfo(
                        $this->params['datas']
                    );
                }
                break;
            
            case 'validateRounding':

                // Return service data
                $required_params = ['datas'];
                // Check parameters validity
                if ($this->checkParametersErrors($required_params)) {
                    $services = new PropertyServices($this->getClientOptions(), 'patch');
                    return $services->validateRounding(
                        $this->params['datas']
                    );
                }
                break;

            case 'refundRounding':

                // Return service data
                $required_params = ['datas'];
                // Check parameters validity
                if ($this->checkParametersErrors($required_params)) {
                    $services = new PropertyServices($this->getClientOptions(), 'delete');
                    return $services->refundRounding(
                        $this->params['datas']
                    );
                }
                break;

            case 'validDeliveryPayment':
                // Return service data
                $required_params = ['pid'];
                // Check parameters validity
                if ($this->checkParametersErrors($required_params)) {
                    $services = new PropertyServices($this->getClientOptions(), 'put');
                    return $services->validDeliveryPayment(
                        $this->params['pid']
                    );
                }
                break;

            case 'createCash':
                
                // Return service data
                $required_params = ['data'];
                // Check parameters validity
                if ($this->checkParametersErrors($required_params)) {
                    $services = new PropertyServices($this->getClientOptions(), 'post');
                    return $services->createCash(
                        $this->params['data']
                    );
                }
                break;
            
            case 'createXTime':
                // Return service data
                $required_params = ['data'];
                // Check parameters validity
                if ($this->checkParametersErrors($required_params)) {
                    $services = new PropertyServices($this->getClientOptions(), 'post');
                    return $services->createXTime(
                        $this->params['data']
                    );
                }
                break;

            case 'createSubscription':
                // Return service data
                $required_params = ['data'];
                // Check parameters validity
                if ($this->checkParametersErrors($required_params)) {
                    $services = new PropertyServices($this->getClientOptions(), 'post');
                    return $services->createSubscription(
                        $this->params['data']
                    );
                }
                break;

            case 'createTokenize':
                // Return service data
                $required_params = ['data'];
                // Check parameters validity
                if ($this->checkParametersErrors($required_params)) {
                    $services = new PropertyServices($this->getClientOptions(), 'post');
                    return $services->createTokenize(
                        $this->params['data']
                    );
                }
                break;

            default:

                // Error occured
                return $this->showError("Le service demandé n'existe pas");

                break;
        }
    }
}
