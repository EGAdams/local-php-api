<?php
/** @class ObjectController: */
/*
 * we need this to depend on something that gets monitored objects.
 * not some concrete object that gets monitored objects.
 * 
 */
class ObjectController extends BaseController {
    public function __construct() { $this->errorObject = new ControllerError();
                                    $this->model       = new ObjectModel()    ; }

	public function selectAction() {  //  "/user/list" Endpoint - Get list of users 
		$requestMethod = $_SERVER[ "REQUEST_METHOD" ];
		if ( strtoupper( $requestMethod ) == 'GET') {
			try {
				$selectResult = $model->getMonitoredObjects();
				$responseData = json_encode( $selectResult  );
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
        $this->isPostOrDie( 'POST' );
        $inputJSON = file_get_contents('php://input');
        $arrQueryStringParams = json_decode( $inputJSON, TRUE );
        try {
            $object_view_id = $this->getQueryStringOrDie( $arrQueryStringParams, "object_view_id" );
            $object_data    = $this->getQueryStringOrDie( $arrQueryStringParams, "object_data"    );
            $insertResult   = $this->model->insertObject( $object_view_id, $object_data           );
            $responseData   = json_encode( $insertResult                                          );
        } catch ( Error $e ) {
            $this->errorObject->addDescription( $e->getMessage() . 'Something went wrong! Please contact support.' );
            $this->errorObject->setErrorHeader( 'HTTP/1.1 500 Internal Server Error' );
            $this->sendErrorOutputAndDie(); }
        $this->sendOutput( $responseData, array( 'Content-Type: application/json', 'HTTP/1.1 200 OK' ));
    }

    private function isPostOrDie( $expectedMethod ) {          
        $requestMethod = $_SERVER[ "REQUEST_METHOD" ];
        if ( strtoupper( $requestMethod ) != $expectedMethod ) { // Not valid type request?  wtf...
            $this->errorObject->addDescription( 'Method not supported'              );
            $this->errorObject->setErrorHeader( 'HTTP/1.1 422 Unprocessable Entity' );
            $this->sendErrorOutputAndDie(); }}

    private function sendErrorOutputAndDie() {
            $this->sendOutput( json_encode( array( 'error' => $this->errorObject->getErrorMessages() )),
                array( 'Content-Type: application/json', $this->errorObject->getErrorHeader() ));
            die; }

    private function getQueryStringOrDie( $arrayQueryStringParameters, $key ) {
        if ( isset( $arrayQueryStringParameters[ $key ]) && $arrayQueryStringParameters[ $key ]) {
            return $arrayQueryStringParameters[ $key ]; 
        } else {
            $this->errorObject->addDescription( "*** ERROR: " . $key . " is required for this action ***" );
            $this->errorObject->setErrorHeader( "HTTP/1.1 422 Unprocessable Entity"                       );
            $this->sendErrorOutputAndDie(); }}
}

/** @method selectAction() */
// * make sure that the request method is ‘GET’
// * use a new objectModel to get a list of users
// * invoke sendOutput( users ) in BaseController

// https://localhost/index.php/{MODULE_NAME}/{METHOD_NAME}?limit={LIMIT_VALUE}
// http://localhost/index.php/user/list?limit=20
