<?php
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);

/**
 * enviar_correo.php (Versión Limpia para 10 Preguntas)
 */

// ==============================
// Capturar datos del nuevo formulario
// ==============================
$job_number            = $_POST['Job_Number'] ?? '';
$salesperson           = $_POST['Salesperson'] ?? '';
$order_paid            = $_POST['Order_Paid'] ?? '';
$bill_rate             = $_POST['Bill_Rate'] ?? '';
$operating_cost        = $_POST['Operating_Cost'] ?? '';
$supplies              = $_POST['Supplies'] ?? '';
$fixed_expenses        = $_POST['Fixed_Expenses'] ?? '';
$net_utility           = $_POST['Net_Utility'] ?? '';
$commission_percentage = $_POST['Commission_Percentage'] ?? '';
$commission_amount     = $_POST['Commission_Amount'] ?? '';

// ==============================
// Generar contenido HTML del PDF
// ==============================
ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>
  body { font-family: Arial, sans-serif; background-color:#fff; color:#333; }
  h1 { text-align:center; color:#a30000; }
  .section { margin-top:20px; border-left:4px solid #a30000; padding-left:10px; }
  .section h2 { color:#a30000; font-size:18px; margin-bottom:10px; }
  .field { margin-bottom:6px; }
  .label { font-weight:bold; display:inline-block; min-width:220px; }
</style>
</head>

<body style="font-family: Arial, sans-serif;">

<div style="
    width: 750px;
    margin: 0 auto;
    padding: 40px 50px;
    font-size: 14px;
    line-height: 1.55;
">

    <!-- LOGO -->
    <div style="text-align:center; margin-bottom:15px;">
        <img src="Images/Facility.png" style="width:240px;">
    </div>

    <!-- TITULO -->
    <h1 style="text-align:center; color:#a30000; margin:5px 0 0 0; font-size:26px;">
        Commission Authorization Form
    </h1>

    <!-- FECHA -->
    <div style="text-align:center; font-size:13px; color:#555; margin-bottom:30px;">
        Date: <?= date('m/d/Y') ?>
    </div>

    <!-- INTRO -->
    <p style="text-align:justify;">
        This document outlines the commission corresponding to the work performed.
        The information entered in this form will be written manually by the responsible user and must match exactly the details of the assigned Work Order.
        <br><br>
        Any discrepancy between this document and the official Work Order may delay the processing and authorization of the commission.
    </p>

    <!-- SUMMARY -->
    <h2 style="color:#a30000; margin-top:25px;">Commission Summary</h2>

    <table style="width:100%; font-size:14px; line-height:1.6;">
        <tr><td><strong>Job / Work Order Number:</strong></td><td><?= htmlspecialchars($job_number) ?></td></tr>
        <tr><td><strong>Salesperson:</strong></td><td><?= htmlspecialchars($salesperson) ?></td></tr>
        <tr><td><strong>Order Paid:</strong></td><td><?= htmlspecialchars($order_paid) ?></td></tr>
        <tr><td><strong>Bill Rate:</strong></td><td>$<?= htmlspecialchars($bill_rate) ?></td></tr>
        <tr><td><strong>Operating Cost:</strong></td><td>$<?= htmlspecialchars($operating_cost) ?></td></tr>
        <tr><td><strong>Supplies:</strong></td><td>$<?= htmlspecialchars($supplies) ?></td></tr>
        <tr><td><strong>Operational & Fixed Expenses:</strong></td><td>$<?= htmlspecialchars($fixed_expenses) ?></td></tr>
        <tr><td><strong>Net Utility:</strong></td><td>$<?= htmlspecialchars($net_utility) ?></td></tr>
        <tr><td><strong>Commission Percentage:</strong></td><td><?= htmlspecialchars($commission_percentage) ?>%</td></tr>
        <tr><td><strong>Total Commission:</strong></td><td>$<?= htmlspecialchars($commission_amount) ?></td></tr>
    </table>

    <!-- NOTES -->
    <h2 style="color:#a30000; margin-top:30px;">Notes</h2>
    <p style="text-align:justify;">
        The commission will only be processed once all information has been verified and approved by the appropriate department.
        Any missing, incorrect, or inconsistent information may delay processing.
    </p>

    <!-- POLICY -->
    <h2 style="color:#a30000; margin-top:30px;">Commission Policy</h2>
    <ul style="margin-top:5px;">
        <li>The commission applies only to the Work Order listed on this form.</li>
        <li>The Work Order must be paid and validated before any commission can be issued.</li>
        <li>Commission percentages are capped according to internal company policy.</li>
        <li>Any fraudulent or incorrect reporting may result in disciplinary action.</li>
    </ul>

    <!-- EXTRA CLAUSES -->
    <h2 style="color:#a30000; margin-top:30px;">Additional Clauses</h2>
    <ul>
        <li>All submitted information must be accurate and verifiable.</li>
        <li>The company reserves the right to adjust or deny commission if discrepancies are found.</li>
        <li>This document must match the Work Order records in the system.</li>
    </ul>


    <!-- SIGNATURE -->
    <div style="margin-top:50px; font-size:14px;">
        <br><br>
        ______________________________________<br>
        Authorized by: Rafael Perez<br>
        Position: Senior Vice President<br>
        Date: _______________________________
    </div>

</div>

</body>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const isMobile = /iPad|iPhone|iPod|Android/i.test(navigator.userAgent);

  // 🔥 AHORA SÍ FUNCIONA EL BASE64
  const pdfData = "data:application/pdf;base64,<?= $base64pdf ?>";

  document.getElementById('openPDF').addEventListener('click', () => {

    if (isMobile) {
      // Mobile opens new tab
      window.open(pdfData, '_blank');
    } else {
      // PC: download file
      const a = document.createElement('a');
      a.href = pdfData;
      a.download = "CommissionForm_<?= $timestamp ?>.pdf";
      document.body.appendChild(a);
      a.click();
      document.body.removeChild(a);
    }

  });
});
</script>

</html>
