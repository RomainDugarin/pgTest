<?php
namespace PaygreenApiClient;

// Get resources files
require_once 'dispatcher.php';

function is_json($string){
	return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
}

// Init request parameters
$req_dispatcher = new Dispatcher();
$req_action = null;
$req_params = [];

// Get target service name
if(isset($_POST['action'])){
	if(!empty($_POST['action'])){
		$req_action = $_POST['action'];
	}
}

// Verifying the targeted action validity
if($req_action !== null){

	// Init var 
	$param_are_valids = true;

	// Format POST Parameters
	foreach ($_POST as $key => $param) {
		if($key !== 'action' && $param_are_valids){

			// Check current param validity
			if(empty($param) && $param !== '0'){
				$param_are_valids = false;
			}else{

				// Automaticly handle apostroph SQL break
				if(is_string($param) && !is_json($param)){
					$param = str_replace("'", "''", $param);
				}
				// Store param for the request
				$req_params[$key] = $param;

			}

		}
	}

	// Check FILES Parameters validity if needed
	if($param_are_valids){
		foreach ($_FILES as $key => $file) {
			if($file["error"] !== 0){
				$param_are_valids = false;
			}else if(!isset($file["name"]) || !isset($file["tmp_name"]) || !isset($file["type"])){
				$param_are_valids = false;
			}else if(empty($file["name"]) || empty($file["tmp_name"]) || empty($file["type"])){
				$param_are_valids = false;
			}
		}
	}

	// Handle potential parameters errors
	if($param_are_valids){

		// Launch user request
		$req_dispatcher->getData($req_action, $req_params);

	}else{

		// Error occured
		echo $req_dispatcher->showError();

	}

}else{

	// Error occured
	echo $req_dispatcher->showError("Paramètre 'action' non valid");

}

?>