<!DOCTYPE html>
<html>
 <head>
    <?php  $this->load->view('vheader');?>
	<link rel="stylesheet" href="<?php echo base_url()?>assets/dist/css/tree.css">   
	<link rel="stylesheet" href="<?php echo base_url()?>assets/dist/css/custom.css"> 
	<link rel="stylesheet" href="<?php echo base_url()?>assets/plugins/select2/select2.min.css">
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
           <section class="content-header">          
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i>Home</a></li>
            <li class="">Settings</li>
			<li class="active">User</li>
          </ol>
        </section>
			
          </ol>
        </section>
		
	    <!-- Main content -->
        <section class="content ">
		    <div class="row">
		        <div class="col-md-12">
					<div class="box box-primary">
						<div class="box-header with-border">
						  <h3 class="box-title">Perekaman Data User</h3>
						</div><!-- /.box-header -->
						<!-- form start -->
						<form class="form-horizontal" method="post" action="<?php echo site_url()?>/user/setUser" role="form">
						  <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" style="display: none">
						  <input type="hidden" name="user_id" />
						  <div class="box-body">
							<?php echo $message;?>
							<div class="form-group">
							  <label class="col-sm-2">Instansi</label>
							  <div class="col-sm-10">
							    <select class="form-control" name="instansi"  >
								<option value="">--Silahkan Pilih--</option>
								<?php foreach($instansi->result() as $value):?>
								<option value="<?php echo $value->INS_KODINS?>" <?php echo set_select('instansi', $value->INS_KODINS); ?>><?php echo $value->INS_NAMINS?></option>
								<?php endforeach;?>
								</select>
								<span class="help-block text-red"><?php echo form_error('instansi'); ?></span>

							  </div>	
							</div>
							<div class="form-group">
							    <label  class="col-sm-2">Firstname</label>
							    <div class="col-sm-4">
							        <input type="text" class="form-control" name="fname" value="<?php echo set_value('fname'); ?>"/>
								    <span class="help-block text-red"><?php echo form_error('fname'); ?></span>
							    </div>
                                <label  class="col-sm-2">Lastname</label>
							    <div class="col-sm-4">
							       <input type="text" class="form-control" name="lname" value="<?php echo set_value('lname'); ?>"/>
								    <span class="help-block text-red"><?php echo form_error('lname'); ?></span>
							    </div>							  
							</div>
							<div class="form-group">
							  <label  class="col-sm-2">Jabatan</label>
							    <div class="col-sm-4">
							        <input type="text" class="form-control" name="jabatan" value="<?php echo set_value('jabatan'); ?>"/>
									<span class="help-block text-red"><?php echo form_error('jabatan'); ?></span>
							    </div>
							  <label  class="col-sm-2">Bidang Pelayanan</label>
							  <div class="col-sm-4">
							    <select class="form-control" name="bidang" >
									<option value="">--Silahkan Pilih--</option>
									<?php foreach($unit_kerja->result() as $value):?>
									<option value="<?php echo $value->id_bidang?>" <?php echo  set_select('bidang', $value->id_bidang); ?>><?php echo $value->nama_unit?></option>
									<?php endforeach;?>
								</select>
								<span class="help-block text-red"><?php echo form_error('bidang'); ?></span>
							  </div>	
							</div>							
							<div class="form-group">
							    <label  class="col-sm-2">Username</label>
							    <div class="col-sm-4">
							      <select id="username" name="username" class="form-control"  >
							      </select>
								  <span class="help-block text-red"><?php echo form_error('username'); ?></span>
							    </div>
                             	 <label  class="col-sm-2">NIP</label>
							    <div class="col-sm-4">
							       <input type="text" class="form-control" name="nip" value="<?php echo set_value('nip'); ?>"/>
								  <span class="help-block text-red"><?php echo form_error('nip'); ?></span>
							    </div>					  
							</div>
							<div class="form-group">
							    <label  class="col-sm-2">Email</label>
							    <div class="col-sm-4">
							       <input type="text" class="form-control" name="email" value="<?php echo set_value('email'); ?>"/>
								  <span class="help-block text-red"><?php echo form_error('email'); ?></span>
							    </div>
                             	<label  class="col-sm-2">User Tipe</label>
							  <div class="col-sm-4">
							    <select class="form-control" name="usertipe" >
									<option value="">--Silahkan Pilih--</option>
									<option value="instansi" <?php echo  set_select('usertipe', 'instansi'); ?>>Instansi</option>
									<option value="1" <?php echo  set_select('usertipe', 1); ?>>Kepala Seksi</option>
									<option value="2" <?php echo  set_select('usertipe', 2); ?>>Kepala Bidang</option>
									<option value="3" <?php echo  set_select('usertipe', 3); ?>>Kepala Kantor</option>
								</select>
								<span class="help-block text-red"><?php echo form_error('usertipe'); ?></span>
							  </div>				  
							</div>
							
							<div class="form-group">
							    <label class="col-md-2 col-sm-2 control-label">Sex</label>
								<div class="col-md-4 col-sm-4">
									<input type="radio" value="L" name="sex"  <?php echo  set_radio('sex', 'L');?> /> Male
									<input type="radio" value="P" name="sex"  <?php echo  set_radio('sex', 'P');?>/> Female
									<span class="help-block text-red"><?php echo form_error('sex'); ?></span>
								</div>
							   	<label  class="col-sm-2">Active</label>
							    <div class="col-sm-4">
									<input type="radio"  value="1" name="active" <?php echo  set_radio('active', '1');?> />&nbsp;Aktif
									<input type="radio"  value="2" name="active" <?php echo  set_radio('active', '2');?> />&nbsp;Tidak
									<span class="help-block text-red"><?php echo form_error('active'); ?></span>
								</div>						  
							</div>

							<div class="form-group">
								<label class="col-md-2 control-label">Area</label>
								<div class="col-md-4">
									<select class="form-control" name="area">
										<option value="">--pilih--</option>
										<option value="70" <?php echo set_select('area', 70); ?> >Sulawesi Utara</option>
										<option value="71" <?php echo set_select('area', 71); ?> >Gorontalo</option>
										<option value="79" <?php echo set_select('area', 79); ?>>Maluku Utara</option>
									</select>
									<span class="help-block text-red"><?php echo form_error('area'); ?></span>
								</div>							
							</div>
					
						</div><!-- /.box-body -->

						<div class="box-footer">
							<button type="submit" class="btn btn-primary">Submit</button>
						</div>
						</form>
						<hr/>
						<div class="nav-tabs-custom">
							<ul class="nav nav-tabs">							
							  <li class="active"> <a href="#tab1" data-toggle="tab">New User</a></li>					  
							  <li> <a href="#tab2" data-toggle="tab">User Aplikasi</a></li>					
							</ul>
						</div>	
						<div class="tab-content">	 
							<div class="active tab-pane" id="tab1">	
							    <form class="navbar-form" name="frmTemp" method="POST" action="<?php echo site_url()?>/user/getUserTemp">
								   <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" style="display: none">
									<div class="form-group" style="display:inline;">
									  <div class="input-group" style="display:table;">
										<span class="input-group-addon" style="width:1%;"><span class="fa fa-search"></span></span>
										<input class="form-control"  name="find" placeholder="Masukan NIP" type="text" value="<?php echo set_value('find'); ?>">
									  </div>
									</div>						
								 </form>
								<div class="table-responsive">						
									<table class="table table-bordered table-striped">
										<thead>
											<tr>
												<th>TID</th>
												<th>USERNAME</th>
												<th>FNAME</th>	
												<th>LNAME</th>
												<th>INSTANSI</th>
												<th>BIDANG</th>
												<th>AKSI</th>								
											</tr>
										</thead>   
										<tbody>
											<?php foreach($temp_user->result() as $value):?>
											<tr>
												<td><?php echo $value->user_temp_id?></td>
												<td><?php echo $value->username?></td>
												<td><?php echo $value->first_name?></td>
												<td><?php echo $value->last_name?></td>
												<td><?php echo $value->INS_NAMINS?></td>
												<td><?php echo $value->nama_unit?></td>
												<td>
								                <a href="#" class="btn btn-primary btn-flat btn-xs" data-tooltip="tooltip"  title="Approve" data-toggle="modal" data-target="#approveModal" data-nip="<?php echo $value->nip?>" data-id="<?php echo $value->user_temp_id?>" ><i class="fa fa-check"></i></a>
												</td>
											</tr>
											<?php endforeach;?>
										</tbody>
									</table>							
								</div>
							</div>
							<div class="tab-pane" id="tab2">	
								<div class="table-responsive">
									<form class="navbar-form" name="frmUser" method="POST" action="<?php echo site_url()?>/user/getUser">
									   <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" style="display: none">
										<div class="form-group" style="display:inline;">
										  <div class="input-group" style="display:table;">
											<span class="input-group-addon" style="width:1%;"><span class="fa fa-search"></span></span>
											<input class="form-control"  name="find" placeholder="Masukan NIP" type="text" value="<?php echo set_value('find'); ?>">
										  </div>
										</div>						
									</form>
									<table class="table table-bordered table-striped">
										<thead>								   
											<tr>
												<th>UID</th>
												<th>USERNAME</th>	
												<th>FNAME</th>	
												<th>LNAME</th>	
												<th>INSTANSI</th>
												<th>BIDANG</th>
												<th>AKSI</th>								
											</tr>							
										</thead>   
										<tbody>
											<?php foreach($user->result() as $value):?>
											<tr>
												<td><?php echo $value->user_id?></td>
												<td><?php echo $value->username?></td>
												<td><?php echo $value->first_name?></td>
												<td><?php echo $value->last_name?></td>
												<td><?php echo $value->INS_NAMINS?></td>
												<td><?php echo $value->nama_unit?></td>
												<td style="width:100px;">
										        <a href="#" class="btn btn-warning btn-flat btn-xs" data-tooltip="tooltip"  title="Reset Password" data-toggle="modal" data-target="#resetModal" data-nip="<?php echo $value->nip?>" data-id="<?php echo $value->user_id?>" ><i class="fa fa-refresh"></i></a>
												<a href="#" class="edit btn-primary btn-flat btn-xs" data-tooltip="tooltip"  title="Edit" data-toggle="modal" data-target="#editModal" data-nip="<?php echo $value->nip?>" data-id="<?php echo $value->user_id?>" data-email="<?php echo $value->email?>" data-active="<?php echo $value->active?>" data-fname="<?php echo $value->first_name?>" data-lname="<?php echo $value->last_name?>" data-sex="<?php echo $value->gender?>" data-jabatan="<?php echo $value->jabatan?>" data-instansi="<?php echo $value->id_instansi?>" data-usertipe="<?php echo $value->user_tipe?>" data-bidang="<?php echo $value->id_bidang?>" data-username="<?php echo $value->username?>" data-area="<?php echo $value->area?>"><i class="fa fa-pencil"></i></a>
												<a href="#" class="btn btn-danger btn-flat btn-xs" data-tooltip="tooltip"  title="Drop" data-toggle="modal" data-target="#dropModal" data-nip="<?php echo $value->nip?>" data-id="<?php echo $value->user_id?>" ><i class="fa fa-trash-o"></i></a>

												</td>
											</tr>
											<?php endforeach;?>
										</tbody>
									</table>							
								</div>
							</div>
						</div>	
					</div><!-- /.box -->
				</div>				
			</div>			    
        </section><!-- /.content -->		
      </div><!-- /.content-wrapper -->     
    </div><!-- ./wrapper -->
	
	<div class="modal fade" id="dropModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
					<h4 class="modal-title" id="myModalLabel"><span id="msg"></span></h4>
				</div>
				<div class="modal-body">
					<form id="nfrmDrop">
					  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name()?>" value="<?php echo $this->security->get_csrf_hash()?>" style="display: none">
					  <div class="form-group"> Yakin User ini akan di DROP ?</div>
                       <input type="hidden" name="drop_nip"/>	
					   <input type="hidden" name="drop_user_id"/>					   
					</form>
				 </div>
				<div class="modal-footer">
					<button type="button" class="btn bg-maroon" id="nBtnDrop"><i class="fa fa-trash-o"></i>&nbsp;OK DROP !</button>
				</div>
			</div>
		</div>	
	</div>
	
	<div class="modal fade" id="resetModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
					<h4 class="modal-title" id="myModalLabel"><span id="msg"></span></h4>
				</div>
				<div class="modal-body">
					<form id="nfrmReset">
					  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name()?>" value="<?php echo $this->security->get_csrf_hash()?>" style="display: none">
					  <div class="form-group"> Yakin User ini akan di Reset ?</div>
                       <input type="hidden" name="reset_nip"/>	
					   <input type="hidden" name="reset_user_id"/>					   
					</form>
				 </div>
				<div class="modal-footer">
					<button type="button" class="btn bg-maroon" id="nBtnReset"><i class="fa fa-refresh"></i>&nbsp;OK RESET !</button>
				</div>
			</div>
		</div>	
	</div>
	
	<div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
					<h4 class="modal-title" id="myModalLabel"><span id="msg"></span></h4>
				</div>
				<div class="modal-body">
					<form id="nfrmApprove">
					  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name()?>" value="<?php echo $this->security->get_csrf_hash()?>" style="display: none">
					  <div class="form-group"> Yakin User ini akan di Approve ?</div>
                       <input type="hidden" name="approve_nip"/>	
					   <input type="hidden" name="approve_user_id"/>					   
					</form>
				 </div>
				<div class="modal-footer">
					<button type="button" class="btn bg-maroon" id="nBtnApprove"><i class="fa fa-check"></i>&nbsp;OK APPROVE !</button>
				</div>
			</div>
		</div>	
	</div>
	
	
	
	<script src="<?php echo base_url()?>assets/plugins/jQuery/jQuery-2.1.4.min.js"></script>    
    <script src="<?php echo base_url()?>assets/bootstrap/js/bootstrap.min.js"></script> 
    <script src="<?php echo base_url()?>assets/dist/js/app.min.js"></script>
	<script src="<?php echo base_url()?>assets/plugins/select2/select2.full.min.js"></script>

	<script>	
	$(document).ready(function () {
	    $('[data-tooltip="tooltip"]').tooltip();
		
		$('#approveModal').on('show.bs.modal',function(e){
		     $('#approveModal #msg').text('Konfirmasi Approve User')
			.removeClass( "text-green")
		    .removeClass( "text-blue" ); 
			
			var nip		=  $(e.relatedTarget).attr('data-nip'),
				id    =  $(e.relatedTarget).attr('data-id');
			
			$('#approveModal input[name=approve_nip]').val(nip);
			$('#approveModal input[name=approve_user_id]').val(id);
		});
		
		$("#nBtnApprove").on("click",function(e){
			e.preventDefault();
			var data = $('#nfrmApprove').serialize();
			
			$('#approveModal #msg').text('Processing Please Wait.....')
                     .removeClass( "text-green")
				     .addClass( "text-blue" );  
			
			$.ajax({
				type: "POST",
				url : "<?php echo site_url()?>/user/approveUser",
				data: data,
				success: function(e){
                  	$('#approveModal #msg').text(e.pesan)
						.removeClass( "text-blue")
						.addClass( "text-green" );															 
			    },
				error : function(e){
					$('#approveModal #msg').text(e.responseJSON.pesan)
                             .removeClass( "text-blue")							 
							 .removeClass( "text-green")
				             .addClass( "text-red" ); 
				}	
		    });
			return false;
		});
		
		$('#resetModal').on('show.bs.modal',function(e){
		     $('#resetModal #msg').text('Konfirmasi Reset Password User')
			.removeClass( "text-green")
		    .removeClass( "text-blue" ); 
			
			var nip		=  $(e.relatedTarget).attr('data-nip'),
				id    =  $(e.relatedTarget).attr('data-id');
			
			$('#resetModal input[name=reset_nip]').val(nip);
			$('#resetModal input[name=reset_user_id]').val(id);
		});
		
		$("#nBtnReset").on("click",function(e){
			e.preventDefault();
			var data = $('#nfrmReset').serialize();
			
			$('#resetModal #msg').text('Processing Please Wait.....')
                     .removeClass( "text-green")
				     .addClass( "text-blue" );  
			
			$.ajax({
				type: "POST",
				url : "<?php echo site_url()?>/user/resetUser",
				data: data,
				success: function(e){
                  	$('#resetModal #msg').text(e.pesan)
						.removeClass( "text-blue")
						.addClass( "text-green" );															 
			    },
				error : function(e){
					$('#resetModal #msg').text(e.responseJSON.pesan)
                             .removeClass( "text-blue")							 
							 .removeClass( "text-green")
				             .addClass( "text-red" ); 
				}	
		    });
			return false;
		});
		
		
		
		$('#dropModal').on('show.bs.modal',function(e){
		     $('#dropModal #msg').text('Konfirmasi DROP User')
			.removeClass( "text-green")
		    .removeClass( "text-blue" ); 
			
			var nip		=  $(e.relatedTarget).attr('data-nip'),
				id    =  $(e.relatedTarget).attr('data-id');
			
			$('#dropModal input[name=drop_nip]').val(nip);
			$('#dropModal input[name=drop_user_id]').val(id);
		});
		
		$("#nBtnDrop").on("click",function(e){
			e.preventDefault();
			var data = $('#nfrmDrop').serialize();
			
			$('#dropModal #msg').text('Processing Please Wait.....')
                     .removeClass( "text-green")
				     .addClass( "text-blue" );  
			
			$.ajax({
				type: "POST",
				url : "<?php echo site_url()?>/user/Drop",
				data: data,
				success: function(e){
                  	$('#dropModal #msg').text(e.pesan)
						.removeClass( "text-blue")
						.addClass( "text-green" );															 
			    },
				error : function(e){
					$('#dropModal #msg').text(e.responseJSON.pesan)
                             .removeClass( "text-blue")							 
							 .removeClass( "text-green")
				             .addClass( "text-red" ); 
				}	
		    });
			return false;
		});
		
		 $("#username").select2({
			placeholder: '-Masukan NIP-',
		    minimumInputLength: 17,
    	    ajax: {
				url:  '<?php echo site_url() ?>'+'/user/getPns',
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
			    url: "<?php echo site_url()?>/user/getPnsdata",
				dataType:'json',
				type:'GET',
				data:{q:this.value},
				success: function(result){		
                    $("input[name=nip]").val(result[0].PNS_NIPBARU);
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
		
		$(".edit").on("click",function(){
			var nip          =  $(this).attr('data-nip'),
				usertipe   	 =  $(this).attr('data-usertipe'),
				fname        =  $(this).attr('data-fname'),
				lname        =  $(this).attr('data-lname'),
				email        =  $(this).attr('data-email'),
				bidang       =  $(this).attr('data-bidang'),
				instansi     =  $(this).attr('data-instansi'),
				sex          =  $(this).attr('data-sex'),
				jabatan      =  $(this).attr('data-jabatan'),
				active       =  $(this).attr('data-active'),
				username     =  $(this).attr('data-username'),
			    user_id      =  $(this).attr('data-id');
				area         =  $(this).attr('data-area');
				
            $("input[name=nip]").val(nip);	
			$("input[name=user_id]").val(user_id);
			$("input[name=fname]").val(fname);
			$("input[name=lname]").val(lname);
			$("input[name=email]").val(email);
			$("[name=usertipe]").val(usertipe);
			$("input[name=jabatan]").val(jabatan);
			$("#username").append(new Option (username,username,true,true)).trigger("change");
			$("[name=bidang]").val(bidang);
			$("[name=instansi]").val(instansi);			
			$("input[name=active][value='"+active+"']").prop("checked",true);
			$("input[name=sex][value='"+sex+"']").prop("checked",true);
			$("[name=area]").val(area);
					
		});	
		
		<?php if($this->input->post()):?>
		$("#username").append(new Option ('<?php echo set_value('nip'); ?>','<?php echo set_value('nip'); ?>',true,true)).trigger("change");
		<?php endif;?>
	});
	</script>	
	</body>
</html>
