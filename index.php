<?php
/**
 * Index Page
 * Visitor Log System CMS
 * Redirects to appropriate page based on login status
 */

require_once 'includes/bootstrap.php';

// Check if admin is logged in
if ($admin->isLoggedIn()) {
    // Redirect to dashboard if logged in
    Utils::redirect('dashboard.php');
} else {
    // Redirect to login if not logged in
    Utils::redirect('login.php');
}
?>

