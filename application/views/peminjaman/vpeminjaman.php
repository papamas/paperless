<!DOCTYPE html>
<html>
  <head>
    <?php echo $this->load->view('vheader');?>
	<link rel="stylesheet" href="<?php echo base_url()?>assets/plugins/select2/select2.min.css">
   
  </head>
  <body class="hold-transition skin-yellow sidebar-mini">
    <div class="wrapper">
      <header class="main-header">
        <!-- Logo -->
        <a href="#" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b>A</b></span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg">AdminPanel</span>
        </a>
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
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="">Peminjaman Takah</li>
			<li class="active">Peminjaman</li>
          </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
		        <div class="col-md-12">
					<div class="box box-primary">
						<div class="box-header with-border">
						  <h3 class="box-title">Perekaman Peminjaman Tata Naskah Kepegawaian PNS</h3>
						</div><!-- /.box-header -->
						<!-- form start -->
						<form class="form-horizontal" role="form">
						  <div class="box-body">
							<div class="form-group">
							  <label class="col-sm-2">Instansi</label>
							  <div class="col-sm-10">
							    <select name="instansi" class="form-control select2">
								</select>
							  </div>	
							</div>
							<div class="form-group">
							  <label  class="col-sm-2">NIP Takah</label>
							  <div class="col-sm-4">
							    <select name="nipTakah" id="nip" class="form-control">
								<option value="">--Silahkan Pilih--</option>
								</select>
							  </div>
                              <label  class="col-sm-2">Peminjaman</label>
							  <div class="col-sm-4">
							    <select name="nipPeminjam" class="form-control">
								</select>
							  </div>							  
							</div>
							<div class="form-group">
							  <label  class="col-sm-2">Keperluan</label>
							  <div class="col-sm-10">
							    <textarea name="keperluan" class="form-control" ></textarea>
							  </div>	
							</div>							
							<div class="form-group">
							  <label  class="col-sm-2">Jenis Dokumen </label>
							  <div class="col-sm-10">
							    <select name="dokPinjam" class="form-control">
								</select>
							  </div>	
							</div>
						  </div><!-- /.box-body -->

						  <div class="box-footer">
							<button type="submit" class="btn btn-primary">Submit</button>
						  </div>
						</form>
					</div><!-- /.box -->
		        </div>
		    </div>
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      <!-- footer -->
      <?php $this->load->view('vfooter');?>
      <!-- footer -->
    </div><!-- ./wrapper -->
	<?php $this->load->view('vfooter-js');?>
	<script src="<?php echo base_url()?>assets/plugins/select2/select2.full.min.js"></script>
	
	<script>	
	$(document).ready(function () {	    

		$("#nip").select2({
			minimumInputLength: 10,
			ajax: {
				url:  '<?php echo site_url() ?>'+'/peminjaman/getPns',
				dataType:'json',
				type:'POST',							
			},
			results: function(data, page) {
				return { results: data.results };
			   //console.log(results: data.results);
				//return {results:data};
			}  
		});
			
	});	
	</script>
  </body>
</html>
