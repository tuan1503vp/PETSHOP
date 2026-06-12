<?php
class Product {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Lấy tất cả sản phẩm với bộ lọc
    public function getProducts($params = []) {
        $sql = 'SELECT p.*, c.name as category_name 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE 1=1';
        
        // Filter by category
        if(!empty($params['category'])) {
            $sql .= ' AND p.category_id = :category_id';
        }

        // Search by name
        if(!empty($params['search'])) {
            $sql .= ' AND p.name LIKE :search';
        }

        // Filter by min price
        if(!empty($params['price_min'])) {
            $sql .= ' AND p.price >= :price_min';
        }

        // Filter by max price
        if(!empty($params['price_max'])) {
            $sql .= ' AND p.price <= :price_max';
        }

        // Filter by target pet (Dog / Cat)
        if(!empty($params['target_pet'])) {
            if($params['target_pet'] === 'dog') {
                $sql .= ' AND (p.name LIKE :dog_name1 OR p.name LIKE :dog_name2 OR p.name LIKE :dog_name3 OR p.description LIKE :dog_desc1 OR p.description LIKE :dog_desc2)';
            } elseif($params['target_pet'] === 'cat') {
                $sql .= ' AND (p.name LIKE :cat_name1 OR p.name LIKE :cat_name2 OR p.name LIKE :cat_name3 OR p.description LIKE :cat_desc1 OR p.description LIKE :cat_desc2)';
            }
        }

        // Sorting
        $sort = $params['sort'] ?? 'newest';
        switch($sort) {
            case 'price_asc':
                $sql .= ' ORDER BY p.price ASC';
                break;
            case 'price_desc':
                $sql .= ' ORDER BY p.price DESC';
                break;
            case 'oldest':
                $sql .= ' ORDER BY p.created_at ASC';
                break;
            default:
                $sql .= ' ORDER BY p.created_at DESC';
        }

        $this->db->query($sql);

        // Bindings
        if(!empty($params['category'])) {
            $this->db->bind(':category_id', $params['category']);
        }
        if(!empty($params['search'])) {
            $this->db->bind(':search', '%' . $params['search'] . '%');
        }
        if(!empty($params['price_min'])) {
            $this->db->bind(':price_min', $params['price_min']);
        }
        if(!empty($params['price_max'])) {
            $this->db->bind(':price_max', $params['price_max']);
        }
        if(!empty($params['target_pet'])) {
            if($params['target_pet'] === 'dog') {
                $this->db->bind(':dog_name1', '%chó%');
                $this->db->bind(':dog_name2', '%cún%');
                $this->db->bind(':dog_name3', '%dog%');
                $this->db->bind(':dog_desc1', '%chó%');
                $this->db->bind(':dog_desc2', '%cún%');
            } elseif($params['target_pet'] === 'cat') {
                $this->db->bind(':cat_name1', '%mèo%');
                $this->db->bind(':cat_name2', '%miu%');
                $this->db->bind(':cat_name3', '%cat%');
                $this->db->bind(':cat_desc1', '%mèo%');
                $this->db->bind(':cat_desc2', '%miu%');
            }
        }
        
        return $this->db->resultSet();
    }

    // Lấy chi tiết sản phẩm theo ID
    public function getProductById($id) {
        $this->db->query('SELECT p.*, c.name as category_name 
                          FROM products p 
                          LEFT JOIN categories c ON p.category_id = c.id
                          WHERE p.id = :id');
        
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Lấy danh mục sản phẩm (type = product)
    public function getProductCategories() {
        $this->db->query('SELECT * FROM categories WHERE type = "product"');
        return $this->db->resultSet();
    }

    // Thêm sản phẩm mới
    public function addProduct($data) {
        $this->db->query('INSERT INTO products (category_id, name, description, price, stock_quantity, image) 
                          VALUES (:category_id, :name, :description, :price, :stock_quantity, :image)');
        
        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':stock_quantity', $data['stock_quantity']);
        $this->db->bind(':image', $data['image']);

        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    // Cập nhật sản phẩm
    public function updateProduct($data) {
        $sql = 'UPDATE products SET category_id = :category_id, name = :name, description = :description, 
                price = :price, stock_quantity = :stock_quantity';
        
        if(!empty($data['image'])) {
            $sql .= ', image = :image';
        }

        $sql .= ' WHERE id = :id';

        $this->db->query($sql);
        
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':stock_quantity', $data['stock_quantity']);

        if(!empty($data['image'])) {
            $this->db->bind(':image', $data['image']);
        }

        return $this->db->execute();
    }

    // Xóa sản phẩm
    public function deleteProduct($id) {
        $this->db->query('DELETE FROM products WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // Cập nhật số lượng tồn kho (đặt số lượng cụ thể)
    public function updateStock($id, $quantity) {
        $this->db->query('UPDATE products SET stock_quantity = :quantity WHERE id = :id');
        $this->db->bind(':quantity', $quantity);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // Giảm số lượng tồn kho khi bán hàng
    public function decreaseStock($id, $quantity) {
        $this->db->query('UPDATE products SET stock_quantity = stock_quantity - :quantity WHERE id = :id AND stock_quantity >= :quantity');
        $this->db->bind(':quantity', $quantity);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // Lấy danh sách ảnh bổ sung của sản phẩm
    public function getProductImages($product_id) {
        $this->db->query('SELECT * FROM product_images WHERE product_id = :product_id ORDER BY id ASC');
        $this->db->bind(':product_id', $product_id);
        return $this->db->resultSet();
    }

    // Thêm ảnh bổ sung cho sản phẩm
    public function addProductImage($product_id, $image) {
        $this->db->query('INSERT INTO product_images (product_id, image) VALUES (:product_id, :image)');
        $this->db->bind(':product_id', $product_id);
        $this->db->bind(':image', $image);
        return $this->db->execute();
    }

    // Lấy thông tin 1 ảnh bổ sung
    public function getProductImageById($id) {
        $this->db->query('SELECT * FROM product_images WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Xóa ảnh bổ sung khỏi CSDL
    public function deleteProductImage($id) {
        $this->db->query('DELETE FROM product_images WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
