<?php
/**
 * All Visitors Page
 * Visitor Log System CMS
 */

require_once 'includes/bootstrap.php';

// Require login
requireLogin();

$pageTitle = 'All Visitors';

// Get filter parameters
$filters = getGetData(['search', 'date_from', 'date_to', 'status', 'host_person']);
$page = max(1, intval($_GET['page'] ?? 1));

// Get visitors with filters
$result = $visitor->getVisitors($page, RECORDS_PER_PAGE, $filters);
$visitors = $result['visitors'];
$totalPages = $result['total_pages'];
$totalRecords = $result['total'];

include 'templates/header.php';
?>

<!-- Search and Filter -->
<div class="search-filter-card">
    <form method="GET" class="row g-3">
        <div class="col-md-3">
            <label for="search" class="form-label">Search</label>
            <input type="text" 
                   class="form-control" 
                   id="search" 
                   name="search" 
                   value="<?php echo htmlspecialchars($filters['search'] ?? ''); ?>"
                   placeholder="Name, email, company...">
        </div>
        
        <div class="col-md-2">
            <label for="date_from" class="form-label">From Date</label>
            <input type="date" 
                   class="form-control" 
                   id="date_from" 
                   name="date_from" 
                   value="<?php echo htmlspecialchars($filters['date_from'] ?? ''); ?>">
        </div>
        
        <div class="col-md-2">
            <label for="date_to" class="form-label">To Date</label>
            <input type="date" 
                   class="form-control" 
                   id="date_to" 
                   name="date_to" 
                   value="<?php echo htmlspecialchars($filters['date_to'] ?? ''); ?>">
        </div>
        
        <div class="col-md-2">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" id="status" name="status">
                <option value="">All Status</option>
                <?php foreach (getVisitorStatusOptions() as $value => $label): ?>
                <option value="<?php echo $value; ?>" 
                        <?php echo ($filters['status'] ?? '') === $value ? 'selected' : ''; ?>>
                    <?php echo $label; ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="col-md-2">
            <label for="host_person" class="form-label">Host Person</label>
            <input type="text" 
                   class="form-control" 
                   id="host_person" 
                   name="host_person" 
                   value="<?php echo htmlspecialchars($filters['host_person'] ?? ''); ?>"
                   placeholder="Host name">
        </div>
        
        <div class="col-md-1 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </form>
    
    <div class="mt-3 d-flex justify-content-between align-items-center">
        <div>
            <span class="text-muted">
                Showing <?php echo count($visitors); ?> of <?php echo $totalRecords; ?> visitors
            </span>
        </div>
        <div>
            <a href="add_visitor.php" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-2"></i>Add New Visitor
            </a>
            <button onclick="exportTableToCSV('visitorsTable', 'visitors.csv')" class="btn btn-outline-success btn-sm">
                <i class="fas fa-download me-2"></i>Export CSV
            </button>
            <button onclick="printPage()" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-print me-2"></i>Print
            </button>
        </div>
    </div>
</div>

