<!DOCTYPE html>
<html>
<head>
<?php  $this->load->view('vheader');?> 
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/sweetalert2/sweetalert2.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/plugins/datepicker/bootstrap-datetimepicker.min.css" />
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
		    <section class="content">
			    <div class="row">
					<div class="col-md-12">
						<h4><b>LIST NOMINATIF&nbsp; - <?php echo $detail_agenda->layanan_nama ?>&nbsp; - No Usul : <?php echo $detail_agenda->agenda_nousul ?> -  Jumlah <?php echo $detail_agenda->agenda_jumlah ?></b></h4>
					</div>
				</div>
			  
			  <?php if($detail_agenda->agenda_status == 'dibuat'){?>
				<!--IMPORT EXCEL HIDDEN!-->
				<div class="row">
					<label class="control-label col-md-2"><a href="<?php echo site_url().'/agenda/getXls/'?>" target="_blank" data-tooltip="tooltip" title="Download Format File Import Nominatif">Format File KLIK DISINI</a></label>
					<div class="col-md-10">
					  <?php echo form_open_multipart('importexcel');?>
					  <div class="input-group">
						<input type="hidden" class="form-control" value="<?php echo $detail_agenda->agenda_id ?>" name="input_agendaid" required>
						<input type="file" class="form-control" name="xls_ins" required>
						<span class="input-group-btn">
						  <button type="submit" class="btn btn-primary" type="button">Import Nominatif</button>
						</span>
					  </div>
					  </form>
					</div>
				</div>
				<!--IMPORT EXCEL HIDDEN-->

			    <div class="row">
					<div class="col-md-4">
					  <?php echo form_open_multipart('agenda/ftambah_nominatif');?>
					  <div class="input-group">
						<input type="hidden" class="form-control" value="<?php echo $detail_agenda->agenda_id ?>" name="input_agendaid">
						<input type="hidden" class="form-control" value="<?php echo $detail_agenda->layanan_id ?>" name="input_layananid">
						<input type="hidden" class="form-control" value="<?php echo $detail_agenda->layanan_nama ?>" name="input_layanannama">
						<input type="hidden" class="form-control" value="<?php echo $detail_agenda->layanan_grup ?>" name="input_layanangrup">
						<input type="hidden" class="form-control" value="<?php echo $detail_agenda->kp_periode ?>" name="input_periodekp">
						<input type="hidden" class="form-control" value="<?php echo $detail_agenda->agenda_nousul ?>" name="input_agenda_nousul">
						<input type="text" class="form-control" name="input_nip" value="" maxlength="19" id="searchbox1" required>
						<span class="input-group-btn">
						  <button type="submit" class="btn btn-primary" type="button">Tambah</button>
						</span>
					  </div>
					  <div id="results1" class="form-control"></div>
					  </form>
					</div>
					<div class="col-md-8">				 
						<form>						
							<div class="col-md-6">
								<input type="text" class="form-control" name="nip" id="nip" disabled><br>
							</div>
							<div class="col-md-6">
								<input type="text" class="form-control" name="nama" id="nama"  disabled><br>
							</div>						  
							
							
							<div class="col-md-2">
							   <input type="text" class="form-control" name="golongan" id="golongan" disabled><br>
							</div>
							<div class="col-md-10">
								<input type="text" class="form-control" name="pendidikan" id="pendidikan"  disabled><br>
							</div>
															  
							<div class="col-md-12">
								<input type="text" class="form-control" name="instansi" id="instansi"  disabled><br>
							</div>
							 
						</form>				  
					</div>
			    </div>
			    <?php } ?>
				
		        <div class="row">
					<div class="col-md-12">
						<div class="box box-primary">
						    <div class="box-header with-border">
							  <h3 class="box-title">Daftar Nominatif Usul</h3>
							</div><!-- /.box-header -->
							<div class="box-body">
								<div class="table-responsive">
									<table id="tblnomi" class="table table-striped">
									  <thead>
									  <tr>
										<th>No</th>
										<th>NIP</th>
										<th>Nama</th>
										<th>Gol/Ruang</th>
										<th>Pendidikan</th>
										<th>Instansi</th>
										<th>Aksi</th>
									  </tr>
									  </tr>
									  </thead>
									  <tbody>
										<?php $no=1; foreach ($list_nominatif as $nominatif){ ?>
										<tr>
										  <td><?php echo $no++; ?></td>
										  <td><?php echo $nominatif->nip; ?></td>
										  <td><?php if($nominatif->pns_pnsnam != NULL){ echo $nominatif->pns_pnsnam; }else{echo "<b>PERIKSA KEMBALI NIP INI  DAN IMPORT KEMBALI</b>";} ?></td>
										  <td><?php echo $nominatif->gol_golnam; ?>-<?php echo $nominatif->gol_pktnam; ?></td>
										  <td><?php echo $nominatif->dik_namdik; ?></td>
										  <td><?php echo $nominatif->ins_namins; ?></td>
										  <?php if($detail_agenda->agenda_status == 'dibuat'){?>
										  <td>
											<a onclick="confirmation(event)" href="<?php echo site_url("agenda/hapus_nominatif/$nominatif->nip/$nominatif->agenda_id")?>" type="button" data-tooltip="tooltip" title="Hapus Nominatif" class="btn btn-danger btn-flat btn-xs"><i class="fa fa-trash"></i></a>
											
											<?php  if($detail_agenda->layanan_id == 19):?>
											<a href="#" data-tooltip="tooltip" title="Edit Nota Usul PMK" data-toggle="modal" data-agenda="<?php echo $nominatif->agenda_id?>" data-nip="<?php echo $nominatif->nip?>" data-target="#eModal"  class="btn btn-info btn-flat btn-xs"><i class="fa fa-edit"></i></a>
											
											<a href="<?php echo site_url().'/pmk/cetakNotaUsul?i='.$this->myencrypt->encode($nominatif->agenda_id).'&n='.$this->myencrypt->encode($nominatif->nip)?>" data-tooltip="tooltip" title="Cetak Nota Usul PMK" class="btn btn-success btn-flat btn-xs"><i class="fa fa-print"></i></a>
											
											<?php endif;?>
										  </td>
										<?php }else{echo "<td></td>"; } ?>
										</tr>
									  <?php } ?>
									  </tbody>
									</table>
								</div>
							</div>	
							<div class="box-footer">
								<?php echo form_open_multipart('agenda/kirim_usul');?>
								 <input type="hidden" class="form-control" value="<?php echo $detail_agenda->layanan_id ?>" name="layananId">
								 <input type="hidden" class="form-control" value="<?php echo $detail_agenda->agenda_id ?>" name="input_agendaid">
								 <input type="hidden" class="form-control" value="<?php echo $detail_agenda->agenda_jumlah ?>" name="input_agendajumlah">
								 <input type="hidden" class="form-control" value="<?php echo $periodeKP?>" name="periodeKP">
								 <input type="hidden" class="form-control" value="<?php echo $detail_agenda->layanan_grup ?>" name="layananGrup">
								   <?php if($detail_agenda->agenda_status == 'dibuat'){?>
									<button type="submit" class="btn btn-block btn-primary btn-flat" name="button"><b>KIRIM USUL </b><i class="fa fa-angle-double-right"></i></button>
								   <?php } ?>						
								</form>	
							</div>
						</div>	
					</div>		
			    </div>			
		  </section><!-- /.content -->
		</div><!-- /.content-wrapper -->
	</div><!-- ./wrapper -->
	
	<div class="modal fade" id="eModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
					<h4 class="modal-title" id=""><span id="msg"></span></h4>
				</div>
				<div class="modal-body">
					<form id="efrmUsul">
						<input class="form-control" type="hidden" name="<?php echo $this->security->get_csrf_token_name()?>" value="<?php echo $this->security->get_csrf_hash()?>">
		                <input class="form-control" type="hidden" value="" name="nip" />
						<input class="form-control" type="hidden" value="" name="agendaId" />

						
						 <!-- Custom Tabs -->
						  <div class="nav-tabs-custom">
							<ul class="nav nav-tabs">
							  <li class="active"><a href="#tab_1" data-toggle="tab">Utama</a></li>
							  <li><a href="#tab_2" data-toggle="tab">Perhitungan</a></li>
							  <li><a href="#tab_3" data-toggle="tab">Ijazah</a></li>
							  <li><a href="#tab_4" data-toggle="tab">Salinan Bukti-Bukti</a></li>
							  <li><a href="#tab_5" data-toggle="tab">SK Pangkat</a></li>
							  <li><a href="#tab_6" data-toggle="tab">Tanda Tangan</a></li>
							</ul>
							<div class="tab-content">
							  <div class="tab-pane active" id="tab_1">
								<table class="table table-bordered ">
									<tr>
										<td colspan="2">Tempat Lahir</td>
										<td colspan="2"><input class="form-control" type="text" placeholder="Tempat Lahir" name="tempatLahir"></td>
									</tr>
									<tr>
										<td rowspan="4" width="5px">LAMA</td>
										<td width="300px">1. MASA KERJA GOL</td>
										<td>						
										<input class="form-control" type="text" placeholder="Tahun" name="oldTahun">
										</td>
										<td>						
										<input class="form-control " type="text" placeholder="Bulan" name="oldBulan"></td>
									</tr>
									<tr>
										<td>2. GAJI POKOK</td>
										<td colspan="2"><input class="form-control" type="text" placeholder="Gaji Pokok" name="oldGaji"></td>
									</tr>
									<tr>
										<td>3. SEJAK</td>
										<td colspan="2">
											<div class='input-group date'><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
												<input id="tmtGaji" class="form-control" type="text" placeholder="TMT Gaji" name="oldTmtGaji">
											</div>	
										</td>
									</tr>
									<tr>
										<td>4. PERSETUJUAN BKN</td>
										<td><input class="form-control" type="text" placeholder="Nomor Persetujuan" name="nomorPersetujuan"></td>
										<td>
											<div class='input-group date'><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
											<input id='tmtAg' class="form-control" type="text" placeholder="Tanggal Persetujuan" name="tanggalPersetujuan">
											</div>
										</td>		
									</tr>
									<tr>
										<td rowspan="3"  width="20px" align="center">BARU</td>
										<td>1. MASA KERJA GOL</td>
										<td><input class="form-control" type="text" placeholder="Tahun" name="baruTahun"></td>
										<td><input class="form-control" type="text" placeholder="Bulan" name="baruBulan"></td>
									</tr>
									<tr>
										<td>2. GAJI POKOK</td>
										<td colspan="2"><input class="form-control" type="text" placeholder="Gaji Pokok" name="baruGaji"></td></td>						
									</tr>
									<tr>
										<td>BERLAKU TERHITUNG MULAI TANGGAL</td>
										<td colspan="2">
											<div class='input-group date'><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
											<input id="baruTmt" class="form-control" type="text" placeholder="TMT" name="baruTmtGaji">
											</div>
										</td>						
									</tr>
								</table>	
							  </div>
							  <!-- /.tab-pane -->
							  <div class="tab-pane" id="tab_2">
								<table class="table table-bordered ">
									<tr>
										<td rowspan="3"  width="20px" align="center">LAMA</td>
										<td width="180px" rowspan="2" align="center"> PENGALAMAN KERJA</td>
										<td  align="center" rowspan="2" colspan="2">MULAI DAN SAMPAI DENGAN TGL. BL. TH</td>
										<td  align="center" colspan="2" width="70px">JUMLAH</td>
										
									</tr>
									<tr>
										<td  align="center">TH</td>
										<td  align="center">BL</td>						
									</tr>
									<tr>
										<td width="180px"  align="center"> DIANGKAT SEBAGAI HONORER</td>
										<td width="180px" align="center">
										  <div class='input-group date'><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
										  <input id="mulaiHonor" class="form-control" type="text" placeholder="MULAI" name="mulaiHonor"></div>
										</td>  
										<td width="180px">
											 <div class='input-group date'><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
										   <input class="form-control" type="text" placeholder="SAMPAI" id="sampaiHonor" name="sampaiHonor"></div>
										</td>
										<td align="center">
										   <input class="form-control" type="text" placeholder="TH" name="tahunHonor">
										</td>
										<td align="center">
										   <input class="form-control" type="text" placeholder="BL" name="bulanHonor">
										</td>
									</tr>
									<tr>
										<td rowspan="3"  width="20px" align="center">BARU</td>
										<td width="180px" rowspan="3" align="center"> DIANGKAT SEBAGAI CALON PEGAWAI</td>
										<td  align="center" rowspan="3"> 
										   <div class='input-group date'><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>	
										   <input class="form-control" type="text" placeholder="MULAI" id="mulaiPegawai" name="mulaiPegawai"></div>
										</td>  
										<td rowspan="3">
											<div class='input-group date'><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
										   <input class="form-control" type="text" placeholder="SAMPAI" id="sampaiPegawai" name="sampaiPegawai"></div>
										</td>
										<td  rowspan="3" align="center" >
										  <input class="form-control" type="text" placeholder="TH" name="tahunPegawai">
										</td>
										<td  rowspan="3" align="center" >
										   <input class="form-control" type="text" placeholder="BL" name="bulanPegawai">
										</td>
										
									</tr>
									<tr>
										
										
									</tr>
									<tr>
										
										
									</tr>
									
								</table>	
							  </div>
							  <!-- /.tab-pane -->
							  <div class="tab-pane" id="tab_3">
								<table class="table table-bordered ">
									<tr>
									   <td>A.</td>
									   <td colspan="3">STTB/Ijazah/Diploma/Akta</td>
									</tr>
									<tr>
									   <td>1.</td>
									   <td> 
										  <select class="form-control" name="tingkat1">
											<option value="">--pilih--</option>
											<option value="S3">S3</option>
											<option value="S2">S2</option>
											<option value="S1">S1</option>
											<option value="D4">D4</option>
											<option value="D3">D3</option>
											<option value="D2">D2</option>
											<option value="D1">D1</option>
											<option value="SMA">SMA</option>
											<option value="SMP">SMP</option>
											<option value="SD">SD</option>
										  </select>
									   </td>
									   <td> <input class="form-control" type="text" placeholder="Nomor Ijazah" name="nomorIjazah1"></td>
									   <td> 
											<div class='input-group date'><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
												<input id="tanggalIjazah1" class="form-control" type="text" placeholder="Tanggal Ijazah" name="tanggalIjazah1">
											</div>
										</td>
									</tr>
									<tr>
									   <td>2.</td>
									   <td> 
										  <select class="form-control" name="tingkat2">
											<option value="">--pilih--</option>
											<option value="S3">S3</option>
											<option value="S2">S2</option>
											<option value="S1">S1</option>
											<option value="D4">D4</option>
											<option value="D3">D3</option>
											<option value="D2">D2</option>
											<option value="D1">D1</option>
											<option value="SMA">SMA</option>
											<option value="SMP">SMP</option>
											<option value="SD">SD</option>
										  </select>
									   </td>
									   <td> <input class="form-control" type="text" placeholder="Nomor Ijazah" name="nomorIjazah2"></td>
									   <td> 
										<div class='input-group date'><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
										<input id="tanggalIjazah2" class="form-control" type="text" placeholder="Tanggal Ijazah" name="tanggalIjazah2"></td>
										</div>
									</tr>
									<tr>
									   <td>3.</td>
									   <td> 
										  <select class="form-control" name="tingkat3">
											<option value="">--pilih--</option>
											<option value="S3">S3</option>
											<option value="S2">S2</option>
											<option value="S1">S1</option>
											<option value="D4">D4</option>
											<option value="D3">D3</option>
											<option value="D2">D2</option>
											<option value="D1">D1</option>
											<option value="SMA">SMA</option>
											<option value="SMP">SMP</option>
											<option value="SD">SD</option>
										  </select>
									   </td>
									   <td> <input class="form-control" type="text" placeholder="Nomor Ijazah" name="nomorIjazah3"></td>
									   <td>  <div class='input-group date'><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span><input class="form-control" id="tanggalIjazah3" type="text" placeholder="Tanggal Ijazah" name="tanggalIjazah3"></div></td>
									</tr>
									<tr>
									   <td>4.</td>
									   <td> 
										  <select class="form-control" name="tingkat4">
											<option value="">--pilih--</option>
											<option value="S3">S3</option>
											<option value="S2">S2</option>
											<option value="S1">S1</option>
											<option value="D4">D4</option>
											<option value="D3">D3</option>
											<option value="D2">D2</option>
											<option value="D1">D1</option>
											<option value="SMA">SMA</option>
											<option value="SMP">SMP</option>
											<option value="SD">SD</option>
										  </select>
									   </td>
									   <td> <input class="form-control" type="text" placeholder="Nomor Ijazah" name="nomorIjazah4"></td>
									   <td>  <div class='input-group date'><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span><input class="form-control" id="tanggalIjazah4" type="text" placeholder="Tanggal Ijazah" name="tanggalIjazah4"></div></td>
									</tr>
									<tr>
									   <td>5.</td>
									   <td> 
										  <select class="form-control" name="tingkat5">
											<option value="">--pilih--</option>
											<option value="S3">S3</option>
											<option value="S2">S2</option>
											<option value="S1">S1</option>
											<option value="D4">D4</option>
											<option value="D3">D3</option>
											<option value="D2">D2</option>
											<option value="D1">D1</option>
											<option value="SMA">SMA</option>
											<option value="SMP">SMP</option>
											<option value="SD">SD</option>
										  </select>
									   </td>
									   <td> <input class="form-control" type="text" placeholder="Nomor Ijazah" name="nomorIjazah5"></td>
									   <td>  <div class='input-group date'><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span><input id="tanggalIjazah5" class="form-control" type="text" placeholder="Tanggal Ijazah" name="tanggalIjazah5"></div></td>
									</tr>
								</table>	
							  </div>
								<div class="tab-pane" id="tab_4">
								   <table class="table table-bordered ">
										<tr>
										   <td>C.</td>
										   <td>Salinan Sah dan bukti-bukti pengalaman kerja</td>
										</tr>
										<tr>
										   <td></td>
										   <td>
											 <textarea class="form-control" placeholder="Salinan sah bukti-bukti" name="salinanSah"></textarea>
										   </td>
										</tr>
								   </table>
								</div>
								<div class="tab-pane" id="tab_5">
									<table class="table table-bordered ">
										<tr>
										   <td>D.</td>
										   <td>Surat Keputusan</td>
										</tr>
										<tr>
										   <td></td>
										   <td>
											 <textarea class="form-control" placeholder="Surat Keputusan Kenaikan Pangkat" name="skPangkat"></textarea>
										   </td>
										</tr>
								   </table>
								</div>
								 <!-- /.tab-pane -->
							  <div class="tab-pane" id="tab_6">
								<table class="table table-bordered ">
									<tr>
										<td>Lokasi</td>
										<td><input class="form-control" type="text" placeholder="Lokasi Tanda Tangan" name="lokasiTtd"></td>
										<td>Tanggal</td>
										<td>  <div class='input-group date'><span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span><input id="tanggalTtd" class="form-control" type="text" placeholder="Tanggal Tanda Tangan" name="tanggalTtd"></div></td>
									</tr>
									<tr>
										<td>Jabatan</td>
										<td><input class="form-control" type="text" placeholder="Jabatan Penanda Tangan" name="jabatanTtd"></td>
										<td>Nama</td>
										<td><input class="form-control" type="text" placeholder="Nama Penanda Tangan" name="namaTtd"></td>
									</tr>
									
									<tr>
										<td>Pangkat</td>
										<td>  <input class="form-control" type="text" placeholder="Pangkat Penanda Tangan" name="pangkatTtd">
										</td>
										<td>NIP</td>
										<td>  <input class="form-control" type="text" placeholder="NIP Penanda Tangan" name="nipTtd">
										</td>
									</tr>
									
								</table>
							  </div>			
							  <!-- /.tab-pane -->
							</div>
							<!-- /.tab-content -->
						  </div>
						  <!-- nav-tabs-custom -->
					</form>
				</div>		
				<div class="modal-footer">
				   <button type="button" class="btn btn-success" id="nBtnSimpan">Simpan Nota Usul PMK</button>
				</div>
			</div>
		</div>	
    </div>
