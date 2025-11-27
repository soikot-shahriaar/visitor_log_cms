<?php
/**
 * User Profile Page
 * Visitor Log System CMS
 */

require_once 'includes/bootstrap.php';

// Require login
requireLogin();

$pageTitle = 'User Profile';

$currentAdmin = getCurrentAdmin();

// Handle password change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    if (handleFormSubmission(['current_password', 'new_password', 'confirm_password'])) {
        $data = getPostData(['current_password', 'new_password', 'confirm_password']);
        
        if ($data['new_password'] !== $data['confirm_password']) {
            Utils::setFlashMessage('error', 'New passwords do not match');
        } elseif (strlen($data['new_password']) < PASSWORD_MIN_LENGTH) {
            Utils::setFlashMessage('error', 'New password must be at least ' . PASSWORD_MIN_LENGTH . ' characters long');
        } else {
            $result = $admin->updatePassword(
                $currentAdmin['id'], 
                $data['current_password'], 
                $data['new_password']
            );
            
            if ($result['success']) {
                Utils::setFlashMessage('success', 'Password updated successfully!');
            } else {
                Utils::setFlashMessage('error', $result['message']);
            }
        }
    }
}

include 'templates/header.php';
?>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <!-- Profile Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-user me-2"></i>Profile Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center">
                        <div class="profile-avatar mb-3">
                            <i class="fas fa-user-circle fa-5x text-primary"></i>
                        </div>
                        <h6><?php echo htmlspecialchars($currentAdmin['name']); ?></h6>
                        <p class="text-muted">System Administrator</p>
                    </div>
                    <div class="col-md-9">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold" width="150">Full Name:</td>
                                <td><?php echo htmlspecialchars($currentAdmin['name']); ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Username:</td>
                                <td><?php echo htmlspecialchars($currentAdmin['username']); ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Email:</td>
                                <td><?php echo htmlspecialchars($currentAdmin['email']); ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">User ID:</td>
                                <td><?php echo $currentAdmin['id']; ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Last Login:</td>
                                <td><?php echo date('d/m/Y H:i:s'); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Change Password -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-key me-2"></i>Change Password</h5>
            </div>
            <div class="card-body">
                <form method="POST" class="needs-validation" novalidate>
                    <input type="hidden" name="csrf_token" value="<?php echo getCSRFToken(); ?>">
                    <input type="hidden" name="change_password" value="1">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="current_password" class="form-label">Current Password *</label>
                            <input type="password" 
                                   class="form-control" 
                                   id="current_password" 
                                   name="current_password" 
                                   placeholder="Enter current password"
                                   required>
                            <div class="invalid-feedback">
                                Please enter your current password.
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="new_password" class="form-label">New Password *</label>
                            <input type="password" 
                                   class="form-control" 
                                   id="new_password" 
                                   name="new_password" 
                                   placeholder="Enter new password"
                                   minlength="<?php echo PASSWORD_MIN_LENGTH; ?>"
                                   required>
                            <div class="invalid-feedback">
                                Password must be at least <?php echo PASSWORD_MIN_LENGTH; ?> characters long.
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="confirm_password" class="form-label">Confirm New Password *</label>
                            <input type="password" 
                                   class="form-control" 
                                   id="confirm_password" 
                                   name="confirm_password" 
                                   placeholder="Confirm new password"
                                   required>
                            <div class="invalid-feedback">
                                Please confirm your new password.
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Password Requirements</h6>
                        <ul class="mb-0">
                            <li>Minimum <?php echo PASSWORD_MIN_LENGTH; ?> characters long</li>
                            <li>Use a combination of letters, numbers, and symbols</li>
                            <li>Avoid using personal information</li>
                            <li>Don't reuse old passwords</li>
                        </ul>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Update Password
                    </button>
                </form>
            </div>
        </div>

        <!-- Activity Summary -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Your Activity Summary</h5>
            </div>
            <div class="card-body">
                <?php
                // Get admin's activity stats
                $adminStats = [
                    'visitors_added' => $db->getRowCount('visitors', 'created_by = ?', [$currentAdmin['id']]),
                    'today_added' => $db->getRowCount('visitors', 'created_by = ? AND visit_date = CURDATE()', [$currentAdmin['id']]),
                    'this_month_added' => $db->getRowCount('visitors', 'created_by = ? AND visit_date >= DATE_FORMAT(CURDATE(), "%Y-%m-01")', [$currentAdmin['id']])
                ];
                ?>
                
                <div class="row text-center">
                    <div class="col-md-4">
                        <h4 class="text-primary"><?php echo $adminStats['visitors_added']; ?></h4>
                        <small class="text-muted">Total Visitors Added</small>
                    </div>
                    <div class="col-md-4">
                        <h4 class="text-success"><?php echo $adminStats['today_added']; ?></h4>
                        <small class="text-muted">Added Today</small>
                    </div>
                    <div class="col-md-4">
                        <h4 class="text-warning"><?php echo $adminStats['this_month_added']; ?></h4>
                        <small class="text-muted">Added This Month</small>
                    </div>
                </div>
                
                <hr>
                
                <div class="row">
                    <div class="col-md-6">
                        <h6>Account Information</h6>
                        <ul class="list-unstyled">
                            <li><strong>Account Status:</strong> <span class="badge bg-success">Active</span></li>
                            <li><strong>Role:</strong> Administrator</li>
                            <li><strong>Permissions:</strong> Full Access</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>Security Status</h6>
                        <ul class="list-unstyled">
                            <li><strong>Password:</strong> <span class="text-success"><i class="fas fa-check"></i> Secure</span></li>
                            <li><strong>Session:</strong> <span class="text-success"><i class="fas fa-check"></i> Active</span></li>
                            <li><strong>Last Activity:</strong> Now</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Password confirmation validation
document.getElementById('confirm_password').addEventListener('input', function() {
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = this.value;
    
    if (newPassword !== confirmPassword) {
        this.setCustomValidity('Passwords do not match');
    } else {
        this.setCustomValidity('');
    }
});

// Password strength indicator
document.getElementById('new_password').addEventListener('input', function() {
    const password = this.value;
    const strength = calculatePasswordStrength(password);
    
    // You can add a password strength indicator here
    console.log('Password strength:', strength);
});

function calculatePasswordStrength(password) {
    let strength = 0;
    
    if (password.length >= 8) strength++;
    if (/[a-z]/.test(password)) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^A-Za-z0-9]/.test(password)) strength++;
    
    return strength;
}
</script>

<?php include 'templates/footer.php'; ?>

