<?php
$colors = array('#007AFF','#FF7000','#FF7000','#15E25F','#CFC700','#CFC700','#CF1100','#CF00BE','#F00');
$color_pick = array_rand($colors);
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style type="text/css">
.chat-wrapper {
	font: bold 11px/normal 'lucida grande', tahoma, verdana, arial, sans-serif;
    background: #00a6bb;
    padding: 20px;
    margin: 20px auto;
    box-shadow: 2px 2px 2px 0px #00000017;
	max-width:700px;
	min-width:500px;
}
#message-box {
    width: 97%;
    display: inline-block;
    height: 300px;
    background: #fff;
    box-shadow: inset 0px 0px 2px #00000017;
    overflow: auto;
    padding: 10px;
}
.user-panel{
    margin-top: 10px;
}
input[type=text]{
    border: none;
    padding: 5px 5px;
    box-shadow: 2px 2px 2px #0000001c;
}
input[type=text]#name{
    width:20%;
}
input[type=text]#message{
    width:60%;
}
button#send-message {
    border: none;
    padding: 5px 15px;
    background: #11e0fb;
    box-shadow: 2px 2px 2px #0000001c;
}
</style>
</head>
<body>

<div class="chat-wrapper">
<div id="message-box"></div>
<div class="user-panel">
<input type="text" name="name" id="name" placeholder="Your Name" maxlength="15" />
<input type="text" name="message" id="message" placeholder="Type your message here..." maxlength="100" />
<button id="send-message">Send</button>
</div>
</div>

<script language="javascript" type="text/javascript">
	//create a new WebSocket object.
	var msgBox = document.getElementById("message-box");
	var wsUri = "ws://localhost:9000";
	websocket = new WebSocket(wsUri);

	websocket.onopen = function(ev) { // connection is open
        var div = document.createElement("div"); div.style.color = "#bbbbbb"; div.innerHTML = "Welcome to my \"Demo WebSocket Chat box\"!"
		msgBox.appendChild(div); //notify user
	}
	// Message received from server
	websocket.onmessage = function(ev) {
		var response 		= JSON.parse(ev.data); //PHP sends Json data

		var res_type 		= response.type; //message type
		var user_message 	= response.message; //message text
		var user_name 		= response.name; //user name
		var user_color 		= response.color; //color
        var div = document.createElement("div");
		switch(res_type){
			case 'usermsg':
                var span = document.createElement("span");
                var span2 = document.createElement("span");
                var node = document.createTextNode(" : ");
                span.classList = "user_name";
                span.innerHTML = "Welcome to my \"Demo WebSocket Chat box\"!";
                span.style.color = user_color;
                span.innerHTML = user_name;
                span2.classList = "user_message";
                span2.innerHTML = user_message;
                div.appendChild(span);
                div.appendChild(node);
                div.appendChild(span2);
				break;
			case 'system':
                div.style.color = "#bbbbbb";
                div.innerHTML = user_message;
				break;
		}
        msgBox.appendChild(div);
		msgBox.scrollTop = msgBox.scrollHeight; //scroll message
	};
    let sysMsg = function(ev){
        var div = document.createElement("div");
        div.innerHTML = ev.data;
        msgBox.appendChild(div);
    };
	websocket.onerror	= sysMsg;
	websocket.onclose 	= sysMsg;
	//Message send button
    var sendMessage = document.getElementById("send-message");
    var newMessage = document.getElementById("message");
    var userName = document.getElementById("name");
	sendMessage.onclick = function(){
		send_message();
	};

	//User hits enter key
	newMessage.addEventListener( "keydown", function( event ) {
	  if(event.which==13){
		  send_message();
	  }
	});

	//Send message
	function send_message(){
		var message_input = newMessage; //user message text
		var name_input = userName; //user name

		if(message_input.value == ""){ //empty name?
			alert("Enter your Name please!");
			return;
		}
		if(message_input.value == ""){ //emtpy message?
			alert("Enter Some message Please!");
			return;
		}
		//prepare json data
		var msg = {
			message: message_input.value,
			name: name_input.value,
			color : '<?php echo $colors[$color_pick]; ?>'
		};
		//convert and send data to server
		websocket.send(JSON.stringify(msg));
		message_input.value = ""; //reset message input
	}
</script>
</body>
</html>
