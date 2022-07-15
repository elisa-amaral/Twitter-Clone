<?php 

namespace App\Models; 

use Miniframework\Model\Model;

class FollowUnfollow extends Model 
{

   private $follower_user_id;

	public function __get($attribute) 
	{
		return $this->$attribute;
	}

	public function __set($attribute, $value) 
	{

		$this->$attribute = $value;
	}  

	public function follow($followed_user_id) 
	{
		
		$query = "insert into follows (follower_user_id, followed_user_id)values(:follower_user_id, :followed_user_id)";

		$statement = $this->connection->prepare($query);

		$statement->bindValue(':follower_user_id', $this->__get('follower_user_id')); 

		$statement->bindValue(':followed_user_id', $followed_user_id);

		$statement->execute();

		return true;

	} 

	public function unfollow($followed_user_id) 
	{

		$query = "delete from follows where follower_user_id = :follower_user_id and followed_user_id = :followed_user_id";

		$statement = $this->connection->prepare($query);

		$statement->bindValue(':follower_user_id', $this->__get('follower_user_id'));

		$statement->bindValue(':followed_user_id', $followed_user_id); 

		$statement->execute();

		return true;

	} 

}


?>