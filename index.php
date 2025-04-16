<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php session_start();
	// Require class database
	//require_once(__DIR__ . '/lib/db.class.php');
	//$databaseClass = new DB();

	// Ambil header
	include("./header.php");
	require_once(__DIR__ . '/lib/db.class.php');
	require_once 'bootstrap.php';
	$databaseClass = new DB();
	?>
	<div class="container document">
    	<div class="row">
	    	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	    		<?php 
	    		// Tampilkan informasi jika ada!
	    		if (isset($_SESSION["informasi_formulir"])) {
	    			echo "<div class='alert alert-info text-danger'>".$_SESSION["informasi_formulir"]."</div>";
	    			unset($_SESSION["informasi_formulir"]);
	    		}

				// Ambil tahun ajaran id
				$query = "SELECT ta_id, tahun_ajaran FROM psb_tahun_ajaran WHERE aktif = 'yes' ORDER BY ta_id DESC LIMIT 1";
				$data  = $databaseClass->query($query);
				$ta_id = "";
				foreach ($data as $dt) {
					$ta_aktif = $dt["tahun_ajaran"];
					$ta_id    = $dt["ta_id"];
				}

				// Jika ada tahun ajaran aktif saat ini - maka tampilkan formulir
				if ($ta_id!="") {
					$hidden_ta_id = "<input type='hidden' value='$ta_id' name='ta_id' id='ta_id'>";
					?>
					<div class="panel panel-default">
						<form class="form form-horizontal validetta" method="post" action="proses_simpan.php">
							<div class="panel-heading"><h4 class="text-center">Tahun Pelajaran  : <strong><?php echo $ta_aktif; ?></strong></h4></div>
							<div class="panel-body">
								<?php echo $hidden_ta_id; ?>
								<input type="hidden" value="simpan_calon_siswa" name="aksi" id="aksi">
								

								<legend> <i class="glyphicon glyphicon-user"></i> &nbsp; Data Calon Siswa</legend>
								<div class="form-group">
									<label class="col-md-3 control-label" for="nama_calon_siswa">
										Nama Calon Siswa :
									</label>
									<div class="col-md-8">
										<input type="text" class="form-control" name="nama_calon_siswa" id="nama_calon_siswa" data-validetta="required">
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label" for="asal_sekolah">
										Asal Sekolah SMP / Mts :
									</label>
									<div class="col-md-8">
										<input type="text" class="form-control" name="asal_sekolah" id="asal_sekolah" data-validetta="required">
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-3 control-label" for="jurusan">
										Pilihan Konsentrasi Keahlian :
									</label>
									<div class="col-md-6">
										<td>
										<select class="form-control" name='jurusan' id="jurusan">
										<option value='(Kosong)'>Pilih Konsentrasi Keahlian</option>
										<option value='TKR'>Teknik Kendaraan Ringan</option>
										<option value='DKV'>Desain Komunikasi Visual</option>
										<option value='ANM'>Animasi</option>
										<option value='TBG'>Kuliner</option>
										<option value='TBS'>Desain dan Produksi Busana</option>
										<option value='AK'>Akuntansi</option>
										<option value='SIJA'>Sistem Informatika Jaringan (CISCO & Mikrotik Academy class) </option>
										</select>
										</td>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label" for="telepon_orang_tua_wali">
										No Telepon / HP :
									</label>
									<div class="col-md-6">
										<input type="text" class="form-control" name="telepon_orang_tua_wali" id="telepon_orang_tua_wali" data-validetta="number,required">
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label" for="tempat_lahir_calon_siswa">
										Tempat Lahir :
									</label>
									<div class="col-md-8">
										<input type="text" class="form-control" name="tempat_lahir_calon_siswa" id="tempat_lahir_calon_siswa">
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label" for="tanggal_lahir_calon_siswa">
										Tanggal Lahir :
									</label>
									<div class="col-md-3">
										<div class="input-group">
											<input type="date" class="form-control" name="tanggal_lahir_calon_siswa" id="tanggal_lahir_calon_siswa">
											<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
										</div>
									</div>
									<!-- <label class="col-md-4 control-label" for="tanggal_lahir_calon_siswa">
										Format : Thn/bln/tgl contoh : 2010/02/04
									</label> -->
								</div>
								<legend> <i class="glyphicon glyphicon-home"></i> &nbsp; Data Orang Tua</legend>
								<div class="form-group">
									<label class="col-md-3 control-label" for="nama_orang_tua_wali">
										Nama Orang Tua / Wali :
									</label>
									<div class="col-md-8">
										<input type="text" class="form-control" name="nama_orang_tua_wali" id="nama_orang_tua_wali" data-validetta="required">
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label" for="pekerjaan_orang_tua_wali">
										Pekerjaan Orang Tua / Wali :
									</label>
									<div class="col-md-8">
										<input type="text" class="form-control" name="pekerjaan_orang_tua_wali" id="pekerjaan_orang_tua_wali">
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label" for="alamat_orang_tua_wali">
										Alamat Orang Tua / Wali :
									</label>
									<div class="col-md-8">
										<textarea class="form-control" name="alamat_orang_tua_wali" id="alamat_orang_tua_wali" style="resize:vertical; max-height:150px;"></textarea>
									</div>
								</div>
								<div class="form-group">
								<label class="col-md-3 control-label" >
									</label>
									<div data-callback="enableSubmit" class="g-recaptcha col-md-8" data-sitekey="<?php echo $_ENV['SITE_KEY'];?>"></div>						
								</div>
							<hr>
							</div>
							<div class="panel-footer text-center">
								<p id="warning" class="text-danger">Silahkan centang captcha terlebih dahulu</p>
								<button disabled style="cursor: pointer;" type="submit" name="proses" id="proses" class="btn btn-primary" >Simpan</button>
							</div>
						</form>
					</div>
					<?php 
				} // akhir jika ada tahun ajaran dibuka
				// Jika tidak ada tahun ajaran yang dibuka
				else {
					?>
					<div class="alert alert-info text-center">Mohon maaf, saat ini tidak sedang membuka pendaftaran.<br>Terima kasih atas kunjungannya!</div>
					<?php
					// Jika yang login admin, tampilkan link untuk mengatur data tahun ajaran
					if (isset($_SESSION['psb_username']) && isset($_SESSION['psb_level']) && $_SESSION['psb_username']!="" && $_SESSION['psb_level']!=""){
						?>
						<div class="panel panel-default">
							<div class="panel-body">
								<p class="text-center">Silahkan untuk melakukan pengaturan terhadap tahun ajaran!</p>
								<p class="text-center"><a class="btn btn-primary" href="dashboard.php">Pengaturan Tahun Ajaran</a></p>
							</div>
						</div>
						<?php
					}
				}
				?>
	    	</div>
    	</div>
	</div>
	<?php

	// Ambil footer
	include("./footer.php");
?>
<script>
    function enableSubmit() {
        document.getElementById("proses").disabled = false;
        document.getElementById("warning").style.display = "none";
    }
</script>