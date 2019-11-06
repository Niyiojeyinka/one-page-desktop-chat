<?php
/**
 * 
 */
DEFINE("server","localhost");
DEFINE("username","root");
DEFINE("password","");
DEFINE("dbname","oaumeetupdb");
$conn = new mysqli(server, username, password,dbname);

if(mysqli_connect_error()){
echo mysqli_connect_error();
}else{
echo "";
}
$date = time();
$sql = "select * from oaumeetupusers where '$date' - lastlogindate < '60' limit 5";
$result = $conn->query($sql);
echo $result->num_rows;
/*$sql = "select meetup_preference from oaumeetup uusers where meetup_preference != '' limit 1";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$meetup_preference = $row["meetup_preference"];
if(!empty($meetup_preference)){
$meetup_preference = json_decode($meetup_preference,true);
}
 $sql = "select userid,username,institution,gender,avatar from oaumeetupusers where ";
 $n = 0;
 foreach($meetup_preference as $key => $value){
 ++$n;
$value = $value;
if($n == 1){
$sql .=" gender != 'male' and meetup_preference like '%$value%'";
}elseif($n > 1){
$sql .=" or gender != 'male' and meetup_preference like '%$value%'";
} 
}//for each loop 
$sql .=" order by id desc limit 10";

$result = $conn->query($sql);
echo $sql."<br><br>";
echo $conn->error."<br><br>";

echo $result->num_rows;
$row = $result->fetch_assoc();
echo $row["username"];

//echo $result->num_rows;
echo rand(0,100)."%";*/