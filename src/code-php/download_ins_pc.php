<?php

require_once('../fpdf/fpdf.php');

$host = "mandiricoal.net";
$user = "podema";
$pass = "Jam10pagi#";
$db = "podema";
$conn = new mysqli($host, $user, $pass, $db);

$query = $conn->prepare("SELECT fi.*, 
                                s.screenshot_name, 
                                cl.casing_lap_name, cl.casing_lap_score, 
                                ip.layar_lap_name, ip.layar_lap_score,
                                bl.booting_lap_name, bl.booting_lap_score,
                                ml.multi_lap_name, ml.multi_lap_score,
                                pl.port_lap_name, pl.port_lap_score,
                                al.audio_lap_name, al.audio_lap_score,
                                sl.software_lap_name, sl.software_lap_score
                        FROM form_inspeksi fi 
                        JOIN screenshots s ON fi.no = s.form_no 
                        JOIN ins_casing_lap cl ON fi.casing_lap = cl.casing_lap_score 
                        JOIN ins_layar_lap ip ON fi.layar_lap = ip.layar_lap_score 
                        JOIN ins_booting_lap bl ON fi.booting_lap = bl.booting_lap_score
                        JOIN ins_multi_lap ml ON fi.multi_lap = ml.multi_lap_score
                        JOIN ins_port_lap pl ON fi.port_lap = pl.port_lap_score
                        JOIN ins_audio_lap al ON fi.audio_lap = al.audio_lap_score
                        JOIN ins_software_lap sl ON fi.software_lap = sl.software_lap_score
                        WHERE fi.no = (SELECT MAX(no) FROM form_inspeksi)");
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

$createDate = date('m/Y');

$nomorInspeksi = sprintf("%03d", $runningNumber) . "/MIP/INS/" . $createDate;

$screenshot_files = [];
$target_screenshot_dir = $_SERVER['DOCUMENT_ROOT'] . "/podema/src/screenshot/";

if ($handle = opendir($target_screenshot_dir)) {
    while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != "..") {
            $screenshot_files[] = $entry;
        }
    }
    closedir($handle);
}

class MYPDF extends FPDF {
    private $screenshot_files;
    private $nomorInspeksi;

    public function __construct($screenshot_files, $nomorInspeksi) {
        parent::__construct();
        $this->screenshot_files = $screenshot_files;
        $this->nomorInspeksi = $nomorInspeksi;
    }

    function Header() {
        $this->Image('../assets/images/logos/mandiri.png',10,8,33);
        $this->addHeaderContent($this->nomorInspeksi);
    }

