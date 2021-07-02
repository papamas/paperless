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
            <li class="">Pengeluaran</li>
			<li class="active">Bidang Mutasi</li>
          </ol>
        </section>
			
          </ol>
        </section>
		
	    <!-- Main content -->
        <section class="content ">
		    <div class="row">
		        <div class="col-md-12">
					<div class="box box-danger">
						<div class="box-header with-border">
						  <h3 class="box-title">Pengeluaran Bidang Mutasi</h3>
						</div><!-- /.box-header -->
						
						
						<form class="form-horizontal" method="post" action="<?php echo site_url()?>/mutasi/getPengeluaran" role="form" >
						<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" style="display: none">
						
						<input type="hidden"   name="agendaId" class="form-control" value=""/> 
						<input type="hidden"   name="layananId" class="form-control" value=""/> 

						  <div class="box-body">
							<div class="form-group">
							  <label class="col-md-2">Nomor Usul Instansi</label>
								<div class="col-md-10">
									<select name="nomorUsul" id="nomorUsul" class="form-control">
										<option value="">--</option>										
									</select>
									<span class="help-block text-red"><?php echo form_error('nomorUsul'); ?></span>	
								</div>							    						  
							</div>							
							<div class="form-group">
								<label class="control-label col-md-2">Nomor Pengeluaran</label>
								<div class="col-md-4">								   					
									<input type="text"   name="nomorPengeluaran" class="form-control" value="<?php echo set_value('nomorPengeluaran'); ?>"/>  					
									<span class="help-block text-red"><?php echo form_error('nomorPengeluaran'); ?></span>	
								</div>					
								<label class="control-label col-md-2">Nomor</label>
								<div class="col-md-4">
									<input type="text"   name="nomor" class="form-control" value="<?php echo set_value('nomor'); ?>"/>  
									<span class="help-block text-red"><?php echo form_error('nomor'); ?></span>	
								</div>	
							</div>							
							<div class="form-group">
							  <label class="col-md-2">Spesimen Pengeluaran</label>
								<div class="col-md-10">
									<select name="spesimenPengeluaran" class="form-control">
										<option value="">-Silahkan Pilih-</option>
										<?php foreach($spesimen->result() as $value ):?>
										<option value="<?php echo $value->nip?>" <?php echo  set_select('spesimenPengeluaran', $value->nip); ?>><?php echo $value->nama?></option>
										<?php endforeach;?>
									</select>
									<span class="help-block text-red"><?php echo form_error('spesimenPengeluaran'); ?></span>	
								</div>							    						  
							</div>
							<div class="form-group">
								<label class="control-label col-md-2">Jabatan Satuan Kerja</label>
								<div class="col-md-9">
									<textarea  name="satker" class="form-control" ><?php echo set_value('satker'); ?></textarea> 					
								</div>
								<div class="col-md-1">
									<input type="checkbox" class="checkbox" name="checkSatker" /> 
								</div>
							</div>
							<div class="form-group row">
							    <label class="control-label col-md-2">Nama Daerah</label>
								<div class="col-md-4">
									<input type="text"  name="namaDaerah" class="form-control" value="<?php echo set_value('namaDaerah'); ?>"/>  					
								</div>
								<label class="control-label col-md-2">Lokasi Satker</label>
								<div class="col-md-4">
									<input type="text"  name="lokasiSatker" class="form-control" value="<?php echo set_value('lokasiSatker'); ?>"/>  					
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
	    $("#nomorUsul").select2({
			placeholder: '-Masukan Nomor Usul Instansi-',
			width: '100%',
		    minimumInputLength: 10,
    	    ajax: {
				url:  '<?php echo site_url() ?>'+'/mutasi/getAgenda',
				dataType:'json',
				type:'GET',
				cache: "true",
				delay: 250,
                processResults: function (data) {
					return {
						results: $.map(data, function(obj) {
							return { 
								id: obj.layanan_id+' '+obj.agenda_id, 
								text:  obj.agenda_nousul 
							};
						})
					};
				}                     
			}			  
		});		
		
		$("#nomorUsul").on("select2:select",function(e){
			var val  	= this.value.split(' '),
				layanan = val[0],
				agenda  = val[1];
			
			$.ajax({
			    url: "<?php echo site_url()?>/mutasi/getNomorPengeluaran",
				dataType:'json',
				type:'GET',
				data:{q:layanan},
				success: function(r){		
                    $("input[name=nomorPengeluaran]").val(r.nomor_surat);
					$("input[name=nomor]").val(r.nomor);
					$("input[name=agendaId]").val(agenda);
					$("input[name=layananId]").val(layanan);
					/*if(r.ada)
					{
						$("input[name=nomorPengeluaran]").prop("readonly",true);
					}
					else
					{
						$("input[name=nomorPengeluaran]").prop("readonly",false);
					}*/	
		        },
			});		
		});	
		
		$("input[name=lokasiSatker]").on('keyup',function(e){
			$("input[name=checkSatker]").prop("checked",true);
		});	
		
		
		
	});
    </script>
	</body>
</html>
