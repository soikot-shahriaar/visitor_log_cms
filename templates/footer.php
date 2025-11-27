    <?php if (getCurrentPage() !== 'login'): ?>
        <!-- Copyright Notice -->
        <div class="text-center my-2">
            <div>
                <span>© 2025 . </span>
                <span class="text- ">Developed by </span>
                <a href="https://rivertheme.com" class="fw-bold text-decoration-none" target="_blank" rel="noopener">RiverTheme</a>
            </div>
        </div>
        </div> <!-- End container-fluid -->
    </div> <!-- End main-content -->
    <?php else: ?>
    </div> <!-- End login-container -->
    <?php endif; ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script src="assets/js/app.js"></script>
    
    <?php if (isset($additionalScripts) && is_array($additionalScripts)): ?>
        <?php foreach ($additionalScripts as $script): ?>
            <script src="<?php echo $script; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <?php if (isset($inlineScript)): ?>
        <script>
            <?php echo $inlineScript; ?>
        </script>
    <?php endif; ?>
    
    <!-- Auto-refresh for dashboard -->
    <?php if (getCurrentPage() === 'dashboard' || getCurrentPage() === 'today_visitors'): ?>
    <script>
        // Auto-refresh every 5 minutes for real-time updates
        setTimeout(function() {
            location.reload();
        }, 300000);
    </script>
    <?php endif; ?>
    
</body>
</html>

