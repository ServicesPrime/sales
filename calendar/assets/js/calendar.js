/**
 * ============================================================
 * CALENDAR JAVASCRIPT - PROFESSIONAL SYSTEM
 * Theme toggle, functional switches, improved UX
 * ============================================================ */

// ============================================================
// THEME MANAGEMENT (Dark/Light Mode)
// ============================================================

function toggleTheme() {
    const currentTheme = document.body.getAttribute('data-theme') || 'light';
    const newTheme = currentTheme === 'light' ? 'dark' : 'light';
    
    document.body.setAttribute('data-theme', newTheme);
    localStorage.setItem('theme', newTheme);
    
    console.log('Theme changed to:', newTheme);
}

// Load saved theme on page load
function loadSavedTheme() {
    const savedTheme = localStorage.getItem('theme') || 'light';
    document.body.setAttribute('data-theme', savedTheme);
}

// Initialize theme on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    loadSavedTheme();
    initializeDragAndDrop(); // Initialize drag & drop
});

// ============================================================
// FUNCTIONAL TASK SWITCHES (Status Control)
// ============================================================

function toggleTaskStatus(taskId, switchElement) {
    const isCompleting = !switchElement.classList.contains('on');
    const taskItem = switchElement.closest('.task-item');
    
    // Optimistic UI update
    if (isCompleting) {
        switchElement.classList.add('on');
        taskItem.style.opacity = '0.6';
    } else {
        switchElement.classList.remove('on');
        taskItem.style.opacity = '1';
    }
    
    // Send to server
    fetch('actions/toggle_task.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            task_id: taskId,
            is_completed: isCompleting
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (isCompleting) {
                // Animate task completion
                setTimeout(() => {
                    taskItem.style.transition = 'all 0.5s ease';
                    taskItem.style.transform = 'translateX(20px)';
                    taskItem.style.opacity = '0';
                    
                    setTimeout(() => {
                        taskItem.remove();
                        
                        // Check if task list is empty
                        const tasksList = document.querySelector('.tasks-list');
                        if (tasksList && tasksList.children.length === 0) {
                            tasksList.innerHTML = '<div class="empty-state-small">No pending tasks</div>';
                        }
                    }, 500);
                }, 800);
            }
            
            showNotification('Task updated successfully', 'success');
        } else {
            // Revert on error
            if (isCompleting) {
                switchElement.classList.remove('on');
            } else {
                switchElement.classList.add('on');
            }
            taskItem.style.opacity = '1';
            showNotification('Error updating task', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        
        // Revert on error
        if (isCompleting) {
            switchElement.classList.remove('on');
        } else {
            switchElement.classList.add('on');
        }
        taskItem.style.opacity = '1';
        showNotification('Error updating task', 'error');
    });
}

// ============================================================
// WORK STATUS SWITCHES (Event Status Control)
// ============================================================

function toggleWorkStatus(eventId, switchElement) {
    const isCompleting = !switchElement.classList.contains('on');
    const workItem = switchElement.closest('.work-item');
    
    // Optimistic UI update
    if (isCompleting) {
        switchElement.classList.add('on');
        if (workItem) workItem.style.opacity = '0.7';
    } else {
        switchElement.classList.remove('on');
        if (workItem) workItem.style.opacity = '1';
    }
    
    // Send to server
    const newStatus = isCompleting ? 'completed' : 'pending';
    
    fetch('actions/toggle_work_status.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            event_id: eventId,
            status: newStatus
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(`Work item ${isCompleting ? 'completed' : 'reopened'}`, 'success');
            
            // Optional: fade out completed items after a delay
            if (isCompleting && workItem) {
                setTimeout(() => {
                    workItem.style.transition = 'all 0.5s ease';
                    workItem.style.transform = 'translateX(20px)';
                    workItem.style.opacity = '0';
                }, 1000);
            }
        } else {
            // Revert on error
            if (isCompleting) {
                switchElement.classList.remove('on');
            } else {
                switchElement.classList.add('on');
            }
            if (workItem) workItem.style.opacity = '1';
            showNotification('Error updating status', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        
        // Revert on error
        if (isCompleting) {
            switchElement.classList.remove('on');
        } else {
            switchElement.classList.add('on');
        }
        if (workItem) workItem.style.opacity = '1';
        showNotification('Error updating status', 'error');
    });
}

// ============================================================
// WORK MODAL
// ============================================================

function openWorkModal() {
    openEventModal();
}

// ============================================================
// MODAL MANAGEMENT
// ============================================================

