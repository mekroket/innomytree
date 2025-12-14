<?php
$host = 'localhost';
$username = 'innomist_tree';
$password = 'Oguz.438';
$dbname = 'innomist_mytree';

try {
    // Connect to MySQL server
    // Note: On shared hosting, you usually connect directly to the database.
    // We'll try connecting with the dbname specified.
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connected to database '$dbname'.<br>";

    // We don't need to create the database or USE it if we connected to it directly.
    // But for compatibility with the rest of the script which expects to just run queries:


    // Create users table
    $sql_users = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(191) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        tree_type VARCHAR(50) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql_users);
    echo "Table 'users' created.<br>";

    // Create messages table
    $sql_messages = "CREATE TABLE IF NOT EXISTS messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        tree_id INT NOT NULL,
        sender_name VARCHAR(255) NOT NULL,
        message TEXT NOT NULL,
        ornament_type VARCHAR(50) NOT NULL,
        is_read TINYINT(1) DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (tree_id) REFERENCES users(id) ON DELETE CASCADE
    )";
    $pdo->exec($sql_messages);
    echo "Table 'messages' created.<br>";

    // Create admins table
    $sql_admins = "CREATE TABLE IF NOT EXISTS admins (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL
    )";
    $pdo->exec($sql_admins);
    echo "Table 'admins' created.<br>";

    // Create default admin if not exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM admins WHERE username = 'admin'");
    $stmt->execute();
    if ($stmt->fetchColumn() == 0) {
        $admin_pass = password_hash('admin123', PASSWORD_DEFAULT); // Default password
        $stmt = $pdo->prepare("INSERT INTO admins (username, password) VALUES ('admin', ?)");
        $stmt->execute([$admin_pass]);
        echo "Default admin account created (user: admin, pass: admin123).<br>";
    }

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>
