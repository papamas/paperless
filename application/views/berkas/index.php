<!DOCTYPE html>
<html>
 <head>
    <?php echo $this->load->view('vheader');?>
	<link rel="stylesheet" href="<?php echo base_url()?>assets/dist/css/tree.css">   
	<link rel="stylesheet" href="<?php echo base_url()?>assets/dist/css/custom.css">   
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
				<li class="">Instansi</li>
				<li class="active">Lacak Berkas Usul</li>
			  </ol>
			</section>     
        </section>
		
	    <!-- Main content -->
        <section class="content ">
		    <div class="row">
		        <div class="col-md-12">
					<div class="box box-primary">
						<div class="box-header with-border">
						  <h3 class="box-title">Lacak Berkas Usul</h3>
						</div><!-- /.box-header -->
						<!-- form start -->
						<form name="frmEntry" class="form-horizontal" role="form" method="post" action="<?php echo site_url()?>/berkas/getBerkas">
						  <div class="box-body">
							<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" style="display: none">
							
							<div class="form-group">
							  <label class="control-label col-md-2 col-sm-2 col-xs-2">Instansi</label>
							  <div class="col-md-10 col-sm-10 col-xs-10">
							    <select name="instansi" class="form-control select2">									
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
							    <label class=" control-label col-md-2 col-sm-2 col-xs-2">Filter</label>									
								<div class="col-md-4 col-sm-4 col-xs-4">
									<select name="searchby" class="form-control">
										<option value="">--silahkan Pilih--</option>
										<option value="1" <?php echo  set_select('searchby', '1'); ?>>NIP</option>
										<option value="3" <?php echo  set_select('searchby', '3'); ?>>NOMOR USUL</option>
										<option value="4" <?php echo  set_select('searchby', '4'); ?>>PELAYANAN</option>
									</select>
									<span class="help-block text-red"><?php echo form_error('searchby'); ?></span>
								</div>
								<div class="col-md-6 col-sm-6 col-xs-6">									
								    <input type="text" name="search" class="form-control" placeholder="Masukan data pencarian" value="<?php echo set_value('search'); ?>">
									<span class="help-block text-red"><?php echo form_error('search'); ?></span>
								</div>								
							</div>
							<div class="form-group row">
								<label class="control-label col-sm-2 col-md-2 col-xs-2">Status:</label>
								<div class="col-sm-6 col-md-6 col-xs-6">
									<input type="radio" value="ACC" name="status" <?php echo  set_radio('status', 'ACC'); ?>  />&nbsp;ACC
									<input type="radio" value="BTL" name="status" <?php echo  set_radio('status', 'BTL');?>  />&nbsp;BTL
									<input type="radio" value="TMS" name="status" <?php echo  set_radio('status', 'TMS'); ?> />&nbsp;TMS
									<input type="radio" value="ALL" name="status" <?php echo  set_radio('status', 'ALL',TRUE);?> />&nbsp;SEMUA
								</div>	
							</div>
							
							<div class="form-group row">
							  	<label class="control-label col-md-2 col-sm-2 col-xs-2">Perintah:</label>
								<div class="col-md-10 col-sm-10 col-xs-10">
									<input type="radio" required value="1" name="perintah"  checked />&nbsp;Tampil
									<input type="radio" required value="2" name="perintah"  />&nbsp;Download									
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
							<table id="tb-entry" class="table table-striped table-condensed">
							<thead>
								<tr>
									<th></th>	
									<th>NOUSUL</th>									
									<th>NIP</th>
									<th>NAMA</th>
									<th>UPDATE</th>
									<th>PELAYANAN</th>
									<th>STATUS</th>
									<th>FILE</th>
									<th>TAHAPAN</th>
								</tr>
							</thead>   
							<tbody>
								<?php if($usul->num_rows() > 0):?>
								<?php  foreach($usul->result() as $value):?>
								<?php
									$link  ='';
									$link2 ='';
									if($value->nomi_status == 'BTL')
									{
										$link='&nbsp;<a href="#" class="btn bg-maroon btn-flat btn-xs" data-tooltip="tooltip"  title="Kirim Ulang Berkas BTL ini" data-toggle="modal" data-target="#kirimModal" data-nip="'.$this->myencrypt->encode($value->nip).'" data-agenda="'.$this->myencrypt->encode($value->agenda_id).'" data-btl="'.$this->myencrypt->encode($value->btl_from).'" ><i class="fa fa-mail-forward"></i></a>';	
									    $link2='<a href="#" class="btn bg-orange btn-xs" data-tooltip="tooltip"  title="Cek Keterangan Alasan BTL" data-toggle="modal" data-target="#cekModal" data-id="?n='.$this->myencrypt->encode($value->nip).'&a='.$this->myencrypt->encode($value->agenda_id).'">'.$value->nomi_status.'</a>';
									}
									else
									{
                                        $link2='<span class="'.$value->bg.'">'.$value->nomi_status.'</span>';
										
									}
  									
								?>
								<tr>
									<td style="width:100px;">
									<a href="#" class="btn bg-orange btn-flat btn-xs" data-tooltip="tooltip"  title="Lihat Kelengkapan Berkas" data-toggle="modal" data-target="#lihatModal" data-id="<?php echo '?n='.$this->myencrypt->encode($value->nip).'&l='.$this->myencrypt->encode($value->layanan_nama)?>"><i class="fa fa-search"></i></a>
									<?php 
									echo '<button class="btn btn-danger btn-xs" data-tooltip="tooltip"  title="Upload Surat Keputusan" data-toggle="modal" data-target="#uploadModal" data-layanan="'.$value->layanan_id.'" data-agenda="'.$value->agenda_id.'" data-instansi="'.$value->agenda_ins.'" data-nip="'.$value->nip.'" data-gol="'.$value->golongan.'"><i class="fa fa-upload"></i></button>';
									echo $link;
									?>
									</td>
									<td><?php echo $value->agenda_nousul?></td>									
									<td style="width:16%"><?php echo ($value->nomi_locked == "1" ?  '<i class="fa fa-lock"></i>'.$value->nip : $value->nip)?></td>
									<td><?php echo $value->nama?></td>
									<td><?php echo $value->update_date?></td>														
									<td><?php echo $value->layanan_nama?></td>											
									<td><?php echo $link2?></td>
									<td style="width:50px;">
									    <?php 
										
									    if(!empty($value->upload_persetujuan))
										{
											$file = $value->file_persetujuan_raw_name.'.pdf';
											
											echo '<span data-toggle="tooltip" data-original-title="Ada File Persetujuan">
											<i class="fa fa-file-pdf-o" data-toggle="modal" data-target="#lihatFileModal" data-id="?id='.$this->myencrypt->encode($value->agenda_ins).'&f='.$this->myencrypt->encode($file).'" style="color:red;"></i></span>';
										}
										else
										{
											echo '<span data-toggle="tooltip" data-original-title="Tidak Ada File Persetujuan">
											<i class="fa fa-file-o" style="color:red;"></i></span>';
										}
										
										
										if(!empty($value->upload_sk))
										{
											$file = $value->file_sk_raw_name.'.pdf';
											
											echo '<span data-toggle="tooltip" data-original-title="Ada File Surat Keputusan">
											<i class="fa fa-file-pdf-o" data-toggle="modal" data-target="#lihatFileModal" data-id="?id='.$this->myencrypt->encode($value->agenda_ins).'&f='.$this->myencrypt->encode($file).'" style="color:red;"></i></span>';
										}
										else
										{
											echo '<span data-toggle="tooltip" data-original-title="Tidak Ada File Surat Keputusan">
											<i class="fa fa-file-o" style="color:red;"></i></span>';
										}
									
									    ?>									
									</td>
									<td><span><?php echo $value->tahapan_nama?> <?php echo (!empty($value->ln_work) ? 'Oleh '.$value->ln_work : '')?></span></td>
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
						    <div class="col-md-12">
							  Anda Yakin akan mengirimkan kembali berkas yang telah BTL ini?	
                            </div>							  
						</div>  
                        <input type="hidden" name="nip"/>	
					    <input type="hidden" name="agenda"/>
						<input type="hidden" name="btlFrom"/>
					</form>
				 </div>
				<div class="modal-footer">
					<button type="button" class="btn bg-maroon" id="nBtnKirim">OK Kirim !</button>
				</div>
			</div>
		</div>	
	</div>
	
	
	<!-- Modal -->
	<div class="modal fade" id="cekModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog modal-md" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
					<h4 class="modal-title" id="">Infomasi Alasan Berkas</h4>
				</div>
							
			</div>
		</div>	
    </div>
	
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
	
	<div id="uploadModal" class="modal fade" role="dialog">
          <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><span id="msg"></span></h4>
                </div>
                <div class="modal-body">
                    <!-- Form -->
                    <form method='post' action='' enctype="multipart/form-data" id="fileUploadForm">
					    <input class="form-control" type="hidden" value="" name="agenda_ins" />
						<input class="form-control" type="hidden" value="" name="agenda_id" />
						<input class="form-control" type="hidden" value="" name="agenda_nip" />
						<input class="form-control" type="hidden" value="" name="agenda_layanan" />
						<input class="form-control" type="hidden" value="" name="agenda_golongan" />
						<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" style="display: none">
                        Select file : <input type='file' name='file' id='file' class='form-control' ><br>
                        <input type='button' class='btn btn-info' value='Upload' id='btn_upload'>
                    </form>
                </div>                
            </div>
        </div>
    </div>
	
	<div class="modal" id="showFile" tabindex="-1" role="dialog" aria-hidden="true">
	    <div class="modal-dialog  md-dialog modal-lg">
		  <div class="modal-content md-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" >File Persetujuan Teknis</h4>
				</div>	
				<div class="modal-body md-body">
					<iframe  id="frame" width="100%" height="100%" frameborder="0" ></iframe>	
					
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
	<script>	
	$(document).ready(function () {
	    $('[data-tooltip="tooltip"]').tooltip();
		
		$('#kirimModal').on('hide.bs.modal',function(e){
			$("#nBtnKirim").show();
		});
		
		$('#kirimModal').on('show.bs.modal',function(e){
			
		     $('#kirimModal #msg').text('Konfirmasi Pengiriman Kembali Berkas BTL')
			.removeClass( "text-green")
		    .removeClass( "text-blue" ); 
			
			var nip		=  $(e.relatedTarget).attr('data-nip'),
				btlFrom	=  $(e.relatedTarget).attr('data-btl'),
				agenda  =  $(e.relatedTarget).attr('data-agenda');
			
			$('#kirimModal input[name=nip]').val(nip);
			$('#kirimModal input[name=agenda]').val(agenda);
			$('#kirimModal input[name=btlFrom]').val(btlFrom);
		
		});
		
		$("#nBtnKirim").on("click",function(e){
			e.preventDefault();
			var data = $('#nfrmKirim').serialize();
			
			$('#kirimModal #msg').text('Updating Please Wait.....')
                     .removeClass( "text-green")
				     .addClass( "text-blue" );  
			
			$.ajax({
				type: "POST",
				url : "<?php echo site_url()?>/berkas/kirim",
				data: data,
				success: function(r){					
					$('#kirimModal #msg').text(r.pesan)
						.removeClass( "text-blue")
						.addClass( "text-green" );
					refreshTable();		
                    $("#nBtnKirim").hide();					
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
		
		$('#cekModal').on('show.bs.modal',function(e) {    		
			var id=  $(e.relatedTarget).attr('data-id');
			$.get('<?php echo site_url()?>/berkas/getAlasan/'+id, function(data){
				$('#cekModal').find('.modal-content').html(data);
			})			
	    });
		
		$('#lihatModal').on('show.bs.modal',function(e) {    		
			var id=  $(e.relatedTarget).attr('data-id');
			$.get('<?php echo site_url()?>/berkas/getKelengkapan/'+id, function(data){
				$('#lihatModal').find('.modal-content').html(data);
			})			
	    });
	
		$('#lihatFileModal').on('show.bs.modal',function(e) {    		 
			var id=  $(e.relatedTarget).attr('data-id');
			var iframe = $('#frame');
			iframe.attr('src', '<?php echo site_url()?>'+'/berkas/getInline/'+id);
					
	    });
		
		$('#uploadModal').on('show.bs.modal',function(e){
			
			$('#uploadModal #msg').text('Upload File Surat Keputusan')
                     .removeClass( "text-green")
					 .removeClass( "text-red")
				     .removeClass( "text-blue" ); 
		   
			var nip   		=  $(e.relatedTarget).attr('data-nip');
			var instansi    =  $(e.relatedTarget).attr('data-instansi');
			var agenda   	=  $(e.relatedTarget).attr('data-agenda');
			var layanan   	=  $(e.relatedTarget).attr('data-layanan');
			var golongan   	=  $(e.relatedTarget).attr('data-gol');
			
			$("input[name=agenda_nip]").val(nip);
			$("input[name=agenda_ins]").val(instansi);
			$("input[name=agenda_id]").val(agenda);
			$("input[name=agenda_layanan]").val(layanan);
			$("input[name=agenda_golongan]").val(golongan);
		});
		
		
		$('#btn_upload').click(function(){
			var form = $('#fileUploadForm')[0];
			// Create an FormData object 
			var data = new FormData(form);
			
			// AJAX request
			$.ajax({
				url: '<?php  echo site_url()?>/berkas/upload',
				type: 'post',
				data: data,
				contentType: false,
				processData: false,
				cache:false,
				success: function(e){                        
					$('#uploadModal #msg').text(e.pesan)
						 .removeClass( "text-blue")
						 .removeClass( "text-red")
						 .addClass( "text-green" );
					refreshTable();	 
				},
				error : function(e){
					$('#uploadModal #msg').text(e.responseJSON.error)
						 .removeClass( "text-blue")							 
						 .removeClass( "text-green")
						 .addClass( "text-red" ); 
					refreshTable();	 
				}	
            });
        });
		
		
		function refreshTable(){						
			$.ajax({   
			    type: 'POST',   
			    url: '<?php echo site_url()?>/berkas/getBerkasAll',   
			    data: $('form[name=frmEntry]').serialize(),
			    success: function(res) {
					$("#tb-entry").html(res);
				},
			});
		}
				
	});
    </script>	
	</body>
</html>
