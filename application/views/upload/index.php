<!DOCTYPE html>
<html>
  <head>
    <?php echo $this->load->view('vheader');?>
	<link rel="stylesheet" href="<?php echo base_url()?>assets/dist/css/tree.css">   
	<link rel="stylesheet" href="<?php echo base_url()?>assets/dist/css/custom.css"> 
    <link rel="stylesheet" href="<?php echo base_url()?>assets/plugins/dropzone/dropzone.css">
	
	<style>
  </style>
  </head>
  <body class="skin-yellow sidebar-mini sidebar-collapse">
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
	    <!-- Main content -->
        <section class="content ">
		    <form id="upload" action="<?php echo site_url()?>/upload/doUpload" class="dropzone">
				<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" style="display: none">

			</form>
			<div class="row">
		        <div class="col-md-12">
					<div class="box box-primary">
						<div class="box-header with-border">
						  <h3 class="box-title">Daftar Tabel Format Dokumen Kepegawaian</h3>
						</div><!-- /.box-header -->					
						<div class="table-responsive">
							<table class="table table-striped">
							<thead>
								<tr>
									<th>No</th>
									<th>Nama Dokumen</th>
									<th>Format</th>
									<th>Flag</th>
									<th>Keterangan</th>													
								</tr>
							</thead>   
							<tbody>	
							    <?php $i=1;foreach($dokumen->result() as $value):?>
								<tr>
									<td><?php echo $i?></td>
									<td><?php echo $value->nama_dokumen?></td>
									<td><?php echo $value->format?></td>
									<td><?php echo ($value->flag == 1 ? "<span class='badge bg-red' data-tooltip='tooltip' title='Dokumen Wajib ada'>Dokumen_Utama</span>" :" " )?></td>
									<td><?php echo $value->keterangan?></td>
								</tr>
								<?php $i++;endforeach;?>
							</tbody>
							</table>
						</div>	
					</div>	
				</div>
			</div>			
        </section><!-- /.content -->

		
      </div><!-- /.content-wrapper -->     
	  
    </div><!-- ./wrapper -->
	
	
    <script src="<?php echo base_url()?>assets/plugins/jQuery/jQuery-2.1.4.min.js"></script>    
    <script src="<?php echo base_url()?>assets/bootstrap/js/bootstrap.min.js"></script> 
    <script src="<?php echo base_url()?>assets/dist/js/app.min.js"></script>
	<script src="<?php echo base_url()?>assets/plugins/dropzone/dropzone.js"></script>
	
	<script>	
	$(document).ready(function () {
        Dropzone.options.upload = {
			dictDefaultMessage: "Letakkan file dokumen kepegawaian yang akan di upload disini"
		};
		
		$('[data-tooltip="tooltip"]').tooltip();
		
	});	
   </script>
  </body>
</html>
