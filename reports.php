<?php
/**
 * Reports Page
 * Visitor Log System CMS
 */

require_once 'includes/bootstrap.php';

// Require login
requireLogin();

$pageTitle = 'Reports & Analytics';

// Get visitor statistics
$stats = $visitor->getVisitorStats();

// Get monthly data for the current year
$monthlyData = [];
for ($i = 1; $i <= 12; $i++) {
    $month = str_pad($i, 2, '0', STR_PAD_LEFT);
    $yearMonth = date('Y') . '-' . $month;
    $count = $visitor->getVisitors(1, 1000, ['date_from' => $yearMonth . '-01', 'date_to' => $yearMonth . '-31'])['total'];
    $monthlyData[] = [
        'month' => date('M', mktime(0, 0, 0, $i, 1)),
        'count' => $count
    ];
}

include 'templates/header.php';
?>

<!-- Statistics Overview -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card">
            <div class="stats-number"><?php echo $stats['today']; ?></div>
            <p class="stats-label">Today's Visitors</p>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card success">
            <div class="stats-number"><?php echo $stats['this_month']; ?></div>
            <p class="stats-label">This Month</p>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card warning">
            <div class="stats-number"><?php echo $stats['checked_in']; ?></div>
            <p class="stats-label">Currently Checked In</p>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stats-card info">
            <div class="stats-number"><?php echo $stats['total']; ?></div>
            <p class="stats-label">Total Visitors</p>
        </div>
    </div>
</div>

<!-- Monthly Chart -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Monthly Visitor Trends (<?php echo date('Y'); ?>)</h5>
            </div>
            <div class="card-body">
                <canvas id="monthlyChart" width="400" height="100"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Quick Reports -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-calendar-week me-2"></i>This Week's Summary</h6>
            </div>
            <div class="card-body">
                <?php
                // Get this week's data
                $weekStart = date('Y-m-d', strtotime('monday this week'));
                $weekEnd = date('Y-m-d', strtotime('sunday this week'));
                $weekVisitors = $visitor->getVisitors(1, 1000, ['date_from' => $weekStart, 'date_to' => $weekEnd]);
                ?>
                <div class="row text-center">
                    <div class="col-6">
                        <h4 class="text-primary"><?php echo $weekVisitors['total']; ?></h4>
                        <small class="text-muted">Total Visitors</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-success"><?php echo round($weekVisitors['total'] / 7, 1); ?></h4>
                        <small class="text-muted">Daily Average</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-users me-2"></i>Top Host Persons</h6>
            </div>
            <div class="card-body">
                <?php
                // Get top host persons (simplified query)
                $topHosts = $db->fetchAll("
                    SELECT host_person, COUNT(*) as visitor_count 
                    FROM visitors 
                    WHERE visit_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                    GROUP BY host_person 
                    ORDER BY visitor_count DESC 
                    LIMIT 3
                ");
                ?>
                
                <?php if (!empty($topHosts)): ?>
                    <?php foreach ($topHosts as $host): ?>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span><?php echo htmlspecialchars($host['host_person']); ?></span>
                        <span class="badge bg-primary"><?php echo $host['visitor_count']; ?></span>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted mb-0">No data available</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Export Options -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-download me-2"></i>Export Reports</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="border rounded p-3 text-center">
                            <i class="fas fa-file-csv fa-2x text-success mb-2"></i>
                            <h6>All Visitors CSV</h6>
                            <p class="text-muted small">Export complete visitor database</p>
                            <a href="export.php?type=all" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-download me-1"></i>Download
                            </a>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="border rounded p-3 text-center">
                            <i class="fas fa-calendar-day fa-2x text-primary mb-2"></i>
                            <h6>Today's Report</h6>
                            <p class="text-muted small">Export today's visitor data</p>
                            <a href="export.php?type=today" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-download me-1"></i>Download
                            </a>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <div class="border rounded p-3 text-center">
                            <i class="fas fa-calendar-week fa-2x text-warning mb-2"></i>
                            <h6>Monthly Report</h6>
                            <p class="text-muted small">Export current month's data</p>
                            <a href="export.php?type=month" class="btn btn-outline-warning btn-sm">
                                <i class="fas fa-download me-1"></i>Download
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Monthly Chart
const ctx = document.getElementById('monthlyChart').getContext('2d');
const monthlyChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode(array_column($monthlyData, 'month')); ?>,
        datasets: [{
            label: 'Visitors',
            data: <?php echo json_encode(array_column($monthlyData, 'count')); ?>,
            borderColor: '#2563eb',
            backgroundColor: 'rgba(37, 99, 235, 0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(0, 0, 0, 0.1)'
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
});

// Set chart height
document.getElementById('monthlyChart').style.height = '300px';
</script>

<?php include 'templates/footer.php'; ?>

