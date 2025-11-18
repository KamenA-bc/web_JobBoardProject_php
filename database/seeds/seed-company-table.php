<?php
include '../../config.php';
try 
{
    $companies = [
        ['name' => 'Company', 'site_url' => 'https://comp.example.com'],
        ['name' => 'TechNova', 'site_url' => 'https://technova.io'],
        ['name' => 'Skyline', 'site_url' => 'https://skyline.org'],
        ['name' => 'ByteLabs', 'site_url' => 'https://bytelabs.net'],
        ['name' => 'GreenCode', 'site_url' => 'https://greencode.dev']
    ];

    $sqlInsert = "INSERT INTO company (name, site_url) 
                  VALUES (:name, :site_url)";
    $stmt = $dbConn->prepare($sqlInsert);

    foreach ($companies as $company) {
        $stmt->execute([
            ':name' => $company['name'],
            ':site_url' => $company['site_url']
        ]);
    }

    echo "Companies seeded successfully!";

} catch (PDOException $e) {
    die("Error seeding companies: " . $e->getMessage());
}