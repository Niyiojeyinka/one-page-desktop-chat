<?php
session_start();
require "classmeetupvalidate.php";
/**
 * php file  for handling signup database operations
 */
class oaumeetupsignup extends meetupvalidate
{
	private $name;
	private $username;
	private $institution;
	private $signupgender;
	private $email;
	private $phone;
	private $password;


	 function __construct()
  {
    $this->createConnection();
  }
    public function setName($name){
		$this->name = $this->clean_input($name);
	}

	private function getName(){
		return $this->name;
	}

	public function setUsername($uname){
		$this->username = $this->clean_input($uname);
	}
	
	private function getUsername(){
		return $this->username;
	}

	public function setInstitution($school){
		$this->institution = $this->clean_input($school);
	}
	
	private function getInstitution(){
		return $this->institution;
	}

	public function setGender($gender){
		$this->signupgender = $this->clean_input($gender);
	}
	
	private function getGender(){
		return $this->signupgender;
	}


	public function setEmail($email){
		$this->email = $this->clean_input($email);
	}
	
	private function getEmail(){
		return $this->email;
	}

	public function setPhone($phone){
		$this->phone = $this->clean_input($phone);
	}
	
	private function getPhone(){
		return $this->phone;
	}

	public function setPassword($pass){
		$this->password = $this->clean_input($pass);
	}
	
	private function getPassword(){
		return $this->password;
	}

	public function insertuser(){
		$name = $this->getName();
		$username = $this->getUsername();
		$institution = $this->getInstitution();
		$gender = $this->getGender();
		$email = $this->getEmail();
		$phone = $this->getPhone();
		$pass = md5($this->getPassword());
		$conn = $this->conn;
		$numsign = '';
		$query50 = "select numsign from numsignups limit 1";
		$result50 = $conn->query($query50);
		if($result50->num_rows == 1){
		$row50 = $result50->fetch_assoc();
		$numsign = $row50["numsign"];
		if(empty($numsign)){
		$numsign = 0;
		}
		++$numsign;
		$sql50 = "update numsignups set numsign ='$numsign' limit 1";
		}else{
        $numsign = 0;
        ++$numsign;
        $sql50 = "insert into numsignups(numsign) values('$numsign')";
		}

		$query = "select id from oaumeetupusers where email = '$email' or phonenumber = '$phone' limit 1";
		$query1 = "select id from oaumeetupusers where username ='$username' limit 1";
		$result = $conn->query($query);
		$result1 = $conn->query($query1);
		if ($result->num_rows > 0) {
		return "<span class='w3-text-red w3-bold'>Email or Phone number is already in use</span>";	
		}elseif ($result1->num_rows > 0) {
		return "<span class='w3-text-red w3-bold'>Username already exists please try another</span>";	
		}else{
		$userid = md5(rand(0,7000).rand(0,4000));
		$date = time();
	    $landpagelink = array();
	    $query7 = "";

		$sql1 = "select landpagelink from notification where type='f' limit 1";
		$result1 = $conn->query($sql1);
		if($result1->num_rows == 1){
		$row = $result1->fetch_assoc();
		$landpagelink = $row["landpagelink"];
		if(empty($landpagelink)){
		$landpagelink = array();
		}else{
		$landpagelink = json_decode($landpagelink,true);
		}
		if(!in_array($userid,$landpagelink)){
		array_push($landpagelink,$userid);
		}
		$landpagelink = json_encode($landpagelink);
		$query7 = "update notification set landpagelink = '$landpagelink',date = '$date' where type ='f' limit 1";
		}else{
		array_push($landpagelink,$userid);
		$landpagelink = json_encode($landpagelink);
		$query7 = "insert into notification(creatornotifyid,type,landpagelink,date) values('oaumeetup','f','$landpagelink' ,'$date')";
		}

        $query10 = "insert into notification(creatornotifyid,type,date) values('$userid','a','$date')";
		$query ="insert into oaumeetupusers(userid,name,username,email,phonenumber,institution,gender,password,signupdate,activated) values('$userid','$name','$username','$email','$phone','$institution','$gender','$pass','$date','1')";
		if ($conn->query($query) == true) {
		$conn->query($query10);
	    $conn->query($query7);
		return "success";
		}else{return "<span class='w3-text-red w3-bold'>Failed to register user please try again ".$conn->error."</span>";}}
    }

}
?>