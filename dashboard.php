<?php
/**
 * Dashboard Page
 * Visitor Log System CMS
 */

require_once 'includes/bootstrap.php';

// Require login
requireLogin();

$pageTitle = 'Dashboard';

// Get visitor statistics
$stats = $visitor->getVisitorStats();

// Get today's visitors
$todayVisitors = $visitor->getTodayVisitors();

// Get recent visitors (last 10)
$recentVisitors = $visitor->getVisitors(1, 10);

include 'templates/header.php';
?>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card">
            <div class="stats-number"><?php echo $stats['today']; ?></div>
            <p class="stats-label">Today's Visitors</p>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card success">
            <div class="stats-number"><?php echo $stats['checked_in']; ?></div>
            <p class="stats-label">Currently Checked In</p>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card warning">
            <div class="stats-number"><?php echo $stats['this_month']; ?></div>
            <p class="stats-label">This Month</p>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card info">
            <div class="stats-number"><?php echo $stats['total']; ?></div>
            <p class="stats-label">Total Visitors</p>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="add_visitor.php" class="btn btn-primary w-100">
                            <i class="fas fa-user-plus me-2"></i>Add New Visitor
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="today_visitors.php" class="btn btn-success w-100">
                            <i class="fas fa-calendar-day me-2"></i>Today's Visitors
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="visitors.php" class="btn btn-info w-100">
                            <i class="fas fa-users me-2"></i>All Visitors
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-3">
                        <a href="reports.php" class="btn btn-warning w-100">
                            <i class="fas fa-chart-bar me-2"></i>Reports
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Today's Visitors -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-calendar-day me-2"></i>Today's Visitors</h5>
                <a href="today_visitors.php" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                <?php if (empty($todayVisitors)): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No visitors today yet.</p>
                        <a href="add_visitor.php" class="btn btn-primary">Add First Visitor</a>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Visitor Name</th>
                                    <th>Company</th>
                                    <th>Host Person</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice($todayVisitors, 0, 5) as $todayVisitor): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars($todayVisitor['visitor_name']); ?></strong>
                                        <?php if ($todayVisitor['visitor_email']): ?>
                                            <br><small class="text-muted"><?php echo htmlspecialchars($todayVisitor['visitor_email']); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($todayVisitor['visitor_company'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($todayVisitor['host_person']); ?></td>
                                    <td>
                                        <?php echo Utils::formatTime($todayVisitor['visit_time']); ?>
                                        <?php if ($todayVisitor['checkout_time']): ?>
                                            <br><small class="text-muted">Out: <?php echo Utils::formatTime($todayVisitor['checkout_time']); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo getStatusBadge($todayVisitor['status']); ?></td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="view_visitor.php?id=<?php echo $todayVisitor['id']; ?>" 
                                               class="btn btn-outline-primary" 
                                               title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <?php if ($todayVisitor['status'] === 'checked_in'): ?>
                                            <a href="checkout_visitor.php?id=<?php echo $todayVisitor['id']; ?>" 
                                               class="btn btn-outline-success" 
                                               title="Check Out">
                                                <i class="fas fa-sign-out-alt"></i>
                                            </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <?php if (count($todayVisitors) > 5): ?>
                        <div class="text-center mt-3">
                            <a href="today_visitors.php" class="btn btn-outline-primary">
                                View All <?php echo count($todayVisitors); ?> Visitors Today
                            </a>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Recent Activity -->
    <div class="col-lg-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Recent Activity</h5>
            </div>
            <div class="card-body">
                <?php if (empty($recentVisitors['visitors'])): ?>
                    <div class="text-center py-4">
                        <i class="fas fa-history fa-2x text-muted mb-3"></i>
                        <p class="text-muted">No recent activity.</p>
                    </div>
                <?php else: ?>
                    <div class="activity-list">
                        <?php foreach (array_slice($recentVisitors['visitors'], 0, 8) as $recentVisitor): ?>
                        <div class="activity-item d-flex align-items-center mb-3">
                            <div class="activity-icon me-3">
                                <i class="fas fa-user-plus text-primary"></i>
                            </div>
                            <div class="activity-content flex-grow-1">
                                <div class="activity-title">
                                    <strong><?php echo htmlspecialchars($recentVisitor['visitor_name']); ?></strong>
                                </div>
                                <div class="activity-meta text-muted small">
                                    <?php echo Utils::formatDate($recentVisitor['visit_date']); ?> at 
                                    <?php echo Utils::formatTime($recentVisitor['visit_time']); ?>
                                </div>
                            </div>
                            <div class="activity-status">
                                <?php echo getStatusBadge($recentVisitor['status']); ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="text-center mt-3">
                        <a href="visitors.php" class="btn btn-outline-primary btn-sm">View All Visitors</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- System Information -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>System Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <strong>System Version:</strong><br>
                        <span class="text-muted"><?php echo APP_VERSION; ?></span>
                    </div>
                    <div class="col-md-3">
                        <strong>Current User:</strong><br>
                        <span class="text-muted"><?php echo getCurrentAdmin()['name']; ?></span>
                    </div>
                    <div class="col-md-3">
                        <strong>Last Login:</strong><br>
                        <span class="text-muted"><?php echo date('d/m/Y H:i'); ?></span>
                    </div>
                    <div class="col-md-3">
                        <strong>Server Time:</strong><br>
                        <span class="text-muted" id="serverTime"><?php echo date('d/m/Y H:i:s'); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Update server time every second
setInterval(function() {
    const now = new Date();
    document.getElementById('serverTime').textContent = now.toLocaleString('en-GB');
}, 1000);
</script>

<?php include 'templates/footer.php'; ?>

