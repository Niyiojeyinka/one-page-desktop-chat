<?php


/**
 * @package : Api.php
 * @author : @niyiojeyinka
 * @descriptions :" class to handle ajax/fetch function requests"
 */

require 'ChatProfile.php';
class Api {
	  public $profile;
	public function __construct()
	{
      $this->profile = new ChatProfile();
	}


    /*
     *@parameter : id of user
     * returns json consisting of the user's friends profiles
     */
    public function get_friends_profiles($id='')
    {
     
     echo json_encode($this->profile->getFriendsProfiles($id),true);
    }

}