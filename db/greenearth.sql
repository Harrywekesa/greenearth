-- Step 1: Create the 'greenearth' database
CREATE DATABASE IF NOT EXISTS greenearth;
USE greenearth;

-- Step 2: Create the 'seedlings' table
CREATE TABLE IF NOT EXISTS seedlings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2),
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Step 3: Create the 'users' table (for admin or user accounts)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Step 4: Create the 'events' table (for reforestation events)
CREATE TABLE IF NOT EXISTS events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    location VARCHAR(255),
    event_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Step 5: Create the 'orders' table (for tracking seedling purchases)
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    seedling_id INT NOT NULL,
    quantity INT DEFAULT 1,
    total_price DECIMAL(10, 2),
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (seedling_id) REFERENCES seedlings(id)
);

-- Step 6: Insert sample data into the 'seedlings' table
INSERT INTO seedlings (name, description, price, image) VALUES
('Moringa Tree', 'Fast-growing tree with nutritional leaves and seeds.', 50.00, 'images/moringa.jpg'),
('Acacia Tortilis', 'Drought-resistant tree suitable for arid regions.', 75.00, 'images/acacia.jpg'),
('Eucalyptus', 'Rapidly growing tree used for timber and oil production.', 60.00, 'images/eucalyptus.jpg');

-- Step 7: Insert sample data into the 'users' table
INSERT INTO users (username, email, password, role) VALUES
('admin', 'admin@greenearth.com', PASSWORD('admin123'), 'admin'),
('user1', 'user1@example.com', PASSWORD('password123'), 'user');

-- Step 8: Insert sample data into the 'events' table
INSERT INTO events (title, description, location, event_date) VALUES
('Community Tree Planting Day', 'Join us for a day of planting trees in Kitale!', 'Kitale National Park', '2023-11-15'),
('Reforestation Drive', 'Help restore the forest in Eldoret!', 'Eldoret Forest Reserve', '2023-12-01');