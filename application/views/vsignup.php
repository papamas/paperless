<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Aplikasi Tata Naskah Kepegawaian | Registration Page</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="<?php echo base_url()?>assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url()?>assets/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo base_url()?>assets/dist/css/AdminLTE.css">
	<link rel="stylesheet" href="<?php echo base_url()?>assets/plugins/select2/select2.min.css">
    <link rel="shortcut icon" href="<?php echo base_url()?>assets/dist/img/favicon_garuda.ico">
    
  </head>
  <body class="hold-transition register-page">
    <div class="register-box">
      <div class="register-logo">
       <img src="<?php echo base_url()?>assets/dist/img/logo-garuda.png" class="image-register"/>
		<p class="text-blue hidden-xs"  style="font-size:20px">Aplikasi Tata Naskah Kepegawaian<br/>
		BKN Kantor Regional XI Manado</p>
      </div>
	  <div class="box box-info">
                 
                <!-- form start -->
                <form class="form-horizontal" method="post" action="<?php echo site_url()?>/signup/register/">
                  <div class="box-body">
				    <?php echo $message;?>
				    <div class="post text-green">Personal Information :</div> 
                    <div class="form-group">
                      <label class="col-sm-3 control-label" >Firstname</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control" placeholder="Firstname" name="firstname" required />
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-3 control-label">Lastname</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control" placeholder="Lastname" name="lastname" required />
                      </div>
                    </div>
					<div class="form-group">
                      <label class="col-sm-3 control-label">Gender</label>
                      <div class="col-sm-9">
                        <input type="radio" value="L" name="gender" required /> Male
						<input type="radio" value="P" name="gender" required /> Female
                      </div>
                    </div>
					<div class="form-group">
                      <label class="col-sm-3 control-label">Unit Kerja</label>
                      <div class="col-sm-9">				    
					    <select class="form-control" name="unit_kerja" required >
						<option value="">--Silahkan Pilih--</option>
                        <?php foreach($unit_kerja->result() as $value):?>
						<option value="<?php echo $value->id_unit?>"><?php echo $value->nama_unit?></option>
						<?php endforeach;?>
						</select>
                      </div>
                    </div>
					<div class="form-group">
                      <label class="col-sm-3 control-label">Jabatan</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control" placeholder="Jabatan" name="jabatan" required />
                      </div>
                    </div>
					<div class="post clear-fix text-green">Login Information :</div> 
                     <div class="form-group">
                      <label class="col-sm-3 control-label">Username</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control" placeholder="Username" name="username" required />
                      </div>
                    </div>
					<div class="form-group">
                      <label class="col-sm-3 control-label">Password</label>
                      <div class="col-sm-9">
                        <input type="password" class="form-control" placeholder="Password" name="password" required />
                      </div>
                    </div>
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                     <a href="<?php echo site_url()?>/autho/" class="text-center">I already have a membership >></a>
                    <button type="submit" class="btn btn-info pull-right">Register</button>
                  </div><!-- /.box-footer -->
                </form>
              </div><!-- /.box -->

      
    </div><!-- /.register-box -->   
    <script src="<?php echo base_url()?>assets/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <script src="<?php echo base_url()?>assets/bootstrap/js/bootstrap.min.js"></script>
	<script src="<?php echo base_url()?>assets/plugins/select2/select2.full.min.js"></script>
	<script>
      $(function () {
        //Initialize Select2 Elements
        $(".select2").select2();
	  });
    </script>	  
    
  </body>
</html>
