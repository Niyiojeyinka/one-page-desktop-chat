<?php
require "classmeetupprefrence.php";
$setpref=$fonttype=$link="";
$setpref = new setuppref();
if($setpref->validateUser() == "false"){
header("location:oaumeetuplogin.php");
exit();
}
if(count($_POST) == 0){
$fonttype = $setpref->getFont();
$a = array("selectfont.php","meetupprefrence.php","setattributes.php","oaumeetuplogin.php");
if(isset($_SERVER["HTTP_REFERER"]) &&  in_array(basename($_SERVER["HTTP_REFERER"]),$a)){
$link = "notification.php";
}else{
$link = $_SERVER["HTTP_REFERER"];
}
}
if(isset($_POST["skincolor"]) && isset($_POST["prefheight"]) && isset($_POST["size"]) && isset($_POST["danceskills"])){
echo $setpref->setmeetuppref($_POST["skincolor"],$_POST["prefheight"],$_POST["size"],$_POST["danceskills"]);
exit();
}

?>
<!DOCTYPE html>
<html>
<head>
<title>oameeetup|Setup Meetuppreference</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<meta name="author" content="Oausocial website">
<meta name="description" content=" social & meetup website">
<meta name="keywords"content="Oau social website,oau meetup, oau meetup site,oau biggest social website,oau social page,oau meetup Dating stories ">
<link rel="stylesheet" type="text/css" href="w3.css">
<link rel="stylesheet" type="text/css" href="font-awesome-4.7.0\css\font-awesome.css">
<script type="text/javascript" src="jquery-3.4.1.min.js"></script>
<!--<script type="text/javascript" src="lazysizemin.js"></script>-->
</head>
<body style="width:100%;height:100%;font-family:<?php echo $fonttype;?>">

<!--container starts here-->
<div class="w3-display-container w3-blue w3-animate-left" style="width:100%;height:100vh;">
<!--meetup logo-->
<div class="w3-center w3-padding" style="width:100%;">
<b><i class='fa fa-heart w3-text-red w3-spin w3-xlarge'></i> Setup Your meetuppreference</b>
<img src ="lovematch.png"class="w3-circle w3-spin" style="width:40px;height:40px;">
</div>
<!--meetup logo-->

<!--question and option list starts here-->
<div class='w3-panel w3-animate-right questions' style="margin-top:50px;padding:3px;width:97%;margin-left:auto;margin-right:auto;">
<h5 class="" style="width:100%;"><b>What's your favourite skin colour? <i class='fa fa-heart w3-text-red w3-spin w3-xlarge'></i></b></h5>
<span class="w3-large"><input class='skincolor'type="radio"name="skincolor" value="black"> Black skin</span><br>
<span class="w3-large"><input class='skincolor'type="radio"name="skincolor" value="brown"> Brown Skin</span><br>
<span class="w3-large"><input class='skincolor'type="radio"name="skincolor" value="white"> White Skin</span>
</div>

<div class='w3-panel questions w3-animate-right w3-hide' style="margin-top:50px;padding:3px;width:97%;margin-left:auto;margin-right:auto;">
<h5 class=""><b>What's your preferred height? <i class='fa fa-heart w3-text-red w3-spin w3-xlarge'></i></b></h5>
<span class="w3-large"><input class='prefheight'type="radio"name="prefheight" value="short"> short</span><br>
<span class="w3-large"><input class='prefheight' type="radio"name="prefheight" value="medium"> medium height</span><br>
<span class="w3-large"><input class='prefheight'type="radio"name="prefheight" value="Tall"> Tall</span><br>
<span class="w3-large"><input class='prefheight'type="radio"name="prefheight" value="any"> Any</span><br>
</div>

<div class='w3-panel questions w3-animate-right w3-hide' style="margin-top:50px;padding:3px;width:97%;margin-left:auto;margin-right:auto;">
<h5 class=""><b>What's your preferred size? <i class='fa fa-heart w3-text-red w3-spin w3-xlarge'></i></b></h5>
<span class="w3-large"><input class='size' type="radio"name="size" value="slim"> slim </span><br>
<span class="w3-large"><input class='size' type="radio"name="size" value="plump"> plump </span><br>
<span class="w3-large"><input class='size' type="radio"name="size" value="moderate"> moderate</span><br> 
<span class="w3-large"><input class='size' type="radio"name="size" value="any"> Any</span><br>
</div>