function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = 'auto';
    }
}

// Close modal on outside click
document.addEventListener('click', (e) => {
    if (e.target.classList.contains('modal')) {
        closeModal(e.target.id);
    }
});

// Close modal on Escape key
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        const activeModal = document.querySelector('.modal.active');
        if (activeModal) {
            closeModal(activeModal.id);
        }
    }
});

// ============================================================
// EVENT MODAL
// ============================================================

function openEventModal(eventId = null) {
    const modal = document.getElementById('eventModal');
    const form = document.getElementById('eventForm');
    const title = document.getElementById('modalTitle');
    
    // Reset form
    form.reset();
    
    if (eventId) {
        // Edit mode
        title.textContent = 'Edit Event';
        document.getElementById('eventId').value = eventId;
        
        // Load event data via AJAX
        loadEventData(eventId);
    } else {
        // Create mode
        title.textContent = 'New Event';
        document.getElementById('eventId').value = '';
        
        // Set default dates
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('eventStartDate').value = today;
        document.getElementById('eventEndDate').value = today;
    }
    
    openModal('eventModal');
}

function loadEventData(eventId) {
    fetch(`api/events.php?id=${eventId}`)
        .then(response => response.json())
        .then(data => {
            // Basic fields
            document.getElementById('eventTitle').value = data.title;
            document.getElementById('eventStartDate').value = data.start_date;
            document.getElementById('eventEndDate').value = data.end_date;
            document.getElementById('eventStartTime').value = data.start_time || '';
            document.getElementById('eventEndTime').value = data.end_time || '';
            document.getElementById('eventCategory').value = data.category_id || '';
            document.getElementById('eventLocation').value = data.location || '';
            document.getElementById('eventDescription').value = data.description || '';
            document.getElementById('eventStatus').value = data.status;
            document.getElementById('eventPriority').value = data.priority;
            document.getElementById('isAllDay').checked = data.is_all_day;
            
            // Scheduling fields
            if (document.getElementById('documentDate')) {
                document.getElementById('documentDate').value = data.document_date || '';
            }
            if (document.getElementById('executionDate')) {
                document.getElementById('executionDate').value = data.execution_date || '';
            }
            if (document.getElementById('frequencyMonths')) {
                document.getElementById('frequencyMonths').value = data.frequency_months || '';
            }
            if (document.getElementById('frequencyYears')) {
                document.getElementById('frequencyYears').value = data.frequency_years || '1';
            }
            if (document.getElementById('isReschedulable')) {
                document.getElementById('isReschedulable').checked = data.is_reschedulable == 1;
            }
            
            toggleAllDay();
        })
        .catch(error => {
            console.error('Error loading event:', error);
            showNotification('Error loading event', 'error');
        });
}

function toggleAllDay() {
    const isAllDay = document.getElementById('isAllDay').checked;
    const startTime = document.getElementById('eventStartTime');
    const endTime = document.getElementById('eventEndTime');
    
    if (isAllDay) {
        startTime.value = '';
        endTime.value = '';
        startTime.disabled = true;
        endTime.disabled = true;
        startTime.parentElement.style.opacity = '0.5';
        endTime.parentElement.style.opacity = '0.5';
    } else {
        startTime.disabled = false;
        endTime.disabled = false;
        startTime.parentElement.style.opacity = '1';
        endTime.parentElement.style.opacity = '1';
    }
}

function openEventDetail(eventId) {
    openEventModal(eventId);
}

// ============================================================
// NOTIFICATIONS
// ============================================================

function showNotification(message, type = 'info') {
    let notification = document.getElementById('flashMessage');
    
    if (!notification) {
        notification = document.createElement('div');
        notification.id = 'flashMessage';
        notification.className = 'flash-message';
        document.body.insertBefore(notification, document.body.firstChild);
    }
    
    notification.className = `flash-message flash-${type}`;
    notification.textContent = message;
    notification.style.display = 'block';
    
    setTimeout(() => {
        notification.style.animation = 'slideUp 0.3s ease-out';
        setTimeout(() => {
            notification.style.display = 'none';
        }, 300);
    }, 3000);
}

// ============================================================
// DRAG & DROP FUNCTIONALITY
// ============================================================

let draggedEventId = null;
let draggedElement = null;
let dropIndicator = null;
let monthChangeTimeout = null;

function initializeDragAndDrop() {
    createDropIndicator();
    initializeDraggableEvents();
    initializeDropZones();
    addMonthNavigationOnDrag();
    console.log('✅ Drag & Drop initialized');
}

