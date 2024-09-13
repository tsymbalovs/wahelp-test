<?php

class Campaign {
    private $conn;
    private $table_name = "campaigns";

    public $id;
    public $title;
    public $message;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (title, message) VALUES (:title, :message)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':message', $this->message);

        if ($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    public function getUsersForResend() {
        $query = "SELECT u.id, u.phone_number, u.name
            FROM users u
            LEFT JOIN campaign_user_status cus ON u.id = cus.user_id AND cus.campaign_id = :campaign_id
            WHERE cus.is_sent = 0 OR cus.is_sent IS NULL";
        
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':campaign_id', $this->id);

        $stmt->execute();

        return $stmt;
    }

    public function markUserAsSent($user_id) {
        $query = "INSERT INTO campaign_user_status (campaign_id, user_id, is_sent) 
            VALUES (:campaign_id, :user_id, 1)
            ON DUPLICATE KEY UPDATE is_sent = 1";

        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':campaign_id', $this->id);
        $stmt->bindParam(':user_id', $user_id);

        return $stmt->execute();
    }
}
