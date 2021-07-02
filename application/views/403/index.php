<!DOCTYPE html>
<html>
 <head>
    <?php  $this->load->view('vheader');?>	
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
			<section class="content ">			
				<div class="callout callout-danger">
                <h4><i class="fa fa-bullhorn"></i> Not allow to view!</h4>
                <p>ooops... anda tidak diijinkan melihat halaman ini.</p>
                </div>
			</section>		     
        </div><!-- ./wrapper -->
	
	
	
	<script src="<?php echo base_url()?>assets/plugins/jQuery/jQuery-2.1.4.min.js"></script>    
    <script src="<?php echo base_url()?>assets/bootstrap/js/bootstrap.min.js"></script> 
    <script src="<?php echo base_url()?>assets/dist/js/app.min.js"></script>
	</body>
</html>