<script src="<?php echo base_url()?>assets/plugins/jQuery/jQuery-2.1.4.min.js"></script>    
<script src="<?php echo base_url()?>assets/bootstrap/js/bootstrap.min.js"></script> 
<script src="<?php echo base_url()?>assets/dist/js/app.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/sweetalert2/sweetalert2.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/daterange/moment-with-locales.js"></script>	
<script src="<?php echo base_url()?>assets/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
<script>  
	var nip, nama, golongan, pendidikan, instansi;
	function kirim(nip, nama, golongan, pendidikan, instansi){
		 var y = $("#nip").val(nip);
		 var y = $("#nama").val(nama);
		 var y = $("#golongan").val(golongan);
		 var y = $("#pendidikan").val(pendidikan);
		 var y = $("#instansi").val(instansi);

		 $("#results1").slideUp('fast');
	}
	
	function confirmation(ev) {
		  ev.preventDefault();
		  var urlToRedirect = ev.currentTarget.getAttribute('href'); //use currentTarget because the click may be on the nested i tag and not a tag causing the href to be empty
		  //console.log(urlToRedirect); // verify if this is the right URL
		  Swal.fire({
			title: 'Yakin Ingin Menghapus Data ini?',
			text: "Data yang dihapus tidak bisa dikembalikan!",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Ya, Hapus!'
		  })
		  .then((result) => {
			if (result.value) {
				location.replace(urlToRedirect)
			}
		});
	}
	
    $(document).ready(function () {
        $('[data-tooltip="tooltip"]').tooltip();
		
		$('#tanggalTtd').datetimepicker({
		   sideBySide: true,
		   locale: 'id',
		   format:'DD-MM-YYYY',
		});
		
		$('#sampaiPegawai').datetimepicker({
		   sideBySide: true,
		   locale: 'id',
		   format:'DD-MM-YYYY',
		});
		
		$('#mulaiPegawai').datetimepicker({
		   sideBySide: true,
		   locale: 'id',
		   format:'DD-MM-YYYY',
		});
		
		$('#sampaiHonor').datetimepicker({
		   sideBySide: true,
		   locale: 'id',
		   format:'DD-MM-YYYY',
		});
		
		$('#mulaiHonor').datetimepicker({
		   sideBySide: true,
		   locale: 'id',
		   format:'DD-MM-YYYY',
		});
		
		$('#tanggalIjazah5').datetimepicker({
		   sideBySide: true,
		   locale: 'id',
		   format:'DD-MM-YYYY',
		});
		
		$('#tanggalIjazah4').datetimepicker({
		   sideBySide: true,
		   locale: 'id',
		   format:'DD-MM-YYYY',
		});
		
		$('#tanggalIjazah3').datetimepicker({
		   sideBySide: true,
		   locale: 'id',
		   format:'DD-MM-YYYY',
		});
		
		$('#tanggalIjazah2').datetimepicker({
		   sideBySide: true,
		   locale: 'id',
		   format:'DD-MM-YYYY',
		});
		
		$('#tanggalIjazah1').datetimepicker({
		   sideBySide: true,
		   locale: 'id',
		   format:'DD-MM-YYYY',
		});
		
		$('#tmtGaji').datetimepicker({
		   sideBySide: true,
		   locale: 'id',
		   format:'DD-MM-YYYY',
		});
		
		
		$('#tmtAg').datetimepicker({
		   sideBySide: true,
		   locale: 'id',
		   format:'DD-MM-YYYY',
		});
		
		$('#baruTmt').datetimepicker({
		   sideBySide: true,
		   locale: 'id',
		   format:'DD-MM-YYYY',
		});
		
		$('#eModal').on('show.bs.modal',function(e){
		    var agenda=  $(e.relatedTarget).attr('data-agenda');
			var nip   =  $(e.relatedTarget).attr('data-nip');
			
			$('#eModal #msg').text('Edit Nota Usul PMK')
                     .removeClass( "text-green")
					 .removeClass( "text-red")
				     .removeClass( "text-blue" );
		
			$("input[name=agendaId]").val(agenda);
			$("input[name=nip]").val(nip);
			
			$.ajax({
				type: "GET",
				url : "<?php echo site_url()?>/pmk/getUsul",
				data: {agendaId:agenda,nip:nip},
				dataType:'json',
				success: function(r){
					$('#eModal input[name=tempatLahir]').val(r.tempat_lahir);	
					$('#eModal input[name=oldTahun]').val(r.old_masa_kerja_tahun);	
                    $('#eModal input[name=oldBulan]').val(r.old_masa_kerja_bulan);
					$('#eModal input[name=oldGaji]').val(r.old_gaji_pokok);
					$('#eModal input[name=oldTmtGaji]').val(r.old_tmt_gaji);
					$('#eModal input[name=nomorPersetujuan]').val(r.nomor_persetujuan);
					$('#eModal input[name=tanggalPersetujuan]').val(r.tanggal_persetujuan);
					$('#eModal input[name=baruTahun]').val(r.baru_masa_kerja_tahun);
					$('#eModal input[name=baruBulan]').val(r.baru_masa_kerja_bulan);
					$('#eModal input[name=baruGaji]').val(r.baru_gaji_pokok);
					$('#eModal input[name=baruTmtGaji]').val(r.baru_tmt_gaji);
					$('#eModal input[name=mulaiHonor]').val(r.mulai_honor);
					$('#eModal input[name=sampaiHonor]').val(r.sampai_honor);
					$('#eModal input[name=tahunHonor]').val(r.tahun_honor);
					$('#eModal input[name=bulanHonor]').val(r.bulan_honor);
					$('#eModal input[name=mulaiPegawai]').val(r.mulai_pegawai);
					$('#eModal input[name=sampaiPegawai]').val(r.sampai_pegawai);
					$('#eModal input[name=tahunPegawai]').val(r.tahun_pegawai);
					$('#eModal input[name=bulanPegawai]').val(r.bulan_pegawai);
					$('#eModal [name=salinanSah]').val(r.salinan_sah);
					$('#eModal [name=skPangkat]').val(r.sk_pangkat);
					
					$('#eModal [name=tingkat1]').val(r.tingkat1);
					$('#eModal input[name=nomorIjazah1]').val(r.nomor_ijazah1);
					$('#eModal input[name=tanggalIjazah1]').val(r.tanggal_ijazah1);
					
					$('#eModal [name=tingkat2]').val(r.tingkat2);
					$('#eModal input[name=nomorIjazah2]').val(r.nomor_ijazah2);
					$('#eModal input[name=tanggalIjazah2]').val(r.tanggal_ijazah2);
					
					$('#eModal [name=tingkat3]').val(r.tingkat3);
					$('#eModal input[name=nomorIjazah3]').val(r.nomor_ijazah3);
					$('#eModal input[name=tanggalIjazah3]').val(r.tanggal_ijazah3);
					
					$('#eModal [name=tingkat4]').val(r.tingkat4);
					$('#eModal input[name=nomorIjazah4]').val(r.nomor_ijazah4);
					$('#eModal input[name=tanggalIjazah4]').val(r.tanggal_ijazah4);
					
					$('#eModal [name=tingkat5]').val(r.tingkat5);
					$('#eModal input[name=nomorIjazah5]').val(r.nomor_ijazah5);
					$('#eModal input[name=tanggalIjazah5]').val(r.tanggal_ijazah5);
					
					$('#eModal input[name=lokasiTtd]').val(r.lokasi_ttd);
					$('#eModal input[name=tanggalTtd]').val(r.tanggal_ttd);
					$('#eModal input[name=jabatanTtd]').val(r.jabatan_ttd);
					$('#eModal input[name=namaTtd]').val(r.nama_ttd);
					$('#eModal input[name=pangkatTtd]').val(r.pangkat_ttd);
					$('#eModal input[name=nipTtd]').val(r.nip_ttd);
				},
			});	
		});
		
		$("#nBtnSimpan").on("click",function(e){
			e.preventDefault();			
			var data = $('#efrmUsul').serialize();
					
			$('#eModal #msg').text('Saving Please Wait.....')
                     .removeClass( "text-green")
				     .addClass( "text-blue" );  
			
			$.ajax({
				type: "POST",
				url : "<?php echo site_url()?>/pmk/saveUsul",
				data: data,
				dataType:'json',
				success: function(e){
					$('#eModal #msg').text(e.pesan)
                             .removeClass( "text-blue")
							 .removeClass( "text-red")
				             .addClass( "text-green" ); 
							 
				}, 
				error : function(e){
					$('#eModal #msg').text(e.responseJSON.pesan)
                             .removeClass( "text-blue")							 
							 .removeClass( "text-green")
				             .addClass( "text-red" ); 
				}	
		    });
			return false;
		});
		 
		$("#searchbox1").on('keyup',function () {
			this.value = $.trim(this.value);
			var key = $(this).val();
			if(key.length > 14)
			{	  
			    $.ajax({
					url:'<?php echo site_url(); ?>/agenda/autocomplete',
					type:'GET',
					data:'kirim='+key,
					beforeSend:function () {
					  $("#results1").slideUp('fast');
					},
					success:function (data) {
					  $("#results1").html(data);
					  $("#results1").slideDown('fast');
					}
			    });
			}	  
		});
    });
	<?php if($show):?>
	 Swal.fire(
		"<?php echo $title;?>",
		"<?php echo $pesan;?>",
		"<?php echo $tipe;?>",
	  ) 
	<?php endif;?>
</script>
</body>
</html>