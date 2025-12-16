<link rel="stylesheet"href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<script src="/patient_system_modern/assets/js/main.js"></script>
<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';

require_login();

$page_title = 'Edit Employee';
$active = 'Employees';

// Validate ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid Employee ID");
}

$id = intval($_GET['id']);

// Fetch patient
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$employee = $stmt->fetch();

if (!$employee) {
    die("Employee not found");
}


// Escape all values
$name = htmlspecialchars($employee['name']);
$email = htmlspecialchars($employee['email']);
$password = htmlspecialchars($employee['password']);
$role = htmlspecialchars($employee['role']);


$content = <<<HTML
<div class="card shadow-sm">
    <div class="card-body">
       <form method="post" enctype="multipart/form-data" action="/patient_system_modern/views/admin/employee_update.php">
        <input type="hidden" name="id" value="{$id}">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" value="{$name}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{$email}" required>
                </div>

                <!-- <div class="col-md-6">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" value="{$password}" required>
                </div> -->

                <!-- show password -->
               <div class="col-md-6">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password" name="password" id="password" class="form-control"  required>

                        <span class="input-group-text" id="togglePassword" style="cursor:pointer;">
                            <i class="fa-solid fa-eye" id="toggleIcon"></i>
                        </span>
                    </div>
                </div>
                

                <div class="col-md-6">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select" required>
                        <option value="employee">Employee</option>
                    </select>
                </div>
            </div>

            <div class="mt-3 text-end">
                <a href="/patient_system_modern/views/admin/employees.php" class="btn btn-outline-secondary">Cancel</a>
                <button class="btn btn-primary" type="submit">Update</button>
            </div>
        </form>
    </div>
</div>
HTML;

include __DIR__ . '/../layout/main.php';
