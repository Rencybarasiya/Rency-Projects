<?php
session_start();

// DB connection
$conn = mysqli_connect("localhost", "root", "", "fees");
if (!$conn) {
    die("DB Connection failed: " . mysqli_connect_error());
}

// Check class selection
if (!isset($_SESSION['selected_class'])) {
    echo "<h3>No class selected. Please go back and choose a class.</h3>";
    exit();
}
$selected_class = $_SESSION['selected_class'];

// Map of class label -> table name
$tableMap = [
    "Class 1" => "student1", "Class 2" => "student2", "Class 3" => "student3",
    "Class 4" => "student4", "Class 5" => "student5", "Class 6" => "student6",
    "Class 7" => "student7", "Class 8" => "student8", "Class 9" => "student9",
    "Class 10" => "student10", "Class 11" => "student11", "Class 12" => "student12",
];
if (!array_key_exists($selected_class, $tableMap)) {
    echo "<h3>Invalid class selected.</h3>";
    exit();
}
$table = $tableMap[$selected_class];

// config
$total_installments = 4;
$installment_amount = 10000.00;

// flash / modal state helpers
$modalOpen = false;
$modalStudent = null;
$modalMessage = '';
$modalMessageType = '';
$postedMode = '';
$postedCheque = '';
$postedUpi = '';
$postedInstallment = null;

