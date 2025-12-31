<?php
include "../../config.php";

try 
{
    $roles = [
        ['name' => 'admin'],
        ['name' => 'user']
    ];

    $sqlInsert = "INSERT INTO roles (name) 
                  VALUES (:role_name)";
    $stmt = $dbConn->prepare($sqlInsert);

    foreach ($roles as $role) {
        $stmt->execute([
            ':role_name' => $role['name']
        ]);
    }

    echo "Roles seeded successfully!";

} catch (PDOException $e) {
    die("Error seeding roles: " . $e->getMessage());
}