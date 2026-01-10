<?php
/**
 * ============================================================
 * MAIN CALENDAR PAGE - V3.0
 * Professional work management system with structured nomenclature
 * ============================================================
 */

ob_start();
require_once 'config.php';

if (!function_exists('isLoggedIn')) {
    die('ERROR: config.php not loaded correctly.');
}

if (!isLoggedIn()) {
    ob_end_clean();
    header('Location: login.php');
    exit;
}

$userId = getCurrentUserId();
$currentUser = getCurrentUser();

if (!$userId || !$currentUser) {
    logoutUser();
    ob_end_clean();
    header('Location: login.php');
    exit;
}

// Month/Year navigation
$month = isset($_GET['m']) ? intval($_GET['m']) : date('n');
$year = isset($_GET['y']) ? intval($_GET['y']) : date('Y');

if ($month > 12) { $month = 1; $year++; }
if ($month < 1) { $month = 12; $year--; }

if ($year < CALENDAR_START_YEAR) $year = CALENDAR_START_YEAR;
if ($year > CALENDAR_END_YEAR) $year = CALENDAR_END_YEAR;

$prevMonth = $month - 1;
$prevYear = $year;
$nextMonth = $month + 1;
$nextYear = $year;

if ($prevMonth < 1) { $prevMonth = 12; $prevYear--; }
if ($nextMonth > 12) { $nextMonth = 1; $nextYear++; }

$events = [];
$categories = [];
$todayEvents = [];

// Work items (JWO, Contracts, Proposals)
$workItems = [];

try {
    $event = new Event();
    $category = new Category();
    
    $events = $event->getByMonth($userId, $year, $month);
    $categories = $category->getAllByUser($userId);
    $todayEvents = $event->getToday($userId);
    
    // Get work items (events with work-related categories)
    // Filter by category type: JWO, Contract, Proposal
    $workItems = array_filter($events, function($evt) {
        $categoryName = strtoupper($evt['category_name'] ?? '');
        return in_array($categoryName, ['JWO', 'CONTRACT', 'PROPOSAL', 'HOODVENT', 'JANITORIAL']);
    });
    
} catch (Exception $e) {
    error_log("Error loading calendar data: " . $e->getMessage());
    setFlashMessage('Error loading some data. Please reload the page.', 'error');
}

$firstDay = getFirstDayOfMonth($month, $year);
$daysInMonth = getDaysInMonth($month, $year);
$monthName = date('F', strtotime("$year-$month-01"));

$flash = getFlashMessage();

// Group work items by type
$jwos = array_filter($workItems, fn($item) => stripos($item['category_name'], 'JWO') !== false);
$contracts = array_filter($workItems, fn($item) => stripos($item['category_name'], 'CONTRACT') !== false);
$proposals = array_filter($workItems, fn($item) => stripos($item['category_name'], 'PROPOSAL') !== false);

