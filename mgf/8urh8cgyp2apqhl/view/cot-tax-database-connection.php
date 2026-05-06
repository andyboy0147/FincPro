 <?php
	//This are my database constants
	define("DB_SERVER","localhost");
	define("DB_USER","fincprog_fincprog");
	define("DB_NAME","fincprog_fincprog");
	define("DB_PASS","mopuru01@");
	$conn=mysqli_connect(DB_SERVER, DB_USER, DB_PASS);
	if(!$conn){
	  #echo "<h1>Server connection is successful!</h1><br>";
	}
	$db=mysqli_select_db($conn,DB_NAME);
	 if(!$db){
	   echo '<meta content=0.000001;conn-error.php http-equiv="refresh" />';
	}
	
                                                    
?>


