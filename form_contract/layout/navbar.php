<!-- ========================================= -->
<!-- BARRA DE NAVEGACIÓN SUPERIOR FIJA -->
<!-- ========================================= -->

<nav class="top-navbar">
    <!-- Sección Izquierda -->
    <div class="nav-left">
        <a href="../index.php" class="nav-btn nav-home" title="<?= t('home', $lang) ?>">
            🏠 <span><?= t('home', $lang) ?></span>
        </a>
        <button 
            id="togglePendingPanel" 
            class="nav-btn nav-pending" 
            title="<?= t('pending', $lang) ?>"
            aria-label="Toggle pending forms sidebar"
        >
            📋 <span><?= t('pending', $lang) ?></span>
        </button>
    </div>
    
    <!-- Sección Central -->
    <div class="nav-center">
        <h1 class="nav-title">
            <?= t('registration_form', $lang) ?>
        </h1>
    </div>
    
    <!-- Sección Derecha -->
    <div class="nav-right">
        <button 
            id="toggleCalculator" 
            class="nav-btn nav-calculator" 
            title="<?= t('calculator', $lang) ?>"
            aria-label="Toggle calculator panel"
        >
            🧮 <span><?= t('calculator', $lang) ?></span>
        </button>
        
        <!-- Selector de Idioma -->
        <a 
            href="?lang=en" 
            class="nav-btn nav-lang <?= $lang == 'en' ? 'active' : '' ?>"
            title="Switch to English"
            aria-label="Switch to English"
        >
            🇺🇸 EN
        </a>
        <a 
            href="?lang=es" 
            class="nav-btn nav-lang <?= $lang == 'es' ? 'active' : '' ?>"
            title="Cambiar a Español"
            aria-label="Cambiar a Español"
        >
            🇪🇸 ES
        </a>
    </div>
</nav>