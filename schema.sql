-- Database schema for meetingqueue_db
-- Host: 192.168.9.234
-- Username: meetingqueue
-- Password: Meeting@11190
-- DBName: meetingqueue_db

CREATE DATABASE IF NOT EXISTS meetingqueue_db DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE meetingqueue_db;

-- Users table (Synced from ZK BioTime)
CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    emp_code VARCHAR(50) NOT NULL UNIQUE,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, -- Will store cid as provided
    first_name VARCHAR(100),
    last_name VARCHAR(100), -- cid in the query
    position_name VARCHAR(100),
    dept_name VARCHAR(100),
    photo LONGTEXT, -- Base64 photo
    role ENUM('admin', 'user') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_username (username)
) ENGINE=InnoDB;

-- Rooms table
CREATE TABLE IF NOT EXISTS rooms (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    capacity INT UNSIGNED NOT NULL,
    location VARCHAR(255),
    description TEXT,
    image_url VARCHAR(255),
    status ENUM('available', 'maintenance') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Bookings table
CREATE TABLE IF NOT EXISTS bookings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    room_id INT UNSIGNED NULL, -- NULL if external meeting
    user_id INT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    start_time DATETIME NOT NULL,
    end_time DATETIME NOT NULL,
    participants_count INT UNSIGNED DEFAULT 0,
    phone VARCHAR(20),
    attachment_path VARCHAR(255),
    department_name VARCHAR(100),
    is_external BOOLEAN DEFAULT FALSE,
    external_org VARCHAR(255),
    status ENUM('pending', 'approved', 'rejected', 'cancelled', 'completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE SET NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_start_end (start_time, end_time),
    INDEX idx_status (status),
    INDEX idx_is_external (is_external)
) ENGINE=InnoDB;

-- Insert default meeting rooms
INSERT INTO rooms (name, capacity, location) VALUES 
('ห้องประชุมเอื้องผึ้ง (30-40 คน)', 40, ''),
('ห้องประชุมต้นข้าว(ศูนย์เสริมสุขไทย 20-30 คน)', 30, 'ศูนย์เสริมสุขไทย'),
('ห้องประชุมเอื้องหลวง (10-15คน)', 15, ''),
('ห้องประชุมเอื้องสามปอย(ข้างร้านค้าสวัสดิการ 40-50 คน)', 50, 'ข้างร้านค้าสวัสดิการ'),
('ห้องประชุมเอื้องคำ(องค์กรแพทย์) ( 10-15 คน)', 15, 'องค์กรแพทย์'),
('ห้องประชุมเอื้องเงิน(10-15คน)', 15, '');
