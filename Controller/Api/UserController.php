<?php
/** @class UserController: */
class UserController extends BaseController {
	public function listAction() {  //  "/user/list" Endpoint - Get list of users 
		$strErrorDesc = '';
		$requestMethod = $_SERVER[ "REQUEST_METHOD" ];
		$arrQueryStringParams = $this->getQueryStringParams();
		if ( strtoupper( $requestMethod ) == 'GET') {
			try {
				$userModel = new UserModel();
				$object_view_id = 10;
				if ( isset( $arrQueryStringParams[ 'limit' ]) && $arrQueryStringParams[ 'limit' ]) {
					$object_view_id = $arrQueryStringParams[ 'limit' ]; }
				$modelInsertActionResult = $userModel->getUsers( $object_view_id );
				$responseData = json_encode( $modelInsertActionResult );
			} catch ( Error $e ) {
				$strErrorDesc = $e->getMessage() . 'Something went wrong! Please contact support.';
				$strErrorHeader = 'HTTP/1.1 500 Internal Server Error';	}
		} else {  // Not a GET request?  wtf...
			$strErrorDesc = 'Method not supported';
			$strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity'; }

		if ( !$strErrorDesc ) {	////// if no error, send output... ///////
			$this->sendOutput( $responseData, array( 'Content-Type: application/json', 'HTTP/1.1 200 OK' ));
		} else {
			$this->sendOutput( json_encode( array( 'error' => $strErrorDesc )),
				array( 'Content-Type: application/json', $strErrorHeader )); }}

        public function insertAction() {  //  "/user/list" Endpoint - Get list of users 
            $strErrorDesc = '';
            $requestMethod = $_SERVER[ "REQUEST_METHOD" ];
            $arrQueryStringParams = $this->getQueryStringParams();
            if ( strtoupper( $requestMethod ) == 'POST') {
                try {
                    $userModel = new UserModel();
                    if ( isset( $arrQueryStringParams[ 'object_view_id' ]) && $arrQueryStringParams[ 'object_view_id' ]) {
                        $object_view_id = $arrQueryStringParams[ 'object_view_id' ]; 
                    } else {
                        $strErrorDesc .= 'object_view_id is required ';
                        $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity'; 
                    }
                    if ( isset( $arrQueryStringParams[ 'object_data' ]) && $arrQueryStringParams[ 'object_data' ]) {
                        $object_data = $arrQueryStringParams[ 'object_data' ]; 
                    } else {
                        $strErrorDesc .= 'object_view_id is required';
                        $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity'; 
                    }
                    $modelInsertActionResult = $userModel->insertObject( $object_view_id, $object_data );
                    $responseData = json_encode( $modelInsertActionResult );
                } catch ( Error $e ) {
                    $strErrorDesc = $e->getMessage() . 'Something went wrong! Please contact support.';
                    $strErrorHeader = 'HTTP/1.1 500 Internal Server Error';	}
            } else {  // Not a GET request?  wtf...
                $strErrorDesc = 'Method not supported';
                $strErrorHeader = 'HTTP/1.1 422 Unprocessable Entity'; }
    
            if ( !$strErrorDesc ) {	////// if no error, send output... ///////
                $this->sendOutput( $responseData, array( 'Content-Type: application/json', 'HTTP/1.1 200 OK' ));
            } else {
                $this->sendOutput( json_encode( array( 'error' => $strErrorDesc )),
                    array( 'Content-Type: application/json', $strErrorHeader )); }}
}


/** @method listAction() */
// * make sure that the request method is ‘GET’
// * use a new UserModel to get a list of users
// * invoke sendOutput( users ) in BaseController

// https://localhost/index.php/{MODULE_NAME}/{METHOD_NAME}?limit={LIMIT_VALUE}
// http://localhost/index.php/user/list?limit=20
