<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

require_once 'Functions.php';

$fun = new Functions();


if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
  $data = json_decode(file_get_contents("php://input"));

  if(isset($data -> operation)){

  	$operation = $data -> operation;

  	if(!empty($operation)){

  		if($operation == 'register')
      {
        if(!empty($data -> user) && isset($data -> user ) && isset($data -> user -> idUsuario) && isset($data -> user -> nombre)
  				 && isset($data -> user -> password) && isset($data -> user -> celular) && isset($data -> user -> idTipo))
          {

  				$user = $data -> user;
          $idUsuario = $user -> idUsuario;
  				$nombre = $user -> nombre;
  				$password = $user -> password;
          $celular = $user -> celular;
          $idTipo = $user -> idTipo;

          echo $fun -> registerUser($idUsuario,$nombre,$password,$celular,$idTipo);

  			  }
          else
          {
            echo $fun -> getMsgInvalidParam();
          }

  		}else if ($operation == 'login') {

        if(isset($data -> user) && !empty($data -> user) && isset($data -> user -> idUsuario) && isset($data -> user -> password)){

          $user = $data -> user;
          $idUsuario = $user -> idUsuario;
          $password = $user -> password;

          echo $fun -> loginUser($idUsuario, $password);
        }
        else
        {
          echo $fun -> getMsgInvalidParam();
        }
      } else if ($operation == 'cuidadores') {
        echo $fun -> getCuidadores();

      } else if ($operation == 'chgPass') {

        if(isset($data -> user ) && !empty($data -> user) && isset($data -> user -> email) && isset($data -> user -> old_password)
          && isset($data -> user -> new_password)){

          $user = $data -> user;
          $email = $user -> email;
          $old_password = $user -> old_password;
          $new_password = $user -> new_password;

          echo $fun -> changePassword($email, $old_password, $new_password);

        } else {

          echo $fun -> getMsgInvalidParam();

        }
      }

  	}else{


  		echo $fun -> getMsgParamNotEmpty();

  	}
  } else {

  		echo $fun -> getMsgInvalidParam();

  }
} else if ($_SERVER['REQUEST_METHOD'] == 'GET'){


  echo "Ayudame API";

}
