
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hook extends CI_Controller {
		
	public function __construct()
    {
        parent::__construct();
		$this->load->library('Telegram');
		$this->load->model('telegram/telegram_model', 'bot');		
    }
	
	public function tes()
	{
		$this->telegram->sendApiAction("882025162");
		$text  = "<pre>Hello, <strong> Nur Muhamad Holik</strong>";
		$text .= "\n Tanggal:".date('d-m-Y H:i:s')."  Kirim Pesan Tes";
		$text .= "</pre>";
		$this->telegram->sendApiMsg("882025162", $text , false, 'HTML');	
	
	}
	
	public function bot()
	{
		$idfile = 'telegram/botID.txt';
		$update_id = 0;
        
		 
		if (file_exists($idfile)) {
			$update_id = (int) file_get_contents($idfile);
			var_dump($update_id);
			echo '-';
		}

        $this->load->library('Telegram');

        $this->telegram->setOffset($update_id);
		$updates = $this->telegram->getApiUpdate();

		foreach ($updates as $message) {
			var_dump($message);
			$update_id = $this->_prosesApiMessage($message);
			file_put_contents($idfile, $update_id + 1);
            echo '+';
		} 
		
		
		 
		//$entityBody = file_get_contents('php://input');
		//$message = json_decode($entityBody, true);
		
		
		 
	}
	
	public function index()
	{
		$this->load->library('Telegram');
		
		$entityBody = file_get_contents('php://input');
		$message = json_decode($entityBody, true);
		//var_dump($message);
		$this->_prosesApiMessage($message);	 
	}
	
	
	function _prosesApiMessage($sumber)
	{
		$updateid = $sumber['update_id'];
		
		if (isset($sumber['message'])) {
			$message = $sumber['message'];

			if (isset($message['text'])) {
				$this->_prosesPesanTeks($message);
			} elseif (isset($message['sticker'])) {
				$this->_prosesPesanSticker($message);
			} else {
				// gak di proses silakan dikembangkan sendiri
			}
        }

		if (isset($sumber['callback_query'])) {
			$CallBack 		= 	$this->telegram->prosesCallBackQuery($sumber['callback_query']);
			$this->_prosesPesanTeks($CallBack);
		}
		return $updateid;
	}
	
	function _prosesPesanTeks($message)
	{
		$pesan 		= $message['text'];
		$chatid 	= $message['chat']['id'];
		$fromid 	= $message['from']['id'];
		$first_name = $message['from']['first_name'];
		$last_name  = (!empty($message['from']['last_name']) ? $message['from']['last_name'] : '');
		
		$reply_to_message = array();
		
		if (array_key_exists('reply_to_message', $message['from'])) {
			$reply_to_message  = $message['from']['reply_to_message'];
		}	
		
		

		switch (true) {
			case $pesan == 'myid':
				$this->telegram->sendApiAction($chatid);
				$text = 'Telegram ID Kamu adalah: '.$fromid;
				$this->telegram->sendApiMsg($chatid, $text);
				break;
			
			case $pesan == '/start':
				$this->telegram->sendApiAction($chatid);
				$text = "Terima kasih telah bergabung dengan <strong>Male_o 1.9 Bot</strong>";
				$text .= "\n";
				$text .= "\n<strong>Male_o 1.9 Bot</strong> adalah suatu platform Telegram berbasis algoritma untuk menjawab berbagai pertanyaan secara personal (one to one) yang ter-enkripsi guna melindungi kerahasian transmisi data dan percakapan anda.";
				$text .= "\n";
			    $text .="\nKomunikasi anda melalui platform ini akan dilayani oleh Sistem algoritma auto-bot";
				$text .="\n";
				$text .="\nBagi anda yang sudah mempunyai akun pada aplikasi <strong>Male_o 1.9</strong> dapat terhubung secara private dengan sistem auto-bot, dengan cara Mendaftarkan Telegram ID Anda.";
				$text .="\n";
				$text .="\nUntuk melanjutkan, Silahkan pilih menu dibawah";
				
				$inkeyboard = [
					[
						['text' => 'Tentang Male_o 1.9', 'callback_data' => 'Tentang'],
						['text' => 'Layanan Kepegawaian', 'callback_data' => 'Layanan'],
					],
					[
						['text' => 'Daftar Notifikasi', 'callback_data' => 'Daftar'],
						['text' => 'Daftar Perintah ', 'callback_data' => 'Perintah'],
					],
					
				];
				$this->telegram->sendApiKeyboard($chatid, $text, $inkeyboard, true);				
				break;
				
			case $pesan == 'Perintah':
				$this->telegram->sendApiAction($chatid);
				$text = "Daftar perintah pada <strong>Male_o 1.9 Bot</strong>";
				$text .= "\n 1. REG NIP";
				$text .= "\n 2. CEK NIP";
				$text .= "\n 3. CEKTASPEN NIP";
				$text .= "\n 4. APPROVE NIP";
				$text .= "\n 5. AKTIF NIP";
				$text .= "\n 6. BLOK NIP";
				$text .= "\n 7. ADMIN NIP";
				$text .= "\n 8. NONADM NIP";
				$text .= "\n 9. RESET NIP";
				$text .= "\n 10.  myid";
				$text .= "\n 11.  Layanan";
				
			    $inkeyboard = [
					[
						['text' => 'Tentang Male_o 1.9', 'callback_data' => 'Tentang'],
						['text' => 'Layanan Kepegawaian', 'callback_data' => 'Layanan'],
					],
					[
						['text' => 'Daftar Notifikasi', 'callback_data' => 'Daftar'],
						['text' => 'Daftar Perintah ', 'callback_data' => 'Perintah'],
					],
					
				];
			    $this->telegram->sendApiKeyboard($chatid, $text, $inkeyboard, true);				
				break;
			case $pesan == '/tentang':
				$this->telegram->sendApiAction($chatid);
				$text = "Aplikasi Manajemen Layanan Elektronik Online <strong>(Male_o 1.9)</strong> merupakan layanan kepegawaian berbasis paperless secara Online di era industri 4.0 yang merupakan pendamping Sistem Aplikasi Layanan Kepegawaian (SAPK)";
				$text .="\n";
				$text .="\n";
				$text .= "Ada 7 (Tujuh) Jenis layanan kepegawaian yang dapat dilakukan melalui Aplikasi <strong>Male_o 1.9</strong> adalah sebagai berikut:";
				$text .="\n";
				$text .= "\n1. Pertimbangan teknis pensiun (BUP,Janda/Duda, APS, dan tidak cakap jasmani dan/atau rohani);";
				$text .="\n2. Pertimbangan teknis kenaikan pangkat (Jabatan Struktural, Jabatan Fungsional dan Penyesuaian Ijazah);";
				$text .="\n3. Pertimbangan Teknis Mutasi/Pindah Instansi;";
				$text .="\n4. Ijin penggunaan/pencatuman Gelar/peningkatan pendidikan;";
				$text .="\n5. Kartu Pegawai (KARPEG);";
				$text .="\n6. Kartu isteri/suami; dan";
				$text .="\n7. TASPEN ( SK Janda/Dua/Yatim dan Mutasi/Penambahan Keluarga).";
				$inkeyboard = [
					[
						['text' => 'Tentang Male_o 1.9', 'callback_data' => 'Tentang'],
						['text' => 'Layanan Kepegawaian', 'callback_data' => 'Layanan'],
					],
					[
						['text' => 'Daftar Notifikasi', 'callback_data' => 'Daftar'],
						['text' => 'Daftar Perintah ', 'callback_data' => 'Perintah'],
						
					],
					
				];
				$this->telegram->sendApiKeyboard($chatid, $text, $inkeyboard, true);
				break;
				
			case $pesan == 'Tentang':
				$this->telegram->sendApiAction($chatid);
				$text = "Aplikasi Manajemen Layanan Elektronik Online <strong>(Male_o 1.9)</strong> merupakan layanan kepegawaian berbasis paperless secara Online di era industri 4.0 yang merupakan pendamping Sistem Aplikasi Layanan Kepegawaian (SAPK)";
				$text .="\n";
				$text .="\n";
				$text .= "Ada 7 (Tujuh) Jenis layanan kepegawaian yang dapat dilakukan melalui Aplikasi <strong>Male_o 1.9</strong> adalah sebagai berikut:";
				$text .="\n";
				$text .= "\n1. Pertimbangan teknis pensiun (BUP,Janda/Duda, APS, dan tidak cakap jasmani dan/atau rohani);";
				$text .="\n2. Pertimbangan teknis kenaikan pangkat (Jabatan Struktural, Jabatan Fungsional dan Penyesuaian Ijazah);";
				$text .="\n3. Pertimbangan Teknis Mutasi/Pindah Instansi;";
				$text .="\n4. Ijin penggunaan/pencatuman Gelar/peningkatan pendidikan;";
				$text .="\n5. Kartu Pegawai (KARPEG);";
				$text .="\n6. Kartu isteri/suami; dan";
				$text .="\n7. TASPEN ( SK Janda/Dua/Yatim dan Mutasi/Penambahan Keluarga).";
				$inkeyboard = [
					[
						['text' => 'Tentang Male_o 1.9', 'callback_data' => 'Tentang'],
						['text' => 'Layanan Kepegawaian', 'callback_data' => 'Layanan'],
					],
					[
						['text' => 'Daftar Notifikasi', 'callback_data' => 'Daftar'],
						['text' => 'Daftar Perintah ', 'callback_data' => 'Perintah'],
						
					],
					
				];
				$this->telegram->sendApiKeyboard($chatid, $text, $inkeyboard, true);
				break;
				
			case $pesan == 'Layanan':
				$this->telegram->sendApiAction($chatid);
				$text = "Silahkan kamu pilih menu yang ingin ditanyakan pada keyboard";
				$keyboard = [
					['Bidang Pengangkatan dan Pensiun'],
					['Bidang Mutasi dan Status Kepegawaian'],
					['Bidang Informasi Kepegawaian'],
				];
				$this->telegram->sendApiKeyboard($chatid, $text, $keyboard);
				break;
				
			case $pesan == 'Bidang Pengangkatan dan Pensiun':
				$this->telegram->sendApiAction($chatid);
				$text = "Terimkasih ".$first_name." ".$last_name. ", tentang layanan apa yang ingin kamu tanyakan di Bidang Pengangkatan dan Pensiun ?";
				$keyboard = [
					['Pensiun BUP','Pensiun APS'],
					['Pensiun Janda/Duda'],					
					['Pensiun tidak cakap jasmani dan/atau rohani'],
					['TASPEN','Layanan'],
				];
				$this->telegram->sendApiKeyboard($chatid, $text, $keyboard);
				break;	
				
			case $pesan == 'Pensiun BUP':
				$this->telegram->sendApiAction($chatid);
				$text = "Baiklah,".$first_name." ".$last_name." berikut persyaratan pensiun karena telah mencapai BUP yang kamu tanyakan:";				
				$text .= "\n1. Surat Pengantar dari PPK";
				$text .= "\n2. Surat Permohonan Pensiun YBS";
				$text .= "\n3. Data perorangan calon penerima pensiun (DPCP)";
				$text .= "\n4. Foto Kopi SK CPNS dan PNS";
				$text .= "\n5. Foto Kopi sah SK Pangkat Terakhir";
				$text .= "\n6. Foto Kopi sah surat nikah";
				$text .= "\n7. Foto Kopi sah akte kelahiran anak";
				$text .= "\n8. Surat Keterangan Kematian ";
				$text .= "\n9. Surat Keterangan janda/Duda";
				$text .= "\n10. Foto Kopi Daftar Keluarga";
				$text .= "\n11. Pasphoto 3x4";
				
				$keyboard = [
				    ['Bidang Pengangkatan dan Pensiun'],					
				];
				$this->telegram->sendApiKeyboard($chatid, $text, $keyboard);
				break;
				
			case $pesan == 'Pensiun APS':
				$this->telegram->sendApiAction($chatid);
				$text = "Baiklah,".$first_name." ".$last_name." berikut persyaratan pensiun atas permintaan sendiri yang kamu tanyakan:";				
				$text .= "\n1. Surat Pengantar dari PPK";
				$text .= "\n2. Surat Permohonan Pensiun YBS";
				$text .= "\n3. Data perorangan calon penerima pensiun (DPCP)";
				$text .= "\n4. Foto Kopi SK CPNS dan PNS";
				$text .= "\n5. Foto Kopi sah SK Pangkat Terakhir";
				$text .= "\n6. Foto Kopi sah surat nikah";
				$text .= "\n7. Foto Kopi sah akte kelahiran anak";
				$text .= "\n8. Surat Keterangan Kematian ";
				$text .= "\n9. Surat Keterangan janda/Duda";
				$text .= "\n10. Foto Kopi Daftar Keluarga";
				$text .= "\n11. Pasphoto 3x4";
				
				$keyboard = [
				    ['Bidang Pengangkatan dan Pensiun'],					
				];
				$this->telegram->sendApiKeyboard($chatid, $text, $keyboard);
				break;	
				
			case $pesan == 'Pensiun Janda/Duda':
				$this->telegram->sendApiAction($chatid);
				$text = "Baiklah,".$first_name." ".$last_name." berikut persyaratan pensiun Janda/Duda yang kamu tanyakan :";				
				$text .= "\n1. Surat Pengantar dari PPK";
				$text .= "\n2. Surat Permohonan Pensiun YBS";
				$text .= "\n3. Data perorangan calon penerima pensiun (DPCP)";
				$text .= "\n4. Foto Kopi SK CPNS dan PNS";
				$text .= "\n5. Foto Kopi sah SK Pangkat Terakhir";
				$text .= "\n6. Foto Kopi sah surat nikah";
				$text .= "\n7. Foto Kopi sah akte kelahiran anak";
				$text .= "\n8. Surat Keterangan Kematian ";
				$text .= "\n9. Surat Keterangan janda/Duda";
				$text .= "\n10. Foto Kopi Daftar Keluarga";
				$text .= "\n11. Pasphoto 3x4";
				
				$keyboard = [
				    ['Bidang Pengangkatan dan Pensiun'],					
				];
				$this->telegram->sendApiKeyboard($chatid, $text, $keyboard);
				break;

			case $pesan == 'Pensiun tidak cakap jasmani dan/atau rohani':
				$this->telegram->sendApiAction($chatid);
				$text = "Baiklah,".$first_name." ".$last_name." berikut persyaratan pensiun tidak cakap jasmani dan/atau rohani yang kamu tanyakan :";				
				$text .= "\n1. Surat Pengantar dari PPK";
				$text .= "\n2. Surat Permohonan Pensiun YBS";
				$text .= "\n3. Data perorangan calon penerima pensiun (DPCP)";
				$text .= "\n4. Foto Kopi SK CPNS dan PNS";
				$text .= "\n5. Foto Kopi sah SK Pangkat Terakhir";
				$text .= "\n6. Foto Kopi sah surat nikah";
				$text .= "\n7. Foto Kopi sah akte kelahiran anak";
				$text .= "\n8. Surat Keterangan Kematian ";
				$text .= "\n9. Surat Keterangan janda/Duda";
				$text .= "\n10. Foto Kopi Daftar Keluarga";
				$text .= "\n11. Pasphoto 3x4";
				
				$keyboard = [
				    ['Bidang Pengangkatan dan Pensiun'],					
				];
				$this->telegram->sendApiKeyboard($chatid, $text, $keyboard);
				break;

             case $pesan == 'TASPEN':
				$this->telegram->sendApiAction($chatid);
				$text = "Terimakasih, ".$first_name." ".$last_name. " silahkan kamu pilih menu pada keyboard dibawah untuk melihat persyaratan berkas TASPEN :";
				$keyboard = [
					['SK Janda/Duda/Yatim'],
					['Penambahan Keluarga'],
					['Bidang Pengangkatan dan Pensiun'],
				];
				$this->telegram->sendApiKeyboard($chatid, $text, $keyboard);	
                break;	
			 
			case $pesan == 'SK Janda/Duda/Yatim':
				$this->telegram->sendApiAction($chatid);
				$text = "Baiklah,".$first_name." ".$last_name." berikut persyaratan berkas TASPEN SK Janda/Duda/Yatim yang kamu tanyakan :";				
				$text .="\n1. Membawa asli SK Pensiun almarhum/almarhumah";
				$text .="\n2. Fotokopi Surat Nikah dilegalisir oleh KUA";
				$text .="\n3. Fotokopi Surat Kematian sebanyak 1 lembar yang dilegalisir oleh Lurah";
				$text .="\n4. Daftar Keluarga / SPTB yang telah disahkan oleh Lurah / Kades";
				$text .="\n5. Surat Keterangan Janda / Duda dari Lurah / Kades";
				$text .="\n6. Surat kelahiran bagi anak yang lahir setelah pensiun";
				$text .="\n7. Surat keterangan anak belum bekerja dan belum menikah yang disahkan oleh Lurah / Kades (bagi Yatim-Piatu)";				
				$text .="\n8. Surat Perwalian dari Pengadilan Negeri (bagi wali anak Yatim-Piatu)";
				$text .="\n9. Fotokopi Piagam Penghargaan bagi Pensiun ABRI (Bintang Gerilya, Sewindu, dan Bintang Angkatan) disahkan Kepala Ajendan bagi yang belum tercantum dalam SKEP Pensiun khusus TNI AD";
				$text .="\n10. Surat Keterangan tempat tinggal terakhir dan fotokopi KTP";
				$text .="\n11. Pas foto terbaru tanpa tutup kepala dan kacamata ukuran 4x6 sebanyak 7 lembar (bagi ABRI 15 lembar)";
				$text .="\n12. Struk penerimaan pensiun terakhir (Carik Dapem / Karip)";
				
				$keyboard = [
					['TASPEN'],
				];
				$this->telegram->sendApiKeyboard($chatid, $text, $keyboard);	
                break;					
			
			case $pesan == 'Penambahan Keluarga':
				$this->telegram->sendApiAction($chatid);
				$text = "Baiklah,".$first_name." ".$last_name." berikut persyaratan berkas TASPEN Penambahan Keluarga yang kamu tanyakan :";				
				$keyboard = [
					['TASPEN'],
				];
				$this->telegram->sendApiKeyboard($chatid, $text, $keyboard);	
                break;		
				
				
			case $pesan == 'Bidang Mutasi dan Status Kepegawaian':
				$this->telegram->sendApiAction($chatid);
				$text = "Terimkasih ".$first_name." ".$last_name. ", tentang layanan apa yang ingin kamu tanyakan di Bidang Mutasi dan Status Kepegawaian ?";
				$keyboard = [
					['KENAIKAN PANGKAT','MUTASI/PINDAH'],
					['KARPEG','KARIS/KARSU'],
					['PENINGKATAN PENDIDIKAN'],
					['JKK/JKM','Layanan'],
				];
				$this->telegram->sendApiKeyboard($chatid, $text, $keyboard);
				break;
				
            case $pesan == 'KENAIKAN PANGKAT':
				$this->telegram->sendApiAction($chatid);
				$text = "Baiklah ".$first_name." ".$last_name. ", tentang Kenaikan Pangkat apa yang ingin kamu tanyakan ?";
				$keyboard = [
				    ['KP Reguler','KP Jabatan Struktural'],
					['KP Jabatan Fungsional','Penyesuaian Ijazah'],					
					['Bidang Mutasi dan Status Kepegawaian'],
				];
				$this->telegram->sendApiKeyboard($chatid, $text, $keyboard);
				break;	

			case $pesan == 'KP Reguler':
				$this->telegram->sendApiAction($chatid);
				$text = "Terimakasih ".$first_name." ".$last_name." berikut saya informasikan persyaratan berkas kenaikan Pangkat Reguler :";
				$text .= "\n1. Sudah 4 tahun dalam pangkat terakhir";
				$text .= "\n2. Foto kopi SK terakhir (legalisir)";
				$text .= "\n3. SKP, Capaian SKP (Penilaian Prestasi Kerja 2 Tahun terakhir sekurang-kurangnya bernilai baik)";
				$keyboard = [
				    ['KENAIKAN PANGKAT'],					
				];
				$this->telegram->sendApiKeyboard($chatid, $text, $keyboard);
				break;
				
			case $pesan == 'KP Jabatan Struktural':
				$this->telegram->sendApiAction($chatid);
				$text = "Terimakasih ".$first_name." ".$last_name." berikut saya informasikan persyaratan berkas kenaikan Pangkat Jabatan Struktural :";
				$text .= "\n1. Sudah 4 tahun dalam pangkat terakhir";
				$text .= "\n2. Foto kopi SK terakhir (legalisir)";
				$text .= "\n3. Foto kopi SK Jabatan yang telah dilegalisir";
				$text .= "\n4. Foto kopi SK Pelantikan";
				$text .= "\n5. SPMT (Surat Perintah Melaksanakan Tugas)";
				$text .= "\n6. SKP, Capaian SKP (Penilaian Prestasi Kerja 2 Tahun terakhir sekurang-kurangnya bernilai baik)";
				$keyboard = [
				    ['KENAIKAN PANGKAT'],					
				];
				$this->telegram->sendApiKeyboard($chatid, $text, $keyboard);
				break;		
			
			case $pesan == 'KP Jabatan Fungsional':
				$this->telegram->sendApiAction($chatid);
				$text = "Terimakasih ".$first_name." ".$last_name." berikut saya informasikan persyaratan berkas kenaikan Pangkat Jabatan Fungsional :";
				$text .= "\n1. Asli Surat Pengantar dari Instansi/BKD";
				$text .= "\n2. Foto copy SK Pangkat Terakhir yang dilegalisir";
				$text .= "\n3. Foto copy SKP 2 Tahun terakhir yang dilegalisir";
			    $text .= "\n4. Foto copy STTB/Ijazah yang telah dilegalisir kecuali ada peningkatan pendidikan";
				$text .= "\n5. Asli Penilaian Angka Kredit (PAK)";
				$text .= "\n6. Foto copy SK Jabatan Fungsional Tertentu yang telah dilegalisir";
				$text .= "\n7. Foto copy Surat Tanda Lulus Diklat Jabatan Fungsional tingkat terampil/ahli yang dilegalisir";
				
				$keyboard = [
				    ['KENAIKAN PANGKAT'],					
				];
				$this->telegram->sendApiKeyboard($chatid, $text, $keyboard);
				break;
				
			case $pesan == 'Penyesuaian Ijazah':
				$this->telegram->sendApiAction($chatid);
				$text = "Terimakasih ".$first_name." ".$last_name." berikut saya informasikan persyaratan berkas penyesuain ijazah :";
				$text .= "\n1. Foto kopi Surat Ijin Belajar/Tugas Belajar";
				$text .= "\n2. Foto kopi Ijazah dan Transkrip Nilai yang telah dilegalisir";
				$text .= "\n3. Uraian Tugas ditandatangani serendah-rendahnya Eselon II";
				$text .= "\n4. Surat Tanda Lulus Kenaikan Pangkat Penyesuaian Ijazah (STLKPPI)";
				$text .= "\n5. Sasaran Kerja Pegawai (SKP) 1 tahun terakhir";
				$text .= "\n6. Foto kopi SK KP terakhir";
				$text .= "\n7. Pangkalan data dikti";
				$text .= "\n8. SK Pembebasan dalam jabatan apabila yang bersangkutan menduduki jabatan fungsional tertentu atau jabatan struktural";
								
				$keyboard = [
				    ['KENAIKAN PANGKAT'],					
				];
				$this->telegram->sendApiKeyboard($chatid, $text, $keyboard);
				break;
				
			case $pesan == 'MUTASI/PINDAH':
				$this->telegram->sendApiAction($chatid);
				$text = "Terimakasih ".$first_name." ".$last_name." berikut saya informasikan persyaratan berkas Mutasi/Pindah Instansi :";
				$text .= "\n1. Surat persetujuan pindah dari pejabat Pembina kepegawaian pemerintah pusat maupun pemerintah daerah propinsi/kab/kota dan instansi asal ";
				$text .= "\n2. Surat persetujuan menerima dari pejabat Pembina kepegawaian pemerintah pusat maupun pemerintah daerah propinsi/kab/kota dan instansi yang dituju";
				$text .= "\n3. Surat pengantar dari pemerintah pusat maupun pemerintah daerah propinsi PNS yang dituju (minimal pejabat Pembina kepegawaian Gubernur/Menteri)";
				$text .= "\n4. Foto copi SK.terahkir";
				$text .= "\n5. Foto copi SKP 2 thn terahkir";
				$text .= "\n6. Surat penyataan tidak pernah dijatuhi hukuman disiplin";
				
				$keyboard = [
				    ['Bidang Mutasi dan Status Kepegawaian'],					
				];
				$this->telegram->sendApiKeyboard($chatid, $text, $keyboard);
				break;	
			
			case $pesan == 'KARPEG':
				$this->telegram->sendApiAction($chatid);
				$text = "Terimakasih ".$first_name." ".$last_name." berikut saya informasikan persyaratan berkas pembuatan KARPEG :";
				$text .= "\n1. Surat Pengantar dari unit kerja (asli) ";
				$text .= "\n2. Fotocopy SK Pengangkatan sebagai CPNS dilegalisir";
				$text .= "\n3. Fotocopy SK Pengangkatan sebagai PNS dilegalisir";
				$text .= "\n4. Fotocopy STTPL Prajabatan dilegalisir";
				$text .= "\n5. Pasfoto (berwarna atau hitam putih ) ukr 3x4 = 2 lembar";
				$text .= "\n";
				$text .="\nUntuk yang hilang : syarat diatas berlaku lagi ditambah surat keterangan hilang mengetahui atasan langsung dan surat leporan kehilangan dari kepolisian (asli)";
				$keyboard = [
				    ['Bidang Mutasi dan Status Kepegawaian'],					
				];
				$this->telegram->sendApiKeyboard($chatid, $text, $keyboard);
				break;	
				
            case $pesan == 'KARIS/KARSU':
				$this->telegram->sendApiAction($chatid);
				$text = "Terimakasih ".$first_name." ".$last_name." berikut saya informasikan persyaratan berkas pembuatan KARIS/KARSU :";
				$text .= "\n1. Surat Pengantar dari Unit Kerja(asli) ";
				$text .= "\n2. Fotocopy SK Pengangkatan sebagai CPNS (dilegalisir)";
				$text .= "\n3. Fotocopy SK Pengangkatan sebagai PNS (dilegalisir)";
				$text .= "\n4. Laporan perkawinan pertama seperti contoh pada : Surat edaran Ka BAKN Nomor :08/SE/1983 (asli)";
				$text .= "\n5. Daftar keluarga tanda tangan ybs (asli)";
				$text .= "\n6. Kartu Keluarga (legalisir)";
				$text .= "\n7. Pas Foto berwarna atau hitam putih ukuran 3x4 = 2 lembar";
				$text .= "\n8. Buku Nikah (legalisir)";
				$text .= "\n";
				$text .="\nUntuk yang hilang : syarat diatas berlaku lagi ditambah surat keterangan hilang mengetahui atasan langsung dan surat leporan kehilangan dari kepolisian (asli)";
				
				$keyboard = [
				    ['Bidang Mutasi dan Status Kepegawaian'],					
				];
				$this->telegram->sendApiKeyboard($chatid, $text, $keyboard);
				break;
				
            case $pesan == 'PENINGKATAN PENDIDIKAN':
				$this->telegram->sendApiAction($chatid);
				$text = "Terimakasih ".$first_name." ".$last_name." berikut saya informasikan persyaratan berkas peningkatan pendidikan/pencatuman gelar :";
				
				$keyboard = [
				    ['Bidang Mutasi dan Status Kepegawaian'],					
				];
				$this->telegram->sendApiKeyboard($chatid, $text, $keyboard);
				break;	
			
			case $pesan == 'JKK/JKM':
				$this->telegram->sendApiAction($chatid);
				$text = "Terimakasih ".$first_name." ".$last_name." berikut saya informasikan persyaratan berkas JKK/JKM :";
				
				$keyboard = [
				    ['Bidang Mutasi dan Status Kepegawaian'],					
				];
				$this->telegram->sendApiKeyboard($chatid, $text, $keyboard);
				break;
			
			case $pesan == 'Bidang Informasi Kepegawaian':
				$this->telegram->sendApiAction($chatid);
				$text = "Terimkasih ".$first_name." ".$last_name. ", tentang layanan apa yang ingin kamu tanyakan di Bidang Informasi Kepegawaian ?";
				$keyboard = [
					['PEREMAJAAN DATA','SALINAN SAH ARSIP'],
					['PERBAIKAN SK','CETAK BARU SK'],					
					['Layanan'],
				];
				$this->telegram->sendApiKeyboard($chatid, $text, $keyboard);
				break;	
				
			 case $pesan == 'PEREMAJAAN DATA':
				$this->telegram->sendApiAction($chatid);
				$text = "Baiklah ".$first_name." ".$last_name. ", tentang peremajaan data apa yang ingin kamu tanyakan ?";
				$keyboard = [
				    ['Pendidikan', 'Golongan/Ruang'],
					['Pindah Instansi'],					
					['Bidang Informasi Kepegawaian'],
				];
				$this->telegram->sendApiKeyboard($chatid, $text, $keyboard);
				break;	

			case $pesan == 'Pendidikan':
				$this->telegram->sendApiAction($chatid);
				$text = "Terimakasih ".$first_name." ".$last_name." berikut saya informasikan persyaratan berkas peremajaan data pendidikan :";
				$text .= "\n1. Asli Surat Pengantar";
				$text .= "\n2. Foto copy SK KP yang disahkan *";
				$text .= "\n3. Foto copy Ijazah yang disahkan **";
				$text .= "\n";
				$text .= "\n * TMT KP sebelum 01-20-2011";
				$text .= "\n ** Pendidikan pda SK KP sesuai Ijazah";
				
				$keyboard = [
					['PEREMAJAAN DATA'],
				];
				$this->telegram->sendApiKeyboard($chatid, $text, $keyboard);
				break;
				
			case $pesan == 'Golongan/Ruang':
				$this->telegram->sendApiAction($chatid);
				$text = "Terimakasih ".$first_name." ".$last_name." berikut saya informasikan persyaratan berkas peremajaan data Golongan/Ruang :";
				$text .= "\n1. Asli Surat Pengantar";
				$text .= "\n2. Foto copy SK KP yang disahkan";				
				
				$keyboard = [
					['PEREMAJAAN DATA'],
				];
				$this->telegram->sendApiKeyboard($chatid, $text, $keyboard);
				break;	
			
			case $pesan == 'Pindah Instansi':
				$this->telegram->sendApiAction($chatid);
				$text = "Persyaratan berkas peremajaan data Pindah Instansi adalah : ";
				$text .= "\n1. Asli Surat Pengantar";
				$text .= "\n2. Foto copy SK Pindah Instansi yang disahkan *";
				$text .= "\n";				
				$text .= "\n * TMT KP sebelum 01-20-2011";
				$keyboard = [
					['PEREMAJAAN DATA'],
				];
				$this->telegram->sendApiKeyboard($chatid, $text, $keyboard);
				break;
				
			case $pesan == 'SALINAN SAH ARSIP':
				$this->telegram->sendApiAction($chatid);
				$text = "Terimakasih ".$first_name." ".$last_name." berikut saya informasikan persyaratan permintaan salinan sah arsip :";
				$text .= "\n1. Asli Surat Pengantar";				
				$keyboard = [
					['Bidang Informasi Kepegawaian'],
				];
				$this->telegram->sendApiKeyboard($chatid, $text, $keyboard);
				break;

			case $pesan == 'PERBAIKAN SK':
				$this->telegram->sendApiAction($chatid);
				$text = "Terimakasih ".$first_name." ".$last_name." berikut saya informasikan persyaratan perbaikan SK Konversi NIP :";
				$text .= "\n1. Asli Surat Pengantar";	
				$text .= "\n2. Foto copy SK CPNS yang telah disahkan";	
				$text .= "\n3. Asli Konversi NIP yang salah";					
				$keyboard = [
					['Bidang Informasi Kepegawaian'],
				];
				$this->telegram->sendApiKeyboard($chatid, $text, $keyboard);
				break;
				
			case $pesan == 'CETAK BARU SK':
				$this->telegram->sendApiAction($chatid);
				$text = "Terimakasih ".$first_name." ".$last_name." berikut saya informasikan persyaratan cetak baru SK Konversi NIP :";
				$text .= "\n1. Asli Surat Pengantar";	
				$text .= "\n2. Foto copy SK CPNS yang telah disahkan";	
				$text .= "\n3. Surat dari BKN Pusat (optional)";					
				$keyboard = [
					['Bidang Informasi Kepegawaian'],
				];
				$this->telegram->sendApiKeyboard($chatid, $text, $keyboard);
				break;	
							
			case $pesan == 'Daftar':
				$this->telegram->sendApiAction($chatid);
				$text = "Untuk menerima notifikasi silahkan mendaftar dengan format :<strong>REG NIP</strong>";
				$this->telegram->sendApiMsg($chatid, $text , false, 'HTML');
				break;	
				
			case preg_match("/REG(.*)/", $pesan, $hasil):
			    $this->_setTelegramAkun($message,$hasil);			    
				break;

			case preg_match("/^APPROVE (.*)/", $pesan, $hasil):
			    $this->_ApproveMember($message,$hasil);			    
				break;
				
			case preg_match("/^APPROVEKASUB (.*)/", $pesan, $hasil):
			    $this->_ApproveMember2($message,$hasil);			    
				break;
				
			case preg_match("/AKTIF(.*)/", $pesan, $hasil):
			    $this->_AktifMember($message,$hasil);			    
				break;
			
			case preg_match("/BLOK(.*)/", $pesan, $hasil):
			    $this->_BlokMember($message,$hasil);			    
				break;
				
			case preg_match("/ADMIN(.*)/", $pesan, $hasil):
			    $this->_AdminMember($message,$hasil);			    
				break;
				
			case preg_match("/NONADM(.*)/", $pesan, $hasil):
			    $this->_NonadminMember($message,$hasil);			    
				break;
				
			case preg_match("/RESET(.*)/", $pesan, $hasil):
			    $this->_ResetMember($message,$hasil);			    
				break;	
				
			case preg_match("/^CEK (.*)/", $pesan, $hasil):
			    $this->_cekUsul($message,$hasil);			    
				break;
							
			case preg_match("/DETAIL(.*) ([0-9]*)/", $pesan, $hasil):
			    $this->_detailUsul($message,$hasil);			    
				break;
				
			case preg_match("/^CEKTASPEN (.*)/", $pesan, $hasil):
			    $this->_cekUsulTaspen($message,$hasil);			    
				break;	
				
			case preg_match("/DTASPEN(.*) ([0-9]*)/", $pesan, $hasil):
			    $this->_detailUsulTaspen($message,$hasil);			    
				break;		
			
			case $pesan == '/keyboard':
				$this->telegram->sendApiAction($chatid);
				
				$keyboard = [
					['tombol 1', 'tombol 2'],
					['/keyboard', '/inline'],
					['/hide'],
				];
				$this->telegram->sendApiKeyboard($chatid, 'tombol pilihan', $keyboard);
				break;
			
			case $pesan == '/inline':
				$this->telegram->sendApiAction($chatid);
				$inkeyboard = [
					[
						['text' => 'Update 1', 'callback_data' => 'data update 1'],
						['text' => 'Update 2', 'callback_data' => 'data update 2'],
					],
					[
						['text' => 'keyboard on', 'callback_data' => '/keyboard'],
						['text' => 'keyboard inline', 'callback_data' => '/inline'],
					],
					[
						['text' => 'keyboard off', 'callback_data' => '!hide'],
					],
				];
				$this->telegram->sendApiKeyboard($chatid, 'Tampilan Inline', $inkeyboard, true);
				break;
			
			case $pesan == '/hide':
				$this->telegram->sendApiAction($chatid);
				
				$this->telegram->sendApiHideKeyboard($chatid, 'keyboard off');
				break;
			
			case preg_match("/\/echo (.*)/", $pesan, $hasil):
				$this->telegram->sendApiAction($chatid);
				
				$text = '*Echo:* '.$hasil[1];
				$this->telegram->sendApiMsg($chatid, $text, false, 'Markdown');
				break;
			
			default:
				// code...
				break;
		}
	}
	
	function _cekUsulTaspen($data,$hasil)
	{
		$listUsul 		= $this->bot->cekTaspen($data,$hasil);
		$pesan 			= $data['text'];
		$chatid 		= $data['chat']['id'];
		$fromid 		= $data['from']['id'];
		$first_name 	= $data['from']['first_name'];
		$last_name  	= $data['from']['last_name'];
		
		if($listUsul->num_rows() > 0)
		{	
	        $text = "Terimakasih, <strong>".$first_name ." ".$last_name. "</strong> berikut daftar usul TASPEN dengan NIP : ".trim($hasil[1]);
			$i = 0;
			$text .= "\n Silahkan pilih nomor usul pada tombol dibawah untuk melihat detailnya";
			// send to telegram API
			$this->telegram->sendApiAction($chatid);
						
			foreach($listUsul->result() as $value)
			{
				$inkeyboard[] = array(
					array(
						'text' => $value->nomor_usul, 'callback_data' => 'DTASPEN '.$value->nip.' '.$value->usul_id,
			        )
				);
				
				$i++;
			}
			$this->telegram->sendApiKeyboard($chatid, $text, $inkeyboard, true);
		}
		else
		{
            $text = "Maaf,<strong>".$first_name ." ".$last_name. "</strong> NIP : <strong>".trim($hasil[1])."</strong> tidak ada dalam usul TASPEN";
            $this->telegram->sendApiMsg($chatid, $text , false, 'HTML');
		}	
		
	}
	
	function _detailUsulTaspen($data,$hasil)
	{
		$detailUsul 	= $this->bot->detailUsulTaspen($data,$hasil);
		$pesan 			= $data['text'];
		$chatid 		= $data['chat']['id'];
		$fromid 		= $data['from']['id'];
		$first_name 	= $data['from']['first_name'];
		$last_name  	= $data['from']['last_name'];
		
		
		if($detailUsul->num_rows() > 0)
		{	
	        $row  = $detailUsul->row();
			$text = "Berikut <strong>Male_o 1.9</strong> kasih kamu detail usulnya :" ;
			// send to telegram API
			$this->telegram->sendApiAction($chatid);
			$text .= "\n Nomor Usul : ".$row->nomor_usul;
			$text .= "\n Layanan	: ".$row->layanan_nama;
			$text .= "\n NIP 		: ".$row->nip;
			$text .= "\n Nama PNS		: ".$row->nama_pns;
			($row->layanan_id == 16 || $row->layanan_id == 17 ? $text .= "\n Nama JD/YT		: ".$row->nama_janda_duda : '');
			$text .= "\n Status		: ".$row->usul_status;
			$text .= "\n Tahapan	: ".$row->tahapan_nama;			
			$text .= "\n Keterangan	: ".$row->usul_alasan;
		}
		else
		{
            $text = "Maaf, <strong>".$first_name ." ".$last_name." data tersebut tidak ada dalam detail usul";
      	}	
		
		$this->telegram->sendApiMsg($chatid, $text , false, 'HTML');
	}	
	
	
	function _detailUsul($data,$hasil)
	{
		$detailUsul 	= $this->bot->detailUsul($data,$hasil);
		$pesan 			= $data['text'];
		$chatid 		= $data['chat']['id'];
		$fromid 		= $data['from']['id'];
		$first_name 	= $data['from']['first_name'];
		$last_name  	= (!empty($data['from']['last_name']) ? $data['from']['last_name'] : '') ;
		
		if($detailUsul->num_rows() > 0)
		{	
	        $row  = $detailUsul->row();
			$text = "Berikut <strong>Male_o 1.9</strong> kasih kamu detail usulnya :" ;
			// send to telegram API
			$this->telegram->sendApiAction($chatid);
			$text .= "\n Nomor Usul : ".$row->agenda_nousul;
			$text .= "\n Layanan	: ".$row->layanan_nama;
			$text .= "\n NIP 		: ".$row->nip;
			$text .= "\n Nama 		: ".$row->PNS_GLRDPN.' '.$row->PNS_PNSNAM.' '.$row->PNS_GLRBLK;
			$text .= "\n Status		: ".$row->nomi_status;
			$text .= "\n Tahapan	: ".$row->tahapan_nama;			
			$text .= "\n Keterangan	: ".$row->nomi_alasan;
		}
		else
		{
            $text = "Maaf, <strong>".$first_name ." ".$last_name." data tersebut tidak ada dalam detail usul";
      	}	
		
		$this->telegram->sendApiMsg($chatid, $text , false, 'HTML');
	}	
	
	
	function _cekUsul($data,$hasil)
	{
		$listUsul 		= $this->bot->cekUsul($data,$hasil);
		$pesan 			= $data['text'];
		$chatid 		= $data['chat']['id'];
		$fromid 		= $data['from']['id'];
		$first_name 	= $data['from']['first_name'];
		$last_name  	= (!empty($data['from']['last_name']) ? $data['from']['last_name'] : '');
		
		if($listUsul->num_rows() > 0)
		{	
	        $text = "Terimakasih, <strong>".$first_name ." ".$last_name. "</strong> berikut daftar usul untuk NIP : ".trim($hasil[1]);
			$i = 0;
			$text .= "\n Silahkan pilih nomor usul pada tombol dibawah untuk melihat detailnya";
			// send to telegram API
			$this->telegram->sendApiAction($chatid);
						
			foreach($listUsul->result() as $value)
			{
				$inkeyboard[] = array(
					array(
						'text' => $value->agenda_nousul, 'callback_data' => 'DETAIL '.$value->nip.' '.$value->agenda_id,
			        )
				);
				
				$i++;
			}
			$this->telegram->sendApiKeyboard($chatid, $text, $inkeyboard, true);
		}
		else
		{
            $text = "Maaf,<strong>".$first_name ." ".$last_name. "</strong> NIP : <strong>".trim($hasil[1])."</strong> tidak ada dalam usul";
            $this->telegram->sendApiMsg($chatid, $text , false, 'HTML');
		}	
		
	}	
	
	function _ResetMember($data,$hasil)
	{
		$result 		= $this->bot->ResetMember($data,$hasil);
		$pesan 			= $data['text'];
		$chatid 		= $data['chat']['id'];
		$fromid 		= $data['from']['id'];
		$first_name 	= $data['from']['first_name'];
		$last_name  	= $data['from']['last_name'];
		
		$response 		= $result['response'];
		
		if($response)
		{	
			$this->telegram->sendApiAction($chatid);
			$text = "Terimkasih <strong>".$first_name ." ".$last_name. "</strong>,".$result['pesan'];
			$this->telegram->sendApiMsg($chatid, $text , false, 'HTML');
		}
		else
		{
			$this->telegram->sendApiAction($chatid);
			$text = "Maaf  <strong>".$first_name ." ".$last_name. "</strong>,".$result['pesan'];
			$this->telegram->sendApiMsg($chatid, $text , false, 'HTML');
		}
	}	
	
	function _NonadminMember($data,$hasil)
	{
		$result 		= $this->bot->NonadminMember($data,$hasil);
		$pesan 			= $data['text'];
		$chatid 		= $data['chat']['id'];
		$fromid 		= $data['from']['id'];
		$first_name 	= $data['from']['first_name'];
		$last_name  	= $data['from']['last_name'];
		
		$response 		= $result['response'];
		
		if($response)
		{	
			$this->telegram->sendApiAction($chatid);
			$text = "Terimkasih <strong>".$first_name ." ".$last_name. "</strong>,".$result['pesan'];
			$this->telegram->sendApiMsg($chatid, $text , false, 'HTML');
		}
		else
		{
			$this->telegram->sendApiAction($chatid);
			$text = "Maaf  <strong>".$first_name ." ".$last_name. "</strong>,".$result['pesan'];
			$this->telegram->sendApiMsg($chatid, $text , false, 'HTML');
		}
	}	
	
	function _AdminMember($data,$hasil)
	{
		$result 		= $this->bot->AdminMember($data,$hasil);
		$pesan 			= $data['text'];
		$chatid 		= $data['chat']['id'];
		$fromid 		= $data['from']['id'];
		$first_name 	= $data['from']['first_name'];
		$last_name  	= $data['from']['last_name'];
		
		$response 		= $result['response'];
		
		if($response)
		{	
			$this->telegram->sendApiAction($chatid);
			$text = "Terimkasih <strong>".$first_name ." ".$last_name. "</strong>,".$result['pesan'];
			$this->telegram->sendApiMsg($chatid, $text , false, 'HTML');
		}
		else
		{
			$this->telegram->sendApiAction($chatid);
			$text = "Maaf  <strong>".$first_name ." ".$last_name. "</strong>,".$result['pesan'];
			$this->telegram->sendApiMsg($chatid, $text , false, 'HTML');
		}
	}	

	function _BlokMember($data,$hasil)
	{
		$result 		= $this->bot->BlokMember($data,$hasil);
		$pesan 			= $data['text'];
		$chatid 		= $data['chat']['id'];
		$fromid 		= $data['from']['id'];
		$first_name 	= $data['from']['first_name'];
		$last_name  	= $data['from']['last_name'];
		
		$response 		= $result['response'];
		
		if($response)
		{	
			$this->telegram->sendApiAction($chatid);
			$text = "Terimkasih <strong>".$first_name ." ".$last_name. "</strong>,".$result['pesan'];
			$this->telegram->sendApiMsg($chatid, $text , false, 'HTML');
		}
		else
		{
			$this->telegram->sendApiAction($chatid);
			$text = "Maaf  <strong>".$first_name ." ".$last_name. "</strong>,".$result['pesan'];
			$this->telegram->sendApiMsg($chatid, $text , false, 'HTML');
		}
	}	
	
	function _AktifMember($data,$hasil)
	{
		$result 		= $this->bot->AktifMember($data,$hasil);
		$pesan 			= $data['text'];
		$chatid 		= $data['chat']['id'];
		$fromid 		= $data['from']['id'];
		$first_name 	= $data['from']['first_name'];
		$last_name  	= $data['from']['last_name'];
		
		$response 		= $result['response'];
		
		if($response)
		{	
			$this->telegram->sendApiAction($chatid);
			$text = "Terimkasih <strong>".$first_name ." ".$last_name. "</strong>,".$result['pesan'];
			$this->telegram->sendApiMsg($chatid, $text , false, 'HTML');
		}
		else
		{
			$this->telegram->sendApiAction($chatid);
			$text = "Maaf  <strong>".$first_name ." ".$last_name. "</strong>,".$result['pesan'];
			$this->telegram->sendApiMsg($chatid, $text , false, 'HTML');
		}
	}
	
	function _ApproveMember($data,$hasil)
	{
		$result 		= $this->bot->ApproveMember($data,$hasil);
		$pesan 			= $data['text'];
		$chatid 		= $data['chat']['id'];
		$fromid 		= $data['from']['id'];
		$first_name 	= $data['from']['first_name'];
		$last_name  	= $data['from']['last_name'];
		
		$response 		= $result['response'];
		
		if($response)
		{	
			$this->telegram->sendApiAction($chatid);
			$text = "Terimkasih <strong>".$first_name ." ".$last_name. "</strong>,".$result['pesan'];
			$this->telegram->sendApiMsg($chatid, $text, false, 'HTML');
		}
		else
		{
			$this->telegram->sendApiAction($chatid);
			$text = "Maaf  <strong>".$first_name ." ".$last_name. " </strong>,".$result['pesan'];
			$this->telegram->sendApiMsg($chatid, $text , false, 'HTML');
		}
	}
	
	function _ApproveMember2($data,$hasil)
	{
		$result 		= $this->bot->ApproveMember2($data,$hasil);
		$pesan 			= $data['text'];
		$chatid 		= $data['chat']['id'];
		$fromid 		= $data['from']['id'];
		$first_name 	= $data['from']['first_name'];
		$last_name  	= $data['from']['last_name'];
		
		$response 		= $result['response'];
		
		if($response)
		{	
			$this->telegram->sendApiAction($chatid);
			$text = "Terimkasih <strong>".$first_name ." ".$last_name. "</strong>,".$result['pesan'];
			$this->telegram->sendApiMsg($chatid, $text, false, 'HTML');
		}
		else
		{
			$this->telegram->sendApiAction($chatid);
			$text = "Maaf  <strong>".$first_name ." ".$last_name. " </strong>,".$result['pesan'];
			$this->telegram->sendApiMsg($chatid, $text , false, 'HTML');
		}
	}
	
	function _setTelegramAkun($data,$hasil)
	{
		$result 		= $this->bot->setTelegramAkun($data,$hasil);
		$pesan 			= $data['text'];
		$chatid 		= $data['chat']['id'];
		$fromid 		= $data['from']['id'];
		$first_name 	= $data['from']['first_name'];
		$last_name  	= $data['from']['last_name'];
		
		$response 		= $result['response'];
		
		if($response)
		{	
			$this->telegram->sendApiAction($chatid);
			$text = "Terimkasih <strong>".$first_name ." ".$last_name. "</strong> dengan NIP <strong>".$hasil[1]." </strong> akun Telegram anda telah kami daftar untuk menerima notifikasi";
			$this->telegram->sendApiMsg($chatid, $text , false, 'HTML');
		}
		else
		{
			$this->telegram->sendApiAction($chatid);
			$text = "Maaf  <strong>".$first_name ." ".$last_name. "</strong> dengan NIP <strong>".$hasil[1]." </strong> akun Telegram anda GAGAL didaftarkan untuk menerima notifikasi";
			$this->telegram->sendApiMsg($chatid, $text , false, 'HTML');
		}
	}	
	
	public function sendMessage_to_telegram_byid($hasil)
	{
	    $nip				= trim($hasil);
		$chatid 			= $this->bot->getTelegramId($nip);
		$this->telegram->sendApiAction($chatid);
		$text = "Tes Kirim Pesan Direct ke Telegram ID : ".$chatid;
		$this->telegram->sendApiMsg($chatid, $text);
		
	}	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */