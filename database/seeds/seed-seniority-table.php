<?php
include "../../config.php";

try 
{
    $levels = [
        ['name' => 'Junior'],
        ['name' => 'Middle'],
        ['name' => 'Senior']
    ];

    $sqlInsert = "INSERT INTO seniority (name) 
                  VALUES (:seniority_name)";
    $stmt = $dbConn->prepare($sqlInsert);

    foreach ($levels as $level) {
        $stmt->execute([
            ':seniority_name' => $level['name']
        ]);
    }

    echo "Seniority levels seeded successfully!";

} catch (PDOException $e) {
    die("Error seeding seniority: " . $e->getMessage());
}
?>