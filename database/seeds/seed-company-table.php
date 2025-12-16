<?php
include '../../config.php';
try 
{
$companies = [
    ['name' => 'Company',     'owner_id' => '1', 'site_url' => 'https://comp.example.com'],
    ['name' => 'TechNova',    'owner_id' => '1', 'site_url' => 'https://technova.io'],
    ['name' => 'Skyline',     'owner_id' => '1', 'site_url' => 'https://skyline.org'],
    ['name' => 'ByteLabs',    'owner_id' => '1', 'site_url' => 'https://bytelabs.net'],
    ['name' => 'GreenCode',   'owner_id' => '1', 'site_url' => 'https://greencode.dev'],
    ['name' => 'CloudEdge',   'owner_id' => '1', 'site_url' => 'https://cloudedge.io'],
    ['name' => 'DataForge',   'owner_id' => '1', 'site_url' => 'https://dataforge.tech'],
    ['name' => 'NextWave',    'owner_id' => '1', 'site_url' => 'https://nextwave.co'],
    ['name' => 'CoreSystems', 'owner_id' => '1', 'site_url' => 'https://coresystems.com'],
    ['name' => 'BrightSoft',  'owner_id' => '1', 'site_url' => 'https://brightsoft.dev'],
    ['name' => 'PixelWorks',  'owner_id' => '1', 'site_url' => 'https://pixelworks.io'],
    ['name' => 'AlphaTech',   'owner_id' => '1', 'site_url' => 'https://alphatech.ai'],
    ['name' => 'FusionIT',    'owner_id' => '1', 'site_url' => 'https://fusionit.net'],
    ['name' => 'NovaStack',   'owner_id' => '1', 'site_url' => 'https://novastack.io'],
    ['name' => 'BlueMatrix',  'owner_id' => '1', 'site_url' => 'https://bluematrix.tech'],
    ['name' => 'ZenithApps',  'owner_id' => '1', 'site_url' => 'https://zenithapps.dev'],
    ['name' => 'OrbitSoft',   'owner_id' => '1', 'site_url' => 'https://orbitsoft.io'],
    ['name' => 'VectorLabs',  'owner_id' => '1', 'site_url' => 'https://vectorlabs.ai'],
    ['name' => 'LogicFlow',   'owner_id' => '1', 'site_url' => 'https://logicflow.tech'],
    ['name' => 'CodeSphere',  'owner_id' => '1', 'site_url' => 'https://codesphere.dev'],
    ['name' => 'CodeSphere1', 'owner_id' => '2', 'site_url' => 'https://codesphere.dev1']
];


    $sqlInsert = "INSERT INTO company (name, owner_id, site_url) 
                  VALUES (:name, :owner_id, :site_url)";
    $stmt = $dbConn->prepare($sqlInsert);

    foreach ($companies as $company) {
        $stmt->execute([
            ':name' => $company['name'],
            ':owner_id' => $company['owner_id'],
            ':site_url' => $company['site_url']
        ]);
    }

    echo "Companies seeded successfully!";

} catch (PDOException $e) {
    die("Error seeding companies: " . $e->getMessage());
}