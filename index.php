<?php
session_start();
$_SESSION['id'] = 1;

//check for session here during intefration by replacing the $_SESSION['id'] with your auth check
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title>Web Chat</title>
	<meta charset="utf-8">
	<link rel="shortcut icon" href="./favicon.ico" />
	<meta name="author" content="author">
	<meta name="description" content="seo description"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0" />
	<meta property="og:description" content="seo description" />
	<meta property="og:url"content="www.seo.com" />
	<meta property="og:title" content="seo description" />
	<meta property="og:image" content="./favicon.ico" />

		<link rel="stylesheet" href="./css/w3-theme-pink.css">
		<link rel="stylesheet"  href="./css/font-awesome-4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
		<link rel="stylesheet"  href="./css/w3mobile.css">
		<link rel="stylesheet"  href="./css/w3.css">

		</script>

		

    <style type="text/css">
   .w3-text-light-pink {
   	color:rgb(250,212,226);
   }
    a {

    		text-decoration:none;
    	}
@media screen and (max-width:600px)
{
}
@media screen and (min-width:600px)
{

}
/*uploaD button styling*/
.upload-btn-wrapper {
  position: relative;
  overflow: hidden;
  display: inline-block;
}

.btn {
  border: 2px solid pink;
  color: gray;
  background-color: white;
  padding: 8px 20px;
  border-radius: 8px;
  font-size: 20px;
  font-weight: bold;
}

.upload-btn-wrapper input[type=file] {
  font-size: 100px;
  position: absolute;
  left: 0;
  top: 0;
  opacity: 0;
}
    </style>
    <?php
require "chatStyle.php";
?>
</head>
<body>

<body onload="getOwnerId()">
<?php
require("nav.php");

?>


<div class="w3-row">

<div class="w3-col m3 s3 l3">
	<?php
require "chat_profile.php";

?>
</div>

<div class="w3-center chat outer w3-col s5 m5 l5 w3-light-grey">
	<?php
require "chat.php";

?>
</div>

<div class="w3-col s4 m4 l4">
	<?php
require "chat_friend_list.php";

?>
</div>

</div>
<script type="text/javascript" src="./js/controller.js"></script>
<!--<script type="text/javascript" src="./js/process.js"></script>-->
<?php

require_once "js/process.js.php";
?>
</body>
</html>