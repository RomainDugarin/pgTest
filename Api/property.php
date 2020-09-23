<?php

namespace PaygreenApiClient;

// Get configuration file
require_once '../conf/config.php';

// Includ api helpers functions
require_once 'common/helpers.php';

/**
 * Class PropertyServices
 * Contient les différents services de propriété à la plateforme Paygreen
 */
class PropertyServices extends Service
{

    public function getTransactionInfo($pid)
    {
        // set Request Options (path, params,...)
        $options = $this->getOptions();

        try {
            $this->response['data'] = $this->requestApi($options, array('pid' => $pid));
            $this->response['success'] = 1;
        } catch (\Throwable $th) {
            $this->response = new ErrorResponse("Error: get Transaction Info");
        }
        return $this->response;
    }

    /**
     * Get Status of the shop
     * @return string json datas
     */
    public function getStatusShop()
    {
        // set Request Options (path, params,...)
        $options = $this->getOptions();

        try {
            $this->response['data'] = $this->requestApi($options, array('type' => 'shop'));
            $this->response['success'] = 1;
        } catch (\Throwable $th) {
            $this->response = new ErrorResponse("Error: get Status Shop");
        }
        return $this->response;
    }

    /**
     * Refund an order
     *
     * @param int $pid paygreen id of transaction
     * @param float $amount amount of refund
     * @return string json answer
     */
    public function refundOrder($pid, $amount)
    {
        // set Request Options (path, params,...)
        $options = $this->getOptions();

        if (empty($pid)) {
            return false;
        }

        $datas = array('pid' => $pid);
        if ($amount != null) {
            $datas['content'] = array('amount' => $amount * 100);
        }

        try {
            $this->response['data'] = $this->requestApi($options, $datas);
            $this->response['success'] = 1;
        } catch (\Throwable $th) {
            $this->response = new ErrorResponse("Error: get Status Shop");
        }
        return $this->response;
    }

    public function sendFingerprintDatas($data)
    {
        // set Request Options (path, params,...)
        $options = $this->getOptions();

        $datas['content'] = $data;
        try {
            $this->response['data'] = $this->requestApi($options, $datas);
            $this->response['success'] = 1;
        } catch (\Throwable $th) {
            $this->response = new ErrorResponse("Error: get Status Shop");
        }
        return $this->response;
    }

    /**
     * To validate the shop
     *
     * @param int $activate 1 or 0 to active the account
     * @return string json answer of false if activate != {0,1}
     */
    public function validateShop($activate)
    {
        // set Request Options (path, params,...)
        $options = $this->getOptions();

        if ($activate != 1 && $activate != 0) {
            $this->response = new ErrorResponse("Error: Parameter don't match");
            return $this->response;
        }
        $datas['content'] = array('activate' => $activate);
        try {
            $this->response['data'] = $this->requestApi($options, $datas);
            $this->response['success'] = 1;
        } catch (\Throwable $th) {
            $this->response = new ErrorResponse("Error: Validate the shop");
        }
        return $this->response;
    }

    /**
    * To check if private Key and Unique Id are valids
    *
    * @return string json answer of false if activate != {0,1}
    */
    public function validIdShop()
    {
        // set Request Options (path, params,...)
        $options = $this->getOptions();

        $valid = $this->requestApi($options, null);

        if ($valid->success == 0 || $valid != false) {
            $valid = false;
        }
        try {
            $this->response['data'] = $valid;
            $this->response['success'] = 1;
        } catch (\Throwable $th) {
            $this->response = new ErrorResponse("Error: Validate the shop ID");
        }
        return $this->response;
    }

    /**
    * Get shop informations
    * @return string json datas
    */
    public function getAccountInfos()
    {
        // set Request Options (path, params,...)
        $options = $this->getOptions();

        $infosAccount = array();
        $errors = array();

        $account = $this->requestApi($options, array('type'=>'account'));
        if ($this->isContainsError($account)) {
            $errors['siret'] = $account->error;
        }
        $infosAccount['siret'] = ($account !== false) ? $account->data->siret : false;

        $bank  = $this->requestApi($options, array('type' => 'bank'));
        if ($this->isContainsError($bank)) {
            $errors['IBAN'] = $bank->error;
        }
        if ($bank == false) {
            $infosAccount['IBAN'] = false;
        } else {
            foreach($bank->data as $rib){
                if($rib->isDefault == "1"){
                    $infosAccount['IBAN']  = $rib->iban;
                }
            }
        }

        $shop = $this->requestApi($options, array('type'=> 'shop'));
        if ($this->isContainsError($bank)) {
            $errors['siret'] = $account->shop;
        }
        $infosAccount['url'] = ($shop == false) ? $shop->data->url : false;
        $infosAccount['modules'] = ($shop == false) ? $shop->data->modules : false;
        $infosAccount['solidarityType'] = ($shop == false) ? $shop->data->extra->solidarityType : false;

        if(isset($shop->data->businessIdentifier)) {
            $infosAccount['siret'] = $shop->data->businessIdentifier;
        }      

        if (empty($infosAccount['url']) && empty($infosAccount['siret']) && empty($infosAccount['IBAN'])) {
            $infosAccount['valide'] = false;
        }

        try {
            $this->response['data'] = $infosAccount;
            $this->response['errors'] = $errors;
            $this->response['success'] = 1;
        } catch (\Throwable $th) {
            $this->response = new ErrorResponse("Error: Validate the shop ID");
        }
        return $this->response;
    }

