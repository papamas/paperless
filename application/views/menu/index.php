<!DOCTYPE html>
<html>
 <head>
    <?php echo $this->load->view('vheader');?>
	<link rel="stylesheet" href="<?php echo base_url()?>assets/dist/css/tree.css">   
	<link rel="stylesheet" href="<?php echo base_url()?>assets/dist/css/custom.css">   
  </head> 	
	<style>
    </style>
  </head>
  <body class="hold-transition skin-yellow">
  <div class="wrapper">	
	 <header class="main-header">
        <!-- Logo -->
        <?php echo $this->load->view('vlogo');?>
        <!-- navbar header-->
		<?php echo $this->load->view('vnavbar-header');?>
        <!-- end navbar header -->
       </header>
       <!-- Left side column -->
        <?php echo $this->load->view('vleft-side');?>
       <!-- End Left side column -->
	
	<!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper"> 
	  
	     <!-- Content Header (Page header) -->
        <section class="content-header">          
           <section class="content-header">          
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i>Home</a></li>
            <li class="">Settings</li>
			<li class="active">Menu</li>
          </ol>
        </section>
			
          </ol>
        </section>
		
	    <!-- Main content -->
        <section class="content ">
		    <div class="row">
		        <div class="col-md-12">
					<div class="box box-primary">
						<div class="box-header with-border">
						  <h3 class="box-title">Perekaman Data Menu</h3>
						</div><!-- /.box-header -->
						<!-- form start -->
						<form class="form-horizontal" role="form">
						  <div class="box-body">							
							<div class="form-group">
							  <label  class="col-sm-2">Nama Menu</label>
							  <div class="col-sm-4">
							     <input type="text" class="form-control" name="first_name" required/>
							  </div>
                              <label  class="col-sm-2">Icon</label>
							  <div class="col-sm-4">
							     <input type="text" class="form-control" name="last_name" required/>
							  </div>							  
							</div>
							<div class="form-group">
							  <label  class="col-sm-2">Link</label>
							  <div class="col-sm-4">
							    <input type="text" class="form-control" name="jabatan" required/>
							  </div>	
							  <label  class="col-sm-2">Active</label>
							  <div class="col-sm-4">
							    <input type="radio" required value="1" name="active" checked />&nbsp;Aktif
								<input type="radio" required value="2" name="active"  />&nbsp;Tidak
							  </div>
							</div>							
							<div class="form-group">
							  <label  class="col-sm-2">Parent Menu</label>
							  <div class="col-sm-10">
							    <select name="bidang" class="form-control" required>
								    <option value="">--Silahkan Pilih--</option>
								   
								</select>
							  </div>	
							</div>
							
						  </div><!-- /.box-body -->

						  <div class="box-footer">
							<button type="submit" class="btn btn-primary">Submit</button>
						  </div>
						</form>
					</div><!-- /.box -->
		        </div>
		    </div>
			
			<div class="row">
				<div class="box box-primary">
				    <div class="box-body">
						<div class="col-md-12">
						 <?php echo $menu2?>
						</div>
					</div>	
                </div>					
			</div>
			
        </section><!-- /.content -->		
      </div><!-- /.content-wrapper -->     
    </div><!-- ./wrapper -->
	
	
	
	<script src="<?php echo base_url()?>assets/plugins/jQuery/jQuery-2.1.4.min.js"></script>    
    <script src="<?php echo base_url()?>assets/bootstrap/js/bootstrap.min.js"></script> 
    <script src="<?php echo base_url()?>assets/dist/js/app.min.js"></script>
	</body>
</html>
