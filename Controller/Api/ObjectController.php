<?php
/** @class ObjectController: */
/*
 * we need this to depend on something that gets monitored objects.
 * not some concrete object that gets monitored objects.
 * 
 */
class ObjectController extends BaseController {
    public function __construct( $table_name ) { 
        $this->errorObject = new ControllerError();
        $this->model       = new ObjectModel( $table_name ); }

	public function selectAction() {  //  "/[ object ]/select" Endpoint - Get list of users 
		$requestMethod = $_SERVER[ "REQUEST_METHOD" ];
		if ( strtoupper( $requestMethod ) == 'GET') {
			try {
				$selectResult = $this->model->getObjects();
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

    public function insertAction() {  //  "/object/insert" Endpoint - insert a new monitored object 
        $this->isExpectedActionOrDie( 'POST' );
        $inputJSON = file_get_contents('php://input');
        $dictionaryQueryParams = json_decode( $inputJSON, TRUE );
        try {
            $object_view_id = $this->getQueryStringOrDie( $dictionaryQueryParams, "object_view_id" );
            $object_data    = $this->getQueryStringOrDie( $dictionaryQueryParams, "object_data"    );
            $insertResult   = $this->model->insertObject( $object_view_id, $object_data           );
            $responseData   = json_encode( $insertResult                                          );
        } catch ( Error $e ) {
            $this->errorObject->addDescription( $e->getMessage() . 'Something went wrong! Please contact support.' );
            $this->errorObject->setErrorHeader( 'HTTP/1.1 500 Internal Server Error' );
            $this->sendErrorOutputAndDie(); }
        $this->sendOutput( $responseData, array( 'Content-Type: application/json', 'HTTP/1.1 200 OK' ));
    }
                        
    public function deleteAction() {  //  "/user/insert" Endpoint - insert a new monitored object 
        $this->isExpectedActionOrDie( 'POST' );
        $inputJSON = file_get_contents('php://input');
        $dictionaryQueryParams = json_decode( $inputJSON, TRUE );
        try {  // if we are going to delete an object, we'll definitely need at least the id.
            $object_view_id = $this->getQueryStringOrDie( $dictionaryQueryParams, "object_view_id" );
            $deleteResult   = $this->model->deleteObject( $object_view_id                         );
            $responseData   = json_encode( $deleteResult                                          );
        } catch ( Error $e ) {
            $this->errorObject->addDescription( $e->getMessage()                                  );
            $this->errorObject->setErrorHeader( 'HTTP/1.1 500 Internal Server Error' );
            $this->sendErrorOutputAndDie(); }
        $this->sendOutput( $responseData, array( 'Content-Type: application/json', 'HTTP/1.1 200 OK' )); }

    public function updateAction() {  //  "/user/insert" Endpoint - insert a new monitored object 
        $this->isExpectedActionOrDie( 'POST' );
        $inputJSON = file_get_contents('php://input');
        $dictionaryQueryParams = json_decode( $inputJSON, TRUE );
        try {
            $object_view_id = $this->getQueryStringOrDie( $dictionaryQueryParams, "object_view_id" );
            $object_data    = $this->getQueryStringOrDie( $dictionaryQueryParams, "object_data"    );
            $updatedResult  = $this->model->updateObject( $object_view_id, $object_data            );
            $responseData   = json_encode( $updatedResult                                          );
        } catch ( Error $e ) {
            $this->errorObject->addDescription( $e->getMessage()                                   );
            $this->errorObject->setErrorHeader( 'HTTP/1.1 500 Internal Server Error'               );
            $this->sendErrorOutputAndDie(); }
        $this->sendOutput( $responseData, array( 'Content-Type: application/json', 'HTTP/1.1 200 OK' )); }

    private function isExpectedActionOrDie( $expectedMethod ) {          
        $requestMethod = $_SERVER[ "REQUEST_METHOD" ];
        if ( strtoupper( $requestMethod ) != $expectedMethod ) { // Not valid action request?  wtf...
            $this->errorObject->addDescription( 'Method not supported'              );
            $this->errorObject->setErrorHeader( 'HTTP/1.1 422 Unprocessable Entity' );
            $this->sendErrorOutputAndDie(); }}

    private function sendErrorOutputAndDie() {
            $this->sendOutput( json_encode( array( 'error' => $this->errorObject->getErrorMessages() )),
                array( 'Content-Type: application/json', $this->errorObject->getErrorHeader() ));
            die; }

    private function getQueryStringOrDie( $arrayQueryStringParameters, $key ) {
        if ( isset( $arrayQueryStringParameters[ $key ]) && $arrayQueryStringParameters[ $key ]) {
            return $arrayQueryStringParameters[ $key ]; // if there's a key, return it.
        } else {                                        // if there's not, die.
            $this->errorObject->addDescription( "*** ERROR: " . $key . " is required for this action ***" );
            $this->errorObject->setErrorHeader( "HTTP/1.1 422 Unprocessable Entity"                       );
            $this->sendErrorOutputAndDie(); }}
}

/** @method $action_type . Action() */
// * make sure that the request method matches the action
// * use the constructed model to perform the action
// * invoke sendOutput( result_data ) in BaseController

// https://localhost/index.php/{ TABLE_NAME }/{ ID }
// http://localhost/index.php/user/list?limit=20