    /**
     * Get rounding informations for $paiementToken
     * @param string $UI unique id
     * @param string $CP private key
     * @param string $paiementToken paiementToken
     * @return string json datas
     */
    public function getRoundingInfo($datas)
    {
        // set Request Options (path, params,...)
        $options = $this->getOptions();

        $transaction = $this->requestApi($options, $datas);
        if($this->isContainsError($transaction)){
            return $transaction->error;
        }
        try {
            $this->response['data'] = $transaction;
            $this->response['success'] = 1;
        } catch (\Throwable $th) {
            $this->response = new ErrorResponse("Error: Validate the shop ID");
        }
        return $this->response;
    }

    public function validateRounding($datas)
    {
        // set Request Options (path, params,...)
        $options = $this->getOptions();

        $validate = $this->requestApi($options, $datas);
        if ($this->isContainsError($validate)){
            return $validate->error;
        }
        try {
            $this->response['data'] = $validate;
            $this->response['success'] = 1;
        } catch (\Throwable $th) {
            $this->response = new ErrorResponse("Error: Validate the shop ID");
        }
        return $this->response;
    }

    public function refundRounding($datas)
    {
        // set Request Options (path, params,...)
        $options = $this->getOptions();

        $datas['content'] = array('paymentToken' => $datas['paymentToken']);
        $refund = $this->requestApi($options, $datas);
        if ($this->isContainsError($refund)){
            return $refund->error;
        }
        try {
            $this->response['data'] = $refund;
            $this->response['success'] = 1;
        } catch (\Throwable $th) {
            $this->response = new ErrorResponse("Error: Validate the shop ID");
        }
        return $this->response;
    }

    public function validDeliveryPayment($pid)
    {
        // set Request Options (path, params,...)
        $options = $this->getOptions();

        $datas['content'] = $pid;
        try {
            $this->response['data'] = $this->requestApi($options, $datas);
            $this->response['success'] = 1;
        } catch (\Throwable $th) {
            $this->response = new ErrorResponse("Error: get Status Shop");
        }
        return $this->response;
    }

    public function createCash($data)
    {
        // set Request Options (path, params,...)
        $options = $this->getOptions();

        $datas['content'] = $data;
        try {
            $this->response['data'] = $this->requestApi($options, $datas);
            $this->response['success'] = 1;
        } catch (\Throwable $th) {
            $this->response = new ErrorResponse("Error: get Status Shop");
        }
        return $this->response;
    }
    
    public function createXTime($data)
    {
        // set Request Options (path, params,...)
        $options = $this->getOptions();

        $datas['content'] = $data;
        try {
            $this->response['data'] = $this->requestApi($options, $datas);
            $this->response['success'] = 1;
        } catch (\Throwable $th) {
            $this->response = new ErrorResponse("Error: get Status Shop");
        }
        return $this->response;
    }
    
    public function createSubscription($data)
    {
        // set Request Options (path, params,...)
        $options = $this->getOptions();

        $datas['content'] = $data;
        try {
            $this->response['data'] = $this->requestApi($options, $datas);
            $this->response['success'] = 1;
        } catch (\Throwable $th) {
            $this->response = new ErrorResponse("Error: get Status Shop");
        }
        return $this->response;
    }
    
    public function createTokenize($data)
    {
        // set Request Options (path, params,...)
        $options = $this->getOptions();

        $datas['content'] = $data;
        try {
            $this->response['data'] = $this->requestApi($options, $datas);
            $this->response['success'] = 1;
        } catch (\Throwable $th) {
            $this->response = new ErrorResponse("Error: get Status Shop");
        }
        return $this->response;
    }

}
