<?php

/**
 * @package : ChatProfile.php
 * @author : @niyiojeyinka
 * @descriptions :" class to handle backend of getting profile details"
 */
require 'Database.php';
class ChatProfile
{
	
	public $db;

	public function __construct()
	{
		$this->db = new Database();
	}
     
     /*
     *@parameter : id of user
     * returns an array of users properties like firstname,lastname etc
     */
	public function getUserProfileById($id)
	{
		return $this->db->select("SELECT * FROM users WHERE id=$id");
	}
     /*
     *@parameter : id of user
     * returns an array of the user friend with each friends properties like firstname,lastname etc
     */
	public function getFriendsProfiles($id)
	{
		$user = $this->getUserProfileById($id);
		$friends_id = json_decode($user['friends'],true);
		$friends = [];
		foreach ($friends_id as $eachId) {
        array_push($friends, $this->getUserProfileById($eachId));
		}
		return $friends;
	}
   
}