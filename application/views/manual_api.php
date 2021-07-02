<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Dokumentasi API MALE_O 1.9</title>

	<style type="text/css">

	::selection { background-color: #E13300; color: white; }
	::-moz-selection { background-color: #E13300; color: white; }

	body {
		background-color: #fff;
		margin: 40px;
		font: 13px/20px normal Helvetica, Arial, sans-serif;
		color: #4F5155;
	}

	a {
		color: #003399;
		background-color: transparent;
		font-weight: normal;
	}

	h1 {
		color: #444;
		background-color: transparent;
		border-bottom: 1px solid #D0D0D0;
		font-size: 19px;
		font-weight: normal;
		margin: 0 0 14px 0;
		padding: 14px 15px 10px 15px;
	}

	code {
		font-family: Consolas, Monaco, Courier New, Courier, monospace;
		font-size: 12px;
		background-color: #f9f9f9;
		border: 1px solid #D0D0D0;
		color: #002166;
		display: block;
		margin: 14px 0 14px 0;
		padding: 12px 10px 12px 10px;
	}

	#body {
		margin: 0 15px 0 15px;
	}

	p.footer {
		text-align: right;
		font-size: 11px;
		border-top: 1px solid #D0D0D0;
		line-height: 32px;
		padding: 0 10px 0 10px;
		margin: 20px 0 0 0;
	}

	#container {
		margin: 10px;
		border: 1px solid #D0D0D0;
		box-shadow: 0 0 8px #D0D0D0;
	}
	</style>
</head>
<body>

<div id="container">
	<h1>Dokumentasi API MALE_O 1.9</h1>

	<div id="body">
		<h1>1. LOGIN REQUEST</h1>

		<p>METHOD POST WITH END POINT:</p>
		<code>https://satupintu.my.id/index.php/api/login</code>

		<p>BODY PARAMETER  application/json:</p>
		<code>{
    "username":"19810512201503xxxx",
    "password":"rahasiaHanyaAkuYangTahu"
}</code>

		<p>RESPONSE:</p>
		<code>{
    "message": "success",
    "data": {
        "user_id": "1",
        "nip": "19810512201503xxxx",
        "last_name": "MUHAMAD HOLIK",
        "first_name": "NUR",
        "email": "bkn.xxrmuxx@gmail.com",
        "created_date": "2018-07-19 15:42:09",
        "last_access": "2021-06-30 09:09:04"
    },
    "meta": {
        "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsImFjdGl2ZSI6IjEiLCJ1c2VybmFtZSI6IjE5ODEwNTEyMjAxNTAzMTAwMSIsImluc3RhbnNpI
		joiNDAxMSIsImlhdCI6MTYyNTAyMTk3OSwiZXhwIjoxNjI1MDM5OTc5fQ.xB9r2r5olJwXFiax7h3p-7-1S9pkSWwLwAa1QCE68rg"
    },
    "response": true
}</code>

    <h1>2. UPLOAD DOKUMEN REQUEST</h1>

		<p>METHOD POST WITH END POINT:</p>
		<code>https://satupintu.my.id/index.php/api/uploadDokumen</code>
		
		<p>HEADER PARAMETER:</p>
		<code>Token : eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsImFjdGl2ZSI6IjEiLCJ1c2VybmFtZSI6IjE5ODEwNTEyMjAxNTAzMTAwMSIsImluc3RhbnNpI
		joiNDAxMSIsImlhdCI6MTYyNTAxNTM2MiwiZXhwIjoxNjI1MDMzMzYyfQ.G531nkFF2-cpo_1AWx_kiN4t9es131dVOBbJOQCZO7U</code>
		
		
		<p>BODY PARAMETER multipart/form-data:</p>
		<code>file : SK_CPNS_19900704201903xxxx.pdf</code>
		
		
		<p>RESPONSE:</p>
		<code>{
    "response": true,
    "insert": true,
    "message": "Dokumen Kepegawaian Berhasil Tersimpan"
}</code>

