<?php 

namespace App\Models; 

use Miniframework\Model\Model;

class User extends Model 
{

	private $id;
	private $fullName;
	private $email;
	private $password;
	private $confirmPassword;
	private $profilePicture;
	private $headerImage; 

	public function __get($attribute) 
	{
		return $this->$attribute;
	}

	public function __set($attribute, $value) 
	{

		$this->$attribute = $value;
	}

   public function validateSignUp() 
   {

		$validations = array();

		$upperCaseLetters = preg_match('@[A-Z]@', $this->__get('password'));
		$lowerCaseLetters = preg_match('@[a-z]@', $this->__get('password'));
		$numbers = preg_match('@[0-9]@', 	$this->__get('password'));
		$specialCharacters = preg_match('@[^\w]@',	$this->__get('password'));
		

		if((!preg_match("/^[a-zA-Z ]*$/", $this->__get('fullName'))) || (strlen($this->__get('fullName')) < 5))  {

			$validations['validateFullName'] = false;
		}
		else 
		{
			$validations['validateFullName'] = true;
		}


		if(!filter_var($this->__get('email'), FILTER_VALIDATE_EMAIL))  {
			$validations['validateEmail'] = false;
		}
		else 
		{
			$validations['validateEmail'] = true;
		}


		if(!$upperCaseLetters || !$lowerCaseLetters || !$numbers || !$specialCharacters || (strlen($this->__get('password')) < 8))  
		{

			$validations['validatePassword'] = false;
		}
		else 
		{
			$validations['validatePassword'] = true;
		}

		if($this->__get('password') != $this->__get('confirmPassword'))  {

			$validations['validateConfirmPassword'] = false;
		}
		else 
		{
			$validations['validateConfirmPassword'] = true;
		}

		return $validations;

    }
  
	public function registerUser() 
	{

	    $query = "insert into users(full_name, email, password)values(:fullName, :email, :password)";

	    $statement = $this->connection->prepare($query); 
	    $statement->bindValue(':fullName', $this->__get('fullName'));
	    $statement->bindValue(':email', $this->__get('email'));
	    $statement->bindValue(':password', $this->__get('password'));
	    $statement->execute();

	    return $this; 

	}
    
	public function getUserByEmail() 
	{

	 	$query = "select 
					full_name, 
					email, 
					password 
				from 
					users 
				where 
					email = :email";

	 	$statement = $this->connection->prepare($query);
	 	$statement->bindValue(':email', $this->__get('email'));
	 	$statement->execute();

	 	return $statement->fetchAll(\PDO::FETCH_ASSOC); 
	}

	public function authenticate() 
	{

	     $query = "select 
						id, 
						full_name, 
						email 
					from  
						users 
					where 
						email = :email 
						and 
						password = :password";

	     $statement = $this->connection->prepare($query);

	     $statement->bindValue(':email', $this->__get('email')); 
	     $statement->bindValue(':password', $this->__get('password')); 
	     $statement->execute();

	     $returnedUser = $statement->fetch(\PDO::FETCH_ASSOC);

	
	     if(!empty($returnedUser)) 
		 { 
	
		    $this->__set('id', $returnedUser['id']);
		    $this->__set('fullName', $returnedUser['full_name']); 

	     }
	     
	     return $this;  
	}

	public function randomWhoToFollow()
	{
     
     $query = 'select 
				     u.id, 
				     u.full_name, 
				     u.profile_picture,
				     (
                     select
	                    count(*)
	                 from
		                 follows as f
		              where
		                 f.follower_user_id = :authenticated_user_id
		               and 
			              f.followed_user_id = u.id
		 	         ) as boolean_follow
				   from 
					   users as u
					where
						u.id != :authenticated_user_id
					order by 
						rand() limit 4
				   ';
				  

	 	$statement = $this->connection->prepare($query);
	 	$statement->bindValue(':authenticated_user_id', $this->__get('id'));
	 	$statement->execute();

	 	return $statement->fetchAll(\PDO::FETCH_ASSOC);
		
	}
    
    public function randomWhoToFollowHideViewedProfileUser($view_profile_user_id)  
    {

		$query = 'select 
				     u.id, 
				     u.full_name, 
				     u.profile_picture,
				     (
                    select
	                    count(*)
	                 from
		                 follows as f
		              where
		                 f.follower_user_id = :authenticated_user_id
		               and 
			              f.followed_user_id = u.id
		 	         ) as boolean_follow
				   from 
					   users as u
					where
						u.id != :authenticated_user_id AND u.id != :view_profile_user_id
					order by 
						rand() limit 4
				   ';

	 	$statement = $this->connection->prepare($query);
	 	$statement->bindValue(':authenticated_user_id', $this->__get('id'));
	 	$statement->bindValue(':view_profile_user_id', $view_profile_user_id);
	 	$statement->execute();

	 	return $statement->fetchAll(\PDO::FETCH_ASSOC);

   }

	public function getAll() 
	{
   
	 	$query = "select 
		 	         u.id, 
		 	         u.full_name, 
		 	         u.profile_picture,
		 	        (select
	                    count(*)
	                 from
		                 follows as f
		              where
		                 f.follower_user_id = :authenticated_user_id
		               and 
			              f.followed_user_id = u.id
		 	         ) as boolean_follow
		 	        from 
			 	       users as u
			 	    where 
				 	    u.full_name like :fullName and u.id != :authenticated_user_id";  
				 	

	 	$statement = $this->connection->prepare($query);
	 	$statement->bindValue(':fullName', '%'.$this->__get('fullName').'%'); 

	 	$statement->bindValue(':authenticated_user_id', $this->__get('id'));
	 	$statement->execute();

	 	return $statement->fetchAll(\PDO::FETCH_ASSOC);	

	}

	public function getUserData() 
	{

	 	$query = "select 
					full_name, profile_picture, header_image
				  from 
					users 
				  where 
					id = :id
				   ";

	 	$statement = $this->connection->prepare($query);
	 	$statement->bindValue(':id', $this->__get('id')); 
	 	$statement->execute();

	 	return $statement->fetch(\PDO::FETCH_ASSOC); 

	}

	public function getTotalTweets() 
	{

	 	$query = "select 
					count(*) as total_tweets 
				  from 
					tweets 
				  where 
					user_id = :user_id
				 	";

	 	$statement = $this->connection->prepare($query);
	 	$statement->bindValue(':user_id', $this->__get('id')); 
	 	$statement->execute();

	 	return $statement->fetch(\PDO::FETCH_ASSOC); 

	}

	public function getTotalFollows() 
	{
		$query = "select 
					count(*) as total_follows 
				  from 
					follows 
				  where
					follower_user_id = :authenticated_user_id
				  ";

			$statement = $this->connection->prepare($query);
		    $statement->bindValue(':authenticated_user_id', $this->__get('id'));
			$statement->execute(); 

			return $statement->fetch(\PDO::FETCH_ASSOC); 

	}

	public function getTotalFollowers() 
	{
	 	$query = "select 
					count(*) as total_followers 
				  from 
					follows 
				  where
					followed_user_id = :authenticated_user_id
				   ";
     
	 	$statement = $this->connection->prepare($query);
	 	$statement->bindValue(':authenticated_user_id', $this->__get('id'));
	 	$statement->execute();

	 	return $statement->fetch(\PDO::FETCH_ASSOC); 

	}
}

?>