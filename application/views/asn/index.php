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
				<li class="active">Profile PNS</li>
			  </ol>
			</section>
			
            
        </section>
		
	    <!-- Main content -->
        <section class="content ">
		   
			<div class="row">
		        <div class="col-md-12">
					<div class="box box-primary">
						<div class="box-header with-border">
						  <h3 class="box-title">Profile PNS</h3>
						</div><!-- /.box-header -->
						
						<div class="box-body">
						    <div class="row">
								<div class="col-md-12">
									<form class="form-horizontal" method="post" action="<?php echo site_url()?>/asn/find" role="form">
										<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" style="display: none">
										<div class="form-group" style="display:inline;">
										  <div class="input-group" style="display:table;">
											<span class="input-group-addon" style="width:1%;"><span class="fa fa-search"></span></span>
											<input class="form-control" pattern="[0-9]{18}" maxlength="18" required name="nip" placeholder="Masukan NIP..." autocomplete="on" autofocus="autofocus" type="number">
										  </div>
										</div>
									</div>	
								</div>					
							</div>
							<hr/>
							<?php if($this->input->post()):?>
							<div class="row">
								<div class="col-md-12">
									<div class="nav-tabs-custom">
											<ul class="nav nav-tabs">
											
											  <li class="active"> <a href="#data-utama" data-toggle="tab">Data Utama</a></li>
											  <li> <a href="#pengadaan" data-toggle="tab">Penetapan NIP</a></li>                  
											  <li><a href="#pendidikan" data-toggle="tab">Pendidikan</a></li>
											  <li><a href="#unor" data-toggle="tab">Posisi & Jabatan</a></li>
											  <li><a href="#kp" data-toggle="tab">Kenaikan Pangkat</a></li>
											</ul>
											<div class="tab-content">					   
											
											  <div class="active tab-pane" id="data-utama">  
												<?php if(count($pupns) > 0){?>
												<div class="post">
												<?php if($pupns->PNS_PNSSEX == 1) {$avatar = 'avatar5.png';}else{$avatar = 'avatar3.png';}?>
													<div class="box box-widget widget-user-2">
													<!-- Add the bg color to the header using any of the bg-* classes -->
													<div class="widget-user-header bg-blue">
														  <div class="widget-user-image">
															<img class="img-circle" src="<?php echo base_url()?>assets/dist/img/<?php echo $avatar;?>" alt="User Avatar">
														  </div><!-- /.widget-user-image -->
														  <h3 class="widget-user-username"><?php echo $pupns->PNS_PNSNAM?></h3>
														  <h5 class="widget-user-desc">NIP. <?php echo $pupns->PNS_NIPBARU?></h5>
													</div>
														 <div class="box-footer no-padding">
															<ul class="nav nav-stacked">
															<li><a href="#">Gelar Depan<span class="pull-right"><?php echo $pupns->PNS_GLRDPN?></span></a></li>
															<li><a href="#">Gelar Belakang<span class="pull-right"><?php echo $pupns->PNS_GLRBLK?></span></a></li>
															<li><a href="#">Status Hukum<span class="pull-right"><?php echo $pupns->KED_KEDNAM?></span></a></li>
															<li><a href="#">Pangkat / Golongan<span class="pull-right"><?php echo $pupns->GOL_PKTNAM?> /  <?php echo $pupns->GOL_GOLNAM?></span></a></li>
															<li><a href="#">TMT Golongan<span class="pull-right"><?php echo $pupns->TMTGOL?></span></a></li>
															<li><a href="#">Pendidikan Terakhir<span class="pull-right"><?php echo $pupns->DIK_NAMDIK?></span></a></li>
															<li><a href="#">Golongan Awal<span class="pull-right"><?php echo $pupns->GOL_AWAL?></span></a></li>
															<li><a href="#">TMT CPNS<span class="pull-right"><?php echo $pupns->CPNS?></span></a></li>
															<li><a href="#">TMT PNS</b> <span class="pull-right"><?php echo $pupns->PNS?></span></a></li>
															<li><a href="#">Jenis Pegawai</b> <span class="pull-right"><?php echo $pupns->JPG_JPGNAM?></span></a></li>
															<li><a href="#">Jenis Jabatan</b> <span class="pull-right"><?php echo $pupns->JJB_JJBNAM?></span></a></li>
															<li><a href="#">Instansi Kerja</b> <span class="pull-right"><?php echo $pupns->INSKER?></span></a></li>						
														  </ul>
														</div>
													</div>		
												</div>
												<?php } else { ?>
												<div class="callout callout-warning">
												  <h4>Warning!</h4>
												  <p>Oops ! sory data not found.</p> <p>We could not find data you were looking for.</p>
												</div>
												<?php } ?>
												</div><!-- /.tab-pane -->
											  
											  <div class="tab-pane" id="pendidikan">
												<?php if($pendidikan->num_rows() > 0) {?>
												  <ul class="timeline timeline-inverse">                      
												  <?php foreach($pendidikan->result() as $value):?>
												  <li class="time-label">
													<span class="bg-blue">
													  Tahun <?php echo $value->PEN_TAHLUL;?>
													</span>
												  </li>
												  <li>
													<i class="fa fa-fw fa-graduation-cap bg-aqua"></i>
													<div class="timeline-item">
													  <h3 class="timeline-header no-border"><a href="#"><?php echo $value->DIK_NMDIK?></a> </h3>
													</div>
												  </li>
												  <?php endforeach;?>					  
												</ul>
												<?php } else {?>
												<div class="callout callout-warning">
												  <h4>Warning!</h4>
												  <p>Oops ! sory data not found.</p> <p>We could not find data you were looking for.</p>
												</div>
												<?php }?>
											  </div><!-- /.tab-pane -->
											  <div class="tab-pane" id="pengadaan">
												<?php if(count($pengadaan) > 0) {?>
												<div class="box box-widget widget-user-2">
												<div class="box-footer no-padding">								
												  <ul class="nav nav-stacked">
													<li><a href="#">Jabatan<span class="pull-right"><?php echo $pengadaan->JABATAN_NAMA?></span></a></li>						
													<li><a href="#">Unit Kerja<span class="pull-right"><?php echo $pengadaan->UNIT_KERJA_NAMA?></span></a></li>						
													<li><a href="#">Ijazah<span class="pull-right"><?php echo $pengadaan->IJASAH_NAMA?></span></a></li>
													<li><a href="#">Tahun Ijazah<span class="pull-right"><?php echo $pengadaan->TAHUN_IJAZAH?></span></a></li>
													<li><a href="#">TMT CPNS<span class="pull-right"><?php echo $pengadaan->CPNS?></span></a></li>
													<li><a href="#">Persetujuan Teknis<span class="pull-right"><?php echo $pengadaan->PERSETUJUAN_TEKNIS_NOMOR?></span></a></li>
													<li><a href="#">Tanggal Teknis<span class="pull-right"><?php echo $pengadaan->TANGGAL_TEKNIS?></span></a></li>
													<li><a href="#">Tanggal Penetepan<span class="pull-right"><?php echo $pengadaan->TANGGAL_PENETAPAN?></span></a></li>
												  </ul>
												</div> 
												</div>					
												<?php } else {?>
												<div class="callout callout-warning">
												  <h4>Warning!</h4>
												  <p>Oops ! sory data not found.</p> <p>We could not find data you were looking for.</p>
												 </div>
												 <?php }?> 
											  </div><!-- /.tab-pane -->
											  

											  <div class="tab-pane" id="unor">
												<?php if(count($unor) > 0) {?>
												<div class="box box-widget widget-user-2">
												<div class="box-footer no-padding">								
												  <ul class="nav nav-stacked">
													<li><a href="#">Jabatan<span class="pull-right"><?php echo $unor->JBF_NAMJAB?></span></a></li>						
													<li><a href="#">Unit Organisasi<span class="pull-right"><?php echo $unor->UNO_NAMUNO?></span></a></li>						
													<li><a href="#">Unit Organisasi Induk<span class="pull-right"><?php echo $unor->UNO_INDUK?></span></a></li>						
												  </ul>
												</div> 
												</div>					
												<?php } else {?>
												<div class="callout callout-warning">
												  <h4>Warning!</h4>
												  <p>Oops ! sory data not found.</p> <p>We could not find data you were looking for.</p>
												 </div>
												 <?php }?> 
											  </div><!-- /.tab-pane -->
											  
											   <div class=" tab-pane" id="kp">  
												   <?php if($kp->num_rows() > 0) {?>
													  <ul class="timeline timeline-inverse">                      
													  <?php foreach($kp->result() as $value):?>
													  <li class="time-label">
														<span class="bg-yellow">
														  TMT <?php echo $value->PKI_TMT_GOLONGAN_BARU;?>
														</span>
													  </li>
													  <li>
														<i class="fa fa-trophy bg-aqua"></i>
														<div class="timeline-item">
														   <span class="time"><i class="fa fa-calendar-o"></i> Tanggal. <?php echo $value->TGL_NOTA_PERSETUJUAN_KP ?></span>
														  <h3 class="timeline-header"><a href="#"><?php echo $value->GOL_LAMA?> - <?php echo $value->GOL_BARU?></a> </h3>
															<div class="timeline-body">Jenis Kenaikan Pangkat : <?php echo $value->JKP_JPNNAMA?><br/>
															 Nomor. <?php echo $value->NOTA_PERSETUJUAN_KP?> 
															</div>
														</div>
													  </li>
													  <?php endforeach;?>					  
													</ul>
													<?php } else {?>
													<div class="callout callout-warning">
													  <h4>Warning!</h4>
													  <p>Oops ! sory data not found.</p> <p>We could not find data you were looking for.</p>
													</div>
													<?php }?>
												</div><!-- /.tab-pane -->
											</div><!-- /.tab-content -->
										</div><!-- /.nav-tabs-custom -->
									</div><!-- /.col -->
								</div>	
								<?php endif;?>
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
	</body>
</html>
