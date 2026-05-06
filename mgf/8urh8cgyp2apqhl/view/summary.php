	     
	             <div class="panel-header bg-primary-gradient">
					<div class="page-inner py-5">
						<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
							<div>
								<h2 class="text-white pb-2 fw-bold">My Account Dashboard</h2>
								<h5 class="text-white op-7 mb-2">
								    <strong>Welcome: <?php echo $_SESSION['hlbank_user_name'];  ?>,</strong> 
                            logged  User IP: <?php echo $_SERVER['REMOTE_ADDR']; ?><br />Logged time: <?php echo date("h:i A d M Y"); ?>
								    
								    
								</h5>
							</div>
							<div class="ml-md-auto py-2 py-md-0">
								<a href="<?php echo WEB_ROOT; ?>view/?v=Statement" class="btn btn-white btn-border btn-round mr-2">E-Statement</a>
								<a href="<?php echo WEB_ROOT; ?>view/?v=start-transfer" class="btn btn-secondary btn-round">Funds Transfer</a>
							</div>
						</div>
					</div>
				</div>
		
	            
	            
				<div class="page-inner mt--5">
					<div class="row mt--2">
						<div class="col-md-6">
							<div class="card full-height">
								<div class="card-body">
									<div class="card-title">Overall Transactions</div>
									<div class="card-category">Daily information about transactional statistics</div>
									<div class="d-flex flex-wrap justify-content-around pb-2 pt-4">
										<div class="px-2 pb-2 pb-md-0 text-center">
											<div id="circles-1"></div>
											<h6 class="fw-bold mt-3 mb-0">Deposits</h6>
										</div>
										<div class="px-2 pb-2 pb-md-0 text-center">
											<div id="circles-2"></div>
											<h6 class="fw-bold mt-3 mb-0">Payments</h6>
										</div>
										<div class="px-2 pb-2 pb-md-0 text-center">
											<div id="circles-3"></div>
											<h6 class="fw-bold mt-3 mb-0">Withdrawal</h6>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="card full-height">
							    
							     <?php 
	  $user_id = $_SESSION['hlbank_user']['user_id'];
	  $acc_no = $_SESSION['hlbank_user']['acc_no'];
	  
	  $balance_sql = "SELECT balance FROM tbl_accounts WHERE user_id = $user_id AND acc_no = $acc_no";
	  $result = dbQuery($balance_sql);
	  $row = dbFetchAssoc($result);
	  ?>
								<div class="card-body">
									<div class="card-title">Total income & spend statistics</div>
									<div class="row py-3">
										<div class="col-md-4 d-flex flex-column justify-content-around">
											<div>
												<h6 class="fw-bold text-uppercase text-success op-8">Credit Balance</h6>
												<h3 class="fw-bold"><?php echo $site_currency; ?><?php echo number_format($row['balance'] , 2); ?></h3>
											</div>
											<div>
												<h6 class="fw-bold text-uppercase text-danger op-8">Ledger Balance</h6>
												<h3 class="fw-bold"><?php echo $site_currency; ?><?php echo number_format($row['balance'] + 1170, 2); ?></h3>
											</div>
										</div>
										<div class="col-md-8">
											<div id="chart-container">
												<canvas id="totalIncomeChart"></canvas>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-8">
							<div class="card">
								<div class="card-header">
									<div class="card-head-row">
										<div class="card-title">Transactions Statistics</div>
										<div class="card-tools">
											<a href="#" class="btn btn-info btn-border btn-round btn-sm mr-2">
												<span class="btn-label">
													<i class="fa fa-pencil"></i>
												</span>
												Export
											</a>
											<a href="#" class="btn btn-info btn-border btn-round btn-sm">
												<span class="btn-label">
													<i class="fa fa-print"></i>
												</span>
												Print
											</a>
										</div>
									</div>
								</div>
								<div class="card-body">
									<div class="chart-container" style="min-height: 375px">
										<canvas id="statisticsChart"></canvas>
									</div>
									<div id="myChartLegend"></div>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="card card-primary">
								<div class="card-header">
									<div class="card-title">Maximum Withdrawal</div>
									<div class="card-category">Updated <?php echo date("h:i A d M Y"); ?></div>
								</div>
								<div class="card-body pb-0">
									<div class="mb-4 mt-2">
										<h1><?php echo $site_currency; ?><?php echo number_format($row['balance'] + 438723, 2); ?></h1>
									</div>
									<div class="pull-in">
										<canvas id="dailySalesChart"></canvas>
									</div>
								</div>
							</div>
							<div class="card">
								<div class="card-body pb-0">
									<div class="h1 fw-bold float-right text-warning">+7%</div>
									<h2 class="mb-2">213</h2>
									<p class="text-muted">Transactions</p>
									<div class="pull-in sparkline-fix">
										<div id="lineChart"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row row-card-no-pd">
						<div class="col-md-12">
							<div class="card">
								<div class="card-header">
									<div class="card-head-row card-tools-still-right">
										<h4 class="card-title">Our Branches Worldwide</h4>
										<div class="card-tools">
											<button class="btn btn-icon btn-link btn-primary btn-xs"><span class="fa fa-angle-down"></span></button>
											<button class="btn btn-icon btn-link btn-primary btn-xs btn-refresh-card"><span class="fa fa-sync-alt"></span></button>
											<button class="btn btn-icon btn-link btn-primary btn-xs"><span class="fa fa-times"></span></button>
										</div>
									</div>
									<p class="card-category">
									Map of <?php echo $site_title; ?> all over the world</p>
								</div>
								<div class="card-body">
									<div class="row">
										<div class="col-md-6">
											<div class="table-responsive table-hover table-sales">
												<table class="table">
													<tbody>
														<tr>
															<td>
																<div class="flag">
																	<img src="<?php echo WEB_ROOT; ?>include/assets/img/flags/id.png" alt="indonesia">
																</div>
															</td>
															<td>Indonesia</td>
															<td class="text-right">
																2.320
															</td>
															<td class="text-right">
																42.18%
															</td>
														</tr>
														<tr>
															<td>
																<div class="flag">
																	<img src="<?php echo WEB_ROOT; ?>include/assets/img/flags/us.png" alt="united states">
																</div>
															</td>
															<td>USA</td>
															<td class="text-right">
																240
															</td>
															<td class="text-right">
																4.36%
															</td>
														</tr>
														<tr>
															<td>
																<div class="flag">
																	<img src="<?php echo WEB_ROOT; ?>include/assets/img/flags/au.png" alt="australia">
																</div>
															</td>
															<td>Australia</td>
															<td class="text-right">
																119
															</td>
															<td class="text-right">
																2.16%
															</td>
														</tr>
														<tr>
															<td>
																<div class="flag">
																	<img src="<?php echo WEB_ROOT; ?>include/assets/img/flags/ru.png" alt="russia">
																</div>
															</td>
															<td>Russia</td>
															<td class="text-right">
																1.081
															</td>
															<td class="text-right">
																19.65%
															</td>
														</tr>
														<tr>
															<td>
																<div class="flag">
																	<img src="<?php echo WEB_ROOT; ?>include/assets/img/flags/cn.png" alt="china">
																</div>
															</td>
															<td>China</td>
															<td class="text-right">
																1.100
															</td>
															<td class="text-right">
																20%
															</td>
														</tr>
														<tr>
															<td>
																<div class="flag">
																	<img src="<?php echo WEB_ROOT; ?>include/assets/img/flags/br.png" alt="brazil">
																</div>
															</td>
															<td>Brasil</td>
															<td class="text-right">
																640
															</td>
															<td class="text-right">
																11.63%
															</td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
										<div class="col-md-6">
											<div class="mapcontainer">
												<div id="map-example" class="vmap"></div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				 
				 
				</div>
				
				</div>
				
				
				
			
	