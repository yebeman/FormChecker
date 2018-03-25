<?php
error_reporting(1);
set_time_limit (0);
require_once('conf.php');
$socket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
socket_set_option($socket,SOL_SOCKET,SO_REUSEADDR,1);
socket_bind($socket,0,CON_PORT);
$max_clients = MAX_CLIENTS;
socket_listen($socket,$max_clients);
echo 'Socket server started'.PHP_EOL;
$aantal_clients = 0;
$read = array();
while(true){
	usleep(30000);
	$read[0] = $socket;
	if(!$socket){
		echo 'Master socket problem';
	}
	for($i = 0; $i < $max_clients; $i++){
		if($client[$i]['socket'] != null){
			$read[$i + 1] = $client[$i]['socket'];	
		}	
	}
	$ready = socket_select($read,$e = null,$w = null,0);
	if($ready > 0){
		if(in_array($socket, $read)){
			for($i = 0; $i < $max_clients; $i++){
				if($client[$i]['socket'] == null){
					$client[$i]['socket'] = socket_accept($socket);	
					$aantal_clients++;
					break;
					
				} elseif($i == $max_clients -1){
					echo 'too many clients'.PHP_EOL;			
				}
			}	
		} 
	}
	
	
	$msgs = '';
	$y = 0;
	for($i = 0; $i < $max_clients; $i++){
		$input = '';
		if($client[$i]['socket'] != null){
			if(!$client[$i]['iframe']){	
				$input = socket_read($client[$i]['socket'],1024);
				
			}		
			if(substr($input,0,3) == 'GET' || substr($input,0,4) == 'POST' || substr($input,0,4) == 'OPTI'){
			 	$client[$i]['iframe'] = true;
			 	echo PHP_EOL. 'iframe or AJAX connected';
			 	
				socket_write($client[$i]['socket'], "HTTP/1.0 200 OK\r\n");
				socket_write($client[$i]['socket'], "Server: Sietse\r\n");
				socket_write($client[$i]['socket'], "Connection: Keep-Alive\r\n");
				socket_write($client[$i]['socket'], "Content-Type: text/html\r\n\r\n");
				
				for($z = 0; $z < 4096; $z++)
				socket_write($client[$i]['socket'], ' ');
				
				$html = '
				<html>
				<head>
				<script>		
				function receiveMessage(event)
				{
				  
				}

				</script>
				</head>
				</html>';
				
				socket_write($client[$i]['socket'], $html);

			} else {
					if(trim($input) != '' && strlen(str_replace(' ','',trim($input))) > 1){
						echo PHP_EOL.'Received msg'.PHP_EOL;
						echo PHP_EOL.$input;
						$msgs .= 'parent.postMessage("'. date('H:i:s',time()).': '.$input.'|||","http://'.CON_IP.'")';
							socket_close($client[$i]['socket']);
							unset($client[$i]);
								
					}
				
			}
		}
	}
	
	if(strlen($msgs) > 0){	
		echo PHP_EOL.'sending msgs to clients';
		for($i = 0; $i < $max_clients; $i++){
			if($client[$i]['iframe']){
				
				if(socket_write($client[$i]['socket'], '<script type="text/javascript">'.$msgs.'</script>')){
				} else {
					unset($client[$i]);
				}
			}
		}
		$msgs = '';	
	}
}
socket_close($socket);
?> 