    function addHeaderContent($nomorInspeksi) {
        $logo_height = 33;
        $this->SetFont('helvetica', 'B', 15);
        $this->SetXY(($this->GetPageWidth() - 80) / 2, 13);
        $this->Cell(83, 5, 'INSPEKSI PERANGKAT', 0, false, 'C', 0, '', 0, false, 'M', 'M');

        $this->SetFont('helvetica', '', 9);
        $this->SetXY(($this->GetPageWidth() - 80) / 2, 22);
        $this->Cell(80, 5, 'Divisi Teknologi Informasi', 0, false, 'C', 0, '', 0, false, 'M', 'M');

        $this->SetFont('helvetica', '', 7);
        $this->SetXY(($this->GetPageWidth() - 20) / 2, 13);
        $this->Cell(100, 5, 'Form: MIP/FRM/ITE/005', 0, false, 'R', 0, '', 0, false, 'M', 'M');

        $this->SetXY(($this->GetPageWidth() - 20) / 2, 18);
        $this->Cell(100, 5, 'Revisi: 00', 0, false, 'R', 0, '', 0, false, 'M', 'M');

        $this->SetLineWidth(0.5);
        $this->Line(10, -2 + $logo_height + 0, $this->GetPageWidth() - 10, -2 + $logo_height + 0);
        $this->SetLineWidth(0.2);

        $this->SetFont('helvetica', '', 11);
        $this->SetXY(($this->GetPageWidth() - 80) / 2, 40);
        $this->Cell(80, -2, 'No Inspeksi: ' . $nomorInspeksi, 0, false, 'C', 0, '', 0, false, 'M', 'M');        
        $this->Ln(5);
    }
    
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('helvetica','I',8);
        $this->Cell(0,10,''.$this->PageNo().'/{nb}',0,0,'C');
    }
    
    function AddScreenshots($target_screenshot_dir, $current_inspection_id) {
        $screenshotTitleShown = false; 
        $conn = new mysqli($GLOBALS['host'], $GLOBALS['user'], $GLOBALS['pass'], $GLOBALS['db']);
        $query = $conn->prepare("SELECT screenshot_name FROM screenshots WHERE form_no = ?");
        $query->bind_param("i", $current_inspection_id);
        $query->execute();
        $result = $query->get_result();
    
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $screenshot = $row['screenshot_name'];
                $screenshot_path = $target_screenshot_dir . $screenshot;
    
                if (!$screenshotTitleShown) { 
                    $this->SetFont('helvetica', 'B', 11);
                    $this->Cell(0, 10, 'Screenshot:', 0, 1, 'L');
                    $this->Ln(5);
                    $screenshotTitleShown = true;
                }
                $this->resizeAndInsertImage($screenshot_path);
            }
        }
        $query->close();
        $conn->close();
    }     
    
    function resizeAndInsertImage($imagePath) {
        list($width, $height) = getimagesize($imagePath);
        $maxWidth = 84; // Mengurangi lebar gambar
        $maxHeight = 52; // Mengurangi tinggi gambar
        $ratio = $width / $height;
    
        if ($width > $height) {
            $newWidth = $maxWidth;
            $newHeight = $maxWidth / $ratio;
        } else {
            $newHeight = $maxHeight;
            $newWidth = $maxHeight * $ratio;
        }
    
        $this->Image($imagePath, 10, null, $newWidth, $newHeight);
    }

    private $columnWidths = array(30, 140, 20);

    function addTableRow($item, $detail, $skor) {
        $fill = $this->RowNeedsFill();
        $this->Cell($this->columnWidths[0], 10, $item, 1, 0, 'C', $fill);
        $this->Cell($this->columnWidths[1], 10, $detail, 1, 0, 'C', $fill);
        $this->Cell($this->columnWidths[2], 10, $skor, 1, 1, 'C', $fill);
    }

    function RowNeedsFill() {
        return $this->GetY() % 20 === 0; // Change to whatever row interval you want to fill
    }
}

$pdf = new MYPDF($screenshot_files, $nomorInspeksi);
$pdf->AliasNbPages();
$pdf->AddPage();

$pageWidth = $pdf->GetPageWidth();
$cellWidth = $pageWidth / 4;
$maxTableWidth = $pageWidth * 0.90; 
$cellWidth = $maxTableWidth / 4; 
$pdf->SetLineWidth(0); 

$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell($cellWidth, 10, 'Tanggal:', 1, 0, 'L', false);
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell($cellWidth, 10, $row['date'], 1, 0, 'L', false);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell($cellWidth, 10, 'Nama pengguna:', 1, 0, 'L', false);
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell($cellWidth, 10, $row['nama_user'], 1, 1, 'L', false); 

$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell($cellWidth, 10, 'Tipe Perangkat:', 1, 0, 'L', false); 
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell($cellWidth, 10, $row['jenis'], 1, 0, 'L', false);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell($cellWidth, 10, 'Divisi:', 1, 0, 'L', false); 
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell($cellWidth, 10, $row['status'], 1, 1, 'L', false); 

$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell($cellWidth * 2, 10, 'Merk/Nomor Serial:', 1, 0, 'L', false);
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell($cellWidth * 2, 10, $row['merk'] . ' / ' . $row['serialnumber'], 1, 1, 'L', false); 

$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell($cellWidth * 2, 10, 'Lokasi/Area Penggunaan:', 1, 0, 'L', false);
$pdf->SetFont('helvetica', '', 11);
$pdf->Cell($cellWidth * 2, 10, $row['lokasi'], 1, 1, 'L', false);
$pdf->Ln(10);

// Informasi Keluhan
$complaints = explode("\n", $row['informasi_keluhan']);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 10, 'Informasi Keluhan/Permasalahan yang disampaikan:', 0, 1, 'L');
$pdf->SetFont('Arial', '', 11);

foreach ($complaints as $complaint) {
    $pdf->Cell(0, 10, $complaint, 'B');
    $pdf->Ln();
}

