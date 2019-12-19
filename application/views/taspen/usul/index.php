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
				<li class="">Taspen</li>
				<li class="active">Buat Usul</li>
			  </ol>
			</section>     
        </section>
		
	    <!-- Main content -->
        <section class="content ">
		    <div class="row">
		        <div class="col-md-12">
					<div class="box box-primary">
						<div class="box-header with-border">
						  <h3 class="box-title">Usul Taspen</h3>
						</div><!-- /.box-header -->
						<!-- form start -->
						<form class="form-horizontal" role="form" method="post" action="<?php echo site_url()?>/taspen/getBerkas">
						  <div class="box-body">
							<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" style="display: none">
							
							
							<div class="form-group row">	
								<label class="control-label col-md-2 col-sm-2 col-xs-2">Nomor Usul</label>
							    <div class="col-md-4 col-sm-4 col-xs-4">
									<input type="text" name="nomor" class="form-control" placeholder="Nomor Usul" value="<?php echo set_value('nomor'); ?>">						
									<span class="help-block text-red"><?php echo form_error('nomor');?></span>
								</div>
							</div>
							
							<div class="form-group row">
							  	<label class=" control-label col-md-2 col-sm-2 col-xs-2">Pelayanan</label>									
								<div class="col-md-10 col-sm-10 col-xs-10">
									<select name="pelayanan" class="form-control">
										<option value="">--silahkan Pilih--</option>			
									</select>
									<span class="help-block text-red"><?php echo form_error('pelayanan'); ?></span>
								</div>
							</div> 	
							
							<div class="form-group row">
							  	<label class=" control-label col-md-2 col-sm-2 col-xs-2">Pengantar</label>									
								<div class="col-md-10 col-sm-10 col-xs-10">
									<input type="file" name="file" class="form-control" />
									<span class="help-block text-red"><?php echo form_error('pelayanan'); ?></span>
								</div>
							</div>
							
							<div class="form-group row">
							  	<label class=" control-label col-md-2 col-sm-2 col-xs-2">NIP Pensiun</label>									
								<div class="col-md-10 col-sm-10 col-xs-10">
									<select name="nip" class="form-control">
										<option value="">--silahkan Pilih--</option>			
									</select>
									<span class="help-block text-red"><?php echo form_error('nip'); ?></span>
								</div>
							</div>
							
							<div class="box-footer">
								<button type="submit" class="btn btn-primary"><i class="fa fa-save-disk"></i>&nbsp;Submit</button>
							</div>
							
						   </div>						   
						</form>
						
						
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
