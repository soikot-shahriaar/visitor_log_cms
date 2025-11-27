<?php
/**
 * Delete Visitor Page
 * Visitor Log System CMS
 */

require_once 'includes/bootstrap.php';

// Require login
requireLogin();

// Get visitor ID
$visitorId = intval($_GET['id'] ?? 0);

if (!$visitorId) {
    Utils::setFlashMessage('error', 'Invalid visitor ID');
    Utils::redirect('visitors.php');
}

// Get visitor details for confirmation
$visitorDetails = $visitor->getVisitorById($visitorId);

if (!$visitorDetails) {
    Utils::setFlashMessage('error', 'Visitor not found');
    Utils::redirect('visitors.php');
}

// Handle deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (handleFormSubmission()) {
        $result = $visitor->deleteVisitor($visitorId, getCurrentAdmin()['id']);
        
        if ($result['success']) {
            Utils::setFlashMessage('success', 'Visitor record deleted successfully');
            Utils::redirect('visitors.php');
        } else {
            Utils::setFlashMessage('error', $result['message']);
        }
    }
}

// If accessed directly via GET, redirect with confirmation
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    Utils::setFlashMessage('warning', 'Please confirm deletion of visitor: ' . $visitorDetails['visitor_name']);
    Utils::redirect('view_visitor.php?id=' . $visitorId);
}
?>

