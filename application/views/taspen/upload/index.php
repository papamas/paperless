<!DOCTYPE html>
<html>
  <head>
    <?php  $this->load->view('vheader');?>
	
   	
	<style>
  </style>
  </head>
  <body class="hold-transition skin-yellow">
    <div class="wrapper">
	 <header class="main-header">
        <!-- Logo -->
        <?php  $this->load->view('vlogo');?>
        <!-- navbar header-->
		<?php  $this->load->view('vnavbar-header');?>
        <!-- end navbar header -->
       </header>
       <!-- Left side column -->
        <?php  $this->load->view('vleft-side');?>
       <!-- End Left side column -->
        
	
	<!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper"> 
	    <!-- Main content -->
        <section class="content ">
			<div class="row">
		        <div class="col-md-12">
					<div class="box box-primary">
						<div class="box-header with-border">
						  <h3 class="box-title">Upload Dokumen Taspen</h3>
						</div><!-- /.box-header -->
						<form class="form-horizontal" role="form" action="<?php echo site_url()?>/taspen/doUpload" method="post" accept-charset="utf-8" enctype="multipart/form-data">
							<div class="box-body">
								<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" style="display: none">
								<?php echo (!empty($pesan) ? $pesan : '') ?>
								<div class="form-group row">							  	    
									<label class="control-label col-md-2 col-sm-2 col-xs-2">Jenis Dokumen</label>									
									<div class="col-md-6 col-sm-6 col-xs-6">
										<select name="jenis" class="form-control">
											<option value="">--silahkan Pilih--</option>
											<?php foreach($dokumen->result() as $value):?>
											<option value="<?php echo $value->id_dokumen?>" <?php echo  set_select('jenis', $value->id_dokumen); ?>><?php echo $value->keterangan?></option>
											<?php endforeach?>
										</select>
										<span class="help-block text-red"><?php echo form_error('jenis'); ?></span>
									</div>
									<div class="col-md-4 col-sm-4 col-xs-4">
										<input type="file" name="file" class="form-control" />
										<span class="help-block text-red"><?php echo (!empty($error) ? $error : '') ?></span>
									</div>								
								</div>
								<div class="form-group row">
									<label class=" control-label col-md-2 col-sm-2 col-xs-2">NIP / NRP / NVP</label>									
									<div class="col-md-6 col-sm-6 col-xs-6">
										<input type="text" name="nip" class="form-control" placeholder="Masukan NIP/NRP/NVP" value="<?php echo set_value('nip'); ?>">
										<span class="help-block text-red"><?php echo form_error('nip'); ?></span>
									</div>
								</div>
								
							</div>
								
							<div class="box-footer">
								<button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i>&nbsp;upload dokumen</button>
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
