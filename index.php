<?php
require_once('conf.php');
?>
<!-- Created by S. Sietsma General Manager www.dynamical.nl -->

<html>
	<head>
		<title>Comet test</title>
		<link rel="stylesheet" href="css.css" />
		<script>
		var x = 0;

	
        function Init () {
            if (window.addEventListener) {  // Firefox, Opera, Google Chrome and Safari
                window.addEventListener ("message", OnMessage, false);
            }
            else {
                if (window.attachEvent) {   // Internet Explorer
                    
                    window.attachEvent("onmessage", OnMessage);
                }
            }
        }

        function OnMessage (event) {
            var message = event.data;
            var arr = message.split ("|||");
            for(var i in arr){
				messagesDiv = document.getElementById('messages');
				newMess = document.createElement('p');
				newMess.innerHTML = arr[i];
				messagesDiv.appendChild(newMess);
				messagesDiv.scrollTop = messagesDiv.scrollHeight;
            }
        }

        function addiframe(){
        	ifrm = document.createElement('iframe');
        	ifrm.setAttribute("src","http://<?php echo CON_IP.':'.CON_PORT; ?>");
        	ifrm.setAttribute("id","messagesiframe");
        	divto = document.getElementsByTagName('body')[0];
        	divto.appendChild(ifrm);
        }

		window.onload = pageload;
		function pageload(){
			Init(); 
			setTimeout("addiframe()",2500);
		}
        
		</script>
	</head>
	<body>
	<div id="holder">
	<table>
	<tbody>
	<tr>
	<td>
		<div id="msg">
			<h3>Chat:</h3><br />	 
			<div id="messages">
	
			</div>  
		</div>	
	</td>
	<td>
		<div id="post">
			<h3>Post new MSG</h3><br />
			<iframe src="post.php" style="border: 0;"></iframe>
		</div>
	</td>	
	</tr>
	</tbody>
	</table>
	</div>
	</body>
</html>