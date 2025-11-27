<?php
/**
 * Checkout Visitor Page
 * Visitor Log System CMS
 */

require_once 'includes/bootstrap.php';

// Require login
requireLogin();

$pageTitle = 'Check Out Visitor';

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

// Check if visitor is already checked out
if ($visitorDetails['status'] !== 'checked_in') {
    Utils::setFlashMessage('error', 'Visitor is not currently checked in');
    Utils::redirect('view_visitor.php?id=' . $visitorId);
}

// Handle checkout form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (handleFormSubmission(['checkout_time'])) {
        $data = getPostData(['checkout_time', 'notes']);
        
        $result = $visitor->checkOutVisitor(
            $visitorId, 
            $data['checkout_time'], 
            getCurrentAdmin()['id'], 
            $data['notes']
        );
        
        if ($result['success']) {
            Utils::setFlashMessage('success', 'Visitor checked out successfully!');
            Utils::redirect('view_visitor.php?id=' . $visitorId);
        } else {
            Utils::setFlashMessage('error', $result['message']);
        }
    }
}

// Set default checkout time to current time
$defaultCheckoutTime = date('H:i');

include 'templates/header.php';
?>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-sign-out-alt me-2"></i>Check Out Visitor</h5>
            </div>
            <div class="card-body">
                <!-- Visitor Information Summary -->
                <div class="alert alert-info">
                    <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Visitor Information</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Name:</strong> <?php echo htmlspecialchars($visitorDetails['visitor_name']); ?><br>
                            <strong>Company:</strong> <?php echo htmlspecialchars($visitorDetails['visitor_company'] ?? 'N/A'); ?><br>
                            <strong>Host Person:</strong> <?php echo htmlspecialchars($visitorDetails['host_person']); ?>
                        </div>
                        <div class="col-md-6">
                            <strong>Visit Date:</strong> <?php echo Utils::formatDate($visitorDetails['visit_date']); ?><br>
                            <strong>Check-in Time:</strong> <?php echo Utils::formatTime($visitorDetails['visit_time']); ?><br>
                            <strong>Status:</strong> <?php echo getStatusBadge($visitorDetails['status']); ?>
                        </div>
                    </div>
                </div>
                
                <!-- Checkout Form -->
                <form method="POST" class="needs-validation" novalidate>
                    <input type="hidden" name="csrf_token" value="<?php echo getCSRFToken(); ?>">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="checkout_time" class="form-label">Check-out Time *</label>
                            <input type="time" 
                                   class="form-control" 
                                   id="checkout_time" 
                                   name="checkout_time" 
                                   value="<?php echo $_POST['checkout_time'] ?? $defaultCheckoutTime; ?>"
                                   required>
                            <div class="invalid-feedback">
                                Please select the check-out time.
                            </div>
                        </div>
                        
                        <div class="col-12 mb-3">
                            <label for="notes" class="form-label">Check-out Notes</label>
                            <textarea class="form-control" 
                                      id="notes" 
                                      name="notes" 
                                      rows="3" 
                                      placeholder="Any additional notes about the checkout (optional)"><?php echo htmlspecialchars($_POST['notes'] ?? ''); ?></textarea>
                            <div class="form-text">
                                You can add any relevant information about the visitor's checkout.
                            </div>
                        </div>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="d-flex justify-content-between">
                        <a href="view_visitor.php?id=<?php echo $visitorId; ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-sign-out-alt me-2"></i>Check Out Visitor
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Visit Duration Calculation -->
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="fas fa-clock me-2"></i>Visit Duration</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-4">
                        <div class="border rounded p-3">
                            <h5 class="text-primary mb-1"><?php echo Utils::formatTime($visitorDetails['visit_time']); ?></h5>
                            <small class="text-muted">Check-in Time</small>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex align-items-center justify-content-center">
                        <i class="fas fa-arrow-right fa-2x text-muted"></i>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded p-3">
                            <h5 class="text-success mb-1" id="currentTime"><?php echo date('H:i'); ?></h5>
                            <small class="text-muted">Current Time</small>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-3">
                    <div class="alert alert-light">
                        <strong>Estimated Duration:</strong> 
                        <span id="visitDuration">Calculating...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Update current time and calculate duration
function updateTimeAndDuration() {
    const now = new Date();
    const currentTimeElement = document.getElementById('currentTime');
    const durationElement = document.getElementById('visitDuration');
    
    // Update current time
    currentTimeElement.textContent = now.toTimeString().slice(0, 5);
    
    // Calculate duration
    const checkinTime = '<?php echo $visitorDetails['visit_time']; ?>';
    const checkinDate = new Date('<?php echo $visitorDetails['visit_date']; ?>T' + checkinTime);
    const duration = now - checkinDate;
    
    if (duration > 0) {
        const hours = Math.floor(duration / (1000 * 60 * 60));
        const minutes = Math.floor((duration % (1000 * 60 * 60)) / (1000 * 60));
        
        let durationText = '';
        if (hours > 0) {
            durationText += hours + ' hour' + (hours > 1 ? 's' : '') + ' ';
        }
        durationText += minutes + ' minute' + (minutes !== 1 ? 's' : '');
        
        durationElement.textContent = durationText;
    } else {
        durationElement.textContent = 'Visit hasn\'t started yet';
    }
}

// Update every second
setInterval(updateTimeAndDuration, 1000);
updateTimeAndDuration(); // Initial call

// Auto-focus on checkout time field
document.getElementById('checkout_time').focus();

// Set current time as default
document.addEventListener('DOMContentLoaded', function() {
    const checkoutTimeInput = document.getElementById('checkout_time');
    if (!checkoutTimeInput.value) {
        const now = new Date();
        checkoutTimeInput.value = now.toTimeString().slice(0, 5);
    }
});
</script>

<?php include 'templates/footer.php'; ?>