ob_end_flush();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($monthName) ?> <?= $year ?> | Work Calendar</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Crimson+Pro:wght@400;600;700&family=DM+Sans:wght@400;500;700&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="assets/css/calendar.css">
</head>
<body data-theme="light">

    <!-- Header -->
    <header class="main-header">
        <div class="header-content">
            <div class="logo">
                <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
                    <rect x="4" y="8" width="24" height="20" rx="2" stroke="currentColor" stroke-width="2"/>
                    <line x1="4" y1="14" x2="28" y2="14" stroke="currentColor" stroke-width="2"/>
                    <line x1="10" y1="5" x2="10" y2="11" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <line x1="22" y1="5" x2="22" y2="11" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <span class="logo-text">Work Calendar</span>
            </div>
            
            <div class="header-actions">
                <!-- Dark/Light Mode Toggle -->
                <button class="theme-toggle" id="themeToggle" onclick="toggleTheme()">
                    <svg class="theme-icon sun-icon" width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <circle cx="10" cy="10" r="4" stroke="currentColor" stroke-width="2"/>
                        <line x1="10" y1="2" x2="10" y2="4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <line x1="10" y1="16" x2="10" y2="18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <line x1="18" y1="10" x2="16" y2="10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <line x1="4" y1="10" x2="2" y2="10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <line x1="15.5" y1="4.5" x2="14.5" y2="5.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <line x1="5.5" y1="14.5" x2="4.5" y2="15.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <line x1="15.5" y1="15.5" x2="14.5" y2="14.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        <line x1="5.5" y1="5.5" x2="4.5" y2="4.5" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                    <svg class="theme-icon moon-icon" width="20" height="20" viewBox="0 0 20 20" fill="none">
                        <path d="M17 10.5C17 14.64 13.64 18 9.5 18C5.36 18 2 14.64 2 10.5C2 6.36 5.36 3 9.5 3C9.67 3 9.83 3.01 10 3.02C8.9 4.13 8.25 5.67 8.25 7.38C8.25 10.83 11.17 13.75 14.62 13.75C15.58 13.75 16.5 13.53 17.33 13.13C17.12 11.86 17 10.68 17 10.5Z" stroke="currentColor" stroke-width="2"/>
                    </svg>
                </button>
                
                <div class="user-info">
                    <span class="user-name"><?= e($currentUser['full_name']) ?></span>
                    <a href="logout.php" class="btn-logout">Sign Out</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Flash Message -->
    <?php if ($flash): ?>
        <div class="flash-message flash-<?= $flash['type'] ?>" id="flashMessage">
            <?= e($flash['message']) ?>
        </div>
    <?php endif; ?>

    <!-- Main Layout: Calendar | Work Sidebar -->
    <div class="calendar-layout">
        
        <!-- Main Calendar (Left) -->
        <main class="calendar-main">
            
            <!-- Calendar Header -->
            <div class="calendar-header">
                <div class="month-navigation">
                    <a href="?m=<?= $prevMonth ?>&y=<?= $prevYear ?>" class="nav-arrow">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path d="M12 4L6 10L12 16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </a>
                    
                    <h1 class="month-title">
                        <span class="month"><?= $monthName ?></span>
                        <span class="year"><?= $year ?></span>
                    </h1>
                    
                    <a href="?m=<?= $nextMonth ?>&y=<?= $nextYear ?>" class="nav-arrow">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                            <path d="M8 4L14 10L8 16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </a>
                </div>
                
                <div class="calendar-actions">
                    <a href="?m=<?= date('n') ?>&y=<?= date('Y') ?>" class="btn-today">Today</a>
                    <button class="btn-primary" onclick="openEventModal()">+ New Event</button>
                </div>
            </div>

            <!-- Calendar Grid -->
            <div class="calendar-grid">
                
                <!-- Day Headers -->
                <?php
                $dayNames = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                foreach ($dayNames as $dayName):
                ?>
                    <div class="day-header"><?= $dayName ?></div>
                <?php endforeach; ?>

                <!-- Empty cells before first day -->
                <?php for ($i = 0; $i < $firstDay; $i++): ?>
                    <div class="calendar-day empty"></div>
                <?php endfor; ?>

                <!-- Days of the month -->
                <?php
                $today = date('Y-m-d');
                for ($day = 1; $day <= $daysInMonth; $day++):
                    $date = sprintf('%04d-%02d-%02d', $year, $month, $day);
                    $isToday = ($date === $today);
                    $dayEvents = array_filter($events, fn($e) => $e['start_date'] === $date);
                ?>
                    <div class="calendar-day <?= $isToday ? 'today' : '' ?>" 
                         data-date="<?= $date ?>"
                         onclick="console.log('Day clicked: <?= $date ?>')">
                        
                        <div class="day-number"><?= $day ?></div>
                        
                        <div class="day-events">
                            <?php foreach ($dayEvents as $evt): ?>
                                <div class="event-dot" 
                                     data-event-id="<?= $evt['event_id'] ?>"
                                      data-event-date="<?= $evt['start_date'] ?>"
                                      data-reschedulable="<?= $evt['is_reschedulable'] ?? 1 ?>"
                                      style="--event-color: <?= e($evt['color_hex'] ?? '#2563eb') ?>"
                                      title="<?= e($evt['title']) ?>"
                                      draggable="true"
                                     onclick="event.stopPropagation(); openEventDetail(<?= $evt['event_id'] ?>)">
                                 <span class="event-label"><?= e($evt['title']) ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>
        </main>

        <!-- Right Sidebar: Today + Work/Jobs -->
        <aside class="sidebar-right">
            
            <!-- Today Section -->
            <div class="today-section work-section">
                <div class="section-header">
                    <h2>Today</h2>
                    <span class="date-badge"><?= date('d') ?></span>
                </div>
                
                <?php if (empty($todayEvents)): ?>
                    <div class="empty-state-small">
                        No events today
                    </div>
                <?php else: ?>
                    <div class="today-events">
                        <?php foreach ($todayEvents as $evt): ?>
                            <div class="today-event work-item" style="--event-color: <?= e($evt['color_hex'] ?? '#2563eb') ?>">
                                <div class="event-details">
                                    <div class="event-title"><?= e($evt['title']) ?></div>
                                    <div class="event-meta">
                                        <span class="event-time">
                                            <?= $evt['start_time'] ? formatTime12h($evt['start_time']) : 'All day' ?>
                                        </span>
                                        <?php if ($evt['category_name']): ?>
                                            <span class="event-category"><?= e($evt['category_name']) ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <!-- Status Switch -->
                                <div class="work-status">
                                    <label class="switch-label" title="Mark as completed">
                                        <div class="switch <?= $evt['status'] === 'completed' ? 'on' : '' ?>" 
                                             onclick="toggleWorkStatus(<?= $evt['event_id'] ?>, this)">
                                            <div class="switch-track"></div>
                                            <div class="switch-thumb"></div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Work/Jobs Section -->
            <div class="work-section">
                <div class="section-header">
                    <h2>Work / Jobs</h2>
                    <button class="btn-add" onclick="openWorkModal()">+</button>
                </div>
                
                <!-- JWO Section -->
                <div class="work-category">
                    <div class="work-category-header">
                        <span class="work-badge jwo">JWO</span>
                        <span class="work-count"><?= count($jwos) ?></span>
                    </div>
                    <div class="work-items">
                        <?php if (empty($jwos)): ?>
                            <div class="empty-state-micro">No JWOs</div>
                        <?php else: ?>
                            <?php foreach (array_slice($jwos, 0, 3) as $jwo): ?>
                                <div class="work-item" onclick="openEventDetail(<?= $jwo['event_id'] ?>)">
                                    <div class="work-code"><?= e($jwo['title']) ?></div>
                                    <div class="work-meta">
                                        <span class="work-date"><?= date('M d', strtotime($jwo['start_date'])) ?></span>
                                        <!-- Status Switch -->
                                        <div class="work-switch switch <?= $jwo['status'] === 'completed' ? 'on' : '' ?>" 
                                             onclick="event.stopPropagation(); toggleWorkStatus(<?= $jwo['event_id'] ?>, this)">
                                            <div class="knob"></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Contracts Section -->
                <div class="work-category">
                    <div class="work-category-header">
                        <span class="work-badge contract">Contracts</span>
                        <span class="work-count"><?= count($contracts) ?></span>
                    </div>
                    <div class="work-items">
                        <?php if (empty($contracts)): ?>
                            <div class="empty-state-micro">No contracts</div>
                        <?php else: ?>
                            <?php foreach (array_slice($contracts, 0, 3) as $contract): ?>
                                <div class="work-item" onclick="openEventDetail(<?= $contract['event_id'] ?>)">
                                    <div class="work-code"><?= e($contract['title']) ?></div>
                                    <div class="work-meta">
                                        <span class="work-date"><?= date('M d', strtotime($contract['start_date'])) ?></span>
                                        <div class="work-switch switch <?= $contract['status'] === 'completed' ? 'on' : '' ?>" 
                                             onclick="event.stopPropagation(); toggleWorkStatus(<?= $contract['event_id'] ?>, this)">
                                            <div class="knob"></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Proposals Section -->
                <div class="work-category">
                    <div class="work-category-header">
                        <span class="work-badge proposal">Proposals</span>
                        <span class="work-count"><?= count($proposals) ?></span>
                    </div>
                    <div class="work-items">
                        <?php if (empty($proposals)): ?>
                            <div class="empty-state-micro">No proposals</div>
                        <?php else: ?>
                            <?php foreach (array_slice($proposals, 0, 3) as $proposal): ?>
                                <div class="work-item" onclick="openEventDetail(<?= $proposal['event_id'] ?>)">
                                    <div class="work-code"><?= e($proposal['title']) ?></div>
                                    <div class="work-meta">
                                        <span class="work-date"><?= date('M d', strtotime($proposal['start_date'])) ?></span>
                                        <div class="work-switch switch <?= $proposal['status'] === 'completed' ? 'on' : '' ?>" 
                                             onclick="event.stopPropagation(); toggleWorkStatus(<?= $proposal['event_id'] ?>, this)">
                                            <div class="knob"></div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Nomenclature Reference -->
                <div class="nomenclature-info">
                    <div class="nomenclature-title">📋 Nomenclature</div>
                    <div class="nomenclature-example">
                        <code>JWO-H100012302025-03-01</code>
                    </div>
                    <div class="nomenclature-breakdown">
                        <div class="nom-item">
                            <span class="nom-label">JWO</span>
                            <span class="nom-desc">Job Work Order</span>
                        </div>
                        <div class="nom-item">
                            <span class="nom-label">H</span>
                            <span class="nom-desc">Hoodvent</span>
                        </div>
                        <div class="nom-item">
                            <span class="nom-label">1000</span>
                            <span class="nom-desc">Doc #</span>
                        </div>
                        <div class="nom-item">
                            <span class="nom-label">12302025</span>
                            <span class="nom-desc">Date</span>
                        </div>
                        <div class="nom-item">
                            <span class="nom-label">03-01</span>
                            <span class="nom-desc">Frequency</span>
                        </div>
                    </div>
                    <div class="nomenclature-codes">
                        <strong>Work Types:</strong>
                        <div class="codes-grid">
                            <span class="code">H = Hoodvent</span>
                            <span class="code">J = Janitorial</span>
                            <span class="code">T = Timesheet</span>
                            <span class="code">I = Installation</span>
                            <span class="code">K = Kitchen</span>
                            <span class="code">S = Staff</span>
                        </div>
                    </div>
                </div>
            </div>
        </aside>
    </div>

    <!-- Event Modal (with nomenclature support) -->
    <!-- Enhanced Event Modal with Scheduling Fields -->