// ---------- Handle payment POST ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['student_id'], $_POST['installment'], $_POST['mode'])) {

    $student_id = intval($_POST['student_id']);
    $installment = intval($_POST['installment']);
    
    // ✅ Normalize payment mode
    $mode = trim($_POST['mode']);
    $mode = ucfirst(strtolower($mode)); // converts 'cash' → 'Cash', 'online' → 'Online'
    
    $cheque_no = trim($_POST['cheque_no'] ?? '');
    $upi_no = trim($_POST['upi_no'] ?? '');

    // Keep posted values so modal can be re-opened with them
    $postedMode = $mode;
    $postedCheque = $cheque_no;
    $postedUpi = $upi_no;
    $postedInstallment = $installment;

    $allowed_modes = ['Cash','Cheque','Online'];
    if (!in_array($mode, $allowed_modes, true)) {
        $modalOpen = true; $modalMessageType = 'error'; $modalMessage = "Invalid payment mode.";
    } elseif ($installment < 1 || $installment > $total_installments) {
        $modalOpen = true; $modalMessageType = 'error'; $modalMessage = "Invalid installment selected.";
    }

    // fetch student row
    if (!$modalOpen) {
        $stmtS = mysqli_prepare($conn, "SELECT id, name, installments_paid, paid_amount, due_amount FROM `$table` WHERE id = ? LIMIT 1");
        mysqli_stmt_bind_param($stmtS, "i", $student_id);
        mysqli_stmt_execute($stmtS);
        $resS = mysqli_stmt_get_result($stmtS);
        if (!$resS || mysqli_num_rows($resS) === 0) {
            mysqli_stmt_close($stmtS);
            $modalOpen = true; $modalMessageType = 'error'; $modalMessage = "Student not found in selected class.";
        } else {
            $student = mysqli_fetch_assoc($resS);
            mysqli_stmt_close($stmtS);
            $modalStudent = [
                'id' => (int)$student['id'],
                'name' => $student['name'],
                'installments_paid' => $student['installments_paid'] ?? '',
                'paid_amount' => floatval($student['paid_amount'] ?? 0),
                'due_amount' => floatval($student['due_amount'] ?? ($total_installments*$installment_amount)),
            ];
        }
    }

    // Mode-specific validation & uniqueness
    if (!$modalOpen) {
        if ($mode === 'Cheque') {
            if (!preg_match('/^\d{6}$/', $cheque_no)) {
                $modalOpen = true; $modalMessageType = 'error'; $modalMessage = "Cheque number must be exactly 6 digits.";
            } else {
                $stmt = mysqli_prepare($conn, "SELECT COUNT(*) FROM payments WHERE cheque_no = ?");
                mysqli_stmt_bind_param($stmt, "s", $cheque_no);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $cnt);
                mysqli_stmt_fetch($stmt);
                mysqli_stmt_close($stmt);
                if (!empty($cnt) && $cnt > 0) {
                    $modalOpen = true; $modalMessageType = 'error'; $modalMessage = "This Cheque number already exists. Use unique Cheque number.";
                }
            }
        } elseif ($mode === 'Online') {
            if (!preg_match('/^\d{10}$/', $upi_no)) {
                $modalOpen = true; 
                $modalMessageType = 'error'; 
                $modalMessage = "UPI number must be exactly 10 digits.";
            }
        }
    }

    // If still OK, insert
    if (!$modalOpen) {
        $installments_paid_str = $modalStudent['installments_paid'] ?? '';
        $parts = array_filter(explode(',', $installments_paid_str), 'strlen');
        $paid_array = array_map('intval', $parts);

        if (in_array($installment, $paid_array, true)) {
            $modalOpen = true; $modalMessageType = 'error'; $modalMessage = "Installment #$installment already paid for this student.";
        } else {
            $paid_array[] = $installment;
            sort($paid_array);
            $new_installments_str = implode(',', $paid_array);
            $new_paid_amount = count($paid_array) * $installment_amount;
            $new_due_amount = max(0, ($total_installments * $installment_amount) - $new_paid_amount);

            // get numeric class id from "Class X"
            if (preg_match('/\d+/', $selected_class, $mm)) $class_id_num = intval($mm[0]); else $class_id_num = 0;

            mysqli_begin_transaction($conn);

            // update class-specific student row
            $stmtU = mysqli_prepare($conn, "UPDATE `$table` SET installments_paid = ?, paid_amount = ?, due_amount = ? WHERE id = ?");
            mysqli_stmt_bind_param($stmtU, "siii", $new_installments_str, $new_paid_amount, $new_due_amount, $student_id);
            $ok = mysqli_stmt_execute($stmtU);
            mysqli_stmt_close($stmtU);

            if (!$ok) {
                mysqli_rollback($conn);
                $modalOpen = true; $modalMessageType = 'error'; $modalMessage = "Failed to update student record: " . mysqli_error($conn);
            } else {
                // insert into payments using prepared statement (all variables assigned)
                $insert_sql = "INSERT INTO payments
                    (student_id, student_name, class_id, installment_no, mode, cheque_no, upi_no, paid_amount, due_amount, paid_on)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmtI = mysqli_prepare($conn, $insert_sql);
                if (!$stmtI) {
                    mysqli_rollback($conn);
                    $modalOpen = true; $modalMessageType = 'error'; $modalMessage = "Prepare failed for payments insert: " . mysqli_error($conn);
                } else {
                    $student_name_esc = $modalStudent['name'];
                    $paid_on = date('Y-m-d');
                    $cheque_param = $cheque_no === '' ? null : $cheque_no;
                    $upi_param = $upi_no === '' ? null : $upi_no;
                    // required variables for bind
                    $s_student_id = $student_id;
                    $s_student_name = $student_name_esc;
                    $s_class_id = $class_id_num;
                    $s_installment_no = $installment;
                    $s_mode = $mode; // ✅ normalized mode
                    $s_cheque = $cheque_param;
                    $s_upi = $upi_param;
                    $s_paid_amount = (float)$installment_amount;
                    $s_due_amount = (float)$new_due_amount;
                    $s_paid_on = $paid_on;

                    $bind_types = "isiisssdds";
                    mysqli_stmt_bind_param($stmtI, $bind_types,
                        $s_student_id,
                        $s_student_name,
                        $s_class_id,
                        $s_installment_no,
                        $s_mode,
                        $s_cheque,
                        $s_upi,
                        $s_paid_amount,
                        $s_due_amount,
                        $s_paid_on
                    );

                    $exec_ok = mysqli_stmt_execute($stmtI);
                    if (!$exec_ok) {
                        $err = mysqli_stmt_error($stmtI);
                        mysqli_stmt_close($stmtI);
                        mysqli_rollback($conn);
                        $modalOpen = true; $modalMessageType = 'error'; $modalMessage = "Payment Insert Error: " . $err;
                    } else {
                        mysqli_stmt_close($stmtI);
                        mysqli_commit($conn);
                        $modalOpen = true; $modalMessageType = 'success'; $modalMessage = "Payment recorded successfully.";
                        // update modalStudent for UI refresh
                        $modalStudent['installments_paid'] = $new_installments_str;
                        $modalStudent['paid_amount'] = $new_paid_amount;
                        $modalStudent['due_amount'] = $new_due_amount;
                    }
                }
            }
        }
    }

    // ensure we have modalStudent
    if ($modalOpen && $modalStudent === null) {
        $stmtS2 = mysqli_prepare($conn, "SELECT id, name, installments_paid, paid_amount, due_amount FROM `$table` WHERE id = ? LIMIT 1");
        mysqli_stmt_bind_param($stmtS2, "i", $student_id);
        mysqli_stmt_execute($stmtS2);
        $resS2 = mysqli_stmt_get_result($stmtS2);
        if ($resS2 && mysqli_num_rows($resS2) > 0) {
            $s2 = mysqli_fetch_assoc($resS2);
            $modalStudent = [
                'id' => (int)$s2['id'],
                'name' => $s2['name'],
                'installments_paid' => $s2['installments_paid'] ?? '',
                'paid_amount' => floatval($s2['paid_amount'] ?? 0),
                'due_amount' => floatval($s2['due_amount'] ?? ($total_installments*$installment_amount)),
            ];
        } else {
            $modalStudent = [
                'id' => $student_id,
                'name' => '',
                'installments_paid' => '',
                'paid_amount' => 0,
                'due_amount' => $total_installments*$installment_amount,
            ];
        }
    }
}

