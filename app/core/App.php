<?php
class App {
    protected $controller = 'HomeController';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        // Tự động hủy các đơn hàng trực tuyến chưa thanh toán sau 1 tiếng
        $db = new Database();
        $db->query("UPDATE orders 
                    SET status = 'cancelled', 
                        cancel_reason = 'Hệ thống tự động hủy đơn do quá hạn thanh toán 1 tiếng.' 
                    WHERE status = 'pending' 
                      AND payment_method IN ('transfer', 'vnpay') 
                      AND created_at <= DATE_SUB(NOW(), INTERVAL 1 HOUR)");
        $db->execute();

        $url = $this->parseUrl();

        // Kiểm tra controller
        if (isset($url[0]) && file_exists('../app/controllers/' . ucwords($url[0]) . 'Controller.php')) {
            $this->controller = ucwords($url[0]) . 'Controller';
            unset($url[0]);
        }

        require_once '../app/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // Kiểm tra method
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // Lấy params
        $this->params = $url ? array_values($url) : [];

        // Gọi method trong controller với params
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseUrl() {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }
}