<!-- Replace the existing eventModal in index.php with this -->

<div id="eventModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitle">New Work Item</h2>
            <button class="btn-close" onclick="closeModal('eventModal')">&times;</button>
        </div>
        
        <form id="eventForm" action="actions/save_event.php" method="POST">
            <input type="hidden" name="event_id" id="eventId">
            <input type="hidden" name="user_id" value="<?= $userId ?>">
            
            <!-- Work Code / Title -->
            <div class="form-group">
                <label for="eventTitle">Work Code / Title *</label>
                <input type="text" id="eventTitle" name="title" 
                       placeholder="e.g., JWO-H100012302025-03-01" 
                       required>
                <small class="form-hint">Use official nomenclature for work items</small>
            </div>
            
            <!-- Document Date (when signed) -->
            <div class="form-group">
                <label for="documentDate">Document Date (Signed)</label>
                <input type="date" id="documentDate" name="document_date">
                <small class="form-hint">When the contract/proposal was signed</small>
            </div>
            
            <!-- Scheduled vs Execution Dates -->
            <div class="form-row">
                <div class="form-group">
                    <label for="eventStartDate">Scheduled Date *</label>
                    <input type="date" id="eventStartDate" name="start_date" required>
                    <small class="form-hint">Originally scheduled</small>
                </div>
                
                <div class="form-group">
                    <label for="executionDate">Execution Date</label>
                    <input type="date" id="executionDate" name="execution_date">
                    <small class="form-hint">When actually executed</small>
                </div>
            </div>
            
            <!-- End Date -->
            <div class="form-group">
                <label for="eventEndDate">End Date *</label>
                <input type="date" id="eventEndDate" name="end_date" required>
            </div>
            
            <!-- Time Fields -->
            <div class="form-row">
                <div class="form-group">
                    <label for="eventStartTime">Start Time</label>
                    <input type="time" id="eventStartTime" name="start_time">
                </div>
                
                <div class="form-group">
                    <label for="eventEndTime">End Time</label>
                    <input type="time" id="eventEndTime" name="end_time">
                </div>
            </div>
            
            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_all_day" id="isAllDay" onchange="toggleAllDay()">
                    <span>All day event</span>
                </label>
            </div>
            
            <!-- Frequency (for recurring services) -->
            <div class="frequency-section">
                <div class="form-row">
                    <div class="form-group">
                        <label for="frequencyMonths">Frequency (Months)</label>
                        <select id="frequencyMonths" name="frequency_months">
                            <option value="">No recurrence</option>
                            <option value="1">Monthly</option>
                            <option value="3">Quarterly (3 months)</option>
                            <option value="6">Biannual (6 months)</option>
                            <option value="12">Annual (12 months)</option>
                        </select>
                        <small class="form-hint">Service frequency</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="frequencyYears">Contract Duration (Years)</label>
                        <select id="frequencyYears" name="frequency_years">
                            <option value="1" selected>1 year</option>
                            <option value="2">2 years</option>
                            <option value="3">3 years</option>
                            <option value="5">5 years</option>
                        </select>
                    </div>
                </div>
                
                <!-- Next Service Preview -->
                <div id="nextServicePreview" class="next-service-preview" style="display: none;">
                    <!-- Calculated dynamically -->
                </div>
            </div>
            
            <!-- Next Service Date (read-only, calculated) -->
            <div id="nextServiceInfo" class="next-service-info" style="display: none;">
                <!-- Shown when editing existing event with next service date -->
            </div>
            
            <!-- Work Type / Category -->
            <div class="form-group">
                <label for="eventCategory">Work Type / Category *</label>
                <select id="eventCategory" name="category_id" required>
                    <option value="">Select work type...</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['category_id'] ?>">
                            <?= e($cat['icon']) ?> <?= e($cat['category_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <!-- Location -->
            <div class="form-group">
                <label for="eventLocation">Location</label>
                <input type="text" id="eventLocation" name="location">
            </div>
            
            <!-- Description -->
            <div class="form-group">
                <label for="eventDescription">Description / Notes</label>
                <textarea id="eventDescription" name="description" rows="3"></textarea>
            </div>
            
            <!-- Status & Priority -->
            <div class="form-row">
                <div class="form-group">
                    <label for="eventStatus">Status</label>
                    <select id="eventStatus" name="status">
                        <option value="pending">Pending</option>
                        <option value="confirmed" selected>Confirmed</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="eventPriority">Priority</label>
                    <select id="eventPriority" name="priority">
                        <option value="low">Low</option>
                        <option value="normal" selected>Normal</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                    </select>
                </div>
            </div>
            
            <!-- Reschedulable Flag -->
            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_reschedulable" id="isReschedulable" checked>
                    <span>Allow drag & drop rescheduling</span>
                </label>
                <small class="form-hint">Uncheck to prevent accidental rescheduling</small>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeModal('eventModal')">Cancel</button>
                <button type="submit" class="btn-primary">Save Work Item</button>
            </div>
        </form>
    </div>
</div>

<!-- Add these styles to calendar.css -->
<style>
.next-service-preview {
    margin-top: var(--spacing-sm);
    padding: var(--spacing-sm);
    background: var(--color-accent-light);
    border-radius: var(--radius-sm);
    border-left: 3px solid var(--color-accent);
}

.next-service-info {
    margin-top: var(--spacing-sm);
    padding: var(--spacing-md);
    background: #ecfdf5;
    border-radius: var(--radius-md);
    border-left: 3px solid var(--color-success);
    color: #065f46;
}

[data-theme="dark"] .next-service-info {
    background: #14532d;
    color: #86efac;
}
</style>

/* ============================================================
   SCHEDULING FIELDS - ALWAYS VISIBLE
   ============================================================ */
.frequency-section {
    display: block !important;
    margin: 20px 0;
    padding: 15px;
    background: #f9fafb;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

[data-theme="dark"] .frequency-section {
    background: #1f2937;
    border-color: #374151;
}

.frequency-section .form-hint {
    color: #6b7280;
    font-size: 12px;
}

    <script src="assets/js/calendar.js"></script>
    <!-- Agregar DESPUÉS de calendar.js -->
    <script src="assets/js/nomenclature_autofill.js"></script>
    
    <!-- DELETE EVENT SYSTEM - INLINE -->
    <script>
    (function() {
        'use strict';
        
        console.log('🗑️ Initializing delete system...');
        
        // Agregar botón de borrar al modal de evento
        function addDeleteButton() {
            const observer = new MutationObserver(function() {
                const modal = document.getElementById('eventModal');
                const eventIdInput = document.getElementById('eventId');
                
                if (modal && modal.classList.contains('active') && eventIdInput && eventIdInput.value) {
                    const eventId = parseInt(eventIdInput.value);
                    
                    // Solo agregar si no existe
                    if (!document.getElementById('btnDeleteEvent')) {
                        const titleInput = document.getElementById('eventTitle');
                        const eventTitle = titleInput ? titleInput.value : 'this event';
                        
                        // Buscar donde insertar el botón
                        const modalBody = modal.querySelector('.modal-body');
                        
                        if (modalBody) {
                            const deleteBtn = document.createElement('button');
                            deleteBtn.id = 'btnDeleteEvent';
                            deleteBtn.type = 'button';
                            deleteBtn.textContent = '🗑️ Delete Event';
                            deleteBtn.className = 'btn-delete-event';
                            deleteBtn.style.cssText = `
                                background: #dc2626;
                                color: white;
                                padding: 10px 20px;
                                border: none;
                                border-radius: 6px;
                                cursor: pointer;
                                font-weight: 500;
                                margin: 0 0 20px 0;
                                display: block;
                                width: 100%;
                            `;
                            
                            deleteBtn.onmouseover = function() {
                                this.style.background = '#b91c1c';
                            };
                            
                            deleteBtn.onmouseout = function() {
                                this.style.background = '#dc2626';
                            };
                            
                            deleteBtn.onclick = function() {
                                confirmDelete(eventId, eventTitle);
                            };
                            
                            // Insertar al inicio del modal body
                            modalBody.insertBefore(deleteBtn, modalBody.firstChild);
                            
                            console.log('✅ Delete button added for event', eventId);
                        }
                    }
                } else {
                    // Remover botón si modal se cierra
                    const btn = document.getElementById('btnDeleteEvent');
                    if (btn) {
                        btn.remove();
                        console.log('🗑️ Delete button removed');
                    }
                }
            });
            
            observer.observe(document.body, {
                childList: true,
                subtree: true,
                attributes: true,
                attributeFilter: ['class']
            });
            
            console.log('✅ Delete button observer active');
        }
        
        // Confirmar borrado
        window.confirmDelete = function(eventId, title) {
            console.log('🗑️ Confirm delete:', eventId, title);
            
            // Verificar si es serie
            checkSeries(title, function(isSeries, count) {
                showDeleteModal(eventId, title, isSeries, count);
            });
        };
        
        // Verificar si es parte de una serie
        function checkSeries(title, callback) {
            let count = 0;
            
            // Contar en el DOM
            const selectors = ['.event-dot', '.work-item', '.today-event'];
            selectors.forEach(selector => {
                document.querySelectorAll(selector).forEach(el => {
                    const elTitle = el.getAttribute('title') || 
                                   el.querySelector('.event-label')?.textContent ||
                                   el.querySelector('.event-title')?.textContent;
                    
                    if (elTitle && elTitle.trim() === title.trim()) {
                        count++;
                    }
                });
            });
            
            console.log('Events with same title:', count);
            callback(count > 1, count);
        }
        
        // Mostrar modal de confirmación
        function showDeleteModal(eventId, title, isSeries, count) {
            // Remover modal anterior
            const oldModal = document.getElementById('deleteConfirmModal');
            if (oldModal) oldModal.remove();
            
            const modal = document.createElement('div');
            modal.id = 'deleteConfirmModal';
            modal.className = 'modal active';
            modal.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.6);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 10000;
            `;
            
            const content = document.createElement('div');
            content.style.cssText = `
                background: white;
                border-radius: 12px;
                padding: 24px;
                max-width: 500px;
                width: 90%;
                box-shadow: 0 20px 25px -5px rgba(0,0,0,0.3);
            `;
            
            content.innerHTML = `
                <h2 style="margin: 0 0 16px 0; font-size: 20px; color: #111827;">Delete Event</h2>
                
                <p style="margin-bottom: 12px; color: #374151;"><strong>Event:</strong> ${title}</p>
                
                ${isSeries ? `
                    <div style="background: #fef3c7; padding: 12px; border-radius: 6px; margin-bottom: 16px; border-left: 3px solid #f59e0b;">
                        <p style="margin: 0; color: #92400e; font-size: 14px;">
                            ⚠️ <strong>Recurring event</strong><br>
                            <span style="font-size: 13px;">${count} occurrence${count > 1 ? 's' : ''} found</span>
                        </p>
                    </div>
                    
                    <p style="margin-bottom: 16px; color: #374151; font-size: 14px;">What would you like to delete?</p>
                    
                    <div style="display: flex; flex-direction: column; gap: 10px;">
                        <button id="deleteOne" style="padding: 12px 20px; background: #6b7280; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 500; transition: background 0.2s;">
                            📅 Delete this occurrence only
                        </button>
                        <button id="deleteSeries" style="padding: 12px 20px; background: #dc2626; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 500; transition: background 0.2s;">
                            🗑️ Delete entire series (${count} events)
                        </button>
                    </div>
                ` : `
                    <p style="margin-bottom: 20px; color: #6b7280; font-size: 14px;">This action cannot be undone.</p>
                    
                    <div style="display: flex; gap: 12px; justify-content: flex-end;">
                        <button id="cancelDelete" style="padding: 10px 20px; background: #e5e7eb; color: #374151; border: none; border-radius: 6px; cursor: pointer; font-weight: 500;">
                            Cancel
                        </button>
                        <button id="confirmDelete" style="padding: 10px 20px; background: #dc2626; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 500;">
                            Delete Event
                        </button>
                    </div>
                `}
            `;
            
            modal.appendChild(content);
            document.body.appendChild(modal);
            
            // Event listeners
            if (isSeries) {
                document.getElementById('deleteOne').onclick = () => executeDelete(eventId, false);
                document.getElementById('deleteSeries').onclick = () => executeDelete(eventId, true);
            } else {
                document.getElementById('cancelDelete').onclick = closeDeleteModal;
                document.getElementById('confirmDelete').onclick = () => executeDelete(eventId, false);
            }
            
            // Cerrar al hacer click fuera
            modal.onclick = function(e) {
                if (e.target === modal) closeDeleteModal();
            };
        }
        
        // Cerrar modal de confirmación
        window.closeDeleteModal = function() {
            const modal = document.getElementById('deleteConfirmModal');
            if (modal) {
                modal.remove();
                console.log('🗑️ Delete modal closed');
            }
        };
        
        // Ejecutar borrado
        window.executeDelete = function(eventId, deleteSeries) {
            console.log(`🗑️ Deleting event ${eventId}, series: ${deleteSeries}`);
            
            fetch('actions/delete_event.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({
                    event_id: eventId,
                    delete_series: deleteSeries
                })
            })
            .then(response => response.json())
            .then(data => {
                closeDeleteModal();
                
                if (data.success) {
                    const count = data.deleted_count || 1;
                    const message = deleteSeries 
                        ? `Series deleted: ${count} event${count > 1 ? 's' : ''} removed`
                        : 'Event deleted successfully';
                    
                    showNotification(message, 'success');
                    
                    // Cerrar modal de evento
                    const eventModal = document.getElementById('eventModal');
                    if (eventModal) {
                        closeModal('eventModal');
                    }
                    
                    // Recargar página
                    setTimeout(() => location.reload(), 600);
                } else {
                    showNotification('Error: ' + (data.error || 'Failed to delete'), 'error');
                }
            })
            .catch(error => {
                console.error('Delete error:', error);
                closeDeleteModal();
                showNotification('Error: Connection failed', 'error');
            });
        };
        
        // Inicializar
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', addDeleteButton);
        } else {
            addDeleteButton();
        }
        
        console.log('✅ Delete system loaded');
        
    })();
    </script>
    <script src="assets/js/delete_event_ui.js"></script>

    <?php if (ENVIRONMENT === 'development'): ?>
    <script>
        console.log('✅ Work Calendar loaded');
        console.log('User:', <?= json_encode($currentUser) ?>);
        console.log('Events this month:', <?= count($events) ?>);
        console.log('Work items:', {
            jwos: <?= count($jwos) ?>,
            contracts: <?= count($contracts) ?>,
            proposals: <?= count($proposals) ?>
        });
    </script>
    <?php endif; ?>
    
</body>
</html>