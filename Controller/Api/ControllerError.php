<?php
/** @class ControllerError */
class ControllerError {
    public function __construct() {
        $this->descriptions = "";
        $this->errorHeader = "";
        $this->clean       = true;        
    }

    public function addDescription( $newDescription ) {
        $this->descriptions .= "\n " . $newDescription;
        $this->clean = false;
    }

    public function setErrorHeader( $headerError ) {
        $this->errorHeader = $headerError;
    }

    public function getErrorHeader() {   return $this->errorHeader;  }
    public function getErrorMessages() { return $this->descriptions; }
    public function isClean() {          return $this->clean;        }
}
