 
  <?php
	 
        session_start();

	error_reporting(0);
	require('../admin-config.php');
	
  ?>
  
  

 



<div class="container-fluid">
          <div class="row">
            <div class="col-md-8">
              
              
              
              
              
                      <div class="card">
                <div class="card-header card-header-primary">
                  <h4 class="card-title">Add New COT Codes for my Bank</h4>
                  <p class="card-category">Please Enter Preferred COT Codes</p>
                </div>
                <div class="card-body">
                   
                   <?php
					                                if(isset($_POST['submit'])){
						   
						                            $conn = mysqli_connect($host, $username, $password, $database);
                                                    
                                                    // Check connection
                                                    
                                                    if (!$conn) {
                                                          die("Connection failed: " . mysqli_connect_error());
                                                    }
                                                     
                                                    echo ""; 
                                                    
                                                    $db_select = mysqli_select_db($con, $database);
						   
						                            $pin =$_REQUEST['pin'];
                                                    $type =$_REQUEST['type']; 
                                                    
                                                    $sql = "INSERT INTO `cot` (`id`, `pin`, `type`) VALUES (NULL, '$pin', '$type')";
                                                    
                            						 
                            						   $insert=mysqli_query($conn, $sql);
                            						   
                            						   if($insert){
                            							   echo '<h3>TAX CODE WASSUCCESSFUL!</h3>';
                            							   
                            						   }else{echo '<h3>TAX CODE HAS FAILED!</h3>';}
                            					   }
                            					?>
					
                  <form name="myForm" action="" onsubmit="return validateForm()" method="post">
                   
  <script>
function validateForm() {
  var x = document.forms["myForm"]["type"].value;
  if (x == "") {
    alert("Please Enter COT in the field");
    return false;
  }
}

function validateForm() {
  var x = document.forms["myForm"]["pin"].value;
  if (x == "") {
    alert("Please Enter 5-10 digits COT Code");
    return false;
  }
}
</script>
  
                    <div class="row">
                      
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating"></label>
                          
                          <input type="text" class="form-control" name="type" id="type"  placeholder="COT" >
                            
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating"></label>
                          <input type="text" class="form-control" placeholder="Enter  5-10 digits COT Code" name="pin" >
                        </div>
                      </div>
                    </div>
                 
                 <img src="<?php echo WEB_ROOT; ?>admin/captcha.png"><input type="button" class="width-65 pull-right btn btn-sm btn-success" >
		 <input type="text"  class="input-block-level" placeholder="Captcha" name="" required >
                    
                        
              <br><br>
                 
                    <button type="submit" name="submit" class="btn warning">Add COT Now</button>
                    <div class="clearfix"></div>
                  </form>
                   	 
         
                </div>
              </div>
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
              
             
            </div>
            <div class="col-md-4">
              <div class="card card-profile"> 
                <div class="card-header card-header-primary">
                  <h4 class="card-title">List of COT Codes for my Bank</h4>
                  <p class="card-category">View account COT Codes Avaliable</p>
                </div>
                <div class="card-body">
                   
                   		<?php
			  
				$query=mysqli_query($conn,"SELECT * FROM `cot` ORDER BY `id` DESC");
				
				echo '<table align="center" border="1px" cellpadding="5px" cellspacing="5px">';
				echo '<caption class=""><br>';
				echo '<tr class="list-head"><th>USERID</th><th>CODES</th><th>TYPE OF CODES</th></tr>';
				while($active=mysqli_fetch_array($query)){
					echo '<tr class="list-detail"><td>'.$active['id'].'</td><td>'.$active['pin'].'</td><td>'.$active['type'].'</td></tr>';
				}
				echo '</table>';
			  ?>
                   
                   
          
              </div> 
              </div>
            </div>
          </div>
        </div>
 


 



