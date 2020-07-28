<!DOCTYPE html>
<html>
 <head>
    <?php echo $this->load->view('vheader');?>
	<link rel="stylesheet" href="<?php echo base_url()?>assets/dist/css/tree.css">   
	<link rel="stylesheet" href="<?php echo base_url()?>assets/dist/css/custom.css"> 
	<link rel="stylesheet" href="<?php echo base_url()?>assets/plugins/select2/select2.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/plugins/datepicker/bootstrap-datetimepicker.min.css" />

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
				<li class="">Surat Pengantar</li>
				<li class="active">Surat Pengantar</li>
			  </ol>
			</section>         
        </section>
		
	    <!-- Main content -->
        <section class="content ">
		    <div class="row">
		        <div class="col-md-12">
					<div class="box box-primary">
						<div class="box-header with-border">
						  <h3 class="box-title">Aplikasi Pelayanan KARPEG dan KARIS/KARSU</h3>
						</div><!-- /.box-header -->
						<!-- form start -->
						<form class="form-horizontal" role="form" method="post" action="<?php echo site_url()?>/ap3k/savePengantar">
						  <div class="box-body">
							<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" style="display: none">
							<?php echo $pesan;?>
							<input type="hidden" name="kdPengantar" >
                            <input type="hidden" name="agendaMaleo" value="<?php echo set_value('agendaMaleo'); ?>">		
                            <input type="hidden" name="agendaUsulmaleo"  value="<?php echo set_value('agendaUsulmaleo'); ?>">							

							<div class="form-group row">							   						 
							    <label class=" control-label col-md-2">Nomor Pengantar</label>									
								<div class="col-md-4">
								    <select id="nomorPengantar" name="nomorPengantar" class="form-control floating lokasi">
                                            <option value="">--</option>
                                    </select>
									<span class="help-block text-red"><?php echo form_error('nomorPengantar');?></span>
								</div>	
								<label class=" control-label col-md-2">Tanggal Pengantar</label>									
								<div class="col-md-4">
									<div class='input-group date'>
										<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
										<input type="text" name="tanggalPengantar" class="form-control datetimepicker" placeholder="Tanggal Pengantar" value="<?php echo set_value('tanggalPengantar'); ?>">						
									</div>
									<span class="help-block text-red"><?php echo form_error('tanggalPengantar');?></span>

								</div>
							</div>
							<div class="form-group row">							   						 
							    <label class=" control-label col-md-2">Nomor Agenda</label>									
								<div class="col-md-4">
									<input type="text" name="nomorAgenda" class="form-control" placeholder="Nomor Agenda" value="<?php echo $nomorAgenda->nomor_agenda+1 ?>">						
									<span class="help-block text-red"><?php echo form_error('nomorAgenda');?></span>
								</div>	
								<label class=" control-label col-md-2">Tanggal Agenda</label>									
								<div class="col-md-4">
									<div class='input-group date'>
										<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
										<input type="text" name="tanggalAgenda" class="form-control datetimepicker" placeholder="Tanggal Agenda" value="<?php echo set_value('tanggalAgenda'); ?>">						
									</div>
									<span class="help-block text-red"><?php echo form_error('tanggalAgenda');?></span>
								</div>
							</div>
							<div class="form-group">
							  <label class="control-label col-md-2">Instansi</label>
							  <div class="col-md-10">
							    <select name="instansi" class="form-control select2">
                                    <option value=""></option>								
									<?php if($instansi->num_rows() > 0):?>
									<?php foreach($instansi->result() as $value):?>
									<option value="<?php echo $value->INS_KODINS?>" <?php echo  set_select('instansi', $value->INS_KODINS); ?>><?php echo $value->INS_NAMINS?></option>
									<?php endforeach;?>
									<?php endif;?>
								</select>
								<span class="help-block text-red"><?php echo form_error('instansi'); ?></span>
							  </div>	
							</div>
							<div class="form-group row">							   						 
							    <label class=" control-label col-md-2">Jenis Usul</label>									
								<div class="col-md-4">
									<select name="jenisUsul" class="form-control">
										<option value="">--silahkan Pilih--</option>
										<option value="1" <?php echo  set_select('jenisUsul', 1); ?>>KARPEG</option>
										<option value="2" <?php echo  set_select('jenisUsul', 2); ?>>KARIS</option>
										<option value="3" <?php echo  set_select('jenisUsul', 3); ?>>KARSU</option>
									</select>
									<span class="help-block text-red"><?php echo form_error('jenisUsul');?></span>
								</div>	
								<label class=" control-label col-md-2">Permintaan</label>									
								<div class="col-md-4">
									<input type="radio" value="Baru" name="permintaan" <?php echo  set_radio('permintaan', 'Baru'); ?>  checked />&nbsp;BARU
									<input type="radio" value="Hilang" name="permintaan" <?php echo  set_radio('permintaan', 'Hilang'); ?>  />&nbsp;HILANG 
									<input type="radio" value="Perbaikan" name="permintaan" <?php echo  set_radio('permintaan', 'Perbaikan'); ?>  />&nbsp;PERBAIKAN

									<span class="help-block text-red"><?php echo form_error('permintaan');?></span>
								</div>
							</div>
							
							<div class="box-footer">
								<button type="submit" class="btn btn-primary">&nbsp;Simpan Data</button>
							  </div>
							
						   </div>						   
						</form>
						
						<?php if($show):?>
						<div class="table-responsive">
						<table class="table table-striped">
						<thead>
							<tr class="bg-orange">
								<th>NO</th>
								<th>No.Pengantar</th>
								<th>No/Tgl Agenda</th>
								<th>Instansi</th>
								<th>Jenis</th>
								<th>Perintah</th>
							</tr>
						</thead>   
						<tbody>
							<?php $no=1; if($daftarPengantar->num_rows() > 0):?>
							<?php  foreach($daftarPengantar->result() as $value):?>							
							<tr>
								<td><?php echo $no;?></td>
								<td><?php echo $value->no_pengantar?></td>
								<td><?php echo $value->no_agenda.'/'.$value->tgl_agenda?></td>	
								<td><?php echo $value->instansi?></td>
								<td>
								<?php 
									switch($value->jenis_pengantar){
										case 1:
										   echo 'KARPEG';
										   break;
										case 2:
										   echo 'KARIS'; 
										   break;
										default:
										   echo 'KARSU'; 	
									}	
								?>
								</td>
								<td>
								<?php 
								echo'<a href="#edit" class=" btn btn-primary btn-xs" data-tooltip="tooltip"  title="Edit Pengantar" data-kdPengantar="'.$value->kd_pengantar.'" data-nomorPengantar="'.$value->no_pengantar.'" data-tanggalPengantar="'.$value->tgl_pengantar.'" data-nomorAgenda="'.$value->no_agenda.'" data-tanggalAgenda="'.$value->tgl_agenda.'" data-instansi="'.$value->kd_instansi.'" data-jenisUsul="'.$value->jenis_pengantar.'" data-permintaan="'.$value->permintaan.'" data-agendaMaleo="'.$value->agenda_maleo.'"><i class="fa fa-edit"></i></a>';
								echo'&nbsp;<a class="btn btn-danger btn-xs" data-tooltip="tooltip"  title="Hapus Pengantar" data-toggle="modal" data-target="#hapusModal" data-id="'.$value->kd_pengantar.'"><i class="fa fa-remove"></i></a>';
							    echo'&nbsp;<a href="'.site_url().'/ap3k/nominatif/?i='.$this->myencrypt->encode($value->agenda_maleo).'&k='.$this->myencrypt->encode($value->kd_pengantar).'" class=" btn btn-primary btn-xs" data-tooltip="tooltip"  title="Lihat Nominatif"><i class="fa fa-search"></i></a>';

								?>
								
								</td>	
							</tr>
							<?php $no++; endforeach;?>
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

	<div class="modal fade" id="hapusModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
					<h4 class="modal-title" id="myModalLabel"><span id="msg"></span></h4>
				</div>
				<div class="modal-body">
					<form id="nfrmHapus">
					  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name()?>" value="<?php echo $this->security->get_csrf_hash()?>" style="display: none">
					  <div class="form-group"> Yakin akan menghapus ?</div>
                       <input type="hidden" name="kdPengantar"/>				   					   
					</form>
				 </div>
				<div class="modal-footer">
					<button type="button" class="btn bg-maroon" id="nBtnHapus"><i class="fa fa-leaf"></i>&nbsp;OK Hapus !</button>
				</div>
			</div>
		</div>	
	</div>
	
	<script src="<?php echo base_url()?>assets/plugins/jQuery/jQuery-2.1.4.min.js"></script>    
    <script src="<?php echo base_url()?>assets/bootstrap/js/bootstrap.min.js"></script> 
    <script src="<?php echo base_url()?>assets/dist/js/app.min.js"></script>
    <script src="<?php echo base_url()?>assets/plugins/select2/select2.full.min.js"></script>
    <script src="<?php echo base_url()?>assets/plugins/daterange/moment-with-locales.js"></script>	
	<script src="<?php echo base_url()?>assets/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
	<script>	
	$(document).ready(function () {
	    
		$(".select2").select2({
			placeholder:'--Silahkan Pilih Instansi--',
			width: '100%'
		});	
		
		$("#nomorPengantar").select2({
			placeholder: 'Masukan Nomor Pengantar',
			width: '100%',
			minimumInputLength: 6,
			ajax: {
				url:  "<?php echo site_url()?>/ap3k/getPengantarByName",
				dataType:'json',
				type:'GET',
				cache: "true",
				delay: 250,
				processResults: function (data) {
					return {
						results: $.map(data, function(obj) {
							return { 
							id: obj.layanan_id, 
							text:  obj.agenda_nousul 
							};
						})
					};
				}                       
			} 
		});
		
		$("#nomorPengantar").on("select2:select",function(e){				    	
			$.ajax({
				url: "<?php echo site_url()?>/ap3k/getPengantarById/",
				dataType:'json',
				data:{q: this.value },
				type:'GET',
				success: function(r){  
					$('input[name=agendaMaleo]').val(r.agenda_id);
					$('input[name=agendaUsulmaleo]').val(r.agenda_nousul);
					$('input[name=tanggalPengantar]').val(r.agenda_tgl);	
                    $('[name=instansi]').val(r.agenda_ins).trigger("change");
                    switch(r.layanan_id){
					    case '11':
						   $('[name=jenisUsul]').val(1);
						break;
						case '9':
						   $('[name=jenisUsul]').val(2);
						break;						 
						default:
						   $('[name=jenisUsul]').val(3);
					}	
					
                    					
				}				
			});			
		});
		
		$('.datetimepicker').datetimepicker({
		   sideBySide: true,
		   locale: 'id',
		   format:'DD-MM-YYYY',
		});
		
		$('[data-tooltip="tooltip"]').tooltip();
		
		<?php if($this->input->post()):?>
		$("[name=nomorPengantar]").append("<option value='"+'<?php echo set_value('agendaMaleo')?>'+"' selected>"+'<?php echo set_value('agendaUsulmaleo')?>'+"</option>").trigger("change");	

		<?php endif;?>
		
		$('.table-responsive').on("click",'a[href="#edit"]',function(e){
			var nomorPengantar     =  $(this).attr('data-nomorPengantar'),
			    tanggalPengantar   =  $(this).attr('data-tanggalPengantar'),
				nomorAgenda		   =  $(this).attr('data-nomorAgenda'),
				tanggalAgenda	   =  $(this).attr('data-tanggalAgenda'),
				instansi		   =  $(this).attr('data-instansi'),
				jenisUsul		   =  $(this).attr('data-jenisUsul'),
				permintaan		   =  $(this).attr('data-permintaan'),
				kdPengantar		   =  $(this).attr('data-kdPengantar'),
				agendaMaleo		   =  $(this).attr('data-agendaMaleo');
				
            $("input[name=agendaMaleo]").val(agendaMaleo);
			$("input[name=agendaUsulmaleo]").val(nomorPengantar);
			$("[name=nomorPengantar]").append("<option value='"+agendaMaleo+"' selected>"+nomorPengantar+"</option>").trigger("change");	
			$("input[name=tanggalPengantar]").val(tanggalPengantar);
			$("[name=instansi]").val(instansi).trigger("change");
			$("input[name=nomorAgenda]").val(nomorAgenda);
			$("input[name=tanggalAgenda]").val(tanggalAgenda);
			$("[name=jenisUsul]").val(jenisUsul);
			$("input:radio[name=permintaan][value='"+permintaan+"']").prop("checked", true);	
			$("input[name=kdPengantar]").val(kdPengantar);
			
		}); 
		
		$('#hapusModal').on('show.bs.modal',function(e){
		     $('#hapusModal #msg').text('Konfirmasi Delete Pengantar')
			.removeClass( "text-green")
		    .removeClass( "text-blue" ); 
			
			var id		=  $(e.relatedTarget).attr('data-id');			
			$('#hapusModal input[name=kdPengantar]').val(id);
			
		});
		
		$("#nBtnHapus").on("click",function(e){
			e.preventDefault();
			var data = $('#nfrmHapus').serialize();
			
			$('#hapusModal #msg').text('Deleting Please Wait.....')
                     .removeClass( "text-green")
				     .addClass( "text-blue" );  
			
			$.ajax({
				type: "POST",
				url : "<?php echo site_url()?>/ap3k/hapusPengantar",
				data: data,
				success: function(){					
					$('#hapusModal #msg').text('Berhasil menghapus data....')
						.removeClass( "text-blue")
						.addClass( "text-green" );															 
			    }, // akhir fungsi sukses
				complete: function () {
					window.location.href = '<?php echo site_url()?>/ap3k/';
				}
		    });
			return false;
		});
	});	
   </script>
	</body>
</html>
