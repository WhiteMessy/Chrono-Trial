<?php
class Leaderboard {
    private $pdo;

    public function __construct() {
        $host = '127.0.0.1';
        $dbname = 'program_oop';
        $username = 'db102128';
        $password = 'Revano102128';

        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->createTableIfNotExists();
        } catch(PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    private function createTableIfNotExists() {
        $sql = "CREATE TABLE IF NOT EXISTS leaderboard (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL,
            time VARCHAR(8) NOT NULL,
            date DATE NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        $this->pdo->exec($sql);
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM leaderboard ORDER BY time ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function add($username, $time, $date) {
        $stmt = $this->pdo->prepare("INSERT INTO leaderboard (username, time, date) VALUES (?, ?, ?)");
        return $stmt->execute([$username, $time, $date]);
    }

    public function update($id, $username, $time, $date) {
        $stmt = $this->pdo->prepare("UPDATE leaderboard SET username = ?, time = ?, date = ? WHERE id = ?");
        return $stmt->execute([$username, $time, $date, $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM leaderboard WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>