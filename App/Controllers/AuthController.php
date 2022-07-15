<?php 

namespace App\Controllers;

use Miniframework\Controller\Action; 
use Miniframework\Model\Container;


class AuthController extends Action 
{

	public function authenticate() {
		
     $user = Container::getModel('User');

     $user->__set('email', $_POST['email']);

     $user->__set('password', $_POST['password']); 

     $user->authenticate();

     if(!empty($user->__get('id')) && !empty($user->__get('fullName'))) 
     {

	     session_start();

	     $_SESSION['id'] = $user->__get('id');
	     $_SESSION['fullName'] = $user->__get('fullName');
     
	     header('Location: /timeline'); 

     } 
     else 
     {
	     header('Location: /?login=error'); 
     }
    
	}

	public function logOut() 
   {
		session_start(); 
		session_destroy(); 
		header('Location: /'); 
	}

}

?>