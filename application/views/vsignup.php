<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Aplikasi Male_o 1.9 | Registration Page</title>
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
        <img class="img-circle image-register" src="<?php echo base_url()?>assets/dist/img/maleo.png" alt="Logo Male_o 1.9">
    	<h1 class="text-yellow text-center" style="margin-top: 0px;">Male_o 1.9</h1>
    			  
      </div>
	  <div class="box box-warning">                 
                <!-- form start -->
                <form class="form-horizontal" method="post" action="<?php echo site_url()?>/register/doReg">
                  <div class="box-body">
				    <?php echo $message;?>
					<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" style="display: none">
				    <div class="post text-yellow">Personal Information :</div> 
                    <div class="form-group row">
						<label class="col-md-1 col-sm-1 control-label" >Firstname</label>
						<div class="col-md-5 col-sm-5">
							<input type="text" class="form-control" placeholder="Firstname" name="fname" readonly value="<?php echo set_value('fname'); ?>" />
						    <span class="help-block text-red"><?php echo form_error('fname'); ?></span>
						</div>

						<label class="col-md-1 col-sm-1 control-label">Lastname</label>
							<div class="col-sm-5">
							<input type="text" class="form-control" placeholder="Lastname" name="lname" value="<?php echo set_value('lname'); ?>" readonly />
							<span class="help-block text-red"><?php echo form_error('lname'); ?></span>
						</div>
                    </div>
					<div class="form-group row">
						<label class="col-md-1 col-sm-1 control-label">Instansi</label>
						<div class="col-md-5 col-sm-5">				    
							<select class="form-control" name="instansi"  >
							<option value="">--Silahkan Pilih--</option>
							<?php foreach($instansi->result() as $value):?>
							<option value="<?php echo $value->INS_KODINS?>" <?php echo set_select('instansi', $value->INS_KODINS); ?>><?php echo $value->INS_NAMINS?></option>
							<?php endforeach;?>
							<span class="help-block text-red"><?php echo form_error('instansi'); ?></span>
							</select>
						</div>
						<label class="col-md-1 col-sm-1 control-label">Jabatan</label>
						<div class="col-md-5 col-sm-5">
							<input type="text" class="form-control" placeholder="Jabatan" name="jabatan" value="<?php echo set_value('jabatan'); ?>"  />
						    <span class="help-block text-red"><?php echo form_error('jabatan'); ?></span>
						</div>
                    </div>
					
					<div class="form-group row">
						<label class="col-md-1  col-sm-1 control-label">Layanan</label>
						<div class="col-md-5 col-sm-5">				    
							<select class="form-control" name="bidang" >
							<option value="">--Silahkan Pilih--</option>
							<?php foreach($unit_kerja->result() as $value):?>
							<option value="<?php echo $value->id_bidang?>" <?php echo  set_select('bidang', $value->id_bidang); ?>><?php echo $value->nama_unit?></option>
							<?php endforeach;?>
							</select>
							<span class="help-block text-red"><?php echo form_error('bidang'); ?></span>
						</div>
						<label class="col-md-1 col-sm-1 control-label">Email</label>
						<div class="col-md-5 col-sm-5">
						    <input type="text" class="form-control" placeholder="email" name="email" value="<?php echo set_value('email'); ?>"  />
							<span class="help-block text-red"><?php echo form_error('email'); ?></span>
						</div>
                    </div>
                    
					<div class="form-group row">
						<label class="col-md-1 col-sm-1 control-label">Sex</label>
						<div class="col-md-5 col-sm-5">
							<input type="radio" value="L" name="sex"  <?php echo  set_radio('sex', 'L');?> /> Male
							<input type="radio" value="P" name="sex"  <?php echo  set_radio('sex', 'P');?>/> Female
							<span class="help-block text-red"><?php echo form_error('sex'); ?></span>
						</div>
                    
						<label class="col-md-1 col-sm-1 control-label">Area</label>
						<div class="col-md-5 col-sm-5">
							<select class="form-control" name="area">
								<option value="">--pilih--</option>
								<option value="70" <?php echo set_select('area', 70); ?> >Sulawesi Utara</option>
								<option value="71" <?php echo set_select('area', 71); ?> >Gorontalo</option>
								<option value="79" <?php echo set_select('area', 79); ?>>Maluku Utara</option>
							</select>
							<span class="help-block text-red"><?php echo form_error('area'); ?></span>
						</div>	
					</div>
					
					<div class="post text-yellow">Login Information :</div> 
						<div class="form-group row">
						  <label class="col-md-1 col-sm-1 control-label">Username</label>
						  <div class="col-md-5 col-sm-5">
							<select id="username" name="username" class="form-control"  >
							</select>
							<span class="help-block text-red"><?php echo form_error('username'); ?></span>
						  </div>
						  <label class="col-md-1 col-sm-1 control-label">Password</label>
						  <div class="col-md-5 col-sm-5">
							<input type="password" class="form-control" placeholder="Password" name="password" value="<?php echo set_value('password'); ?>"  readonly />
							<span class="help-block text-red"><?php echo form_error('password'); ?></span>
						</div>
						<label class="col-md-12 col-sm-12 control-label text-yellow">- Default Password akan otomatis diset dengan NIP</label>						
                    </div>
					
                  </div><!-- /.box-body -->
                  <div class="box-footer">
                     <a href="<?php echo site_url()?>/autho/" class="text-yellow">I already have a membership >></a>
                    <button type="submit" class="btn btn-warning pull-right">Register</button>
                  </div><!-- /.box-footer -->
                </form>
              </div><!-- /.box -->
    </div><!-- /.box -->

      
   
    <script src="<?php echo base_url()?>assets/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <script src="<?php echo base_url()?>assets/bootstrap/js/bootstrap.min.js"></script>
	<script src="<?php echo base_url()?>assets/plugins/select2/select2.full.min.js"></script>
	<script>
      $(function () {
        $("#username").select2({
			placeholder: '-Masukan NIP-',
		    minimumInputLength: 17,
    	    ajax: {
				url:  '<?php echo site_url() ?>'+'/register/getPns',
				dataType:'json',
				type:'GET',
				cache: "true",
				delay: 250,	
                
			},
			results: function(data, page) {
			    return { results: data.results };               
            }  
		});
		
		$("#username").change(function(){
		    myApp.showPleaseWait();
			$('.progress-bar').css('width', '' + 0 + '%');
			$('.progress-bar').text( 0 + '% Complete' );
			$('.progress-bar').attr("aria-valuenow", 0);
			
			$.ajax({
			    url: "<?php echo site_url()?>/register/getPnsdata",
				dataType:'json',
				type:'GET',
				data:{q:this.value},
				success: function(result){		
                    $("input[name=password]").val(result[0].PNS_NIPBARU);
					$("input[name=fname]").val(result[0].FIRSTNAME);
					$("input[name=lname]").val(result[0].LASTNAME);	
                    $("[name=instansi]").val(result[0].PNS_INSKER);
					$("input[name=sex][value='"+result[0].PNS_PNSSEX+"']").prop("checked",true);
					$("input[name=email]").val(result[0].PNS_EMAIL);
					
					setTimeout(function() {myApp.hidePleaseWait();	} , 1000);					
		        },
				xhr: function() {
						var xhr = new window.XMLHttpRequest();
						xhr.addEventListener('progress', function(e) {
							if (e.lengthComputable) {
							    var value = (100 * e.loaded / e.total);
								$('.progress-bar').css('width', '' + value + '%');
								$('.progress-bar').text( value + '% Complete' );
								$('.progress-bar').attr("aria-valuenow", value);
								
							}
						});
						return xhr;
					}, 
			});			
		  
		});
		
		var myApp;
		myApp = myApp || (function () {
			var pleaseWaitDiv = $('<div class="modal fade" id="pleaseWaitDialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><img src="<?php echo base_url()?>assets/dist/img/load.gif"/><label> Processing...</label></h2></div><div class="modal-body"><div class="progress"><div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div></div></div></div></div>');
			return {
				showPleaseWait: function() {
					pleaseWaitDiv.modal('show');
				},
				hidePleaseWait: function () {
					pleaseWaitDiv.modal('hide');
				},	

			};
		})();  
		
	  });
    </script>	  
    
  </body>
</html>
