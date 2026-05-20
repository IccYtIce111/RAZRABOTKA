<?php
class ClientRepository extends AbstractRepository {
    protected $table = 'clients';
    
    protected function getAllowedSortColumns() {
        return ['id', 'last_name', 'first_name', 'phone'];
    }
    
    public function findByPhone($phone) {
        $stmt = $this->pdo->prepare("SELECT * FROM clients WHERE phone = ?");
        $stmt->execute([$phone]);
        return $stmt->fetch();
    }
    
    public function create($data) {
        $stmt = $this->pdo->prepare("INSERT INTO clients (last_name, first_name, phone, email, birth_date) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$data['last_name'], $data['first_name'], $data['phone'], $data['email'] ?? null, $data['birth_date'] ?? null]);
        return $this->pdo->lastInsertId();
    }
    
    public function update($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE clients SET last_name=?, first_name=?, phone=?, email=?, birth_date=? WHERE id=?");
        return $stmt->execute([$data['last_name'], $data['first_name'], $data['phone'], $data['email'] ?? null, $data['birth_date'] ?? null, $id]);
    }
    
    public function hasFutureAppointments($id) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM appointments WHERE client_id = ? AND date >= CURDATE()");
        $stmt->execute([$id]);
        return $stmt->fetchColumn() > 0;
    }
    
    public function deleteWithCheck($id) {
        if ($this->hasFutureAppointments($id)) {
            throw new RepositoryException("Нельзя удалить клиента: есть будущие записи");
        }
        return $this->delete($id);
    }
}
