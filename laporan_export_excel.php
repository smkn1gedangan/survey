<?php
/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Asia/Jakarta');

if (PHP_SAPI == 'cli')
      die('This file should only be run from a Web Browser');

// Get parameter value
$ta_id       = $_GET["tahun_ajaran_id"];
$ta_name     = $_GET["tahun_ajaran_name"];
$ta_name_alt = str_replace(" ", "_", $ta_name);

/** Include PhpSpreadsheet */
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

// Create new Spreadsheet object
$spreadsheet = new Spreadsheet();

// Set document properties
$spreadsheet->getProperties()->setCreator("Noerman Agustiyan")
    ->setLastModifiedBy("Noerman Agustiyan")
    ->setTitle("Laporan Jumlah Calon Siswa")
    ->setSubject("Laporan Jumlah Calon Siswa")
    ->setDescription("Menampilkan data calon siswa berdasarkan tahun ajaran yang dipilih")
    ->setKeywords("calon-siswa pendaftaran psb ppdb siswa online daftar enroll database php");

// Rename worksheet
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Laporan Jumlah Calon Siswa');

// Header Title
$sheet->setCellValue("B2", " Laporan Jumlah Calon Siswa Pendaftaran Online. TA : $ta_name");
$sheet->getStyle('B2')->getFont()->setSize(20)->setBold(true);

// Header Column Setting
$sheet->getStyle('B4:M4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)->setVertical(Alignment::VERTICAL_CENTER);

// Width
$columns = ['C' => 25, 'D' => 25, 'E' => 15, 'F' => 15, 'G' => 25, 'H' => 20, 'I' => 30, 'J' => 25, 'K' => 15, 'L' => 15, 'M' => 20];
foreach ($columns as $col => $width) {
    $sheet->getColumnDimension($col)->setWidth($width);
}

// Value
$sheet->fromArray(
    ['No', 'Nama Calon Siswa', 'Asal Sekolah', 'Tempat Lahir', 'Tanggal Lahir', 'Nama Orang Tua / Wali', 'Pekerjaan Orang Tua / Wali', 'Alamat', 'Jurusan', 'No Telepon Orang Tua / Wali', 'Status Penerimaan', 'Checker'],
    NULL,
    'B4'
);

// Header style
$sheet->getStyle('B4:M4')->applyFromArray([
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['argb' => 'ccffcc']
    ],
    'borders' => [
        'allBorders' => ['style' => Border::BORDER_MEDIUM]
    ]
]);

// Require class database
require_once(__DIR__ . '/lib/db.class.php');
$databaseClass = new DB();

// Content
$query_data_cs = "SELECT * FROM psb_data_siswa WHERE ta_id = '$ta_id'";
$data_list = $databaseClass->query($query_data_cs);

$row = 5;
$no = 1;
foreach ($data_list as $dl) {
    $sheet->fromArray([
        $no,
        $dl["nama_calon_siswa"],
        $dl["asal_sekolah"],
        $dl["tempat_lahir_calon_siswa"],
        $dl["tanggal_lahir_calon_siswa"],
        $dl["nama_orang_tua_wali"],
        $dl["pekerjaan_orang_tua_wali"],
        $dl["alamat_orang_tua_wali"],
        $dl["jurusan"],
        $dl["telepon_orang_tua_wali"],
        $dl["status_penerimaan"],
        $dl["checker"],
    ], NULL, "B$row");

    // Zebra color
    if ($row % 2 == 1) {
        $sheet->getStyle("B$row:M$row")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('e1eaea');
    }
    $row++;
    $no++;
}

// Wrap text
$sheet->getStyle('B6:M' . ($row - 1))->getAlignment()->setWrapText(true);

// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment;filename=Laporan_Calon_Siswa_{$ta_name_alt}_" . date('d-m-Y') . ".xlsx");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
