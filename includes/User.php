<?php  

class User extends Database {

    private $tableName = "player";

    public function register($data)
    {
        if (!empty($data)) {
            $fields = $placeholders = [];
            foreach ($data as $field => $value) {
                $fields[] = $field;
                $placeholders[] = ":{$field}";
            }
        }
        $sql = "INSERT INTO {$this->tableName} (" . implode(',', $fields) . ",created_at) VALUES (" . implode(',', $placeholders) . ",NOW())";
        $stmt = $this->conn->prepare($sql);
        try {
            $this->conn->beginTransaction();
            $stmt->execute($data);
            //$lastInsertedId = $this->conn->lastInsertId();
            $this->conn->commit();

            return;
        } catch (PDOException $e) {
            echo "Une erreur est survenue " . $e->getMessage();
            $this->conn->rollBack();
        }
        
    }
}