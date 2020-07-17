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
				<li class="">Pengantar</li>
				<li class="active">Daftar Pengantar</li>
			  </ol>
			</section>         
        </section>
		
	    <!-- Main content -->
        <section class="content ">
		    <div class="row">
		        <div class="col-md-12">
					<div class="box box-primary">
						<div class="box-header with-border">
						  <h3 class="box-title">Daftar Pengantar Instansi</h3>
						</div><!-- /.box-header -->
						<!-- form start -->
						<form class="form-horizontal" role="form" method="post" action="<?php echo site_url()?>/pengantar/getPengantar">
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
						<table class="table table-striped">
						<thead>
							<tr>
								<th>File Pengantar</th>
								<th>INSTANSI</th>
								<th>NIP</th>
								<th>Nama</th>
							</tr>
						</thead>   
						<tbody>
							<?php if($daftar->num_rows() > 0):?>
							<?php  foreach($daftar->result() as $value):?>							
							<tr>
								<td><button class="btn btn-primary btn-xs" data-tooltip="tooltip"  title="Lihat File Pengantar" data-toggle="modal" data-target="#pengantarModal" data-id="?id=<?php echo $this->myencrypt->encode($value->agenda_ins)?>&f=<?php echo $this->myencrypt->encode($value->agenda_dokumen)?>"><i class="fa fa-search"></i></button> <?php echo $value->agenda_dokumen?></td>
								<td><?php echo $value->nama_instansi?></td>	
								<td><?php echo $value->nip?></td>
								<td><?php echo $value->nama_pns?></td>								
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
	
      </div><!-- /.content-wrapper -->     
    </div><!-- ./wrapper -->

	
	
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
		
		$('#pengantarModal').on('show.bs.modal',function(e) {    		
			var id=  $(e.relatedTarget).attr('data-id');
			var iframe = $('#frame');
			iframe.attr('src', '<?php echo site_url()?>'+'/pengantar/getInline/'+id);			
	    });
	});	
   </script>
	</body>
</html>
