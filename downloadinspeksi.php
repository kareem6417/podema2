<?php

require_once('tcpdf/tcpdf.php');

$host = "mandiricoal.net";
$user = "podema"; 
$pass = "Jam10pagi#";
$db = "podema";
$conn = new mysqli($host, $user, $pass, $db);

$query = $conn->prepare("SELECT * FROM form_inspeksi ORDER BY no DESC LIMIT 1");
$query->execute();

if ($query->error) {
    die("Query gagal: " . $query->error);
}

$result = $query->get_result();

if ($result) {
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        die("Tidak ada data ditemukan di tabel form_inspeksi.");
    }
} else {
    die("Error hasil set: " . $conn->error);
}

$runningNumber = $row['no'] + 1;

$createDate = date('m/Y', strtotime($row['date']));

$nomorInspeksi = sprintf("%03d", $runningNumber) . "/MIP/INS/" . $createDate;

$jenis = $_POST['jenis'];
$informasi_keluhan = $_POST['informasi_keluhan'];
$casing_lap = $_POST['casing_lap'];
$layar_lap = $_POST['layar_lap'];
$engsel_lap = $_POST['engsel_lap'];
$keyboard_lap = $_POST['keyboard_lap'];
$touchpad_lap = $_POST['touchpad_lap'];
$booting_lap = $_POST['booting_lap'];
$multi_lap = $_POST['multi_lap'];
$tampung_lap = $_POST['tampung_lap'];
$isi_lap = $_POST['isi_lap'];
$port_lap = $_POST['port_lap'];
$audio_lap = $_POST['audio_lap'];
$software_lap = $_POST['software_lap'];
$hasil_pemeriksaan = $_POST['hasil_pemeriksaan'];
$rekomendasi = $_POST['rekomendasi'];

$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetMargins(10, 10, 10);
$pdf->SetFont('helvetica', 'B', 10);

class MYPDF extends TCPDF {
    private $currentPage = 1;

    public function Header() {
        if ($this->currentPage === 1) {
        global $nomorInspeksi;
        $image_file = 'mandiri.png';
        $logo_width = 33.1;
        $logo_height = 17.5;

        // Gambar logo di kolom pertama
        $this->Image($image_file, 10, 10, $logo_width, $logo_height, 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);

        // Tabel pada kolom kedua untuk header text
        $this->SetFont('helvetica', 'B', 15);
        $this->SetXY(($this->GetPageWidth() - 80) / 2, 13);
        $this->Cell(83, 5, 'FORM INSPEKSI DAN', 0, false, 'C', 0, '', 0, false, 'M', 'M');

        $this->SetXY(($this->GetPageWidth() - 80) / 2, 20);
        $this->Cell(82, 5, 'PEMERIKSAAN PERANGKAT', 0, false, 'C', 0, '', 0, false, 'M', 'M');

        // Kolom ketiga (center)
        $this->SetFont('helvetica', '', 9);
        $this->SetXY(($this->GetPageWidth() - 80) / 2, 27);
        $this->Cell(80, 15, 'Divisi Information & Technology', 0, false, 'C', 0, '', 0, false, 'M', 'M');

        // Kolom pada sebelah kanan (center)
        $this->SetFont('helvetica', '', 7);
        $this->SetXY(($this->GetPageWidth() - 20) / 2, 13);
        $this->Cell(100, 5, 'Form : MIP/FRM/ITE/005', 0, false, 'R', 0, '', 0, false, 'M', 'M');

        $this->SetXY(($this->GetPageWidth() - 20) / 2, 18);
        $this->Cell(100, 5, 'Revisi : 00', 0, false, 'R', 0, '', 0, false, 'M', 'M');

        // Garis pemisah
        $this->SetLineWidth(0.5);
        $this->Line(10, 10 + $logo_height + 5, $this->GetPageWidth() - 10, 10 + $logo_height + 5);
        $this->SetLineWidth(0.2);

        $this->SetFont('helvetica', '', 11);
        $this->SetXY(($this->GetPageWidth() - 80) / 2, 40);
        $this->Cell(80, 15, 'Nomor Inspeksi: ' . $nomorInspeksi, 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->Ln(20);
    }
}

    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'B', 6);
        $this->Cell(0, 10, 'Halaman '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }

