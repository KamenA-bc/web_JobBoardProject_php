<?php
include "config.php";

try {
    // Enable error reporting
    $dbConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "<h1>Database Setup & Seeding</h1>";

    // =========================================================
    // 1. DROP EXISTING TABLES (Reverse Dependency Order)
    // =========================================================
    echo "<h3>1. Dropping old tables...</h3>";
    
    // Disable FK checks to allow dropping tables in any order (safer)
    $dbConn->exec("SET FOREIGN_KEY_CHECKS = 0");

    $tablesToDrop = [
        'audit_logs',
        'documents',
        'application',
        'positions',
        'company',
        'users',
        'roles',
        'title',
        'seniority',
        'position_status',
        'application_status'
    ];

    foreach ($tablesToDrop as $table) {
        $dbConn->exec("DROP TABLE IF EXISTS $table");
        echo "Dropped table: $table <br>";
    }
    
    // Re-enable FK checks
    $dbConn->exec("SET FOREIGN_KEY_CHECKS = 1");


    // =========================================================
    // 2. CREATE TABLES (Schema Migration)
    // =========================================================
    echo "<hr><h3>2. Creating Tables...</h3>";

    // --- 1. Roles ---
    $dbConn->exec("CREATE TABLE IF NOT EXISTS roles (
        id INT NOT NULL AUTO_INCREMENT,
        name VARCHAR(64) NOT NULL UNIQUE,
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8");
    echo "Created table: roles <br>";

    // --- 2. Users ---
    $dbConn->exec("CREATE TABLE IF NOT EXISTS users (
        id INT NOT NULL AUTO_INCREMENT,
        username VARCHAR(128) NOT NULL UNIQUE,
        first_name VARCHAR(64) NOT NULL,
        last_name VARCHAR(64) NOT NULL,
        email VARCHAR(128) NOT NULL UNIQUE,
        password VARCHAR(256) NOT NULL,
        role_id INT NOT NULL,
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8");
    
    $dbConn->exec("ALTER TABLE users ADD CONSTRAINT FK_users_roles FOREIGN KEY (role_id) REFERENCES roles(id)");
    echo "Created table: users <br>";

    // --- 3. Company ---
    $dbConn->exec("CREATE TABLE IF NOT EXISTS company (
        id INT NOT NULL AUTO_INCREMENT,
        owner_id INT NOT NULL,
        name VARCHAR(128) NOT NULL UNIQUE,
        site_url VARCHAR(2048) NOT NULL,
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8");
    // Note: Assuming you might want a FK to users here later, but sticking to your file content for now.
    echo "Created table: company <br>";

    // --- 4. Title ---
    $dbConn->exec("CREATE TABLE IF NOT EXISTS title (
        id INT NOT NULL AUTO_INCREMENT,
        name VARCHAR(64) NOT NULL UNIQUE,
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8");
    echo "Created table: title <br>";

    // --- 5. Seniority ---
    $dbConn->exec("CREATE TABLE IF NOT EXISTS seniority (
        id INT NOT NULL AUTO_INCREMENT,
        name VARCHAR(64) NOT NULL UNIQUE,
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8");
    echo "Created table: seniority <br>";

    // --- 6. Position Status ---
    $dbConn->exec("CREATE TABLE IF NOT EXISTS position_status (
        id INT NOT NULL AUTO_INCREMENT,
        name VARCHAR(64) NOT NULL UNIQUE,
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8");
    echo "Created table: position_status <br>";

    // --- 7. Application Status ---
    $dbConn->exec("CREATE TABLE IF NOT EXISTS application_status (
        id INT NOT NULL AUTO_INCREMENT,
        name VARCHAR(64) NOT NULL UNIQUE,
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8");
    echo "Created table: application_status <br>";

    // --- 8. Positions ---
    $dbConn->exec("CREATE TABLE IF NOT EXISTS positions (
        id INT NOT NULL AUTO_INCREMENT,
        company_id INT NOT NULL,
        title_id INT NOT NULL,
        location VARCHAR(2048) NOT NULL,
        seniority_id INT NOT NULL,
        status_id INT NOT NULL,
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8");

    $dbConn->exec("ALTER TABLE positions ADD CONSTRAINT FK_positions_company FOREIGN KEY (company_id) REFERENCES company(id)");
    $dbConn->exec("ALTER TABLE positions ADD CONSTRAINT FK_positions_title FOREIGN KEY (title_id) REFERENCES title(id)");
    $dbConn->exec("ALTER TABLE positions ADD CONSTRAINT FK_positions_seniority FOREIGN KEY (seniority_id) REFERENCES seniority(id)");
    $dbConn->exec("ALTER TABLE positions ADD CONSTRAINT FK_positions_status_id FOREIGN KEY (status_id) REFERENCES position_status(id)");
    echo "Created table: positions <br>";

    // --- 9. Application ---
    $dbConn->exec("CREATE TABLE IF NOT EXISTS application (
        id INT NOT NULL AUTO_INCREMENT,
        position_id INT NOT NULL,
        user_id INT NOT NULL,
        applied_on DATE NOT NULL,
        status_id INT NOT NULL,
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8");

    $dbConn->exec("ALTER TABLE application ADD CONSTRAINT FK_application_position FOREIGN KEY (position_id) REFERENCES positions(id)");
    $dbConn->exec("ALTER TABLE application ADD CONSTRAINT FK_application_user FOREIGN KEY (user_id) REFERENCES users(id)");
    $dbConn->exec("ALTER TABLE application ADD CONSTRAINT FK_application_status FOREIGN KEY (status_id) REFERENCES application_status(id)");
    echo "Created table: application <br>";

    // --- 10. Documents ---
    $dbConn->exec("CREATE TABLE IF NOT EXISTS documents (
        id INT NOT NULL AUTO_INCREMENT,
        application_id INT NOT NULL,
        file_path VARCHAR(512) NOT NULL,
        uploaded_at DATE NOT NULL,
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8");

    $dbConn->exec("ALTER TABLE documents ADD CONSTRAINT FK_documents_application FOREIGN KEY (application_id) REFERENCES application(id) ON DELETE CASCADE");
    echo "Created table: documents <br>";

    // --- 11. Audit Logs (Table Only) ---
    $dbConn->exec("CREATE TABLE IF NOT EXISTS audit_logs (
        id INT NOT NULL AUTO_INCREMENT,
        user_id INT NOT NULL,
        action VARCHAR(10) NOT NULL,
        entity VARCHAR(32) NOT NULL,
        entity_id INT NOT NULL,
        created_at DATETIME NOT NULL,
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8");
    
    // Attempt FK for audit logs (might fail if users are deleted, but good practice)
    try {
        $dbConn->exec("ALTER TABLE audit_logs ADD CONSTRAINT FK_audit_user_id FOREIGN KEY (user_id) REFERENCES users(id)");
    } catch (Exception $e) { /* Ignore if fails */ }
    echo "Created table: audit_logs <br>";


    


    // =========================================================
    // 4. SEEDING DATA
    // =========================================================
    echo "<hr><h3>4. Seeding Data...</h3>";

    // --- Seed Roles ---
    $roles = [['name' => 'admin'], ['name' => 'user']];
    $stmt = $dbConn->prepare("INSERT INTO roles (name) VALUES (:name)");
    foreach ($roles as $row) $stmt->execute($row);
    echo "Seeded: Roles <br>";

    // --- Seed Users ---
    $users = [
        ['username' => 'admin', 'first_name' => 'Alice', 'last_name' => 'Admin', 'email' => 'alice.admin@example.com', 'password' => 'AdminPass123', 'role_id' => 1],
        ['username' => 'john', 'first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john.doe@example.com', 'password' => 'JohnPass123', 'role_id' => 2],
        ['username' => 'jane', 'first_name' => 'Jane', 'last_name' => 'Smith', 'email' => 'jane.smith@example.com', 'password' => 'JanePass123', 'role_id' => 2],
        ['username' => 'mark', 'first_name' => 'Mark', 'last_name' => 'Hunt', 'email' => 'mark.hunt@example.com', 'password' => 'MarkPass123', 'role_id' => 2],
        ['username' => 'sara', 'first_name' => 'Sara', 'last_name' => 'Lee', 'email' => 'sara.lee@example.com', 'password' => 'SaraPass123', 'role_id' => 2]
    ];
    $stmt = $dbConn->prepare("INSERT INTO users (username, first_name, last_name, email, password, role_id) VALUES (:username, :first_name, :last_name, :email, :password, :role_id)");
    foreach ($users as $user) {
        $user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);
        $stmt->execute($user);
    }
    echo "Seeded: Users <br>";

    // --- Seed Companies ---
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
    $stmt = $dbConn->prepare("INSERT INTO company (name, owner_id, site_url) VALUES (:name, :owner_id, :site_url)");
    foreach ($companies as $row) $stmt->execute($row);
    echo "Seeded: Companies <br>";

    // --- Seed Titles ---
    $titles = [
        ['name' => 'Software Engineer'], ['name' => 'Frontend Developer'], ['name' => 'Backend Developer'],
        ['name' => 'Full Stack Developer'], ['name' => 'DevOps Engineer'], ['name' => 'QA Engineer'],
        ['name' => 'Product Manager'], ['name' => 'UI/UX Designer'], ['name' => 'Data Scientist'],
        ['name' => 'System Administrator']
    ];
    $stmt = $dbConn->prepare("INSERT INTO title (name) VALUES (:name)");
    foreach ($titles as $row) $stmt->execute($row);
    echo "Seeded: Titles <br>";

    // --- Seed Seniority ---
    $levels = [['name' => 'Junior'], ['name' => 'Middle'], ['name' => 'Senior']];
    $stmt = $dbConn->prepare("INSERT INTO seniority (name) VALUES (:name)");
    foreach ($levels as $row) $stmt->execute($row);
    echo "Seeded: Seniority <br>";

    // --- Seed Position Status ---
    $p_statuses = [['name' => 'active'], ['name' => 'non_active']];
    $stmt = $dbConn->prepare("INSERT INTO position_status (name) VALUES (:name)");
    foreach ($p_statuses as $row) $stmt->execute($row);
    echo "Seeded: Position Status <br>";

    // --- Seed Application Status ---
    $a_statuses = [
        ['name' => 'applied'], ['name' => 'screening'], ['name' => 'interview'],
        ['name' => 'offer'], ['name' => 'hired'], ['name' => 'rejected']
    ];
    $stmt = $dbConn->prepare("INSERT INTO application_status (name) VALUES (:name)");
    foreach ($a_statuses as $row) $stmt->execute($row);
    echo "Seeded: Application Status <br>";

    // --- Seed Positions ---
    $positions = [
        ['company_id' => 1, 'title_id' => 1, 'location' => 'Sofia', 'seniority_id' => 1, 'status_id' => 1],
        ['company_id' => 1, 'title_id' => 2, 'location' => 'Remote', 'seniority_id' => 2, 'status_id' => 1],
        ['company_id' => 1, 'title_id' => 3, 'location' => 'Plovdiv', 'seniority_id' => 3, 'status_id' => 1],
        ['company_id' => 1, 'title_id' => 4, 'location' => 'Varna', 'seniority_id' => 2, 'status_id' => 2],
        ['company_id' => 1, 'title_id' => 5, 'location' => 'Remote', 'seniority_id' => 3, 'status_id' => 1],
        ['company_id' => 1, 'title_id' => 1, 'location' => 'Burgas', 'seniority_id' => 1, 'status_id' => 1],
        ['company_id' => 1, 'title_id' => 6, 'location' => 'Sofia', 'seniority_id' => 2, 'status_id' => 1],
        ['company_id' => 1, 'title_id' => 7, 'location' => 'Remote', 'seniority_id' => 3, 'status_id' => 1],
        ['company_id' => 1, 'title_id' => 2, 'location' => 'London', 'seniority_id' => 2, 'status_id' => 1],
        ['company_id' => 1, 'title_id' => 3, 'location' => 'Berlin', 'seniority_id' => 1, 'status_id' => 1]
    ];
    $stmt = $dbConn->prepare("INSERT INTO positions (company_id, title_id, location, seniority_id, status_id) VALUES (:company_id, :title_id, :location, :seniority_id, :status_id)");
    foreach ($positions as $row) $stmt->execute($row);
    echo "Seeded: Positions <br>";

    // --- Seed Applications ---
    $applications = [
        ['position_id' => 1, 'user_id' => 1, 'applied_on' => '2023-11-01', 'status_id' => 1],
        ['position_id' => 2, 'user_id' => 1, 'applied_on' => '2023-11-05', 'status_id' => 2],
        ['position_id' => 3, 'user_id' => 1, 'applied_on' => '2023-11-10', 'status_id' => 3],
        ['position_id' => 4, 'user_id' => 1, 'applied_on' => '2023-11-12', 'status_id' => 6],
        ['position_id' => 5, 'user_id' => 1, 'applied_on' => '2023-11-15', 'status_id' => 1],
        ['position_id' => 6, 'user_id' => 1, 'applied_on' => '2023-11-20', 'status_id' => 4],
        ['position_id' => 1, 'user_id' => 1, 'applied_on' => '2023-12-01', 'status_id' => 5],
        ['position_id' => 8, 'user_id' => 1, 'applied_on' => '2023-12-05', 'status_id' => 1]
    ];
    $stmt = $dbConn->prepare("INSERT INTO application (position_id, user_id, applied_on, status_id) VALUES (:position_id, :user_id, :applied_on, :status_id)");
    foreach ($applications as $row) $stmt->execute($row);
    echo "Seeded: Applications <br>";

    echo "<hr><strong style='color:green; font-size:1.5em;'>SUCCESS: Database completely reset, configured, and seeded!</strong>";

} catch (PDOException $e) {
    echo "<hr><strong style='color:red'>FATAL ERROR: " . $e->getMessage() . "</strong>";
}

// =========================================================
    // 3. AUDIT TRIGGERS
    // =========================================================
    echo "<hr><h3>3. Installing Audit Triggers...</h3>";
    
    $query = $dbConn->query("SHOW TABLES");
    $allTables = $query->fetchAll(PDO::FETCH_COLUMN);
    $excludedTables = ['audit_logs']; 

    foreach ($allTables as $table) 
    {
        if (in_array($table, $excludedTables)) continue;

        $trigInsert = "audit_{$table}_insert";
        $trigUpdate = "audit_{$table}_update";
        $trigDelete = "audit_{$table}_delete";

        // Insert Trigger
        $dbConn->exec("DROP TRIGGER IF EXISTS $trigInsert");
        $sqlInsert = "CREATE TRIGGER $trigInsert AFTER INSERT ON $table FOR EACH ROW BEGIN INSERT INTO audit_logs (user_id, action, entity, entity_id, created_at) VALUES (COALESCE(@audit_user_id, 1), 'INSERT', '$table', NEW.id, NOW()); END";
        $dbConn->exec($sqlInsert);

        // Update Trigger
        $dbConn->exec("DROP TRIGGER IF EXISTS $trigUpdate");
        $sqlUpdate = "CREATE TRIGGER $trigUpdate AFTER UPDATE ON $table FOR EACH ROW BEGIN INSERT INTO audit_logs (user_id, action, entity, entity_id, created_at) VALUES (COALESCE(@audit_user_id, 1), 'UPDATE', '$table', NEW.id, NOW()); END";
        $dbConn->exec($sqlUpdate);

        // Delete Trigger
        $dbConn->exec("DROP TRIGGER IF EXISTS $trigDelete");
        $sqlDelete = "CREATE TRIGGER $trigDelete AFTER DELETE ON $table FOR EACH ROW BEGIN INSERT INTO audit_logs (user_id, action, entity, entity_id, created_at) VALUES (COALESCE(@audit_user_id, 1), 'DELETE', '$table', OLD.id, NOW()); END";
        $dbConn->exec($sqlDelete);
    }
    echo "Audit triggers attached to all tables.<br>";
?>