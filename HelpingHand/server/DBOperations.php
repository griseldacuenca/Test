<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

class DBOperations{

	 private $host = 'localhost';
	 private $user = 'root';
	 private $db = 'ayudame';
	 private $pass = 'grinando';
	 private $conn;

public function __construct() {

	$this -> conn = new PDO("mysql:host=".$this -> host.";dbname=".$this -> db, $this -> user, $this -> pass);

}


 public function insertData($idUsuario,$nombre,$password,$celular,$idTipo)
 {
  $hash = $this->getHash($password);
  $encrypted_password = $hash["encrypted"];
	$salt = $hash["salt"];

	//$sql = 'INSERT INTO usuario SET idUsuario=:idUsuario, nombre=:nombre, password=:password,
	//				celular=:celular, idTipo=:idTipo, salt=:salt';
	$sql = 'INSERT INTO usuario (idUsuario, nombre, password, celular, idTipo, salt)
	VALUES (:idUsuario, :nombre, :password, :celular, :idTipo, :salt)';

 	$query = $this ->conn ->prepare($sql);
 	$query->execute(array(':idUsuario'=>$idUsuario, ':nombre'=>$nombre, ':password'=>$encrypted_password,
					':celular'=>$celular, ':idTipo'=>$idTipo, ':salt'=>$salt));

    if ($query)
		{
        return true;
    }
		else
		{
        return false;
    }
 }

 public function selectCuidadores()
 {
	 $sql = 'SELECT name FROM usuario WHERE idTipo=2';
	 $query = $this -> conn -> prepare($sql);
	 $query -> execute();
	 if ($query)
	 {
	 		$output = array();
	 		while($row = $query->fetch(PDO::FETCH_ASSOC))
	 		{
	 			$output[] = $row['nombre'];
	 		}
			return $output;
		}
	 	else
	 	{
	 		 return false;
	 	}
 }

 public function checkLogin($idUsuario, $password) {

    $sql = 'SELECT * FROM usuario WHERE idUsuario = :idUsuario';
    $query = $this -> conn -> prepare($sql);
    $query -> execute(array(':idUsuario' => $idUsuario));
    $data = $query -> fetchObject();
    $salt = $data -> salt;
    $db_encrypted_password = $data -> password;

    if ($this -> verifyHash($password.$salt,$db_encrypted_password) )
		{
        $user["idUsuario"] = $data -> idUsuario;
				$user["nombre"] = $data -> nombre;
				$user["celular"] = $data -> celular;
				$user["idTipo"] = $data -> idTipo;

        return $user;
    }
		else
		{
        return false;
    }

 }


 public function changePassword($email, $password){


    $hash = $this -> getHash($password);
    $encrypted_password = $hash["encrypted"];
    $salt = $hash["salt"];

    $sql = 'UPDATE users SET encrypted_password = :encrypted_password, salt = :salt WHERE email = :email';
    $query = $this -> conn -> prepare($sql);
    $query -> execute(array(':email' => $email, ':encrypted_password' => $encrypted_password, ':salt' => $salt));

    if ($query) {

        return true;

    } else {

        return false;

    }

 }

 public function checkUserExist($idUsuario){

    $sql = 'SELECT COUNT(*) from usuario WHERE idUsuario =:idUsuario';
    $query = $this -> conn -> prepare($sql);
    $query -> execute(array('idUsuario' => $idUsuario));

    if($query){

        $row_count = $query -> fetchColumn();

        if ($row_count == 0){

            return false;

        } else {

            return true;

        }
    } else {

        return false;
    }
 }

 public function getHash($password) {

     $salt = sha1(rand());
     $salt = substr($salt, 0, 10);
     $encrypted = password_hash($password.$salt, PASSWORD_DEFAULT);
     $hash = array("salt" => $salt, "encrypted" => $encrypted);

     return $hash;

}



public function verifyHash($password, $hash) {

    return password_verify ($password, $hash);
}
}
