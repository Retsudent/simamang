<?php
/**
 * Add updated_at columns to admin, pembimbing, and siswa tables
 * This script will add the missing updated_at columns for PostgreSQL
 */

echo "=== ADDING UPDATED_AT COLUMNS TO POSTGRESQL TABLES ===\n\n";

try {
    // Database connection for PostgreSQL
    $host = 'localhost';
    $dbname = 'simamang';
    $username = 'postgres';
    $password = 'postgres';
    
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Connected to PostgreSQL database\n\n";
    
    // Check if tables exist and add updated_at columns
    $tables = ['admin', 'pembimbing', 'siswa'];
    
    foreach ($tables as $table) {
        echo "Processing table: $table\n";
        
        // Check if table exists
        $stmt = $pdo->query("SELECT EXISTS (SELECT FROM information_schema.tables WHERE table_name = '$table')");
        $tableExists = $stmt->fetchColumn();
        
        if (!$tableExists) {
            echo "❌ Table $table does not exist\n";
            continue;
        }
        
        echo "✅ Table $table exists\n";
        
        // Check if updated_at column exists
        $stmt = $pdo->query("SELECT EXISTS (SELECT FROM information_schema.columns WHERE table_name = '$table' AND column_name = 'updated_at')");
        $columnExists = $stmt->fetchColumn();
        
        if ($columnExists) {
            echo "✅ Column updated_at already exists in $table\n";
        } else {
            // Add updated_at column
            $sql = "ALTER TABLE $table ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP";
            $pdo->exec($sql);
            echo "✅ Added updated_at column to $table\n";
            
            // Create trigger to automatically update updated_at
            $triggerName = "update_${table}_updated_at";
            $functionName = "update_${table}_updated_at_function";
            
            // Create function
            $functionSql = "
                CREATE OR REPLACE FUNCTION $functionName()
                RETURNS TRIGGER AS $$
                BEGIN
                    NEW.updated_at = CURRENT_TIMESTAMP;
                    RETURN NEW;
                END;
                $$ LANGUAGE plpgsql;
            ";
            $pdo->exec($functionSql);
            
            // Create trigger
            $triggerSql = "
                CREATE TRIGGER $triggerName
                BEFORE UPDATE ON $table
                FOR EACH ROW
                EXECUTE FUNCTION $functionName();
            ";
            $pdo->exec($triggerSql);
            
            echo "✅ Created trigger for automatic updated_at update in $table\n";
        }
        
        echo "\n";
    }
    
    // Verify the changes
    echo "=== VERIFICATION ===\n";
    foreach ($tables as $table) {
        $stmt = $pdo->query("SELECT column_name, data_type, is_nullable, column_default 
                             FROM information_schema.columns 
                             WHERE table_name = '$table' 
                             ORDER BY ordinal_position");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "Table: $table\n";
        foreach ($columns as $column) {
            echo "  - {$column['column_name']}: {$column['data_type']} " . 
                 ($column['is_nullable'] === 'YES' ? 'NULL' : 'NOT NULL') .
                 ($column['column_default'] ? " DEFAULT {$column['column_default']}" : '') . "\n";
        }
        echo "\n";
    }
    
    echo "✅ All updated_at columns have been added successfully!\n";
    echo "✅ Triggers have been created for automatic timestamp updates.\n";
    
} catch (PDOException $e) {
    echo "❌ Database Error: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== SCRIPT COMPLETE ===\n";
?>
