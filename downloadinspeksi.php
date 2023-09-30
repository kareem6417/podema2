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
    die("Query failed: " . $query->error);
}

$result = $query->get_result();

if ($result) {
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        die("No data found in the form_inspeksi table.");
    }
} else {
    die("Result set error: " . $conn->error);
}

$runningNumber = $row['no'] + 1;

$createDate = date('m/Y', strtotime($row['date']));

$nomorInspeksi = sprintf("%03d", $runningNumber) . "/MIP/INS/" . $createDate;


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

//keluhan
$html = '<table style="width: 100%; border-collapse: collapse; border: none;">';
$html .= '<thead>';
$html .= '<tr>';
$html .= '<th style="background-color: #FFE4B5; padding: 5px; font-weight: bold;" colspan="2">Informasi Keluhan/Permasalahan yang disampaikan:</th>';
$html .= '</tr>';
$html .= '</thead>';
$html .= '<tbody>';

$html .= '<tr><td style="padding: 5px; border: none;" colspan="2"></td></tr>';

$complaints = explode("\n", $row['informasi_keluhan']);
foreach ($complaints as $index => $complaint) {
    $html .= '<tr>';
    $html .= '<td style="padding: 5px; border: none;" colspan="2">' . nl2br($complaint) . '</td>';
    $html .= '</tr>';
    if ($index < count($complaints) - 1 || $index == count($complaints) - 1) {
        $html .= '<tr>';
        $html .= '<td style="padding: 0; border: none; border-top: 1px solid black; height: 100%;" colspan="2"></td>';
        $html .= '</tr>';
    }
}

$html .= '</tbody>';
$html .= '</table>';
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Ln();

