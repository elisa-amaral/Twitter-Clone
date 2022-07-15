<?php 

namespace App\Controllers;

use Miniframework\Controller\Action; 
use Miniframework\Model\Container; 


class AppController extends Action {

	public function validateAuthentication() 
	{

		session_start();

		if(!isset($_SESSION['id']) || empty($_SESSION['id']) || 
			!isset($_SESSION['fullName']) || empty($_SESSION['fullName'])) 
		{

			header ('Location: /?login=error');

		}
	} 

	public function timeline() 
	{  

		$this->validateAuthentication(); 

   		$tweet = Container::getModel('Tweet');

		$user = Container::getModel('User');

   
		if(empty($_GET['view_profile_user_id']))
		{
			$tweet->__set('user_id', $_SESSION['id']);
			$user->__set('id', $_SESSION['id']);
			$this->view->tweets = $tweet->getAll();	
			$this->view->view_profile_user_id = $_SESSION['id'];
			$this->view->user_is_on_home_timeline = true;

         
		}

		else 
		{  
			$tweet->__set('user_id', $_GET['view_profile_user_id']);
			$user->__set('id', $_GET['view_profile_user_id']);
			$this->view->tweets = $tweet->getViewedProfileUserTweets();
			$this->view->view_profile_user_id = $_GET['view_profile_user_id'];
			$this->view->user_is_on_home_timeline = false;

		}
      
		$this->view->userData = $user->getUserData();
		$this->view->totalTweets = $user->getTotalTweets();
		$this->view->totalFollows = $user->getTotalFollows();
		$this->view->totalFollowers = $user->getTotalFollowers();

		if($this->view->userData['profile_picture'] == '')
		{
			$this->view->defaultProfilePicture = true;
		}
		else
		{
			$this->view->defaultProfilePicture = false;
		}

		if($this->view->userData['header_image'] == '')
		{
			$this->view->defaultHeaderImage = true;
		}
		else
		{
			$this->view->defaultHeaderImage = false;
		}

		$randomWhoToFollow = Container::getModel('User');
		$randomWhoToFollow->__set('id', $_SESSION['id']);

		$hide_viewed_profile = isset($_GET['hide_viewed_profile']) ? $_GET['hide_viewed_profile'] : '';

		if($hide_viewed_profile == '')
		{  
			$this->view->randomWhoToFollow = $randomWhoToFollow->randomWhoToFollow();
			$this->view->showFollowButton = false;
			$this->view->showUnfollowButton = false;
		}
		else 
		{ 
		
			if($hide_viewed_profile == 'yes') 
			{  

				$this->view->randomWhoToFollow = $randomWhoToFollow->randomWhoToFollowHideViewedProfileUser($_GET['view_profile_user_id']);
				$this->view->followed_user_id = $_GET['view_profile_user_id'];
				$this->view->showFollowButton = true;
				$this->view->showUnfollowButton = false;

			}
			else if($hide_viewed_profile == 'no')
			{  

				$this->view->randomWhoToFollow = $randomWhoToFollow->randomWhoToFollow();
				$this->view->followed_user_id = $_GET['view_profile_user_id'];
				$this->view->showFollowButton = false;
				$this->view->showUnfollowButton = true;
			
			}
			
		}    
		$this->render('timeline');
        
	} 

	public function followOnTimeline() 
	{

		$this->validateAuthentication(); 

		$follower_user = Container::getModel('FollowUnfollow');

		$follower_user->__set('follower_user_id', $_SESSION['id']); 

		$followed_user_id = isset($_GET['followed_user_id']) ? $_GET['followed_user_id'] : '';

     	$follower_user->follow($followed_user_id); 

     	header('Location: /timeline');

	}

   public function followUnfollowOnViewedProfile() 
   {

		$this->validateAuthentication();

		$follower_user = Container::getModel('FollowUnfollow');

		$follower_user->__set('follower_user_id', $_SESSION['id']); 

		$action = isset($_GET['action']) ? $_GET['action'] : '';

		$followed_user_id = isset($_GET['followed_user_id']) ? $_GET['followed_user_id'] : '';

		if ($action == 'follow') 
		{

			$follower_user->follow($followed_user_id); 

			header("Location: /timeline?view_profile_user_id=".$followed_user_id."&hide_viewed_profile=no");

		} 
		else if ($action = 'unfollow') 
		{

			$follower_user->unfollow($followed_user_id); 

			header("Location: /timeline?view_profile_user_id=".$followed_user_id."&hide_viewed_profile=yes");
		}

 
	} 

	public function findFriends() 
	{
		$this->validateAuthentication();

		$sidePanelDataUser = Container::getModel('User');
		$sidePanelDataUser->__set('id', $_SESSION['id']);

		$this->view->userData = $sidePanelDataUser->getUserData();
		$this->view->totalTweets = $sidePanelDataUser->getTotalTweets();
		$this->view->totalFollows = $sidePanelDataUser->getTotalFollows();
		$this->view->totalFollowers = $sidePanelDataUser->getTotalFollowers();

		if($this->view->userData['profile_picture'] == '')
		{
			$this->view->defaultProfilePicture = true;
		}
		else
		{
			$this->view->defaultProfilePicture = false;
		}

		if($this->view->userData['header_image'] == '')
		{
			$this->view->defaultHeaderImage = true;
		}
		else
		{
			$this->view->defaultHeaderImage = false;
		}
		
		$search_for = isset($_GET['search_for']) ? $_GET['search_for'] : ''; 
		$this->view->search_for = $search_for;
     
        $searchResultsUsers = array(); 

		if($search_for != '') 
		{ 
			 
			$searchResultsUser = Container::getModel('User');
			$searchResultsUser->__set('fullName', $search_for);
			$searchResultsUser->__set('id', $_SESSION['id']);
			$searchResultsUsers = $searchResultsUser->getAll();

		}
        
		$this->view->searchResultsUsers = $searchResultsUsers;

		$this->render('findFriends'); 

	} 


	public function followUnfollowOnSearchResults() 
	{
		$this->validateAuthentication(); 

		$follower_user = Container::getModel('FollowUnfollow');

		$follower_user->__set('follower_user_id', $_SESSION['id']); 

		$action = isset($_GET['action']) ? $_GET['action'] : '';

		$search_for = isset($_GET['search_for']) ? $_GET['search_for'] : '';

		$followed_user_id = isset($_GET['followed_user_id']) ? $_GET['followed_user_id'] : '';

		if ($action == 'follow') 
		{
			$follower_user->follow($followed_user_id); 

		}
		else if ($action = 'unfollow') 
		{

			$follower_user->unfollow($followed_user_id); 
		} 


		header('Location: /find_friends?search_for='.$search_for);
		

	} 

	public function publishTweet() 
	{

		$this->validateAuthentication();

		$tweet = Container::getModel('Tweet');

		$tweet->__set('tweet', $_POST['tweet']);
		$tweet->__set('user_id', $_SESSION['id']);

		$tweet->publish();

		header('Location: /timeline');
        
	} 

	public function deleteTweet() 
	{

		$this->validateAuthentication();

		$tweet = Container::getModel('Tweet');

		$tweet->__set('id', $_GET['id']); 
		$tweet->__set('user_id', $_SESSION['id']);
		
		$tweet->delete();

		header('Location: /timeline');
        
	} 

} 

?>
