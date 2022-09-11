<?php
require_once  __DIR__ . "/Database.php";
echo "project root path in ObjectModel.php is: " . __DIR__ . "/../" . "<br>";
class ObjectModel extends Database /* implements ObjectInserter, ObjectUpdater, ObjectDeleter, ObjectSelector */ {
    public function __construct( $table_arg ) {
        echo "constructing database parent... <br>";
        parent::__construct(); 
        echo "done constructing super. <br>";
        $this->table = $table_arg   ; 
    }
                                                
	public function getObjects() { return $this->select( "SELECT * FROM $this->table" ); }     
    
    public function deleteObject( $object_view_id ) {
		return $this->delete( "DELETE FROM $this->table WHERE object_view_id='" . 
                               $object_view_id . "'"); }

    public function insertObject( $object_view_id, $object_data ) {
        return $this->insert( "INSERT INTO $this->table ( object_view_id, object_data ) 
                               VALUES ( $object_view_id, $object_data )" ); }

    public function updateObject( $object_view_id, $object_data ) {
        return $this->updateObject( 
            "  UPDATE $this->table " .
            "  SET object_data='" . $object_data .
            "' WHERE object_view_id='" . $object_view_id . "'" ); }
}
