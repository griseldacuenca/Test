<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

require_once 'DBOperations.php';

class Functions{

private $db;

public function __construct() {

      $this -> db = new DBOperations();

}


public function registerUser($idUsuario,$nombre,$password,$celular,$idTipo)
{
	$db = $this -> db;
	if (!empty($idUsuario) && !empty($nombre) && !empty($password)
       && !empty($celular) && !empty($idTipo))
  {
  		if ($db -> checkUserExist($idUsuario)) {
  			$response["result"] = "failure";
  			$response["message"] = "User Already Registered !";

  			return json_encode($response);

  		} else {
  			$result = $db -> insertData($idUsuario,$nombre,$password,$celular,$idTipo);

  			if ($result) {
				  $response["result"] = "success";
  				$response["message"] = "User Registered Successfully !";
  				return json_encode($response);

  			} else {
  				$response["result"] = "failure";
  				$response["message"] = "Registration Failure";
  				return json_encode($response);

  			}
  		}
  	} else {

  		return $this -> getMsgParamNotEmpty();

  	}
}
public function getCuidadores()
{
  $db = $this -> db;
  $result = $db -> selectCuidadores();
  return json_encode($result);
}

public function loginUser($idUsuario, $password) {

  $db = $this -> db;

  if (!empty($idUsuario) && !empty($password)) {

    if ($db -> checkUserExist($idUsuario)) {

       $result =  $db -> checkLogin($idUsuario, $password);
       if(!$result) {

        $response["result"] = "failure";
        $response["message"] = "Invaild Login Credentials 0";
        return json_encode($response);

       } else {

        $response["result"] = "success";
        $response["message"] = "Login Successful";
        $response["usuario"] = $result;
        return json_encode($response);

       }

    } else {

      $response["result"] = "failure";
      $response["message"] = "Invaild Login Credentials 1";
      return json_encode($response);

    }
  } else {

      return $this -> getMsgParamNotEmpty();
    }

}


public function changePassword($email, $old_password, $new_password) {

  $db = $this -> db;

  if (!empty($email) && !empty($old_password) && !empty($new_password)) {

    if(!$db -> checkLogin($email, $old_password)){

      $response["result"] = "failure";
      $response["message"] = 'Invalid Old Password';
      return json_encode($response);

    } else {


    $result = $db -> changePassword($email, $new_password);

      if($result) {

        $response["result"] = "success";
        $response["message"] = "Password Changed Successfully";
        return json_encode($response);

      } else {

        $response["result"] = "failure";
        $response["message"] = 'Error Updating Password';
        return json_encode($response);

      }

    }
  } else {

      return $this -> getMsgParamNotEmpty();
  }

}

public function isEmailValid($email){

  return filter_var($email, FILTER_VALIDATE_EMAIL);
}

public function getMsgParamNotEmpty(){


  $response["result"] = "failure";
  $response["message"] = "Parameters should not be empty !";
  return json_encode($response);

}

public function getMsgInvalidParam(){

  $response["result"] = "failure";
  $response["message"] = "Invalid Parameters";
  return json_encode($response);

}

public function getMsgInvalidEmail(){

  $response["result"] = "failure";
  $response["message"] = "Invalid Email";
  return json_encode($response);

}

}
