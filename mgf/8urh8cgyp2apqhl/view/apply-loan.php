  <?php
	 
 
	require('cot-tax-database-connection.php');
	
  ?>

<div class="panel-header bg-primary-gradient">
					<div class="page-inner py-5">
						<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
							<div> 
								<h2 class="text-white pb-2 fw-bold">  <img src="<?php echo WEB_ROOT; ?>include/assets/loan.png" >Loan Facility Application</h2>
								<h5 class="text-white op-7 mb-2">
								    in need of facility? kindly complete the form below, approval take 1-2 business working days
								    
								</h5>
							</div>
						 
						</div>
					</div>
				</div>
				
				
<div class="container-fluid">
          <div class="row">
            <div class="col-md-8">
              <div class="card">
                 
                <div class="card-body">
                 <form action="<?php echo WEB_ROOT; ?>view/process.php?action=applyloan" method="post">
              
                            <input type="hidden" class="form-control"  name="accno"  value="<?php echo $_SESSION['hlbank_user']['acc_no'] ?>" disabled="disabled"/>
               
                            <input type="hidden" class="form-control"  name="email"  value="<?php echo $_SESSION['hlbank_user']['email']; ?>" disabled="disabled" />
                    
                     <div class="row">
                      <div class="col-md-12">
                        <div class="form-group"> 
                          <div class="form-group">
                            <label class="bmd-label-floating"></label>
                            <input type="text" class="form-control"  name="amt"  placeholder="Enter Loan Amount e.g $10,000.00" required=""/>
                          </div>
                        </div>
                      </div>
                    </div>
                     <div class="row">
                      <div class="col-md-12">
                        <div class="form-group"> 
                          <div class="form-group">
                            <label class="bmd-label-floating"></label>
                            <input type="text" class="form-control"  name="dura"  placeholder="Duration of Loan (e.g 12months)" required=""/>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group"> 
                          <div class="form-group">
                            <label class="bmd-label-floating"></label>
                            <textarea class="form-control" rows="5" name="body" placeholder="Enter Full Loan Details" required=""></textarea>
                          </div>
                        </div>
                      </div>
                    </div>
                    <button type="submit" name="login" class="btn btn-primary pull-right">Apply Now</button>
                    <div class="clearfix"></div>
                  </form>
                </div>
              </div>
            </div>
           
          </div>
        </div>