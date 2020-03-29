<!DOCTYPE html>
<html>
 <head>
    <?php echo $this->load->view('vheader');?>
	<link rel="stylesheet" href="<?php echo base_url()?>assets/dist/css/tree.css">   
	<link rel="stylesheet" href="<?php echo base_url()?>assets/dist/css/custom.css">  
    <link rel="stylesheet" href="<?php echo base_url()?>assets/plugins/select2/select2.css">	
  </head> 	
	<style>
    /*Bootstrap modal size iframe*/
	@media (max-width: 1280px){
		.md-dialog  {
			height:630px;
			width:800px;
		}
		.md-body {
			height: 500px;	
		}
	}
	@media screen and (min-width:1281px) and (max-width:1600px){
		.md-dialog  {
			height:700px;
			width:1000px;
		}
		.md-body {
			height: 550px;	
		}
	}
	@media screen and (min-width:1601px) and (max-width:1920px){
		.md-dialog  {
			height:830px;
			width:1200px;
		}
		.md-body {
			height: 700px;	
		}
	}

	
	.md-content {
		/* Bootstrap sets the size of the modal in the modal-dialog class, we need to inherit it */
		width:inherit;
		height:inherit;
		/* To center horizontally */
		margin: 0 auto;
		pointer-events: all;
	}
	
	
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
				<li class="">Verifikasi</li>
				<li class="active">Verifikasi Berkas</li>
			  </ol>
			</section>     
        </section>
		
	    <!-- Main content -->
        <section class="content ">
		    <div class="row">
		        <div class="col-md-12">
					<div class="box box-primary">
						<div class="box-header with-border">
						  <h3 class="box-title">Verifikasi Kelengkapan Berkas</h3>
						</div><!-- /.box-header -->
						<!-- form start -->
						<form name="frmVerifikasi" class="form-horizontal" role="form" method="post" action="<?php echo site_url()?>/verifikasi/getBerkas">
						  <div class="box-body">
							<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" style="display: none">
							
							<div class="form-group">
							  <label class="control-label col-md-2 col-sm-2 col-xs-2">Instansi</label>
							  <div class="col-md-10 col-sm-10 col-xs-10">
							    <select name="instansi" class="form-control select2">
                                    <option value="">--Silahkan Pilih--</option>
                                    <option value="9" <?php echo  set_select('instansi',9); ?>>TASPEN</option>									
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
							
							<div class="box-footer">
							    <div class="row">
									<div class="col-md-8">
										<button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;Cari</button>
									</div>									
								</div>
							</div>
							
						   </div>						   
						</form>
						
						
						<?php if($show):?>	
                       	<form name="frmtableVerifikasi">
						<div class="table-responsive">
							<table id="tb-layanan" class="table table-striped table-condensed">
							<thead>
								<tr>
									<th></th>
									<th>NOUSUL</th>									
									<th>PELAYANAN</th>
									<th>INSTANSI</th>
									<th>NIP</th>
									<th>NAMA PNS</th>
									<th>NAMA</th>
									<th></th>
								</tr>
							</thead> 
                           							
							<tbody>
								<?php if($usul->num_rows() > 0):?>
								<?php  foreach($usul->result() as $value):?>
								<tr>
									<td style="width:65px;">
									<a href="#" class="btn bg-orange btn-flat btn-xs" data-tooltip="tooltip"  title="Lihat Berkas" data-toggle="modal" data-target="#lihatModal" data-id="<?php echo '?n='.$this->myencrypt->encode($value->nip).'&l='.$this->myencrypt->encode($value->layanan_nama)?>"><i class="fa fa-search"></i></a>
									<a href="#" class="btn btn-danger btn-flat btn-xs" data-tooltip="tooltip"  title="Kirim Teknis" data-toggle="modal" data-target="#kirimModal" data-nip="<?php echo $value->nip?>" data-usul="<?php echo $value->usul_id?>" ><i class="fa fa-mail-forward"></i></a>
                                     
									</td>
									<td><?php echo $value->nomor_usul?></td>													
									<td><?php echo $value->layanan_nama?></td>
                                    <td>TASPEN</td>		
                                    <td><?php echo $value->nip?></td>
									<td><?php echo $value->nama_pns?></td>	
									<td><?php echo $value->nama_janda_duda?></td>
								    <td>
									   <input type="checkbox" value="<?php echo $value->nip?>" class="checkbox" name="nip[]" /> 
									   <input type="checkbox" value="<?php echo $value->usul_id?>" class="checkbox" name="usul_id[]"  style="opacity: 0.0; position: absolute; left: -9999px">
									   <input type="hidden" value="" name="penerima" />
									</td>
							    </tr>
								<?php endforeach;?>
								<?php endif;?>
								<tr><td colspan="7" class="full-right">
								    <label class="form-label">Jumlah Berkas : <?php echo $usul->num_rows();?></label>
									</td>
								</tr>							
							</tbody>
							</table>
							
						</div>
						 <?php if($usul->num_rows() > 0):?>
						<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" style="display: none">
						<a href="#check" class="btn bg-olive btn-flat btn-sm" data-tooltip="tooltip"  title="Pilih Semua"><i class="fa fa-check-square-o"> Check</i></a>
						<a href="#uncheck" class="btn bg-maroon btn-flat btn-sm" data-tooltip="tooltip"  title="Batal Pilih Semua"><i class="fa fa-square-o"></i> UnCheck</a>
						<a href="#kirim" disabled="disabled" data-toggle="modal" data-target="" class="btn bg-orange btn-flat btn-sm" data-tooltip="tooltip"  title="Kirim semua ke Teknis"><i class="fa fa-mail-forward"></i>&nbsp;Kirim</a>
                        <?php endif;?>
						</form>
						    
						
						<?php endif;?>
					</div>
                </div>
            </div> 	
			
        </section><!-- /.content -->		
      </div><!-- /.content-wrapper -->     
    </div><!-- ./wrapper -->
	
	<!-- Modal -->
	<div class="modal fade" id="lihatModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog modal-md" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
					<h4 class="modal-title" id="">Infomasi Kelengkapan Berkas</h4>
				</div>
							
			</div>
		</div>	
    </div>
	
	<div class="modal" id="showFile" tabindex="-1" role="dialog" aria-hidden="true">
	    <div class="modal-dialog  md-dialog modal-lg">
		  <div class="modal-content md-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" >File Dokumen</h4>
				</div>	
				<div class="modal-body md-body">
					<iframe  id="frame" width="100%" height="100%" frameborder="0" ></iframe>					
				</div>
		  </div>
		</div>
	</div>	
	
	<div class="modal fade" id="kirimModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
					<h4 class="modal-title" id="myModalLabel"><span id="msg"></span></h4>
				</div>
				<div class="modal-body">
					<form id="nfrmKirim">
					  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name()?>" value="<?php echo $this->security->get_csrf_hash()?>" style="display: none">
						<div class="form-group row">							   						 
							<label class=" control-label col-md-2 col-sm-2 col-xs-2">Penerima</label>									
							<div class="col-md-10 col-sm-10 col-xs-10">
								<select name="penerima" class="form-control" required>
									<option value="">--silahkan Pilih--</option>
									<?php if($penerima->num_rows() > 0):?>
									<?php foreach($penerima->result() as $value):?>
									<option value="<?php echo $value->user_id?>" <?php echo  set_select('penerima', $value->user_id); ?> ><?php echo $value->first_name.' '.$value->last_name;?></option>
									<?php endforeach;?>
									<?php endif;?>
								</select>
								<span class="help-block text-red"><?php echo form_error('penerima'); ?></span>
							</div>														
						</div>
                       <input type="hidden" name="nip"/>	
					   <input type="hidden" name="usul_id"/>					   
					</form>
				 </div>
				<div class="modal-footer">
					<button type="button" class="btn bg-maroon" id="nBtnKirim">OK Kirim !</button>
				</div>
			</div>
		</div>	
	</div>
	
	<div class="modal fade" id="kirimAllModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
					<h4 class="modal-title" id="myModalLabel"><span id="msg"></span></h4>
				</div>
				<div class="modal-body">
					<div class="form-group row">							   						 
						<label class=" control-label col-md-2 col-sm-2 col-xs-2">Penerima</label>									
						<div class="col-md-10 col-sm-10 col-xs-10">
							<select name="penerima" id="penerima" class="form-control" required>
								<option value="">--silahkan Pilih--</option>
								<?php if($penerima->num_rows() > 0):?>
								<?php foreach($penerima->result() as $value):?>
								<option value="<?php echo $value->user_id?>" <?php echo  set_select('penerima', $value->user_id); ?> ><?php echo $value->first_name.' '.$value->last_name;?></option>
								<?php endforeach;?>
								<?php endif;?>
							</select>
							<span class="help-block text-red"><?php echo form_error('penerima'); ?></span>
						</div>														
					</div>				
				 </div>
				<div class="modal-footer">
					<button type="button" class="btn bg-maroon" id="nBtnKirimAll">OK Kirim !</button>
				</div>
			</div>
		</div>	
	</div>
	
	<div class="modal fade" id="infoKirim" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
					<h4 class="modal-title" id="myModalLabel"><span id="msg"></span></h4>
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
		
		// hide empty column
		var columns = $("#tb-layanan > tbody > tr:first > td").length;
		for (var i = 0; i < columns; i++) {
			if ($("#tb-layanan > tbody > tr > td:nth-child(" + i + ")").filter(function() {
			  return $(this).text() != '';
			}).length == 0) {
			  $("#tb-layanan > tbody > tr > td:nth-child(" + i + "), #tb-layanan > thead > tr > th:nth-child(" + i + ")").hide();
			}
		}
		
	    $('[data-tooltip="tooltip"]').tooltip();
		
		$(".select2").select2({
			width: '100%'
		});			
		
		$('#lihatModal').on('show.bs.modal',function(e) {    		
			var id=  $(e.relatedTarget).attr('data-id');
			$.get('<?php echo site_url()?>/verifikasi/getKelengkapanTaspen/'+id, function(data){
				$('#lihatModal').find('.modal-content').html(data);
			})			
	    });
	
		$('#showFile').on('show.bs.modal',function(e) {    		 
			var id=  $(e.relatedTarget).attr('data-id');
			var iframe = $('#frame');
			iframe.attr('src', '<?php echo site_url()?>'+'/verifikasi/getInlineTaspen/'+id);
					
	    });
		
		$('#kirimModal').on('show.bs.modal',function(e){
		     $('#kirimModal #msg').text('Konfirmasi Pengiriman Berkas')
			.removeClass( "text-green")
		    .removeClass( "text-blue" ); 
			
			var nip		=  $(e.relatedTarget).attr('data-nip');
			    usul    =  $(e.relatedTarget).attr('data-usul');
			
			$('#kirimModal input[name=nip]').val(nip);
			$('#kirimModal input[name=usul_id]').val(usul);
		});
		
		$("#nBtnKirim").on("click",function(e){
			e.preventDefault();
			var data = $('#nfrmKirim').serialize();
			
			$('#kirimModal #msg').text('Updating Please Wait.....')
                     .removeClass( "text-green")
				     .addClass( "text-blue" );  
			
			$.ajax({
				type: "POST",
				url : "<?php echo site_url()?>/verifikasi/kirimTaspen",
				data: data,
				success: function(){					
					$('#kirimModal #msg').text('Berkas sudah dikirim ke Teknis....')
						.removeClass( "text-blue")
						.addClass( "text-green" );
					refreshTable();											 
			    }, // akhir fungsi sukses
		    });
			return false;
		});
		
		$('a[href="#check"]').on('click',function(){            
		    $('.checkbox').prop('checked',true);
		    $('a[href="#kirim').attr("data-target", "#kirimAllModal");	
            $('a[href="#kirim').removeAttr("disabled");		   
		});
		
		$('a[href="#uncheck"]').on('click',function(){          
		   $('.checkbox').prop('checked',false);
		   $('a[href="#kirim').attr("data-target", "#");
		   $('a[href="#kirim').attr("disabled", "disabled");
		});
		
		$('#kirimAllModal').on('show.bs.modal',function(e){
		     $('#kirimAllModal #msg').text('Konfirmasi Pengiriman Berkas')
			.removeClass( "text-green")
			.removeClass( "text-danger")
		    .removeClass( "text-blue" ); 
			
			
			
		});
		
		$("#nBtnKirimAll").on("click",function(e){
			e.preventDefault();
			var data = $('form[name=frmtableVerifikasi]').serialize();	
            $.ajax({
				type: "POST",
				url : "<?php echo site_url()?>/verifikasi/kirimAllTaspen",
				data: data,
				success: function(res){				
					if(res.nip != null){						
						$('#kirimAllModal #msg').text('Semua Berkas sudah dikirim ke Teknis....')
								 .removeClass( "text-blue")
								 .addClass( "text-green" );
						refreshTable();
                    }
                    $('a[href="#kirim').attr("data-target", "#");
		            $('a[href="#kirim').attr("disabled", "disabled");					
			    }, // akhir fungsi sukses
		    });
			return false;
			
		});
		
		$("#penerima").on("change",function(e){
		    var penerima  = $(this).val();
            $('form[name=frmtableVerifikasi] input[name=penerima]').val(penerima);
		});	
		
		function refreshTable(){
						
			$.ajax({   
			    type: 'POST',   
			    url: '<?php echo site_url()?>/verifikasi/getVerifikasiTaspen',   
			    data: $('form[name=frmVerifikasi]').serialize(),
			    success: function(res) {
					$("#tb-layanan").html(res);
				},
			});
		}
		
		
		
	});
    </script>
	</body>
</html>
