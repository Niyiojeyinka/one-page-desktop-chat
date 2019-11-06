<?php
require "countsystem.php";
$count=$notestrue=$chatstrue=$achattrue=$storytrue=$noteadd=$chatadd=$achatadd=$storyadd=$noteadddesign=$chatadddesign=$achatadddesign=$storyadddesign=$findmatchadd=$findmatchdesign="";
$count = new countsys();
$count->setudetails();
$notestrue = $count->checknewnotes();
$chatstrue = $count->checknewchat();
$achattrue = $count->checknewachat();
$storytrue = $count-> checknewstory();
$profiledesign=$storydesign=$chatdesign=$matchdesign=$achatdesign=$filename=$notedesign="w3-bar-item w3-tiny w3-center w3-btn w3-hover-white w3-ripple w3-hover-text-blue w3-display-container";
$path = basename($_SERVER["SCRIPT_FILENAME"]);
if($path == "oaumeetupdatingstories.php"){
$storydesign = "w3-bar-item w3-tiny w3-center w3-btn w3-white w3-ripple w3-text-blue w3-display-container";
}elseif($path == "oaumeetupshowfchats.php"){
$chatdesign ="w3-bar-item w3-tiny w3-center w3-btn w3-white w3-ripple w3-text-blue w3-display-container";
}elseif($path == "oaumeetupfindmatch.php"){
$matchdesign = "w3-bar-item w3-tiny w3-center w3-btn w3-white w3-ripple w3-text-blue w3-display-container";
}elseif ($path == "oaumeetupshowachats.php"){
$achatdesign = "w3-bar-item w3-tiny w3-center w3-btn w3-white w3-ripple w3-text-blue w3-display-container";
}elseif($path == "notification.php"){
$notedesign = "w3-bar-item w3-tiny w3-center w3-btn w3-white w3-ripple w3-text-blue w3-display-container";
}

if($notestrue == "yes" && $path !="notification.php"){
$noteadd="<span class='w3-red w3-circle w3-display-topright w3-tiny w3-padding-small'>new</span>";
$noteadddesign = "w3-spin";
}
if($chatstrue == "yes" && $path != "oaumeetupshowfchats.php"){
$chatadd="<span class='w3-red w3-circle w3-display-topright w3-tiny w3-padding-small'>new</span>";
$chatadddesign = "w3-spin";
}
if($achattrue == "yes" && $path != "oaumeetupshowachats.php"){
$achatadd="<span class='w3-red w3-circle w3-display-topright w3-tiny w3-padding-small'>new</span>";
$achatadddesign = "w3-spin";
}

if($storytrue == "yes" && $path != "oaumeetupdatingstories.php"){
$storyadd="<span class='w3-red w3-circle w3-display-topright w3-tiny w3-padding-small'>new</span>";
$storyadddesign = "w3-spin";
}
if($path != "oaumeetupfindmatch.php"){
$findmatchadd ="<span class='w3-red w3-circle w3-display-topright w3-tiny w3-padding-small'>new</span>";
}
?>
<div class="w3-top" style="">
<div class="w3-bar w3-blue"style="">
		<!--oau meetup logo starts here-->
		<h5 class="w3-bar-item w3-text-light-grey w3-italics w3-left" style="letter-spacing:4px;font-family:<?php echo $fonttype;?>"><a href="index.php" style="text-decoration: none;"> OAU Meetup</a></h5>
		<!--oau meetup logo ends here-->
		<a class="w3-bar-item w3-right w3-ripple w3-btn w3-display-container w3-block w3-hover-white w3-hover-text-blue"href='search.php'style="margin-top:12px;height:100%;text-decoration:none;"><i class="fa fa-search w3-large"></i></a>
		<a href="notification.php" class="<?php echo $notedesign;?> w3-right" style="margin-top:12px;height:100%;text-decoration:none;width:20%;text-align:center;"><i class="fa fa-bell w3-large">
		</i>
		<?php echo $noteadd;?>
	</a>
	</div>

<div class="w3-bar w3-blue  w3-center w3-card">
<a href="oaumeetupprofile.php"><div class="<?php echo $profiledesign;?>" style="width:20%;">
		<img src ="profileicon.png"class="w3-circle"style="width:40px;height:40px;"/><br/>
		<span>Profile</span>
		
	</div></a>

	<a href="oaumeetupdatingstories.php" style="text-decoration: none;"><div class="<?php echo $storydesign;?>" style="width:20%;">
<img src ="storylove.jpg"class="w3-circle"style="width:40px;height:40px;"/><br/>
		<span>Stories & Tip</span>
			<?php echo $storyadd;?>
			<?php //echo $storyadddesign;?>

</div></a>

		<a href="oaumeetupshowfchats.php" style="text-decoration: none;"><div class="<?php echo $chatdesign;?>" style="width:20%;">
<img src ="chaticon.png"class="w3-circle <?php echo $chatadddesign;?>"style="width:40px;height:40px;"/><br/>
		<span>Chat</span>
	<?php echo $chatadd;?>
</div></a>

<a href="oaumeetupfindmatch.php" style="text-decoration: none;"><div class="<?php echo $matchdesign;?>" style="width:20%;">
<img src ="lovematch.png"class="w3-circle"style="width:40px;height:40px;"/><br/>
		<span>Find Match</span>
		<?php echo $findmatchadd;?>
</div></a>

<a href="oaumeetupshowachats.php" style="text-decoration: none;"><div class="<?php echo $achatdesign;?>" style="width:20%;">
<img src ="chathide.jpg"class="w3-circle <?php echo $achatadddesign;?>"style="width:40px;height:40px;"/><br/>
		<span>Achat</span>
	<?php echo $achatadd;?>
</div></a>



</div></div>