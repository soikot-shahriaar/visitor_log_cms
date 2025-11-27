<?php
/**
 * View Visitor Details Page
 * Visitor Log System CMS
 */

require_once 'includes/bootstrap.php';

// Require login
requireLogin();

$pageTitle = 'Visitor Details';

// Get visitor ID
$visitorId = intval($_GET['id'] ?? 0);

if (!$visitorId) {
    Utils::setFlashMessage('error', 'Invalid visitor ID');
    Utils::redirect('visitors.php');
}

// Get visitor details
$visitorDetails = $visitor->getVisitorById($visitorId);

if (!$visitorDetails) {
    Utils::setFlashMessage('error', 'Visitor not found');
    Utils::redirect('visitors.php');
}

// Get visitor logs
$visitorLogs = $visitor->getVisitorLogs($visitorId);

include 'templates/header.php';
?>

<div class="row">
    <div class="col-lg-8">
        <!-- Visitor Information -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-user me-2"></i>Visitor Information</h5>
                <div>
                    <a href="edit_visitor.php?id=<?php echo $visitorDetails['id']; ?>" class="btn btn-outline-warning btn-sm">
                        <i class="fas fa-edit me-2"></i>Edit
                    </a>
                    <?php if ($visitorDetails['status'] === 'checked_in'): ?>
                    <a href="checkout_visitor.php?id=<?php echo $visitorDetails['id']; ?>" class="btn btn-outline-success btn-sm">
                        <i class="fas fa-sign-out-alt me-2"></i>Check Out
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold">Full Name:</td>
                                <td><?php echo htmlspecialchars($visitorDetails['visitor_name']); ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Email:</td>
                                <td>
                                    <?php if ($visitorDetails['visitor_email']): ?>
                                        <a href="mailto:<?php echo htmlspecialchars($visitorDetails['visitor_email']); ?>">
                                            <?php echo htmlspecialchars($visitorDetails['visitor_email']); ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">Not provided</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Phone:</td>
                                <td>
                                    <?php if ($visitorDetails['visitor_phone']): ?>
                                        <a href="tel:<?php echo htmlspecialchars($visitorDetails['visitor_phone']); ?>">
                                            <?php echo htmlspecialchars($visitorDetails['visitor_phone']); ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">Not provided</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Company:</td>
                                <td><?php echo htmlspecialchars($visitorDetails['visitor_company'] ?? 'Not provided'); ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Status:</td>
                                <td><?php echo getStatusBadge($visitorDetails['status']); ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold">Visit Date:</td>
                                <td><?php echo Utils::formatDate($visitorDetails['visit_date']); ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Check-in Time:</td>
                                <td><?php echo Utils::formatTime($visitorDetails['visit_time']); ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Check-out Time:</td>
                                <td>
                                    <?php if ($visitorDetails['checkout_time']): ?>
                                        <?php echo Utils::formatTime($visitorDetails['checkout_time']); ?>
                                    <?php else: ?>
                                        <span class="text-muted">Not checked out</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Host Person:</td>
                                <td><?php echo htmlspecialchars($visitorDetails['host_person']); ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Host Department:</td>
                                <td><?php echo htmlspecialchars($visitorDetails['host_department'] ?? 'Not specified'); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="fw-bold">Purpose of Visit:</h6>
                        <p class="mb-3"><?php echo nl2br(htmlspecialchars($visitorDetails['purpose_of_visit'])); ?></p>
                        
                        <?php if ($visitorDetails['notes']): ?>
                        <h6 class="fw-bold">Additional Notes:</h6>
                        <p class="mb-3"><?php echo nl2br(htmlspecialchars($visitorDetails['notes'])); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Additional Information -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Additional Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold">ID Document Type:</td>
                                <td><?php echo getIdDocumentTypes()[$visitorDetails['id_document_type']] ?? 'Not specified'; ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">ID Document Number:</td>
                                <td><?php echo htmlspecialchars($visitorDetails['id_document_number'] ?? 'Not provided'); ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="fw-bold">Vehicle Number:</td>
                                <td><?php echo htmlspecialchars($visitorDetails['vehicle_number'] ?? 'Not provided'); ?></td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Created By:</td>
                                <td><?php echo htmlspecialchars($visitorDetails['created_by_name'] ?? 'Unknown'); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Activity Log -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-history me-2"></i>Activity Log</h5>
            </div>
            <div class="card-body">
                <?php if (empty($visitorLogs)): ?>
                    <div class="text-center py-3">
                        <i class="fas fa-history fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">No activity recorded</p>
                    </div>
                <?php else: ?>
                    <div class="timeline">
                        <?php foreach ($visitorLogs as $log): ?>
                        <div class="timeline-item mb-3">
                            <div class="timeline-marker">
                                <i class="fas fa-<?php echo $log['action'] === 'check_in' ? 'sign-in-alt' : ($log['action'] === 'check_out' ? 'sign-out-alt' : 'edit'); ?> 
                                   text-<?php echo $log['action'] === 'check_in' ? 'success' : ($log['action'] === 'check_out' ? 'warning' : 'info'); ?>"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-title">
                                    <?php 
                                    $actionLabels = [
                                        'check_in' => 'Checked In',
                                        'check_out' => 'Checked Out',
                                        'update' => 'Information Updated',
                                        'cancel' => 'Cancelled'
                                    ];
                                    echo $actionLabels[$log['action']] ?? ucfirst($log['action']);
                                    ?>
                                </div>
                                <div class="timeline-meta text-muted small">
                                    <?php echo Utils::formatDateTime($log['action_time']); ?>
                                    <?php if ($log['performed_by_name']): ?>
                                        by <?php echo htmlspecialchars($log['performed_by_name']); ?>
                                    <?php endif; ?>
                                </div>
                                <?php if ($log['notes']): ?>
                                <div class="timeline-notes small">
                                    <?php echo htmlspecialchars($log['notes']); ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="edit_visitor.php?id=<?php echo $visitorDetails['id']; ?>" class="btn btn-outline-warning">
                        <i class="fas fa-edit me-2"></i>Edit Information
                    </a>
                    
                    <?php if ($visitorDetails['status'] === 'checked_in'): ?>
                    <a href="checkout_visitor.php?id=<?php echo $visitorDetails['id']; ?>" class="btn btn-outline-success">
                        <i class="fas fa-sign-out-alt me-2"></i>Check Out Visitor
                    </a>
                    <?php endif; ?>
                    
                    <button onclick="printPage()" class="btn btn-outline-secondary">
                        <i class="fas fa-print me-2"></i>Print Details
                    </button>
                    
                    <a href="delete_visitor.php?id=<?php echo $visitorDetails['id']; ?>" 
                       class="btn btn-outline-danger"
                       data-confirm="Are you sure you want to delete this visitor record? This action cannot be undone.">
                        <i class="fas fa-trash me-2"></i>Delete Record
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Navigation -->
<div class="row mt-4">
    <div class="col-12">
        <div class="d-flex justify-content-between">
            <a href="visitors.php" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Visitors List
            </a>
            <a href="add_visitor.php" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Add New Visitor
            </a>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
}

.timeline-item {
    position: relative;
    padding-left: 2rem;
}

.timeline-marker {
    position: absolute;
    left: 0;
    top: 0;
    width: 1.5rem;
    height: 1.5rem;
    background: white;
    border: 2px solid #e2e8f0;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: 0.75rem;
    top: 1.5rem;
    bottom: -1rem;
    width: 2px;
    background: #e2e8f0;
}

.timeline-title {
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.timeline-notes {
    margin-top: 0.25rem;
    padding: 0.5rem;
    background: #f8fafc;
    border-radius: 4px;
}
</style>

<?php include 'templates/footer.php'; ?>

