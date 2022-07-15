<?php 

namespace App; 

use Miniframework\Init\Bootstrap; 

class Route extends Bootstrap 
{

    protected function initRoutes() 
	{

	   $routes['home'] = array(
	       'route' => '/',
	       'controller'=> 'IndexController',
	       'action' => 'index'
	   );

	   $routes['sign_up'] = array(
	       'route' => '/sign_up',
	       'controller'=> 'IndexController',
	       'action' => 'signUp'
	   );

	   $routes['create_account'] = array(
	       'route' => '/create_account',
	       'controller'=> 'IndexController',
	       'action' => 'createAccount'
	   );

	   $routes['authenticate'] = array(
	       'route' => '/authenticate',
	       'controller'=> 'AuthController',
	       'action' => 'authenticate'
	   );

	   $routes['log_out'] = array(
	       'route' => '/log_out',
	       'controller'=> 'AuthController',
	       'action' => 'logOut'
	   );

	   $routes['timeline'] = array(
	       'route' => '/timeline',
	       'controller'=> 'AppController',
	       'action' => 'timeline'
	   );
     
	   $routes['follow_on_timeline'] = array(
	       'route' => '/follow_on_timeline',
	       'controller'=> 'AppController',
	       'action' => 'followOnTimeline'
	   );

	   $routes['find_friends'] = array(
		'route' => '/find_friends',
		'controller'=> 'AppController',
		'action' => 'findFriends'
	   
	   );

	   $routes['follow_unfollow'] = array(
	       'route' => '/follow_unfollow',
	       'controller'=> 'AppController',
	       'action' => 'followUnfollowOnSearchResults'
	   );
 

	   $routes['follow_unfollow_on_viewed_profile'] = array(
	       'route' => '/follow_unfollow_on_viewed_profile',
	       'controller'=> 'AppController',
	       'action' => 'followUnfollowOnViewedProfile'
	   );

	   $routes['publish_tweet'] = array(
		'route' => '/publish_tweet',
		'controller'=> 'AppController',
		'action' => 'publishTweet'
		);

		$routes['delete_tweet'] = array(
			'route' => '/delete_tweet',
			'controller'=> 'AppController',
			'action' => 'deleteTweet'
		);

	   $this->setRoutes($routes);
    }

} 

?>