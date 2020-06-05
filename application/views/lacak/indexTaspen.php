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
				<li class="active">Lacak Berkas</li>
			  </ol>
			</section>     
        </section>
		
	    <!-- Main content -->
        <section class="content ">
		    <div class="row">
		        <div class="col-md-12">
					<div class="box box-primary">
						<div class="box-header with-border">
						  <h3 class="box-title">Lacak Berkas</h3>
						</div><!-- /.box-header -->
						<!-- form start -->
						<form name="frmVerifikasi" class="form-horizontal" role="form" method="post" action="<?php echo site_url()?>/lacak/getBerkas">
						  <div class="box-body">
							<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" style="display: none">
							<div class="form-group row">
							  	<label class="control-label col-md-2">Usul</label>
								<div class="col-md-10">
									<input type="radio" value="1" name="usul"  <?php echo  set_radio('usul', '1', TRUE); ?> />&nbsp;INSTANSI DAERAH/PUSAT
									<input type="radio" value="2" name="usul"  <?php echo  set_radio('usul', '2'); ?>/>&nbsp;TASPEN									
								</div>	
								<span class="help-block text-red"><?php echo form_error('usul'); ?></span>
							</div> 
							<div class="form-group">							   						 
							    <label class=" control-label col-md-2">Filter</label>									
								<div class="col-md-4">
									<select name="searchby" class="form-control">
										<option value="">--silahkan Pilih--</option>
										<option value="1" <?php echo  set_select('searchby', 1); ?>>NIP</option>										
										<option value="2" <?php echo  set_select('searchby', 2); ?>>INSTANSI</option>
										<option value="3" <?php echo  set_select('searchby', 3); ?>>NOMOR USUL</option>
										<option value="4" <?php echo  set_select('searchby', 4); ?>>PELAYANAN</option>
										<option value="5" <?php echo  set_select('searchby', 5); ?>>NAMA</option>
									</select>
									<span class="help-block text-red"><?php echo form_error('searchby'); ?></span>
								</div>
								<div class="col-md-6">									
								    <input type="text" name="search" class="form-control" placeholder="Masukan Kata Kunci pencarian" value="<?php echo set_value('search'); ?>">
								    <span class="help-block text-red"><?php echo form_error('search'); ?></span>
								</div>															
							</div>						
							<div class="box-footer">
								<button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>&nbsp;Cari</button>
							  </div>
							
						   </div>						   
						</form>
						
						
						<?php if($show):?>	                       	
						<div class="table-responsive">
							<table id="tb-lacak" class="table table-striped table-condensed">
							<thead>
								<tr>
									
									<th>NOUSUL</th>									
									<th>PELAYANAN</th>
									<th>INSTANSI</th>
									<th>NIP</th>									
									<th>NAMA PNS</th>	
									<th>NAMA</th>
									<th>STATUS</th>
								</tr>
							</thead> 
                           							
							<tbody>
								<?php if($usul->num_rows() > 0):?>
								<?php  foreach($usul->result() as $value):?>
								<tr>		
									<td><?php echo $value->nomor_usul?></td>													
									<td><?php echo $value->layanan_nama?></td>
                                    <td>TASPEN</td>		
                                    <td><?php echo $value->nip?></td>									
									<td><?php echo $value->nama_pns?></td>		
									<td><?php echo $value->nama_janda_duda?></td>	
									<td><span class="<?php echo $value->bg?>"><?php echo $value->usul_status?></span></td>
							    </tr>
								<tr>
								
								<td colspan="7">
								    <ul class="timeline timeline-inverse">					  
										<li class="time-label">
											<span class="bg-green">
											 <?php echo date_format(date_create($value->created_date),'d M Y')  ?>
											</span>
										</li>
										<li>
											<i class="fa fa-newspaper-o bg-aqua"></i>
											<div class="timeline-item">
											    <span class="time"><i class="fa fa-clock-o"></i>  <?php echo date_format(date_create($value->created_date),'H:i')  ?></span>
												<h3 class="timeline-header"><a href="#">TASPEN</a></h3>
												<div class="timeline-body"> Proses Berkas Usul</div>
											</div>
										</li>
										
										<li class="time-label">
											<span class="bg-blue">
											 <?php echo date_format(date_create($value->kirim_bkn_date),'d M Y') ?>
											</span>
										</li>
										<li>
											<i class="fa fa-envelope bg-aqua"></i>
											<div class="timeline-item">
											   <span class="time"><i class="fa fa-clock-o"></i>  <?php echo date_format(date_create($value->kirim_bkn_date),'H:i')  ?></span>
												<h3 class="timeline-header"><a href="#">TASPEN</a></h3>
												<div class="timeline-body"> Kirim Berkas atas nama <?php echo $value->nama_pns?> ke BKN  dikirim Oleh : <?php echo $value->kirim_by?></div>											
											</div>
										</li>
										
										<li class="time-label">
											<span class="bg-blue">
											 <?php echo date_format(date_create($value->usul_kirim_date),'d M Y') ?>
											</span>
										</li>
										<li>
											<i class="fa fa-envelope bg-aqua"></i>
											<div class="timeline-item">
											   <span class="time"><i class="fa fa-clock-o"></i>  <?php echo date_format(date_create($value->usul_kirim_date),'H:i')  ?></span>
												<h3 class="timeline-header"><a href="#">BKN</a></h3>
												<div class="timeline-body"> Kirim Berkas atas nama <?php echo $value->nama_pns?> ke Tim Teknis BKN  dikirim Oleh : <?php echo $value->usul_kirim_name?></div>											
											</div>
										</li>
										
										<li class="time-label">
											<span class="bg-blue">
											 <?php echo date_format(date_create($value->usul_lock_date),'d M Y')  ?>
											</span>
										</li>
										<li>
											<i class="fa fa-envelope bg-aqua"></i>
											<div class="timeline-item">
											   <span class="time"><i class="fa fa-clock-o"></i>  <?php echo date_format(date_create($value->usul_lock_date),'H:i') ?></span>
												<h3 class="timeline-header"><a href="#">BKN</a></h3>
												<div class="timeline-body"> Kunci Berkas atas nama <?php echo $value->nama_pns?> Oleh : <?php echo $value->usul_lock_name?></div>											
											</div>
										</li>
										
										<li class="time-label">
											<span class="bg-blue">
											 <?php echo date_format(date_create($value->usul_verif_date),'d M Y') ?>
											</span>
										</li>
										<li>
											<i class="fa fa-check bg-yellow"></i>
											<div class="timeline-item">
											   <span class="time"><i class="fa fa-clock-o"></i>  <?php echo date_format(date_create($value->usul_verif_date),'H:i') ?></span>
												<h3 class="timeline-header"><a href="#">BKN</a></h3>
												<div class="timeline-body"> Verifikasi Berkas atas nama <?php echo $value->nama_pns?> Oleh : <?php echo $value->usul_verif_name?></div>											
											</div>
										</li>
										
										<li class="time-label">
											<span class="bg-yellow">
											 <?php echo date_format(date_create($value->usul_entry_date),'d M Y') ?>
											</span>
										</li>
										<li>
											<i class="fa fa-trophy bg-red"></i>
											<div class="timeline-item">
											   <span class="time"><i class="fa fa-clock-o"></i>  <?php echo date_format(date_create($value->usul_entry_date),'H:i') ?></span>
												<h3 class="timeline-header"><a href="#">BKN</a></h3>
												<div class="timeline-body"> Entry Berkas atas nama <?php echo $value->nama_pns?> Oleh : <?php echo $value->usul_entry_name?> dengan Nomor Persetujuan <?php echo $value->usul_no_persetujuan?> dan Tanggal <?php echo date_format(date_create($value->usul_tgl_persetujuan),'d-m-Y')?> </div>											
											</div>
										</li>
									
									</ul>									
								</td>
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
					  <div class="form-group"> Yakin berkas ini akan dikirim ke Tim Teknis ?</div>
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
	    $('[data-tooltip="tooltip"]').tooltip();
		
		$(".select2").select2({
			width: '100%'
		});			
		
		// hide empty column
		var columns = $("#tb-lacak > tbody > tr:first > td").length;
		for (var i = 0; i < columns; i++) {
			if ($("#tb-lacak > tbody > tr > td:nth-child(" + i + ")").filter(function() {
			  return $(this).text() != '';
			}).length == 0) {
			  $("#tb-lacak > tbody > tr > td:nth-child(" + i + "), #tb-lacak > thead > tr > th:nth-child(" + i + ")").hide();
			}
		}
		
	});
    </script>
	</body>
</html>
