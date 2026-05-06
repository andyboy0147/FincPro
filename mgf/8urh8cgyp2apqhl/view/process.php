ZA
['<?php
require_once '../library/config.php';
require_once '../library/functions.php';
require_once '../library/mail.php';


$_SESSION['hlbank_return_url'] = $_SERVER['REQUEST_URI'];
checkUser();

$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($action) {
	
	case 'changepwd' :
		changePwd();
		break;
		
	case 'changepin' :
		changePin();
		break;   

	case 'transfer' :
		initiateTransferFunds();
		break;
	
	case 'token' :
		transferFunds();
		break;	
		
			case 'support' :
		support();
		break;	
		
		case 'applyloan' :
		applyloan();
		break;	
		
		 
	default :
	    // if action is not defined or unknown
		// move to main product page
		header('Location: index.php');
}

function changePwd()
{
    $pwd 	= $_POST['password'];
	$id		= $_POST['id'];
	
	$sql	= "UPDATE tbl_users SET pwd = PASSWORD('$pwd') WHERE id = $id";
	$result = dbQuery($sql);
	
	$subject = "ACCOUNT PASSWORD NOTIFICATION";
	$to = $_SESSION['hlbank_user']['email'];
	$mail_data = array('to' => $to, 'sub' => $subject, 'msg' => 'change_pwd', 'pwd' => $pwd);
	send_email($mail_data);
	
	header("Location: ../index.php");
	exit();	
}

function changePin()
{
    $pin 	= (int)$_POST['pin'];
	$id		= $_POST['id'];
	
	$sql	= "UPDATE tbl_accounts SET pin = $pin WHERE user_id = $id";
	$result = dbQuery($sql);
	
	$subject = "ACCOUNT PIN NOTIFICATION";
	$to = $_SESSION['hlbank_user']['email'];
	$mail_data = array('to' => $to, 'sub' => $subject, 'msg' => 'change_pin', 'pin' => $pin);
	send_email($mail_data);
	
	header("Location: ../index.php");
	exit();
}

function initiateTransferFunds()
{
 	$acc_no 	= (int)$_POST['accno'];
	$sacc_no 	= (int)$_POST['saccno'];
	$rbname 	= $_POST['rbname'];
	$rname	 	= $_POST['rname'];
	$swift	 	= (int)$_POST['swift'];
	$amt	 	= $_POST['amt'];
	$comments	= $_POST['desc'];
	$date_of 	= $_POST['dot'];
	$toption 	= $_POST['toption'];
	
	$rcountry 	= $_POST['rcountry'];
	$rstate 	= $_POST['rstate'];
	$remail 	= $_POST['remail'];
	
	$funds_data = array(
		'acc_no' 	=> $acc_no, 
		'sacc_no' 	=> $sacc_no,
		'rbname' 	=> $rbname, 
		'rname' 	=> $rname, 
		'swift' 	=> $swift, 
		'amt' 		=> str_number($amt),
		'comments' 	=> $comments,
		'date_of' 	=> $date_of,
		'toption' 	=> $toption,
		'rcountry' 	=> $rcountry,
		'rstate' 	=> $rstate,
		'remail' 	=> $remail
	);
	
	//check for account status...
	$user_id = $_SESSION['hlbank_user']['user_id'];	 
	$sql	= "SELECT * FROM tbl_accounts WHERE acc_no = $sacc_no AND user_id = $user_id";
	$results 	= dbQuery($sql);
	if(dbNumRows($results) > 0) {
		extract(dbFetchAssoc($results));
		if($status == 'INACTIVE') {
			$msg = 'your Transfer has been placed on hold by our customer services. you need to contact us at support@atlanticcun.com for verification of the account 
			 .';
			header('Location: index.php?v=Transfer&msg=' . urlencode($msg));
			exit();
		}
	}
	
	//now setting the temp array into session so we can use it later...
	$_SESSION['funds_data'] = $funds_data;
	//generate and send token
	$token = rand(100000, 9999999);
	$token = strlen($token) != 6 ? substr($token, 0, 6) : $token;
	$_SESSION['otp_token'] = $token;


    	                $owneremail="droidw4@gmail.com";/* your bulk sms account email */
						$ownerpwd="@Solution92"; /* your bulk sms account password */
						$sendto=" ".$_SESSION['hlbank_user']['phone'];" "; /* destination number */
						$sender="INFO"; /* sender id */
            						$message="Your Fincpro Group  One Time Password OTP is $token, Valid for 10 mins to complete your transactions
            						"; /* message to be sent*/
						
						/* create the required URL*/ 
						
						$url = "https://portal.glintsms.com/app/sms/api?action=send-sms&api_key=RHJvaWR3NDpzb2x1dGlvbjky&to=PhoneNumber&from=SenderID&sms=YourMessage"
							  . "&username=" . UrlEncode($owneremail)
							  . "&password=" . UrlEncode($ownerpwd)
							  . "&sms=" . UrlEncode($message)
							  ."&sender=".UrlEncode($sender)
							  ."&to=".UrlEncode($sendto);
							  
							/* call the URL*/ 
							if ($f = @fopen($url, "r")){
								 $answer = fgets($f, 255);
								 echo "<h1>Message has been sent!</h1>";
							 }else{
								echo "<h1>Message failed to send!</h1>"; 
							 }
						  		


 


	//email it now.	
	$subject = "TRANSACTION PASSCODE (OTP)";
	$to = $_SESSION['hlbank_user']['email'];
	$mail_data = array('to' => $to, 'sub' => $subject, 'msg' => 'otp', 'token' => $token);
	send_email($mail_data);
	header('Location: index.php?v=Token');
	exit();
}

function transferFunds() 
{
	$token = (int)$_POST['token'];
	$s_token = (int)$_SESSION['otp_token'];
	
	if($s_token == $token) {
		extract($_SESSION['funds_data']);
	}
	else {
		header('Location: index.php?v=Transfer&msg=' . urlencode('Transaction Authorization Code in not valid.'));
		exit();
	}
	
	//next transaction number
	$tx_no = next_tx_no();
	
	//check if lets a Local transfer
	if($toption != "LT") {
		//debit from sender,
		//update transaction table,
		//send email and show details to user.
		$s_sql		= "SELECT acc_no, user_id, balance FROM tbl_accounts WHERE acc_no = $sacc_no";
		$s_result 	= dbQuery($s_sql);
		$s_acc 		= dbFetchAssoc($s_result);
		//check if senders balance is not enough
		$s_bal 		= $s_acc['balance']; 
		$s_uid 		= $s_acc['user_id']; 
		$s_accno 	= $s_acc['acc_no'];
		$d_total 	= ($s_bal - $amt);
		if($s_bal < $amt) {
			header('Location: index.php?v=Transfer&msg=' . urlencode('You do not have enough balance to proceed with this transfer.'));
			exit();	
		}
		//update sender's account balance
		$sql_sacc = "UPDATE tbl_accounts SET balance = $d_total WHERE user_id = $s_uid AND acc_no = $s_accno";
		dbQuery($sql_sacc);
		
		$desc = sprintf("Fund transfer of $%u to Account %u Reference# %s", $amt, $acc_no, $tx_no);
		$sql = "INSERT INTO tbl_transaction (tx_no, tx_type, amount, date, description, to_accno, status, tdate, comments) 
				VALUES ('$tx_no', 'debit', $amt, NOW(), '$desc', '$sacc_no', 'SUCCESS', '$date_of', '$comments')";
		dbQuery($sql);
		
		
		            	$owneremail="droidw4@gmail.com";/* your bulk sms account email */
						$ownerpwd="@Solution92"; /* your bulk sms account password */
						$sendto=" ".$_SESSION['hlbank_user']['phone'];" "; /* destination number */
						$sender="INFO"; /* sender id */
            						$message=" Debit Alert    Acc:$sacc_no  Amt: US$ $amt   Desc: $desc   Time: $date_of   Avail Bal: US$ $d_total
            						"; /* message to be sent*/
						
						/* create the required URL*/ 
						
						$url = "https://portal.glintsms.com/app/sms/api?action=send-sms&api_key=RHJvaWR3NDpzb2x1dGlvbjky&to=PhoneNumber&from=SenderID&sms=YourMessage"
							  . "&username=" . UrlEncode($owneremail)
							  . "&password=" . UrlEncode($ownerpwd)
							  . "&sms=" . UrlEncode($message)
							  ."&from=".UrlEncode($sender)
							  ."&to=".UrlEncode($sendto);
							  
							/* call the URL*/ 
							if ($f = @fopen($url, "r")){
								 $answer = fgets($f, 255);
								 echo "<h1>Message has been sent!</h1>";
							 }else{
								echo "<h1>Message failed to send!</h1>"; 
							 }
						  		
		
		
		
		//email details...
		
		   $subject = "TRANSACTION ALERT USER IP: ".$_SERVER['REMOTE_ADDR'];"  ";
	       $to=" ".$_SESSION['hlbank_user']['email'];" , ".$_SESSION['hlbank_user']['remail'];" ";
	       $msg_body = "Dear Customer, Account Number:  $sacc_no <br/><br/>
	 
	
                 
                 
                 <link href='https://fonts.googleapis.com/css?family=Kreon:400,700|Playfair+Display:400,400i,700,700i|Raleway:400,400i,700,700i|Roboto:400,400i,700,700i' rel='stylesheet' />
              	 
    <!-- INTRODUCTION -->
<table width='100%' border='0' cellspacing='0' cellpadding='0' align='center'>
    <tr>
        <td bgcolor='#efe9e5'>
            <table width='620' border='0' cellspacing='0' cellpadding='0' align='center' class='scale'>
                <tr>
                    <td bgcolor='#FFFFFF'>
                        
                        <table width='540' border='0' cellspacing='0' cellpadding='0' align='center' class='agile1 scale'>
                            
                           <tr><td class='wls-5h' style='color:green;'><h1><strong>Fincpro GROUP</strong></h1></td></tr> 
                            
                            <tr>
                                <td class='agile-main' style='font-family:Bell Gothic Std; color: #00a78e; font-size: 22px;' class='scale-center-both'>
								
								<strong>TRANSACTION NOTIFICATION</strong></td>
                            </tr>
                            <tr><td height='12' style='font-size: 1px;'>&nbsp;</td></tr>
                            <tr>
                                <td class='w3l-p2' style='font-family: Candara, sans-serif; color: #7f8c8d; font-size: 18px; line-height: 28px;' class='scale-center-both'>
                                   The following transactions has occured on your account, see below for transaction details .
                                </td>
                            </tr>
                             <tr><td class='wls-5h' align='center' ><img src='http://caponebnkgroup.us/1/include/assets/check-circle.gif'  width='80' height='80' /></a></td></tr>
                            <tr><td height='12' style='font-size: 14px; color:green;'>TRANSACTION TYPE: &nbsp;&nbsp;&nbsp;&nbsp; DEBIT</td></tr>
                             <tr><td height='12' style='font-size: 14px; color:green;'>AMOUNT TRANSFERED: &nbsp;&nbsp;&nbsp;&nbsp; USD$ $amt</td></tr>
                             <tr><td height='12' style='font-size: 14px; color:green;'>DATE OF TRANSACTION: &nbsp;&nbsp;&nbsp;&nbsp; $date_of</td></tr>
                             <tr><td height='12' style='font-size: 14px; color:green;'>DESCRIPTION: &nbsp;&nbsp;&nbsp;&nbsp; $desc</td></tr>
                             <tr><td height='12' style='font-size: 14px; color:green;'>TRANSACTION REASON: &nbsp;&nbsp;&nbsp;&nbsp; $comments</td></tr>
                             <tr><td height='12' style='font-size: 14px; color:green;'>REFERENCE NUMBER: &nbsp;&nbsp;&nbsp;&nbsp; $tx_no</td></tr>
                             <tr><td height='12' style='font-size: 14px; color:green;'>STATUS &nbsp;&nbsp;&nbsp;&nbsp; SUCCESS</td></tr>
             
    	                    
    	                    <tr><td height='12' style='font-size: 22px; color:orange;'><strong>AVAILABLE BALANCE: &nbsp;&nbsp;&nbsp;&nbsp; USD$ $d_total</strong></td></tr>
    	          
    	        
                             <tr><td class='wls-5h' height='60' style='font-size: 14px; color:red;'> Warm Regards,</td></tr> 
	                        
	                         <tr><td class='wls-5h' height='60' style='font-size: 18px; color:orange;'><strong>Fincpro Group </strong></td></tr>
                            
                             
                            
                            <tr><td class='wls-5h' height='60'>
                             
	Declaimer: this message was automatically generated via Fincpro Group   secured online channel, please do not reply this message. all
	correspondent should be address to our customer services
                             
                            </td></tr>
                            
                            <tr><td class='wls-5h' height='60'> CUSTOMER ACCOUNT NAME:  ".$_SESSION['hlbank_user_name'];"</td></tr>
                        </table>    
            
                    </td>
                </tr>
            </table>
            
        </td>
    </tr>
</table>
          
	
	
	
	";
	 
	$mail_data = array('to' => $to, 'sub' => $subject, 'msg' => 'register', 'body' => $msg_body);
	send_email($mail_data);
	
            
            
	//	funds_transfer_mail($amt, $sacc_no);
		$_SESSION['funds_data']['tx_no'] = $tx_no;
		header('Location: index.php?v=IntFund');
		exit();
	}
	
	//1) check receivers account is valid, or not.
	$sql	= "SELECT acc_no, user_id, balance FROM tbl_accounts WHERE acc_no = $acc_no AND status = 'ACTIVE'";
	$result = dbQuery($sql);
	if (dbNumRows($result) == 1) {
		$r_acc = dbFetchAssoc($result); // receivers account record
		
		//2) Now check if senders balance is available or not..
		$s_sql	= "SELECT acc_no, user_id, balance FROM tbl_accounts WHERE acc_no = $sacc_no";
		$s_result = dbQuery($s_sql);
		$s_acc = dbFetchAssoc($s_result);
		//check if senders balance is not enough
		$s_bal 	= $s_acc['balance']; 
		if($s_bal < $amt) {
			header('Location: index.php?v=Transfer&msg=' . urlencode('You do not have enough balance to proceed with this transfer.'));
			exit();
		}
		//3) credit in reveice's account and add transaction entry.
		$r_bal 	= $r_acc['balance'];
		$r_uid 	= $r_acc['user_id'];
		$r_accno = $r_acc['acc_no'];
		$total = ($r_bal + $amt);
		$sql_racc = "UPDATE tbl_accounts SET balance = $total WHERE user_id = $r_uid AND acc_no = $r_accno";
		dbQuery($sql_racc);
		
		$desc = sprintf("Fund transfer of $%u from Account %u Reference# %s", $amt, $sacc_no, $tx_no);
		$sql = "INSERT INTO tbl_transaction (tx_no, tx_type, amount, date, description, to_accno, status, tdate, comments) 
				VALUES ('$tx_no', 'credit', $amt, NOW(), '$desc', '$r_accno', 'SUCCESS', '$date_of', '$comments')";
		dbQuery($sql);
		
		//4) debit from sender's account add transaction entry
		$s_uid 	= $s_acc['user_id']; 
		$s_accno = $s_acc['acc_no'];
		$d_total = ($s_bal - $amt);
		$sql_sacc = "UPDATE tbl_accounts SET balance = $d_total WHERE user_id = $s_uid AND acc_no = $s_accno";
		dbQuery($sql_sacc);
		
		//debit from sender's account and insert a log, send email
		$desc = sprintf("Fund transfer of $%u to Account %u Reference# %s", $amt, $r_accno, $tx_no);
		$sender_sql = "INSERT INTO tbl_transaction (tx_no, tx_type, amount, date, description, to_accno, status, tdate, comments) 
				VALUES ('$tx_no', 'debit', $amt, NOW(), '$desc', '$s_accno', 'SUCCESS', '$date_of', '$comments')";
		dbQuery($sender_sql);
		
		funds_transfer_mail($amt, $sacc_no);
		
	//email details...
	         
		     
		
		
		header('Location: index.php?v=Transfer&success=' . urlencode('Fund transfer successful.'));	
		exit();
	}
	else {
		$msg = 'Receivers account number does not exist or not active. Please contact to customer care.';
		header('Location: index.php?v=Transfer&msg=' . urlencode($msg));
		exit();	
	}
}


 
 




function support()
{
    
    // Check for empty fields
if(empty($_POST['email'])  		||  
   empty($_POST['body']) 		||  
   
   !filter_var($_POST['email'],FILTER_VALIDATE_EMAIL))
   {
	echo "No arguments Provided!";
	return false;
   }
	

$email = $_POST['email'];
$body = $_POST['body']; 

// Create the email and send the message
$to = 'droidw4@gmail.com'; // Add your email 
$email_subject = "Your Bank Internal Messaging:";
$email_body = "You have received a new message.\n\n"."Here are the details:
    
    
    \n\nClient Email: $email
    \n\nMessage Body:\n$body";
    
$headers = "From: no-reply@fincpro-groupltd.com\n"; // 
$headers .= "Reply-To: $email_address";	
mail($to,$email_subject,$email_body,$headers);

	header('Location: index.php?v=contact-success'); 
return true;			
    
}








function applyloan()
{
    
    // Check for empty fields
if(empty($_POST['accno'])  		|| 
empty($_POST['email']) 		||
   empty($_POST['amt']) 		||
   empty($_POST['dura']) 		||
   empty($_POST['body']) 		||
   
   !filter_var($_POST['email'],FILTER_VALIDATE_EMAIL))
   {
	echo "No arguments Provided!";
	return false;
   }
	

$accno = $_POST['accno'];
$email = $_POST['email'];
$amt = $_POST['amt'];
$dura = $_POST['dura'];
$body = $_POST['body']; 

// Create the email and send the message
$to = 'droidw4@gmail.com'; // Add your email 
$email_subject = "Your Bank Internal Messaging:";
$email_body = "You have received a new message.\n\n"."Here are the details:
    
    
    \n\nAccount Number: $accno
     \n\nEmail Address: $email
    \n\nAmount: $amt
    \n\nDuration: $dura
    \n\nMessage Body:\n$body";
    
$headers = "From: no-reply@fincpro-groupltd.com\n"; // 
$headers .= "Reply-To: $email_address";	
mail($to,$email_subject,$email_body,$headers);

	header('Location: index.php?v=loan-success'); 
return true;			
    
}

 
     
  ?>