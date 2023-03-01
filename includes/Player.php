<?php
require_once "includes/Database.php";

class Player extends Database
{

    // table name

    protected $tableName = "player";
    /**
     * function is used to add record 
     * @param array $data
     * @return int $lastInsertedId
     */

    public function add($data)
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
            $lastInsertedId = $this->conn->lastInsertId();
            $this->conn->commit();

            return $lastInsertedId;
        } catch (PDOException $e) {
            echo "Une erreur est survenue " . $e->getMessage();
            $this->conn->rollBack();
        }
    }


    /**
     * function is used to get records
     * @param int $start
     * @param int $limit
     * @return array $results
     */

    public function getRows($start = 0, $limit = 4)
    {
        $sql = "SELECT * FROM {$this->tableName} ORDER BY id DESC LIMIT {$start}, {$limit}";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $results = [];
        }
        return $results;
    }
    /**
     * function is used to get single record based on the column value
     * @param string $field
     * @param any $value
     * @return array $result
     */
    public function getRow($field, $value)
    {
        $sql = "SELECT * FROM {$this->tableName} WHERE {$field} = :{$field}";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([":{$field}" => $value]);

        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $result = [];
        }

        return $result;
    }


    /**
     * function is used to upload file
     * @param array $file
     * @return string $newFileName
     */

    public function uploadPhoto($file)
    {
        if (!empty($file)) {
            $fileTempPath = $file['tmp_name'];
            $fileName = $file["name"];
            $fileSize = $file["size"];
            $fileType = $file["type"];
            $fileNameCmps = explode('.', $fileName);
            $fileExtension = strtolower(end($fileNameCmps));
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $allowedExtension = ["jpg", "png", "jpeg", "gif"];
            if (in_array($fileExtension, $allowedExtension)) {
                $uploadFileDir = getcwd() . '/uploads/';
                $destFilePath = $uploadFileDir . $newFileName;

                if (move_uploaded_file($fileTempPath, $destFilePath)) {
                    return $newFileName;
                }
            }
        }
    }


    public function pCount( )
    {
        $sql = "SELECT COUNT(*) as pcount  FROM {$this->tableName}";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);

        return $results['pcount'];
    }

    public function update($data, $id)
    {
        if (!empty($data)) {
            $fields = "";
            $count = 1;
            $fieldCount = count($data);
            foreach ($data as $field => $value) {
                $fields .= "{$field} = :{$field}";

                if ($count < $fieldCount) {
                    $fields.=", ";
                }
                $count++;
            }
        }

        $sql = "UPDATE {$this->tableName} SET {$fields} WHERE id=:id";
        $stmt = $this->conn->prepare($sql);

        try {
            $this->conn->beginTransaction();
            $data["id"] = $id;
            $stmt->execute($data);
            $this->conn->commit();
        } catch (PDOException $e) {
            echo("Erreur ".$e->getMessage());

            $this->conn->rollBack();
        }
    }

    public function deleteRow($id)
    {

        $sql = "DELETE  FROM {$this->tableName} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(["id" => $id]);

        if ($stmt->rowCount() > 0) {
            return True;
        } else {
            return False;
        }

    }

    
}
