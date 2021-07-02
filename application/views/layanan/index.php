<!DOCTYPE html>
<html>
 <head>
    <?php  $this->load->view('vheader');?>	
  </head> 
	
	<style>
    /*Bootstrap modal size iframe*/
	@media (max-width: 1280px){
		.modal-dialog  {
			height:630px;
			width:800px;
		}
		.modal-body {
			height: 500px;	
		}
	}
	@media screen and (min-width:1281px) and (max-width:1600px){
		.modal-dialog  {
			height:700px;
			width:1000px;
		}
		.modal-body {
			height: 550px;	
		}
	}
	@media screen and (min-width:1601px) and (max-width:1920px){
		.modal-dialog  {
			height:830px;
			width:1200px;
		}
		.modal-body {
			height: 700px;	
		}
	}

	/*Vertically centering Bootstrap modal window*/
	.vertical-alignment-helper {
		display:table;
		height: 100%;
		width: 100%;
		pointer-events:none; /* This makes sure that we can still click outside of the modal to close it */
	}
	.vertical-align-center {
		/* To center vertically */
		display: table-cell;
		vertical-align: middle;
		pointer-events:none;
	}
	.modal-content {
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
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-book"></i> Home</a></li>
            <li class="">TU</li>
			<li class="active">Layanan</li>
          </ol>
        </section>
		
	    <!-- Main content -->
        <section class="content ">
		    <div class="row">
		        <div class="col-md-12">
					<div class="box box-danger">
						<div class="box-header with-border">
						  <h3 class="box-title">Daftar Antrian Layanan <?php echo $this->auth->getBidang();?></h3>
						</div><!-- /.box-header -->
						
						<div class="nav-tabs-custom">
							<ul class="nav nav-tabs">							
							  <li class="active"> <a href="#tab1" data-toggle="tab">INSTANSI DAERAH/PUSAT</a></li>
							  <?php if($this->session->userdata('session_bidang') == 2):?>
							  <li> <a href="#tab2" data-toggle="tab">TASPEN</a></li>
							  <?php endif;?>
							</ul>
						</div>	
						
						<div class="tab-content">					   
							<div class="active tab-pane" id="tab1">	
							    <div class="post">
									<div class="table-responsive">						
										<table class="table table-striped">
										<thead>
											<tr>
												<th>NO USUL</th>
												<th>INSTANSI</th>
												<th>TANGGAL KIRIM</th>
												<th>PELAYANAN</th> 
												<th>ANTRIAN</th>
												<th>NOMINATIF</th>
												<th>PERINTAH</th>								
											</tr>
										</thead>   
										<tbody>	
											<?php $total=0;?>
											<?php if($usul->num_rows() > 0):?>								
											<?php  foreach($usul->result() as $value):?>
											<tr>
												<td><?php echo $value->agenda_nousul?></td>
												<td><?php echo $value->instansi?></td>
												<td><?php echo $value->agenda_timestamp?></td>
												<td style="width:20px"><?php echo $value->layanan_nama?></td>
												<td class="text-center text-info"><?php echo $value->jumlah_usul?></td>
												<td class="text-center text-maroon"><?php echo $value->agenda_jumlah?></td>
												<td>
												<button class="btn btn-primary btn-xs" data-tooltip="tooltip"  title="Lihat Pengantar" data-toggle="modal" data-target="#pengantarModal" data-id="?id=<?php echo $this->myencrypt->encode($value->agenda_ins).'&f='.$this->myencrypt->encode($value->agenda_dokumen)?>"><i class="fa fa-search"></i></button>
												<a href="#dPdf" class="btn btn-danger btn-xs"  data-tooltip="tooltip"  title="Unduh Pengantar" id="?id=<?php echo $this->myencrypt->encode($value->agenda_ins).'&f='.$this->myencrypt->encode($value->agenda_dokumen)?>"><i class="fa fa-file-pdf-o"></i></a>
												<a href="#dExcel" class="btn btn-success btn-xs" data-tooltip="tooltip"  title="Cetak Nominatif" id="?id=<?php echo $value->agenda_id?>" ><i class="fa fa-file-excel-o"></i></a>
												</td>
											</tr>
											<?php $total = $total + $value->jumlah_usul;?>
											<?php endforeach;?>
											<?php endif;?>				
											<tr><td colspan="7" class="full-right">
												<label class="form-label">Jumlah Seluruh Berkas : <?php echo $total;?></label>
												</td>
											</tr>						
										</tbody>
										</table>
									</div>
								</div>
							</div>
							
							<div class="tab-pane" id="tab2">	
							    <div class="post">
									<div class="table-responsive">						
										<table class="table table-striped">
										<thead>
											<tr>
												<th>NO USUL</th>
												<th>INSTANSI</th>
												<th>TANGGAL KIRIM</th>
												<th>PELAYANAN</th> 
												<th>ANTRIAN</th>
												<th>NOMINATIF</th>
												<th>PERINTAH</th>								
											</tr>
										</thead>   
										<tbody>	
											<?php $totalTaspen=0;?>
											<!-- TASPEN USUL!-->
											<?php if($usulTaspen->num_rows() > 0):?>								
											<?php  foreach($usulTaspen->result() as $value):?>
											<tr>
												<td><?php echo $value->nomor_usul?></td>
												<td>TASPEN</td>
												<td><?php echo $value->kirim_bkn_date?></td>
												<td style="width:20px"><?php echo $value->layanan_nama?></td>
												<td class="text-center text-info"><?php echo $value->jumlah_usul?></td>
												<td class="text-center text-maroon"><?php echo $value->jumlah_usul?></td>
												<td>
												<button class="btn btn-primary btn-xs" data-tooltip="tooltip"  title="Lihat Pengantar" data-toggle="modal" data-target="#pengantarModalTaspen" data-id="?id=<?php echo $this->myencrypt->encode($value->usul_id).'&f='.$this->myencrypt->encode($value->file_pengantar)?>"><i class="fa fa-search"></i></button>
												<a href="#dPdfTaspen" class="btn btn-danger btn-xs"  data-tooltip="tooltip"  title="Unduh Pengantar" id="?id=<?php echo $this->myencrypt->encode($value->usul_id).'&f='.$this->myencrypt->encode($value->file_pengantar)?>"><i class="fa fa-file-pdf-o"></i></a>
												<a href="#dExcelTaspen" class="btn btn-success btn-xs" data-tooltip="tooltip"  title="Cetak Nominatif" id="?id=<?php echo $value->usul_id?>" ><i class="fa fa-file-excel-o"></i></a>
												</td>
											</tr>
											<?php $totalTaspen = $totalTaspen + $value->jumlah_usul;?>
											<?php endforeach;?>
											<?php endif;?>											
											<tr><td colspan="7" class="full-right">
												<label class="form-label">Jumlah Seluruh Berkas : <?php echo $totalTaspen;?></label>
												</td>
											</tr>						
										</tbody>
										</table>
									</div>
								</div>
							</div>
                        </div>							
							
					    
					</div>
                </div>					
			</div>
        </section><!-- /.content -->		
      </div><!-- /.content-wrapper -->     
    </div><!-- ./wrapper -->
	
	<div class="modal" id="pengantarModal" role="dialog" style="display:none;">
	   <div class="modal-dialog modal-lg">
	      <div class="modal-content">
		      <div class="modal-header">
			    <button type="button" class="close" data-dismiss="modal">&times;</button>
		        <h4 class="modal-title" >File Pengantar Instansi</h4>
			  </div>	
		      <div class="modal-body">
				<iframe   id="frame" width="100%" height="100%" frameborder="0" ></iframe>	
			  </div>
		  </div>
		</div>
	</div>	
	
	<div class="modal" id="pengantarModalTaspen" role="dialog" style="display:none;">
	   <div class="modal-dialog modal-lg">
	      <div class="modal-content">
		      <div class="modal-header">
			    <button type="button" class="close" data-dismiss="modal">&times;</button>
		        <h4 class="modal-title" >File Pengantar Taspen</h4>
			  </div>	
		      <div class="modal-body">
				<iframe   id="frameTaspen" width="100%" height="100%" frameborder="0" ></iframe>	
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
		
		$('#pengantarModal').on('show.bs.modal',function(e) {    		
			var id=  $(e.relatedTarget).attr('data-id');
			var iframe = $('#frame');
			iframe.attr('src', '<?php echo site_url()?>'+'/layanan/getInline/'+id);			
	    });
		
		$('a[href="#dExcel"]').click(function(){
			var id= this.id;
		     document.location = "<?php echo site_url()?>/layanan/getExcel/"+id;
		}); 

		$('a[href="#dPdf"]').click(function(){
			var id= this.id;
		     document.location  = "<?php echo site_url()?>/layanan/getPdf/"+id;
		}); 
		
		/*TASPEN*/
		$('#pengantarModalTaspen').on('show.bs.modal',function(e) {    		
			var id=  $(e.relatedTarget).attr('data-id');
			var iframe = $('#frameTaspen');
			iframe.attr('src', '<?php echo site_url()?>'+'/layanan/getInlineTaspen/'+id);			
	    });
		
		$('a[href="#dPdfTaspen"]').click(function(){
			var id= this.id;
		     document.location  = "<?php echo site_url()?>/layanan/getPdfTaspen/"+id;
		});
		
		$('a[href="#dExcelTaspen"]').click(function(){
			var id= this.id;
		     document.location = "<?php echo site_url()?>/layanan/getExcelTaspen/"+id;
		}); 
		
	});	
   </script>
   
	</body>
</html>
