<?php
require_once PROJECT_ROOT_PATH . "/Model/Database.php";

class UserModel extends Database {
	public function getUsers( $limit ) {
		return $this->select( "SELECT * FROM users ORDER BY user_id ASC LIMIT ?", [ "i", $limit ]);
	}

    public function insertObject( $object_view_id, $object_data ) {
        return $this->insert( "INSERT INTO users ( object_view_id, object_data ) VALUES ( $object_view_id, $object_data )" );
    }
}