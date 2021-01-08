<!DOCTYPE html>
<html>
 <head>
   <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Aplikasi Male_o 1.9 </title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<link rel="Shortcut icon" href="<?php echo base_url()?>assets/dist/img/favicon_garuda.ico" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url()?>assets/dist/css/AdminLTE.css">
    <link rel="stylesheet" href="<?php echo base_url()?>assets/dist/css/skins/skin-yellow.css">
	 <style>	
	.marquee {
	  width: 100%; /* the plugin works for responsive layouts so width is not necessary */
	  overflow: hidden;
	  border:0px solid #ccc;
	  height: 100vh;
	  /* height:405px; */
	}
.centered {
   text-align: center;
   font-size: 0;
}
.centered > div {
   float: none;
   display: inline-block;
   text-align: left;
   font-size: 15px;
}

#div1 {
  /*background-color: red;*/
  transform: translateY(33%);
}

#time {
  font-family: 'Nova Mono', monospace;
  font-size: 20px;
  text-align: center;
  color:#ffffff;
}

#date {
  font-family: 'Eczar', serif;
  font-size: 20px;
  text-align: center;
  color:#ffffff;
}



  	  </style>
  </head> 	
 

  <body class="hold-transition skin-yellow" id="box">
  <div class="wrapper">	
	 <header class="main-header">
        <a href="#" class="logo">         
          <span class="logo-lg">Male_o 1.9</span>
        </a>
       
        <nav class="navbar navbar-static-top" role="navigation">
			<div class="navbar-custom-menu">
				<div id="div1">
					<span id="time"></span> <span  id="date"> </span>
				  </div>
				
			</div>
        </nav>
        <!-- end navbar header -->
       </header>
	   <div class="container-fluid">
		 <div class="row centered">
        
          <div class="box" >
            
            <!-- /.box-header -->
			<table class="table table-hover">
				<tr>
				 
				  <td class="col-md-3"><strong>INSTANSI</strong></td>
				  <td class="col-md-3"><strong>LAYANAN</strong></td>
				  <td class="col-md-1"><strong>IN</strong></td>
				  <td class="col-md-1"><strong>ACC</strong></td>
				  <td class="col-md-1"><strong>BTL</strong></td>
				  <td class="col-md-1"><strong>BLM</strong></td>
				  <td class="col-md-1"><strong>TMS</strong></td>
				  <td class="col-md-1"><strong>UPDATE</strong></td>				 
				</tr>			
			</table>	
            <div class="marquee box-body no-padding">
			
            <table class="table table-hover">
					  	
			    <?php $i=1;?>
				<?php foreach($dashboard->result() as $value):?>				
					<tr>
					 
					  <td class="col-md-3"><?php echo $value->INS_NAMINS?></td>
					  <td class="col-md-3"><?php echo $value->layanan_nama?></td>
					  <td class="col-md-1 center-block"><span class="label bg-maroon"><?php echo $value->JUMLAH?></span></td>
					  <td class="col-md-1 center-block"><span class="label label-success"><?php echo $value->ACC?></span></td>
					  <td class="col-md-1 center-block"><span class="label label-warning"><?php echo $value->BTL?></span></td>
					  <td class="col-md-1 center-block"><span class="label label-info"><?php echo $value->BELUM?></span></td>
					  <td class="col-md-1 center-block"><span class="label label-danger"><?php echo $value->TMS?></span></td>
					  <td class="col-md-1 center-block"><span class="label label-info"><?php echo $value->update_date?></span></td>
					 
					</tr>
				<?php endforeach?>				
              </table>
            </div>	
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
         </div>
      </div>

	</div>    
	<script src="<?php echo base_url()?>assets/plugins/jQuery/jQuery-2.1.4.min.js"></script>    
    <script src="<?php echo base_url()?>assets/bootstrap/js/bootstrap.min.js"></script> 
    <script src="<?php echo base_url()?>assets/plugins/marque/jquery.marquee.min.js"></script> 
	
	<script type="text/javascript">
	$(document).ready(function() {	
	    
		$("html, body").animate({ scrollTop: $(document).height() }, "slow");	
		
		$('.marquee')
		.bind('finished', function () {
			$.ajax({
				dataType: 'html',
				async: true,
				url:'<?php echo site_url()?>/dashboard/ajaxLoad',
				success:function(result){             
					$('.marquee').html(result);	
					$('.marquee').marquee({
						duration: 10000,
						gap: 1,
						delayBeforeStart: 0,
						direction: 'up',
						duplicated: false,
						pauseOnHover: true,
						css3AnimationIsSupported:true
					});				
				}
			 });  
		})
		.marquee({
			duration: 10000,
			gap: 1,
			delayBeforeStart: 0,
			direction: 'up',
			duplicated: false,
			pauseOnHover: true,
			css3AnimationIsSupported:true,
		});
		 
		window.setInterval(ut, 1000);

		function ut() {
		  var d = new Date();
		  document.getElementById("time").innerHTML = d.toLocaleTimeString('id-ID');
		  document.getElementById("date").innerHTML = d.toLocaleDateString('id-ID');
		}
		
	});	
		
	</script> 
	</body>
</html>
