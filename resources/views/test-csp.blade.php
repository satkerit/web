<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel CSP Test</title>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; max-width: 800px; margin: 0 auto; }
        .test-result { padding: 15px; margin: 15px 0; border-radius: 8px; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .info { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
        #editor { min-height: 200px; border: 1px solid #ddd; margin: 20px 0; }
        .header { background: #007bff; color: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
        .csp-info { background: #f8f9fa; padding: 15px; border-radius: 8px; margin: 20px 0; }
        code { background: #f8f9fa; padding: 2px 6px; border-radius: 4px; font-family: monospace; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🔒 Laravel CSP Test Page</h1>
        <p>Testing Content Security Policy with Laravel SecurityHeaders middleware</p>
    </div>
    
    <div class="csp-info">
        <h3>📋 CSP Information</h3>
        <p><strong>Current URL:</strong> {{ url()->current() }}</p>
        <p><strong>Environment:</strong> {{ app()->environment() }}</p>
        <p><strong>Laravel Version:</strong> {{ app()->version() }}</p>
    </div>
    
    <div id="test-results">
        <div class="test-result info">
            <strong>🧪 Running Tests...</strong>
            <p>This page will test if jQuery and Summernote can load through Laravel's CSP middleware.</p>
        </div>
        
        <div class="test-result" id="jquery-test">
            <strong>jQuery Test:</strong> <span id="jquery-status">Loading...</span>
        </div>
        
        <div class="test-result" id="summernote-test">
            <strong>Summernote Test:</strong> <span id="summernote-status">Loading...</span>
        </div>
        
        <div class="test-result" id="csp-test">
            <strong>CSP Violations:</strong> <span id="csp-status">Checking...</span>
        </div>
    </div>
    
    <h2>📝 Summernote Editor Test</h2>
    <div id="editor">
        <p>This should become a rich text editor if everything works correctly.</p>
        <p>Try typing here and using the toolbar buttons.</p>
    </div>
    
    <div class="test-result info">
        <h3>🔍 How to Check for CSP Issues:</h3>
        <ol>
            <li>Open Developer Tools (F12)</li>
            <li>Go to the <strong>Console</strong> tab</li>
            <li>Look for any red error messages mentioning "Content Security Policy"</li>
            <li>If you see CSP violations, the domains need to be added to the CSP policy</li>
            <li>If no CSP errors appear, the configuration is working correctly! ✅</li>
        </ol>
    </div>
    
    <div class="test-result info">
        <h3>🎯 Expected Results:</h3>
        <ul>
            <li>✅ No CSP violation errors in console</li>
            <li>✅ jQuery loads successfully</li>
            <li>✅ Summernote editor appears with toolbar</li>
            <li>✅ Rich text editing works</li>
        </ul>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
    
    <script>
        console.log('🔒 Laravel CSP Test Page Loaded');
        console.log('📋 Checking for CSP violations...');
        
        let cspViolations = 0;
        
        // Listen for CSP violations
        document.addEventListener('securitypolicyviolation', function(e) {
            cspViolations++;
            console.error('🚨 CSP Violation:', e.violatedDirective, e.blockedURI);
            document.getElementById('csp-status').textContent = `${cspViolations} violation(s) detected`;
            document.getElementById('csp-test').className = 'test-result error';
        });
        
        // Test jQuery
        if (typeof jQuery !== 'undefined') {
            console.log('✅ jQuery loaded successfully');
            document.getElementById('jquery-status').textContent = 'SUCCESS - jQuery v' + jQuery.fn.jquery + ' loaded';
            document.getElementById('jquery-test').className = 'test-result success';
            
            // Test Summernote
            jQuery(document).ready(function($) {
                if (typeof $.fn.summernote !== 'undefined') {
                    console.log('✅ Summernote loaded successfully');
                    document.getElementById('summernote-status').textContent = 'SUCCESS - Summernote loaded and ready';
                    document.getElementById('summernote-test').className = 'test-result success';
                    
                    // Initialize Summernote
                    try {
                        $('#editor').summernote({
                            height: 200,
                            placeholder: 'Type here to test Summernote functionality...',
                            toolbar: [
                                ['style', ['style']],
                                ['font', ['bold', 'italic', 'underline', 'clear']],
                                ['color', ['color']],
                                ['para', ['ul', 'ol', 'paragraph']],
                                ['table', ['table']],
                                ['insert', ['link']],
                                ['view', ['fullscreen', 'codeview']]
                            ]
                        });
                        console.log('✅ Summernote editor initialized successfully');
                    } catch (error) {
                        console.error('❌ Error initializing Summernote:', error);
                        document.getElementById('summernote-status').textContent = 'ERROR - Failed to initialize Summernote: ' + error.message;
                        document.getElementById('summernote-test').className = 'test-result error';
                    }
                } else {
                    console.error('❌ Summernote not loaded');
                    document.getElementById('summernote-status').textContent = 'ERROR - Summernote not loaded';
                    document.getElementById('summernote-test').className = 'test-result error';
                }
                
                // Check CSP status after a delay
                setTimeout(function() {
                    if (cspViolations === 0) {
                        document.getElementById('csp-status').textContent = 'No violations detected ✅';
                        document.getElementById('csp-test').className = 'test-result success';
                        console.log('✅ No CSP violations detected - Configuration is working correctly!');
                    }
                }, 2000);
            });
        } else {
            console.error('❌ jQuery not loaded');
            document.getElementById('jquery-status').textContent = 'ERROR - jQuery not loaded (CSP violation?)';
            document.getElementById('jquery-test').className = 'test-result error';
            document.getElementById('summernote-status').textContent = 'ERROR - Cannot test Summernote without jQuery';
            document.getElementById('summernote-test').className = 'test-result error';
        }
    </script>
</body>
</html>