function createDropIndicator() {
    dropIndicator = document.createElement('div');
    dropIndicator.className = 'drop-indicator';
    dropIndicator.style.cssText = `
        position: absolute;
        background: rgba(37, 99, 235, 0.1);
        border: 2px dashed #2563eb;
        border-radius: 8px;
        pointer-events: none;
        display: none;
        z-index: 1000;
    `;
    document.body.appendChild(dropIndicator);
}

function showDropIndicator(target) {
    if (!target) return;
    const rect = target.getBoundingClientRect();
    dropIndicator.style.display = 'block';
    dropIndicator.style.left = rect.left + 'px';
    dropIndicator.style.top = rect.top + 'px';
    dropIndicator.style.width = rect.width + 'px';
    dropIndicator.style.height = rect.height + 'px';
}

function hideDropIndicator() {
    dropIndicator.style.display = 'none';
}

function initializeDraggableEvents() {
    const events = document.querySelectorAll('.event-dot');
    events.forEach(event => {
        event.setAttribute('draggable', 'true');
        event.addEventListener('dragstart', handleDragStart);
        event.addEventListener('dragend', handleDragEnd);
        event.style.cursor = 'grab';
    });
    console.log(`✅ ${events.length} events made draggable`);
}

function handleDragStart(e) {
    draggedEventId = this.getAttribute('data-event-id');
    draggedElement = this;
    this.style.opacity = '0.5';
    this.style.cursor = 'grabbing';
    e.dataTransfer.effectAllowed = 'move';
    e.dataTransfer.setData('text/html', this.innerHTML);
    console.log('🎯 Dragging event:', draggedEventId);
}

function handleDragEnd(e) {
    this.style.opacity = '1';
    this.style.cursor = 'grab';
    hideDropIndicator();
    if (monthChangeTimeout) {
        clearTimeout(monthChangeTimeout);
        monthChangeTimeout = null;
    }
}

function initializeDropZones() {
    const calendarDays = document.querySelectorAll('.calendar-day');
    calendarDays.forEach(day => {
        day.addEventListener('dragover', handleDragOver);
        day.addEventListener('dragenter', handleDragEnter);
        day.addEventListener('dragleave', handleDragLeave);
        day.addEventListener('drop', handleDrop);
    });
    console.log(`✅ ${calendarDays.length} drop zones initialized`);
}

function handleDragOver(e) {
    e.preventDefault();
    e.dataTransfer.dropEffect = 'move';
    showDropIndicator(this);
    return false;
}

function handleDragEnter(e) {
    e.preventDefault();
    this.classList.add('drag-over');
}

function handleDragLeave(e) {
    this.classList.remove('drag-over');
}

function handleDrop(e) {
    e.stopPropagation();
    e.preventDefault();
    this.classList.remove('drag-over');
    hideDropIndicator();
    
    if (!draggedEventId) {
        console.error('❌ No event ID found');
        return false;
    }
    
    const newDate = this.getAttribute('data-date');
    if (!newDate) {
        console.error('❌ No date found on drop target');
        showNotification('Error: Invalid drop target', 'error');
        return false;
    }
    
    console.log('📍 Dropped on date:', newDate);
    
    if (confirm(`Reschedule event to ${formatDateForDisplay(newDate)}?`)) {
        rescheduleEvent(draggedEventId, newDate);
    }
    
    return false;
}

function rescheduleEvent(eventId, newDate) {
    console.log('🔄 Rescheduling event', eventId, 'to', newDate);
    
    fetch('actions/reschedule_event.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            event_id: eventId,
            new_date: newDate
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('✅ Event rescheduled successfully');
            showNotification('Event rescheduled successfully!', 'success');
            setTimeout(() => window.location.reload(), 500);
        } else {
            console.error('❌ Reschedule failed:', data.error);
            showNotification('Error: ' + (data.error || 'Failed to reschedule'), 'error');
        }
    })
    .catch(error => {
        console.error('❌ Network error:', error);
        showNotification('Error: Could not connect to server', 'error');
    });
}

function addMonthNavigationOnDrag() {
    document.addEventListener('dragover', function(e) {
        if (!draggedEventId) return;
        
        const x = e.clientX;
        const windowWidth = window.innerWidth;
        
        if (x < 100) {
            if (!monthChangeTimeout) {
                monthChangeTimeout = setTimeout(() => {
                    navigateToPreviousMonth();
                }, 800);
            }
        } else if (x > windowWidth - 100) {
            if (!monthChangeTimeout) {
                monthChangeTimeout = setTimeout(() => {
                    navigateToNextMonth();
                }, 800);
            }
        } else {
            if (monthChangeTimeout) {
                clearTimeout(monthChangeTimeout);
                monthChangeTimeout = null;
            }
        }
    });
}

