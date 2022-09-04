<?php
require_once PROJECT_ROOT_PATH . "/Model/Database.php";

class ObjectModel extends Database /* implements ObjectInserter, ObjectUpdater, ObjectDeleter, ObjectSelector */ {

    public function __construct( $table_arg ) { $this->table = $table_arg   ; }
                                                
	public function getObjects() { return $this->select( "SELECT * FROM $this->table" ); }     
    
    public function deleteObject( $object_view_id ) {
		return $this->delete( "DELETE FROM $this->table WHERE object_view_id='" . 
                               $object_view_id . "'"); }

    public function insertObject( $object_view_id, $object_data ) {
        return $this->insert( "INSERT INTO users ( object_view_id, object_data ) 
                               VALUES ( $object_view_id, $object_data )" ); }

    public function updateObject( $object_view_id, $object_data ) {
        return $this->update( 
            "  UPDATE $this->table " .
            "  SET object_data='" . $object_data .
            "' WHERE object_view_id='" . $object_view_id . "'" ); }
}
