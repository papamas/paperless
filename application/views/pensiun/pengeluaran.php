<!DOCTYPE html>
<html>
 <head>
    <?php echo $this->load->view('vheader');?>
	<link rel="stylesheet" href="<?php echo base_url()?>assets/dist/css/tree.css">   
	<link rel="stylesheet" href="<?php echo base_url()?>assets/dist/css/custom.css">   
	<link rel="stylesheet" href="<?php echo base_url()?>assets/plugins/select2/select2.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/plugins/daterange/daterangepicker-bs3.css" />
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
            <li class="">Pengeluaran</li>
			<li class="active">Bidang Pensiun</li>
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
						  <h3 class="box-title">Pengeluaran Bidang Pensiun</h3>
						</div><!-- /.box-header -->
						<!-- form start -->
						<form class="form-horizontal" method="post" action="<?php echo site_url()?>/pensiun/getPengeluaran" role="form" >
						<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" style="display: none">
						  <div class="box-body">
							<div class="form-group">
							  <label class="col-md-2 col-sm-2 col-xs-2">Nomor Usul Instansi</label>
							    <div class="col-sm-10 col-md-10 col-xs-10">
									<select name="nomorUsul" id="nomorUsul" class="form-control">
										<option value="">--</option>										
									</select>
									<span class="help-block text-red"><?php echo form_error('nomorUsul'); ?></span>	
							    </div>
							    						  
							</div>
							
							<div class="form-group row">
								<label class="control-label col-md-2 col-sm-2 col-xs-2">Nomor Pengeluaran</label>
								<div class="col-md-4 col-xs-4 col-sm-4">
								   	<input type="number"   name="nomorPengeluaran" class="form-control" value=""/>  					
									<span class="help-block text-red"><?php echo form_error('nomorPengeluaran'); ?></span>	
								</div>
							</div>
							<div class="form-group row">
							    <label class="control-label col-md-2 col-sm-2 col-xs-2">Cetak Tanda Terima:</label>
								<div class="col-md-4 col-sm-4 col-xs-4">
								    <input type="radio" value="1" name="tandaTerima"  <?php echo  set_radio('tandaTerima', 1);?>  />&nbsp;Ya
									<input type="radio" value="2" name="tandaTerima"  <?php echo  set_radio('tandaTerima', 2,true);?>  />&nbsp;Tidak
									<span class="help-block text-red"><?php echo form_error('tanda_terima'); ?></span>
								</div>
							</div>		
						   </div>
						    <div class="box-footer">
							<button type="submit" class="btn btn-primary"><i class="fa fa-print"></i>&nbsp;Print</button>
						  </div>
						</form>
					</div>
                </div>
            </div> 				
						   
			
        </section><!-- /.content -->		
      </div><!-- /.content-wrapper -->     
    </div><!-- ./wrapper -->
	
	
	
	<script src="<?php echo base_url()?>assets/plugins/jQuery/jQuery-2.1.4.min.js"></script>    
    <script src="<?php echo base_url()?>assets/bootstrap/js/bootstrap.min.js"></script> 
    <script src="<?php echo base_url()?>assets/dist/js/app.min.js"></script>
	<script src="<?php echo base_url()?>assets/plugins/daterange/moment-with-locales.js"></script>	
	<script type="text/javascript" src="<?php echo base_url()?>assets/plugins/daterange/daterangepicker.js"></script>
	<script src="<?php echo base_url()?>assets/plugins/select2/select2.full.min.js"></script>
	<script>	
	$(document).ready(function () {
	    $("#nomorUsul").select2({
			placeholder: '-Masukan Nomor Usul Instansi-',
			width: '100%',
		    minimumInputLength: 10,
    	    ajax: {
				url:  '<?php echo site_url() ?>'+'/pensiun/getAgenda',
				dataType:'json',
				type:'GET',
				cache: "true",
				delay: 250,	
                
			},
			results: function(data, page) {
			    return { results: data.results };               
            }  
		});		
		
		$("#nomorUsul").change(function(){			
			$.ajax({
			    url: "<?php echo site_url()?>/pensiun/getNomorPengeluaran",
				dataType:'json',
				type:'GET',
				data:{q:this.value},
				success: function(r){		
                    $("input[name=nomorPengeluaran]").val(r.last_number);
					if(r.ada)
					{
						$("input[name=nomorPengeluaran]").prop("readonly",true);
					}
					else
					{
						$("input[name=nomorPengeluaran]").prop("readonly",false);
					}		
		        },
			});		
		});	
		
	});
    </script>
	</body>
</html>
