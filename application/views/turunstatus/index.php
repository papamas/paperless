<!DOCTYPE html>
<html>
 <head>
    <?php  $this->load->view('vheader');?>
	<link rel="stylesheet" href="<?php echo base_url()?>assets/dist/css/tree.css">   
	<link rel="stylesheet" href="<?php echo base_url()?>assets/dist/css/custom.css"> 
	<link rel="stylesheet" href="<?php echo base_url()?>assets/plugins/select2/select2.css">

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
				<li class="">USUL</li>
				<li class="active">Turun Status</li>
			  </ol>
			</section>         
        </section>
		
	    <!-- Main content -->
        <section class="content ">
		    <div class="row">
		        <div class="col-md-12">
					<div class="box box-primary">
						<div class="box-header with-border">
						  <h3 class="box-title">TURUN STATUS USUL INSTANSI PUSAT DAN DAERAH</h3>
						</div><!-- /.box-header -->
						<!-- form start -->
						<form name="frmDaftar" class="form-horizontal" role="form" method="post" action="<?php echo site_url()?>/turunstatus/getUsul">
						  <div class="box-body">
							<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" style="display: none">
							
							<div class="form-group row">							   						 
							    <label class=" control-label col-md-2">Filter</label>									
								<div class="col-md-4">
									<select name="searchby" class="form-control">
										<option value="">--silahkan Pilih--</option>
										<option value="1" <?php echo  set_select('searchby', '1'); ?> >NIP</option>	
										<option value="2" <?php echo  set_select('searchby', '2'); ?> >NOMOR USUL</option>
									</select>
									<span class="help-block text-red"><?php echo form_error('searchby'); ?></span>
								</div>
								<div class="col-md-6">									
								    <input type="text" name="search" class="form-control" placeholder="Masukan data pencarian" value="<?php echo set_value('search'); ?>">
									<span class="help-block text-red"><?php echo form_error('search'); ?></span>
								</div>								
							</div>
							<div class="form-group row">
							  	<label class="control-label col-md-2">Perintah</label>
								<div class="col-md-10">
									<input type="radio" required value="1" name="perintah"  checked />&nbsp;Tampil																	
								</div>	
							</div> 	
							
							<div class="box-footer">
								<button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;Cari</button>
							  </div>
							
						   </div>						   
						</form>
						<hr/>
						<?php if($show):?>
						<div class="table-responsive">
						<table id="tbDaftar" class="table table-striped">
						<thead>
							<tr>
								<th>NO</th>
								<th>#</th>
								<th>STATUS</th>
								<th>TAHAPAN</th>
								<th>BY DATE</th>
								<th>USUL</th>
								<th>INSTANSI</th>
								<th>NIP</th>
								<th>NAMA</th>
							</tr>
						</thead>   
						<tbody>
							<?php $no=1; if($daftar->num_rows() > 0):?>
							<?php  foreach($daftar->result() as $value):?>							
							<tr>
								<td><?php echo $no; ?></td>
								<td><a class="btn btn-danger btn-xs" data-tooltip="tooltip"  title="Turun Status" data-toggle="modal" data-target="#turunModal" data-agenda="<?php echo $value->agenda_id?>" data-nip="<?php echo $value->nip?>" data-alasan="<?php echo $value->nomi_alasan?>" data-status="<?php echo $value->nomi_status?>" data-tahapan="<?php echo $value->tahapan_id?>"><i class="fa fa-long-arrow-down"></i></a></td> 
								<td><?php echo $value->nomi_status?></td>
								<td><?php echo $value->tahapan_nama?></td>
								<td><?php echo $value->verify_date.'<br/>'.$value->first_name?></td>
								<td><?php echo $value->agenda_nousul?></td>
								<td><?php echo $value->nama_instansi?></td>	
								<td><?php echo $value->nip?></td>
								<td><?php echo $value->nama_pns?></td>								
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

	<div class="modal fade" id="turunModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
						<h4 class="modal-title" id="myModalLabel"><span id="msg"></span></h4>
					</div>
					<div class="modal-body">
						<form id="nfrmTurun">
						   <input type="hidden" name="<?php echo $this->security->get_csrf_token_name()?>" value="<?php echo $this->security->get_csrf_hash()?>" style="display: none">
							<div class="form-group">
								<label for="status">Status</label>
									<select name="status" class="form-control" required>
									<option value="">--</option>
									<option value="BELUM">BELUM</option>
									<option value="ACC">ACC</option>
									<option value="BTL">BTL</option>
									<option value="TMS">TMS</option>
								</select>
							</div>	
							<div class="form-group">
								<label for="tahapan">Tahapan</label>
									<select name="tahapan" class="form-control" required>
									<option value="">--</option>
									<?php foreach($tahapan->result() as $value):?>
									<option value="<?php echo $value->tahapan_id?>"><?php echo $value->tahapan_id.' - '.$value->tahapan_nama?></option>
									<?php endforeach;?>	
								</select>
							</div>	
							<div class="form-group">
								<label>Catatan</label>
								<textarea name="nomiAlasan" class="form-control"></textarea>
							</div>
							<div class="form-group">
								<input type="checkbox" value="1" name="all">&nbsp;Ya, saya Yakin
								<label class="form-check-label text-red">
									 PERINGATAN! jika anda Klik disini maka akan merubah status untuk seluruhnya dan proses ini tidak bisa dikembalikan akan menjadi permanen								
								</label>	
							</div>	
                            <input class="form-control" type="hidden" value="" name="agendaId" />	
							<input class="form-control" type="hidden" value="" name="nip" />
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn bg-maroon" id="nBtn">OK Kaseh Ubah Jo.. !</button>
					</div>
				</div>
			</div>	
		</div>
	
	
	<script src="<?php echo base_url()?>assets/plugins/jQuery/jQuery-2.1.4.min.js"></script>    
    <script src="<?php echo base_url()?>assets/bootstrap/js/bootstrap.min.js"></script> 
    <script src="<?php echo base_url()?>assets/dist/js/app.min.js"></script>
    <script src="<?php echo base_url()?>assets/plugins/select2/select2.full.min.js"></script>

	<script>	
	$(document).ready(function () {
	    
		$(".select2").select2({
			width: '100%'
		});	
		
		$('[data-tooltip="tooltip"]').tooltip();
		
		$('#turunModal').on('show.bs.modal',function(e){
		    $('#turunModal #msg').text('Konfimasi Turun Status') 
			.removeClass( "text-green")
		    .removeClass( "text-blue" ); 
			
			var agenda  	= $(e.relatedTarget).attr('data-agenda'),
				nip			= $(e.relatedTarget).attr('data-nip'),
				alasan		= $(e.relatedTarget).attr('data-alasan'),
				status		= $(e.relatedTarget).attr('data-status'),
				tahapan		= $(e.relatedTarget).attr('data-tahapan');
				
			$('#turunModal input[name=agendaId]').val(agenda);
			$('#turunModal input[name=nip]').val(nip);	
			$('#turunModal [name=nomiAlasan]').val(alasan);	
			$('#turunModal [name=status]').val(status);
			$('#turunModal [name=tahapan]').val(tahapan);
			
		});
		
		$("#nBtn").on("click",function(e){
			e.preventDefault();
			var data = $('#nfrmTurun').serialize();
			
			$('#turunModal #msg').text('Updating Please Wait.....')
					 .removeClass( "text-green")
					 .addClass( "text-blue" );  
			
			$.ajax({
				type: "POST",
				url : "<?php echo site_url()?>/turunstatus/okTurun",
				data: data,
				success: function(r){
					$('#turunModal #msg').text(r.pesan)
						.removeClass( "text-blue")
						.addClass( "text-green");
					refreshTable();						
				}, // akhir fungsi sukses
				error : function(r) {		
					 $('#turunModal #msg').text(r.responseJSON.pesan)
					 .removeClass( "text-green")
					 .removeClass( "text-blue")
					 .addClass( "text-red" ); 
				}
			});
			return false;
		});
		
		function refreshTable(){						
			$.ajax({   
			    type: 'POST',   
			    url: '<?php echo site_url()?>/turunstatus/getTurunstatus',   
			    data: $('form[name=frmDaftar]').serialize(),
			    success: function(res) {
					$("#tbDaftar").html(res);					
				},
			});
		}
		
	});	
   </script>
	</body>
</html>