<code>{
    "response": true,
    "update": true,
    "message": "File dokumen kepegawaian sudah ada, overwrite file"
}</code>


    <h1>3. LIST UPLOAD DOKUMEN REQUEST</h1>

		<p>METHOD GET WITH END POINT:</p>
		<code>https://satupintu.my.id/index.php/api/listUploadDokumen</code>
		
		<p>HEADER PARAMETER:</p>
		<code>Token : eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsImFjdGl2ZSI6IjEiLCJ1c2VybmFtZSI6IjE5ODEwNTEyMjAxNTAzMTAwMSIsImluc3RhbnNpI
		joiNDAxMSIsImlhdCI6MTYyNTAxNTM2MiwiZXhwIjoxNjI1MDMzMzYyfQ.G531nkFF2-cpo_1AWx_kiN4t9es131dVOBbJOQCZO7U</code>
		
		
		<p>QUERY PARAMETER:</p>
		<code>nip: 19900704201903xxxx</code>
		
		<p>RESPONSE:</p>
		<code>{
    "response": true,
    "message": "List Of Document Files",
    "size": 7,
    "files": [
        {
            "raw_name": "AKTA_NIKAH_19900704201903xxxx",
            "file_name": "AKTA_NIKAH_19900704201903xxxx.pdf",
            "file_type": "application/pdf",
            "file_size": "310.17",
            "file_ext": ".pdf",
            "nip": "19900704201903xxxx",
            "minor_dok": null,
            "flag_update": null,
            "upload_by": "122",
            "upload_name": "MIRANTI",
            "created_date": "2020-09-22 11:22:45",
            "update_date": "2020-09-22 11:22:45",
            "instansi": "Pemerintah Kab. Bone Bolango",
            "nama_pns": "SULISTIORINY NADJAMUDIN",
            "jenis_dokumen": "AKTA_NIKAH"
        },
        {
            "raw_name": "DAFTAR_KELUARGA_19900704201903xxxx",
            "file_name": "DAFTAR_KELUARGA_1990070420190xxxx.pdf",
            "file_type": "application/pdf",
            "file_size": "115.62",
            "file_ext": ".pdf",
            "nip": "19900704201903xxxx",
            "minor_dok": null,
            "flag_update": null,
            "upload_by": "122",
            "upload_name": "MIRANTI",
            "created_date": "2020-09-22 11:22:40",
            "update_date": "2020-09-22 11:22:40",
            "instansi": "Pemerintah Kab. Bone Bolango",
            "nama_pns": "SULISTIORINY NADJAMUDIN",
            "jenis_dokumen": "DAFTAR_KELUARGA"
        },
        {
            "raw_name": "LAPORAN_PERKAWINAN_19900704201903xxxx",
            "file_name": "LAPORAN_PERKAWINAN_1990070420190xxxx.pdf",
            "file_type": "application/pdf",
            "file_size": "120.08",
            "file_ext": ".pdf",
            "nip": "19900704201903xxxx",
            "minor_dok": null,
            "flag_update": null,
            "upload_by": "122",
            "upload_name": "MIRANTI",
            "created_date": "2020-09-22 11:22:47",
            "update_date": "2020-09-22 11:22:47",
            "instansi": "Pemerintah Kab. Bone Bolango",
            "nama_pns": "SULISTIORINY NADJAMUDIN",
            "jenis_dokumen": "LAPORAN_PERKAWINAN"
        },
        {
            "raw_name": "PERTEK_PMK_19900704201903xxxx",
            "file_name": "PERTEK_PMK_19900704201903xxxx.pdf",
            "file_type": "application/pdf",
            "file_size": "146.74",
            "file_ext": ".pdf",
            "nip": "19900704201903xxxx",
            "minor_dok": null,
            "flag_update": "1",
            "upload_by": "39",
            "upload_name": "RAHMAT ",
            "created_date": "2021-06-30 11:20:19",
            "update_date": "2021-06-30 11:21:47",
            "instansi": "Pemerintah Kab. Bone Bolango",
            "nama_pns": "SULISTIORINY NADJAMUDIN",
            "jenis_dokumen": "PERTEK_PMK"
        },
        {
            "raw_name": "SK_CPNS_19900704201903xxxx",
            "file_name": "SK_CPNS_19900704201903xxxx.pdf",
            "file_type": "application/pdf",
            "file_size": "138.66",
            "file_ext": ".pdf",
            "nip": "19900704201903xxxx",
            "minor_dok": null,
            "flag_update": "1",
            "upload_by": "122",
            "upload_name": "MIRANTI",
            "created_date": "2020-08-04 13:41:10",
            "update_date": "2020-09-22 11:22:52",
            "instansi": "Pemerintah Kab. Bone Bolango",
            "nama_pns": "SULISTIORINY NADJAMUDIN",
            "jenis_dokumen": "SK_CPNS"
        },
        {
            "raw_name": "SK_PNS_19900704201903xxxx",
            "file_name": "SK_PNS_19900704201903xxxx.pdf",
            "file_type": "application/pdf",
            "file_size": "140.1",
            "file_ext": ".pdf",
            "nip": "19900704201903xxxx",
            "minor_dok": null,
            "flag_update": "1",
            "upload_by": "122",
            "upload_name": "MIRANTI",
            "created_date": "2020-08-04 13:41:36",
            "update_date": "2020-09-22 11:22:52",
            "instansi": "Pemerintah Kab. Bone Bolango",
            "nama_pns": "SULISTIORINY NADJAMUDIN",
            "jenis_dokumen": "SK_PNS"
        },
        {
            "raw_name": "STTPL_19900704201903xxxx",
            "file_name": "STTPL_19900704201903xxxx.pdf",
            "file_type": "application/pdf",
            "file_size": "220.79",
            "file_ext": ".pdf",
            "nip": "19900704201903xxxx",
            "minor_dok": null,
            "flag_update": null,
            "upload_by": "122",
            "upload_name": "MIRANTI",
            "created_date": "2020-08-04 13:43:36",
            "update_date": "2020-08-04 13:43:36",
            "instansi": "Pemerintah Kab. Bone Bolango",
            "nama_pns": "SULISTIORINY NADJAMUDIN",
            "jenis_dokumen": "STTPL"
        }
    ]
}</code>

     <h1>4. HAPUS DOKUMEN REQUEST</h1>

		<p>METHOD DELETE WITH END POINT:</p>
		<code>https://satupintu.my.id/index.php/api/hapusDokumen</code>
		
		<p>HEADER PARAMETER:</p>
		<code>Token : eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsImFjdGl2ZSI6IjEiLCJ1c2VybmFtZSI6IjE5ODEwNTEyMjAxNTAzMTAwMSIsImluc3RhbnNpI
		joiNDAxMSIsImlhdCI6MTYyNTAxNTM2MiwiZXhwIjoxNjI1MDMzMzYyfQ.G531nkFF2-cpo_1AWx_kiN4t9es131dVOBbJOQCZO7U</code>
		
		<p>BODY PARAMETER x-www-form-urlencoded:</p>
		<code>name : SK_CPNS_19900704201903xxxx.pdf</code>
		
		<p>RESPONSE</p>
		<code>{
    "response": true,
    "pesan": "File dokumen berhasil dihapus"
}</code>
		
		
		 <h1>5. UPLOAD PHOTO REQUEST</h1>

		<p>METHOD POST WITH END POINT:</p>
		<code>https://satupintu.my.id/index.php/api/uploadPhoto</code>
		
		<p>HEADER PARAMETER:</p>
		<code>Token : eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsImFjdGl2ZSI6IjEiLCJ1c2VybmFtZSI6IjE5ODEwNTEyMjAxNTAzMTAwMSIsImluc3RhbnNpI
		joiNDAxMSIsImlhdCI6MTYyNTAxNTM2MiwiZXhwIjoxNjI1MDMzMzYyfQ.G531nkFF2-cpo_1AWx_kiN4t9es131dVOBbJOQCZO7U</code>
		
		
		<p>BODY PARAMETER multipart/form-data:</p>
		<code>file : KARPEG_19900704201903xxxx.jpeg</code>
		
		<p>RESPONSE:</p>
		<code>{
    "response": true,
    "insert": true,
    "message": "Photo Berhasil Tersimpan"
}</code>
		<code>{
    "response": true,
    "update": true,
    "message": "Photo sudah ada, overwrite photo"
}</code>
		
		
		<h1>6. LIST UPLOAD PHOTO REQUEST</h1>

		<p>METHOD GET WITH END POINT:</p>
		<code>https://satupintu.my.id/index.php/api/listUploadPhoto</code>
		
		<p>HEADER PARAMETER:</p>
		<code>Token : eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsImFjdGl2ZSI6IjEiLCJ1c2VybmFtZSI6IjE5ODEwNTEyMjAxNTAzMTAwMSIsImluc3RhbnNpI
		joiNDAxMSIsImlhdCI6MTYyNTAxNTM2MiwiZXhwIjoxNjI1MDMzMzYyfQ.G531nkFF2-cpo_1AWx_kiN4t9es131dVOBbJOQCZO7U</code>
		
		
		<p>QUERY PARAMETER:</p>
		<code>nip: 19900704201903xxxx</code>
		
		<p>RESPONSE:</p>
		<code>{
    "response": true,
    "message": "List Of Photo Files",
    "size": 2,
    "files": [
        {
            "raw_name": "KARPEG_19900704201903xxxx",
            "file_name": "KARPEG_19900704201903xxxx.jpeg",
            "file_type": "image/jpeg",
            "file_size": "23.56",
            "file_ext": ".jpeg",
            "nip": "19900704201903xxxx",
            "flag_update": null,
            "upload_by": "39",
            "upload_name": "RAHMAT ",
            "created_date": "2021-06-30 12:42:20",
            "update_date": "2021-06-30 12:42:20",
            "instansi": "Pemerintah Kab. Bone Bolango",
            "nama": "SULISTIORINY NADJAMUDIN",
            "layanan_nama": "KARPEG"
        },
        {
            "raw_name": "KARSU_19900704201903xxxx",
            "file_name": "KARSU_19900704201903xxxx.jpg",
            "file_type": "image/jpeg",
            "file_size": "31.93",
            "file_ext": ".jpg",
            "nip": "19900704201903xxxx",
            "flag_update": null,
            "upload_by": "122",
            "upload_name": "MIRANTI",
            "created_date": "2020-09-22 11:23:00",
            "update_date": "2020-09-22 11:23:00",
            "instansi": "Pemerintah Kab. Bone Bolango",
            "nama": "SULISTIORINY NADJAMUDIN",
            "layanan_nama": "KARSU"
        }
    ]
}</code>


    <h1>7. HAPUS PHOTO REQUEST</h1>

		<p>METHOD DELETE WITH END POINT:</p>
		<code>https://satupintu.my.id/index.php/api/hapusPhoto</code>
		
		<p>HEADER PARAMETER:</p>
		<code>Token : eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsImFjdGl2ZSI6IjEiLCJ1c2VybmFtZSI6IjE5ODEwNTEyMjAxNTAzMTAwMSIsImluc3RhbnNpI
		joiNDAxMSIsImlhdCI6MTYyNTAxNTM2MiwiZXhwIjoxNjI1MDMzMzYyfQ.G531nkFF2-cpo_1AWx_kiN4t9es131dVOBbJOQCZO7U</code>
		
		<p>BODY PARAMETER x-www-form-urlencoded:</p>
		<code>name : KARPEG_19900704201903xxxx.jpeg</code>
		
		<p>RESPONSE</p>
		<code>{
    "response": true,
    "pesan": "Photo berhasil dihapus"
}</code>
		
		
		
	</div>

</div>

</body>
</html>