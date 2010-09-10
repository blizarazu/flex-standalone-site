<?php

require_once 'utils/Datasource.php';
require_once 'utils/Config.php';
require_once 'vo/UserVO.php';


/**
 * This class is used to make queries related to an VO object. When the results
 * are stored on our VO class AMFPHP parses this data and makes it available for
 * AS3/Flex use.
 *
 * It must be placed under amfphp's services folder, once we have successfully
 * installed amfphp's files in apache's web folder.
 *
 */
class UserDAO {
	private $conn;

	public function UserDAO(){
		$settings = new Config();
		$this->conn = new Datasource($settings->host, $settings->db_name, $settings->db_username, $settings->db_password);
	}

	public function getTopTenCredited()
	{
		$sql = "SELECT id, name, email, creditCount FROM users AS U WHERE U.active = 1 ORDER BY creditCount DESC LIMIT 10";

		$searchResults = $this->_listQuery($sql);

		return (count($searchResults))? $searchResults : false ;
	}

	public function getUserInfo($userId){
		if (!$userId)
		{
			return false;
		}

		$sql = "SELECT id, name, email, creditCount, realName, realSurname, active, joiningDate, isAdmin FROM users WHERE (id = %d) ";

		return $this->_singleQuery($sql, $userId);
	}
	
	public function keepAlive($userId){
		
		session_start();
		$sessionId = session_id();
		
		//Check that there's not another active session for this user
		$sql = "SELECT * FROM user_session WHERE ( session_id = '%s' AND fk_user_id = '%d' AND closed = 0 )";
		$result = $this->conn->_execute ( $sql, $sessionId, $userId );
		$row = $this->conn->_nextRow($result);
		if($row){
			$sql = "UPDATE user_session SET keep_alive = 1 WHERE fk_user_id = '%d' AND closed=0";
		
			return $this->_databaseUpdate($sql, $userId);
		}
	}

	//Returns a single User object
	private function _singleQuery($sql, $userId){
		$valueObject = new UserVO();
		$result = $this->conn->_execute($sql, $userId);

		$row = $this->conn->_nextRow($result);
		if ($row)
		{
			$valueObject->id = $row[0];
			$valueObject->name = $row[1];
			$valueObject->email = $row[2];
			$valueObject->creditCount = $row[3];
			$valueObject->realName = $row[4];
			$valueObject->realSurname = $row[5];
			$valueObject->active = $row[6];
			$valueObject->joiningDate = $row[7];
			$valueObject->isAdmin = $row[8]==1;
		}
		else
		{
			return false;
		}
		return $valueObject;
	}
	
	public function changePass($userId, $oldpass, $newpass)
	{
		$sql = "SELECT * FROM users WHERE id = %d AND password = '%s'";
		$result = $this->conn->_execute($sql, $userId, $oldpass);
		$row = $this->conn->_nextRow($result);
		if (!$row)
			return false;

		$sql = "UPDATE users SET password = '%s' WHERE id = %d AND password = '%s'";
		$result = $this->conn->_execute($sql, $newpass, $userId, $oldpass);
		
		return true;
	}
	
	public function restorePass($username)
	{
		$id = -1;
		$email = "";
		$user = "";
		$realName = "";

		require_once('Mailer.php');

		$aux = "name";
		if ( Mailer::checkEmail($username) )
			$aux = "email";

		// Username or email checking
		$sql = "SELECT id, name, email, realName FROM users WHERE $aux = '%s'";
		$result = $this->conn->_execute($sql, $username);
		$row = $this->conn->_nextRow($result);

		if ($row)
		{
			$id = $row[0];
			$user = $row[1];
			$email = $row[2];
			$realName = $row[3];
		}

		if ( $realName == '' || $realName == 'unknown' ) $realName = $user;
		
		// User dont exists
		if ( $id == -1 ) return "Unregistered user";

		$newPassword = $this->_createNewPassword();
		
		$sql = "UPDATE users SET password = '%s' WHERE id = %d";
		$result = $this->conn->_execute($sql, sha1($newPassword), $id);

		
		$args = array(
						'REAL_NAME' => $realName,
						'USERNAME' => $user,
						'PASSWORD' => $newPassword,
						'SIGNATURE' => 'The Babelium Project Team');

		$mail = new Mailer($email);

		if ( !$mail->makeTemplate("restorepass", $args, "es_ES") ) return null;

		$subject = "Your password has been reseted";

		$mail->send($mail->txtContent, $subject, $mail->htmlContent);
		
		return "Done";
	}
	
	// Returns an array of Users
	private function _listQuery($sql)
	{
		$searchResults = array();
		$result = $this->conn->_execute($sql);

		while ($row = $this->conn->_nextRow($result))
		{
			$temp = new UserVO();
			$temp->id = $row[0];
			$temp->name = $row[1];
			$temp->email = $row[2];
			$temp->creditCount = $row[3];
			$temp->realName = $row[4];
			$temp->realSurname = $row[5];
			$temp->active = $row[6];
			$temp->joiningDate = $row[7];
			$temp->isAdmin = $row[8]==1;
			array_push($searchResults, $temp);
		}

		return $searchResults;
	}

	private function _createNewPassword()
	{
		$pass = "";
		$chars = "zbcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$length = rand(8, 14);

		// Generate password
		for ( $i = 0; $i < $length; $i++ )
			$pass .= substr($chars, rand(0, strlen($chars)-1), 1);  // java: chars.charAt( random );

		return $pass;
	}
	
	private function _databaseUpdate() {
		$result = $this->conn->_execute ( func_get_args() );
		
		return $result;
	}

}

?>
