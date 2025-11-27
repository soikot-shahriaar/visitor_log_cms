<?php
/**
 * Today's Visitors Page
 * Visitor Log System CMS
 */

require_once 'includes/bootstrap.php';

// Require login
requireLogin();

$pageTitle = "Today's Visitors";

// Get today's visitors
$todayVisitors = $visitor->getTodayVisitors();

// Get today's statistics
$todayStats = [
    'total' => count($todayVisitors),
    'checked_in' => count(array_filter($todayVisitors, function($v) { return $v['status'] === 'checked_in'; })),
    'checked_out' => count(array_filter($todayVisitors, function($v) { return $v['status'] === 'checked_out'; })),
    'cancelled' => count(array_filter($todayVisitors, function($v) { return $v['status'] === 'cancelled'; }))
];

include 'templates/header.php';
?>

<!-- Today's Statistics -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card">
            <div class="stats-number"><?php echo $todayStats['total']; ?></div>
            <p class="stats-label">Total Visitors Today</p>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card success">
            <div class="stats-number"><?php echo $todayStats['checked_in']; ?></div>
            <p class="stats-label">Currently Checked In</p>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card warning">
            <div class="stats-number"><?php echo $todayStats['checked_out']; ?></div>
            <p class="stats-label">Checked Out</p>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card info">
            <div class="stats-number"><?php echo date('d/m/Y'); ?></div>
            <p class="stats-label">Today's Date</p>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="mb-1">Quick Actions</h6>
                        <small class="text-muted">Manage today's visitors efficiently</small>
                    </div>
                    <div>
                        <a href="add_visitor.php" class="btn btn-primary me-2">
                            <i class="fas fa-user-plus me-2"></i>Add New Visitor
                        </a>
                        <button onclick="exportTableToCSV('todayVisitorsTable', 'today_visitors.csv')" class="btn btn-outline-success me-2">
                            <i class="fas fa-download me-2"></i>Export CSV
                        </button>
                        <button onclick="printPage()" class="btn btn-outline-secondary">
                            <i class="fas fa-print me-2"></i>Print
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Today's Visitors Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="fas fa-calendar-day me-2"></i>
            Visitors for <?php echo date('l, F j, Y'); ?>
        </h5>
        <div class="auto-refresh">
            <small class="text-muted">
                <i class="fas fa-sync-alt me-1"></i>Auto-refreshes every 5 minutes
            </small>
        </div>
    </div>
    <div class="card-body p-0">
        <?php if (empty($todayVisitors)): ?>
            <div class="text-center py-5">
                <i class="fas fa-calendar-day fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No visitors today yet</h5>
                <p class="text-muted">Start by adding the first visitor for today.</p>
                <a href="add_visitor.php" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add First Visitor
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="todayVisitorsTable">
                    <thead>
                        <tr>
                            <th>Visitor Information</th>
                            <th>Company</th>
                            <th>Time</th>
                            <th>Host Person</th>
                            <th>Purpose</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($todayVisitors as $todayVisitor): ?>
                        <tr class="<?php echo $todayVisitor['status'] === 'checked_in' ? 'table-success' : ''; ?>">
                            <td>
                                <div>
                                    <strong><?php echo htmlspecialchars($todayVisitor['visitor_name']); ?></strong>
                                    <?php if ($todayVisitor['visitor_email']): ?>
                                        <br><small class="text-muted">
                                            <i class="fas fa-envelope me-1"></i>
                                            <?php echo htmlspecialchars($todayVisitor['visitor_email']); ?>
                                        </small>
                                    <?php endif; ?>
                                    <?php if ($todayVisitor['visitor_phone']): ?>
                                        <br><small class="text-muted">
                                            <i class="fas fa-phone me-1"></i>
                                            <?php echo htmlspecialchars($todayVisitor['visitor_phone']); ?>
                                        </small>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($todayVisitor['visitor_company'] ?? 'N/A'); ?>
                            </td>
                            <td>
                                <div>
                                    <strong class="text-success">In:</strong> <?php echo Utils::formatTime($todayVisitor['visit_time']); ?>
                                    <?php if ($todayVisitor['checkout_time']): ?>
                                        <br><strong class="text-warning">Out:</strong> <?php echo Utils::formatTime($todayVisitor['checkout_time']); ?>
                                        <?php
                                        // Calculate duration
                                        $checkin = new DateTime($todayVisitor['visit_date'] . ' ' . $todayVisitor['visit_time']);
                                        $checkout = new DateTime($todayVisitor['visit_date'] . ' ' . $todayVisitor['checkout_time']);
                                        $duration = $checkin->diff($checkout);
                                        ?>
                                        <br><small class="text-muted">
                                            Duration: <?php echo $duration->format('%h:%I'); ?>
                                        </small>
                                    <?php else: ?>
                                        <?php
                                        // Calculate current duration for checked-in visitors
                                        $checkin = new DateTime($todayVisitor['visit_date'] . ' ' . $todayVisitor['visit_time']);
                                        $now = new DateTime();
                                        $duration = $checkin->diff($now);
                                        ?>
                                        <br><small class="text-info">
                                            Duration: <?php echo $duration->format('%h:%I'); ?>
                                        </small>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <?php echo htmlspecialchars($todayVisitor['host_person']); ?>
                                    <?php if ($todayVisitor['host_department']): ?>
                                        <br><small class="text-muted"><?php echo htmlspecialchars($todayVisitor['host_department']); ?></small>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <span title="<?php echo htmlspecialchars($todayVisitor['purpose_of_visit']); ?>">
                                    <?php echo truncateText($todayVisitor['purpose_of_visit'], 40); ?>
                                </span>
                            </td>
                            <td>
                                <?php echo getStatusBadge($todayVisitor['status']); ?>
                            </td>
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
                                    
                                    <a href="edit_visitor.php?id=<?php echo $todayVisitor['id']; ?>" 
                                       class="btn btn-outline-warning" 
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Summary Information -->
<?php if (!empty($todayVisitors)): ?>
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Today's Summary</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <h4 class="text-success"><?php echo $todayStats['checked_in']; ?></h4>
                        <small class="text-muted">Still Here</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-warning"><?php echo $todayStats['checked_out']; ?></h4>
                        <small class="text-muted">Departed</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-clock me-2"></i>Peak Hours</h6>
            </div>
            <div class="card-body">
                <?php
                // Calculate peak hours
                $hourCounts = [];
                foreach ($todayVisitors as $v) {
                    $hour = date('H', strtotime($v['visit_time']));
                    $hourCounts[$hour] = ($hourCounts[$hour] ?? 0) + 1;
                }
                arsort($hourCounts);
                $peakHours = array_slice($hourCounts, 0, 2, true);
                ?>
                
                <?php if (!empty($peakHours)): ?>
                    <?php foreach ($peakHours as $hour => $count): ?>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span><?php echo sprintf('%02d:00 - %02d:59', $hour, $hour); ?></span>
                        <span class="badge bg-primary"><?php echo $count; ?> visitor<?php echo $count > 1 ? 's' : ''; ?></span>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted mb-0">No peak hours data available</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
// Auto-refresh page every 5 minutes
setTimeout(function() {
    location.reload();
}, 300000);

// Update current time display
function updateCurrentTime() {
    const now = new Date();
    const timeElements = document.querySelectorAll('.current-time');
    timeElements.forEach(element => {
        element.textContent = now.toLocaleTimeString();
    });
}

setInterval(updateCurrentTime, 1000);

// Real-time duration updates for checked-in visitors
function updateDurations() {
    const durationElements = document.querySelectorAll('.duration-live');
    durationElements.forEach(element => {
        const checkinTime = element.dataset.checkinTime;
        const checkinDate = new Date(checkinTime);
        const now = new Date();
        const duration = now - checkinDate;
        
        const hours = Math.floor(duration / (1000 * 60 * 60));
        const minutes = Math.floor((duration % (1000 * 60 * 60)) / (1000 * 60));
        
        element.textContent = `Duration: ${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`;
    });
}

setInterval(updateDurations, 60000); // Update every minute
</script>

<?php include 'templates/footer.php'; ?>

