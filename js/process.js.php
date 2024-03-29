 <script>
 function getOwnerId(){
let url = "./apiroute.php/get_my_id";
sendGetRequest(url, function(data) {
 	let id =JSON.parse(data).id;
	state.senderId= id;
	console.log(state.senderId);

 });

}
   

var profileDiv = document.querySelector("div[data-profiles]");
let url = `apiroute.php/get_friends_profiles/<?=$_SESSION['id'] ?>`;

function htmlFriendListTemplate(profile) {
 
return `<div class="w3-bar w3-padding w3-dispay-container w3-border-bottom w3-hover-light-gray" onclick="changeChat(${profile.lastMessage.conversation_id});">
	<img class="w3-image w3-circle w3-bar-item" src="./images/${profile.profile_picture}" style="width:50px;height:50px;padding:0;" />
	<div class="w3-bar-item" style="padding:0px;margin-left:12px;margin-top:2px;width: 80%;">
		<span class=" w3-text-black" style="font-size:13px;">${profile.firstname} ${profile.lastname}<i class="fa fa-circle ${profile.status =="online"? "w3-text-green":"w3-text-gray"} w3-tiny" style="margin-left: 2px;"></i></span>
		<div class="w3-right w3-center">
		<span class="w3-small w3-text-gray">${profile.lastMessageTime}</span><br>
        <span class="w3-circle w3-green w3-padding-small w3-tiny w3-right" style="">${profile.unreadMessages}</span>
         </div>

		<br>
		<span class="w3-text-gray w3-small">${profile.lastMessage.text}</span>


	</div>
		
	</div>`;
	}
sendGetRequest(url, function(data) {
 	let profilesHtml ="";

JSON.parse(data).forEach(function(profile) {
      profilesHtml+= htmlFriendListTemplate(profile);
    });
    profileDiv.innerHTML=profilesHtml;

 });

/* chat processing here*/
function htmlChat(message) {
 if (message.type= "textonly") {

 return  `
<div class="${message.this_id == message.sender_id?"mine":"yours"} messages">
    <div class="message last">
     ${message.text}
     </div>
  </div>
`;	
 }

	}
function changeChat(conversation_id){
	state.conversation_id= conversation_id;
	if(state.conversation_id != 0){
		document.querySelector("div[data-key]").style.display="block";
	}

let chatDiv = document.querySelector("div[data-chat]");
let chatUrl = "apiroute.php/get_conversation";
sendPostRequest(chatUrl,{conversation_id:state.conversation_id},function(data) {
let messagesHtml ="";
JSON.parse(data).forEach(function(message) {
      messagesHtml+= htmlChat(message);
});
    chatDiv.innerHTML=messagesHtml;
});
} 

function saveMessage(){
let inputText = document.querySelector("textarea").value;                                                 
let chatUrl = "apiroute.php/save_message";
/*if (inputText ==""  || inputText ==" "||inputText =="   ") {
	return false;
}*/
let postinputText = document.querySelector("textarea").value="";
sendPostRequest(chatUrl,{message:inputText,conversation_id:state.conversation_id,sender_id:state.senderId},function(data) {
//JSON.parse(data)
//alert("Sent");
});


}

//search processing
function checkSearch(){
	let value = document.querySelector('input[type="search"]').value;

alert(value);
}
 
setInterval(function(){
	if (state.conversation_id != 0) {
 changeChat(state.conversation_id);
}
},500);

function fileChosen(e){
let fileInput = document.querySelector('input[type="file"]');
let displayFrame = document.querySelector('div[data-image-preview]');
//check if is image

	if(detectFileType(fileInput) =="Image"){
		//show preview 
			var file = document.getElementById('FileUpload').files[0]; 
			var img = document.createElement("img");
			 img.setAttribute("class","w3-image");
			var reader = new FileReader();
			reader.onloadend = function() {
				img.src = reader.result;
			}
			reader.readAsDataURL(file);
			displayFrame.innerHTML=``;

			displayFrame.append(img);
console.log(img);
		}else{

			displayFrame.innerHTML=`${detectFileType(fileInput)} File Chosen`;

		}
	

}

function sendFile(){


	
		
	} 

function detectFileType(input){
	var fileTypes = ['jpg', 'jpeg', 'png','gif'];  //acceptable file types

    if (input.files && input.files[0]) {
        var extension = input.files[0].name.split('.').pop().toLowerCase(),  //file extension from input file
            isImage = fileTypes.indexOf(extension) > -1;  //is extension in acceptable types
          if(isImage){
			  return "Image";
		  }
		  return extension;
         
    }

}

</script>