<table border="1">
	<tr>
		<th>NO.</th>
		<th>NAMA LENGKAP</th>
		<th>ASAL SMP</th>
		<th>No Handphone</th>
		<th>JURUSAN</th>
	</tr>
	<?php
	//koneksi ke database
	mysql_connect("localhost", "nilaiktsp", "p4ssw0rd");
	mysql_select_db("data_psb");
	//query menampilkan data
	$sql = mysql_query("SELECT * FROM psb_data_siswa ORDER BY data_id ASC");
	$no = 1;
	while($data = mysql_fetch_assoc($sql)){
		echo '
		<tr>
			<td>'.$no.'</td>
			<td>'.$data['nama_calon_siswa'].'</td>
			<td>'.$data['asal_sekolah'].'</td>
			<td>'.$data['telepon_orang_tua_wali'].'</td>
			<td>'.$data['jurusan'].'</td>
		</tr>
		';
		$no++;
	}
	?>
</table>
