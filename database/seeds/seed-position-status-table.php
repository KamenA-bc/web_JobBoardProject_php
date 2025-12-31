<?php
include "../../config.php";

try 
{
    $statuses = [
        ['name' => 'active'],
        ['name' => 'non_active']
    ];

    $sqlInsert = "INSERT INTO position_status (name) 
                  VALUES (:status_name)";
    $stmt = $dbConn->prepare($sqlInsert);

    foreach ($statuses as $status) {
        $stmt->execute([
            ':status_name' => $status['name']
        ]);
    }

    echo "Statuses seeded successfully!";

} catch (PDOException $e) {
    die("Error seeding statuses: " . $e->getMessage());
}
?>