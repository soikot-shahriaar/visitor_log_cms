-- Visitor Log System Database Setup
-- This file creates the necessary database and tables for the Visitor Log System CMS

-- Create database
CREATE DATABASE IF NOT EXISTS visitor_log_system;
USE visitor_log_system;

-- Create admins table for user authentication
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_active BOOLEAN DEFAULT TRUE
);

-- Create visitors table for visitor information
CREATE TABLE IF NOT EXISTS visitors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    visitor_name VARCHAR(100) NOT NULL,
    visitor_email VARCHAR(100),
    visitor_phone VARCHAR(20),
    visitor_company VARCHAR(100),
    visit_date DATE NOT NULL,
    visit_time TIME NOT NULL,
    checkout_time TIME,
    purpose_of_visit TEXT NOT NULL,
    host_person VARCHAR(100) NOT NULL,
    host_department VARCHAR(100),
    visitor_photo VARCHAR(255),
    id_document_type ENUM('passport', 'driver_license', 'national_id', 'other') DEFAULT 'national_id',
    id_document_number VARCHAR(50),
    vehicle_number VARCHAR(20),
    notes TEXT,
    status ENUM('checked_in', 'checked_out', 'cancelled') DEFAULT 'checked_in',
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES admins(id) ON DELETE SET NULL
);

-- Create visitor_logs table for tracking visitor activities
CREATE TABLE IF NOT EXISTS visitor_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    visitor_id INT NOT NULL,
    action ENUM('check_in', 'check_out', 'update', 'cancel') NOT NULL,
    action_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    performed_by INT,
    notes TEXT,
    FOREIGN KEY (visitor_id) REFERENCES visitors(id) ON DELETE CASCADE,
    FOREIGN KEY (performed_by) REFERENCES admins(id) ON DELETE SET NULL
);

-- Insert default admin user (password: admin123)
-- Password hash for 'admin123' using PHP password_hash() with proper salt
INSERT INTO admins (username, email, password_hash, full_name) VALUES 
('admin', 'admin@company.com', '$2y$10$Hv/kr28C7m5Fu8r2b44.J.QcbD3.W0rObfmM82p49tveJZSZKejAy', 'System Administrator');

-- Create indexes for better performance
CREATE INDEX idx_visitor_date ON visitors(visit_date);
CREATE INDEX idx_visitor_name ON visitors(visitor_name);
CREATE INDEX idx_visitor_host ON visitors(host_person);
CREATE INDEX idx_visitor_status ON visitors(status);
CREATE INDEX idx_visitor_logs_visitor_id ON visitor_logs(visitor_id);
CREATE INDEX idx_visitor_logs_action_time ON visitor_logs(action_time);

-- Create view for visitor summary
CREATE VIEW visitor_summary AS
SELECT 
    v.id,
    v.visitor_name,
    v.visitor_email,
    v.visitor_phone,
    v.visitor_company,
    v.visit_date,
    v.visit_time,
    v.checkout_time,
    v.purpose_of_visit,
    v.host_person,
    v.host_department,
    v.status,
    a.full_name as created_by_name,
    v.created_at
FROM visitors v
LEFT JOIN admins a ON v.created_by = a.id
ORDER BY v.visit_date DESC, v.visit_time DESC;

