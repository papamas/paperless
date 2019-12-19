<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Document Management System | Search</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="Shortcut icon" href="<?php echo base_url()?>assets/dist/img/favicon_garuda.ico" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="<?php echo base_url()?>assets/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo base_url()?>assets/font-awesome/css/font-awesome-animation.css">  	
    <link rel="stylesheet" href="<?php echo base_url()?>assets/dist/css/AdminLTE.css">
    <link rel="stylesheet" href="<?php echo base_url()?>assets/dist/css/skins/_all-skins.min.css">
	<link rel="stylesheet" href="<?php echo base_url()?>assets/dist/css/tree.css">   
  </head>
  <body class="skin-yellow layout-top-nav">
    <div class="wrapper">
	<header class="main-header">
        <nav class="navbar navbar-static-top" >
	    <div class="container">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                <i class="fa fa-bars"></i>
              </button>
            </div>	   
			
			<div class="navbar-custom-menu"> 
			 <ul class="nav navbar-nav">
				<li class="dropdown user user-menu">
					<!-- Menu Toggle Button -->
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
					  <!-- The user image in the navbar-->
					  <img src="<?php echo base_url()?>assets/dist/img/<?php echo $avatar?>" class="user-image" alt="User Image">					  
					  <!-- hidden-xs hides the username on small devices so only the image appears. -->
					  <i class="fa fa-circle text-success faa-flash animated" title="online"></i><span class="hidden-xs "><?php echo $lname?> <br/>
					</a>
					<ul class="dropdown-menu">
					  <!-- The user image in the menu -->
					  <li class="user-header">
						<img src="<?php echo base_url()?>assets/dist/img/<?php echo $avatar?>" class="img-circle" alt="User Image">
						<p >
						  <?php echo $name?> <br/> <?php echo $jabatan?>
						  <small>Member since -  <?php echo $member;?></small>
						</p>
						
					  </li>
					  <!-- Menu Body -->
					  <li class="user-body">
						<div class="col-xs-4 text-center">
						  <a href="#">Followers</a>
						</div>
						<div class="col-xs-4 text-center">
						  <a href="#">Sales</a>
						</div>
						<div class="col-xs-4 text-center">
						  <a href="#">Friends</a>
						</div>
					  </li>
					  <!-- Menu Footer-->
					  <li class="user-footer">
						<div class="pull-left">
						  <a href="#" class="btn btn-default btn-flat">Profile</a>
						</div>
						<div class="pull-right">
						  <a href="<?php echo site_url()?>/autho/logout/" class="btn btn-default btn-flat">Sign out</a>
						</div>
					  </li>
					</ul>
				  </li>
		    </ul>	
			</div>
            		
			<div class="collapse navbar-collapse" id="navbar-collapse">			
			<form class="navbar-form"  method="post" action="<?php echo site_url()?>/dms/search">
				<div class="form-group" style="display:inline;">
				  <div class="input-group" style="display:table;">
					<span class="input-group-addon" style="width:1%;"><span class="fa fa-search"></span></span>
					<input class="form-control" pattern="[0-9]{18}" maxlength="18" required name="nip" placeholder="Masukan NIP..." autocomplete="off" autofocus="autofocus" type="text">
				  </div>
				</div>
			 </form>			
			</div><!--/.nav-collapse --> 
			
		</div><!-- container-->			
		</nav>
	</header>
	
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper"> 
	  <?php if($this->input->post()):?>
	  <!-- Main content -->
        <section class="content">
          <div class="row">
		    <div class="col-md-3">
			    <div class="box box-primary">
				    <div class="box-header with-border">
				    <h3 class="box-title">File Browser</h3>
					</div>
					<?php if($children == "<ul class='tree'></ul>"){?>
					<div class="callout callout-warning">
					  <h4>Warning!</h4>
					  <p>Oops ! sory file not found.</p><p> We could not find file you were looking for.</p>
					</div>
					<?php }else {?>
					<div class="box-body" style="max-height:500px;overflow-y:scroll">
					   
					   <?php echo $children;?>		
                        				   
					</div>
					<?php }?>
					
				</div>
			</div>
            <div class="col-md-9">
              <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
				  <li class="active"> <a href="#file-browser" data-toggle="tab">File Preview</a></li>
                  <li> <a href="#data-utama" data-toggle="tab">Data Utama</a></li>
                  <li><a href="#pendidikan" data-toggle="tab">Pendidikan</a></li>
                  <li><a href="#unor" data-toggle="tab">Posisi & Jabatan</a></li>
				  <li><a href="#kp" data-toggle="tab">Kenaikan Pangkat</a></li>
                </ul>
                <div class="tab-content">
				    <div class="active tab-pane file-browser" id="file-browser">	                            					
							<div style="height:750px">							  
							    <?php if($children == "<ul class='tree'></ul>"){?>
							    <div class="callout callout-warning">
								  <h4>Warning!</h4>
								  <p>Oops ! sory file not found.</p> <p>We could not find file you were looking for.</p>
								</div>
								<?php } else { ?>
								<div class="callout callout-info" id="tips">
								  <h4>Tips!</h4>
								  <p>Select file on left to preview.</p> 
								</div>
								<iframe   id="frame" style="width:100%;height:100%;border:0" ></iframe>								
								<?php } ?>	
                                								
							</div>
						 	
						<div class="overlay" id="overlay" style="display:none;"><i class="fa fa-refresh fa-spin fa-file-pdf-o"></i></div>	
					</div>
				
                  <div class=" tab-pane" id="data-utama">  
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
          </div><!-- /.row -->
        </section><!-- /.content -->
		 <?php endif;?>
      </div><!-- /.content-wrapper -->     
    </div><!-- ./wrapper -->
    <script src="<?php echo base_url()?>assets/plugins/jQuery/jQuery-2.1.4.min.js"></script>    
    <script src="<?php echo base_url()?>assets/bootstrap/js/bootstrap.min.js"></script> 
    <script src="<?php echo base_url()?>assets/plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="<?php echo base_url()?>assets/plugins/fastclick/fastclick.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?php echo base_url()?>assets/dist/js/app.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="<?php echo base_url()?>assets/dist/js/demo.js"></script>	
	<script>	
	$(document).ready(function () {		   
		$('.file-preview').on('click',function() {			    
			var uuid = this.id;
			var iframe = $('#frame');
			$('#overlay').show();
			$('#tips').hide();
		    iframe.attr('src', '<?php echo site_url()?>'+'/dms/getContent/'+ uuid);			
	    });
		
		$('#frame').load(function() {
		    $('#overlay').hide();
		});
	});	
   </script>
  </body>
</html>
