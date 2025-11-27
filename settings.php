<?php
/**
 * Settings Page
 * Visitor Log System CMS
 */

require_once 'includes/bootstrap.php';

// Require login
requireLogin();

$pageTitle = 'System Settings';

include 'templates/header.php';
?>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <!-- System Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>System Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold">Application Name:</td>
                                <td><?php echo APP_NAME; ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Version:</td>
                                <td><?php echo APP_VERSION; ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">PHP Version:</td>
                                <td><?php echo phpversion(); ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Database:</td>
                                <td>MySQL <?php echo $db->fetch("SELECT VERSION() as version")['version']; ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold">Server Time:</td>
                                <td><?php echo date('Y-m-d H:i:s'); ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Session Timeout:</td>
                                <td><?php echo SESSION_TIMEOUT / 60; ?> minutes</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Max File Size:</td>
                                <td><?php echo round(MAX_FILE_SIZE / 1024 / 1024, 1); ?> MB</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Records Per Page:</td>
                                <td><?php echo RECORDS_PER_PAGE; ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Database Statistics -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-database me-2"></i>Database Statistics</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <h4 class="text-primary"><?php echo $db->getRowCount('visitors'); ?></h4>
                        <small class="text-muted">Total Visitors</small>
                    </div>
                    <div class="col-md-3">
                        <h4 class="text-success"><?php echo $db->getRowCount('admins'); ?></h4>
                        <small class="text-muted">Admin Users</small>
                    </div>
                    <div class="col-md-3">
                        <h4 class="text-warning"><?php echo $db->getRowCount('visitor_logs'); ?></h4>
                        <small class="text-muted">Activity Logs</small>
                    </div>
                    <div class="col-md-3">
                        <h4 class="text-info"><?php echo round(filesize(__DIR__ . '/visitor_log_system.sql') / 1024, 1); ?> KB</h4>
                        <small class="text-muted">Schema Size</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Security Settings -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Security Settings</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Security Features Enabled</h6>
                    <ul class="mb-0">
                        <li>Password hashing with bcrypt</li>
                        <li>CSRF protection on all forms</li>
                        <li>Input sanitization and validation</li>
                        <li>Session timeout management</li>
                        <li>SQL injection prevention</li>
                        <li>XSS protection</li>
                    </ul>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <h6>Current Security Settings:</h6>
                        <ul class="list-unstyled">
                            <li><strong>Min Password Length:</strong> <?php echo PASSWORD_MIN_LENGTH; ?> characters</li>
                            <li><strong>Session Timeout:</strong> <?php echo SESSION_TIMEOUT / 60; ?> minutes</li>
                            <li><strong>CSRF Token:</strong> Enabled</li>
                            <li><strong>Secure Headers:</strong> Enabled</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>Recommendations:</h6>
                        <ul class="list-unstyled text-muted">
                            <li>• Enable HTTPS in production</li>
                            <li>• Regular security updates</li>
                            <li>• Strong admin passwords</li>
                            <li>• Regular database backups</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Maintenance -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-tools me-2"></i>System Maintenance</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="border rounded p-3 text-center">
                            <i class="fas fa-download fa-2x text-primary mb-2"></i>
                            <h6>Backup Database</h6>
                            <p class="text-muted small">Create a backup of the database</p>
                            <button class="btn btn-outline-primary btn-sm" onclick="alert('Feature coming soon!')">
                                <i class="fas fa-download me-1"></i>Create Backup
                            </button>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="border rounded p-3 text-center">
                            <i class="fas fa-broom fa-2x text-warning mb-2"></i>
                            <h6>Clear Logs</h6>
                            <p class="text-muted small">Clear old activity logs</p>
                            <button class="btn btn-outline-warning btn-sm" onclick="alert('Feature coming soon!')">
                                <i class="fas fa-broom me-1"></i>Clear Logs
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-warning">
                    <h6 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Important Notes</h6>
                    <ul class="mb-0">
                        <li>Always backup your database before making changes</li>
                        <li>Test any updates in a staging environment first</li>
                        <li>Keep your PHP and MySQL versions updated</li>
                        <li>Monitor system logs regularly</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'templates/footer.php'; ?>

