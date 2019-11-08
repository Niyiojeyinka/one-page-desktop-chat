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

}