<?php
include "../../config.php";
try 
{
    $users = [
        [
            'username' => 'admin',
            'first_name' => 'Alice',
            'last_name' => 'Admin',
            'email' => 'alice.admin@example.com',
            'password' => 'AdminPass123',
            'role_id' => 1 //admin
        ],
        [
            'username' => 'john',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'password' => 'JohnPass123',
            'role_id' => 2 //user
        ],
        [
            'username' => 'jane',
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'email' => 'jane.smith@example.com',
            'password' => 'JanePass123',
            'role_id' => 2
        ],
        [
            'username' => 'mark',
            'first_name' => 'Mark',
            'last_name' => 'Hunt',
            'email' => 'mark.hunt@example.com',
            'password' => 'MarkPass123',
            'role_id' => 2
        ],
        [
            'username' => 'sara',
            'first_name' => 'Sara',
            'last_name' => 'Lee',
            'email' => 'sara.lee@example.com',
            'password' => 'SaraPass123',
            'role_id' => 2
        ]
    ];


    $sqlInsert = "INSERT INTO users (username, first_name, last_name, email, password, role_id) 
                  VALUES (:username, :first_name, :last_name, :email, :password, :role)";
    $stmt = $dbConn->prepare($sqlInsert);

    foreach ($users as $user) {
        $stmt->execute([
            ':username'   => $user['username'],
            ':first_name' => $user['first_name'],
            ':last_name'  => $user['last_name'],
            ':email'      => $user['email'],
            ':password'   => password_hash($user['password'], PASSWORD_DEFAULT), 
            ':role'       => $user['role_id']
        ]);
    }

    echo "Users seeded successfully!";

} catch (PDOException $e) {
    die("Error seeding users: " . $e->getMessage());
}

