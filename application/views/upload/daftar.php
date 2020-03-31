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
				<li class="active">Daftar Dokumen</li>
			  </ol>
			</section>         
        </section>
		
	    <!-- Main content -->
        <section class="content ">
		    <div class="row">
		        <div class="col-md-12">
					<div class="box box-primary">
						<div class="box-header with-border">
						  <h3 class="box-title">Daftar Dokumen</h3>
						</div><!-- /.box-header -->
						<!-- form start -->
						<form class="form-horizontal" role="form" method="post" action="<?php echo site_url()?>/upload/getDaftar">
						  <div class="box-body">
							<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" style="display: none">
							
							<div class="form-group">
							  <label class="control-label col-md-2 col-sm-2 col-xs-2">Instansi</label>
							  <div class="col-md-10 col-sm-10 col-xs-10">
							    <select name="instansi" class="form-control select2">									
									<?php if($instansi->num_rows() > 0):?>
									<?php foreach($instansi->result() as $value):?>
									<option value="<?php echo $value->INS_KODINS?>" <?php echo set_select('instansi', $value->INS_KODINS)?>><?php echo $value->INS_NAMINS?></option>
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
										<option value="1" <?php echo  set_select('searchby', '1'); ?> >NIP</option>	
										<option value="2" <?php echo  set_select('searchby', '2'); ?> >JENIS SK</option>
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
						<table class="table table-striped">
						<thead>
							<tr>
								<th>SK</th>
								<th>INSTANSI</th>
								<th>NIP</th>
								<th>NAMA</th>
								<th>UPLOAD</th>
								<th>UPDATE</th>	
                                <th>BY</th>								
							</tr>
						</thead>   
						<tbody>
							<?php if($daftar->num_rows() > 0):?>
							<?php  foreach($daftar->result() as $value):?>
							<?php 
							$jenis_sk     = $value->nama_dokumen;
														
							if($jenis_sk != "IJAZAH" && $jenis_sk != "TRANSKRIP" && $jenis_sk != "IBEL" && $jenis_sk != "MOU") 
							{
								switch($value->minor_dok){
									case 45:
										$n = "IV/e";
									break;
									case 44:
										$n = "IV/d";
									break;
									case 43:
										$n = "IV/c";
									break;
									case 42:
										$n = "IV/b";
									break;
									case 41:
										$n = "IV/a";
									break;
									case 34:
										$n = "III/d";
									break;
									case 33:
										$n = "III/c";
									break;
									case 32:
										$n = "III/b";
									break;
									case 31:
										$n = "III/a";
									break;
									case 24:
										$n = "II/d";
									break;
									case 23:
										$n = "II/c";
									break;
									case 22:
										$n = "II/b";
									break;
									case 21:
										$n = "II/a";
									break;
									case 14:
										$n = "I/d";
									break;
									case 13:
										$n = "I/c";
									break;
									case 12:
										$n = "I/b";
									break;
									case 11:
										$n = "I/a";
									break;
									default:
										$n = $value->minor_dok;									
																				
								}	
							}
							else
							{
								
								switch($value->minor_dok){
									case 50:
										$n = "S-3/Doktor";
									break;
									case 45:
										$n = "S-2";
									break;
									case 40:
										$n = "S-1/Sarjana";
									break;
									case 35:
										$n = "Diploma IV";
									break;
									case 30:
										$n = "Diploma III/Sarjana Muda";
									break;
									case 25:
										$n = "Diploma II";
									break;
									case 20:
										$n = "Diploma I";
									break;
									case 18:
										$n = "SLTA Keguruan";
									break;
									case 17:
										$n = "SLTA Kejuruan";
									break;
									case 15:
										$n = "SLTA";
									break;
									case 12:
										$n = "SLTP Kejuruan";
									break;
									case 10:
										$n = "SLTP";
									break;
									case 05:
										$n = "Sekolah Dasar";
									break;														
									default:
										$n = $value->minor_dok;									
																				
								}								
									
							}
							
							?>
							<tr>
								<td><button class="btn btn-primary btn-xs" data-tooltip="tooltip"  title="Lihat SK" data-toggle="modal" data-target="#skModal" data-id="?id=<?php echo $this->myencrypt->encode($value->id_instansi)?>&f=<?php echo $this->myencrypt->encode($value->orig_name)?>"><i class="fa fa-search"></i></button> <?php echo $value->nama_dokumen?> <?php echo $n?></td>
								<td><?php echo $value->instansi?></td>
								<td><?php echo $value->nip?></td>
								<td><?php echo $value->nama?></td>
								<td><?php echo $value->created_date?></td>	
								<td><?php echo $value->update_date?></td>	
								<td><?php echo $value->name?></td>
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

        <div class="modal fade" id="skModal" tabindex="-1" role="dialog" aria-hidden="true">
		   <div class="modal-dialog modal-lg">
			  <div class="modal-content">
				    <div class="modal-header">
					    <button type="button" class="close" data-dismiss="modal">&times;</button>
					    <h4 class="modal-title" >File Dokumen Instansi</h4>
				    </div>	
				    <div class="modal-body">
				        <div class="embed-responsive z-depth-1-half" style="height:100%">
							<iframe   id="frame" width="100%" height="100%" frameborder="0" ></iframe>	
						</div>
					</div>
			  </div>
			</div>
		</div>	
	
      </div><!-- /.content-wrapper -->     
    </div><!-- ./wrapper -->

	
	
	<script src="<?php echo base_url()?>assets/plugins/jQuery/jQuery-2.1.4.min.js"></script>    
    <script src="<?php echo base_url()?>assets/bootstrap/js/bootstrap.min.js"></script> 
    <script src="<?php echo base_url()?>assets/dist/js/app.min.js"></script>
	<!--
	<script src="<?php echo base_url()?>assets/plugins/pdfo/pdfobject.js"></script>	
	!-->
	<script>	
	$(document).ready(function () {
	    
		$('[data-tooltip="tooltip"]').tooltip();
		
		$('#skModal').on('show.bs.modal',function(e) {    		
			var id=  $(e.relatedTarget).attr('data-id');
			var iframe = $('#frame');
			iframe.attr('src', '<?php echo site_url()?>'+'/upload/getInline/'+id);			
	    });
	});	
   </script>
	</body>
</html>
