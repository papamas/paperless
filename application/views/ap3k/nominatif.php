<!DOCTYPE html>
<html>
 <head>
    <?php  $this->load->view('vheader');?>
	<link rel="stylesheet" href="<?php echo base_url()?>assets/dist/css/tree.css">   
	<link rel="stylesheet" href="<?php echo base_url()?>assets/dist/css/custom.css"> 
	<link rel="stylesheet" href="<?php echo base_url()?>assets/plugins/select2/select2.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/plugins/datepicker/bootstrap-datetimepicker.min.css" />

  </head> 	
	
	<style>
    
	
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
           <section class="content-header">          
			  <ol class="breadcrumb">
				<li><a href="#"><i class="fa fa-dashboard"></i>Home</a></li>
				<li class="">Surat Pengantar</li>
				<li class="active">Surat Pengantar</li>
			  </ol>
			</section>         
        </section>
		
	    <!-- Main content -->
        <section class="content ">
		    <div class="row">
		        <div class="col-md-12">
					<div class="box box-primary">
						<div class="box-header with-border">
						  <h3 class="box-title">Aplikasi Pelayanan KARPEG dan KARIS/KARSU</h3>
						</div><!-- /.box-header -->
					
						<div class="table-responsive">
						<table class="table table-striped">
						<thead>
							<tr class="bg-orange">
								<th>No</th>
								<th>NIP</th>
								<th>Nama</th>
								<th>Layanan</th>
								<th>Instansi</th>	
							</tr>
						</thead>   
						<tbody>							
							<?php $no=1; foreach($nominatif->result() as $value):?>							
							<tr>
								<td><?php echo $no;?></td>
								<td><?php echo $value->nip?></td>
								<td><?php echo $value->PNS_PNSNAM?></td>
								<td><?php echo $value->layanan_nama?></td>	
								<td><?php echo $value->instansi?></td>
							</tr>
							<?php $no++; endforeach;?>													
						</tbody>
						</table>
						</div>
						<?php $row = $nominatif->row();?>
						<form method="post" action="<?php echo site_url()?>/ap3k/saveNominatif">
							<input type="hidden" name="agendaId" value="<?php echo $row->agenda_id?>">
							<input type="hidden" name="kdPengantarAp3k" value="<?php echo $kdPengantarAp3k?>">
							<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" style="display: none">
							<button type="submit" class="btn btn-danger btn-block">&nbsp;Simpan Daftar Nominatif Ke Aplikasi AP3K</button>
						</form>
					</div>
                </div>
            </div> 	
			
        </section><!-- /.content -->	

      
      </div><!-- /.content-wrapper -->     
    </div><!-- ./wrapper -->

	
	
	<script src="<?php echo base_url()?>assets/plugins/jQuery/jQuery-2.1.4.min.js"></script>    
    <script src="<?php echo base_url()?>assets/bootstrap/js/bootstrap.min.js"></script> 
    <script src="<?php echo base_url()?>assets/dist/js/app.min.js"></script>
    <script src="<?php echo base_url()?>assets/plugins/select2/select2.full.min.js"></script>
    <script src="<?php echo base_url()?>assets/plugins/daterange/moment-with-locales.js"></script>	
	<script src="<?php echo base_url()?>assets/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
	<script>	
	$(document).ready(function () {
	    
		
	});	
   </script>
	</body>
</html>
