-- Tạo bảng users
CREATE TABLE IF NOT EXISTS users (
                                     id SERIAL PRIMARY KEY,
                                     name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

-- Tạo bảng categories
CREATE TABLE IF NOT EXISTS categories (
                                          id SERIAL PRIMARY KEY,
                                          name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

-- Tạo bảng products
CREATE TABLE IF NOT EXISTS products (
                                        id SERIAL PRIMARY KEY,
                                        category_id INTEGER REFERENCES categories(id),
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(12, 2) NOT NULL,
    stock INTEGER NOT NULL DEFAULT 0,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );
ALTER TABLE products ADD COLUMN discount INTEGER DEFAULT 0 CHECK (discount >= 0 AND discount <= 100);

-- Tạo bảng orders
CREATE TABLE IF NOT EXISTS orders (
                                      id SERIAL PRIMARY KEY,
                                      user_id INTEGER REFERENCES users(id),
    total_amount DECIMAL(12, 2) NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'pending',
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address TEXT NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    );

-- Tạo bảng order_items
CREATE TABLE IF NOT EXISTS order_items (
                                           id SERIAL PRIMARY KEY,
                                           order_id INTEGER REFERENCES orders(id),
    product_id INTEGER REFERENCES products(id),
    quantity INTEGER NOT NULL,
    price DECIMAL(12, 2) NOT NULL
    );

-- Tạo bảng carts
CREATE TABLE IF NOT EXISTS carts (
                                     id SERIAL PRIMARY KEY,
                                     user_id INTEGER REFERENCES users(id),
                                     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                                     updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tạo bảng cart_items
CREATE TABLE IF NOT EXISTS cart_items (
                                          id SERIAL PRIMARY KEY,
                                          cart_id INTEGER REFERENCES carts(id) ON DELETE CASCADE,
                                          product_id INTEGER REFERENCES products(id),
                                          quantity INTEGER NOT NULL DEFAULT 1,
                                          created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                                          updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                                          UNIQUE(cart_id, product_id)
);

CREATE TABLE IF NOT EXISTS chatbot_responses (
    id SERIAL PRIMARY KEY,
    keywords TEXT NOT NULL,
    response TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Thêm một số câu trả lời mẫu
INSERT INTO chatbot_responses (keywords, response) VALUES
    ('xin chào,chào,hi,hello', 'Xin chào! Tôi có thể giúp gì cho bạn?'),
    ('giờ làm việc,thời gian làm việc', 'Chúng tôi làm việc từ 8h00 - 22h00 các ngày trong tuần'),
    ('phí vận chuyển,ship,giao hàng', 'Phí vận chuyển sẽ được tính dựa trên địa chỉ giao hàng của bạn. Bạn có thể kiểm tra phí vận chuyển trong giỏ hàng.'),
    ('thanh toán,payment,trả góp', 'Chúng tôi hỗ trợ thanh toán qua: COD (tiền mặt khi nhận hàng), VNPAY, thẻ tín dụng/ghi nợ'),
    ('đổi trả,hoàn tiền,bảo hành', 'Chúng tôi có chính sách đổi trả trong vòng 7 ngày với sản phẩm còn nguyên vẹn. Vui lòng liên hệ hotline để được hướng dẫn.'),
    ('tạm biệt,bye,goodbye', 'Cảm ơn bạn đã liên hệ! Chúc bạn một ngày tốt lành!');

-- Tạo tài khoản admin mặc định (password: admin123)
INSERT INTO users (name, email, password, role) VALUES
    ('Admin', 'admin@example.com', '$2y$10$ZHJ1Ht0GbQhxsOEWQxKQHuM3sGF9nKpBO4xnnrQnC5JZ2YTEIVtlO', 'admin')
    ON CONFLICT (email) DO NOTHING;

-- Tạo một số danh mục mẫu
INSERT INTO categories (name, description) VALUES
                                               ('Điện thoại', 'Các loại điện thoại di động'),
                                               ('Laptop', 'Các loại máy tính xách tay'),
                                               ('Máy tính bảng', 'Các loại máy tính bảng'),
                                               ('Phụ kiện', 'Các loại phụ kiện điện tử'),
                                               ('Máy tính bàn', 'Các loại máy tính bàn'),
                                               ('Màn hình', 'Các loại màn hình')
    ON CONFLICT DO NOTHING;

-- Tạo một số sản phẩm mẫu
INSERT INTO products (category_id, name, description, price, stock, image) VALUES
                                                                               (1, 'iPhone 13', 'Điện thoại iPhone 13 mới nhất từ Apple', 22990000, 10, 'https://via.placeholder.com/300x200?text=iPhone+13'),
                                                                               (1, 'Samsung Galaxy S21', 'Điện thoại Samsung Galaxy S21 mới nhất', 19990000, 15, 'https://via.placeholder.com/300x200?text=Samsung+S21'),
                                                                               (2, 'MacBook Pro', 'Laptop MacBook Pro mới nhất từ Apple', 35990000, 5, 'https://via.placeholder.com/300x200?text=MacBook+Pro'),
                                                                               (2, 'Dell XPS 13', 'Laptop Dell XPS 13 mới nhất', 29990000, 8, 'https://via.placeholder.com/300x200?text=Dell+XPS+13'),
                                                                               (3, 'iPad Pro', 'Máy tính bảng iPad Pro mới nhất từ Apple', 25990000, 7, 'https://via.placeholder.com/300x200?text=iPad+Pro'),
                                                                               (4, 'AirPods Pro', 'Tai nghe không dây AirPods Pro từ Apple', 5990000, 20, 'https://via.placeholder.com/300x200?text=AirPods+Pro'),
                                                                               (4, 'Sạc dự phòng', 'Sạc dự phòng dung lượng cao', 1290000, 30, 'https://via.placeholder.com/300x200?text=Sạc+dự+phòng')
    ON CONFLICT DO NOTHING;