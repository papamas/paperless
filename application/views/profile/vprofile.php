<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Profile</title>
    <?php echo $this->load->view('vheader');?>
	<link rel="stylesheet" href="<?php echo base_url()?>assets/dist/css/tree.css">   
	<link rel="stylesheet" href="<?php echo base_url()?>assets/dist/css/custom.css">   
	<link rel="stylesheet" href="<?php echo base_url()?>assets/plugins/select2/select2.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/plugins/daterange/daterangepicker-bs3.css" />	
  </head>
  <body class="hold-transition skin-yellow">
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
          <h1>
            User Profile
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Dashboard</a></li>
            <li class="active">User profile</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">

          <div class="row">
            <div class="col-md-3">
              <!-- Profile Image -->
              <div class="box box-primary">
                <div class="box-body box-profile">
                  <img class="profile-user-img img-responsive img-circle" src="<?php echo base_url()?>assets/dist/img/<?php echo $avatar?>" alt="User profile picture">
                  <h3 class="profile-username text-center"><?php echo $name?></h3>
                  <p class="text-muted text-center"><?php echo $jabatan?></p>

                  <ul class="list-group list-group-unbordered">
                    <li class="list-group-item">
                      <b>Followers</b> <a class="pull-right">1,322</a>
                    </li>
                    <li class="list-group-item">
                      <b>Following</b> <a class="pull-right">543</a>
                    </li>
                    <li class="list-group-item">
                      <b>Friends</b> <a class="pull-right">13,287</a>
                    </li>
                  </ul>

                  <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a>
                </div><!-- /.box-body -->
              </div><!-- /.box -->

              <!-- About Me Box -->
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">About Me</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <strong><i class="fa fa-book margin-r-5"></i>  Education</strong>
                  <p class="text-muted">
                    B.S. in Computer Science from the University of Tennessee at Knoxville
                  </p>

                  <hr>

                  <strong><i class="fa fa-map-marker margin-r-5"></i> Location</strong>
                  <p class="text-muted">Malibu, California</p>

                  <hr>

                  <strong><i class="fa fa-pencil margin-r-5"></i> Skills</strong>
                  <p>
                    <span class="label label-danger">UI Design</span>
                    <span class="label label-success">Coding</span>
                    <span class="label label-info">Javascript</span>
                    <span class="label label-warning">PHP</span>
                    <span class="label label-primary">Node.js</span>
                  </p>

                  <hr>

                  <strong><i class="fa fa-file-text-o margin-r-5"></i> Notes</strong>
                  <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam fermentum enim neque.</p>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
            <div class="col-md-9">
              <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                  <li class="<?php echo $tab_setting?>" ><a href="#settings" data-toggle="tab">Settings</a></li>
				  <li class="<?php echo $tab_change_password?>" ><a href="#change-password" data-toggle="tab">Change Password</a></li>
				  <li class="<?php echo $tab_activity?> " ><a href="#activity" data-toggle="tab">Activity</a></li>                  
                </ul>
                <div class="tab-content">
                  <div class="tab-pane" id="activity">
                    <p class="text-info">Under Development</p>
                  </div><!-- /.tab-pane -->
				  <div class="<?php echo $tab_change_password?> tab-pane" id="change-password">
                    <?php echo $message?>
					
					<form class="form-horizontal" method="post" action="<?php echo site_url()?>/profile/changePassword/">
					  <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" style="display: none">
					  <div class="form-group">
                        <label class="col-sm-3 control-label">Current Password</label>
                        <div class="col-sm-9">
                          <input type="text" name="currentPassword" class="form-control"  value="" required />
						  <p class="help-block"><?php echo form_error('currentPassword'); ?></p>
                        </div>
                      </div>
					  <div class="form-group">
                        <label class="col-sm-3 control-label">New Password</label>
                        <div class="col-sm-9">
                          <input type="password" name="newPassword" class="form-control"  value="" required />
						  <p class="help-block"><?php echo form_error('newPassword'); ?></p>
                        </div>
                      </div>
					  <div class="form-group">
                        <label class="col-sm-3 control-label">Confirmation Password</label>
                        <div class="col-sm-9">
                          <input type="password" name="retypePassword" class="form-control"  value="" required />
						    <p class="help-block"><?php echo form_error('retypePassword'); ?></p>
                        </div>
                      </div>
					  <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-9">
                          <button type="submit" class="btn btn-danger">Submit</button>
                        </div>
                      </div>
					</form>  
                  </div><!-- /.tab-pane -->

                  <div class="<?php echo $tab_setting?> tab-pane" id="settings">
				     <?php echo $message?>
                    <form class="form-horizontal" method="post" action="<?php echo site_url()?>/profile/setting/">
					  <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" style="display: none">
					  <div class="form-group">
                        <label class="col-sm-2 control-label">Username</label>
                        <div class="col-sm-10">
                          <input type="text" disabled class="form-control"  value="<?php echo $profile->username?>">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label">Firstname</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control"  name="first_name" value="<?php echo $profile->first_name?>" required>
                        </div>
                      </div>
					  <div class="form-group">
                        <label class="col-sm-2 control-label">Lastname</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" name="last_name" value="<?php echo $profile->last_name?>" required>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-2 control-label">Email</label>
                        <div class="col-sm-10">
                          <input type="email" class="form-control" name="email" placeholder="Email" value="<?php echo $profile->email?>">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="inputName" class="col-sm-2 control-label">Gender</label>
                        <div class="col-sm-10">
                          <input type="radio" value="L" name="gender" <?php if($profile->gender == 'L') echo 'checked';?> required /> Male
						  <input type="radio" value="P" name="gender" <?php if($profile->gender == 'P') echo 'checked';?> required /> Female
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="inputExperience" class="col-sm-2 control-label">Status</label>
                        <div class="col-sm-10">
                           <input type="checkbox" <?php if($profile->active == '1') echo 'checked';?> disabled /> 
                        </div>
                      </div>  
						<div class="form-group">
							  <label class="col-sm-2">Instansi</label>
							  <div class="col-sm-10">
							    <?php if($this->session->userdata('user_id') === 1):?>
								<select name="instansi" class="form-control">
									<?php if($instansi->num_rows() > 0):?>
									<?php foreach($instansi->result() as $value):?>
									<option value="<?php echo $value->INS_KODINS?>" <?php if($value->INS_KODINS == $profile->id_instansi) echo 'selected';?>><?php echo $value->INS_NAMINS?></option>
									<?php endforeach;?>
									<?php endif;?>
								</select>
								<?php endif;?>
								 <input type="text" class="form-control" value="<?php echo $profile->instansi?>">
							  </div>	
							</div>
                      <div class="form-group">
						  <label class="col-sm-2 control-label">Unit Kerja</label>
						  <div class="col-sm-10">				    
							 <?php if($this->session->userdata('user_id') === 1):?>
							 <select class="form-control" name="unit_kerja" required style="width:100%">								
							<?php foreach($unit_kerja->result() as $value):?>
							<option value="<?php echo $value->id_unit?>" <?php if($value->id_unit == $profile->id_bidang) echo 'selected';?>><?php echo $value->nama_unit?></option>
							<?php endforeach;?>
							</select>
							<?php endif;?>
							<input type="text" class="form-control" value="<?php echo $profile->nama_unit?>">
						  </div>
					  </div>
					  <div class="form-group">
                        <label class="col-sm-2 control-label">Jabatan</label>
                        <div class="col-sm-10">
                          <input type="text" name="jabatan" class="form-control" value="<?php echo $profile->jabatan?>" required />
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                          <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                      </div>
                    </form>					
				    </div>
                  </div><!-- /.tab-pane -->
				  
				  
                </div><!-- /.tab-content -->
              </div><!-- /.nav-tabs-custom -->
            </div><!-- /.col -->
          </div><!-- /.row -->

        </section><!-- /.content -->
       
        </div><!-- /.content-wrapper -->     
    </div><!-- ./wrapper -->
    <?php $this->load->view('vfooter-js');?>
    <script src="<?php echo base_url()?>assets/plugins/select2/select2.full.min.js"></script>	
	<script>
      $(function () {
        //Initialize Select2 Elements
        $(".select2").select2();
	  });
    </script>	
  </body>
</html>
