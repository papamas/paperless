<!DOCTYPE html>
<html>
 <head>
    <?php echo $this->load->view('vheader');?>
	<link rel="stylesheet" href="<?php echo base_url()?>assets/dist/css/tree.css">   
	<link rel="stylesheet" href="<?php echo base_url()?>assets/dist/css/custom.css"> 
	<link rel="stylesheet" href="<?php echo base_url()?>assets/plugins/select2/select2.min.css">
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
			<li class="active">PUPNS</li>
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
						  <h3 class="box-title">Penambahan Data PUPNS</h3>
						</div><!-- /.box-header -->
						<!-- form start -->
						<form class="form-horizontal" method="post" action="<?php echo site_url()?>/pupns/salin" role="form">
						  <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" style="display: none">
						  <input type="hidden" name="user_id" />
						  <div class="box-body">
							<?php echo $pesan;?>
							
							<div class="form-group">
							   	<label  class="col-sm-2">NIP</label>
							    <div class="col-sm-4">
							        <select id="nip" name="nip" class="form-control">
							       </select>
								   <span class="help-block text-red"><?php echo form_error('nip'); ?></span>
							    </div>	
								<label  class="col-sm-1">Nama</label>
							    <div class="col-sm-5">
							        <input type="text" class="form-control" name="nama" value="<?php echo set_value('nama'); ?>"/>
								    <span class="help-block text-red"><?php echo form_error('nama'); ?></span>
							    </div>
							</div>
							
							<div class="form-group">
							    <label class="col-md-2 control-label">Sex</label>
								<div class="col-md-2">
									<input type="radio" value="1" name="sex"  <?php echo  set_radio('sex', '1');?> /> Pria
									<input type="radio" value="2" name="sex"  <?php echo  set_radio('sex', '2');?>/> Wanita
									<span class="help-block text-red"><?php echo form_error('sex'); ?></span>
								</div>
							   	
								<label class="col-md-2 control-label">Status Kepegawaian</label>
								<div class="col-md-2">
									<input type="radio" value="P" name="statusKepegawaian"  <?php echo  set_radio('statusKepegawaian', 'P');?> /> PNS
									<input type="radio" value="C" name="statusKepegawaian"  <?php echo  set_radio('statusKepegawaian', 'C');?>/> CPNS
									<span class="help-block text-red"><?php echo form_error('sex'); ?></span>
								</div>	
                                <label  class="col-md-2">Tanggal Lahir</label>
							    <div class="col-md-2">
				                    <input type="text" class="form-control" name="TglLahir" value="<?php echo set_value('TglLahir'); ?>"/>
									<span class="help-block text-red"><?php echo form_error('TglLahir'); ?></span>
								</div>								
							</div>	
							
							<div class="form-group">
							    <label class="col-md-2">Golongan Awal</label>
								<div class="col-md-4">
									<select class="form-control" name="golAwal" >
										<option value="">--</option>
										<?php foreach($golongan->result() as $value):?>	
										<option value="<?php echo $value->kode?>" <?php echo set_select('golAwal', $value->kode); ?>><?php echo $value->nama.' - '.$value->pangkat?></option>
										<?php endforeach;?>
									</select>
									<span class="help-block text-red"><?php echo form_error('golAwal'); ?></span>
								</div>		
							    <label class="col-md-2">Golongan Akhir</label>
								<div class="col-md-4">
									<select class="form-control" name="golAkhir" >
										<option value="">--</option>
										<?php foreach($golongan->result() as $value):?>	
										<option value="<?php echo $value->kode?>" <?php echo set_select('golAkhir', $value->kode); ?>><?php echo $value->nama.' - '.$value->pangkat?></option>
										<?php endforeach;?>
									</select>
									<span class="help-block text-red"><?php echo form_error('golAkhir'); ?></span>
								</div>		
							</div>
							<div class="form-group">
								<label class="col-sm-2">Instansi Induk</label>
								<div class="col-sm-4">
									<select class="form-control" name="instansiInduk"  >
									<option value="">--Silahkan Pilih--</option>
									<?php foreach($instansi->result() as $value):?>
									<option value="<?php echo $value->INS_KODINS?>" <?php echo set_select('instansiInduk', $value->INS_KODINS); ?>><?php echo $value->INS_NAMINS?></option>
									<?php endforeach;?>
									</select>
									<span class="help-block text-red"><?php echo form_error('instansiInduk'); ?></span>
								</div>
								<label class="col-sm-2">Instansi Kerja</label>
								<div class="col-sm-4">
									<select class="form-control" name="instansiKerja"  >
									<option value="">--Silahkan Pilih--</option>
									<?php foreach($instansi->result() as $value):?>
									<option value="<?php echo $value->INS_KODINS?>" <?php echo set_select('instansiKerja', $value->INS_KODINS); ?>><?php echo $value->INS_NAMINS?></option>
									<?php endforeach;?>
									</select>
									<span class="help-block text-red"><?php echo form_error('instansi'); ?></span>
								</div>	
							</div>
							<div class="form-group">
							   	<label  class="col-sm-2">KANREG</label>
							    <div class="col-sm-4">
							        <input type="text" class="form-control" name="kanreg" value="<?php echo set_value('kanreg'); ?>"/>
								    <span class="help-block text-red"><?php echo form_error('kanreg'); ?></span>
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
        </section><!-- /.content -->		
      </div><!-- /.content-wrapper -->     
    </div><!-- ./wrapper -->
	
	
	
	<script src="<?php echo base_url()?>assets/plugins/jQuery/jQuery-2.1.4.min.js"></script>    
    <script src="<?php echo base_url()?>assets/bootstrap/js/bootstrap.min.js"></script> 
    <script src="<?php echo base_url()?>assets/dist/js/app.min.js"></script>
	<script src="<?php echo base_url()?>assets/plugins/select2/select2.min.js"></script>

	<script>	
	$(document).ready(function () {
	    $('[data-tooltip="tooltip"]').tooltip();	
		<?php if($this->input->post()):?>
		$("#nip").append(new Option ('<?php echo set_value('nip'); ?>','<?php echo set_value('nip'); ?>',true,true)).trigger("change");
		<?php endif;?>
		
		$("#nip").select2({
					placeholder: 'Masukan NIP',
					width: '100%',
					minimumInputLength: 17,
					ajax: {
						url:  "<?php echo site_url()?>/pupns/getPns",
						dataType:'json',
						type:'GET',
						cache: "true",
						delay: 250,
						processResults: function (data) {
							return {
								results: $.map(data, function(obj) {
									return { id: obj.NIP_BARU, text: obj.NIP_BARU+'-'+obj.NAMA };
								})
							};
						}                       
					} 
				});
		
		$("#nip").change(function(){
		    myApp.showPleaseWait();
			$('.progress-bar').css('width', '' + 0 + '%');
			$('.progress-bar').text( 0 + '% Complete' );
			$('.progress-bar').attr("aria-valuenow", 0);
			
			$.ajax({
			    url: "<?php echo site_url()?>/pupns/getPnsdata",
				dataType:'json',
				type:'GET',
				data:{q:this.value},
				success: function(result){                  
					$("input[name=nama]").val(result.GELAR_DEPAN+' '+result.NAMA+' '+result.GELAR_BLK);
					$("input[name=sex][value='"+result.SEX+"']").prop("checked",true);
					$("input[name=statusKepegawaian][value='"+result.STATUS_CPNS_PNS+"']").prop("checked",true);
					$("input[name=TglLahir]").val(result.TGL_LHR);
					$("[name=golAwal]").val(result.GOLONGAN_AWAL_ID);
					$("[name=golAkhir]").val(result.GOLONGAN_ID);
					$("[name=instansiInduk]").val(result.INSTANSI_INDUK);
					$("[name=instansiKerja]").val(result.INSTANSI_KERJA);
					$("input[name=kanreg]").val(result.KANREG_ID);
					setTimeout(function() {myApp.hidePleaseWait();	} , 1000);					
		        },
				xhr: function() {
						var xhr = new window.XMLHttpRequest();
						xhr.addEventListener('progress', function(e) {
							if (e.lengthComputable) {
							    var value = (100 * e.loaded / e.total);
								$('.progress-bar').css('width', '' + value + '%');
								$('.progress-bar').text( value + '% Complete' );
								$('.progress-bar').attr("aria-valuenow", value);
								
							}
						});
						return xhr;
					}, 
			});			
		  
		});
		
		var myApp;
		myApp = myApp || (function () {
			var pleaseWaitDiv = $('<div class="modal fade" id="pleaseWaitDialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><img src="<?php echo base_url()?>assets/dist/img/load.gif"/><label> Processing...</label></h2></div><div class="modal-body"><div class="progress"><div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div></div></div></div></div>');
			return {
				showPleaseWait: function() {
					pleaseWaitDiv.modal('show');
				},
				hidePleaseWait: function () {
					pleaseWaitDiv.modal('hide');
				},	

			};
		})();  
		
		
	});
	</script>	
	</body>
</html>