    public function AddPage($orientation = '', $format = '', $keepmargins = false, $tocpage = false) {
        parent::AddPage($orientation, $format, $keepmargins, $tocpage);
        $this->currentPage++;
    }
}



$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

$pdf->SetFont('times', '', 12);

$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);
$pdf->Ln(20);

$pageWidth = $pdf->GetPageWidth();
$cellWidth = $pageWidth / 4;
$maxTableWidth = $pageWidth * 0.86; 
$cellWidth = $maxTableWidth / 4; 
$pdf->SetLineWidth(0); 

$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell($cellWidth, 10, 'Tanggal:', 1, 0, 'L', false);
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell($cellWidth, 10, $row['date'], 1, 0, 'L', false);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell($cellWidth, 10, 'Nama Pengguna:', 1, 0, 'L', false);
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell($cellWidth, 10, $row['nama_user'], 1, 1, 'L', false); 

$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell($cellWidth, 10, 'Tipe Perangkat:', 1, 0, 'L', false); 
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell($cellWidth, 10, $row['jenis'], 1, 0, 'L', false);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell($cellWidth, 10, 'Jabatan/Bagian:', 1, 0, 'L', false); 
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell($cellWidth, 10, $row['status'], 1, 1, 'L', false); 

$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell($cellWidth * 2, 10, 'Merk/Nomor Serial:', 1, 0, 'L', false);
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell($cellWidth * 2, 10, $row['merk'] . ' / ' . $row['serialnumber'], 1, 1, 'L', false); 

$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell($cellWidth * 2, 10, 'Lokasi/Area Kerja Perangkat Digunakan:', 1, 0, 'L', false);
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell($cellWidth * 2, 10, $row['lokasi'], 1, 1, 'L', false);
$pdf->Ln(10);

