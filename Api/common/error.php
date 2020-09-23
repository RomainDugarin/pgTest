<?php
namespace PaygreenApiClient;

/**
 * Class ErrorResponse
 * Gère les erreurs dîtes "basiques"
 */
class ErrorResponse{

	/**
	 * @var string Message contenant la description de l'erreur survenue
	 */
	public $error_msg;

	/**
	 * @param string|null $msg Message contenant la description de l'erreur survenue
	 */
	public function __construct($msg = null){

		// Init error message
		$this->error_msg = 0;
		$this->error_msg = "Invalids parameters";

		// Check for custom message
		if($msg !== null){
			$this->error_msg = $msg;
		}

	}

	/**
	 * Function permettant de modifier le contenu du message d'erreur
	 * @param string $msg Nouveau message d'erreur
	 */
	public function setMessage($msg){
		$this->error_msg = $msg;
	}

}

?>