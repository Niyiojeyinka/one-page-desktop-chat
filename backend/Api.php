<?php
      session_start();


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
     * returns json consisting of the user's profiles
     */
    public function get_user_profile_by_id($id='')
    {
     
     echo json_encode($this->profile->getUserProfileById($id),true);
    }
   
    /*
     *@parameter : id of user
     * returns json consisting of the user's friends profiles
     */
     public function get_friends_profiles($id)
    {
      $data=array();
     $friends=$this->profile->getFriendsProfiles($id);
      foreach($friends as $each_profile) {
        unset($each_profile['password']);

      $conversation=$this->profile->getConversation( $_SESSION['id'],$each_profile['id']);
      if ($conversation == 0) {
      	continue;
      }
      $messages= $this->profile->getMessagesByConversationId($conversation['id']);

      $each_profile['lastMessage']  = $messages[count($messages)>0?count($messages)-1:0];
      $each_profile['unreadMessages']= $this->profile->getUnreadMessagesByConversationId($conversation['id']);
      $each_profile['lastMessageTime']  = date( "F j, Y, g:i a",$messages[count($messages)-1]['time']);  
      $each_profile['status']  = time()-$each_profile['lastlog']  >120?"offline":"online";
 

       array_push($data, $each_profile);

      }


    echo  json_encode($data);

    }
    public function get_conversation()
    {
      $messages= $this->profile->getMessagesByConversationId($_POST['conversation_id']);
       $message_array=array();
      foreach ($messages as $message) {
       $message['this_id'] = $_SESSION['id'] ;
       array_push($message_array, $message);       
      }
      echo json_encode($message_array);

    }

}