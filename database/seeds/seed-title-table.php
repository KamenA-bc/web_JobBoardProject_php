<?php
include "../../config.php";

try 
{
    $titles = [
        ['name' => 'Software Engineer'],
        ['name' => 'Frontend Developer'],
        ['name' => 'Backend Developer'],
        ['name' => 'Full Stack Developer'],
        ['name' => 'DevOps Engineer'],
        ['name' => 'QA Engineer'],
        ['name' => 'Product Manager'],
        ['name' => 'UI/UX Designer'],
        ['name' => 'Data Scientist'],
        ['name' => 'System Administrator']
    ];

    $sqlInsert = "INSERT INTO title (name) 
                  VALUES (:title_name)";
    $stmt = $dbConn->prepare($sqlInsert);

    foreach ($titles as $title) {
        $stmt->execute([
            ':title_name' => $title['name']
        ]);
    }

    echo "Job titles seeded successfully!";

} catch (PDOException $e) {
    die("Error seeding titles: " . $e->getMessage());
}
?>