switch ($row['jenis']) {
    case 'laptop':
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell($cellWidth * 2, 10, 'Casing:', 1, 0, 'L', false);
        $pdf->SetFont('helvetica', '', 11);
        $pdf->Cell($cellWidth * 2, 10, $row['casing_lap'], 1, 1, 'L', false);

        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell($cellWidth * 2, 10, 'Layar:', 1, 0, 'L', false);
        $pdf->SetFont('helvetica', '', 11);
        $pdf->Cell($cellWidth * 2, 10, $row['layar_lap'], 1, 1, 'L', false);

        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell($cellWidth * 2, 10, 'Engsel:', 1, 0, 'L', false);
        $pdf->SetFont('helvetica', '', 11);
        $pdf->Cell($cellWidth * 2, 10, $row['engsel_lap'], 1, 1, 'L', false);

        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell($cellWidth * 2, 10, 'Keyboard:', 1, 0, 'L', false);
        $pdf->SetFont('helvetica', '', 11);
        $pdf->Cell($cellWidth * 2, 10, $row['keyboard_lap'], 1, 1, 'L', false);

        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell($cellWidth * 2, 10, 'Touchpad:', 1, 0, 'L', false);
        $pdf->SetFont('helvetica', '', 11);
        $pdf->Cell($cellWidth * 2, 10, $row['touchpad_lap'], 1, 1, 'L', false);

        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell($cellWidth * 2, 10, 'Proses Booting:', 1, 0, 'L', false);
        $pdf->SetFont('helvetica', '', 11);
        $pdf->Cell($cellWidth * 2, 10, $row['booting_lap'], 1, 1, 'L', false);

        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell($cellWidth * 2, 10, 'Multitasking Apps/Program:', 1, 0, 'L', false);
        $pdf->SetFont('helvetica', '', 11);
        $pdf->Cell($cellWidth * 2, 10, $row['multi_lap'], 1, 1, 'L', false);

        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell($cellWidth * 2, 10, 'Daya Tampung Baterai:', 1, 0, 'L', false);
        $pdf->SetFont('helvetica', '', 11);
        $pdf->Cell($cellWidth * 2, 10, $row['tampung_lap'], 1, 1, 'L', false);

        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell($cellWidth * 2, 10, 'Waktu Pengisian Baterai:', 1, 0, 'L', false);
        $pdf->SetFont('helvetica', '', 11);
        $pdf->Cell($cellWidth * 2, 10, $row['isi_lap'], 1, 1, 'L', false);

        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell($cellWidth * 2, 10, 'Port:', 1, 0, 'L', false);
        $pdf->SetFont('helvetica', '', 11);
        $pdf->Cell($cellWidth * 2, 10, $row['port_lap'], 1, 1, 'L', false);

        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell($cellWidth * 2, 10, 'Audio:', 1, 0, 'L', false);
        $pdf->SetFont('helvetica', '', 11);
        $pdf->Cell($cellWidth * 2, 10, $row['audio_lap'], 1, 1, 'L', false);

        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell($cellWidth * 2, 10, 'Software:', 1, 0, 'L', false);
        $pdf->SetFont('helvetica', '', 11);
        $pdf->Cell($cellWidth * 2, 10, $row['software_lap'], 1, 1, 'L', false);

        break;

    case 'pc_desktop':
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell($cellWidth * 2, 10, 'Casing:', 1, 0, 'L', false);
        $pdf->SetFont('helvetica', '', 11);
        $pdf->Cell($cellWidth * 2, 10, $row['casing_lap'], 1, 1, 'L', false);

        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell($cellWidth * 2, 10, 'Layar:', 1, 0, 'L', false);
        $pdf->SetFont('helvetica', '', 11);
        $pdf->Cell($cellWidth * 2, 10, $row['layar_lap'], 1, 1, 'L', false);

        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell($cellWidth * 2, 10, 'Keyboard:', 1, 0, 'L', false);
        $pdf->SetFont('helvetica', '', 11);
        $pdf->Cell($cellWidth * 2, 10, $row['keyboard_lap'], 1, 1, 'L', false);

        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell($cellWidth * 2, 10, 'Proses Booting:', 1, 0, 'L', false);
        $pdf->SetFont('helvetica', '', 11);
        $pdf->Cell($cellWidth * 2, 10, $row['booting_lap'], 1, 1, 'L', false);

        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell($cellWidth * 2, 10, 'Multitasking Apps/Program:', 1, 0, 'L', false);
        $pdf->SetFont('helvetica', '', 11);
        $pdf->Cell($cellWidth * 2, 10, $row['multi_lap'], 1, 1, 'L', false);

        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell($cellWidth * 2, 10, 'Port:', 1, 0, 'L', false);
        $pdf->SetFont('helvetica', '', 11);
        $pdf->Cell($cellWidth * 2, 10, $row['port_lap'], 1, 1, 'L', false);

        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell($cellWidth * 2, 10, 'Audio:', 1, 0, 'L', false);
        $pdf->SetFont('helvetica', '', 11);
        $pdf->Cell($cellWidth * 2, 10, $row['audio_lap'], 1, 1, 'L', false);

        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell($cellWidth * 2, 10, 'Software:', 1, 0, 'L', false);
        $pdf->SetFont('helvetica', '', 11);
        $pdf->Cell($cellWidth * 2, 10, $row['software_lap'], 1, 1, 'L', false);

        break;

    case 'monitor':
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell($cellWidth * 2, 10, 'Casing:', 1, 0, 'L', false);
        $pdf->SetFont('helvetica', '', 11);
        $pdf->Cell($cellWidth * 2, 10, $row['casing_lap'], 1, 1, 'L', false);

        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell($cellWidth * 2, 10, 'Layar:', 1, 0, 'L', false);
        $pdf->SetFont('helvetica', '', 11);
        $pdf->Cell($cellWidth * 2, 10, $row['layar_lap'], 1, 1, 'L', false);
        
        break;

    case 'printer':
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell($cellWidth * 2, 10, 'Casing:', 1, 0, 'L', false);
        $pdf->SetFont('helvetica', '', 11);
        $pdf->Cell($cellWidth * 2, 10, $row['casing_lap'], 1, 1, 'L', false);

        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell($cellWidth * 2, 10, 'Ink Pad:', 1, 0, 'L', false);
        $pdf->SetFont('helvetica', '', 11);
        $pdf->Cell($cellWidth * 2, 10, $row['ink_pad'], 1, 1, 'L', false);

        break;
}


