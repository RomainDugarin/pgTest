<?php

	// SERVER GLOBAL VARS
	define('URL_ROOT', "https://paygreen.fr");
	define('API_SUB', "/api"); 
	define('URL_API', URL_ROOT.API_SUB);
	
    
    // return url of Authentication
	define('URL_AUTHENTICATION', "/auth"); 
	// return url of Authorization
	define('URL_AUTHORIZATION', URL_AUTHENTICATION."/authorize"); 
	// return url of auth token
	define('URL_AUTH_TOKEN', URL_AUTHENTICATION."/access_token"); 

	//
	//	UI + URL
	//
	// return url of shop
	define('UI_URL_SHOP', "/shop");
	
	// return url of shop
	define('URL_TRANSACTION', "/payins/transaction");

	define('TRANSACTION_CASH', URL_TRANSACTION."/cash");
	
	define('TRANSACTION_SUBSCRIPTION', URL_TRANSACTION."/subscription");

	define('TRANSACTION_TOKEN', URL_TRANSACTION."/tokenize");
	
	define('TRANSACTION_XTIME', URL_TRANSACTION."/xTime");

	define('URL_SOLIDARITY', "/solidarity");

	define('URL_CCARBONE', "/payins/ccarbone");

?>