<?php
require_once PROJECT_ROOT_PATH . "/Model/Database.php";

class UserModel extends Database {
	public function getMonitoredObjects() {return $this->select("SELECT * FROM monitored_objects");}

	public function insertMonitoredObject($object_view_id, $object_data) {
		return $this->insert("INSERT INTO monitored_objects ( object_view_id, object_data )
                               VALUES ( " . "'" . $object_view_id . "', '" . $object_data . "')");
	}

	public function updateMonitoredObject($object_view_id, $object_data) {
		return $this->update("INSERT INTO monitored_objects ( object_view_id, object_data )
                               VALUES ( " . "'" . $object_view_id . "', '" . $object_data . "')");
	}
}
