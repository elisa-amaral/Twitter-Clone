<?php 

namespace App;

class Connection {

	public static function getDB() {
     
	    try 
		{

	    	$connection = new \PDO
			( 

	    		"mysql:host=localhost;dbname=twitter_clone;charset=utf8",
	    		"root",
	    		"" 
	    	);

	    	return $connection;
	    
	    } 
		catch (\PDOExcepion $error) 
		{ 
	    	echo $error;
	    }

	}
}

?>