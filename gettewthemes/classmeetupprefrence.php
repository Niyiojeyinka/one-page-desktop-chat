<?php
session_start();
require 'classmeetupvalidate.php';
/**
 * class to handle meetupprfernce of user
 */
class setuppref extends meetupvalidate
{
  private $userid;

  function __construct(){
  $this->createConnection();
  if(isset($_SESSION["userid"])){
  $this->userid = $this->clean_input($_SESSION["userid"]);
  }
  }

  //public function to set user meetup prefernce starts here
  public function setmeetuppref($skincolor,$prefheight,$size,$danceskill){
  $conn = $this->conn;
  $uid = $this->userid;
  $skincolor = $this->clean_input($skincolor);
  $size = $this->clean_input($size);
  $prefheight = $this->clean_input($prefheight);
  $danceskill = $this->clean_input($danceskill);
  if(empty($skincolor) || empty($size) || empty($prefheight) || empty($danceskill) || empty($uid)){
  return "Failed missing values to continue";
  }
  $a = array("skincolor"=>$skincolor,"size"=>$size,"height"=>$prefheight,"danceskill"=>$danceskill);
  $a = json_encode($a);
  $a = $conn->real_escape_string($a);
  $sql = "update oaumeetupusers set meetup_preference = '$a' where userid='$uid' limit 1";
  if($conn->query($sql) == "true"){
  return "success";
  }else{
  return "Failed".$conn->error;
  }
  }
  //public function to set user meetup prefernce ends here
}
?>