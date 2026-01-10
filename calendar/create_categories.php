<?php
/**
 * ============================================================
 * CREATE DEFAULT CATEGORIES
 * Script para crear categorías por defecto
 * Ejecutar una sola vez
 * ============================================================
 */

require_once 'config.php';

// Verificar autenticación
requireAuth();

$userId = getCurrentUserId();
$category = new Category();

// Definir categorías por defecto
$defaultCategories = [
    // Tipos de documentos
    [
        'name' => 'JWO',
        'color' => '#3b82f6',
        'icon' => '📋',
        'is_default' => true
    ],
    [
        'name' => 'Contract',
        'color' => '#10b981',
        'icon' => '📄',
        'is_default' => false
    ],
    [
        'name' => 'Proposal',
        'color' => '#f59e0b',
        'icon' => '📊',
        'is_default' => false
    ],
    
    // Tipos de trabajo
    [
        'name' => 'Hoodvent',
        'color' => '#ef4444',
        'icon' => '🔥',
        'is_default' => false
    ],
    [
        'name' => 'Janitorial',
        'color' => '#8b5cf6',
        'icon' => '🧹',
        'is_default' => false
    ],
    [
        'name' => 'Timesheet',
        'color' => '#06b6d4',
        'icon' => '⏱️',
        'is_default' => false
    ],
    [
        'name' => 'Installation',
        'color' => '#f97316',
        'icon' => '🔧',
        'is_default' => false
    ],
    [
        'name' => 'Kitchen',
        'color' => '#ec4899',
        'icon' => '🍳',
        'is_default' => false
    ],
    [
        'name' => 'Staff',
        'color' => '#14b8a6',
        'icon' => '👥',
        'is_default' => false
    ]
];

echo "<h1>Creating Default Categories</h1>";
echo "<p>User ID: $userId</p>";
echo "<hr>";

$created = 0;
$errors = 0;

foreach ($defaultCategories as $cat) {
    echo "<p>Creating: {$cat['name']} {$cat['icon']}... ";
    
    $result = $category->create(
        $userId,
        $cat['name'],
        $cat['color'],
        $cat['icon'],
        $cat['is_default']
    );
    
    if ($result) {
        echo "<strong style='color: green;'>✓ OK (ID: $result)</strong></p>";
        $created++;
    } else {
        echo "<strong style='color: red;'>✗ ERROR</strong></p>";
        $errors++;
    }
}

echo "<hr>";
echo "<h2>Summary</h2>";
echo "<p>✓ Created: <strong>$created</strong></p>";
echo "<p>✗ Errors: <strong>$errors</strong></p>";

if ($created > 0) {
    echo "<p style='color: green; font-weight: bold;'>Categories created successfully!</p>";
    echo "<p><a href='index.php'>Go to Calendar</a></p>";
} else {
    echo "<p style='color: red;'>No categories were created. Check for errors.</p>";
}

// Mostrar categorías actuales
echo "<hr>";
echo "<h2>Current Categories</h2>";
$categories = $category->getAllByUser($userId);

if (empty($categories)) {
    echo "<p>No categories found.</p>";
} else {
    echo "<table border='1' cellpadding='10' style='border-collapse: collapse;'>";
    echo "<tr><th>ID</th><th>Icon</th><th>Name</th><th>Color</th><th>Default</th></tr>";
    
    foreach ($categories as $cat) {
        echo "<tr>";
        echo "<td>{$cat['category_id']}</td>";
        echo "<td style='font-size: 24px;'>{$cat['icon']}</td>";
        echo "<td><strong>{$cat['category_name']}</strong></td>";
        echo "<td>";
        echo "<div style='background: {$cat['color_hex']}; width: 50px; height: 20px; border-radius: 4px;'></div>";
        echo "<small>{$cat['color_hex']}</small>";
        echo "</td>";
        echo "<td>" . ($cat['is_default'] ? '✓ Yes' : 'No') . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
}
?>

<style>
    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif;
        max-width: 800px;
        margin: 40px auto;
        padding: 20px;
        background: #f5f5f5;
    }
    h1 {
        color: #333;
        border-bottom: 3px solid #3b82f6;
        padding-bottom: 10px;
    }
    table {
        width: 100%;
        background: white;
        margin-top: 20px;
    }
    th {
        background: #3b82f6;
        color: white;
        padding: 12px;
    }
    td {
        padding: 10px;
    }
    a {
        display: inline-block;
        padding: 10px 20px;
        background: #3b82f6;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        margin-top: 10px;
    }
    a:hover {
        background: #2563eb;
    }
</style>