// ---------- Fetch students ----------
$sql = "SELECT * FROM `$table` ORDER BY name ASC";
$res = mysqli_query($conn, $sql);
?>
<!-- Your HTML + JS below remains unchanged -->

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Students of <?= htmlspecialchars($selected_class) ?></title>
    <style>
        body { font-family: Comic Sans MS; padding: 30px; background-color: #e3eef1ff; font-size: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; background:#fff; }
        th, td { border: 1px solid #ccc; padding: 12px; text-align: left; }
        th { background-color: #e0f7fa; }
        button, .btn { font-family: Comic Sans MS; padding: 6px 12px; background-color: #007BFF; border: none; color: white; border-radius: 4px; cursor: pointer; }
        button:hover, .btn:hover { background-color: #0056b3; }
        .back { margin-top: 20px; display: inline-block; background: #2d89ef; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; }
        .modal { display: none; position: fixed; z-index: 2000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); }
        .modal-content { background: white; margin: 5% auto; padding: 20px; width: 520px; border-radius: 10px; position: relative; }
        .close { position: absolute; top: 10px; right: 15px; font-size: 24px; cursor: pointer; color: red; }
        .blurred { filter: blur(1px); pointer-events: none; color: gray; }
        .paid { color: green; font-weight: bold; }
        input[type=text], select { width:95%; padding:8px; margin:6px 0; border:1px solid #ccc; border-radius:4px; }
        .msg { padding:10px; border-radius:6px; margin-bottom:12px; }
        .msg.error { background:#ffecec; border:1px solid #f5a7a7; color:#a33; }
        .msg.success { background:#eef7ff; border:1px solid #cfe9ff; color:#0a4f8a; }
        /* installments: each on its own line */
        .installments div { margin: 8px 0; font-size: 16px; }

.btn-danger { background-color: #dc3545; }
.btn-danger:hover { background-color: #b02a37; }

  .delete-btn {
    background-color: #dc3545;   /* Red background */
    font-family: Comic Sans MS;
    border: none;
    color: white;
    border-radius: 4px;
    cursor: pointer;
    padding: 6px 12px;
    transition: background-color 0.3s ease;
}
.delete-btn:hover {
    background-color: #b02a37; /* Darker red on hover */
}


.top-right-image {
  position: absolute;
  top: 10px;
  right: 10px;
  z-index: 1000;
}

.top-right-image img {
  height: 60px; /* Adjust size as needed */
  width: auto;
  border-radius: 5px; /* optional for rounded corners */
}


    </style>
</head>
<body>
    
<div class="top-right-image">
    <img src="evil.jpg" alt="evil-eye Image">
</div>


<h2>📘 Students of <?= htmlspecialchars($selected_class) ?></h2>

<?php if ($modalMessage && $modalMessageType === 'success'): ?>
    <div class="msg success"><?= htmlspecialchars($modalMessage) ?></div>
<?php endif; ?>

<?php if (!$res || mysqli_num_rows($res) === 0): ?>
    <p>No students found.</p>
<?php else: ?>
    <table>
        <tr>
            <th>ID</th><th>Name</th><th>Phone</th><th>Paid</th><th>Due</th><th>Action</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($res)):
            $paid = $row['paid_amount'] ?? 0;
            $due = $row['due_amount'] ?? ($total_installments*$installment_amount - $paid);
        ?>
        <tr>
            <td><?= (int)$row['id'] ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['phone'] ?? 'N/A') ?></td>
            <td>₹<?= number_format($paid,2) ?></td>
            <td>₹<?= number_format($due,2) ?></td>
<td>
    <button class="btn view-btn"
        data-id="<?= (int)$row['id'] ?>"
        data-name="<?= htmlspecialchars($row['name'], ENT_QUOTES) ?>"
        data-installments="<?= htmlspecialchars($row['installments_paid'] ?? '', ENT_QUOTES) ?>"
        data-paid="<?= number_format($paid,2) ?>"
        data-due="<?= number_format($due,2) ?>">
        View Details
    </button>

    <!-- Delete button -->
   <button class="delete-btn"
    data-id="<?= (int)$row['id'] ?>"
    data-installments="<?= htmlspecialchars($row['installments_paid'] ?? '', ENT_QUOTES) ?>">
    Delete
</button>

</td>

        </tr>
        <?php endwhile; ?>
    </table>
<?php endif; ?>


<div id="feeModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <div id="feeDetails"></div>
    </div>
</div>


<?php include 'delete_modal.php'; ?>

<script>
function openModalObj(data) {
    // Build modal content
    let paidArray = data.installmentsPaid ? data.installmentsPaid.split(",").filter(Boolean).map(Number) : [];
    let total = <?= $total_installments ?>;
    let amount = <?= $installment_amount ?>;
    let next = 1;
    for (let i = 1; i <= total; i++) {
        if (!paidArray.includes(i)) { next = i; break; }
    }

    let html = '';
    if (data.message) {
        let cls = data.messageType === 'error' ? 'msg error' : 'msg success';
        html += `<div class="${cls}">${data.message}</div>`;
    }

    html += `<h3>Fee Details - ${escapeHtml(data.name)} (<?= htmlspecialchars($selected_class) ?>)</h3>`;
    html += `<p><strong>Paid:</strong> ₹${data.paidAmount} &nbsp; <strong>Due:</strong> ₹${data.dueAmount}</p>`;
    html += `<form method="POST" onsubmit="return clientValidate(this);">`;
    html += `<input type="hidden" name="student_id" value="${data.id}">`;
    html += `<div class="installments">`;
    for (let i=1;i<=total;i++){
        if (paidArray.includes(i)) {
            html += `<div class="blurred paid"><input type="checkbox" checked disabled> Installment ${i} (Paid)</div>`;
        } else if (i === (data.selected_installment || next)) {
            html += `<div><label><input type="checkbox" name="installment" value="${i}" required ${data.selected_installment && data.selected_installment==i ? 'checked' : ''}> Installment ${i} - ₹${amount}</label></div>`;
        } else {
            html += `<div class="blurred"><input type="checkbox" disabled> Installment ${i}</div>`;
        }
    }
    html += `</div>`;

    let modeValue = data.mode || '';
    html += `<label>Payment Mode:</label>`;
    html += `<select name="mode" id="modeSelect" onchange="toggleFields()" required>
                <option value="">--Select--</option>
                <option value="Cash" ${modeValue==='Cash'?'selected':''}>Cash</option>
                <option value="Cheque" ${modeValue==='Cheque'?'selected':''}>Cheque</option>
                <option value="Online" ${modeValue==='Online'?'selected':''}>Online</option>
             </select>`;

    let chkStyle = (modeValue==='Cheque') ? 'block':'none';
    let upiStyle = (modeValue==='Online') ? 'block':'none';
    let chequeVal = data.cheque_no ? data.cheque_no : '';
    let upiVal = data.upi_no ? data.upi_no : '';

    html += `<div id="chequeField" style="display:${chkStyle}; margin-top:8px;">
                <label>Cheque No. (6 digits):</label>
                <input type="text" name="cheque_no" id="cheque_no" maxlength="6" value="${escapeHtml(chequeVal)}">
             </div>`;

    html += `<div id="upiField" style="display:${upiStyle}; margin-top:8px;">
                <label>UPI No. (10 digits):</label>
                <input type="text" name="upi_no" id="upi_no" maxlength="10" value="${escapeHtml(upiVal)}">
             </div>`;

    // Buttons
    html += `<div style="margin-top:12px; display:flex; gap:10px;">`;
    html += `<button class="btn" type="submit">Submit Payment</button>`;

    // Fix: always set receiptInstallment properly
    let receiptInstallment = data.selected_installment || (paidArray.length ? paidArray[paidArray.length-1] : next);
    html += `<button type="button" class="btn" onclick="downloadReceipt(${data.id}, ${receiptInstallment})">Download Receipt</button>`;
    html += `</div>`;

    html += `</form>`;

    document.getElementById('feeDetails').innerHTML = html;
    document.getElementById('feeModal').style.display = 'block';
}

function closeModal() { document.getElementById('feeModal').style.display = 'none'; }
window.onclick = function(e){ if(e.target === document.getElementById('feeModal')) closeModal(); }

function toggleFields(){
    let mode = document.getElementById('modeSelect').value;
    document.getElementById('chequeField').style.display = (mode === 'Cheque') ? 'block' : 'none';
    document.getElementById('upiField').style.display = (mode === 'Online') ? 'block' : 'none';
    let chequeInput = document.getElementById('cheque_no');
    let upiInput = document.getElementById('upi_no');
    if (chequeInput) chequeInput.required = (mode === 'Cheque');
    if (upiInput) upiInput.required = (mode === 'Online');
}

function clientValidate(form) {
    let mode = form.mode.value;
    if (mode === 'Cheque') {
        let c = (form.cheque_no.value || '').trim();
        if (!/^\d{6}$/.test(c)) { alert('Enter a valid 6-digit cheque number'); return false; }
    }
    if (mode === 'Online') {
        let u = (form.upi_no.value || '').trim();
        if (!/^\d{10}$/.test(u)) { alert('Enter a valid 10-digit UPI number'); return false; }
    }
    return true;
}

function escapeHtml(unsafe) {
    if (unsafe === null || unsafe === undefined) return '';
    return String(unsafe)
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");
}

// Attach click handlers to all ".view-btn" buttons
document.addEventListener('DOMContentLoaded', function(){
    document.querySelectorAll('.view-btn').forEach(function(btn){
        btn.addEventListener('click', function(){
            const data = {
                id: btn.dataset.id,
                name: btn.dataset.name,
                installmentsPaid: btn.dataset.installments || '',
                paidAmount: btn.dataset.paid || '0.00',
                dueAmount: btn.dataset.due || '0.00',
                selected_installment: null,
                mode: '',
                cheque_no: '',
                upi_no: '',
                message: '',
                messageType: ''
            };
            openModalObj(data);
        });
    });

    // If server requested modal open (error/success) inject it now
    <?php if ($modalOpen && $modalStudent):
        $jsData = [
            'id' => $modalStudent['id'],
            'name' => $modalStudent['name'],
            'installmentsPaid' => $modalStudent['installments_paid'],
            'paidAmount' => number_format($modalStudent['paid_amount'],2),
            'dueAmount' => number_format($modalStudent['due_amount'],2),
            'message' => $modalMessage,
            'messageType' => $modalMessageType,
            'mode' => $postedMode,
            'cheque_no' => $postedCheque,
            'upi_no' => $postedUpi,
            'selected_installment' => $postedInstallment
        ];
    ?>
    openModalObj(<?= json_encode($jsData, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP) ?>);
    <?php endif; ?>
});

function downloadReceipt(studentId, installmentNo){
    if(!installmentNo){
        alert('Select an installment to download receipt.');
        return;
    }
    let url = `download_receipt.php?class=<?= urlencode($selected_class) ?>&student_id=${studentId}&installment=${installmentNo}`;
    
    // Create hidden iframe for download
    let iframe = document.createElement("iframe");
    iframe.style.display = "none";
    iframe.src = url;
    document.body.appendChild(iframe);

    // After small delay redirect to student_by_class.php
    setTimeout(function(){
        window.location.href = "student_by_class.php";
    }, 2000); // 2 seconds delay so file download can start
}


// Delete button click event
document.addEventListener('DOMContentLoaded', function(){
    document.querySelectorAll('.delete-btn').forEach(function(btn){
        btn.addEventListener('click', function(){
            let id = btn.dataset.id;
            let installments = btn.dataset.installments ? btn.dataset.installments.split(',').map(Number) : [];
            if(installments.length === 0){
                alert("No installment paid yet, nothing to delete.");
                return;
            }
            let lastInstallment = installments[installments.length-1];

            // fill modal hidden inputs
            document.getElementById('delete_student_id').value = id;
            document.getElementById('deleteInstallments').innerHTML =
                `<p>Last Paid Installment: ${lastInstallment}</p>
                 <input type="hidden" name="delete_installment" value="${lastInstallment}">`;

            // open modal
            document.getElementById('deleteModal').style.display = 'block';
        });
    });
});

// Close delete modal
function closeDeleteModal(){
    document.getElementById('deleteModal').style.display = 'none';
}



</script>
<br>
<p><a href="welcome.php">Back to Dashboard</a></p>

</body>
</html> 