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
			<li class="active">Bidang Pensiun</li>
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
						  <h3 class="box-title">Pengeluaran Bidang Pensiun</h3>
						</div><!-- /.box-header -->
						<div class="nav-tabs-custom">
							<ul class="nav nav-tabs">							
							  <li class="<?php echo $tab1 ?>"> <a href="#tab1" data-toggle="tab">INSTANSI DAERAH/PUSAT</a></li>
							  <li class="<?php echo $tab2 ?>"> <a href="#tab2" data-toggle="tab">TASPEN</a></li>                  
							</ul>
						</div>	
						<div class="tab-content">					   
							<div class="<?php echo $tab1 ?> tab-pane" id="tab1">
								<form class="form-horizontal" method="post" action="<?php echo site_url()?>/pensiun/getPengeluaran" role="form" >
								<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" style="display: none">
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
										<div class="col-md-2">
											<input type="number"   name="nomorPengeluaran" class="form-control" value=""/>  					
											<span class="help-block text-red"><?php echo form_error('nomorPengeluaran'); ?></span>	
										</div>
										<label class="control-label col-md-2">Pilihan Pengeluaran</label>
										<div class="col-md-2">
											<select name="pilihanPengeluaran" class="form-control">
												<option value="1">Semua</option>	
												<option value="2">Yang Sudah Saja</option>
												<option value="3">Yang Belum Saja</option>	
											</select>
											<span class="help-block text-red"><?php echo form_error('pilihanPengeluaran'); ?></span>
										</div>
										<label class="control-label col-md-2">Cetak Tanda Terima</label>
										<div class="col-md-2">
											<input type="radio" value="1" name="tandaTerima"  <?php echo  set_radio('tandaTerima', 1);?>  />&nbsp;Ya
											<input type="radio" value="2" name="tandaTerima"  <?php echo  set_radio('tandaTerima', 2,true);?>  />&nbsp;Tidak
											<span class="help-block text-red"><?php echo form_error('tandaTerima'); ?></span>
										</div>
										
									</div>							
									<div class="form-group">
									  <label class="col-md-2">Spesimen Pengeluaran</label>
										<div class="col-md-10">
											<select name="spesimenPengeluaran" class="form-control">
												<option value="">-Silahkan Pilih-</option>
												<?php foreach($spesimen->result() as $value ):?>
												<option value="<?php echo $value->nip?>"><?php echo $value->nama?></option>
												<?php endforeach;?>
											</select>
											<span class="help-block text-red"><?php echo form_error('spesimenPengeluaran'); ?></span>	
										</div>							    						  
									</div>
									<div class="form-group">
										<label class="control-label col-md-2">Jabatan Satuan Kerja</label>
										<div class="col-md-9">
											<textarea  name="satker" class="form-control" ></textarea> 					
										</div>
										<div class="col-md-1">
											<input type="checkbox" class="checkbox" name="checkSatker" /> 
										</div>
									</div>
									<div class="form-group row">
										<label class="control-label col-md-2">Lokasi Satker</label>
										<div class="col-md-10">
											<input type="text"  name="lokasiSatker" class="form-control" value=""/>  					
										</div>
									</div>
								   </div>
									<div class="box-footer">
									<button type="submit" class="btn btn-primary"><i class="fa fa-print"></i>&nbsp;Print</button>
								  </div>
								</form>
							</div>
							
							<div class="<?php echo $tab2 ?> tab-pane" id="tab2">
								<form class="form-horizontal" method="post" action="<?php echo site_url()?>/pensiun/getPengeluaranTaspen" role="form" >
								<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" style="display: none">
								    <div class="box-body">
										<div class="form-group">
										  <label class="col-md-2">Nomor Usul Taspen</label>
											<div class="col-md-10">
												<select name="usulTaspen" id="usulTaspen" class="form-control">
													<option value="">--</option>										
												</select>
												<span class="help-block text-red"><?php echo form_error('usulTaspen'); ?></span>	
											</div>							    						  
										</div>	
										<div class="form-group">
											<label class="control-label col-md-2">Nomor Pengeluaran</label>
											<div class="col-md-4">
												<input type="number"   name="nomorPengeluaranTaspen" class="form-control" value=""/>  					
												<span class="help-block text-red"><?php echo form_error('nomorPengeluaranTaspen'); ?></span>	
											</div>
											<label class="control-label col-md-2">Cetak Tanda Terima</label>
											<div class="col-md-4">
												<input type="radio" value="1" name="tandaTerimaTaspen"  <?php echo  set_radio('tandaTerimaTaspen', 1);?>  />&nbsp;Ya
												<input type="radio" value="2" name="tandaTerimaTaspen"  <?php echo  set_radio('tandaTerimaTaspen', 2,true);?>  />&nbsp;Tidak
												<span class="help-block text-red"><?php echo form_error('tandaTerimaTaspen'); ?></span>
											</div>
										</div>
										<div class="form-group">
										    <label class="col-md-2">Spesimen Pengeluaran</label>
											<div class="col-md-10">
												<select name="spesimenPengeluaranTaspen" class="form-control">
													<option value="">-Silahkan Pilih-</option>
													<?php foreach($spesimen->result() as $value ):?>
													<option value="<?php echo $value->nip?>"><?php echo $value->nama?></option>
													<?php endforeach;?>
												</select>
												<span class="help-block text-red"><?php echo form_error('spesimenPengeluaranTaspen'); ?></span>	
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
				url:  '<?php echo site_url() ?>'+'/pensiun/getAgenda',
				dataType:'json',
				type:'GET',
				cache: "true",
				delay: 250,	
                
			},
			results: function(data, page) {
			    return { results: data.results };               
            }  
		});		
		
		$("#nomorUsul").change(function(){			
			$.ajax({
			    url: "<?php echo site_url()?>/pensiun/getNomorPengeluaran",
				dataType:'json',
				type:'GET',
				data:{q:this.value},
				success: function(r){		
                    $("input[name=nomorPengeluaran]").val(r.last_number);
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
		
		$("#usulTaspen").select2({
			placeholder: '-Masukan Nomor Usul TASPEN-',
			width: '100%',
		    minimumInputLength: 10,
    	    ajax: {
				url:  '<?php echo site_url() ?>'+'/pensiun/getUsulTaspen',
				dataType:'json',
				type:'GET',
				cache: "true",
				delay: 250,	
                
			},
			results: function(data, page) {
			    return { results: data.results };               
            }  
		});	

		$("#usulTaspen").change(function(){			
			$.ajax({
			    url: "<?php echo site_url()?>/pensiun/getNomorPengeluaranTaspen",
				dataType:'json',
				type:'GET',
				data:{q:this.value},
				success: function(r){		
                    $("input[name=nomorPengeluaranTaspen]").val(r.last_number);
					if(r.ada)
					{
						$("input[name=nomorPengeluaranTaspen]").prop("readonly",true);
					}
					else
					{
						$("input[name=nomorPengeluaranTaspen]").prop("readonly",false);
					}	
		        },
			});		
		});	
		
	});
    </script>
	</body>
</html>