//hasil_pemeriksaan
$html = '<table style="width: 100%; border-collapse: collapse; border: none;">';
$html .= '<thead>';
$html .= '<tr>';
$html .= '<th style="background-color: #FFE4B5; padding: 5px; font-weight: bold;" colspan="2">Hasil Pemeriksaan:</th>';
$html .= '</tr>';
$html .= '</thead>';
$html .= '<tbody>';
$html .= '<tr><td style="padding: 5px; border: none;" colspan="2"></td></tr>'; 

$complaints = explode("\n", $row['hasil_pemeriksaan']);
foreach ($complaints as $index => $complaint) {
    $html .= '<tr>';
    $html .= '<td style="padding: 5px; border: none;" colspan="2">' . nl2br($complaint) . '</td>';
    $html .= '</tr>';
    if ($index < count($complaints) - 1 || $index == count($complaints) - 1) {
        $html .= '<tr>';
        $html .= '<td style="padding: 0; border: none; border-top: 1px solid black; height: 100%;" colspan="2"></td>';
        $html .= '</tr>';
    }
}

$html .= '</tbody>';
$html .= '</table>';
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Ln();

//rekomendasi
$html = '<table style="width: 100%; border-collapse: collapse; border: none;">';
$html .= '<thead>';
$html .= '<tr>';
$html .= '<th style="background-color: #FFE4B5; padding: 5px; font-weight: bold;" colspan="2">Rekomendasi:</th>';
$html .= '</tr>';
$html .= '</thead>';
$html .= '<tbody>';
$html .= '<tr><td style="padding: 5px; border: none;"></td></tr>';

$complaints = explode("\n", $row['rekomendasi']);
foreach ($complaints as $index => $complaint) {
    $html .= '<tr>';
    $html .= '<td style="padding: 5px; border: none;" colspan="2">' . nl2br($complaint) . '</td>';
    $html .= '</tr>';
    if ($index < count($complaints) - 1 || $index == count($complaints) - 1) {
        $html .= '<tr>';
        $html .= '<td style="padding: 0; border: none; border-top: 1px solid black; height: 1px;" colspan="2"></td>';
        $html .= '</tr>';
    }
}

$html .= '<tr><td style="padding: 5px; border: none; height: 5px;"></td></tr>'; 

$html .= '</tbody>';
$html .= '</table>';
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Ln();

$pdf->SetFont('helvetica', 'B', 10);
$location = '    Jakarta,';
$currentDate = date('d F Y');
$locationWidth = $pdf->GetStringWidth($location);
$dateWidth = $pdf->GetStringWidth($currentDate);
$totalWidth = $locationWidth + $dateWidth + 5;

$pdf->SetX(10);
$pdf->Cell($locationWidth, 5, $location, 0, 0, 'L');
$pdf->Cell(1, 1, '', 0, 0, 'C');
$pdf->Cell($dateWidth, 5, $currentDate, 0, 1, 'L');

$pdf->Ln(20);

$pdf->Cell(95, 10, '', 0, 0, 'C');
$pdf->Cell(5, 10, '', 0, 0, 'C');
$pdf->Cell(95, 10, '', 0, 1, 'C');

$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(47.5, 10, 'Diperiksa Oleh', 'T', 0, 'L');
$pdf->Cell(5, 10, '', 0, 0, 'C');
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(47.5, 10, 'Pengguna', 'T', 1, 'C');

$filename = "Inspection-Devices.pdf";
$pdf->Output($filename, 'D');
exit;
?>