<?php
// download_receipt.php
session_start();
require_once __DIR__ . '/fpdf/fpdf.php';  // include FPDF

// ✅ CLASS SESSION MATHI LO
if (!isset($_SESSION['class'])) {
    die("Unauthorized access.");
}
$classNum = intval($_SESSION['class']);
$class = "Class " . $classNum;

// DB connection
$conn = mysqli_connect("localhost","root","","fees");
if(!$conn){ die("DB Connection failed: ".mysqli_connect_error()); }

// get params (class GET thi nathi levanu)
$student_id = intval($_GET['student_id'] ?? 0);
$installment = intval($_GET['installment'] ?? 0);

if(!$student_id || !$installment){
    die("Invalid parameters");
}

// Map class -> table
$tableMap = [
    "Class 1"=>"student1","Class 2"=>"student2","Class 3"=>"student3",
    "Class 4"=>"student4","Class 5"=>"student5","Class 6"=>"student6",
    "Class 7"=>"student7","Class 8"=>"student8","Class 9"=>"student9",
    "Class 10"=>"student10","Class 11"=>"student11","Class 12"=>"student12",
];

if(!isset($tableMap[$class])) die("Invalid class");
$table = $tableMap[$class];

// fetch student info
$stmt = mysqli_prepare($conn,"SELECT id,name,class,phone FROM `$table` WHERE id=? LIMIT 1");
mysqli_stmt_bind_param($stmt,"i",$student_id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
if(!$res || mysqli_num_rows($res)==0) die("Student not found");
$student = mysqli_fetch_assoc($res);
mysqli_stmt_close($stmt);

// fetch payment info
$stmtP = mysqli_prepare($conn,"SELECT * FROM payments WHERE student_id=? AND installment_no=? ORDER BY paid_on DESC LIMIT 1");
mysqli_stmt_bind_param($stmtP,"ii",$student_id,$installment);
mysqli_stmt_execute($stmtP);
$resP = mysqli_stmt_get_result($stmtP);
$payment = mysqli_fetch_assoc($resP);
mysqli_stmt_close($stmtP);

if(!$payment){
    die("No payment record found for this installment.");
}

// Generate PDF
$pdf = new FPDF();
$pdf->AddPage();

// ===== START BOX =====
$startX = 10;
$startY = 10;
$pdf->SetXY($startX, $startY);

// ===== HEADER =====
$pdf->Ln(9); // 10 px niche move karo
$pdf->SetFont('Arial','B',20);
$pdf->Cell(0,7,"LITTLE STAR",0,2,'C');
$pdf->Ln(3); // 10 px niche move karo
$pdf->SetFont('Arial','',10);
$pdf->Cell(0,6,"Khothariya Main Rd., Bhojalram Soc., 80 FT Rd., Rajkot, Gujarat-360002",0,1,'C');
$pdf->Cell(0,6,"Phone: 0281-2561515 | Email: littlestarrajkot@gmail.com",0,1,'C');
$pdf->Cell(0,6,"Web: www.littlestarrajkot.in",0,1,'C');
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,8,"FEE RECEIPT - STUDENT COPY",0,1,'C');
$pdf->Ln(4);

// ===== STUDENT INFO SECTION =====
$pdf->SetFont('Arial','',11);
$receiptDate = date("d-m-Y", strtotime($payment['paid_on']));
$pdf->Cell(95,8,"Receipt No: ".$payment['id'],0,0);
$pdf->Cell(95,8,"Receipt Date: ".$receiptDate,0,1);
$pdf->Cell(95,8,"Student Name: ".$student['name'],0,0);
$pdf->Cell(95,8,"Class: ".$class,0,1);
$pdf->Ln(4);

// ===== TITLE =====
$pdf->SetFont('Arial','B',11);
$pdf->Cell(20,8,"S.No",1,0,'C');
$pdf->Cell(120,8,"Description",1,0,'C');
$pdf->Cell(50,8,"Amount",1,1,'C');

$pdf->SetFont('Arial','',11);
$pdf->Cell(20,20,"1",1,0,'C');
$pdf->Cell(120,20,"Tuition Fee",1,0,'L');
$pdf->Cell(50,20,"Rs. ".number_format($payment['paid_amount'],2),1,1,'L');

// ===== PAYMENT DETAIL TABLE =====
$pdf->SetFont('Arial','B',11);
$pdf->Cell(40,8,"Pay Mode",1,0,'C');
$pdf->Cell(40,8,"Date",1,0,'C');
$pdf->Cell(40,8,"Installment No",1,0,'C');
$pdf->Cell(40,8,"Cheque No. / UPI No.",1,0,'C');
$pdf->Cell(30,8,"Amount",1,1,'C');

$pdf->SetFont('Arial','',11);
$cheque_upi = "-";
if (isset($payment['mode'])) {
    if (strtolower($payment['mode']) === "cheque" && !empty($payment['cheque_no'])) {
        $cheque_upi = $payment['cheque_no'];
    } elseif (strtolower($payment['mode']) === "online" && !empty($payment['upi_no'])) {
        $cheque_upi = $payment['upi_no'];
    }
}

$pdf->Cell(40,8,($payment['mode'] ?? 'N/A'),1,0,'C');
$pdf->Cell(40,8,date("d-m-Y", strtotime($payment['paid_on'])),1,0,'C');
$pdf->Cell(40,8,"#".$payment['installment_no'],1,0,'C');
$pdf->Cell(40,8,$cheque_upi,1,0,'C');
$pdf->Cell(30,8,"Rs. ".number_format($payment['paid_amount'],2),1,1,'C');

$pdf->Ln(4);
$pdf->SetFont('Arial','',11);
$pdf->Cell(0,8,"Amount Received: Rs. ".number_format($payment['paid_amount'],2),0,1);
$pdf->Ln(6);

// ===== FOOTER =====
$pdf->SetFont('Arial','I',9);
$pdf->MultiCell(0,6,"\nNote: Fee once paid will not be refunded under any circumstances.\nIf the cheque returns for any reason then the cheque return charges will be levied from the student.");
$pdf->Ln(15);
$pdf->SetFont('Arial','',11);
$pdf->Cell(90,10,"[STAMP HERE]",0,0,'L');
$pdf->Cell(90,10,"SIGNATURE",0,1,'R');

// ===== END BOX =====
$endY = $pdf->GetY();
$pdf->SetLineWidth(0.4);
$pdf->Rect($startX, $startY, 190, $endY - $startY + 5);

// OUTPUT FILE
$filename = "Fee_Receipt_" . str_replace(' ', '', $student['name']) . "_Inst{$installment}.pdf";
$pdf->Output("D", $filename);
exit;
?>
