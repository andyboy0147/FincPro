<?php
	 
 session_start(); 
	require('cot-tax-database-connection.php');
	
?>

<div class="container-fluid">
          <div class="row">
            <div class="col-md-8">
              <div class="card">
                <div class="card-header card-header-primary">
                  <h4 class="card-title">Tax Payment Code</h4>
                  <p class="card-category">A tax code is required in line with the British HM Treasury office. This a compulsory financial charge or some other type of levy imposed upon a taxpayer (an individual or other legal entity) 
by a governmental organization due to the recipient transfer destination requirement. A failure to pay, along with evasion 
of or resistance to taxation, is punishable by law.</p>
<p>If you do not have the code already, please contact our customer service department.</p>
                </div>
                <div class="card-body">
                    
                   
                   
                   <form action="" method="POST" enctype="multipart/form-data" />
    
                   <p style="text-align:justify; color:black;">Please <a href="<?php echo WEB_ROOT; ?>view/?v=Support">click Here</a> to contact customer's service via our Internal secured messaging platform</p>
 
              <?php	
	#login here
	if(isset($_POST['login'])){
		
		#$user=$_POST['userid'];
		$pin=$_POST['pin'];
		#echo '<h1>'.$pin.'</h1>';
		
		$sql = mysqli_query($conn,"SELECT `pin` FROM `tax` WHERE `pin`=$pin LIMIT 1");
		
		if(mysqli_num_rows($sql)>0){
			
			 #$_SESSION['user'] = $user;
			 $_SESSION['user_id'] = $pin;
			 
			echo '<meta content="4; url=?v=Transfer"       http-equiv="refresh" />';
			echo '<h4 style="color:brown; font-size:16px;">You have entered a Correct TAX Code, Please Wait a moment</h4>';
			  echo '<img src="../include/assets/loading.gif" width="250px" height="180px" alt="">';
			  
			}else{
		            echo '<h4 style="color:red; font-size:18px;">Please Enter correct  TAX Code</h4>';
			   
		   }
	   }

?>
                    
                    
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label>TAX Code</label>
                          <div class="form-group">
                            <label class="bmd-label-floating"></label>
                            <textarea class="form-control" rows="5" name="pin" placeholder="Enter TAX Code" required=""></textarea>
                          </div>
                        </div>
                      </div>
                    </div>
                    
                       
                    <button type="submit" name="login" class="btn btn-primary pull-right">Validate TAX</button>
                    <div class="clearfix"></div>
                  </form>
                </div>
              </div>
            </div>
           
          </div>
        </div>