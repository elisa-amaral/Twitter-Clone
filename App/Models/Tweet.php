<?php 

namespace App\Models; 

use Miniframework\Model\Model;

class Tweet extends Model 
{

    private $id;
    private $user_id;
    private $tweet;
    private $datetime_published;
   
	public function __get($attribute) 
	{
		return $this->$attribute;
	}

	public function __set($attribute, $value) 
	{

		$this->$attribute = $value;
	}

	public function publish() 
	{
		$query = "insert into tweets(user_id, tweet) values (:user_id, :tweet)";

		$statement = $this->connection->prepare($query);
		$statement->bindValue(':user_id', $this->__get('user_id'));
		$statement->bindValue(':tweet', $this->__get('tweet'));
		$statement->execute();

		return $this;

	}

	public function delete() 
	{
		
		$query = "delete 
					  from 
				  tweets 
					  where
				  id = :id
					  and 
				  user_id = :user_id
				  ";

		$statement = $this->connection->prepare($query);
		$statement->bindValue(':id', $this->__get('id'));
		$statement->bindValue(':user_id', $this->__get('user_id'));
		$statement->execute();

		return $this; 

	}

	public function getAll() 
	{

		$query = "select 
				      t.id, 
				      t.user_id, 
					  u.full_name, 
					  t.tweet, 
					  DATE_FORMAT(t.datetime_published, '%M %d, %Y at %l:%i %p') as formated_datetime
				  from 
					  tweets as t
					  left join users as u on (t.user_id = u.id)
				  where 
					  t.user_id = :user_id
					  or 
					  t.user_id in (SELECT followed_user_id FROM follows WHERE follower_user_id = :user_id)
				  order by 
				      t.datetime_published desc
				   ";

		$statement = $this->connection->prepare($query);
		$statement->bindValue(':user_id', $this->__get('user_id')); 
		$statement->execute();

		return $statement->fetchAll(\PDO::FETCH_ASSOC); 

	}

	public function getViewedProfileUserTweets() 
	{

		$query = "select 
				      t.id, 
				      t.user_id, 
					  u.full_name, 
					  t.tweet, 
					  DATE_FORMAT(t.datetime_published, '%M %d, %Y at %l:%i %p') as formated_datetime
				  from 
					  tweets as t
					  left join users as u on (t.user_id = u.id)
				  where 
					  t.user_id = :user_id		  
				  order by 
				      t.datetime_published desc
				   ";

		$statement = $this->connection->prepare($query);
		$statement->bindValue(':user_id', $this->__get('user_id')); 
		$statement->execute();

		return $statement->fetchAll(\PDO::FETCH_ASSOC); 

	}

}

?>