<?php
require_once('tcpdf/tcpdf.php');

$host = "localhost";
$user = "root"; 
$pass = ""; 
$db = "podema";
$conn = new mysqli($host, $user, $pass, $db);

$result = mysqli_query($conn, "SELECT assess_laptop.*, operating_sistem_laptop.os_name, processor_laptop.processor_name, batterylife_laptop.battery_name, device_age_laptop.age_name, issue_software_laptop.issue_name, ram_laptop.ram_name, 
                              storage_laptop.storage_name, keyboard_laptop.keyboard_name, screen_laptop.screen_name, touchpad_laptop.touchpad_name, audio_laptop.audio_name, body_laptop.body_name
                              FROM assess_laptop
                              JOIN operating_sistem_laptop ON assess_laptop.os = operating_sistem_laptop.os_score
                              JOIN processor_laptop ON assess_laptop.processor = processor_laptop.processor_score
                              JOIN batterylife_laptop ON assess_laptop.batterylife = batterylife_laptop.battery_score
                              JOIN device_age_laptop ON assess_laptop.age = device_age_laptop.age_score
                              JOIN issue_software_laptop ON assess_laptop.issue = issue_software_laptop.issue_score
                              JOIN ram_laptop ON assess_laptop.ram = ram_laptop.ram_score
                              JOIN storage_laptop ON assess_laptop.storage = storage_laptop.storage_score
                              JOIN keyboard_laptop ON assess_laptop.keyboard = keyboard_laptop.keyboard_score
                              JOIN screen_laptop ON assess_laptop.screen = screen_laptop.screen_score
                              JOIN touchpad_laptop ON assess_laptop.touchpad = touchpad_laptop.touchpad_score
                              JOIN audio_laptop ON assess_laptop.audio = audio_laptop.audio_score
                              JOIN body_laptop ON assess_laptop.body = body_laptop.body_score
                              ORDER BY assess_laptop.id DESC
                              LIMIT 1");

$query = mysqli_fetch_array($result);
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetMargins(10, 10, 10);
$pdf->SetFont('helvetica', 'B', 10);

class MYPDF extends TCPDF {

    public function Header() {
        $this->SetFont('helvetica', 'B', 15);
        $this->SetX(15);
        $this->MultiCell(0, 5, 'ASSESSMENT PENGGANTIAN LAPTOP', 0, 'C', false);
        $this->SetLineWidth(0.5);
        $this->Line($this->GetX(), $this->GetY() + 1, $this->GetX() + $this->GetPageWidth() - $this->GetX() - $this->rMargin, $this->GetY() + 1);
        $this->SetLineWidth(0.2);
        $this->Ln(5);
    }
    
    public function Footer() {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'B', 6);
        $this->Cell(0, 10, ''.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }

    private $runningNumber = 0;

    public function GetRunningNumber() {
        $this->runningNumber++;
        return str_pad($this->runningNumber, 3, '0', STR_PAD_LEFT) . "/MIP/APL/" . date('m/Y');
    }
}

$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

$pdf->SetFont('times', '', 12);

$name = $query['name'];
$runningNumberFormatted = $pdf->GetRunningNumber();
$pdf->AddPage();
$pdf->writeHTML('<p style="font-size: 16px;"><strong>Nomor Assessment:</strong> ' . $runningNumberFormatted . '</p>', true, false, true, false, '');
$pdf->Ln(5);
$pdf->writeHTML('<p style="font-size: 16px;"><strong>Nama Pengguna:</strong> ' . $query['name'] . '</p>', true, false, true, false, '');
$pdf->Ln(5);
$pdf->writeHTML('<p style="font-size: 16px;"><strong>Perusahaan:</strong> ' . $query['company'] . '</p>', true, false, true, false, '');
$pdf->Ln(5);
$pdf->writeHTML('<p style="font-size: 16px;"><strong>Divisi:</strong> ' . $query['divisi'] . '</p>', true, false, true, false, '');
$pdf->Ln(5);
$pdf->writeHTML('<p style="font-size: 16px;"><strong>Tipe/Merk:</strong> ' . $query['type'] . '</p>', true, false, true, false, '');
$pdf->Ln(4);

$html = '<table style="width: 100%; border-collapse: collapse;">';
$html .= '<thead>';
$html .= '<tr>';
$html .= '<th style="background-color: #FFE4B5; border: 1px solid #000; padding: 5px; text-align: center; font-weight: bold;">Detail</th>';
$html .= '<th style="background-color: #FFE4B5; border: 1px solid #000; padding: 5px; text-align: center; font-weight: bold;">Deskripsi</th>';
$html .= '<th style="background-color: #FFE4B5; border: 1px solid #000; padding: 5px; text-align: center; font-weight: bold;">Skor</th>';
$html .= '</tr>';
$html .= '</thead>';
$html .= '<tbody>';

$html .= '<tr>';
$html .= '<td style="border: 1px solid #000; padding: 5px; font-weight: bold;">Operating System</td>';
$html .= '<td style="border: 1px solid #000; padding: 5px;">' . $query['os_name'] . '</td>';
$html .= '<td style="border: 1px solid #000; padding: 5px; text-align: center;">' . $query['os'] . '</td>';
$html .= '</tr>';

$html .= '<tr>';
$html .= '<td style="background-color: #FFE4B5; border: 1px solid #000; padding: 5px; font-weight: bold;">Processor</td>';
$html .= '<td style="background-color: #FFE4B5; border: 1px solid #000; padding: 5px;">' . $query['processor_name'] . '</td>';
$html .= '<td style="background-color: #FFE4B5; border: 1px solid #000; padding: 5px; text-align: center;">' . $query['processor'] . '</td>';
$html .= '</tr>';

$html .= '<tr>';
$html .= '<td style="border: 1px solid #000; padding: 5px; font-weight: bold;">Battery Life</td>';
$html .= '<td style="border: 1px solid #000; padding: 5px;">' . $query['battery_name'] . '</td>';
$html .= '<td style="border: 1px solid #000; padding: 5px; text-align: center;">' . $query['batterylife'] . '</td>';
$html .= '</tr>';

$html .= '<tr>';
$html .= '<td style="background-color: #FFE4B5;border: 1px solid #000; padding: 5px; font-weight: bold;">Device Age</td>';
$html .= '<td style="background-color: #FFE4B5;border: 1px solid #000; padding: 5px;">' . $query['age_name'] . '</td>';
$html .= '<td style="background-color: #FFE4B5;border: 1px solid #000; padding: 5px; text-align: center;">' . $query['age'] . '</td>';
$html .= '</tr>';

$html .= '<tr>';
$html .= '<td style="border: 1px solid #000; padding: 5px; font-weight: bold;">Issue Related Software</td>';
$html .= '<td style="border: 1px solid #000; padding: 5px;">' . $query['issue_name'] . '</td>';
$html .= '<td style="border: 1px solid #000; padding: 5px; text-align: center;">' . $query['issue'] . '</td>';
$html .= '</tr>';

$html .= '<tr>';
$html .= '<td style="background-color: #FFE4B5; border: 1px solid #000; padding: 5px; font-weight: bold;">RAM</td>';
$html .= '<td style="background-color: #FFE4B5; border: 1px solid #000; padding: 5px;">' . $query['ram_name'] . '</td>';
$html .= '<td style="background-color: #FFE4B5; border: 1px solid #000; padding: 5px; text-align: center;">' . $query['ram'] . '</td>';
$html .= '</tr>';

$html .= '<tr>';
$html .= '<td style="border: 1px solid #000; padding: 5px; font-weight: bold;">Storage</td>';
$html .= '<td style="border: 1px solid #000; padding: 5px;">' . $query['storage_name'] . '</td>';
$html .= '<td style="border: 1px solid #000; padding: 5px; text-align: center;">' . $query['storage'] . '</td>';
$html .= '</tr>';

$html .= '<tr>';
$html .= '<td style="background-color: #FFE4B5; border: 1px solid #000; padding: 5px; font-weight: bold;">Keyboard</td>';
$html .= '<td style="background-color: #FFE4B5; border: 1px solid #000; padding: 5px;">' . $query['keyboard_name'] . '</td>';
$html .= '<td style="background-color: #FFE4B5; border: 1px solid #000; padding: 5px; text-align: center;">' . $query['keyboard'] . '</td>';
$html .= '</tr>';

$html .= '<tr>';
$html .= '<td style="border: 1px solid #000; padding: 5px; font-weight: bold;">Screen</td>';
$html .= '<td style="border: 1px solid #000; padding: 5px;">' . $query['screen_name'] . '</td>';
$html .= '<td style="border: 1px solid #000; padding: 5px; text-align: center;">' . $query['screen'] . '</td>';
$html .= '</tr>';

$html .= '<tr>';
$html .= '<td style="background-color: #FFE4B5; border: 1px solid #000; padding: 5px; font-weight: bold;">Touchpad</td>';
$html .= '<td style="background-color: #FFE4B5; border: 1px solid #000; padding: 5px;">' . $query['touchpad_name'] . '</td>';
$html .= '<td style="background-color: #FFE4B5; border: 1px solid #000; padding: 5px; text-align: center;">' . $query['touchpad'] . '</td>';
$html .= '</tr>';

$html .= '<tr>';
$html .= '<td style="border: 1px solid #000; padding: 5px; font-weight: bold;">Audio</td>';
$html .= '<td style="border: 1px solid #000; padding: 5px;">' . $query['audio_name'] . '</td>';
$html .= '<td style="border: 1px solid #000; padding: 5px; text-align: center;">' . $query['audio'] . '</td>';
$html .= '</tr>';

$html .= '<tr>';
$html .= '<td style="background-color: #FFE4B5; border: 1px solid #000; padding: 5px; font-weight: bold;">Body</td>';
$html .= '<td style="background-color: #FFE4B5; border: 1px solid #000; padding: 5px;">' . $query['body_name'] . '</td>';
$html .= '<td style="background-color: #FFE4B5; border: 1px solid #000; padding: 5px; text-align: center;">' . $query['body'] . '</td>';
$html .= '</tr>';

$totalScore = $query['os'] + $query['processor'] + $query['batterylife'] + $query['age'] + $query['issue'] + $query['ram'] + $query['storage'] + $query['keyboard'] + $query['screen'] + $query['touchpad'] + $query['audio'] + $query['body'];

$html .= '<tr>';
$html .= '<td colspan="2" style="border: 1px solid #000; padding: 5px; text-align: center; font-weight: bold;">Total Skor</td>';
$html .= '<td style="border: 1px solid #000; padding: 5px; text-align: center;">' . $totalScore . '</td>';
$html .= '</tr>';

$html .= '</tbody>';
$html .= '</table>';

$pdf->writeHTML($html, true, false, true, false, '');

if ($totalScore > 100) {
    $recommendation = 'Berdasarkan pada hasil diatas, <b>direkomendasikan untuk mengganti perangkat Anda dengan yang baru</b>.';
} else {
    $recommendation = 'Berdasarkan pada hasil diatas, <b>dinyatakan bahwa perangkat Anda masih dapat digunakan</b>.<br>' . "\n" .
                      'Oleh karena itu, tim IT akan melakukan peningkatan sesuai dengan kebutuhan perangkat Anda.';
}

$pdf->SetFont('', ''); 
$pdf->writeHTML($recommendation, true, false, true, false);

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

$pdf->Ln(20); 

$pdf->Cell(95, 10, '', 0, 0, 'C');
$pdf->Cell(5, 10, '', 0, 0, 'C'); 
$pdf->Cell(95, 10, '', 0, 1, 'C');

$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(47.5, 10, 'Diperiksa Oleh', 'T', 0, 'L');
$pdf->Cell(5, 10, '', 0, 0, 'C'); 
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(47.5, 10, 'Nama Pengguna', 'T', 1, 'C');

// awal
$filename = "Assessment-for-Laptop-Replacement ({$runningNumberFormatted}).pdf";
$pdf->Output($filename, 'D');
exit;
?>