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
						<form class="form-horizontal" role="form" method="post" action="<?php echo site_url()?>/berkas/getBerkas">
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
									<th></th>	
									<th>NOUSUL</th>									
									<th>NIP</th>
									<th>NAMA</th>
									<th>UPDATE</th>
									<th>PELAYANAN</th>
									<th>STATUS</th>
									<th>TAHAPAN</th>
								</tr>
							</thead>   
							<tbody>
								<?php if($usul->num_rows() > 0):?>
								<?php  foreach($usul->result() as $value):?>
								<tr>
									<td style="width:25px;">
									<a href="#" class="btn bg-orange btn-flat btn-xs" data-tooltip="tooltip"  title="Lihat Kelengkapan Berkas" data-toggle="modal" data-target="#lihatModal" data-id="<?php echo '?n='.$this->myencrypt->encode($value->nip).'&l='.$this->myencrypt->encode($value->layanan_nama)?>"><i class="fa fa-search"></i></a>
									</td>
									<td><?php echo $value->agenda_nousul?></td>									
									<td style="width:16%"><?php echo ($value->nomi_locked == "1" ?  '<i class="fa fa-lock"></i>'.$value->nip : $value->nip)?></td>
									<td><?php echo $value->nama?></td>
									<td><?php echo $value->update_date?></td>														
									<td><?php echo $value->layanan_nama?></td>											
									<td><span class="<?php echo $value->bg?>"><?php echo $value->nomi_status?></span></td>
									<td><span class="badge bg-light-blue"><?php echo $value->tahapan_nama?> <?php echo (!empty($value->ln_work) ? 'Oleh '.$value->ln_work : '')?></span></td>
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
	
	<script src="<?php echo base_url()?>assets/plugins/jQuery/jQuery-2.1.4.min.js"></script>    
    <script src="<?php echo base_url()?>assets/bootstrap/js/bootstrap.min.js"></script> 
    <script src="<?php echo base_url()?>assets/dist/js/app.min.js"></script>
	<script>	
	$(document).ready(function () {
	    $('[data-tooltip="tooltip"]').tooltip();
		
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
	});
    </script>	
	</body>
</html>
