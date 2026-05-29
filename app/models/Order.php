<?php
class Order {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Tạo đơn hàng mới
    public function createOrder($data) {
        $this->db->query('INSERT INTO orders (customer_id, customer_name, customer_phone, total_amount, payment_method, order_type, status, shipping_name, shipping_phone, shipping_address) 
                          VALUES (:customer_id, :customer_name, :customer_phone, :total_amount, :payment_method, :order_type, :status, :shipping_name, :shipping_phone, :shipping_address)');
        
        $this->db->bind(':customer_id', $data['customer_id'] ?? null);
        $this->db->bind(':customer_name', $data['customer_name'] ?? null);
        $this->db->bind(':customer_phone', $data['customer_phone'] ?? null);
        $this->db->bind(':total_amount', $data['total_amount']);
        $this->db->bind(':payment_method', $data['payment_method'] ?? 'cash');
        $this->db->bind(':order_type', $data['order_type'] ?? 'online');
        $this->db->bind(':status', $data['status'] ?? 'pending');
        $this->db->bind(':shipping_name', $data['shipping_name'] ?? null);
        $this->db->bind(':shipping_phone', $data['shipping_phone'] ?? null);
        $this->db->bind(':shipping_address', $data['shipping_address'] ?? null);

        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }

    // Thêm chi tiết đơn hàng (Order items)
    public function addOrderItem($order_id, $product_id, $quantity, $unit_price) {
        $this->db->query('INSERT INTO order_items (order_id, product_id, quantity, unit_price) 
                          VALUES (:order_id, :product_id, :quantity, :unit_price)');
        
        $this->db->bind(':order_id', $order_id);
        $this->db->bind(':product_id', $product_id);
        $this->db->bind(':quantity', $quantity);
        $this->db->bind(':unit_price', $unit_price);

        return $this->db->execute();
    }

    // Lấy danh sách đơn hàng của một người dùng
    public function getOrdersByUser($user_id) {
        $this->db->query('SELECT * FROM orders WHERE customer_id = :user_id ORDER BY created_at DESC');
        $this->db->bind(':user_id', $user_id);
        return $this->db->resultSet();
    }

    // Lấy chi tiết các sản phẩm trong một đơn hàng
    public function getOrderItems($order_id) {
        $this->db->query('SELECT oi.*, p.name, p.image 
                          FROM order_items oi 
                          JOIN products p ON oi.product_id = p.id 
                          WHERE oi.order_id = :order_id');
        $this->db->bind(':order_id', $order_id);
        return $this->db->resultSet();
    }

    // Lấy tất cả đơn hàng (cho Admin)
    public function getAllOrders() {
        $this->db->query('SELECT o.*, COALESCE(o.customer_name, u.fullname) as customer_name 
                          FROM orders o 
                          LEFT JOIN users u ON o.customer_id = u.id 
                          ORDER BY o.created_at DESC');
        return $this->db->resultSet();
    }

    // Lấy tất cả đơn hàng đã hoàn thành
    public function getCompletedOrders() {
        $this->db->query('SELECT o.*, COALESCE(o.customer_name, u.fullname) as customer_name 
                          FROM orders o 
                          LEFT JOIN users u ON o.customer_id = u.id 
                          WHERE o.status = "completed"
                          ORDER BY o.created_at DESC');
        return $this->db->resultSet();
    }

    // Cập nhật trạng thái đơn hàng
    public function updateStatus($order_id, $status) {
        $this->db->query('UPDATE orders SET status = :status WHERE id = :id');
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $order_id);
        return $this->db->execute();
    }

    // Duyệt đơn hàng (có số tiền thực nhận và ghi chú)
    public function approveOrder($order_id, $paid_amount, $admin_note) {
        $this->db->query('UPDATE orders SET status = "shipping", paid_amount = :paid_amount, admin_note = :admin_note WHERE id = :id');
        $this->db->bind(':paid_amount', $paid_amount);
        $this->db->bind(':admin_note', $admin_note);
        $this->db->bind(':id', $order_id);
        return $this->db->execute();
    }

    // Cập nhật trạng thái kèm lý do (dùng cho hủy đơn)
    public function updateStatusWithReason($order_id, $status, $reason) {
        $this->db->query('UPDATE orders SET status = :status, cancel_reason = :reason WHERE id = :id');
        $this->db->bind(':status', $status);
        $this->db->bind(':reason', $reason);
        $this->db->bind(':id', $order_id);
        return $this->db->execute();
    }

    // Lấy đơn hàng theo loại (online/pos)
    public function getOrdersByType($type) {
        $this->db->query('SELECT o.*, COALESCE(o.customer_name, u.fullname) as customer_name 
                          FROM orders o 
                          LEFT JOIN users u ON o.customer_id = u.id 
                          WHERE o.order_type = :type
                          ORDER BY o.created_at DESC');
        $this->db->bind(':type', $type);
        return $this->db->resultSet();
    }

    public function getOrdersFiltered($filters = []) {
        $sql = 'SELECT o.*, COALESCE(o.customer_name, u.fullname) as customer_name, u.email as customer_email, 
                       COALESCE(o.customer_phone, m.phone) as customer_phone, m.address as customer_address 
                FROM orders o 
                LEFT JOIN users u ON o.customer_id = u.id
                LEFT JOIN members m ON u.id = m.user_id
                WHERE 1=1';
        
        if (!empty($filters['type']) && $filters['type'] != 'all') {
            $sql .= ' AND o.order_type = :type';
        }
        if (!empty($filters['status']) && $filters['status'] != 'all') {
            if ($filters['status'] == 'refund_pending') {
                $sql .= " AND o.status = 'cancelled' AND o.refund_status = 'pending'";
            } elseif ($filters['status'] == 'refund_completed') {
                $sql .= " AND o.status = 'cancelled' AND o.refund_status = 'completed'";
            } else {
                $sql .= ' AND o.status = :status';
            }
        }
        if (!empty($filters['date'])) {
            $sql .= ' AND DATE(o.created_at) = :date';
        } elseif (!empty($filters['month']) && !empty($filters['year'])) {
            $sql .= ' AND MONTH(o.created_at) = :month AND YEAR(o.created_at) = :year';
        } elseif (!empty($filters['year'])) {
            $sql .= ' AND YEAR(o.created_at) = :year';
        }
        
        $sql .= ' ORDER BY o.created_at DESC';
        
        $this->db->query($sql);
        
        if (!empty($filters['type']) && $filters['type'] != 'all') {
            $this->db->bind(':type', $filters['type']);
        }
        if (!empty($filters['status']) && $filters['status'] != 'all' && !in_array($filters['status'], ['refund_pending', 'refund_completed'])) {
            $this->db->bind(':status', $filters['status']);
        }
        if (!empty($filters['date'])) {
            $this->db->bind(':date', $filters['date']);
        } elseif (!empty($filters['month']) && !empty($filters['year'])) {
            $this->db->bind(':month', $filters['month']);
            $this->db->bind(':year', $filters['year']);
        } elseif (!empty($filters['year'])) {
            $this->db->bind(':year', $filters['year']);
        }
        
        return $this->db->resultSet();
    }

    // Thống kê doanh thu theo ngày/tháng/năm
    public function getRevenueStats($filters = []) {
        $sql = 'SELECT DATE(o.created_at) as date, COUNT(*) as total_orders, SUM(o.total_amount) as revenue
                FROM orders o WHERE o.status = "completed"';
        
        if (!empty($filters['month']) && !empty($filters['year'])) {
            $sql .= ' AND MONTH(o.created_at) = :month AND YEAR(o.created_at) = :year';
        } elseif (!empty($filters['year'])) {
            $sql .= ' AND YEAR(o.created_at) = :year';
        }
        
        $sql .= ' GROUP BY DATE(o.created_at) ORDER BY date DESC LIMIT 30';
        $this->db->query($sql);
        
        if (!empty($filters['month']) && !empty($filters['year'])) {
            $this->db->bind(':month', $filters['month']);
            $this->db->bind(':year', $filters['year']);
        } elseif (!empty($filters['year'])) {
            $this->db->bind(':year', $filters['year']);
        }
        
        return $this->db->resultSet();
    }
    public function getOrderById($id) {
        $this->db->query('SELECT o.*, COALESCE(o.customer_name, u.fullname) as customer_name, u.email as customer_email, 
                                 COALESCE(o.customer_phone, m.phone) as customer_phone, m.address as customer_address 
                          FROM orders o 
                          LEFT JOIN users u ON o.customer_id = u.id
                          LEFT JOIN members m ON u.id = m.user_id
                          WHERE o.id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
}
