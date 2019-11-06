<?php
require 'classshowfchats.php';
$showchat=$fonttype=$fchatlist="";
$showchat = new showfchats();
if($showchat->validateUser() == "false") {
header("location:oaumeetuplogin.php");
exit();
}
if(count($_POST) == 0){
$fonttype = $showchat->getFont();
$fchatlist = $showchat->getFchats();
}
//code to handle getting more chat starts here
if(isset($_POST["morechat"])){
echo $showchat->getmorechat();
exit();
}
//code to handle getting more chat ends here
?>
<!DOCTYPE html>
<html>
<head>
<title>oaumeetup.com|Show Anonymous Chat</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<meta name="author" content="Oausocial website">
<meta name="description" content=" social & meetup website">
<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
<script type="text/javascript" src="lazysizemin.js"></script> 
<link rel="stylesheet" type="text/css" href="w3.css">
<link rel="stylesheet" type="text/css" href="font-awesome-4.7.0\css\font-awesome.css"> 
</head>
<body class="w3-light-grey" style="font-family:<?php echo $fonttype;?>">
<!--navbar starts here-->
<?php include_once "navbar2.php";?>
<!--navbar ends here-->
<!--mainpage starts here-->
<div class=""style="margin-top:150px;margin-bottom:60px;">

<!--list of fchat starts here-->
<ul class="w3-ul w3-animate-left" id='fchatlist'>
<?php echo $fchatlist;?>	
</ul>
<!--list of fchat ends here-->
</div>
<!--mainpage ends here-->
<!--footer-->
<?php include_once 'footer.php';?>
<!--footer-->
<!--script-->
<script type="text/javascript">
$(function(){
var morechatbtn=morechatshow=morechatprg=fchatlist="";
morechatbtn = $("#morechatbtn");
morechatshow = $("#morechatshow");
morechatprg = $("#morechatprg");
fchatlist = $("#fchatlist");

morechatbtn.click(function(){
morechatbtn.attr("disabled",true);
morechatshow.addClass("w3-hide");
morechatprg.removeClass("w3-hide");
$.ajax({
url:"oaumeetupshowfchats.php",
method:"post",
data:{morechat:""},
success:function(data){
//alert(data);
if(data.indexOf("nomorechat") > -1){
morechatshow.html("no more chat to get");
morechatshow.removeClass("w3-hide");
morechatprg.addClass("w3-hide");
}else if(data.indexOf("li") > -1){
morechatbtn.before(data);
morechatbtn.attr("disabled",false);
morechatshow.removeClass("w3-hide");
morechatprg.addClass("w3-hide");
}else{
alert("something went wrong please try again");
morechatbtn.attr("disabled",false);
morechatshow.removeClass("w3-hide");
morechatprg.addClass("w3-hide");
}
},
error:function(xhr,status,err){
alert("could not load more notifications due to bad connection please try again");
morechatbtn.attr("disabled",false);
morechatshow.removeClass("w3-hide");
morechatprg.addClass("w3-hide");
}
});
});
});
</script>
<!--script-->
</body>
</html>