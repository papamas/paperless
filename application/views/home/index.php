<!DOCTYPE html>
<html>
 <head>
    <?php echo $this->load->view('vheader');?>
	<link rel="stylesheet" href="<?php echo base_url()?>assets/dist/css/tree.css">   
	<link rel="stylesheet" href="<?php echo base_url()?>assets/dist/css/custom.css">   
  </head> 	
	<style>
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
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
			
          </ol>
        </section>
		
	    <!-- Main content -->
        <section class="content ">    
			<div class="box box-info">
			    <br/>
				<div class="box-body">
				  <img class="profile-user-img img-responsive img-circle" src="<?php echo base_url()?>assets/dist/img/maleo.png" alt="Logo Male_o 1.9">

				  <h1 class="text-yellow text-center">Male_o 1.9</h1>

				  <p class="text-muted text-center page-header"><b>Aplikasi Manajemen Layanan Kepegawaian Elektronik Online</b></p>
				  
				   <p class="text-muted ">8 (delapan) Jenis layanan kepegawaian yang dapat dilakukan melalui Aplikasi Male_o 1.9 :</p>
					<ul class="text-muted">
					<li>Pemberian Pertimbangan teknis pensiun (pensiun karena BUP, pensiun Janda/Duda, pensiun atas permintaan sendiri, dan pensiun karena tidak cakap jasmani dan/atau rohani);</li>
					<li>Pemberian Pertimbangan teknis kenaikan pangkat (Kenaikan Pangkat Pilihan Jabatan Struktural, Kenaikan Pangkat Pilihan Jabatan Fungsional dan Kenaikan Pangkat Penyesuaian Ijazah); </li>                                                                                                                                                                    Jabatan Fungsional dan Kenaikan Pangkat Penyesuaian Ijazah);
					<li>Pemberian Pertimbangan Teknis Mutasi/Pindah Instansi;</li>
					<li>Pemberian Ijin penggunaan/pencatuman Gelar/peningkatan pendidikan;</li>
					<li>Kartu Pegawai (KARPEG); </li>
					<li>Pengajuan dan penetapan surat keputusan pemberian hak Pensiun Janda/Duda dari Pensiuan PNS yang meninggal dunia dan yang dalam surat keputusan pensiun pegawai belum ditetapkan secara otomatis pensiun janda/dudanya; dan</li>
					<li>Pengajuan dan pengesahan atau pencatatan pendaftaran  isteri (isteri-isteri)/suami atau anak dari Pensiunan PNS.</li>
					</ul>
					<p class="text-red text-right"><small>Male_o 1.9 &copy 2019-2020 BKN XI</small></p>
				</div>				
			</div>
		</section><!-- /.content -->		
      </div><!-- /.content-wrapper -->     
    </div><!-- ./wrapper -->
	
	
	
	<script src="<?php echo base_url()?>assets/plugins/jQuery/jQuery-2.1.4.min.js"></script>    
    <script src="<?php echo base_url()?>assets/bootstrap/js/bootstrap.min.js"></script> 
    <script src="<?php echo base_url()?>assets/dist/js/app.min.js"></script>
	</body>
</html>
