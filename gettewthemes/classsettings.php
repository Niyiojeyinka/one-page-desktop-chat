<?php
session_start();
require "classmeetupvalidate.php";
/**
  *class to handle users settings starts here 
  */
 class settings extends meetupvalidate
 {
   private $userid;

   function __construct(){
   $this->createConnection();
   if(isset($_SESSION["userid"])){
   $this->userid = $this->clean_input($_SESSION["userid"]);
   }
   }

   //public function to remove profile picture starts here
   public function removepic(){
   $conn = $this->conn;
   $uid = $this->userid;
   if(empty($uid)){
   return "failed missing values to continue";
   }
   $sql = " select avatar from oaumeetupusers where userid ='$uid' limit 1";
   $result = $conn->query($sql);

   //if result->nums rows
   if($result->num_rows == 1){
   $row = $result->fetch_assoc();
   $avatar = $row["avatar"];

   if(empty($avatar)){
   return "success";
   }else{
   $sql2 = "update oaumeetupusers set avatar = '' where userid = '$uid' limit 1";
   if($conn->query($sql2) == "true"){
   if(file_exists($avatar)){
   unlink($avatar);
   }
   return "success";
   }else{
   return "failed could not remove profile picture please try again".$conn->error;
   }
   }

   }else{
   return "failed something went wrong please try again".$conn->error;
   }
   }
   //public function to remove profile picture ends here

   //public function to change password starts here
   public function changepass($oldpass,$newpass){
   $conn = $this->conn;
   $uid = $this->userid;
   $oldpass = $this->clean_input($oldpass);
   $newpass = $this->clean_input($newpass);
   if(empty($oldpass) || empty($newpass) || empty($uid)){
   return "failed missing values to continue";
   }
   if(strlen($oldpass) < 10 || strlen($oldpass) > 10){
   return "failed oldpassword length shorter or greater than required length";
   }
   if(strlen($newpass) < 10 || strlen($newpass) > 10){
   return "failed newpassword length shorter or greater than required length";
   }
   $oldpass = md5($oldpass);
   $sql = "select password from oaumeetupusers where userid ='$uid' and password ='$oldpass' limit 1";
   $result = $conn->query($sql);
   if($result->num_rows == 1){
   $row = $result->fetch_assoc();
   $dbpass = $row["password"];
   if($oldpass != $dbpass){
   return "failed old password doesnot match with the one prviously registered on this platform";
   }
   $newpass = md5($newpass);
   $sql2 = "update oaumeetupusers set password = '$newpass' where userid = '$uid' limit 1";
   if($conn->query($sql2) == "true"){
   $_SESSION["pass"] = $newpass;
   return "success";
   }else{
   return "failed could not change password at this time please try again".$conn->error;
   }
   }else{
   return "failed old password doesnot match with the one previously registered on this platform";
   }

   }
   //public function to chnge password ends here

   //public function to handle change of phone number starts here
   public function changephone($oldphone,$newphone){
   $conn = $this->conn;
   $uid = $this->userid;
   $oldphone = $this->clean_input($oldphone);
   $newphone = $this->clean_input($newphone);
   if(empty($oldphone) || empty($newphone) || empty($uid)){
   return "failed missing values to continue";
   }
   if(strlen($oldphone) < 11 || strlen($oldphone) > 11){
   return "failed phonenumber length shorter or greater than required length";
   }
   if(strlen($newphone) < 11 || strlen($newphone) > 11){
   return "failed phonenumber length shorter or greater than required length";
   }  
   $sql = "select phonenumber from oaumeetupusers where userid = '$uid' and phonenumber = '$oldphone' limit 1";
   $result = $conn->query($sql);
   if($result->num_rows == 1){
   $row = $result->fetch_assoc();
   $dbphonenum = $row["phonenumber"];
   if($dbphonenum != $oldphone){
   return "failed old phonenumber provided doesnot match with the one previously registered on this platform";
   }
   //check if new number provided already exists on this platform
   $sql2 = "select id from oaumeetupusers where phonenumber ='$newphone' limit 1";
   $result2 = $conn->query($sql2);
   if($result2->num_rows == 1){
   return "failed new phonenumber provided is already in use by someone else on this platform";
   }
   //check if new number provided already exists on this platform
   $sql2 = "update oaumeetupusers set phonenumber = '$newphone' where userid = '$uid' limit 1";
   if($conn->query($sql2)){
   return "success";
   }else{
   return "failed could not change number at this time please try again".$conn->error;
   }
   }else{
   return "failed old phonenumber provided doesnot match with the one previously registered on this platform";
   }

   }
   //public function to handle change of phone number ends here

   //public function to change username starts here
   public function changename($newusername){
   $conn = $this->conn;
   $uid = $this->userid;
   $uname = $this->clean_input($newusername);
   if(empty($uname) || empty($uid)){
   return "failed missing values to continue";
   }
   if($uname == $_SESSION["uname"]){
   return "success";
   }
   if(strlen($uname) > 15 || strlen($uname) < 3){
   return "failed username must be between 3-15 characters long";
   }elseif (!preg_match("/^[a-zA-Z@]*$/",$uname)) {
   return "failed username can only contain letters and @ and no whitespace";
   }
   $sql = "select id from oaumeetupusers where userid !='$uid' and username = '$uname' limit 1";
   $result = $conn->query($sql);
   if($result->num_rows == 1){
   return "failed username is already in use please try another";
   }
   $sql2 = "update oaumeetupusers set username ='$uname' where userid ='$uid' limit 1";
   if($conn->query($sql2) == true){
   $_SESSION["uname"] = $uname;
   return "success";
   }else{
   return "failed could not change username at this time please try again".$conn->error;
   }

   }
   //public function to chnage username ends here

   //public function to log out starts here
   public function logout(){
   if(isset($_SESSION["userid"]) && isset($_SESSION["uname"]) && isset($_SESSION["pass"])){
   unset($_SESSION["userid"]);
   unset($_SESSION["uname"]);
   unset($_SESSION["pass"]);
   session_destroy();
   if(isset($_SESSION["userid"]) && isset($_SESSION["uname"]) && isset($_SESSION["pass"])){
   return "failed could not log you out at this time please try again";
   }else{
   return "success";
   }
   }else{
   return "failed you are already logged out";
   }
   }
   //public function to log out ends here


 } 
?>