if ($jenis == "Laptop") {
    // Tambahkan elemen khusus untuk Laptop
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 10, 'Pemeriksaan Laptop', 0, true, 'C', 0, '', 0, false, 'M', 'M');

    $pdf->SetFont('helvetica', '', 11);
    $pdf->MultiCell(0, 10, 'Casing: ' . $casing_lap, 0, 'L', false, 1, '', '', true, 0, false, true, 10, 'T');
    $pdf->MultiCell(0, 10, 'Layar: ' . $layar_lap, 0, 'L', false, 1, '', '', true, 0, false, true, 10, 'T');
    $pdf->MultiCell(0, 10, 'Engsel: ' . $engsel_lap, 0, 'L', false, 1, '', '', true, 0, false, true, 10, 'T');
    $pdf->MultiCell(0, 10, 'Keyboard: ' . $keyboard_lap, 0, 'L', false, 1, '', '', true, 0, false, true, 10, 'T');
    $pdf->MultiCell(0, 10, 'Touchpad: ' . $touchpad_lap, 0, 'L', false, 1, '', '', true, 0, false, true, 10, 'T');
    $pdf->MultiCell(0, 10, 'Booting: ' . $booting_lap, 0, 'L', false, 1, '', '', true, 0, false, true, 10, 'T');
    $pdf->MultiCell(0, 10, 'Multi: ' . $multi_lap, 0, 'L', false, 1, '', '', true, 0, false, true, 10, 'T');
    $pdf->MultiCell(0, 10, 'Tampung: ' . $tampung_lap, 0, 'L', false, 1, '', '', true, 0, false, true, 10, 'T');
    $pdf->MultiCell(0, 10, 'Isi: ' . $isi_lap, 0, 'L', false, 1, '', '', true, 0, false, true, 10, 'T');
    $pdf->MultiCell(0, 10, 'Port: ' . $port_lap, 0, 'L', false, 1, '', '', true, 0, false, true, 10, 'T');
    $pdf->MultiCell(0, 10, 'Audio: ' . $audio_lap, 0, 'L', false, 1, '', '', true, 0, false, true, 10, 'T');
    $pdf->MultiCell(0, 10, 'Software: ' . $software_lap, 0, 'L', false, 1, '', '', true, 0, false, true, 10, 'T');
} else if ($jenis == "Desktop") {
    // Tambahkan elemen khusus untuk Desktop
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 10, 'Pemeriksaan Desktop', 0, true, 'C', 0, '', 0, false, 'M', 'M');

    $pdf->SetFont('helvetica', '', 11);
    $pdf->MultiCell(0, 10, 'Casing: ' . $casing_lap, 0, 'L', false, 1, '', '', true, 0, false, true, 10, 'T');
    $pdf->MultiCell(0, 10, 'Layar: ' . $layar_lap, 0, 'L', false, 1, '', '', true, 0, false, true, 10, 'T');
    $pdf->MultiCell(0, 10, 'Keyboard: ' . $keyboard_lap, 0, 'L', false, 1, '', '', true, 0, false, true, 10, 'T');
    $pdf->MultiCell(0, 10, 'Booting: ' . $booting_lap, 0, 'L', false, 1, '', '', true, 0, false, true, 10, 'T');
    $pdf->MultiCell(0, 10, 'Multi: ' . $multi_lap, 0, 'L', false, 1, '', '', true, 0, false, true, 10, 'T');
    $pdf->MultiCell(0, 10, 'Port: ' . $port_lap, 0, 'L', false, 1, '', '', true, 0, false, true, 10, 'T');
    $pdf->MultiCell(0, 10, 'Audio: ' . $audio_lap, 0, 'L', false, 1, '', '', true, 0, false, true, 10, 'T');
    $pdf->MultiCell(0, 10, 'Software: ' . $software_lap, 0, 'L', false, 1, '', '', true, 0, false, true, 10, 'T');
} else if ($jenis == "Monitor") {
    // Tambahkan elemen khusus untuk Monitor
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 10, 'Pemeriksaan Monitor', 0, true, 'C', 0, '', 0, false, 'M', 'M');

    $pdf->SetFont('helvetica', '', 11);
    $pdf->MultiCell(0, 10, 'Casing: ' . $casing_lap, 0, 'L', false, 1, '', '', true, 0, false, true, 10, 'T');
    $pdf->MultiCell(0, 10, 'Layar: ' . $layar_lap, 0, 'L', false, 1, '', '', true, 0, false, true, 10, 'T');
} else if ($jenis == "Printer") {
    // Tambahkan elemen khusus untuk Printer
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->Cell(0, 10, 'Pemeriksaan Printer', 0, true, 'C', 0, '', 0, false, 'M', 'M');

    $pdf->SetFont('helvetica', '', 11);
    $pdf->MultiCell(0, 10, 'Casing: ' . $casing_lap, 0, 'L', false, 1, '', '', true, 0, false, true, 10, 'T');
    $pdf->MultiCell(0, 10, 'Ink Pad: ' . $ink_pad, 0, 'L', false, 1, '', '', true, 0, false, true, 10, 'T');
}

$pdf->MultiCell(0, 10, 'Hasil Pemeriksaan: ' . $hasil_pemeriksaan, 0, 'L', false, 1, '', '', true, 0, false, true, 10, 'T');
$pdf->MultiCell(0, 10, 'Rekomendasi: ' . $rekomendasi, 0, 'L', false, 1, '', '', true, 0, false, true, 10, 'T');


$filename = "Inspection-Devices.pdf";
$pdf->Output($filename, 'D');
exit;
?>