<?php
class Database {
    protected $connection = null;
	public function __construct() {
        try {
            $this->connection = new mysqli( DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE_NAME );
			if ( mysqli_connect_errno()) { throw new DatabaseException( "Could not connect to database." ); }
		} catch ( Exception $e ) { throw new DatabaseException( $e->getMessage()); }}
    
	public function select( $query = "", $params = []) {
        try {
            $stmt = $this->executeStatement( $query, $params);
			$result = $stmt->get_result()->fetch_all( MYSQLI_ASSOC );
			$stmt->close();
			return $result;
		} catch ( Exception $e ) { throw new DatabaseException( $e->getMessage()); }
		return false;
	}
    
    public function insert( $query ) {
        try {
            $stmt = $this->executeStatement( $query );
            $result = $stmt->get_result()->fetch_all( MYSQLI_ASSOC );
            $stmt->close();
            return $result;
        } catch ( Exception $e ) { throw new DatabaseException( $e->getMessage()); }
        return false;
    }
    
	private function executeStatement( $query = "", $params = []) {
		try {
            $stmt = $this->connection->prepare( $query );
			if ( $stmt === false ) { throw new DatabaseException( "Unable to do prepared statement: " . $query); }
			if ( $params ) { $stmt->bind_param( $params[ 0 ], $params[ 1 ]); }
			$stmt->execute();
			return $stmt;
		} catch ( Exception $e ) { throw new DatabaseException( $e->getMessage()); }
	}
}
class DatabaseException extends Exception {} // hush the generic Exception warning
