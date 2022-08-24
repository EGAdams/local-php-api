<?php
require_once PROJECT_ROOT_PATH . "/Model/Database.php";

class ObjectModel extends Database implements ObjectUpdater, ObjectSource {
	public function getObjects() {
		return $this->select( "SELECT * FROM monitored_objecs" );
	}

    public function insertObject( $object_view_id, $object_data ) {
        return $this->insert( "INSERT INTO users ( object_view_id, object_data ) VALUES ( $object_view_id, $object_data )" );
    }

    public function updateObject( $object_view_id, $object_data ) {
        return $this->updateObject( 
            "  UPDATE monitored_objects " .
            "  SET object_data='" . $object_data .
            "' WHERE object_view_id='" . $object_view_id . "'" );
    }
}