<div class='w3-panel questions w3-animate-right w3-hide' style="margin-top:50px;padding:3px;width:97%;margin-left:auto;margin-right:auto;">
<h5 class=""><b>How much of a dancer would you wish your crush to be? <i class='fa fa-heart w3-text-red w3-spin w3-xlarge'></i></b></h5>
<span class="w3-large"><input class='dance' type="radio"name="dance" value="excellent">Excellent Dancer</span><br>
<span class="w3-large"><input class='dance' type="radio"name="dance" value="good"></span>A very Good Dancer<br>
<span class="w3-large"><input class='dance' type="radio"name="dance" value="moderate">Moderate Dancer</span><br> 
<span class="w3-large"><input class='dance' type="radio"name="dance" value="any"> Any</span><br>
</div>
<!--question and option list ends here-->

<button id='submitpref' class="w3-btn w3-large w3-card w3-hide w3-white w3-right w3-white w3-text-blue w3-hover-blue w3-hover-text-white" style="margin-bottom:50px;margin-right:10px;outline:none;">
<span id='show' class="">Submit</span>
<span id='prg' class="w3-hide"><i class="fa fa-spinner w3-spin"></i> submitting...</span>
</button>
</div>
<!--container ends here-->
<!--buttons-->
<button id='slidenum' class="w3-display-topright w3-card-4 w3-btn w3-opacity w3-black w3-text-blue w3-round-large" style="margin-top:60px;margin-right:15px;">1/4</button>	
<button onclick='prev()' class="w3-round-large btn_move w3-display-left w3-btn w3-card-4  w3-black w3-opacity w3-hover-white w3-hover-text-blue"style="margin:10px;margin-top:100px;"><i class="fa fa-angle-left w3-xlarge "></i></button>
<button onclick='next()'class="w3-round-large btn_move w3-display-right w3-btn w3-card-4 w3-black w3-opacity w3-hover-white w3-hover-text-blue"style="margin:10px;margin-top:100px;"><i class="fa fa-angle-right w3-xlarge"></i></button>

<!--buttons-->
<!--script-->
<script type="text/javascript">
var q=len=slidenum=count=submitpref=show=prg=link=/*skincolor=prefheight=size=dance=*/"";

q = $(".questions");
len = q.length;
slidenum = $("#slidenum");
count = 0;
slidenum.html("1/"+len);
submitpref = $("#submitpref");	
/*skincolor = $(".skincolor");
prefheight = $(".prefheight");
size =$(".size");
dance = $(".dance");*/
show = $("#show");
prg = $("#prg");
link = "<?php echo $link;?>";
$(function(){
//code to handle submiting of prfrence starts here
submitpref.click(function(){
if($("input:radio[name='skincolor']:checked").length == 1
&& $("input:radio[name='prefheight']:checked").length == 1
&& $("input:radio[name='size']:checked").length == 1
&& $("input:radio[name='dance']:checked").length == 1
){
var skincolor = $("input:radio[name='skincolor']:checked").val();
var prefheight = $("input:radio[name='prefheight']:checked").val();
var danceskills = $("input:radio[name='dance']:checked").val();
var size =$("input:radio[name='size']:checked").val();
submitpref.attr("disabled",true);
show.addClass("w3-hide");
prg.removeClass("w3-hide");
$.ajax({
url:"meetupprefrence.php",
method:"post",
data:{skincolor:skincolor,prefheight:prefheight,size:size,danceskills:danceskills},
success:function(data){
if(data.indexOf("Failed") > -1){
alert(data);
submitpref.attr("disabled",false);
show.removeClass("w3-hide");
prg.addClass("w3-hide");
}else if(data.indexOf("success") > -1){
alert("your meetup preference has being set successful :) ,you can make changes from your settings page");
submitpref.attr("disabled",false);
show.removeClass("w3-hide");
prg.addClass("w3-hide");
window.location = link;
}else{
alert("something went wrong please try again");
submitpref.attr("disabled",false);
show.removeClass("w3-hide");
prg.addClass("w3-hide");
}
},
error:function(xhr,status,err){
alert("could not setup your meetupprefernce due to poor connection try again");
submitpref.attr("disabled",false);
show.removeClass("w3-hide");
prg.addClass("w3-hide");
}
});	
}

});
//code to handle submiting of prfrence ends here 



});
function next(){
submitpref.addClass("w3-hide");
if(count < len-1){
++count;	
}else if(count >= len-1){
count = 0;
}
if(count == len-1){
submitpref.removeClass("w3-hide");
}
q.addClass("w3-hide");
$(".questions:eq("+count+")").removeClass("w3-hide");
slidenum.html(count+1+"/"+len);
}


function prev(){
submitpref.addClass("w3-hide");
if(count < 0){	
count = 0;
}else if(count == 0){
count = count;
}else{
--count;
}
q.addClass("w3-hide");
$(".questions:eq("+count+")").removeClass("w3-hide");
slidenum.html(count+1+"/"+len);
}

</script>
<!--script-->
</body>
</html>