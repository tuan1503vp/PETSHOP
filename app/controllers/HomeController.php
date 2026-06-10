<?php
class HomeController extends Controller {
    public function __construct() {
        redirectManagement();
        // Init models if needed
    }

    public function index() {
        $data = [
            'title' => 'Chào mừng đến với PETSHOP',
            'description' => 'Hệ thống quản lý toàn diện dành cho cửa hàng thú cưng.'
        ];
        
        $this->view('home/index', $data);
    }
}
