<?php session_start();

// Jika user sudah sign in
//if (isset($_SESSION['psb_username']) && isset($_SESSION['psb_level']) && $_SESSION['psb_username']!="" && $_SESSION['psb_level']!="") {
	// Require class database
	require_once(__DIR__ . '/lib/db.class.php');
	$databaseClass = new DB();

	// Ambil header
	include("./header.php");
	?>
		<div class="container document">
    	<div class="row">
	    	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	    		<?php // Tampilkan informasi jika ada!
	    		if (isset($_SESSION["informasi_formulir"])) {
	    			echo "<div class='alert alert-info'>".$_SESSION["informasi_formulir"]."</div>";
	    			unset($_SESSION["informasi_formulir"]);
	    		}
	    		?>
				<div class="panel panel-default">
					<div class="panel-body">
						<!-- Tab Options -->
						<ul class="nav nav-tabs" role="tablist">
							<li role="presentation" class="active">
							<a href="#daftar_siswa" id="daftar_siswa_tab" role="tab" data-toggle="tab" aria-controls="daftar_siswa" aria-expanded="true"><i class="glyphicon glyphicon-user"></i> Daftar Siswa</a></li>
						</ul>
				    <!-- Tab content -->
						<div class="tab-content">
							<!-- Data calon siswa yang sudah terdaftar -->
							<div role="tabpanel" class="tab-pane fade active in" id="daftar_siswa" aria-labelledby="daftar_siswa_tab">
								<div class="row">
									<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
										<h4>
											Daftar Responden Survey Peminatan Jurusan SMK Nega
										</h4>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 ">
										<form class="form" method="post" action="">
											<div class="input-group">
												<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i> Tahun Ajaran</span>
												<select class="form-control" name="pilih_tahun_ajaran" id="pilih_tahun_ajaran">
													<?php 
													// Ambil semua tahun ajaran
													$query_ta_select = "SELECT ta_id, tahun_ajaran FROM psb_tahun_ajaran ORDER BY aktif ASC";
													$data_select     = $databaseClass->query($query_ta_select);
													$view_ta_select  = "";
													foreach ($data_select as $dselect) {
														$ta_id_select   = $dselect["ta_id"];
														$ta_name_select = stripslashes($dselect["tahun_ajaran"]);
														if (isset($_POST["pilih_tahun_ajaran"]) && $_POST["pilih_tahun_ajaran"] == $ta_id_select) {
															$view_ta_select.= "<option value='$ta_id_select' selected>$ta_name_select</option>";
														}
														else {
															$view_ta_select.= "<option value='$ta_id_select'>$ta_name_select</option>";
														}
													}
													echo $view_ta_select;
													?>
												</select>
												<div class="input-group-btn">
													<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-search"></i> Lihat</button>
												</div>
											</div>
										</form>
									</div>
								</div>
								<div><hr></div>
								<div class="table-responsive">
									<table class="table table-striped" id="datatable">
										<thead>
											<tr>
												<th>No</th>
												<th>Nama</th>
												<th>Tempat, Tgl Lahir</th>
												<th>Asal Sekolah</th>
												<th>Nama Orang Tua</th>
												<!-- <th>Telepon</th> -->
												<th>Tgl Daftar</th>
												<!--<th>Status</th> -->
												<th>Aksi</th> 
											</tr>
										</thead>
										<tbody>
											<?php 
											// Ambil semua data calon siswa
											if (isset($_POST["pilih_tahun_ajaran"])) {
												$ta_pilihan = $_POST["pilih_tahun_ajaran"];
											}
											else {
												$query_ta_standar = "SELECT ta_id FROM psb_tahun_ajaran WHERE aktif = 'yes' ORDER BY ta_id DESC LIMIT 1";
												$data_standar     = $databaseClass->query($query_ta_standar);
												$ta_pilihan       = "";
												foreach ($data_standar as $dt_standar) {
													$ta_pilihan = $dt_standar["ta_id"];
												}
											}
											$query = "SELECT * FROM  psb_data_siswa WHERE ta_id = '$ta_pilihan' ORDER BY data_id DESC";
											
												$data  = $databaseClass->query($query);
												$view  = "";
												$no    = 0;
												foreach ($data as $dt) {
													// Set data id
													$data_id = $dt["data_id"];
													// Set tgl lahir
													$tgl_lahir_break = explode("-", $dt["tanggal_lahir_calon_siswa"]);
													$tb[0]           = substr($tgl_lahir_break[2], 0, 1) . '*';
													$tb[1]           = '**';
													$tb[2]           = substr($tgl_lahir_break[0], 0, 2) . '**';


													$tgl_lahir_baru  = implode("-", $tb);
													// Set tgl daftar
													$tgl_daftar_break = explode("-", (substr($dt["created_date"], 0,10)));
													$tdb[0]           = $tgl_daftar_break[2];
													$tdb[1]           = $tgl_daftar_break[1];
													$tdb[2]           = $tgl_daftar_break[0];
													$tgl_daftar_baru  = implode("-", $tdb); //." ".substr($dt["created_date"], -9);
													// Set tampilan
													$no++;
													$view    .= "<tr>";
													$view    .= "<td>$no</td>";
													$view    .= "<td id='nama_cs_$data_id'>".stripslashes($dt["nama_calon_siswa"])."</td>";

													$lengthOtw = strlen(stripslashes($dt["tempat_lahir_calon_siswa"]));	
													$tengah = floor($lengthOtw / 2) - 2; 
													
													$view    .= "<td id='ttl_cs_$data_id'>".stripslashes(substr($dt["tempat_lahir_calon_siswa"], 0, $tengah) . "****" . substr($dt["tempat_lahir_calon_siswa"], $tengah + 4)).", $tgl_lahir_baru</td>";
													$view    .= "<td id='asekolah_cs_$data_id'>".stripslashes($dt["asal_sekolah"])."</td>";

													$lengthOtw = strlen(stripslashes($dt["nama_orang_tua_wali"]));	
													$tengah = floor($lengthOtw / 2) - 2; 
													$view    .= "<td id='nama_ot_$data_id'>".substr($dt["nama_orang_tua_wali"], 0, $tengah) . "****" . substr($dt["nama_orang_tua_wali"], $tengah + 4)."</td>";
													//$view    .= "<td>".stripslashes($dt["telepon_orang_tua_wali"])."</td>";
													$view    .= "<td id='tgl_daftar_$data_id'>$tgl_daftar_baru</td>";
													//$view    .= "<td id='status_$data_id'>".ucfirst($dt["status_penerimaan"])."</td>";
													// Tampilan aksi
													$view    .= "<td><div class='btn-group btn-group-sm'>";
													$view    .= "<button class='btn btn-sm btn-primary' data-toggle='modal' data-target='#modal_detail' onclick=lihat_detail('$data_id') title='Detail Calon Siswa'> <!--<i class='glyphicon glyphicon-eye-open'></i>--> Detail</button><button class='btn btn-sm btn-success' onclick=cetak('$data_id') title='Detail Calon Siswa'> <!--<i class='glyphicon glyphicon-eye-open'></i>--> Cetak</button>";
													//$view    .= "<button class='btn btn-sm btn-default' onclick=\"ubah_data_cs('$data_id')\" title='Ubah Data Calon Siswa'> <!--<i class='glyphicon glyphicon-pencil'></i>--> Ubah</button>";
														// if ($dt["status_penerimaan"]=="terdaftar seleksi") {
														// 	$view    .= "<a href='#' class='btn btn-sm btn-success' title='Terima calon siswa' onclick=\"confirm('Proses TERIMA Calon siswa $dt[nama_calon_siswa] ?')\"> <i class='glyphicon glyphicon-ok'></i> Terima </a>";
														// 	$view    .= "<a href='#' class='btn btn-sm btn-danger' title='Tolak calon siswa' onclick=\"confirm('Proses TOLAK Calon siswa $dt[nama_calon_siswa] ?')\"> <i class='glyphicon glyphicon-remove'></i> Tolak</a>";
														// }
														// elseif ($dt["status_penerimaan"]=="terima") {
														// 	$view    .= "<a href='#' class='btn btn-sm btn-danger' title='Tolak calon siswa' onclick=\"confirm('Proses TOLAK Calon siswa $dt[nama_calon_siswa] ?')\"> <i class='glyphicon glyphicon-remove'></i> Tolak</a>";
														// }
														// elseif ($dt["status_penerimaan"]=="tolak") {
														// 	$view    .= "<a href='#' class='btn btn-sm btn-success' title='Terima calon siswa' onclick=\"confirm('Proses TERIMA Calon siswa $dt[nama_calon_siswa] ?')\"> <i class='glyphicon glyphicon-ok'></i> Terima</a>";
														// }
													$view    .= "</div></td>";
													// Hidden data
													$view    .= "<input type='hidden' name='tmp_lhr_$data_id' id='tmp_lhr_$data_id' value='".stripslashes($dt["tempat_lahir_calon_siswa"])."'>";
													$view    .= "<input type='hidden' name='tgl_lhr_$data_id' id='tgl_lhr_$data_id' value='".$dt["tanggal_lahir_calon_siswa"]."'>";
													$view    .= "<input type='hidden' name='tlp_ot_$data_id' id='tlp_ot_$data_id' value='".stripslashes($dt["telepon_orang_tua_wali"])."'>";
													$view    .= "<input type='hidden' name='pkj_ot_$data_id' id='pkj_ot_$data_id' value='".stripslashes($dt["pekerjaan_orang_tua_wali"])."'>";
													$view    .= "<input type='hidden' name='alt_ot_$data_id' id='alt_ot_$data_id' value='".stripslashes($dt["alamat_orang_tua_wali"])."'>";
													$view    .= "<input type='hidden' name='eml_ot_$data_id' id='eml_ot_$data_id' value='".stripslashes($dt["jurusan"])."'>";
													$view    .= "</tr>";
												}
												// Tampilkan
												echo $view;
											?>
										</tbody>
									</table>
								</div>
							</div>

							<!-- Data tahun ajaran yang sudah diinputkan -->
							
						<!-- End tab content -->
					</div>
				</div>
			</div>
		</div>
	<?php
	// Ambil footer
	include "./footer.php";

	// Ambil modal detail siswa
	include './modal_detail_siswa.php';

	// Ambil modal tahun ajaran
	//include './modal_tahun_ajaran.php';
//}
//else {
	// Redirect dashboard
	//header("Location: ./index.php");
	//die();
//}

?>
<!-- <iframe id="printing-frame" name="print_frame" style="display:block;" srcdoc="
	
	"></iframe> -->

	<script type="text/javascript">
		function printDiv(divName) {
			var hasil_cetak='<img src="./assets/images/sclogo.png" height="40"><br><br>';
			hasil_cetak+=($("#"+divName).html());
			var printContents = hasil_cetak;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}
	</script>
