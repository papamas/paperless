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
				<li class="">DMS</li>
				<li class="active">Lacak Usul TASPEN</li>
			  </ol>
			</section>     
        </section>
		
	    <!-- Main content -->
        <section class="content ">
		    <div class="row">
		        <div class="col-md-12">
					<div class="box box-primary">
						<div class="box-header with-border">
						  <h3 class="box-title">Lacak Status Usul TASPEN</h3>
						</div><!-- /.box-header -->
						<!-- form start -->
						<form class="form-horizontal" role="form" method="post" action="<?php echo site_url()?>/taspen/getBerkas">
						  <div class="box-body">
							<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" style="display: none">
							
							
							<div class="form-group row">							   						 
							    <label class=" control-label col-md-2 col-sm-2 col-xs-2">Filter</label>									
								<div class="col-md-4 col-sm-4 col-xs-4">
									<select name="searchby" class="form-control">
										<option value="">--silahkan Pilih--</option>
										<option value="1" <?php echo  set_select('searchby', '1'); ?>>NIP</option>
										<option value="2" <?php echo  set_select('searchby', '2'); ?>>NOMOR USUL</option>
										<option value="3" <?php echo  set_select('searchby', '3'); ?>>PELAYANAN</option>
									</select>
									<span class="help-block text-red"><?php echo form_error('searchby'); ?></span>
								</div>
								<div class="col-md-6 col-sm-6 col-xs-6">									
								    <input type="text" name="search" class="form-control" placeholder="Masukan data pencarian" value="<?php echo set_value('search'); ?>">
									<span class="help-block text-red"><?php echo form_error('search'); ?></span>
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
							<table class="table table-striped table-condensed">
							<thead>
								<tr>
									<th>NOMOR</th>									
									<th>NIP</th>
									<th>NAMA PNS</th>
									<th>NAMA</th>
									<th>UPDATE</th>
									<th>PELAYANAN</th>
									<th>FILE</th>
									<th>STATUS</th>
									<th>TAHAPAN</th>
								</tr>
							</thead>   
							<tbody>
								<?php if($usul->num_rows() > 0):?>
								<?php  foreach($usul->result() as $value):?>
								<tr>
									<td><?php echo $value->	nomor_usul?></td>									
									<td><?php echo (!empty($value->nip_lama) ? $value->nip_lama.' / '.$value->nip_baru : $value->nip)?></td>
									<td><?php echo $value->nama_pns?></td>
									<td><?php echo $value->nama_janda_duda?></td>
									<td><?php echo $value->updated_date?></td>														
									<td><?php echo $value->layanan_nama?></td>	
									<td>
										<?php 
										if(!empty($value->file_persetujuan))
										{
											$file = $value->file_persetujuan;
											
											echo '<span data-toggle="tooltip" data-original-title="Ada File Persetujuan">
											<i class="fa fa-file-pdf-o" data-toggle="modal" data-target="#lihatFileModal" data-id="?t='.$this->myencrypt->encode('application/pdf').'&f='.$this->myencrypt->encode($file).'" style="color:red;"></i></span>';
										}
										else
										{
											echo '<span data-toggle="tooltip" data-original-title="Tidak Ada File Persetujuan">
											<i class="fa fa-file-o" style="color:red;"></i></span>';
										}
										?>
									</td>
									<td><span class="<?php echo $value->bg?>"><?php echo $value->usul_status?></span></td>
									<td><span class="badge bg-maroon"><?php echo $value->tahapan_nama?></span></td>
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
	
	<script src="<?php echo base_url()?>assets/plugins/jQuery/jQuery-2.1.4.min.js"></script>    
    <script src="<?php echo base_url()?>assets/bootstrap/js/bootstrap.min.js"></script> 
    <script src="<?php echo base_url()?>assets/dist/js/app.min.js"></script>
	<script>	
	$(document).ready(function () {
	    $('[data-tooltip="tooltip"]').tooltip();
		
		$('#lihatFileModal').on('show.bs.modal',function(e) {    		 
			var id=  $(e.relatedTarget).attr('data-id');			
			var iframe = $('#frame');
			iframe.attr('src', '<?php echo site_url()?>'+'/taspen/getInlineTaspen/'+id);
					
	    });
		
	
	});	
	</script> 
	</body>
</html>
