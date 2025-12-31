<?php
include "../../config.php";

try {
    $applications = [
        ['position_id' => 1, 'user_id' => 1, 'applied_on' => '2023-11-01', 'status_id' => 1], // Applied
        ['position_id' => 2, 'user_id' => 1, 'applied_on' => '2023-11-05', 'status_id' => 2], // Screening
        ['position_id' => 3, 'user_id' => 1, 'applied_on' => '2023-11-10', 'status_id' => 3], // Interview
        ['position_id' => 4, 'user_id' => 1, 'applied_on' => '2023-11-12', 'status_id' => 6], // Rejected
        ['position_id' => 5, 'user_id' => 1, 'applied_on' => '2023-11-15', 'status_id' => 1], // Applied
        ['position_id' => 6, 'user_id' => 1, 'applied_on' => '2023-11-20', 'status_id' => 4], // Offer
        ['position_id' => 1, 'user_id' => 1, 'applied_on' => '2023-12-01', 'status_id' => 5], // Hired
        ['position_id' => 8, 'user_id' => 1, 'applied_on' => '2023-12-05', 'status_id' => 1]  // Applied
    ];

    $sqlInsert = "INSERT INTO application (position_id, user_id, applied_on, status_id) 
                  VALUES (:position_id, :user_id, :applied_on, :status_id)";
    
    $stmt = $dbConn->prepare($sqlInsert);

    foreach ($applications as $app) {
        $stmt->execute([
            ':position_id' => $app['position_id'],
            ':user_id'     => $app['user_id'],
            ':applied_on'  => $app['applied_on'],
            ':status_id'   => $app['status_id']
        ]);
    }

    echo "<br>Applications seeded successfully!";

} catch (PDOException $e) {
    die("Error seeding applications: " . $e->getMessage());
}
?>