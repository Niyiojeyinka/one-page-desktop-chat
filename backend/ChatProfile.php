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
	/*
     *@parameters :$firstparty-id of the onwnerof this session,second id of message receiver 
     * returns conversation row array
     */
   public function getConversation($firstPartyId,$SecondPartyId)
  {
  return $this->db->select("SELECT * FROM conversation WHERE receiver_id =$firstPartyId && sender_id=$SecondPartyId OR receiver_id =$SecondPartyId && sender_id=$firstPartyId");

  }

  /*
     *@parameter : conversation id
     *@returns an array of messages of a converstion
     */
  public function getMessagesByConversationId($conversationId)
  {
  	$query = $this->db->selectAll("SELECT * FROM messages WHERE conversation_id = $conversationId");
  	return $query;
  }

  /*
     *@parameter : conversation id
     *@returns number of unread message
     */
  public function getUnreadMessagesByConversationId($conversationId)
  {
  	$query = $this->db->selectAll("SELECT * FROM messages WHERE conversation_id = $conversationId AND status='sent'");
  	//var_dump($query);exit();
  	return count($query);
  }
  /*
     *@parameter : conversation id
     *@returns the conversation array
     */
  public function getConversationById($conversationId)
  {
    $query = $this->db->select("SELECT * FROM conversation WHERE id = $conversationId");
    return $query;
  }
  public function saveMessage($message,$conversation_id,$sender_id)
  {
    $conversation = $this->getConversationById($conversation_id);
    $receiver_id=$conversation['receiver_id'];
    if ($conversation['sender_id'] !=$sender_id ) {
     $receiver_id = $conversation['sender_id'];
    }
    $now =time();
$query= "INSERT INTO messages (`text`,receiver_id,sender_id,conversation_id,status,type,`time`) VALUES ('".$message."',".$receiver_id.",".$sender_id.",$conversation_id,'sent','textonly',".$now.");";
//var_dump($query);exit();
    $this->db->query($query);
  }
}