<?php

/**
 * Test Controller - Yeni Core Controller yapısını test eder
 */
class TestController extends BaseController
{
    public function index()
    {
        // Performance tracking başlat
        $startTime = microtime(true);
        
        // Global veri ekle
        $this->addGlobalData('page_description', 'Core Controller test sayfası');
        
        // Test verileri
        $testResults = [
            'core_controller' => $this->testCoreController(),
            'base_controller' => $this->testBaseController(),
            'performance' => $this->testPerformance(),
            'security' => $this->testSecurity(),
            'api' => $this->testApiMethods()
        ];
        
        // Performance tracking bitir
        $this->trackPerformance('TestController::index', microtime(true) - $startTime);
        
        $data = [
            'page_title' => 'Controller Test Sayfası',
            'test_results' => $testResults,
            'execution_time' => $this->getExecutionTime(),
            'memory_usage' => $this->getMemoryUsage()
        ];
        
        $this->view('test/controller-test', $data);
    }

    /**
     * Core Controller metodlarını test et
     */
    private function testCoreController()
    {
        $tests = [];
        
        // Temel metodlar test
        $tests['view_method'] = method_exists($this, 'view') ? '✅' : '❌';
        $tests['model_method'] = method_exists($this, 'model') ? '✅' : '❌';
        $tests['service_method'] = method_exists($this, 'service') ? '✅' : '❌';
        $tests['redirect_method'] = method_exists($this, 'redirect') ? '✅' : '❌';
        $tests['json_method'] = method_exists($this, 'json') ? '✅' : '❌';
        
        // Request helper metodlar
        $tests['input_method'] = method_exists($this, 'input') ? '✅' : '❌';
        $tests['isPost_method'] = method_exists($this, 'isPost') ? '✅' : '❌';
        $tests['isAjax_method'] = method_exists($this, 'isAjax') ? '✅' : '❌';
        
        // Security metodlar
        $tests['validateCSRFToken_method'] = method_exists($this, 'validateCSRFToken') ? '✅' : '❌';
        $tests['validate_method'] = method_exists($this, 'validate') ? '✅' : '❌';
        
        return $tests;
    }

    /**
     * BaseController'dan gelen metodları test et
     */
    private function testBaseController()
    {
        $tests = [];
        
        // Enhanced helper metodlar
        $tests['redirectWithSuccess_method'] = method_exists($this, 'redirectWithSuccess') ? '✅' : '❌';
        $tests['redirectWithError_method'] = method_exists($this, 'redirectWithError') ? '✅' : '❌';
        $tests['validateOrRedirect_method'] = method_exists($this, 'validateOrRedirect') ? '✅' : '❌';
        $tests['verifyCsrfOrFail_method'] = method_exists($this, 'verifyCsrfOrFail') ? '✅' : '❌';
        
        // API metodlar
        $tests['apiSuccess_method'] = method_exists($this, 'apiSuccess') ? '✅' : '❌';
        $tests['apiError_method'] = method_exists($this, 'apiError') ? '✅' : '❌';
        
        // Pagination & File metodlar
        $tests['paginate_method'] = method_exists($this, 'paginate') ? '✅' : '❌';
        $tests['forceDownload_method'] = method_exists($this, 'forceDownload') ? '✅' : '❌';
        
        return $tests;
    }

    /**
     * Performance tracking test et
     */
    private function testPerformance()
    {
        $tests = [];
        
        // Memory usage test
        $memoryUsage = $this->getMemoryUsage();
        $tests['memory_tracking'] = isset($memoryUsage['current']) ? '✅' : '❌';
        $tests['memory_current'] = $memoryUsage['formatted']['current'] ?? 'N/A';
        $tests['memory_peak'] = $memoryUsage['formatted']['peak'] ?? 'N/A';
        
        // Execution time test
        $executionTime = $this->getExecutionTime();
        $tests['execution_time_tracking'] = $executionTime > 0 ? '✅' : '❌';
        $tests['execution_time'] = number_format($executionTime * 1000, 2) . ' ms';
        
        // Cache test
        $cacheKey = 'test_cache_' . time();
        $testData = ['test' => 'data', 'timestamp' => time()];
        
        // Cache'e kaydet
        $this->cache($cacheKey, $testData, 60);
        
        // Cache'den oku
        $cachedData = $this->cache($cacheKey);
        $tests['cache_functionality'] = ($cachedData && $cachedData['test'] === 'data') ? '✅' : '❌';
        
        return $tests;
    }

    /**
     * Security özellikleri test et
     */
    private function testSecurity()
    {
        $tests = [];
        
        // CSRF token test
        $csrfToken = $this->getGlobalData('csrf_token');
        $tests['csrf_token_generation'] = !empty($csrfToken) ? '✅' : '❌';
        
        // Auth kontrol test
        $tests['auth_check'] = method_exists($this, 'isUserLoggedIn') ? '✅' : '❌';
        $tests['require_auth'] = method_exists($this, 'requireAuth') ? '✅' : '❌';
        $tests['require_admin'] = method_exists($this, 'requireAdmin') ? '✅' : '❌';
        
        // Rate limiting test
        $tests['rate_limiting'] = method_exists($this, 'checkRateLimit') ? '✅' : '❌';
        
        // Input validation test
        $testValidation = $this->validate(['email' => 'test@example.com'], ['email' => 'required|email']);
        $tests['input_validation'] = empty($testValidation) ? '✅' : '❌';
        
        return $tests;
    }

