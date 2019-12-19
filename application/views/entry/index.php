<!DOCTYPE html>
<html>
 <head>
    <?php echo $this->load->view('vheader');?>
	<link rel="stylesheet" href="<?php echo base_url()?>assets/plugins/select2/select2.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/plugins/datepicker/bootstrap-datetimepicker.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/plugins/daterange/daterangepicker-bs3.css" />

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
            <li class="">Entry</li>
			<li class="active">Berkas</li>
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
						  <h3 class="box-title">Entry Berkas</h3>
						</div><!-- /.box-header -->
						<!-- form start -->
						<form class="form-horizontal" method="post" action="<?php echo site_url()?>/entry/getEntry" role="form">
						 <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" style="display: none">
						  <div class="box-body">
							<div class="form-group">
							  <label class="col-sm-2 col-md-2 col-xs-2">Instansi</label>
							  <div class="col-sm-10 col-md-10 col-xs-10">
							    <select name="instansi" class="form-control select2">
									<option value="">--</option>
									<?php if($instansi->num_rows() > 0):?>
									<?php foreach($instansi->result() as $value):?>
									<option value="<?php echo $value->INS_KODINS?>"  <?php echo  set_select('instansi', $value->INS_KODINS); ?>><?php echo $value->INS_NAMINS?></option>
									<?php endforeach;?>
									<?php endif;?>
								</select>
								<span class="help-block text-red"><?php echo form_error('instansi'); ?></span>
							  </div>	
							</div>
							<div class="form-group">
							  <label class="col-sm-2 col-md-2 col-xs-2">Pelayanan</label>
							  <div class="col-sm-10 col-md-10 col-xs-10">
							    <select name="layanan" class="form-control select2">
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
							<div class="form-group row">
								<label class="col-sm-2 col-md-2 col-xs-2 control-label">Periode ACC</label>
								<div class="col-sm-5 col-md-5 col-xs-5 controls">
								  <div class="input-group">
									<span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
									<input type="text"  style="" name="reportrange" id="reportrange" class="form-control" value="<?php echo date("d/m/Y", strtotime( "-1 month" )).' - '.date( "d/m/Y")?>"/>  
								  </div>
								  <span class="help-block text-red"><?php echo form_error('reportrange'); ?></span>
								</div>
                                <div class="col-md-5 col-sm-5 col-xs-5">									
								    <input type="number" name="nip" class="form-control" placeholder="Masukan NIP" value="<?php echo set_value('nip'); ?>">									
								</div>									
							</div>
							<div class="form-group row">
							    <label class="control-label col-md-2 col-sm-2 col-xs-2">Status:</label>
								<div class="col-md-4 col-sm-10 col-xs-10">
									<input type="radio" value="1" name="status"  <?php echo  set_radio('status', 1);?>  />&nbsp;Sudah Entry
									<input type="radio" value="2" name="status"  <?php echo  set_radio('status', 2);?> />&nbsp;Belum Entry
									<input type="radio" value="3" name="status"  <?php echo  set_radio('status', 3);?>  checked />&nbsp;Semua
									<span class="help-block text-red"><?php echo form_error('status'); ?></span>
								</div>
								<label class="control-label col-md-2 col-sm-2 col-xs-2">Perintah:</label>
								<div class="col-md-4 col-sm-10 col-xs-10">
									<input type="radio" value="1" name="perintah"  <?php echo  set_radio('perintah', 1);?> checked />&nbsp;Tampil
									<input type="radio" value="2" name="perintah"  <?php echo  set_radio('perintah', 2);?>/>&nbsp;Download									
									<span class="help-block text-red"><?php echo form_error('perintah'); ?></span>
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
						<table class="table table-striped table-condensed">
						<thead>
							<tr>
								<th></th>
								<th>NOMOR</th>
								<th>NIP</th>
								<th>NAMA</th>								
								<th>PELAYANAN</th>                               						
								<th>ACC DATE</th>
								<th>ENTRY DATE</th>
								<th>PERSETUJUAN</th>							
							</tr>
						</thead>   
						<tbody>
							<?php if($usul->num_rows() > 0):?>
							<?php  foreach($usul->result() as $value):?>
							<tr>
								<td>
								<?php 
								    $layanan = $value->layanan_id;
									if(IS_NULL($value->entry_by))
									{  
										echo '<button class="btn btn-primary btn-xs" data-tooltip="tooltip"  title="Input Persetujuan" data-toggle="modal" data-target="#skModal" data-agenda="'.$this->myencrypt->encode($value->agenda_id).'" data-nip="'.$this->myencrypt->encode($value->nip).'"><i class="fa fa-edit"></i></button>';
									    if($layanan === "10" || $layanan === "11" || $layanan === "12")
										{  
											echo '&nbsp;<a href="#dPhoto" class="btn btn-danger btn-xs" data-tooltip="tooltip"  title="Unduh Photo" id="?id='.$this->myencrypt->encode($value->id_instansi).'&f='.$this->myencrypt->encode($value->orig_name).'&n='.$this->myencrypt->encode($value->nip).'"><i class="fa fa-search"></i></a>';
										}
										
									}
																	
								?>
								</td>
								<td><?php echo $value->agenda_nousul;?></td>
								<td><?php echo $value->nip?></td>
								<td><?php echo $value->nama?></td>																					
								<td><?php echo $value->layanan_nama?></td>
								<td><span class="badge bg-green"><?php echo $value->verify_date?></span></td>
								<td><?php echo $value->entry_date?></td>
								<td><?php echo $value->nomi_persetujuan?><br/><?php echo $value->tgl?></td>								
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
	
	 <div class="modal fade" id="skModal" tabindex="-1" role="dialog" aria-hidden="true">
	    <div class="modal-dialog modal-sm">
		    <div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" ><span id="msg"></span></h4>
				</div>	
				
				<div class="modal-body">
					<form id="nfrmPersetujuan">
					   <div class="form-group">
						<label for="status">Nomor Persetujuan</label>
						<input class="form-control" type="text" value="" name="persetujuan" />
						<input class="form-control" type="hidden" value="" name="nip" />
						<input class="form-control" type="hidden" value="" name="agenda" />
						<input class="form-control" type="hidden" name="<?php echo $this->security->get_csrf_token_name()?>" value="<?php echo $this->security->get_csrf_hash()?>">
					
					  </div>
					  <div class="form-group">
						<label for="status">Tanggal Persetujuan</label>
						<div class='input-group date' id='datetimepicker'>
						    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
							<input pattern="^\d{1,2}\-\d{1,2}\-\d{4}$" type='text' required name="tanggal" value="<?php echo date('d-m-Y')?>" class="form-control" />
															
						</div>											
					  </div>
					</form>
				</div>
				<div class="modal-footer">
				   <button type="button" class="btn btn-default" id="nBtnPersetujuan">Simpan</button>
				</div>
		    </div>
		</div>
	</div>	
	
	
	<script src="<?php echo base_url()?>assets/plugins/jQuery/jQuery-2.1.4.min.js"></script>    
    <script src="<?php echo base_url()?>assets/bootstrap/js/bootstrap.min.js"></script> 
    <script src="<?php echo base_url()?>assets/dist/js/app.min.js"></script>
	<script src="<?php echo base_url()?>assets/plugins/daterange/moment-with-locales.js"></script>	
	<script src="<?php echo base_url()?>assets/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
	<script src="<?php echo base_url()?>assets/plugins/select2/select2.full.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url()?>assets/plugins/daterange/daterangepicker.js"></script>

	<script>	
	$(document).ready(function () {
		$('[data-tooltip="tooltip"]').tooltip();
		
	    $(".select2").select2({
			width: '100%'
		});			
		
		$('#reportrange').daterangepicker({
			   format: 'DD/MM/YYYY',
			   minDate: '01/04/2015',
			   startDate : moment().subtract(1, 'months'),
			   locale: 'id',
	    });	
		
		$('#datetimepicker').datetimepicker({
		   sideBySide: true,
		   locale: 'id',
		   format:'DD-MM-YYYY',
		});
		
		$('#skModal').on('show.bs.modal',function(e){
		    var agenda=  $(e.relatedTarget).attr('data-agenda');
			var nip   =  $(e.relatedTarget).attr('data-nip');
			
			$('#skModal #msg').text('Input Persetujuan')
                     .removeClass( "text-green")
					 .removeClass( "text-red")
				     .removeClass( "text-blue" ); 
			$("input[name=persetujuan]").val('');
			$("input[name=tanggal]").val('');
			$("input[name=agenda]").val(agenda);
			$("input[name=nip]").val(nip);
			
			$.ajax({
				type: "GET",
				url : "<?php echo site_url()?>/entry/simpanTahapan",
				data: {agenda:agenda,nip:nip},
				dataType:'json',
				success: function(e){
												 
				},
			});	
		});
		
		$("#nBtnPersetujuan").on("click",function(e){
			e.preventDefault();			
			var data = $('#nfrmPersetujuan').serialize();
					
			$('#skModal #msg').text('Saving Please Wait.....')
                     .removeClass( "text-green")
				     .addClass( "text-blue" );  
			
			$.ajax({
				type: "POST",
				url : "<?php echo site_url()?>/entry/simpan",
				data: data,
				dataType:'json',
				success: function(e){
					$('#skModal #msg').text(e.pesan)
                             .removeClass( "text-blue")
							 .removeClass( "text-red")
				             .addClass( "text-green" ); 
							 
				}, 
				error : function(e){
					$('#skModal #msg').text(e.responseJSON.pesan)
                             .removeClass( "text-blue")							 
							 .removeClass( "text-green")
				             .addClass( "text-red" ); 
				}	
		    });
			return false;
		});
		
		$('a[href="#dPhoto"]').click(function(){
			var id= this.id;
		    document.location = "<?php echo site_url()?>/photo/getPhoto/"+id;
		}); 
	});
</script>
	</body>
</html>
