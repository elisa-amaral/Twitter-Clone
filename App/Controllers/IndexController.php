<?php 

namespace App\Controllers;

use Miniframework\Controller\Action; 
use Miniframework\Model\Container; 

class IndexController extends Action 
{

	public function index() 
	{
      
      $this->view->login = isset($_GET['login']) ? $_GET['login'] : '';
      $this->render('index'); 

	} 

	public function signUp() 
	{

	   $this->view->validateFullNameError = false;
	   $this->view->validateEmailError = false;
	   $this->view->validatePasswordError = false;
	   $this->view->validateConfirmPasswordError = false;
	   $this->view->emailAlreadyInUseError = false;

       $this->render('sign_up'); 
	} 

	public function createAccount() 
	{
	    $this->view->validateFullNameError = false;
	    $this->view->validateEmailError = false;
	    $this->view->validatePasswordError = false;
	    $this->view->validateConfirmPasswordError = false;
	    $this->view->emailAlreadyInUseError = false;

		$user = Container::getModel('User');

		$user->__set('fullName', $_POST['fullName']); 
		$user->__set('email', $_POST['email']); 
		$user->__set('password', $_POST['password']); 
		$user->__set('confirmPassword',$_POST['confirmPassword']);
		
		$validations = $user->validateSignUp();


		if(!$validations['validateFullName']) 
		{
			$this->view->validateFullNameError  = true;
			$this->view->validateFullNameErrorMessage = '*Error: enter your full name (letters only, no special characters).'; 
			$this->view->user = array(
			   'fullName' => $_POST['fullName'],
			   'email' => $_POST['email'] 
		   );
         $validateFullNameError = true;
		}
		else 
		{
	       $validateFullNameError = false;
		}

		if(!$validations['validateEmail']) 
		{
			$this->view->validateEmailError  = true;
			$this->view->validateEmailErrorMessage = '*Error: invalid email address. Please enter a valid email address.'; 
			$this->view->user = array(
				'fullName' => $_POST['fullName'],
				'email' => $_POST['email'] 
				);
			$validateEmailError = true;
		}
		else 
		{
	       $validateEmailError = false;
		}

		if(!$validations['validatePassword']) 
		{
			$this->view->validatePasswordError = true;
			$this->view->validatePasswordErrorMessage = '*Error: invalid password. Try again. Your password must contain:<br> at least 8 characteres<br>at least one lowercase letter (a-z)<br>at least one uppercase letter (A-Z)<br>at least one number (0-9)<br>at least one special character (@, #, $, &, etc.)'; 
         
			$this->view->user = array(
				'fullName' => $_POST['fullName'],
				'email' => $_POST['email'] 
				);
			$validatePasswordError = true;
		}
		else 
		{   
	       $validatePasswordError = false;
		}

		if($validations['validatePassword'] && !$validations['validateConfirmPassword']) 
		{
			$this->view->validateConfirmPasswordError  = true;
			$this->view->validateConfirmPasswordErrorMessage = "*Error: the passwords entered don't match. Make sure that the password entered in the <i>Password</i> field matches the password entered in the <i>Confirm password</i> field."; 
			$this->view->user = array(
			   'fullName' => $_POST['fullName'],
			   'email' => $_POST['email'] 
			);
			$validateConfirmPasswordError = true;
		}
		else 
		{
	       $validateConfirmPasswordError = false;
		}

		if(!$validateFullNameError && !$validateEmailError && !$validatePasswordError && !$validateConfirmPasswordError) 
		{

			if(count($user->getUserByEmail()) == 0) 
			{
				$user->__set('password', $_POST['password']); 
				$user->registerUser();
				$this->render('user_registered_successfully');

			} 
			else 
			{ 

				$this->view->user = array(
				'fullName' => $_POST['fullName'],
					'email' => $_POST['email']
				);

				$this->view->emailAlreadyInUseError  = true;
				$this->view->emailAlreadyInUseErrorMessage = '*Error: the email address you entered is already in use on Twitter. Try again with a different email address.'; 

			}
		}

		$this->render('sign_up');  

	}  
 
} 

?>