<?php 

namespace Miniframework\Model;
use App\Connection; 

class Container 
{

	public static function getModel($model) { 

		$connection = Connection::getDB();
    
		$class = "App\\Models\\".ucfirst($model); 

     	return new $class($connection); 
        
	}
}

?>