    /**
     * API metodlarını test et
     */
    private function testApiMethods()
    {
        $tests = [];
        
        // API response metodları
        $tests['apiSuccess'] = method_exists($this, 'apiSuccess') ? '✅' : '❌';
        $tests['apiError'] = method_exists($this, 'apiError') ? '✅' : '❌';
        $tests['apiValidationError'] = method_exists($this, 'apiValidationError') ? '✅' : '❌';
        $tests['apiUnauthorized'] = method_exists($this, 'apiUnauthorized') ? '✅' : '❌';
        $tests['apiNotFound'] = method_exists($this, 'apiNotFound') ? '✅' : '❌';
        
        // Export metodları
        $tests['csv_export'] = method_exists($this, 'csv') ? '✅' : '❌';
        $tests['xml_export'] = method_exists($this, 'xml') ? '✅' : '❌';
        
        // Middleware metodları
        $tests['requireAjax'] = method_exists($this, 'requireAjax') ? '✅' : '❌';
        $tests['requireJson'] = method_exists($this, 'requireJson') ? '✅' : '❌';
        $tests['requireHttps'] = method_exists($this, 'requireHttps') ? '✅' : '❌';
        
        return $tests;
    }

    /**
     * API test endpoint
     */
    public function apiTest()
    {
        try {
            $testData = [
                'framework' => 'Simple Framework v2.0',
                'controller_type' => 'Core Controller',
                'features' => [
                    'Enhanced API responses',
                    'Automatic validation',
                    'Performance tracking',
                    'Security middleware',
                    'Rate limiting'
                ],
                'test_timestamp' => date('c'),
                'memory_usage' => $this->getMemoryUsage(),
                'execution_time' => $this->getExecutionTime()
            ];
            
            $this->apiSuccess($testData, 'API test başarılı!');
            
        } catch (Exception $e) {
            $this->apiError('API test hatası: ' . $e->getMessage());
        }
    }

    /**
     * Rate limiting test
     */
    public function rateLimitTest()
    {
        // 10 saniyede maksimum 3 istek
        $this->checkRateLimit(3, 10);
        
        $this->apiSuccess([
            'message' => 'Rate limit test geçildi',
            'timestamp' => date('c'),
            'tip' => 'Bu endpoint 10 saniyede maksimum 3 istek kabul eder'
        ]);
    }

    /**
     * Cache test
     */
    public function cacheTest()
    {
        $cacheKey = 'test_data_' . date('Y-m-d-H-i');
        
        // Cache'den veri al
        $cachedData = $this->cache($cacheKey);
        
        if ($cachedData === null) {
            // Cache'de yok, yeni veri oluştur
            $data = [
                'generated_at' => date('c'),
                'random_number' => rand(1000, 9999),
                'expensive_calculation' => $this->performExpensiveCalculation()
            ];
            
            // Cache'e kaydet (60 saniye)
            $this->cache($cacheKey, $data, 60);
            
            $fromCache = false;
        } else {
            $data = $cachedData;
            $fromCache = true;
        }
        
        $this->apiSuccess([
            'data' => $data,
            'from_cache' => $fromCache,
            'cache_key' => $cacheKey
        ], $fromCache ? 'Veri cache\'den geldi' : 'Yeni veri oluşturuldu');
    }

    /**
     * Export test - CSV
     */
    public function exportCsv()
    {
        $testData = [
            ['id' => 1, 'name' => 'Test User 1', 'email' => 'test1@example.com'],
            ['id' => 2, 'name' => 'Test User 2', 'email' => 'test2@example.com'],
            ['id' => 3, 'name' => 'Test User 3', 'email' => 'test3@example.com']
        ];
        
        $this->csv($testData, 'test_export_' . date('Y-m-d') . '.csv');
    }

    /**
     * Export test - XML
     */
    public function exportXml()
    {
        $testData = [
            'framework' => 'Simple Framework',
            'version' => '2.0',
            'features' => [
                'Core Controller',
                'Enhanced API',
                'Performance Tracking'
            ],
            'exported_at' => date('c')
        ];
        
        $this->xml($testData);
    }

    /**
     * Debug bilgileri göster
     */
    public function debugInfo()
    {
        // Debug mode kontrolü
        if (!$this->getGlobalData('debug_mode')) {
            $this->apiError('Debug modu aktif değil', 403);
            return;
        }
        
        $debugData = [
            'framework_info' => [
                'name' => $this->getGlobalData('framework_name'),
                'version' => $this->getGlobalData('framework_version'),
                'debug_mode' => $this->getGlobalData('debug_mode')
            ],
            'performance' => [
                'execution_time' => $this->getExecutionTime(),
                'memory_usage' => $this->getMemoryUsage()
            ],
            'request_info' => [
                'method' => $_SERVER['REQUEST_METHOD'],
                'uri' => $_SERVER['REQUEST_URI'],
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown'
            ],
            'global_data' => $this->getGlobalData(),
            'available_methods' => get_class_methods($this)
        ];
        
        $this->apiSuccess($debugData, 'Debug bilgileri');
    }

    /**
     * Expensive calculation simulation
     */
    private function performExpensiveCalculation()
    {
        // Simulate expensive operation
        usleep(100000); // 0.1 second
        
        return [
            'result' => rand(1000, 9999),
            'calculated_at' => microtime(true)
        ];
    }
}
