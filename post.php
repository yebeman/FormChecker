<?php
require_once('conf.php');
session_start();
if(isset($_POST['msg'])){
	$msg = $_POST['msg'];
	if(preg_match('%^[A-Za-z0-9\s\?\!\'\-\_\;]{1,255}$%', trim($msg))){
			$fp = fsockopen(CON_IP,CON_PORT,$errors,$errdesc,1);
			fputs($fp,$_SESSION['name'].' says: '.$msg);
			fclose($fp);

	} else {
		echo 'Wrong input';
	}

}

if(isset($_POST['name'])){
	$_SESSION['name'] = strip_tags($_POST['name']);	
}
if(!isset($_SESSION['name'])){
	$_SESSION['name'] = 'Anonymous';
}

?>
<html>
<head>
	<title>Post something</title>
</head>
	<body>
		<form method="post">
		<input name="msg" type="text" /><br />
		<input type="submit" value="say something" />
		</form>
		
		<form method="post">
		<input name="name" type="text" value="<?php echo $_SESSION['name']; ?>" /><br />
		<input type="submit" value="change name" />
		</form>
	</body>
</html>