<!-- Visitors Table -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-users me-2"></i>Visitors List</h5>
    </div>
    <div class="card-body p-0">
        <?php if (empty($visitors)): ?>
            <div class="text-center py-5">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No visitors found</h5>
                <p class="text-muted">Try adjusting your search criteria or add a new visitor.</p>
                <a href="add_visitor.php" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add New Visitor
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0 sortable-table" id="visitorsTable">
                    <thead>
                        <tr>
                            <th data-sort="name" style="cursor: pointer;">
                                Visitor Name <i class="fas fa-sort text-muted"></i>
                            </th>
                            <th data-sort="company">Company</th>
                            <th data-sort="date" style="cursor: pointer;">
                                Visit Date <i class="fas fa-sort text-muted"></i>
                            </th>
                            <th data-sort="time">Time</th>
                            <th data-sort="host">Host Person</th>
                            <th data-sort="status">Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($visitors as $visitorRecord): ?>
                        <tr>
                            <td data-sort="name">
                                <div>
                                    <strong><?php echo htmlspecialchars($visitorRecord['visitor_name']); ?></strong>
                                    <?php if ($visitorRecord['visitor_email']): ?>
                                        <br><small class="text-muted">
                                            <i class="fas fa-envelope me-1"></i>
                                            <?php echo htmlspecialchars($visitorRecord['visitor_email']); ?>
                                        </small>
                                    <?php endif; ?>
                                    <?php if ($visitorRecord['visitor_phone']): ?>
                                        <br><small class="text-muted">
                                            <i class="fas fa-phone me-1"></i>
                                            <?php echo htmlspecialchars($visitorRecord['visitor_phone']); ?>
                                        </small>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td data-sort="company">
                                <?php echo htmlspecialchars($visitorRecord['visitor_company'] ?? 'N/A'); ?>
                            </td>
                            <td data-sort="date">
                                <?php echo Utils::formatDate($visitorRecord['visit_date']); ?>
                            </td>
                            <td data-sort="time">
                                <div>
                                    <strong>In:</strong> <?php echo Utils::formatTime($visitorRecord['visit_time']); ?>
                                    <?php if ($visitorRecord['checkout_time']): ?>
                                        <br><strong>Out:</strong> <?php echo Utils::formatTime($visitorRecord['checkout_time']); ?>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td data-sort="host">
                                <div>
                                    <?php echo htmlspecialchars($visitorRecord['host_person']); ?>
                                    <?php if ($visitorRecord['host_department']): ?>
                                        <br><small class="text-muted"><?php echo htmlspecialchars($visitorRecord['host_department']); ?></small>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td data-sort="status">
                                <?php echo getStatusBadge($visitorRecord['status']); ?>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="view_visitor.php?id=<?php echo $visitorRecord['id']; ?>" 
                                       class="btn btn-outline-primary" 
                                       title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="edit_visitor.php?id=<?php echo $visitorRecord['id']; ?>" 
                                       class="btn btn-outline-warning" 
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php if ($visitorRecord['status'] === 'checked_in'): ?>
                                    <a href="checkout_visitor.php?id=<?php echo $visitorRecord['id']; ?>" 
                                       class="btn btn-outline-success" 
                                       title="Check Out">
                                        <i class="fas fa-sign-out-alt"></i>
                                    </a>
                                    <?php endif; ?>
                                    <a href="delete_visitor.php?id=<?php echo $visitorRecord['id']; ?>" 
                                       class="btn btn-outline-danger" 
                                       title="Delete"
                                       data-confirm="Are you sure you want to delete this visitor record?">
                                        <i class="fas fa-trash"></i>
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

<!-- Pagination -->
<?php if ($totalPages > 1): ?>
<div class="mt-4">
    <?php echo Utils::generatePagination($page, $totalPages, 'visitors.php', $filters); ?>
</div>
<?php endif; ?>

<script>
// Auto-submit form on filter change
document.addEventListener('DOMContentLoaded', function() {
    const filterInputs = document.querySelectorAll('#search, #date_from, #date_to, #status, #host_person');
    
    filterInputs.forEach(input => {
        if (input.type === 'text') {
            let timeout;
            input.addEventListener('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    this.form.submit();
                }, 500);
            });
        } else {
            input.addEventListener('change', function() {
                this.form.submit();
            });
        }
    });
});

// Clear filters
function clearFilters() {
    window.location.href = 'visitors.php';
}

// Add clear filters button if any filters are active
<?php if (!empty(array_filter($filters))): ?>
document.addEventListener('DOMContentLoaded', function() {
    const filterCard = document.querySelector('.search-filter-card');
    const clearButton = document.createElement('button');
    clearButton.type = 'button';
    clearButton.className = 'btn btn-outline-secondary btn-sm ms-2';
    clearButton.innerHTML = '<i class="fas fa-times me-1"></i>Clear Filters';
    clearButton.onclick = clearFilters;
    
    const searchButton = filterCard.querySelector('button[type="submit"]');
    searchButton.parentNode.appendChild(clearButton);
});
<?php endif; ?>
</script>

<?php include 'templates/footer.php'; ?>

