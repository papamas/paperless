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
           <section class="content-header">          
			  <ol class="breadcrumb">
				<li><a href="#"><i class="fa fa-dashboard"></i>Home</a></li>
				<li class="">PNS</li>
				<li class="active">Search PNS</li>
			  </ol>
			</section>
			
            
        </section>
		
	    <!-- Main content -->
        <section class="content ">
		   
			<div class="row">
		        <div class="col-md-12">
					<div class="box box-primary">
						<div class="box-header with-border">
						  <h3 class="box-title">Search PNS by Name</h3>
						</div><!-- /.box-header -->
						<form class="form-horizontal" method="post" action="<?php echo site_url()?>/asn/dosearch" role="form">
						<div class="box-body">
						    <div class="row">
								<div class="col-md-12">
									<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" style="display: none">
									<div class="form-group" style="display:inline;">
									  <div class="input-group" style="display:table;">
										<span class="input-group-addon" style="width:1%;"><span class="fa fa-search"></span></span>
										<input type="text" class="form-control" name="nama" placeholder="Masukan Nama..." autocomplete="on" autofocus="autofocus">
									  </div>
									  <span class="help-block text-red"><?php echo form_error('nama'); ?></span>
									</div>									
								</div>	
							</div>					
					    </div>
						</form>	
						<hr/>
						<?php if($show):?>
						<div class="table-responsive">
							<table class="table table-striped">
								<thead>
									<tr>
										<th>NO</th>
										<th>NIP</th>									
										<th>NAMA</th>
										<th>INSTANSI</th>								
									</tr>
								</thead>
								<tbody>
									<?php if($pns->num_rows() > 0):?>
								    <?php  $i=1;foreach($pns->result() as $value):?>
									<tr>
										<td><?php echo $i?></td>
										<td><?php echo $value->PNS_NIPBARU?></td>
										<td><?php echo $value->PNS_PNSNAM?></td>
										<td><?php echo $value->INS_NAMINS?></td>
									</tr>
									<?php $i++;endforeach;?>
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
	
	
	
	<script src="<?php echo base_url()?>assets/plugins/jQuery/jQuery-2.1.4.min.js"></script>    
    <script src="<?php echo base_url()?>assets/bootstrap/js/bootstrap.min.js"></script> 
    <script src="<?php echo base_url()?>assets/dist/js/app.min.js"></script>
	</body>
</html>
