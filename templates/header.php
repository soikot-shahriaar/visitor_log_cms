<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' . APP_NAME : APP_NAME; ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="assets/css/style.css" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.ico">
    
    <meta name="description" content="Visitor Log System - Efficient visitor management for organizations">
    <meta name="author" content="Visitor Log System CMS">
</head>
<body>
    <?php if (getCurrentPage() !== 'login'): ?>
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h4><i class="fas fa-users me-2"></i><span>Visitor Log</span></h4>
        </div>
        
        <div class="sidebar-nav">
            <a href="dashboard.php" class="nav-link <?php echo isActivePage('dashboard'); ?>">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
            
            <a href="visitors.php" class="nav-link <?php echo isActivePage('visitors'); ?>">
                <i class="fas fa-users"></i>
                <span>All Visitors</span>
            </a>
            
            <a href="add_visitor.php" class="nav-link <?php echo isActivePage('add_visitor'); ?>">
                <i class="fas fa-user-plus"></i>
                <span>Add Visitor</span>
            </a>
            
            <a href="today_visitors.php" class="nav-link <?php echo isActivePage('today_visitors'); ?>">
                <i class="fas fa-calendar-day"></i>
                <span>Today's Visitors</span>
            </a>
            
            <a href="reports.php" class="nav-link <?php echo isActivePage('reports'); ?>">
                <i class="fas fa-chart-bar"></i>
                <span>Reports</span>
            </a>
            
            <a href="settings.php" class="nav-link <?php echo isActivePage('settings'); ?>">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </a>
            
            <hr style="border-color: rgba(255,255,255,0.1); margin: 1rem;">
            
            <a href="profile.php" class="nav-link <?php echo isActivePage('profile'); ?>">
                <i class="fas fa-user"></i>
                <span>Profile</span>
            </a>
            
            <a href="logout.php" class="nav-link" data-confirm="Are you sure you want to logout?">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </div>
    </nav>
    
    <!-- Main Content -->
    <div class="main-content" id="main-content">
        <!-- Header -->
        <header class="header">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <button class="toggle-sidebar me-3">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="header-title"><?php echo $pageTitle ?? 'Dashboard'; ?></h1>
                </div>
                
                <div class="header-actions">
                    <div class="dropdown">
                        <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-2"></i>
                            <?php 
                            $currentAdmin = getCurrentAdmin();
                            echo $currentAdmin ? $currentAdmin['name'] : 'Admin';
                            ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user me-2"></i>Profile</a></li>
                            <li><a class="dropdown-item" href="settings.php"><i class="fas fa-cog me-2"></i>Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php" data-confirm="Are you sure you want to logout?"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>
        
        <!-- Page Content -->
        <div class="container-fluid p-4">
            <?php
            // Display flash messages
            $flashMessage = Utils::getFlashMessage();
            if ($flashMessage):
            ?>
            <div class="alert alert-<?php echo $flashMessage['type'] === 'error' ? 'danger' : $flashMessage['type']; ?> alert-dismissible fade show" role="alert">
                <i class="fas fa-<?php echo $flashMessage['type'] === 'error' ? 'exclamation-triangle' : ($flashMessage['type'] === 'success' ? 'check-circle' : 'info-circle'); ?> me-2"></i>
                <?php echo htmlspecialchars($flashMessage['message']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
    <?php else: ?>
    <!-- Login page layout -->
    <div class="login-container">
    <?php endif; ?>

