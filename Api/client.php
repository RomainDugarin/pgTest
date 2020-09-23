<?php

namespace PaygreenApiClient;

class ApiClient
{

    public $UI = '';
    public $CP = '';
    public $HOST = null;

    public function __construct($UI, $CP, $HOST = null)
    {
        $this->IdsAreEmpty($UI, $CP);
        $this->setHost($HOST);
    }

    /**
    * Set $host 
    * @param string $host
    */
    private function setHost($host = null)
    {
        if (empty($host)) {
            $host = URL_ROOT;
        }
        $this->HOST = $host.API_SUB;
    }

    /**
    * Check if UI and CP are empty and set them
    * @param string $UI
    * @param string $CP
    */
    private function IdsAreEmpty($UI, $CP)
    {
        if (empty($this->CP)) {
            $this->CP = $CP;
        }
        if (empty($this->UI)) {
            $this->UI = $UI;
        }
    }

}
