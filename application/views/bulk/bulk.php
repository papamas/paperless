<!DOCTYPE html>
<html>
 <head>
    <?php echo $this->load->view('vheader');?>	  
	<link rel="stylesheet" href="<?php echo base_url()?>assets/plugins/select2/select2.css">
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
				<li class="">Instansi</li>
				<li class="active">Daftar Photo</li>
			  </ol>
			</section>         
        </section>
		
	    <!-- Main content -->
        <section class="content ">
		    <div class="row">
		        <div class="col-md-12">
					<div class="box box-primary">
						<div class="box-header with-border">
						  <h3 class="box-title">Download Photo Bulk</h3>
						</div><!-- /.box-header -->
						<!-- form start -->
						<form class="form-horizontal" role="form" method="post" action="<?php echo site_url()?>/bulk/getPhoto">
						  <div class="box-body">
							<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" style="display: none">
							
							<div class="form-group">
							  <label class="control-label col-md-2 col-sm-2 col-xs-2">Instansi</label>
							  <div class="col-md-10 col-sm-10 col-xs-10">
							    <select name="instansi" class="form-control select2">	
                                    <option value="">--Silahkan Pilih--</option>									
									<?php if($instansi->num_rows() > 0):?>
									<?php foreach($instansi->result() as $value):?>
									<option value="<?php echo $value->INS_KODINS?>" <?php echo ($this->session->userdata('session_instansi') == $value->INS_KODINS ? 'selected="selected"' : '')?>><?php echo $value->INS_NAMINS?></option>
									<?php endforeach;?>
									<?php endif;?>
								</select>								
								<span class="help-block text-red"><?php echo form_error('instansi'); ?></span>
							  </div>	
							</div>
							<div class="form-group row">							   						 
							    <label class=" control-label col-md-2 col-sm-2 col-xs-2">Layanan</label>									
								<div class="col-md-10 col-sm-10 col-xs-10">
									<select name="layanan" class="form-control">
										<option value="">--silahkan Pilih--</option>
										<?php if($layanan->num_rows() > 0):?>
									    <?php foreach($layanan->result() as $value):?>
										<option value="<?php echo $value->layanan_id?>" <?php echo  set_select('layanan', $value->layanan_id); ?>><?php echo $value->layanan_nama?></option>
										<?php endforeach;?>
										<?php endif;?>
									</select>
									<span class="help-block text-red"><?php echo form_error('layanan'); ?></span>
								</div>
															
							</div>
							<div class="form-group row">							   						 
							    <label class=" control-label col-md-2 col-sm-2 col-xs-2">Filter</label>									
								<div class="col-md-4 col-sm-4 col-xs-4">
									<select name="searchby" class="form-control">
										<option value="">--silahkan Pilih--</option>
										<option value="1" <?php echo  set_select('searchby', '1'); ?> >NOMOR USUL</option>											
									</select>
									<span class="help-block text-red"><?php echo form_error('searchby'); ?></span>
								</div>
								<div class="col-md-6 col-sm-6 col-xs-6">									
								    <input type="text" name="search" class="form-control" placeholder="Masukan data pencarian" value="<?php echo set_value('search'); ?>">
									<span class="help-block text-red"><?php echo form_error('search'); ?></span>
								</div>								
							</div>						
							<div class="box-footer">
								<button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;Download Photo</button>
							  </div>
							
						   </div>						   
						</form>
						<hr/>
						
					</div>
                </div>
            </div> 	
			
        </section><!-- /.content -->	

	
      </div><!-- /.content-wrapper -->     
    </div><!-- ./wrapper -->

	
	
	<script src="<?php echo base_url()?>assets/plugins/jQuery/jQuery-2.1.4.min.js"></script>    
    <script src="<?php echo base_url()?>assets/bootstrap/js/bootstrap.min.js"></script> 
    <script src="<?php echo base_url()?>assets/dist/js/app.min.js"></script>
	<script src="<?php echo base_url()?>assets/plugins/select2/select2.full.min.js"></script>
	<script>	
	$(document).ready(function () {
	    
		$('[data-tooltip="tooltip"]').tooltip();
		$(".select2").select2({
			width: '100%'
		});	
		
	});	
   </script>
	</body>
</html>
