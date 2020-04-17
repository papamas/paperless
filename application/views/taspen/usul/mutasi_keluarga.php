<!DOCTYPE html>
<html>
 <head>
    <?php echo $this->load->view('vheader');?>
	<link rel="stylesheet" href="<?php echo base_url()?>assets/plugins/select2/select2.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/plugins/datepicker/bootstrap-datetimepicker.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/plugins/daterange/daterangepicker-bs3.css" />
   <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/sweetalert2/sweetalert2.min.css">

  </head> 	
	<style>
	 /*Bootstrap modal size iframe*/
	@media (max-width: 1280px){
		.md-dialog  {
			height:630px;
			width:800px;
		}
		.md-body {
			height: 500px;	
		}
	}
	@media screen and (min-width:1281px) and (max-width:1600px){
		.md-dialog  {
			height:700px;
			width:1000px;
		}
		.md-body {
			height: 550px;	
		}
	}
	@media screen and (min-width:1601px) and (max-width:1920px){
		.md-dialog  {
			height:830px;
			width:1200px;
		}
		.md-body {
			height: 700px;	
		}
	}

	
	.md-content {
		/* Bootstrap sets the size of the modal in the modal-dialog class, we need to inherit it */
		width:inherit;
		height:inherit;
		/* To center horizontally */
		margin: 0 auto;
		pointer-events: all;
	}
	.page-header {
		padding-bottom: 9px;
		margin: 40px 0 20px;
		border-bottom: 1px solid #eee;
		border-top: 2px solid #eee;
	}


    </style>
  </head>
  <body class="hold-transition skin-yellow sidebar-collapse">
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
				<li class="">Taspen</li>
				<li class="active">Buat Usul</li>
			  </ol>
			</section>     
        </section>
		
	    <!-- Main content -->
        <section class="content ">
		    <div class="row">
		        <div class="col-md-12">
					<div class="box box-primary">
						<div class="box-header with-border">
						  <h3 class="box-title">Usul Taspen Mutasi Keluarga</h3>
						</div><!-- /.box-header -->
						<!-- form start -->
						<form class="form-horizontal" role="form" method="post" action="<?php echo site_url()?>/taspen/saveUsulmk" accept-charset="utf-8" enctype="multipart/form-data">
						  <div class="box-body">
							<input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" style="display: none">
							<input type="hidden" name="usul_id" value="<?php echo set_value('usul_id'); ?>"/>
									
							<div class="form-group row">	
								<label class="control-label col-md-2 col-sm-2 col-xs-2">Nomor Usul</label>
							    <div class="col-md-4 col-sm-4 col-xs-4">
									<input type="text" name="nomor_usul" class="form-control" placeholder="Nomor Usul" value="<?php echo set_value('nomor_usul'); ?>">						
									<span class="help-block text-red"><?php echo form_error('nomor_usul');?></span>
								</div>
								<label class="col-sm-2 col-md-2 col-xs-2 control-label">Tanggal</label>	
								<div class="col-md-4 col-sm-4 col-xs-4">									
									<div class='input-group date' >
										<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
										<input id=''  pattern="^\d{1,2}\-\d{1,2}\-\d{4}$" type='text' name="tgl_usul" value="<?php echo (set_value('tgl_usul') ? set_value('tgl_usul') : date('d-m-Y'))?>" class="form-control datetimepicker" />
									</div>
									<span class="help-block text-red"><?php echo form_error('tgl_usul');?></span>
								</div>	
							</div>
							
							<div class="form-group row">
							  	<label class=" control-label col-md-2 col-sm-2 col-xs-2">Pelayanan</label>									
								<div class="col-md-4 col-sm-4 col-xs-4">
									<select name="layanan_id" class="form-control">
										<option value="">--silahkan Pilih--</option>
										<?php foreach($layanan->result() as $value):?>
										<option value="<?php echo $value->layanan_id?>" <?php echo  set_select('layanan_id', $value->layanan_id); ?>><?php echo $value->layanan_nama?></option>
										<?php endforeach?>
									</select>
									<span class="help-block text-red"><?php echo form_error('layanan_id'); ?></span>
								</div>
								<label class="control-label col-md-2 col-sm-2 col-xs-2">Surat Pengantar</label>									
								<div class="col-md-4 col-sm-4 col-xs-4">
									<input type="file" name="file" class="form-control" />
									<span class="help-block text-red"><?php echo (!empty($error) ? $error : '') ?></span>

								</div>
							</div> 	
							
							
							<div class="form-group row">					
								<label class=" control-label col-md-2 col-sm-2 col-xs-2">Nama PNS</label>									
								<div class="col-md-4 col-sm-4 col-xs-4">
									<input type="text" name="nama_pns" class="form-control" placeholder="Masukan Nama PNS" value="<?php echo set_value('nama_pns'); ?>">
									<span class="help-block text-red"><?php echo form_error('nama_pns'); ?></span>
								</div>
								<label class=" control-label col-md-2 col-sm-2 col-xs-2">Nama Kecil <span id="tlabel"></span></label>									
								<div class="col-md-4 col-sm-4 col-xs-4">
									<input type="text" name="nama_kecil" class="form-control" placeholder="Masukan Nama Kecil" value="<?php echo set_value('nama_kecil'); ?>">
									<span class="help-block text-red"><?php echo form_error('nama_kecil'); ?></span>
								</div>
							</div>	

							<div class="form-group row">	
								<label class="control-label col-md-2 col-sm-2 col-xs-2">Tempat Lahir</label>
							    <div class="col-md-4 col-sm-4 col-xs-4">
									<input type="text" name="tempat_lahir" class="form-control" placeholder="Masukan Tempat Lahir" value="<?php echo set_value('tempat_lahir'); ?>">						
									<span class="help-block text-red"><?php echo form_error('tempat_lahir');?></span>
								</div>
								<label class="col-sm-2 col-md-2 col-xs-2 control-label">Tanggal Lahir</label>	
								<div class="col-md-4 col-sm-4 col-xs-4">									
									<div class='input-group date' >
										<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
										<input id=''  pattern="^\d{1,2}\-\d{1,2}\-\d{4}$" type='text' name="tgl_lahir" value="<?php echo set_value('tgl_lahir');?>" class="form-control datetimepicker" />							
									</div>	
									<span class="help-block text-red"><?php echo form_error('tgl_lahir');?></span>	
								</div>	
							</div>	
							
							<div class="form-group row">	
								<label class="control-label col-md-2 col-sm-2 col-xs-2">No.Surat Keputusan Pensiun</label>
							    <div class="col-md-4 col-sm-4 col-xs-4">
									<input type="text" name="nomor_skep" class="form-control" placeholder="Masukan Nomor Surat Keputusan Pensiun" value="<?php echo set_value('nomor_skep'); ?>">						
									<span class="help-block text-red"><?php echo form_error('nomor_skep');?></span>
								</div>
								<label class="col-sm-2 col-md-2 col-xs-2 control-label">Tanggal Surat Keputusan</label>	
								<div class="col-md-4 col-sm-4 col-xs-4">									
									<div class='input-group date' >
										<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
										<input id=''  pattern="^\d{1,2}\-\d{1,2}\-\d{4}$" type='text' name="tgl_skep" value="<?php echo set_value('tgl_skep');?>" class="form-control datetimepicker" />
									</div>			
									<span class="help-block text-red"><?php echo form_error('tgl_skep');?></span>
								</div>	
							</div>
							
							<div class="form-group row">
								<label class=" control-label col-md-2 col-sm-2 col-xs-2">NOPEN / No. Dosir</label>									
								<div class="col-md-4 col-sm-4 col-xs-4">
									<input type="text" name="nopen" class="form-control" placeholder="Masukan NOPEN / No. Dosir" value="<?php echo set_value('nopen'); ?>">
									<span class="help-block text-red"><?php echo form_error('nopen'); ?></span>
								</div>
								
							  	<label class=" control-label col-md-2 col-sm-2 col-xs-2">NIP / NRP / NVP</label>									
								<div class="col-md-4 col-sm-4 col-xs-4">
									<select name="nip" class="form-control select2" >
										<option value="">--silahkan Pilih--</option>
										<?php foreach($upload->result() as $value):?>
										<option value="<?php echo $value->nip?>" <?php echo  set_select('nip', $value->nip); ?>><?php echo $value->nip?></option>
										<?php endforeach?>
									</select>
									<span class="help-block text-red"><?php echo form_error('nip'); ?></span>
								</div>
							</div>
														
							<div class="form-group row">	
								<label class="col-sm-2 col-md-2 col-xs-2 control-label">Pensiun Pokok</label>	
								<div class="col-md-4 col-sm-4 col-xs-4">									
									<input type="number" name="pensiun_pokok" class="form-control" placeholder="Masukan Pensiun Pokok" value="<?php echo set_value('pensiun_pokok'); ?>">
									<span class="help-block text-red"><?php echo form_error('pensiun_pokok'); ?></span>						
								</div>
								
								<label class="col-sm-2 col-md-2 col-xs-2 control-label">Pensiun TMT</label>	
								<div class="col-md-4 col-sm-4 col-xs-4">									
									<div class='input-group date' >
										<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
										<input id=''  pattern="^\d{1,2}\-\d{1,2}\-\d{4}$" type='text' name="pensiun_tmt" value="<?php echo set_value('pensiun_tmt');?>" class="form-control datetimepicker" />
									</div>
									<span class="help-block text-red"><?php echo form_error('pensiun_tmt');?></span>
								</div>	
							</div>
							
							<div class="form-group row">
							  	<label class="control-label col-md-2 col-sm-2 col-xs-2">Alamat Ybs</label>									
								<div class="col-md-10 col-sm-10 col-xs-10">
									<input type="text" name="alamat" class="form-control" placeholder="Masukan Alamat Ybs" value="<?php echo set_value('alamat'); ?>">
									<span class="help-block text-red"><?php echo form_error('alamat'); ?></span>
								</div>								
							</div>
							
							<h3 class="page-header">ISTRI(2)SUAMI</h3>	
							<span class="help-block text-red"><?php echo form_error('nama_istri[]');?></span>
							<span class="help-block text-red"><?php echo form_error('nama_kecil_istri[]');?></span>
							<span class="help-block text-red"><?php echo form_error('tempat_lahir_istri[]');?></span>
							<span class="help-block text-red"><?php echo form_error('tgl_lahir_istri[]');?></span>
							<span class="help-block text-red"><?php echo form_error('tgl_nikah_istri[]');?></span>
							<span class="help-block text-red"><?php echo form_error('tgl_pendaftaran_istri[]');?></span>
							<span class="help-block text-red"><?php echo form_error('alamat_istri[]');?></span>
							<div class="table-responsive">
								<table id="tb-istri"  class="table table-striped table-condensed">
								<thead>
									<tr>
										<th>Aksi</th>
										<th>Nama</th>
										<th>Nama Kecil</th>
										<th>Tempat/Tgl Lahir</th>
										<th>Tanggl Nikah</th>
										<th>Tanggal Pendaftaran</th>
										<th>Tanggal Cerai</th>
										<th>Tanggal Wafat</th>	
										<th>Alamat</th>
									</tr>
								</thead>  
								
								<tbody>
								    <?php if($temp_istri->num_rows() > 0):?>
									<?php foreach($temp_istri->result()  as $value ):?>
									<tr>
										<td>
										<?php 
										echo '<a class="btn btn-primary btn-xs" data-tooltip="tooltip"  title="Edit Istri" data-toggle="modal" data-target="#istriModal" data-id="'.$value->mutasi_id.'" data-nama="'.$value->nama.'" data-nama_kecil="'.$value->nama_kecil.'" data-tempat_lahir="'.$value->tempat_lahir.'" data-tgl_lahir="'.$value->tgl_lahir.'" data-tgl_nikah="'.$value->tgl_nikah.'" data-tgl_pendaftaran="'.$value->tgl_pendaftaran.'" data-tgl_cerai="'.$value->tgl_cerai.'" data-tgl_wafat="'.$value->tgl_wafat.'" data-alamat="'.$value->alamat.'" data-usul="'.$value->usul_id.'"><i class="fa fa-edit"></i></a>';
										echo '&nbsp;<a class="btn btn-danger btn-xs" data-tooltip="tooltip"  title="Hapus Istri" data-toggle="modal" data-target="#hapusModal" data-id="'.$value->mutasi_id.'"><i class="fa fa-remove"></i></a>';
										?>
										</td>
										<td><?php echo $value->nama?></td>
										<td><?php echo $value->nama_kecil?></td>
										<td><?php echo $value->tempat_lahir.'/'.$value->tgl_lahir?></td>
										<td><?php echo $value->tgl_nikah?></td>
										<td><?php echo $value->tgl_pendaftaran?></td>
										<td><?php echo $value->tgl_cerai?></td>
										<td><?php echo $value->tgl_wafat?></td>
										<td><?php echo $value->alamat?></td>
									</tr>
									<?php endforeach;?>
									<?php endif;?>
								</tbody>
								</table>
							</div>	
							
							<h3 class="page-header">NAMA ANAK(2)KANDUNG</h3>															
							<div class="table-responsive">
								<table id="tb-anak"  class="table table-striped table-condensed">
								<thead>
									<tr>
										<th rowspan="2">Aksi</th>
										<th rowspan="2">Nama</th>
										<th rowspan="2">LK/PR</th>
										<th rowspan="2">Tgl Lahir</th>
										<th colspan="3">Keterangan Tentang Ibu/Ayah </th>										
									</tr>									
									<tr>									
										<th>Nama</th>
										<th>Cerai Tgl</th>
										<th>Meninggal Tgl</th>
									</tr>									
								</thead>   
								<tbody>
									<?php if($temp_anak->num_rows() > 0):?>
									<?php foreach($temp_anak->result()  as $value ):?>
									<tr>
										<td>
										<?php 
										echo '<a class="btn btn-primary btn-xs" data-tooltip="tooltip"  title="Edit Anak" data-toggle="modal" data-target="#anakModal" data-id="'.$value->mutasi_id.'" data-nama="'.$value->nama.'" data-sex="'.$value->sex.'" data-tgl_lahir="'.$value->tgl_lahir.'" data-tgl_cerai="'.$value->cerai_tgl.'" data-tgl_wafat="'.$value->meninggal_tgl.'" data-nama_ibu_ayah="'.$value->nama_ibu_ayah.'" data-usul="'.$value->usul_id.'"><i class="fa fa-edit"></i></a>';
										echo '&nbsp;<a class="btn btn-danger btn-xs" data-tooltip="tooltip"  title="Hapus Anak" data-toggle="modal" data-target="#hapusAnakModal" data-id="'.$value->mutasi_id.'"><i class="fa fa-remove"></i></a>';
										?>
										</td>
										<td><?php echo $value->nama?></td>
										<td><?php echo $value->sex?></td>
										<td><?php echo $value->tgl_lahir?></td>
										<td><?php echo $value->nama_ibu_ayah?></td>										
										<td><?php echo $value->cerai_tgl?></td>
										<td><?php echo $value->meninggal_tgl?></td>
									</tr>
									<?php endforeach;?>
									<?php endif;?>
								</tbody>
								</table>
							</div>								
														
							<div class="box-footer">				
								<button type="submit" class="btn btn-primary btn-block" data-tooltip="tooltip" id="simpan" title="Simpan data Usul"><i class="fa fa-save"></i>&nbsp;Simpan Usul</button>
							</div>
							
						   </div>						   
						</form>
						
						
						<div class="box-footer">
							<form class="navbar-form" name="frmUsul" method="POST" action="<?php echo site_url()?>/taspen/getUsul">
							   <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" style="display: none">
								<div class="form-group" style="display:inline;">
								  <div class="input-group" style="display:table;">
									<span class="input-group-addon" style="width:1%;"><span class="fa fa-search"></span></span>
									<input class="form-control"  name="find" placeholder="Masukan NIP atau Nomor Usul" type="text" value="<?php echo set_value('find'); ?>">
								  </div>
								</div>						
							 </form>
						</div>
						
						<div class="table-responsive">
							<table id="tb-usul"  class="table table-striped table-condensed">
							<thead>
								<tr>
									<th style="width:150px;"></th>
									<th>NOMOR</th>
									<th>TGL USUL</th>
									<th>NIP</th>
									<th>NAMA PNS</th>
									<th>PELAYANAN</th>
									<th>FILE</th>
									<th>ISTRI</th>
									<th>ANAK</th>
									<th>SYSDATE</th>							
								</tr>
							</thead>   
							<tbody>
								<?php if($usul->num_rows() > 0):?>
								<?php  foreach($usul->result() as $value):?>
								<tr>
									<td>
										<?php 
										echo'<button class="edit btn btn-primary btn-xs" data-tooltip="tooltip"  title="Edit Usul" data-nomor="'.$value->nomor_usul.'" data-tgl="'.$value->tgl.'" data-layanan="'.$value->layanan_id.'" data-nama="'.$value->nama_pns.'" data-nama_kecil="'.$value->nama_kecil.'" data-nopen="'.$value->nopen.'" data-usul="'.$value->usul_id.'" data-nip="'.$value->nip.'" data-tempat_lahir="'.$value->tempat_lahir.'" data-tgl_lahir="'.$value->atgl_lahir.'" data-nomor_skep="'.$value->nomor_skep.'" data-tgl_skep="'.$value->atgl_skep.'" data-penpok="'.$value->pensiun_pokok.'" data-pensiun_tmt="'.$value->apensiun_tmt.'" data-alamat="'.$value->alamat.'"><i class="fa fa-edit"></i></button>';
										?>
										<a href="#" class="btn bg-orange btn-flat btn-xs" data-tooltip="tooltip"  title="Lihat Kelengkapan Berkas" data-toggle="modal" data-target="#lihatModal" data-id="<?php echo '?n='.$this->myencrypt->encode($value->nip).'&l='.$this->myencrypt->encode($value->layanan_nama)?>"><i class="fa fa-search"></i></a>
										<a href="#" class="btn btn-success btn-flat btn-xs" data-toggle="modal" data-target="#istriModal" data-tooltip="tooltip"  title="Tambah data Istri" data-usul="<?php echo $value->usul_id?>"> <i class="fa fa-user-plus"></i></a>
									    <a href="#" class="btn btn-primary btn-flat btn-xs" data-toggle="modal" data-target="#anakModal" data-tooltip="tooltip"  title="Tambah data Anak" data-usul="<?php echo $value->usul_id?>"><i class="fa fa-child"></i></a>
									   	<a href="#" class="btn btn-danger btn-flat btn-xs" data-tooltip="tooltip"  title="Kirim Usul BKN" data-toggle="modal" data-target="#kirimModal" data-nip="<?php echo $value->nip?>" data-usul="<?php echo $value->usul_id?>" ><i class="fa fa-mail-forward"></i></a>

									</td>
									<td><?php echo $value->nomor_usul ?></td>
									<td><?php echo $value->tgl ?></td>
									<td><?php echo $value->nip ?></td>
									<td><?php echo $value->nama_pns ?></td>
									<td><?php echo $value->layanan_nama ?></td>									
									<td>
										<?php 
										if(!empty($value->file_pengantar))
										{
											$file = $value->file_pengantar;
											
											echo '<span data-toggle="tooltip" data-original-title="Ada File Pengantar">
											<i class="fa fa-file-pdf-o" data-toggle="modal" data-target="#showFile" data-id="?t='.$this->myencrypt->encode("application/pdf").'&f='.$this->myencrypt->encode($file).'" style="color:red;"></i></span>';
										}
										else
										{
											echo '<span data-toggle="tooltip" data-original-title="Tidak Ada File Pengantar">
											<i class="fa fa-file-o" style="color:red;"></i></span>';
										}
										?>
									</td>
									<td><?php echo $value->jumlah_istri ?></td>
									<td><?php echo $value->jumlah_anak ?></td>
									<td><?php echo $value->updated_date ?></td>
								</tr>
								<?php endforeach;?>
								<?php endif;?>
                            </tbody>
                            </table>
                        </div>
						
					</div>
                </div>
            </div> 	
			
        </section><!-- /.content -->		
      </div><!-- /.content-wrapper -->     
    </div><!-- ./wrapper -->
	
	<!-- Modal -->
	<div class="modal fade" id="lihatModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog modal-md" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
					<h4 class="modal-title" id="">Infomasi Kelengkapan Berkas</h4>
				</div>
							
			</div>
		</div>	
    </div>
	
	<div class="modal" id="showFile" tabindex="-1" role="dialog" aria-hidden="true">
	    <div class="modal-dialog  md-dialog modal-lg">
		  <div class="modal-content md-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" >File Dokumen</h4>
				</div>	
				<div class="modal-body md-body">
					<iframe  id="frame" width="100%" height="100%" frameborder="0" ></iframe>					
				</div>
		  </div>
		</div>
	</div>	

	<div class="modal fade" id="kirimModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
					<h4 class="modal-title" id="myModalLabel"><span id="msg"></span></h4>
				</div>
				<div class="modal-body">
					<form id="nfrmKirim">
					  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name()?>" value="<?php echo $this->security->get_csrf_hash()?>" style="display: none">
					  <div class="form-group"> Yakin Usul ini akan dikirim ke BKN ?</div>
                       <input type="hidden" name="usul_nip"/>	
					   <input type="hidden" name="usul_id"/>					   
					</form>
				 </div>
				<div class="modal-footer">
					<button type="button" class="btn bg-maroon" id="nBtnKirim"><i class="fa fa-leaf"></i>&nbsp;OK Kirim !</button>
				</div>
			</div>
		</div>	
	</div>
	
	<div id="istriModal" class="modal fade" role="dialog">
          <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><span id="msg"></span></h4>
                </div>
                <div class="modal-body">
                    <!-- Form -->
                    <form method='post' action='' enctype="multipart/form-data" id="istriForm">
					    <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" style="display: none">
                        <input class="form-control" type="hidden" value="" name="temp_mutasi_id" />
						<input class="form-control" type="hidden" value="" name="usul_id" />
						<div class="form-group row">						    
							<label class="col-sm-2 col-md-2 col-xs-2 control-label">Nama</label>							
							<div class="col-sm-4 col-md-4 col-xs-4">
								<input class="form-control" type="text" value="" name="nama" />	
                            </div>
							<label class="col-sm-2 col-md-2 col-xs-2 control-label">Nama Kecil</label>
							<div class="col-sm-4 col-md-4 col-xs-4">
								<input class="form-control" type="text" value="" name="nama_kecil" />	
                            </div>
						</div>
						<div class="form-group row">						    
							<label class="col-sm-2 col-md-2 col-xs-2 control-label">Tempat Lahir</label>
							<div class="col-sm-4 col-md-4 col-xs-4">
								<input class="form-control" type="text" value="" name="tempat_lahir" />	
                            </div>
							<label class="col-sm-2 col-md-2 col-xs-2 control-label">Tgl Lahir</label>
							<div class="col-sm-4 col-md-4 col-xs-4">
								<div class='input-group date' >
									<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
									<input pattern="^\d{1,2}\-\d{1,2}\-\d{4}$" type='text' name="tgl_lahir" value="<?php echo (set_value('tgl_lahir') ? set_value('tgl_lahir') : date('d-m-Y'))?>" class="form-control datetimepicker" />
								</div>
							</div>
						</div>
						<div class="form-group row">						    
							<label class="col-sm-2 col-md-2 col-xs-2 control-label">Tgl Nikah</label>
							<div class="col-sm-4 col-md-4 col-xs-4">
								<div class='input-group date' >
									<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
									<input pattern="^\d{1,2}\-\d{1,2}\-\d{4}$" type='text' name="tgl_nikah" value="<?php echo (set_value('tgl_nikah') ? set_value('tgl_nikah') : date('d-m-Y'))?>" class="form-control datetimepicker" />
								</div>									
                            </div>
							<label class="col-sm-2 col-md-2 col-xs-2 control-label">Tgl Pendaftaran</label>
							<div class="col-sm-4 col-md-4 col-xs-4">
								<div class='input-group date' >
									<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
									<input pattern="^\d{1,2}\-\d{1,2}\-\d{4}$" type='text' name="tgl_pendaftaran" value="<?php echo (set_value('tgl_pendaftaran') ? set_value('tgl_pendaftaran') : date('d-m-Y'))?>" class="form-control datetimepicker" />
								</div>								
                            </div>
						</div>
						<div class="form-group row">						    
							<label class="col-sm-2 col-md-2 col-xs-2 control-label">Tgl Cerai</label>
							<div class="col-sm-4 col-md-4 col-xs-4">
								<div class='input-group date' >
									<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
									<input pattern="^\d{1,2}\-\d{1,2}\-\d{4}$" type='text' name="tgl_cerai" value="" class="form-control datetimepicker" />
								</div>								
                            </div>
							<label class="col-sm-2 col-md-2 col-xs-2 control-label">Tgl Wafat</label>
							<div class="col-sm-4 col-md-4 col-xs-4">
								<div class='input-group date' >
									<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
									<input pattern="^\d{1,2}\-\d{1,2}\-\d{4}$" type='text' name="tgl_wafat" value="" class="form-control datetimepicker" />
								</div>							
                            </div>
						</div>
						<div class="form-group row">						    
							<label class="col-sm-2 col-md-2 col-xs-2 control-label">Alamat</label>
							<div class="col-sm-10 col-md-10 col-xs-10">
								<input class="form-control" type="text" value="" name="alamat" />	
                            </div>							
						</div>
                    </form>
                </div> 
                <div class="modal-footer">
				   <button type="button" class="btn btn-primary" id="nBtnIstri">Simpan</button>
				</div>				
            </div>
        </div>
    </div>
	
	<div class="modal fade" id="hapusModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
					<h4 class="modal-title" id="myModalLabel"><span id="msg"></span></h4>
				</div>
				<div class="modal-body">
					<form id="nfrmHapus">
					  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name()?>" value="<?php echo $this->security->get_csrf_hash()?>" style="display: none">
					  <div class="form-group"> Yakin akan menghapus ?</div>
                       <input type="hidden" name="temp_mutasi_id"/>				   					   
					</form>
				 </div>
				<div class="modal-footer">
					<button type="button" class="btn bg-maroon" id="nBtnHapus"><i class="fa fa-leaf"></i>&nbsp;OK Hapus !</button>
				</div>
			</div>
		</div>	
	</div>
	
	<div id="anakModal" class="modal fade" role="dialog">
          <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><span id="msg"></span></h4>
                </div>
                <div class="modal-body">
                    <!-- Form -->
                    <form method='post' action='' enctype="multipart/form-data" id="anakForm">
					    <input type="hidden" name="<?=$this->security->get_csrf_token_name();?>" value="<?=$this->security->get_csrf_hash();?>" style="display: none">
                        <input class="form-control" type="hidden" value="" name="temp_mutasi_id" />
						<input class="form-control" type="hidden" value="" name="usul_id" />
						<div class="form-group row">						    
							<label class="col-sm-2 col-md-2 col-xs-2 control-label">Nama</label>							
							<div class="col-sm-4 col-md-4 col-xs-4">
								<input class="form-control" type="text" value="" name="nama" />	
                            </div>
							<label class="col-sm-2 col-md-2 col-xs-2 control-label">Sex</label>
							<div class="col-sm-4 col-md-4 col-xs-4">
								<select name="sex" class="form-control">
									<option value="">--silahkan Pilih--</option>
									<option value="LK">Laki-Laki</option>
									<option value="PR">Perempuan</option>									
								</select>	
                            </div>
						</div>
						<div class="form-group row">						    
							<label class="col-sm-2 col-md-2 col-xs-2 control-label">Tgl Lahir</label>
							<div class="col-sm-4 col-md-4 col-xs-4">
								<div class='input-group date' >
									<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
									<input pattern="^\d{1,2}\-\d{1,2}\-\d{4}$" type='text' name="tgl_lahir" value="<?php echo (set_value('tgl_lahir') ? set_value('tgl_lahir') : date('d-m-Y'))?>" class="form-control datetimepicker" />
								</div>
							</div>
							<label class="col-sm-2 col-md-2 col-xs-2 control-label">Nama Ibu/Ayah</label>
							<div class="col-sm-4 col-md-4 col-xs-4">
								<input class="form-control" type="text" value="" name="nama_ibu_ayah" />	
                            </div>	
						</div>
						
						<div class="form-group row">						    
							<label class="col-sm-2 col-md-2 col-xs-2 control-label">Tgl Cerai</label>
							<div class="col-sm-4 col-md-4 col-xs-4">
								<div class='input-group date' >
									<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
									<input pattern="^\d{1,2}\-\d{1,2}\-\d{4}$" type='text' name="tgl_cerai" value="" class="form-control datetimepicker" />
								</div>								
                            </div>
							<label class="col-sm-2 col-md-2 col-xs-2 control-label">Tgl Wafat</label>
							<div class="col-sm-4 col-md-4 col-xs-4">
								<div class='input-group date' >
									<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
									<input pattern="^\d{1,2}\-\d{1,2}\-\d{4}$" type='text' name="tgl_wafat" value="" class="form-control datetimepicker" />
								</div>							
                            </div>
						</div>
						
                    </form>
                </div> 
                <div class="modal-footer">
				   <button type="button" class="btn btn-primary" id="nBtnAnak">Simpan</button>
				</div>				
            </div>
        </div>
    </div>
	
	<div class="modal fade" id="hapusAnakModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
					<h4 class="modal-title" id="myModalLabel"><span id="msg"></span></h4>
				</div>
				<div class="modal-body">
					<form id="nfrmHapusAnak">
					  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name()?>" value="<?php echo $this->security->get_csrf_hash()?>" style="display: none">
					  <div class="form-group"> Yakin akan menghapus ?</div>
                       <input type="hidden" name="temp_mutasi_id"/>				   					   
					</form>
				 </div>
				<div class="modal-footer">
					<button type="button" class="btn bg-maroon" id="nBtnHapusAnak"><i class="fa fa-leaf"></i>&nbsp;OK Hapus !</button>
				</div>
			</div>
		</div>	
	</div>
	<script src="<?php echo base_url()?>assets/plugins/jQuery/jQuery-2.1.4.min.js"></script>    
    <script src="<?php echo base_url()?>assets/bootstrap/js/bootstrap.min.js"></script> 
    <script src="<?php echo base_url()?>assets/dist/js/app.min.js"></script>
	<script src="<?php echo base_url()?>assets/plugins/select2/select2.full.min.js"></script>
	<script src="<?php echo base_url()?>assets/plugins/daterange/moment-with-locales.js"></script>	
	<script src="<?php echo base_url()?>assets/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/plugins/sweetalert2/sweetalert2.min.js"></script>

	<script>	
	$(document).ready(function () {
	    $('[data-tooltip="tooltip"]').tooltip();
		
		$(".select2").select2({
			width: '100%'
		});
				
		$('.datetimepicker').datetimepicker({
		   sideBySide: true,
		   locale: 'id',
		   format:'DD-MM-YYYY',
		});
		
		$('#showFile').on('show.bs.modal',function(e) {    		
			var id=  $(e.relatedTarget).attr('data-id');
			var iframe = $('#frame');
			iframe.attr('src', '<?php echo site_url()?>'+'/taspen/getInlineTaspen/'+id);			
	    });
		
		$(".edit").on("click",function(){
			var nip     =  $(this).attr('data-nip'),
			    nomor   =  $(this).attr('data-nomor'),
				tgl     =  $(this).attr('data-tgl'),
				layanan =  $(this).attr('data-layanan'),
				nama	=  $(this).attr('data-nama'),
				nama_kecil	=  $(this).attr('data-nama_kecil'),
				tempat_lahir	=  $(this).attr('data-tempat_lahir'),
				tgl_lahir	=  $(this).attr('data-tgl_lahir'),
				nomor_skep   =  $(this).attr('data-nomor_skep'),
				tgl_skep   =  $(this).attr('data-tgl_skep'),
				nopen   =  $(this).attr('data-nopen'),
				penpok   =  $(this).attr('data-penpok'),
				pensiun_tmt   =  $(this).attr('data-pensiun_tmt'),
				alamat     =  $(this).attr('data-alamat'),
				usul_id    =  $(this).attr('data-usul');
				
            $("input[name=nomor_usul]").val(nomor);	
			$("input[name=tgl_usul]").val(tgl);
			$("[name=layanan_id]").val(layanan);
			$("input[name=nama_pns]").val(nama);
			$("input[name=nama_kecil]").val(nama_kecil);
			$("input[name=tempat_lahir]").val(tempat_lahir);
			$("input[name=tgl_lahir]").val(tgl_lahir);
			$("input[name=nomor_skep]").val(nomor_skep);
			$("input[name=tgl_skep]").val(tgl_skep);
			$("input[name=nopen]").val(nopen);
			$("input[name=pensiun_pokok]").val(penpok);
			$("input[name=pensiun_tmt]").val(pensiun_tmt);
			$("input[name=usul_id]").val(usul_id);
			$("[name=nip]").val(nip);
			$("[name=nip]").select2().trigger('change');			
			$("input[name=alamat]").val(alamat);
			
			refreshTempIstri();
			refreshTempAnak();
		});	
		
		$('#kirimModal').on('hide.bs.modal',function(e){
			$("#nBtnKirim").show();			
		});	
		
		$('#kirimModal').on('show.bs.modal',function(e){
		     $('#kirimModal #msg').text('Konfirmasi Pengiriman Usul')
			.removeClass( "text-green")
		    .removeClass( "text-blue" ); 
			
			var nip		=  $(e.relatedTarget).attr('data-nip'),
				usul    =  $(e.relatedTarget).attr('data-usul');
			
			$('#kirimModal input[name=usul_nip]').val(nip);
			$('#kirimModal input[name=usul_id]').val(usul);
		});
		
		$("#nBtnKirim").on("click",function(e){
			e.preventDefault();
			var data = $('#nfrmKirim').serialize();
			
			$('#kirimModal #msg').text('Updating Please Wait.....')
                     .removeClass( "text-green")
				     .addClass( "text-blue" );  
			
			$.ajax({
				type: "POST",
				url : "<?php echo site_url()?>/taspen/kirim",
				data: data,
				success: function(){					
					$("#nBtnKirim").hide();
					
					$('#kirimModal #msg').text('Usul sudah dikirim ke BKN....')
						.removeClass( "text-blue")
						.addClass( "text-green" );
					refreshTable();											 
			    }, // akhir fungsi sukses
		    });
			return false;
		});
		
		
		$('#lihatModal').on('show.bs.modal',function(e) {    		
			var id=  $(e.relatedTarget).attr('data-id');
			$.get('<?php echo site_url()?>/taspen/getKelengkapan/'+id, function(data){
				$('#lihatModal').find('.modal-header').html(data); 
				
			});			
	    });
		
			
		
		$('#istriModal').on('show.bs.modal',function(e){
		     $('#istriModal #msg').text('Penambahan Data Istri')
			.removeClass( "text-green")
		    .removeClass( "text-blue" ); 
			
			var id				=  $(e.relatedTarget).attr('data-id'),
				nama    		=  $(e.relatedTarget).attr('data-nama'),
				nama_kecil      =  $(e.relatedTarget).attr('data-nama_kecil'),
				tempat_lahir    =  $(e.relatedTarget).attr('data-tempat_lahir'),
				tgl_lahir       =  $(e.relatedTarget).attr('data-tgl_lahir'),
				tgl_nikah       =  $(e.relatedTarget).attr('data-tgl_nikah'),
				tgl_pendaftaran       =  $(e.relatedTarget).attr('data-tgl_pendaftaran'),
				tgl_cerai		      =  $(e.relatedTarget).attr('data-tgl_cerai'),
				tgl_wafat		      =  $(e.relatedTarget).attr('data-tgl_wafat'),
				alamat		      	  =  $(e.relatedTarget).attr('data-alamat'),
				usul_id		      	  =  $(e.relatedTarget).attr('data-usul');
			
			$('#istriModal input[name=temp_mutasi_id]').val(id);
			$('#istriModal input[name=nama]').val(nama);
			$('#istriModal input[name=nama_kecil]').val(nama_kecil);
			$('#istriModal input[name=tempat_lahir]').val(tempat_lahir);
			$('#istriModal input[name=tgl_lahir]').val(tgl_lahir);
			$('#istriModal input[name=tgl_nikah]').val(tgl_nikah);
			$('#istriModal input[name=tgl_cerai]').val(tgl_cerai);
			$('#istriModal input[name=tgl_pendaftaran]').val(tgl_pendaftaran);
			$('#istriModal input[name=tgl_wafat]').val(tgl_wafat);
			$('#istriModal input[name=alamat]').val(alamat);
			$('#istriModal input[name=usul_id]').val(usul_id);
		});
		
		
		$('#hapusModal').on('show.bs.modal',function(e){
		     $('#hapusModal #msg').text('Konfirmasi Delete Istri')
			.removeClass( "text-green")
		    .removeClass( "text-blue" ); 
			
			var id		=  $(e.relatedTarget).attr('data-id');			
			$('#hapusModal input[name=temp_mutasi_id]').val(id);
			
		});
		
		$("#nBtnIstri").on("click",function(e){
			e.preventDefault();			
			var data = $('#istriForm').serialize();
					
			$('#istriModal #msg').text('Saving Please Wait.....')
                     .removeClass( "text-green")
				     .addClass( "text-blue" );  
			
			$.ajax({
				type: "POST",
				url : "<?php echo site_url()?>/taspen/simpanTempIstri",
				data: data,
				dataType:'json',
				success: function(e){
					$('#istriModal #msg').text(e.pesan)
                             .removeClass( "text-blue")
							 .removeClass( "text-red")
				             .addClass( "text-green" ); 
					refreshTempIstri();		 
				}, 
				error : function(e){
					$('#istriModal #msg').text(e.responseJSON.pesan)
                             .removeClass( "text-blue")							 
							 .removeClass( "text-green")
				             .addClass( "text-red" ); 
				}	
		    });
			return false;
		});
		
		
		$("#nBtnHapus").on("click",function(e){
			e.preventDefault();
			var data = $('#nfrmHapus').serialize();
			
			$('#hapusModal #msg').text('Deleting Please Wait.....')
                     .removeClass( "text-green")
				     .addClass( "text-blue" );  
			
			$.ajax({
				type: "POST",
				url : "<?php echo site_url()?>/taspen/hapusTempIstri",
				data: data,
				success: function(){					
					$('#hapusModal #msg').text('Berhasil menghapus data....')
						.removeClass( "text-blue")
						.addClass( "text-green" );
					refreshTempIstri();											 
			    }, // akhir fungsi sukses
		    });
			return false;
		});
		
		
		$('#anakModal').on('show.bs.modal',function(e){
		     $('#anakModal #msg').text('Penambahan Data Anak')
			.removeClass( "text-green")
		    .removeClass( "text-blue" ); 
			
			var id				=  $(e.relatedTarget).attr('data-id'),
				nama    		=  $(e.relatedTarget).attr('data-nama'),
				sex      		=  $(e.relatedTarget).attr('data-sex'),				
				tgl_lahir       =  $(e.relatedTarget).attr('data-tgl_lahir'),				
				tgl_cerai		      =  $(e.relatedTarget).attr('data-tgl_cerai'),
				tgl_wafat		      =  $(e.relatedTarget).attr('data-tgl_wafat'),
				nama_ibu_ayah      	  =  $(e.relatedTarget).attr('data-nama_ibu_ayah'),
				usul_id		      	  =  $(e.relatedTarget).attr('data-usul');
			
			$('#anakModal input[name=temp_mutasi_id]').val(id);
			$('#anakModal input[name=nama]').val(nama);
			$('#anakModal [name=sex]').val(sex);			
			$('#anakModal input[name=tgl_lahir]').val(tgl_lahir);			
			$('#anakModal input[name=tgl_cerai]').val(tgl_cerai);			
			$('#anakModal input[name=tgl_wafat]').val(tgl_wafat);
			$('#anakModal input[name=nama_ibu_ayah]').val(nama_ibu_ayah);
			$('#anakModal input[name=usul_id]').val(usul_id);
			
		});
		
		$("#nBtnAnak").on("click",function(e){
			e.preventDefault();			
			var data = $('#anakForm').serialize();
					
			$('#anakModal #msg').text('Saving Please Wait.....')
                     .removeClass( "text-green")
				     .addClass( "text-blue" );  
			
			$.ajax({
				type: "POST",
				url : "<?php echo site_url()?>/taspen/simpanTempAnak",
				data: data,
				dataType:'json',
				success: function(e){
					$('#anakModal #msg').text(e.pesan)
                             .removeClass( "text-blue")
							 .removeClass( "text-red")
				             .addClass( "text-green" ); 
					refreshTempAnak();		 
				}, 
				error : function(e){
					$('#anakModal #msg').text(e.responseJSON.pesan)
                             .removeClass( "text-blue")							 
							 .removeClass( "text-green")
				             .addClass( "text-red" ); 
				}	
		    });
			return false;
		});
		
		$('#hapusAnakModal').on('show.bs.modal',function(e){
		     $('#hapusAnakModal #msg').text('Konfirmasi Delete Anak')
			.removeClass( "text-green")
		    .removeClass( "text-blue" ); 
			
			var id		=  $(e.relatedTarget).attr('data-id');			
			$('#hapusAnakModal input[name=temp_mutasi_id]').val(id);
			
		});
		
		$("#nBtnHapusAnak").on("click",function(e){
			e.preventDefault();
			var data = $('#nfrmHapusAnak').serialize();
			
			$('#hapusAnakModal #msg').text('Deleting Please Wait.....')
                     .removeClass( "text-green")
				     .addClass( "text-blue" );  
			
			$.ajax({
				type: "POST",
				url : "<?php echo site_url()?>/taspen/hapusTempAnak",
				data: data,
				success: function(){					
					$('#hapusAnakModal #msg').text('Berhasil menghapus data....')
						.removeClass( "text-blue")
						.addClass( "text-green" );
					refreshTempAnak();											 
			    }, // akhir fungsi sukses
		    });
			return false;
		});
		
				
		function refreshTable(){						
			$.ajax({   
			    type: 'POST',   
			    url: '<?php echo site_url()?>/taspen/getUsulAllmk',   
			    data: $('form[name=frmUsul]').serialize(),
			    success: function(res) {
					$("#tb-usul").html(res);
				},
			});
		}
		
		function refreshTempIstri(){						
			$.ajax({   
			    type: 'POST',   
			    url: '<?php echo site_url()?>/taspen/getTempIstriAll',   
			    data:  $('#istriForm').serialize(),
			    success: function(res) {
					$("#tb-istri").html(res);
				},
			});
		}
		
		function refreshTempAnak(){						
			$.ajax({   
			    type: 'POST',   
			    url: '<?php echo site_url()?>/taspen/getTempAnakAll',   
			    data:  $('#anakForm').serialize(),
			    success: function(res) {
					$("#tb-anak").html(res);
				},
			});
		}
	});	
	</script>
	<script type="text/javascript">
		<?php if($show):?>
		Swal.fire(
			"<?php echo $title?>",
			"<?php echo $pesan; ?>",
			"<?php echo $tipe?>"
		) 
		<?php endif;?> 
	</script>
	</body>
</html>
