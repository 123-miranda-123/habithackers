CREATE TABLE tbl {
    firstName STR
    lastName STR
    userName STR
    pass STR
    joinCode INT
}
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    firstName VARCHAR(255),
    lastName VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    password VARCHAR(255),
    role VARCHAR(50) DEFAULT 'member',  -- Added column for role (team member, team captain, admin)
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
