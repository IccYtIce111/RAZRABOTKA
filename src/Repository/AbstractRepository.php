<?php
abstract class AbstractRepository {
    protected $pdo;
    protected $table;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function findAll($where = [], $orderBy = '', $limit = null, $offset = null) {
        $sql = "SELECT * FROM {$this->table}";
        $params = [];
        
        if ($where) {
            $conditions = [];
            foreach ($where as $col => $val) {
                $conditions[] = "$col = ?";
                $params[] = $val;
            }
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }
        
        if ($orderBy) $sql .= " ORDER BY $orderBy";
        if ($limit) $sql .= " LIMIT $limit";
        if ($offset) $sql .= " OFFSET $offset";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    abstract protected function getAllowedSortColumns();
}
