<?php

require_once('tcpdf/tcpdf.php');

$host = "mandiricoal.net";
$user = "podema"; 
$pass = "Jam10pagi#";
$db = "podema";
$conn = new mysqli($host, $user, $pass, $db);

$result = mysqli_query($conn, "SELECT assess_pc.*, pctype_pc.pctype_name, operating_sistem_pc.os_name, processor_pc.processor_name, vga_pc.vga_name, device_age_pc.age_name, 
                            issue_software_pc.issue_name, ram_pc.ram_name, 
                            storage_pc.storage_name, typemonitor_pc.monitor_name, sizemonitor_pc.size_name
                              FROM assess_pc
                              JOIN pctype_pc ON assess_pc.typepc = pctype_pc.pctype_score
                              JOIN operating_sistem_pc ON assess_pc.os = operating_sistem_pc.os_score
                              JOIN processor_pc ON assess_pc.processor = processor_pc.processor_score
                              JOIN vga_pc ON assess_pc.vga = vga_pc.vga_score
                              JOIN device_age_pc ON assess_pc.age = device_age_pc.age_score
                              JOIN issue_software_pc ON assess_pc.issue = issue_software_pc.issue_score
                              JOIN ram_pc ON assess_pc.ram = ram_pc.ram_score
                              JOIN storage_pc ON assess_pc.storage = storage_pc.storage_score
                              JOIN typemonitor_pc ON assess_pc.typemonitor = typemonitor_pc.monitor_score
                              JOIN sizemonitor_pc ON assess_pc.sizemonitor = sizemonitor_pc.size_score
                              ORDER BY assess_pc.id DESC
                              LIMIT 1");

$query = mysqli_fetch_array($result);
$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetMargins(10, 10, 10);
$pdf->SetFont('helvetica', 'B', 10);

class MYPDF extends TCPDF {

    public function Header() {
        $this->SetFont('helvetica', 'B', 15);
        $this->Ln(5);
        $this->MultiCell(0, 5, 'ASSESSMENT PENGGANTIAN PC DESKTOP', 0, 'C', false);
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
        return str_pad($this->runningNumber, 3, '0', STR_PAD_LEFT) . "/MIP/APC/" . date('m/Y');
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

$pdf->SetFont('helvetica', '', 12);

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
$pdf->writeHTML('<p style="font-size: 16px;"><strong>Tipe/Merk:</strong> ' . $query['merk'] . '</p>', true, false, true, false, '');
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
$html .= '<td style="border: 1px solid #000; padding: 5px; font-weight: bold;">PC Type</td>';
$html .= '<td style="border: 1px solid #000; padding: 5px;">' . $query['pctype_name'] . '</td>';
$html .= '<td style="border: 1px solid #000; padding: 5px; text-align: center;">' . $query['typepc'] . '</td>';
$html .= '</tr>';

$html .= '<tr>';
$html .= '<td style="background-color: #FFE4B5;border: 1px solid #000; padding: 5px; font-weight: bold;">Operating System</td>';
$html .= '<td style="background-color: #FFE4B5;border: 1px solid #000; padding: 5px;">' . $query['os_name'] . '</td>';
$html .= '<td style="background-color: #FFE4B5;border: 1px solid #000; padding: 5px; text-align: center;">' . $query['os'] . '</td>';
$html .= '</tr>';

$html .= '<tr>';
$html .= '<td style="border: 1px solid #000; padding: 5px; font-weight: bold;">Processor</td>';
$html .= '<td style="border: 1px solid #000; padding: 5px;">' . $query['processor_name'] . '</td>';
$html .= '<td style="border: 1px solid #000; padding: 5px; text-align: center;">' . $query['processor'] . '</td>';
$html .= '</tr>';

$html .= '<tr>';
$html .= '<td style="background-color: #FFE4B5; border: 1px solid #000; padding: 5px; font-weight: bold;">VGA</td>';
$html .= '<td style="background-color: #FFE4B5; border: 1px solid #000; padding: 5px;">' . $query['vga_name'] . '</td>';
$html .= '<td style="background-color: #FFE4B5; border: 1px solid #000; padding: 5px; text-align: center;">' . $query['vga'] . '</td>';
$html .= '</tr>';

$html .= '<tr>';
$html .= '<td style="border: 1px solid #000; padding: 5px; font-weight: bold;">RAM</td>';
$html .= '<td style="border: 1px solid #000; padding: 5px;">' . $query['ram_name'] . '</td>';
$html .= '<td style="border: 1px solid #000; padding: 5px; text-align: center;">' . $query['ram'] . '</td>';
$html .= '</tr>';

$html .= '<tr>';
$html .= '<td style="background-color: #FFE4B5; border: 1px solid #000; padding: 5px; font-weight: bold;">Storage</td>';
$html .= '<td style="background-color: #FFE4B5; border: 1px solid #000; padding: 5px;">' . $query['storage_name'] . '</td>';
$html .= '<td style="background-color: #FFE4B5; border: 1px solid #000; padding: 5px; text-align: center;">' . $query['storage'] . '</td>';
$html .= '</tr>';

$html .= '<tr>';
$html .= '<td style="border: 1px solid #000; padding: 5px; font-weight: bold;">Device Age</td>';
$html .= '<td style="border: 1px solid #000; padding: 5px;">' . $query['age_name'] . '</td>';
$html .= '<td style="border: 1px solid #000; padding: 5px; text-align: center;">' . $query['age'] . '</td>';
$html .= '</tr>';

$html .= '<tr>';
$html .= '<td style="background-color: #FFE4B5; border: 1px solid #000; padding: 5px; font-weight: bold;">Type Monitor</td>';
$html .= '<td style="background-color: #FFE4B5; border: 1px solid #000; padding: 5px;">' . $query['monitor_name'] . '</td>';
$html .= '<td style="background-color: #FFE4B5; border: 1px solid #000; padding: 5px; text-align: center;">' . $query['typemonitor'] . '</td>';
$html .= '</tr>';

$html .= '<tr>';
$html .= '<td style="border: 1px solid #000; padding: 5px; font-weight: bold;">Size Monitor</td>';
$html .= '<td style="border: 1px solid #000; padding: 5px;">' . $query['size_name'] . '</td>';
$html .= '<td style="border: 1px solid #000; padding: 5px; text-align: center;">' . $query['sizemonitor'] . '</td>';
$html .= '</tr>';

$html .= '<tr>';
$html .= '<td style="background-color: #FFE4B5; border: 1px solid #000; padding: 5px; font-weight: bold;">Issue Related Software</td>';
$html .= '<td style="background-color: #FFE4B5; border: 1px solid #000; padding: 5px;">' . $query['issue_name'] . '</td>';
$html .= '<td style="background-color: #FFE4B5; border: 1px solid #000; padding: 5px; text-align: center;">' . $query['issue'] . '</td>';
$html .= '</tr>';

$totalScore = $query ['typepc'] + $query['os'] + $query['processor'] + $query['vga'] + $query['ram'] + $query['storage'] + $query['age'] + $query['typemonitor'] + $query['sizemonitor'] + $query['issue'];

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

$filename = "Assessment-for-PC-Replacement ({$runningNumberFormatted}).pdf";
$pdf->Output($filename, 'D');
exit;
?>