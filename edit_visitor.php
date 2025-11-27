<?php
/**
 * Edit Visitor Page
 * Visitor Log System CMS
 */

require_once 'includes/bootstrap.php';

// Require login
requireLogin();

$pageTitle = 'Edit Visitor';

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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requiredFields = ['visitor_name', 'visit_date', 'visit_time', 'purpose_of_visit', 'host_person'];
    
    if (handleFormSubmission($requiredFields)) {
        $data = getPostData([
            'visitor_name', 'visitor_email', 'visitor_phone', 'visitor_company',
            'visit_date', 'visit_time', 'purpose_of_visit', 'host_person', 
            'host_department', 'id_document_type', 'id_document_number', 
            'vehicle_number', 'notes'
        ]);
        
        // Validate email if provided
        if (!empty($data['visitor_email']) && !Utils::validateEmail($data['visitor_email'])) {
            Utils::setFlashMessage('error', 'Please enter a valid email address');
        } else {
            $result = $visitor->updateVisitor($visitorId, $data, getCurrentAdmin()['id']);
            
            if ($result['success']) {
                Utils::setFlashMessage('success', 'Visitor information updated successfully!');
                Utils::redirect('view_visitor.php?id=' . $visitorId);
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
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Visitor Information</h5>
            </div>
            <div class="card-body">
                <form method="POST" class="needs-validation" novalidate>
                    <input type="hidden" name="csrf_token" value="<?php echo getCSRFToken(); ?>">
                    
                    <!-- Visitor Information -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3"><i class="fas fa-user me-2"></i>Visitor Information</h6>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="visitor_name" class="form-label">Full Name *</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="visitor_name" 
                                   name="visitor_name" 
                                   value="<?php echo htmlspecialchars($_POST['visitor_name'] ?? $visitorDetails['visitor_name']); ?>"
                                   placeholder="Enter visitor's full name"
                                   required>
                            <div class="invalid-feedback">
                                Please enter the visitor's full name.
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="visitor_email" class="form-label">Email Address</label>
                            <input type="email" 
                                   class="form-control" 
                                   id="visitor_email" 
                                   name="visitor_email" 
                                   value="<?php echo htmlspecialchars($_POST['visitor_email'] ?? $visitorDetails['visitor_email']); ?>"
                                   placeholder="Enter email address">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="visitor_phone" class="form-label">Phone Number</label>
                            <input type="tel" 
                                   class="form-control" 
                                   id="visitor_phone" 
                                   name="visitor_phone" 
                                   value="<?php echo htmlspecialchars($_POST['visitor_phone'] ?? $visitorDetails['visitor_phone']); ?>"
                                   placeholder="Enter phone number">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="visitor_company" class="form-label">Company/Organization</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="visitor_company" 
                                   name="visitor_company" 
                                   value="<?php echo htmlspecialchars($_POST['visitor_company'] ?? $visitorDetails['visitor_company']); ?>"
                                   placeholder="Enter company or organization">
                        </div>
                    </div>
                    
                    <!-- Visit Information -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3"><i class="fas fa-calendar me-2"></i>Visit Information</h6>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="visit_date" class="form-label">Visit Date *</label>
                            <input type="date" 
                                   class="form-control" 
                                   id="visit_date" 
                                   name="visit_date" 
                                   value="<?php echo $_POST['visit_date'] ?? $visitorDetails['visit_date']; ?>"
                                   required>
                            <div class="invalid-feedback">
                                Please select the visit date.
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="visit_time" class="form-label">Visit Time *</label>
                            <input type="time" 
                                   class="form-control" 
                                   id="visit_time" 
                                   name="visit_time" 
                                   value="<?php echo $_POST['visit_time'] ?? $visitorDetails['visit_time']; ?>"
                                   required>
                            <div class="invalid-feedback">
                                Please select the visit time.
                            </div>
                        </div>
                        
                        <div class="col-12 mb-3">
                            <label for="purpose_of_visit" class="form-label">Purpose of Visit *</label>
                            <textarea class="form-control" 
                                      id="purpose_of_visit" 
                                      name="purpose_of_visit" 
                                      rows="3" 
                                      placeholder="Describe the purpose of the visit"
                                      required><?php echo htmlspecialchars($_POST['purpose_of_visit'] ?? $visitorDetails['purpose_of_visit']); ?></textarea>
                            <div class="invalid-feedback">
                                Please describe the purpose of the visit.
                            </div>
                        </div>
                    </div>
                    
                    <!-- Host Information -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3"><i class="fas fa-user-tie me-2"></i>Host Information</h6>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="host_person" class="form-label">Host Person *</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="host_person" 
                                   name="host_person" 
                                   value="<?php echo htmlspecialchars($_POST['host_person'] ?? $visitorDetails['host_person']); ?>"
                                   placeholder="Enter host person's name"
                                   required>
                            <div class="invalid-feedback">
                                Please enter the host person's name.
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="host_department" class="form-label">Host Department</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="host_department" 
                                   name="host_department" 
                                   value="<?php echo htmlspecialchars($_POST['host_department'] ?? $visitorDetails['host_department']); ?>"
                                   placeholder="Enter host department">
                        </div>
                    </div>
                    
                    <!-- Additional Information -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h6 class="text-primary mb-3"><i class="fas fa-info-circle me-2"></i>Additional Information</h6>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="id_document_type" class="form-label">ID Document Type</label>
                            <select class="form-select" id="id_document_type" name="id_document_type">
                                <?php foreach (getIdDocumentTypes() as $value => $label): ?>
                                <option value="<?php echo $value; ?>" 
                                        <?php echo ($_POST['id_document_type'] ?? $visitorDetails['id_document_type']) === $value ? 'selected' : ''; ?>>
                                    <?php echo $label; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="id_document_number" class="form-label">ID Document Number</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="id_document_number" 
                                   name="id_document_number" 
                                   value="<?php echo htmlspecialchars($_POST['id_document_number'] ?? $visitorDetails['id_document_number']); ?>"
                                   placeholder="Enter ID document number">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="vehicle_number" class="form-label">Vehicle Number</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="vehicle_number" 
                                   name="vehicle_number" 
                                   value="<?php echo htmlspecialchars($_POST['vehicle_number'] ?? $visitorDetails['vehicle_number']); ?>"
                                   placeholder="Enter vehicle number (if applicable)">
                        </div>
                        
                        <div class="col-12 mb-3">
                            <label for="notes" class="form-label">Additional Notes</label>
                            <textarea class="form-control" 
                                      id="notes" 
                                      name="notes" 
                                      rows="3" 
                                      placeholder="Any additional notes or comments"><?php echo htmlspecialchars($_POST['notes'] ?? $visitorDetails['notes']); ?></textarea>
                        </div>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <a href="view_visitor.php?id=<?php echo $visitorId; ?>" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Update Visitor
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-focus on visitor name field
document.getElementById('visitor_name').focus();
</script>

<?php include 'templates/footer.php'; ?>

