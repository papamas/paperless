<!DOCTYPE html>
<html>
 <head>
    <?php  $this->load->view('vheader');?>
	<link rel="stylesheet" href="<?php echo base_url()?>assets/dist/css/tree.css">   
	<link rel="stylesheet" href="<?php echo base_url()?>assets/dist/css/custom.css">   
	<link rel="stylesheet" href="<?php echo base_url()?>assets/plugins/select2/select2.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/plugins/daterange/daterangepicker-bs3.css" />
  </head> 	
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
	  
	     <!-- Content Header (Page header) -->
        <section class="content-header">          
           <section class="content-header">          
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i>Home</a></li>
            <li class="">Laporan</li>
			<li class="active">Bidang Mutasi</li>
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
						  <h3 class="box-title">Laporan Bidang Mutasi dan Status Kepegawaian</h3>
						</div><!-- /.box-header -->
						<!-- form start -->
						<form class="form-horizontal" method="post" action="<?php echo site_url()?>/mutasi/getLaporan" role="form">
						<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" style="display: none">
						  <div class="box-body">
							<div class="form-group">
							  <label class="col-md-2">Instansi</label>
							  <div class="col-md-10">
							    <select name="instansi" class="form-control select2">
									<option value="">--</option>
									<?php if($instansi->num_rows() > 0):?>
									<?php foreach($instansi->result() as $value):?>
									<option value="<?php echo $value->INS_KODINS?>" <?php echo  set_select('instansi', $value->INS_KODINS); ?>><?php echo $value->INS_NAMINS?></option>
									<?php endforeach;?>
									<?php endif;?>
								</select>
								<span class="help-block text-red"><?php echo form_error('instansi'); ?></span>
							  </div>	
							</div>
							<div class="form-group">
							  <label class="col-md-2">Pelayanan</label>
							  <div class="col-md-10">
							    <select name="layanan" class="form-control ">
								    <option value="">--</option>
									<?php if($layanan->num_rows() > 0):?>
									<?php foreach($layanan->result() as $value):?>
									<option value="<?php echo $value->layanan_id?>" <?php echo  set_select('layanan', $value->layanan_id); ?>><?php echo $value->layanan_nama?></option>
									<?php endforeach;?>
									<?php endif;?>
								</select>
								<span class="help-block text-red"><?php echo form_error('layanan'); ?></span>
							  </div>	
							</div>
							<div class="form-group">
								<label class="col-md-2 control-label">Periode</label>
								<div class="col-md-5 controls">
								  <div class="input-group">
									<span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
									<input type="text"  style="" name="reportrange" id="reportrange" class="form-control" value="<?php echo date("d/m/Y", strtotime( "-1 month" )).' - '.date( "d/m/Y")?>"/>  
								  </div>
								</div>
								<span class="help-block text-red"><?php echo form_error('reportrange'); ?></span>	
								<label class="control-label col-md-1">By Date</label>
								<div class="col-md-4">
								    <input type="radio" required value="3" name="bydate"  checked />&nbsp;Berkas Masuk
									<input type="radio" required value="1" name="bydate"    />&nbsp;Verifikasi
									<input type="radio" required value="2" name="bydate"  />&nbsp;Entry SAPK								
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-md-2">Status</label>
								<div class="col-md-4">
									<input type="radio" required value="ACC" name="status"   />&nbsp;ACC
									<input type="radio" required value="BTL" name="status"  />&nbsp;BTL
									<input type="radio" required value="TMS" name="status"  />&nbsp;TMS
									<input type="radio" required value="ALL" name="status"  checked />&nbsp;SEMUA
								</div>		
							</div> 	
							
						   </div>
						    <div class="box-footer">
							<button type="submit" class="btn btn-primary"><i class="fa fa-print"></i>&nbsp;Print</button>
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
	<script src="<?php echo base_url()?>assets/plugins/daterange/moment-with-locales.js"></script>	
	<script type="text/javascript" src="<?php echo base_url()?>assets/plugins/daterange/daterangepicker.js"></script>
	<script src="<?php echo base_url()?>assets/plugins/select2/select2.full.min.js"></script>
	<script>	
	$(document).ready(function () {
	    $(".select2").select2({
			width: '100%'
		});			
		
		$('#reportrange').daterangepicker({
			   format: 'DD/MM/YYYY',
			   minDate: '01/04/2015',
			   locale: 'id',
	    });	
	});
</script>
	</body>
</html>
