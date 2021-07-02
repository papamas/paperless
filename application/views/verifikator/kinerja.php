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
            <li class="">Verifikator</li>
			<li class="active">Kinerja</li>
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
						  <h3 class="box-title">Kinerja Verifikator</h3>
						</div><!-- /.box-header -->
						<!-- form start -->
						<form class="form-horizontal" role="form" method="post" action="<?php echo site_url()?>/verifikator/getKinerja">
						 <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" style="display: none">
						    <div class="box-body">
								<div class="form-group">
								  <label class="col-md-2">Instansi</label>
								  <div class="col-md-10">
									<select name="instansi" class="form-control select2">
										<option value="">--</option>
										<option value="9">TASPEN</option>
										<?php if($instansi->num_rows() > 0):?>
										<?php foreach($instansi->result() as $value):?>
										<option value="<?php echo $value->INS_KODINS?>" <?php echo  set_select('instansi', $value->INS_KODINS); ?> ><?php echo $value->INS_NAMINS?></option>
										<?php endforeach;?>
										<?php endif;?>
									</select>
									<span class="help-block text-red"><?php echo form_error('instansi'); ?></span>
								  </div>	
								</div>
								<div class="form-group">
									<label class="col-md-2">Pelayanan</label>
									<div class="col-md-10">
										<select name="layanan" class="form-control">
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
									<label class="col-md-2 control-label">Periode Kinerja:</label>
									<div class="col-md-6">
									  <div class="input-group">
										<span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
										<input type="text"  style="" name="reportrange" id="reportrange" class="form-control" value="<?php echo date("d/m/Y", strtotime( "-1 month" )).' - '.date( "d/m/Y")?>"/>  
									  </div>
									  	<span class="help-block text-red"><?php echo form_error('reportrange'); ?></span>
									</div>										
								</div>
								<div class="form-group">
									<label class="control-label col-md-2">Status</label>
									<div class="col-md-6">
										<input type="radio" value="ACC" name="status"   />&nbsp;ACC
										<input type="radio" value="BTL" name="status"  />&nbsp;BTL
										<input type="radio" value="TMS" name="status"  />&nbsp;TMS
										<input type="radio" value="ALL" name="status"  checked />&nbsp;SEMUA
									</div>	
								</div> 	
								<div class="form-group">
									<label class="col-md-2">Pemeriksa</label>
									<div class="col-md-5">
										<select name="verifikator" class="form-control">										
											<?php if($verifikator->num_rows() > 0):?>
											<?php foreach($verifikator->result() as $value):?>
											<option value="<?php echo $value->user_id?>" <?php echo ($this->session->userdata('user_id') == $value->user_id ? 'selected="selected"' : '')?>><?php echo $value->first_name.' '.$value->last_name?></option>
											<?php endforeach;?>
											<?php endif;?>
										</select>
									</div>	
									<label class="control-label col-md-1">Perintah</label>
									<div class="col-md-4">
										<input type="radio" required value="1" name="perintah"  checked />&nbsp;Tampil
										<input type="radio" required value="2" name="perintah"   />&nbsp;Download
										
									</div>	
								</div>							
						    </div>
						    <div class="box-footer">
							<button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;Cari</button>
						  </div>
						</form>
						<hr/>
						<?php if($show):?>
						<div class="table-responsive">
						<table class="table table-striped">
						<thead>
							<tr>
								<th>NOUSUL</th>								
								<th>NIP</th>
								<th>NAMA</th>
								<th>USUL DATE</th>
								<th>VERIF DATE</th>
								<th>PELAYANAN</th>                               						
								<th>STATUS</th>
															
							</tr>
						</thead>   
						<tbody>
							<?php if($usul->num_rows() > 0):?>
							<?php  foreach($usul->result() as $value):?>
							<tr>
								<td><?php echo $value->agenda_nousul?></td>								
								<td><?php echo $value->nip?></td>
								<td><?php echo $value->nama?></td>
								<td><?php echo $value->agenda_timestamp?></td>	
								<td><?php echo $value->verify_date?></td>									
								<td><?php echo $value->layanan_nama?></td>
								<td><span class="<?php echo $value->bg?>"><?php echo $value->nomi_status?></span></td>						
							</tr>
							<?php endforeach;?>
							<?php endif;?>
													
						</tbody>
						</table>
						</div>
						<?php endif;?>
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
			   startDate : moment().subtract(3, 'months'),
			   locale: 'id',
	    });	
	});
</script>
	</body>
</html>