function navigateToPreviousMonth() {
    const urlParams = new URLSearchParams(window.location.search);
    let month = parseInt(urlParams.get('m')) || new Date().getMonth() + 1;
    let year = parseInt(urlParams.get('y')) || new Date().getFullYear();
    month--;
    if (month < 1) { month = 12; year--; }
    console.log('⬅️ Previous month:', month, year);
    window.location.href = `index.php?m=${month}&y=${year}`;
}

function navigateToNextMonth() {
    const urlParams = new URLSearchParams(window.location.search);
    let month = parseInt(urlParams.get('m')) || new Date().getMonth() + 1;
    let year = parseInt(urlParams.get('y')) || new Date().getFullYear();
    month++;
    if (month > 12) { month = 1; year++; }
    console.log('➡️ Next month:', month, year);
    window.location.href = `index.php?m=${month}&y=${year}`;
}

function formatDateForDisplay(dateString) {
    const date = new Date(dateString);
    const options = { weekday: 'short', month: 'short', day: 'numeric', year: 'numeric' };
    return date.toLocaleDateString('en-US', options);
}

// ============================================================
// FORM VALIDATION
// ============================================================

document.getElementById('eventForm')?.addEventListener('submit', function(e) {
    const startDate = document.getElementById('eventStartDate').value;
    const endDate = document.getElementById('eventEndDate').value;
    
    if (new Date(endDate) < new Date(startDate)) {
        e.preventDefault();
        showNotification('End date cannot be before start date', 'error');
        return false;
    }
    
    const isAllDay = document.getElementById('isAllDay').checked;
    
    if (!isAllDay) {
        const startTime = document.getElementById('eventStartTime').value;
        const endTime = document.getElementById('eventEndTime').value;
        
        if (startDate === endDate && startTime && endTime) {
            if (endTime <= startTime) {
                e.preventDefault();
                showNotification('End time must be after start time', 'error');
                return false;
            }
        }
    }
});

// ============================================================
// AUTO-HIDE FLASH MESSAGES
// ============================================================

document.addEventListener('DOMContentLoaded', function() {
    const flashMessage = document.getElementById('flashMessage');
    
    if (flashMessage) {
        setTimeout(() => {
            flashMessage.style.animation = 'slideUp 0.3s ease-out';
            setTimeout(() => {
                flashMessage.style.display = 'none';
            }, 300);
        }, 3000);
    }
});

// ============================================================
// DATE SYNCHRONIZATION
// ============================================================

document.getElementById('eventStartDate')?.addEventListener('change', function() {
    const endDateInput = document.getElementById('eventEndDate');
    if (!endDateInput.value || endDateInput.value < this.value) {
        endDateInput.value = this.value;
    }
});

// ============================================================
// KEYBOARD SHORTCUTS
// ============================================================

document.addEventListener('keydown', function(e) {
    // Ctrl/Cmd + N: New event
    if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
        e.preventDefault();
        openEventModal();
    }
    
    // Ctrl/Cmd + D: Toggle theme
    if ((e.ctrlKey || e.metaKey) && e.key === 'd') {
        e.preventDefault();
        toggleTheme();
    }
});

// ============================================================
// UTILITY FUNCTIONS
// ============================================================

function formatDate(dateString) {
    const date = new Date(dateString);
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return date.toLocaleDateString('en-US', options);
}

function formatTime(timeString) {
    if (!timeString) return '';
    
    const [hours, minutes] = timeString.split(':');
    const hour = parseInt(hours);
    const ampm = hour >= 12 ? 'PM' : 'AM';
    const hour12 = hour % 12 || 12;
    
    return `${hour12}:${minutes} ${ampm}`;
}

// ============================================================
// CONSOLE INFO
// ============================================================

console.log('%c📅 Calendar System v2.0', 'color: #dc2626; font-size: 16px; font-weight: bold;');
console.log('%cKeyboard Shortcuts:', 'color: #78716c; font-weight: bold;');
console.log('Ctrl/Cmd + N: New Event');
console.log('Ctrl/Cmd + D: Toggle Dark/Light Mode');
console.log('Escape: Close Modal');
console.log('💡 Drag events to reschedule');
console.log('⬅️ Drag to left edge (100px) for previous month');
console.log('➡️ Drag to right edge (100px) for next month');