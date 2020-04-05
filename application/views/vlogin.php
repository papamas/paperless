<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Aplikasi Male_o 1.9 | Login page</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="<?php echo base_url()?>assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url()?>assets/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo base_url()?>assets/dist/css/AdminLTE.css"> 
    <link rel="shortcut icon" href="<?php echo base_url()?>assets/dist/img/favicon_garuda.ico">	
	
	<style>
	
	
	.limiter {
	  width: 100%;
	  margin: 0 auto;
	}

	.container-login100 {
	  width: 100%;  
	  min-height: 100vh;
	  display: flex;	  
	  justify-content: center;
	  align-items: center;	  
	  background: #f2f2f2;
	 
	}
	
	.aligned-row{		
		display: -webkit-flex;
	    display: -ms-flexbox;
	    display: flex;
	    overflow: hidden;
		flex-direction: row-reverse;
	}	
	
	.col{
		background: rgba(0,0,0,0.3);
		display: table-cell;
	}	
	
	.carousel-inner .item img{
		width:650px;
		height:407px;
	}	
	
		
	/* If the browser window is smaller than 600px, make the columns stack on top of each other */
	@media only screen and (max-width: 600px) {
	  .col {
		display: block;
		width: 100%;
	  }
	}
	</style>
  </head>
   <div class="limiter">
		<div class="container-login100">
			<div class="">
				<div class="row aligned-row">
					<div class="col col-md-6 col-xs-12 no-padding">
						<div class="box box-widget widget-user">
							<!-- Add the bg color to the header using any of the bg-* classes -->
							<div class="widget-user-header bg-yellow-active">
							 
							 
							</div>
							<div class="widget-user-image">
							  <img class="img-circle" src="<?php echo base_url()?>assets/dist/img/asn2.png" alt="User Avatar">
							</div>
							
							
							<form class="form-horizontal" method="post" action="<?php echo site_url()?>/autho/login/">
								<div class="box-body">						
									<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" style="display: none">
									 <br/><br/>
									 <?php echo $message;?>
									
										<div class="col-md-12">
											<div class="form-group form-group-lg">									  
												<div class="input-group"> 			
													<span class="input-group-addon">
													  <i class="fa fa-user"></i>
													</span> <input type="text" required name="username" placeholder="Username" class="form-control">					
												</div>					
											</div>
											<div class="form-group form-group-lg">			
												<div class="input-group"> 				
													<span class="input-group-addon">
														<i class="fa fa-key"></i>
													</span><input type="password" required name="password" placeholder="Password" class="form-control">
												</div>						  
											</div>	
										</div>										
																					 
								</div>					
								<div class="box-footer">
								    <span><small>Male_o 1.9 &copy 2019 BKN XI </small></span>
									<button type="submit" class="btn bg-yellow-active btn-flat pull-right">Sign in</button>
								</div>
							</form>	
						</div>
						
					</div>
					<div class="col col-md-6 hidden-xs no-padding">
						<div id="myCarousel" class="col carousel slide " data-ride="carousel">
						  <!-- Indicators -->
						  <ol class="carousel-indicators">
							<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
							<li data-target="#myCarousel" data-slide-to="1"></li>
							<li data-target="#myCarousel" data-slide-to="2"></li>
							<li data-target="#myCarousel" data-slide-to="3"></li>
							<li data-target="#myCarousel" data-slide-to="4"></li>
							<li data-target="#myCarousel" data-slide-to="5"></li>
							<li data-target="#myCarousel" data-slide-to="6"></li>
							<li data-target="#myCarousel" data-slide-to="7"></li>
							<li data-target="#myCarousel" data-slide-to="8"></li>
							<li data-target="#myCarousel" data-slide-to="9"></li>
							<li data-target="#myCarousel" data-slide-to="10"></li>
							<li data-target="#myCarousel" data-slide-to="11"></li>
							<li data-target="#myCarousel" data-slide-to="12"></li>
						   </ol>
							<!-- Wrapper for slides -->
							<div class="carousel-inner">
								<div class="item active">
								  <img  src="<?php echo base_url()?>assets/dist/img/Infografis-Karpeg.jpg" >
								</div>
								<div class="item">
								  <img  src="<?php echo base_url()?>assets/dist/img/Infografis-Karis-Karsu.jpg">
								</div>
								<div class="item">
								  <img  src="<?php echo base_url()?>assets/dist/img/Infografis-Kenaikan-Pangkat.jpg" >
								</div>
								<div class="item">
								  <img  src="<?php echo base_url()?>assets/dist/img/Infografis-Penyesuai-Ijasah.jpg">
								</div>
								<div class="item">
								  <img  src="<?php echo base_url()?>assets/dist/img/Infografis-Pensiun.jpg">
								</div>
								<div class="item">
								  <img src="<?php echo base_url()?>assets/dist/img/Infografis-Pensiun2.jpg">
								</div>
								<div class="item">
								  <img src="<?php echo base_url()?>assets/dist/img/Fix_Use.jpg">
								</div>
								<div class="item">
								  <img src="<?php echo base_url()?>assets/dist/img/Berkas-KP-1-2-1024x613.png">
								</div>
								<div class="item">
								  <img src="<?php echo base_url()?>assets/dist/img/Berkas-KP-3-4-1024x613.png">
								</div>
								<div class="item">
								  <img src="<?php echo base_url()?>assets/dist/img/Berkas-KP-5-6-1024x613.png">
								</div>
								<div class="item">
								  <img src="<?php echo base_url()?>assets/dist/img/Berkas-KP-9-10-1024x613.png" >
								</div>
								<div class="item">
								  <img src="<?php echo base_url()?>assets/dist/img/Berkas-KP-11-12-1024x613.png" >
								</div>
								<div class="item">
								  <img src="<?php echo base_url()?>assets/dist/img/PI.png" >
								</div>
							</div>	
						</div>  					   
					</div>
				</div>	
			</div>
		</div>
	</div>
    <script src="<?php echo base_url()?>assets/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <script src="<?php echo base_url()?>assets/bootstrap/js/bootstrap.min.js"></script>   
  </body>
</html>
