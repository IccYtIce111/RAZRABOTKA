<?php
require_once 'config/config.php';
require_once 'src/Database.php';
require_once 'src/Exception/RepositoryException.php';
require_once 'src/Repository/AbstractRepository.php';
require_once 'src/Repository/ClientRepository.php';
require_once 'src/Repository/ServiceRepository.php';
require_once 'src/Repository/SpecialistRepository.php';

$pdo = Database::getConnection();
$clientRepo = new ClientRepository($pdo);

echo "<pre>";

// 1. Все клиенты
echo "=== ВСЕ КЛИЕНТЫ ===\n";
$clients = $clientRepo->findAll([], 'last_name ASC', 5);
print_r($clients);

// 2. Поиск по ID
if ($clients) {
    echo "\n=== КЛИЕНТ ПО ID ===\n";
    print_r($clientRepo->findById($clients[0]['id']));
}

// 3. Создание
echo "\n=== СОЗДАНИЕ ===\n";
$id = $clientRepo->create([
    'last_name' => 'Иванов',
    'first_name' => 'Иван',
    'phone' => '+79991112233'
]);
echo "Создан ID: $id\n";

// 4. Обновление
echo "\n=== ОБНОВЛЕНИЕ ===\n";
$clientRepo->update($id, ['first_name' => 'Петр']);
echo "Обновлён\n";

// 5. Поиск по телефону
echo "\n=== ПОИСК ПО ТЕЛЕФОНУ ===\n";
print_r($clientRepo->findByPhone('+79991112233'));

// 6. Удаление с проверкой
echo "\n=== УДАЛЕНИЕ ===\n";
try {
    $clientRepo->deleteWithCheck($id);
    echo "Удалён\n";
} catch (RepositoryException $e) {
    echo "Ошибка: " . $e->getMessage() . "\n";
}

echo "\n✅ ГОТОВО\n";
echo "</pre>";
