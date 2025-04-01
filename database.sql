-- Create database
CREATE DATABASE IF NOT EXISTS satta_db;
USE satta_db;

-- Create satta_results table
CREATE TABLE IF NOT EXISTS satta_results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date DATE NOT NULL,
    number VARCHAR(10) NOT NULL,
    game_type VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create admins table
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default admin user (password: admin123)
INSERT INTO admins (username, password) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Insert sample results
INSERT INTO satta_results (date, number, game_type) VALUES
(CURDATE(), '786', 'Morning'),
(CURDATE(), '234', 'Evening'),
(DATE_SUB(CURDATE(), INTERVAL 1 DAY), '567', 'Morning'),
(DATE_SUB(CURDATE(), INTERVAL 1 DAY), '890', 'Evening'),
(DATE_SUB(CURDATE(), INTERVAL 2 DAY), '123', 'Morning');

-- Add indexes for better performance
CREATE INDEX idx_date ON satta_results(date);
CREATE INDEX idx_game_type ON satta_results(game_type);