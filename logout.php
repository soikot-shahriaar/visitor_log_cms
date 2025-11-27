<?php
/**
 * Logout Page
 * Visitor Log System CMS
 */

require_once 'includes/bootstrap.php';

// Perform logout
$result = $admin->logout();

// Set flash message
Utils::setFlashMessage('success', 'You have been logged out successfully.');

// Redirect to login page
Utils::redirect('login.php');
?>

