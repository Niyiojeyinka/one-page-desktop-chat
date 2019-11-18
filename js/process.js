 function getOwnerId(){
let url = "./apiroute.php/get_my_id";
sendGetRequest(url, function(data) {
 	let id =JSON.parse(data).id;
    state.senderId= id;

 });

}
   

var profileDiv = document.querySelector("div[data-profiles]");
let url = "apiroute.php/get_friends_profiles/1";

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
 

 
setInterval(function(){
	if (state.conversation_id != 0) {
 changeChat(state.conversation_id);
}
},500);

