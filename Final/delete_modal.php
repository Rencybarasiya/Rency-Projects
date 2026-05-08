<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$conn = mysqli_connect("localhost", "root", "", "fees");
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_student_id'], $_POST['delete_installment'])) {
    $class = $_SESSION['selected_class'] ?? '';
    $tableMap = [
        "Class 1" => "student1", "Class 2" => "student2", "Class 3" => "student3",
        "Class 4" => "student4", "Class 5" => "student5", "Class 6" => "student6",
        "Class 7" => "student7", "Class 8" => "student8", "Class 9" => "student9",
        "Class 10" => "student10", "Class 11" => "student11", "Class 12" => "student12",
    ];

    if (!isset($tableMap[$class])) {
        die("Invalid class");
    }

    $table = $tableMap[$class];
    $student_id = (int)$_POST['delete_student_id'];
    $delete_installment = (int)$_POST['delete_installment'];

    // Fetch student row
    $sql = "SELECT * FROM `$table` WHERE id = $student_id";
    $res = mysqli_query($conn, $sql);

    if ($res && mysqli_num_rows($res) > 0) {
        $student = mysqli_fetch_assoc($res);

        $paid_array = array_filter(explode(',', $student['installments_paid'] ?? ''), 'strlen');
        $paid_array = array_map('intval', $paid_array);

        $payment_modes_array = array_values(array_filter(explode(',', $student['payment_mode'] ?? ''), 'strlen'));

        // Only delete if last installment
        if (!empty($paid_array) && $delete_installment === max($paid_array)) {
            // Remove last installment from arrays
            array_pop($paid_array);
            array_pop($payment_modes_array);

            $new_paid_string = implode(',', $paid_array);
            $new_payment_mode_string = implode(',', $payment_modes_array);

            $total_installments = 4;
            $installment_amount = 10000;
            $paid_amount = count($paid_array) * $installment_amount;
            $due_amount = ($total_installments * $installment_amount) - $paid_amount;

            // Update student table
            $update_sql = "UPDATE `$table` SET 
                installments_paid = '$new_paid_string',
                paid_amount = $paid_amount,
                due_amount = $due_amount,
                payment_mode = '$new_payment_mode_string'
                WHERE id = $student_id";
            mysqli_query($conn, $update_sql);

            // --- DELETE corresponding payment from payments table ---
            if (preg_match('/\d+/', $class, $mm)) $class_id_num = intval($mm[0]); else $class_id_num = 0;

            $delete_payment_sql = "DELETE FROM payments 
                WHERE student_id = $student_id 
                  AND class_id = $class_id_num 
                  AND installment_no = $delete_installment
                LIMIT 1";
            mysqli_query($conn, $delete_payment_sql);
        }
    }

    header("Location: student_by_class.php"); // redirect back to main page
    exit();
}
?>

<!-- Delete Modal -->
<div id="deleteModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeDeleteModal()">&times;</span>
    <h3>Delete Last Paid Installment</h3>
    <form method="POST" action="delete_modal.php" onsubmit="return confirm('Are you sure you want to delete this installment?');">
        <input type="hidden" name="delete_student_id" id="delete_student_id">
        <div id="deleteInstallments"></div>
        <button type="submit" class="btn btn-confirm">Yes, Delete</button>
        <button type="button" class="btn btn-cancel" onclick="closeDeleteModal()">Cancel</button>
    </form>
  </div>
</div>
