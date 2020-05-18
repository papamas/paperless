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
	
	#spinner-modal .modal-dialog,
    #spinner-modal .modal-content,
    #spinner-modal .modal-body {
        background: transparent;
        color: rgba(255,255,255,1);
        box-shadow: none;
        border: none;
    }
	</style>
  </head>
  <body class="skin-yellow">
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
				<li class="active">Verifikasi dan Distribusi Berkas</li>
			  </ol>
			</section>     
        </section>
		
	    <!-- Main content -->
        <section class="content ">
		    <div class="row">
		        <div class="col-md-12">
					<div class="box box-primary">
						<div class="box-header with-border">
						  <h3 class="box-title">Verifikasi dan Distribusi Berkas</h3>
						</div><!-- /.box-header -->
						<!-- form start -->
						<form name="frmVerifikasi" class="form-horizontal" role="form" method="post" action="<?php echo site_url()?>/distribusi/getBerkas">
						  <div class="box-body">
							<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" style="display: none">
							
							<div class="form-group">
							  <label class="control-label col-md-2 col-sm-2 col-xs-2">Instansi</label>
							  <div class="col-md-10 col-sm-10 col-xs-10">
							    <select name="instansi" class="form-control select2">
                                    <option value="">--Silahkan Pilih--</option>								
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
                            <div class="form-group row">							   						 
							    <label class=" control-label col-md-2 col-sm-2 col-xs-2">Golongan</label>									
								<div class="col-md-10 col-sm-10 col-xs-10">
									<select name="golongan" class="form-control">
										<option value="">--silahkan Pilih--</option>
										<?php if($golongan->num_rows() > 0):?>
									    <?php foreach($golongan->result() as $value):?>
										<option value="<?php echo $value->GOL_KODGOL?>" <?php echo  set_select('golongan', $value->GOL_KODGOL); ?> ><?php echo $value->GOL_PKTNAM.'-'.$value->GOL_GOLNAM;?></option>
										<?php endforeach;?>
										<?php endif;?>
									</select>
									<span class="help-block text-red"><?php echo form_error('golongan'); ?></span>
								</div>
															
							</div>

							<div class="form-group row">							   						 
							    <label class=" control-label col-md-2 col-sm-2 col-xs-2">No. Usul</label>									
								<div class="col-md-10 col-sm-10 col-xs-10">
                                     <input type="text" name="nousul" class="form-control" placeholder="Masukan No. Usul" value="<?php echo set_value('nousul'); ?>">								
									<span class="help-block text-red"><?php echo form_error('nousul'); ?></span>
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
						 <input type="hidden" name="<?php echo $this->security->get_csrf_token_name()?>" value="<?php echo $this->security->get_csrf_hash()?>" style="display: none">
						<div class="table-responsive">						   
							<table id="tb-layanan" class="table table-striped table-condensed">
							<thead>
							    
								<tr>
									<th></th>
									<th>NOUSUL</th>									
									<th>PELAYANAN</th>
									<th>INSTANSI</th>
									<th>NIP</th>
									<th>NAMA</th>
									<th>GOL</th>
									<th></th>
								</tr>
							</thead> 
                           							
							<tbody>
								<?php if($usul->num_rows() > 0):?>
								<?php  foreach($usul->result() as $value):?>
								<tr>
									<td style="width:65px;">
									<a href="#" class="btn bg-orange btn-flat btn-xs" data-tooltip="tooltip"  title="Lihat Berkas" data-toggle="modal" data-target="#lihatModal" data-id="<?php echo '?n='.$this->myencrypt->encode($value->nip).'&l='.$this->myencrypt->encode($value->layanan_nama)?>"><i class="fa fa-search"></i></a>
									<a href="#" class="btn btn-danger btn-flat btn-xs" data-tooltip="tooltip"  title="Kirim Teknis" data-toggle="modal" data-target="#kirimModal" data-nip="<?php echo $value->nip?>" data-agenda="<?php echo $value->agenda_id?>" ><i class="fa fa-mail-forward"></i></a>
                                     
									</td>
									<td><?php echo $value->agenda_nousul?></td>													
									<td><?php echo $value->layanan_nama?></td>
                                    <td><?php echo $value->instansi?></td>		
                                    <td><?php echo $value->nip?></td>
									<td><?php echo $value->nama?></td>
									<td><?php echo $value->golongan?></td>									
								    <td>
									   <input type="checkbox" value="<?php echo $value->nip?>" class="checkbox" name="nip[]" /> 
									   <input type="checkbox" value="<?php echo $value->agenda_id?>" class="checkbox" name="agenda[]"  style="opacity: 0.0; position: absolute; left: -9999px">
									   <input type="hidden" name="penerima"/>	
					  
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
						</form>	
						</div>
						<?php if($usul->num_rows() > 0):?>
						<div class="row">
							<div class="col-md-12">
								<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" style="display: none">
								<a href="#check" class="btn bg-olive btn-flat btn-sm" data-tooltip="tooltip"  title="Pilih Semua"><i class="fa fa-check-square-o"> Check</i></a>
								<a href="#uncheck" class="btn bg-maroon btn-flat btn-sm" data-tooltip="tooltip"  title="Batal Pilih Semua"><i class="fa fa-square-o"></i> UnCheck</a>
								<a href="#kirim" disabled="disabled" data-toggle="modal" data-target="" class="btn bg-orange btn-flat btn-sm" data-tooltip="tooltip"  title="Kirim semua ke Teknis"><i class="fa fa-mail-forward"></i>&nbsp;Kirim</a>
							</div>
						</div>	
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
	
	<div class="modal" id="lihatFileModal" tabindex="-1" role="dialog" aria-hidden="true">
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
					    <input type="hidden" name="agenda"/>					   
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
					<form id="nfrmKirimAll">
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
                       					   
					</form>
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
	
	<!--[ SPINNER MODAL ]-->
	<div class="modal fade" id="spinner-modal">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-body text-center">
				    <img src="<?php echo base_url()?>/assets/dist/img/loading.gif" alt="waiting..." />
					<h3><i class="fa fa-cog fa-spin"></i> Working...</h3>
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
	    $('[data-tooltip="tooltip"]').tooltip();
		
		$(".select2").select2({
			width: '100%'
		});			
		
		$('#lihatModal').on('show.bs.modal',function(e) {    		
			var id=  $(e.relatedTarget).attr('data-id');
			$.get('<?php echo site_url()?>/verifikasi/getKelengkapan/'+id, function(data){
				$('#lihatModal').find('.modal-content').html(data);
			})			
	    });
	
		$('#lihatFileModal').on('show.bs.modal',function(e) {    		 
			var id=  $(e.relatedTarget).attr('data-id');
			var iframe = $('#frame');
			iframe.attr('src', '<?php echo site_url()?>'+'/verifikasi/getInline/'+id);
					
	    });
		
		$('#kirimModal').on('show.bs.modal',function(e){
		     $('#kirimModal #msg').text('Konfirmasi Pengiriman Berkas')
			.removeClass( "text-green")
			.removeClass( "text-danger")
		    .removeClass( "text-blue" ); 
			
			var nip		=  $(e.relatedTarget).attr('data-nip');
			var agenda  =  $(e.relatedTarget).attr('data-agenda');
			
			$('#kirimModal input[name=nip]').val(nip);
			$('#kirimModal input[name=agenda]').val(agenda);
		});
		
		$("#nBtnKirim").on("click",function(e){
			e.preventDefault();
			var data = $('#nfrmKirim').serialize();
			
			$('#kirimModal #msg').text('Updating Please Wait.....')
                     .removeClass( "text-green")
				     .addClass( "text-blue" );  
			
			$.ajax({
				type: "POST",
				url : "<?php echo site_url()?>/distribusi/kirim",
				data: data,
				success: function(r){					
					$('#kirimModal #msg').text(r.pesan)
						.removeClass( "text-blue")
						.addClass( "text-green" );
					refreshTable();				
			    }, // akhir fungsi sukses
				error : function(r) {
					$('#kirimModal #msg').text(r.responseJSON.pesan)
						.removeClass( "text-blue")
						.removeClass( "text-green")
						.addClass( "text-danger" );
				},
                beforeSend: function () {
                   $('#spinner-modal').modal('show');	 
                },
                complete: function () {
                    $('#spinner-modal').modal('hide');
                }				
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
		
		$('#kirimAllModal [name=penerima]').on('change',function(e){
		    var penerima  = $(this).val();
			$('form[name=frmtableVerifikasi] [name=penerima]').val(penerima);
		});
		
		$("#nBtnKirimAll").on("click",function(e){
			e.preventDefault();
			var data = $('form[name=frmtableVerifikasi]').serialize();	
			
			$('#kirimAllModal #msg').text('Updating Please Wait.....')
                     .removeClass( "text-green")
				     .addClass( "text-blue" );  
			
			$.ajax({
				type: "POST",
				url : "<?php echo site_url()?>/distribusi/kirimAll",
				data: data,
				success: function(r){					
					$('#kirimAllModal #msg').text(r.pesan)
						.removeClass( "text-blue")
						.addClass( "text-green" );
					refreshTable();	
					
                    $('a[href="#kirim').attr("data-target", "#");
		            $('a[href="#kirim').attr("disabled", "disabled");	
					
			    }, // akhir fungsi sukses
				error : function(r) {
					$('#kirimAllModal #msg').text(r.responseJSON.pesan)
						.removeClass( "text-blue")
						.removeClass( "text-green")
						.addClass( "text-danger" );
				},
				beforeSend: function () {
                   $('#spinner-modal').modal('show');	 
                },
                complete: function () {
                    $('#spinner-modal').modal('hide');
                }	
		    });
			return false;
		});
		
		
		function refreshTable(){						
			$.ajax({   
			    type: 'POST',   
			    url: '<?php echo site_url()?>/distribusi/getVerifikasi',   
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
