<?php
/** @class UserController: */
class UserController extends BaseController {
    public function __construct() { $this->errorObject = new ControllerError(); }

	public function listAction() {  //  "/user/list" Endpoint - Get list of users 
		$requestMethod = $_SERVER[ "REQUEST_METHOD" ];
		if ( strtoupper( $requestMethod ) == 'GET') {
			try {
				$objectModel = new ObjectModel();
				$modelInsertActionResult = $objectModel->getMonitoredObjects();
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

    public function insertAction() {  //  "/user/insert" Endpoint - insert a new monitored object 
        $this->checkRequestMethod( 'POST' );
        $inputJSON = file_get_contents('php://input');
        $arrQueryStringParams = json_decode( $inputJSON, TRUE );
        try {
            $objectModel = new ObjectModel();
            $object_view_id = $this->getQueryString( $arrQueryStringParams, "object_view_id" );
            $object_data    = $this->getQueryString( $arrQueryStringParams, "object_data"    );
            if ( $this->errorObject->isClean ) {
                $modelInsertActionResult = $objectModel->insertMonitoredObject( $object_view_id, $object_data );
                $responseData = json_encode( $modelInsertActionResult ); }
        } catch ( Error $e ) {
            $this->errorObject->addDescription( $e->getMessage() . 'Something went wrong! Please contact support.' );
            $this->setErrorHeader( 'HTTP/1.1 500 Internal Server Error' );	}
        
        if ( $this->errorObject->isClean ) {	////// if no error, send output... ///////
            $this->sendOutput( $responseData, array( 'Content-Type: application/json', 'HTTP/1.1 200 OK' ));
        } else { 
            $this->sendErrorOutput(); 
        }
    }


    private function checkRequestMethod( $expectedMethod ) {          
        $requestMethod = $_SERVER[ "REQUEST_METHOD" ];
        if ( strtoupper( $requestMethod ) != $expectedMethod ) {  // Not a POST request?  wtf...
            $this->errorObject->addDescription( 'Method not supported'              );
            $this->errorObject->setErrorHeader( 'HTTP/1.1 422 Unprocessable Entity' );
            $this->sendErrorOutput(); die; }
    }

    private function sendErrorOutput() {
            $this->sendOutput( json_encode( array( 'error' => $this->errorObject->getErrorMessages() )),
                array( 'Content-Type: application/json', $this->errorObject->getErrorHeader() )); }

    private function getQueryString( $arrayQueryStringParameters, $key ) {
        if ( isset( $arrayQueryStringParameters[ $key ]) && $arrayQueryStringParameters[ $key ]) {
            return $arrayQueryStringParameters[ $key ]; 
        } else {
            $this->errorObject->addDescription( "*** ERROR: " . $key . " is required for this action ***" );
            $this->errorObject->setErrorHeader( "HTTP/1.1 422 Unprocessable Entity"                       ); }}
}

/** @method listAction() */
// * make sure that the request method is ‘GET’
// * use a new objectModel to get a list of users
// * invoke sendOutput( users ) in BaseController

// https://localhost/index.php/{MODULE_NAME}/{METHOD_NAME}?limit={LIMIT_VALUE}
// http://localhost/index.php/user/list?limit=20