// Hasil Pemeriksaan
$pdf->Ln(5);
$complaints = explode("\n", $row['hasil_pemeriksaan']);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 10, 'Hasil Pemeriksaan:', 0, 1, 'L');
$pdf->SetFont('Arial', '', 11);

foreach ($complaints as $complaint) {
    $pdf->Cell(0, 10, $complaint, 'B');
    $pdf->Ln();
}

$pdf->Ln(5);

$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetFillColor(173, 216, 230);
$pdf->addTableRow('Item', 'Detail', 'Skor');

$pdf->SetFont('helvetica', '', 9);

$pdf->addTableRow('Casing', $row['casing_lap_name'], $row['casing_lap_score']);
$pdf->addTableRow('Layar', $row['layar_lap_name'], $row['layar_lap_score']);
$pdf->addTableRow('Proses Booting', $row['booting_lap_name'], $row['booting_lap_score']);
$pdf->addTableRow('Multitasking Apps', $row['multi_lap_name'], $row['multi_lap_score']);
$pdf->addTableRow('Port', $row['port_lap_name'], $row['port_lap_score']);
$pdf->addTableRow('Audio', $row['audio_lap_name'], $row['audio_lap_score']);
$pdf->addTableRow('Software', $row['software_lap_name'], $row['software_lap_score']);

$totalScore = $row['casing_lap_score'] + $row['layar_lap_score'] + $row['booting_lap_score'] + $row['multi_lap_score'] + $row['port_lap_score'] + $row['audio_lap_score'] + $row['software_lap_score'];
$pdf->SetFont('helvetica', 'B', 11);
$pdf->addTableRow('Total Skor', '', $totalScore);

// Rekomendasi
$pdf->Ln(5);
$complaints = explode("\n", $row['rekomendasi']);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 10, 'Rekomendasi:', 0, 1, 'L');
$pdf->SetFont('Arial', '', 11);

foreach ($complaints as $complaint) {
    $pdf->Cell(0, 10, $complaint, 'B');
    $pdf->Ln();
}

$pdf->Ln(5);
$pdf->AddScreenshots($target_screenshot_dir, $row['no']);

$current_inspection_id = $row['no'];
$target_screenshot_dir = $_SERVER['DOCUMENT_ROOT'] . "/podema/src/screenshot/";

// Mendapatkan daftar file screenshot terbaru di direktori
$latest_screenshot = null;
$latest_timestamp = 0;

if ($handle = opendir($target_screenshot_dir)) {
    while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != "..") {
            $screenshot_path = $target_screenshot_dir . $entry;
            $timestamp = filemtime($screenshot_path);

            if ($timestamp > $latest_timestamp) {
                $latest_timestamp = $timestamp;
                $latest_screenshot = $entry;
            }
        }
    }
    closedir($handle);
}

if ($latest_screenshot !== null && !in_array($latest_screenshot, $screenshot_files)) {
    $screenshot_path = $target_screenshot_dir . $latest_screenshot;
    list($width, $height) = getimagesize($screenshot_path);
    $maxWidth = 84; // Mengurangi lebar gambar
    $maxHeight = 52; // Mengurangi tinggi gambar
    $ratio = $width / $height;

    if ($width > $height) {
        $newWidth = $maxWidth;
        $newHeight = $maxWidth / $ratio;
    } else {
        $newHeight = $maxHeight;
        $newWidth = $maxHeight * $ratio;
    }

    $pdf->Image($screenshot_path, 10, null, $newWidth, $newHeight);
    $screenshot_files[] = $latest_screenshot;
}

$pdf->Ln(10);
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

$pdf->Ln(15); 

$pdf->Cell(95, 10, '', 0, 0, 'C');
$pdf->Cell(5, 10, '', 0, 0, 'C'); 
$pdf->Cell(95, 10, '', 0, 1, 'C');

$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetX(15);
$pdf->Cell(47.5, 10, 'Diperiksa Oleh', 'T', 0, 'L');
$pdf->Cell(5, 10, '', 0, 0, 'C'); 
$pdf->SetFont('helvetica', 'B', 10);
$pdf->SetX(95);
$pdf->Cell(47.5, 10, 'Nama Pengguna', 'T', 1, 'C');
$pdf->AliasNbPages();

$filename = "Inspection-Devices.pdf";
$pdf->Output($filename, 'D');
echo '<a href="Inspection-Devices.pdf">Download</a>';
exit;
?>
