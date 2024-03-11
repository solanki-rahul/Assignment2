-- Create Product table and insert sample data
CREATE TABLE Product (
    id INT AUTO_INCREMENT PRIMARY KEY,
    description TEXT,
    image VARCHAR(255),
    pricing DECIMAL(10,2),
    shipping_cost DECIMAL(6,2)
);

INSERT INTO Product (description, image, pricing, shipping_cost)
VALUES 
    ('Sample Product 1', 'image1.jpg', 19.99, 5.00),
    ('Sample Product 2', 'image2.jpg', 29.99, 7.50),
    ('Sample Product 3', 'image3.jpg', 39.99, 9.99);

-- Create User table and insert sample data
CREATE TABLE User (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255),
    password VARCHAR(255),
    username VARCHAR(255),
    purchase_history TEXT,
    shipping_address TEXT
);

INSERT INTO User (email, password, username, purchase_history, shipping_address)
VALUES 
    ('user1@example.com', 'password1', 'User 1', 'Purchase history for User 1', 'Address for User 1'),
    ('user2@example.com', 'password2', 'User 2', 'Purchase history for User 2', 'Address for User 2'),
    ('user3@example.com', 'password3', 'User 3', 'Purchase history for User 3', 'Address for User 3');

-- Create Comments table and insert sample data
CREATE TABLE Comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT,
    user_id INT,
    rating INT,
    image VARCHAR(255),
    text TEXT,
    FOREIGN KEY (product_id) REFERENCES Product(id),
    FOREIGN KEY (user_id) REFERENCES User(id)
);

INSERT INTO Comments (product_id, user_id, rating, image, text)
VALUES 
    (1, 1, 5, 'image1.jpg', 'Great product!'),
    (2, 2, 4, 'image2.jpg', 'Nice product, but could be improved.'),
    (3, 3, 3, 'image3.jpg', 'Average product.');

-- Create Cart table and insert sample data
CREATE TABLE Cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT,
    quantity INT,
    user_id INT,
    FOREIGN KEY (product_id) REFERENCES Product(id),
    FOREIGN KEY (user_id) REFERENCES User(id)
);

INSERT INTO Cart (product_id, quantity, user_id)
VALUES 
    (1, 2, 1),
    (2, 1, 2),
    (3, 3, 3);

-- Create Orders table and insert sample data
CREATE TABLE Orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    total_amount DECIMAL(10,2),
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES User(id)
);

INSERT INTO Orders (user_id, total_amount, order_date, shipping_address)
VALUES 
    (1, 39.98, '2024-03-10', 'Shipping address for Order 1'),
    (2, 29.99, '2024-03-10', 'Shipping address for Order 2'),
    (3, 119.97, '2024-03-10', 'Shipping address for Order 3');