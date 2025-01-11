
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    password VARCHAR(255),
    role VARCHAR(50) DEFAULT 'member',  -- Added column for role (team member, team captain, admin)
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
