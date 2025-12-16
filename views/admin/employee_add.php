<!-- show password -->
<link rel="stylesheet"href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<script src="/patient_system_modern/assets/js/main.js"></script>
<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../helpers.php';

require_login('admin');

$page_title = 'Add Employee';
$active = 'employees';

$content = <<<HTML
<div class="card shadow-sm">
    <div class="card-body">
        <form method="post" action="/patient_system_modern/controllers/save_employee.php">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <!-- <div class="col-md-6">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div> -->
                <!-- show password -->
               <div class="col-md-6">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password" name="password" id="password" class="form-control" required>

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
                <button class="btn btn-primary" type="submit">Save</button>
            </div>
        </form>
    </div>
</div>
HTML;

include __DIR__ . '/../layout/main.php';
