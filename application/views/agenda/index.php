<!DOCTYPE html>
<html>
<head>
<?php  $this->load->view('vheader');?> 
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/sweetalert2/sweetalert2.min.css">
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
			<h3><b>LIST AGENDA</b></h3>			
		</section>

	  <!-- Main content -->
	  <section class="content">
		<div class="row">
			<div class="col-md-12">
			  <a href="<?php echo site_url(); ?>/agenda/tambah" type="button" class="btn btn-block btn-primary btn-flat"><i class="fa fa-plus"></i>&nbsp;&nbsp; <b>Tambah Agenda</b></a>
			</div>
		</div>
		
		<div class="row">
		    <div class="col-md-12">
				<div class="box">
				    <div class="box-body">
					    <div class="table-responsive">
							<table id="tblagenda" class="table table-striped">
							  <thead>
							  <tr>
								<th>No</th>
								<th>Tanggal</th>
								<th>No usul</th>
								<th>Layanan</th>
								<th>Aksi</th>
							  </tr>
							  </thead>
							  <tbody>
								<?php $no=1; foreach ($list_agenda as $agenda): ?>
								<tr>
								  <td><?php echo $no++; ?></td>
								  <td><?php echo $agenda->agenda_tgl; ?></td>
								  <td><?php echo $agenda->agenda_nousul; ?></td>
								  <td><?php echo $agenda->layanan_nama; ?></td>
								  <td>
									<a href="<?php echo site_url("agenda/ubah/$agenda->agenda_id") ?>" type="button" class="btn btn-warning btn-flat"><i class="fa fa-edit"></i>&nbsp;Edit</a>
									<a onclick="confirmation(event)" href="<?php echo site_url("agenda/hapus/$agenda->agenda_id")?>" type="button" class="btn btn-danger btn-flat"><i class="fa fa-trash"></i>&nbsp;Hapus</a>
									<a href="<?php echo site_url("agenda/nominatif/$agenda->agenda_id")?>" type="button" class="btn btn-primary btn-flat"><i class="fa fa-edit"></i>&nbsp; Input Nominatif</a>
								  </td>
								</tr>
							  <?php endforeach; ?>
							  </tbody>
							</table>
						</div>	
				    </div><!-- /.box-body -->
				</div><!-- /.box -->
		    </div> <!-- /.col -->
		</div><!-- /.row -->
		
	  </section><!-- /.content -->
	</div><!-- /.content-wrapper -->
  </div><!-- ./wrapper -->
<script src="<?php echo base_url()?>assets/plugins/jQuery/jQuery-2.1.4.min.js"></script>    
<script src="<?php echo base_url()?>assets/bootstrap/js/bootstrap.min.js"></script> 
<script src="<?php echo base_url()?>assets/dist/js/app.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/sweetalert2/sweetalert2.min.js"></script>
<script type="text/javascript">
   function confirmation(ev) {
		  ev.preventDefault();
		  var urlToRedirect = ev.currentTarget.getAttribute('href'); //use currentTarget because the click may be on the nested i tag and not a tag causing the href to be empty
		  //console.log(urlToRedirect); // verify if this is the right URL
		  Swal.fire({
			title: 'Yakin Ingin Menghapus Data ini?',
			text: "Data yang dihapus tidak bisa dikembalikan!",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Ya, Hapus!'
		  })
		  .then((result) => {
			if (result.value) {
				location.replace(urlToRedirect)
			}
		});
	}
	<?php if($show):?>
    Swal.fire(
		"<?php echo $title?>",
		"<?php echo $pesan; ?>",
		"<?php echo $tipe?>"
	) 
	<?php endif;?>  
</script>
</body>
</html>