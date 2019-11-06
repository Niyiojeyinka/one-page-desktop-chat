<?php
?>
<!DOCTYPE html>
<html>
<head>
<title>oaumeetup.com|Admin</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<meta name="author" content="Oausocial website">
<meta name="description" content=" social & meetup website">
<link rel="stylesheet" type="text/css" href="w3.css">
<link rel="stylesheet" type="text/css" href="font-awesome-4.7.0\css\font-awesome.css">
<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
<script type="text/javascript" src="lazysizemin.js"></script>
</head>
<body  class="w3-light-grey">
<!--navbar starts here-->
<div class=" w3-bar w3-top  w3-card w3-blue">
<button id='totalusers' class="w3-bar-item w3-hover-white w3-hover-text-blue" style="padding:0;width:20%;">Total users</button>
<button id='totalnumchat' class="w3-bar-item w3-hover-white w3-hover-text-blue"style="padding:0;width:20%;">Total num chat</button>
<button id='totalnumachat' class="w3-bar-item w3-hover-white w3-hover-text-blue"style="padding:0;width:20%;">Total num achat</button>
<button id='totalnumstories' class="w3-bar-item w3-hover-white w3-hover-text-blue"style="padding:0;width:20%;">Total num stories</button>
<button id='totalstat' class="w3-bar-item w3-hover-white w3-hover-text-blue"style="padding:0;width:20%;">Stat</button>
</div>
<!--navbar ends here-->	
<!--mainpage starts here-->
<div style="margin-top:90px;">
<!--user ctn-->
<ul class=" w3-display-container w3-ul" id="usersctn" style="height:100vh;">
<span id='getuserprg'class="w3-display-middle w3-text-blue"><i class="fa fa-spinner w3-spin"></i> getting users</span>
</ul>
<!--user ctn-->
<!--chatctn-->
<ul class=" w3-display-container w3-hide w3-ul"style="height:100vh;" id="chatctn">
<span id='getchatprg'class="w3-display-middle w3-text-blue"><i class="fa fa-spinner w3-spin"></i> getting chat</span>
</ul>
<!--chatctn-->

<!--achatctn-->
<ul class=" w3-display-container w3-hide w3-ul"style="height:100vh;" id="achatctn">
<span id='getachatprg'class="w3-display-middle w3-text-blue"><i class="fa fa-spinner w3-spin"></i> getting achat</span>
</ul>
<!--achatctn-->

<!--stories-->
<ul class=" w3-display-container w3-hide w3-ul"style="height:100vh;" id="statctn">
<span id='getstatprg'class="w3-display-middle w3-text-blue"><i class="fa fa-spinner w3-spin"></i> getting stat</span>
</ul>
<!--stories-->

<!--stories-->
<ul class=" w3-display-container w3-hide w3-ul"style="height:100vh;" id="storiesctn">
<span id='getstoriesprg'class="w3-display-middle w3-text-blue"><i class="fa fa-spinner w3-spin"></i> getting stories</span>
</ul>
<!--stories-->

</div>
<!--mainpage ends here-->
<!--script-->
<script type="text/javascript">
$(function(){
var usersctn=chatctn=achatctn=statctn=storiesctn=getuserprg=getchatprg=getstatprg=getstoriesprg="";
usersctn = $("#usersctn");
chatctn = $("#chatctn");
achatctn = $("#achatctn");
statctn = $("#statctn");
storiesctn = $("#storiesctn");
getuserprg = $("#getuserprg");
getchatprg = $("#getchatprg");
getstatprg = $("#getstatprg");
getstoriesprg = $("#getstoriesprg");


});
</script>
<!--script-->
</body>
</html>