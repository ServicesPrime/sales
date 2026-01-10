<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <title><?= t('registration_form', $lang) ?> - <?= APP_NAME ?></title>
    
    <meta name="description" content="<?= t('complete_info', $lang) ?>">
    <meta name="author" content="<?= APP_NAME ?>">
    
    <!-- Favicon (opcional) -->
    <!-- <link rel="icon" type="image/png" href="Images/favicon.png"> -->
    
    <!-- Estilos CSS - Orden importa -->
    <link rel="stylesheet" href="styles/variables.css">
    <link rel="stylesheet" href="styles/base.css">
    <link rel="stylesheet" href="styles/navbar.css">
    <link rel="stylesheet" href="styles/sidebar.css">
    <link rel="stylesheet" href="styles/form.css">
    <link rel="stylesheet" href="styles/calculator.css">
    <link rel="stylesheet" href="styles/responsive.css">
    
    <!-- Google Fonts (opcional pero recomendado) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Preload critical resources -->
    <link rel="preload" href="styles/variables.css" as="style">
    <link rel="preload" href="styles/base.css" as="style">
    
    <!-- Exportar traducciones a JavaScript -->
    <?php export_translations_to_js(); ?>
    
    <style>
        /* Critical CSS - Inline para performance */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        
        /* Evitar FOUC (Flash of Unstyled Content) */
        .split-screen-wrapper {
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .split-screen-wrapper.loaded {
            opacity: 1;
        }
    </style>
    
    <script>
        // Prevent FOUC
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                document.querySelector('.split-screen-wrapper')?.classList.add('loaded');
            }, 100);
        });
    </script>
</head>