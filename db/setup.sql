-- Create admin table for authentication
CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create profile table for portfolio information
CREATE TABLE IF NOT EXISTS profile (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    title VARCHAR(100) NOT NULL,
    about TEXT,
    email VARCHAR(100),
    phone VARCHAR(20),
    location VARCHAR(100),
    profile_picture VARCHAR(255),
    github_url VARCHAR(255),
    linkedin_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create projects table
CREATE TABLE IF NOT EXISTS projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    image VARCHAR(255) NOT NULL,
    technologies TEXT,
    project_link VARCHAR(255),
    github_link VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default admin account (password: admin123)
INSERT INTO admin (username, password) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi')
ON DUPLICATE KEY UPDATE id=id;

-- Insert default profile
INSERT INTO profile (name, title, about, email, phone, location) 
VALUES (
    'Francis Miravilla',
    'Web Developer & BSIT Student',
    'Hi, I''m an aspiring web developer with a passion for creating beautiful, functional websites. I''m currently enrolled in WMSU as a BSIT student, focused on expanding my skills in web development.',
    'usman.albrianejay@gmail.com',
    '+977 4244 540',
    'Zamboanga City, Philippines'
)
ON DUPLICATE KEY UPDATE id=id;
