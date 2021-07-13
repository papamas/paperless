<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Dokumentasi API MALE_O 1.9 - TASPEN</title>

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
	<h1>Dokumentasi API MALE_O 1.9 - TASPEN </h1>

	<div id="body">
		<h1>1. LOGIN REQUEST</h1>

		<p>METHOD POST WITH END POINT:</p>
		<code>https://satupintu.my.id/index.php/api/login</code>

		<p>BODY PARAMETER  application/json:</p>
		<code>{
    "username":"3361",
    "password":"IniAdalahRahasiaAku"
}</code>

		<p>RESPONSE:</p>
		<code>{
    "message": "success",
    "data": {
        "user_id": "183",
        "nip": "3361",
        "last_name": "Kumalasari Lessy",
        "first_name": "Vemila",
        "email": "-",
        "created_date": "2020-04-23 07:40:05",
        "last_access": "2021-07-09 03:26:42"
    },
    "meta": {
        "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMTgzIiwiYWN0aXZlIjoiMSIsInVzZXJuYW1lIjoiMzM2MSIsImluc3RhbnNpIjoiOSIsImlhdCI6MTYyNjAxMTI2NCwiZXhwIjoxNjI2MDI5MjY0fQ.q-3pl0uSue8C4YEbPslCNKyUPAZfjaLMlO8ZuFekVAU"
    },
    "response": true
}</code>

    <h1>2. UPLOAD DOKUMEN REQUEST</h1>

        <p>METHOD POST WITH END POINT:</p>
		<code>https://satupintu.my.id/index.php/api/taspenUpload</code>
		
		<p>HEADER PARAMETER:</p>
		<code>Token : eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsImFjdGl2ZSI6IjEiLCJ1c2VybmFtZSI6IjE5ODEwNTEyMjAxNTAzMTAwMSIsImluc3RhbnNpI
		joiNDAxMSIsImlhdCI6MTYyNTAxNTM2MiwiZXhwIjoxNjI1MDMzMzYyfQ.G531nkFF2-cpo_1AWx_kiN4t9es131dVOBbJOQCZO7U</code>
		
		
		<p>BODY PARAMETER multipart/form-data:</p>
		<code>{ "file" : "SURAT_NIKAH.pdf", "jenis" : 1, "nip" : "198105"}</code>
		
		<p>DAFTAR TABEL JENIS DOKUMEN:</p>
		<code>https://drive.google.com/file/d/1Lnw9serYNvcly0qyVwHs2ZQm1Z7TYdlL/view?usp=sharing</code>

		
		<p>RESPONSE:</p>
		<code>{
    "response": true,
    "insert": true,
    "message": "File Berhasil Tersimpan",
    "file_name": "SURAT_NIKAH_198105.pdf",
    "file_type": "application/pdf",
    "file_size": 146.74,
    "file_ext": ".pdf",
    "is_image": false
}</code>

<code>{
    "response": true,
    "update": true,
    "message": "File telah ada, overwrite file",
    "file_name": "SURAT_NIKAH_198105.pdf",
    "file_type": "application/pdf",
    "file_size": 146.74,
    "file_ext": ".pdf",
    "is_image": false
}</code>


    <h1>3. LIST UPLOAD DOKUMEN REQUEST</h1>

		<p>METHOD GET WITH END POINT:</p>
		<code>https://satupintu.my.id/index.php/api/taspenUpload</code>
		
		<p>HEADER PARAMETER:</p>
		<code>Token : eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsImFjdGl2ZSI6IjEiLCJ1c2VybmFtZSI6IjE5ODEwNTEyMjAxNTAzMTAwMSIsImluc3RhbnNpI
		joiNDAxMSIsImlhdCI6MTYyNTAxNTM2MiwiZXhwIjoxNjI1MDMzMzYyfQ.G531nkFF2-cpo_1AWx_kiN4t9es131dVOBbJOQCZO7U</code>
		
		
		<p>QUERY PARAMETER:</p>
		<code>{ "nip": "198105" }</code>
		
		<p>RESPONSE:</p>
		<code>{
    "response": true,
    "message": "List Of Files",
    "size": 2,
    "files": [
        {
            "raw_name": "SURAT_NIKAH_198105",
            "file_name": "SURAT_NIKAH_198105.pdf",
            "file_type": "application/pdf",
            "file_size": "146.74",
            "file_ext": ".pdf",
            "nip": "198105",
            "minor_dok": null,
            "flag_update": "1",
            "upload_by": "183",
            "upload_name": "Vemila",
            "created_date": "2021-07-11 21:10:06",
            "update_date": "2021-07-11 21:10:59",
            "nama_dokumen": "SURAT_NIKAH",
            "keterangan": "ASLI SURAT NIKAH LEGALISIR KAU/LURAH",
            "nama_pns": null
        },
        {
            "raw_name": "PHOTO_198105",
            "file_name": "PHOTO_198105.jpeg",
            "file_type": "image/jpeg",
            "file_size": "65.97",
            "file_ext": ".jpeg",
            "nip": "198105",
            "minor_dok": null,
            "flag_update": null,
            "upload_by": "183",
            "upload_name": "Vemila",
            "created_date": "2021-07-11 21:09:43",
            "update_date": "2021-07-11 21:09:43",
            "nama_dokumen": "PHOTO",
            "keterangan": "PAS PHOTO UKURAN 4X6 TERBARU TANPA TUTUP KEPALA DAN KACAMATA",
            "nama_pns": null
        }
    ]
}</code>

     <h1>4. HAPUS DOKUMEN REQUEST</h1>

		<p>METHOD DELETE WITH END POINT:</p>
		<code>https://satupintu.my.id/index.php/api/taspenUpload</code>
		
		<p>HEADER PARAMETER:</p>
		<code>Token : eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiMSIsImFjdGl2ZSI6IjEiLCJ1c2VybmFtZSI6IjE5ODEwNTEyMjAxNTAzMTAwMSIsImluc3RhbnNpI
		joiNDAxMSIsImlhdCI6MTYyNTAxNTM2MiwiZXhwIjoxNjI1MDMzMzYyfQ.G531nkFF2-cpo_1AWx_kiN4t9es131dVOBbJOQCZO7U</code>
		
		<p>BODY PARAMETER x-www-form-urlencoded:</p>
		<code>  { "name" : "SURAT_NIKAH_198105.pdf" }</code>
		
		<p>RESPONSE</p>
		<code>{
    "response": true,
    "pesan": "File berhasil dihapus"
}</code>
		
		
		
		
		
		
	</div>

</div>

</body>
</html>