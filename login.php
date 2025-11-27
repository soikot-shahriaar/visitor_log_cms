<?php
/**
 * Enhanced Login Page
 * Visitor Log System CMS
 */

require_once 'includes/bootstrap.php';

// Redirect if already logged in
if ($admin->isLoggedIn()) {
    Utils::redirect('dashboard.php');
}

$pageTitle = 'Login';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = Utils::sanitizeInput($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $rememberMe = isset($_POST['remember_me']);
    
    if (empty($username) || empty($password)) {
        Utils::setFlashMessage('error', 'Please enter both username and password');
    } else {
        $result = $admin->login($username, $password);
        
        if ($result['success']) {
            // Set remember me cookie if requested
            if ($rememberMe) {
                setcookie('remember_user', $username, time() + (30 * 24 * 60 * 60), '/'); // 30 days
            }
            
            Utils::setFlashMessage('success', 'Login successful! Welcome back.');
            Utils::redirect('dashboard.php');
        } else {
            Utils::setFlashMessage('error', $result['message']);
        }
    }
}

// Get remembered username
$rememberedUser = $_COOKIE['remember_user'] ?? '';

include 'templates/header.php';
?>

<div class="login-container">
    <!-- Background Animation -->
    <div class="login-background">
        <div class="floating-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
            <div class="shape shape-4"></div>
            <div class="shape shape-5"></div>
        </div>
    </div>
    
    <!-- Main Login Card -->
    <div class="login-card">
        <div class="login-header">
            <div class="logo-container">
                <div class="logo-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
            </div>
            <h1>Welcome Back</h1>
            <p>Sign in to your Visitor Log System account</p>
        </div>
        
        <!-- Flash Messages -->
        <?php if (isset($_SESSION['flash_messages'])): ?>
            <?php foreach ($_SESSION['flash_messages'] as $type => $message): ?>
                <div class="alert alert-<?php echo $type === 'error' ? 'danger' : $type; ?> alert-dismissible fade show" role="alert">
                    <i class="fas fa-<?php echo $type === 'error' ? 'exclamation-triangle' : ($type === 'success' ? 'check-circle' : 'info-circle'); ?> me-2"></i>
                    <?php echo htmlspecialchars($message); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endforeach; ?>
            <?php unset($_SESSION['flash_messages']); ?>
        <?php endif; ?>
        
        <form method="POST" class="login-form" id="loginForm">
            <div class="form-group">
                <label for="username" class="form-label">
                    <i class="fas fa-user me-2"></i>Username or Email
                </label>
                <div class="input-wrapper">
                    <input type="text" 
                           class="form-control" 
                           id="username" 
                           name="username" 
                           value="<?php echo htmlspecialchars($rememberedUser); ?>"
                           placeholder="Enter your username or email"
                           autocomplete="username">
                    <div class="input-focus-border"></div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">
                    <i class="fas fa-lock me-2"></i>Password
                </label>
                <div class="input-wrapper">
                    <input type="password" 
                           class="form-control" 
                           id="password" 
                           name="password" 
                           placeholder="Enter your password"
                           autocomplete="current-password">
                    <button type="button" class="password-toggle" onclick="togglePassword()" title="Toggle password visibility">
                        <i class="fas fa-eye" id="toggleIcon"></i>
                    </button>
                    <div class="input-focus-border"></div>
                </div>
            </div>
            
            <div class="form-options">
                <div class="form-check">
                    <input type="checkbox" 
                           class="form-check-input" 
                           id="remember_me" 
                           name="remember_me"
                           <?php echo !empty($rememberedUser) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="remember_me">
                        Remember me for 30 days
                    </label>
                </div>
            </div>
            
            <button type="submit" class="btn btn-login w-100" id="loginBtn">
                <span class="btn-text">
                    <i class="fas fa-arrow-right me-2"></i>Sign In
                </span>
                <span class="btn-loading d-none">
                    <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                    Signing in...
                </span>
            </button>
        </form>
        
        <div class="login-footer">
            <div class="system-info">
                <small class="text-muted">
                    <i class="fas fa-shield-alt me-1"></i>
                    Secure Visitor Management System
                </small>
            </div>
        </div>
        
    </div>
</div>

<script>
// Password toggle functionality
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
        toggleIcon.title = 'Hide password';
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
        toggleIcon.title = 'Show password';
    }
}

// Form validation and submission
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('loginForm');
    const loginBtn = document.getElementById('loginBtn');
    const btnText = loginBtn.querySelector('.btn-text');
    const btnLoading = loginBtn.querySelector('.btn-loading');
    const usernameInput = document.getElementById('username');
    const passwordInput = document.getElementById('password');
    
    // Auto-focus on username field
    usernameInput.focus();
    
    // Form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Basic validation
        let isValid = true;
        
        // Reset previous error states
        usernameInput.classList.remove('is-invalid');
        passwordInput.classList.remove('is-invalid');
        
        // Validate username
        if (!usernameInput.value.trim()) {
            usernameInput.classList.add('is-invalid');
            isValid = false;
        }
        
        // Validate password
        if (!passwordInput.value.trim()) {
            passwordInput.classList.add('is-invalid');
            isValid = false;
        }
        
        if (isValid) {
            // Show loading state
            btnText.classList.add('d-none');
            btnLoading.classList.remove('d-none');
            loginBtn.disabled = true;
            loginBtn.classList.add('loading');
            
            // Submit form
            form.submit();
        }
    });
    
    // Real-time validation feedback
    usernameInput.addEventListener('input', function() {
        if (this.value.trim() !== '') {
            this.classList.remove('is-invalid');
        }
    });
    
    passwordInput.addEventListener('input', function() {
        if (this.value.trim() !== '') {
            this.classList.remove('is-invalid');
        }
    });
    
    // Enter key navigation
    usernameInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            passwordInput.focus();
        }
    });
    
    passwordInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            form.dispatchEvent(new Event('submit'));
        }
    });
});

// Add floating animation to shapes
document.addEventListener('DOMContentLoaded', function() {
    const shapes = document.querySelectorAll('.shape');
    shapes.forEach((shape, index) => {
        shape.style.animationDelay = `${index * 0.5}s`;
    });
});
</script>

<?php include 'templates/footer.php'; ?>

