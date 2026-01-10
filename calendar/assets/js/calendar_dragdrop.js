/**
 * ============================================================
 * DRAG & DROP SCHEDULING SYSTEM
 * Intelligent event rescheduling with execution date logic
 * ============================================================
 */

// ============================================================
// DRAG & DROP STATE
// ============================================================

let draggedEventId = null;
let draggedEventElement = null;
let originalDate = null;

// ============================================================
// INITIALIZE DRAG & DROP
// ============================================================

function initializeDragAndDrop() {
    // Make all event dots draggable
    document.querySelectorAll('.event-dot').forEach(eventDot => {
        eventDot.setAttribute('draggable', 'true');
        
        eventDot.addEventListener('dragstart', handleDragStart);
        eventDot.addEventListener('dragend', handleDragEnd);
    });
    
    // Make all calendar days drop targets
    document.querySelectorAll('.calendar-day:not(.empty)').forEach(day => {
        day.addEventListener('dragover', handleDragOver);
        day.addEventListener('drop', handleDrop);
        day.addEventListener('dragleave', handleDragLeave);
    });
    
    console.log('✅ Drag & Drop initialized');
}

// ============================================================
// DRAG HANDLERS
// ============================================================

function handleDragStart(e) {
    draggedEventElement = e.target;
    draggedEventId = draggedEventElement.getAttribute('data-event-id');
    originalDate = draggedEventElement.closest('.calendar-day').getAttribute('data-date');
    
    // Visual feedback
    draggedEventElement.style.opacity = '0.4';
    draggedEventElement.classList.add('dragging');
    
    // Set drag data
    e.dataTransfer.effectAllowed = 'move';
    e.dataTransfer.setData('text/html', draggedEventElement.innerHTML);
    
    console.log('Dragging event:', draggedEventId, 'from:', originalDate);
}

function handleDragEnd(e) {
    // Reset visual state
    if (draggedEventElement) {
        draggedEventElement.style.opacity = '1';
        draggedEventElement.classList.remove('dragging');
    }
    
    // Clear highlights
    document.querySelectorAll('.calendar-day').forEach(day => {
        day.classList.remove('drag-over');
    });
}

function handleDragOver(e) {
    if (e.preventDefault) {
        e.preventDefault();
    }
    
    e.dataTransfer.dropEffect = 'move';
    
    // Add visual feedback
    e.currentTarget.classList.add('drag-over');
    
    return false;
}

function handleDragLeave(e) {
    e.currentTarget.classList.remove('drag-over');
}

function handleDrop(e) {
    if (e.stopPropagation) {
        e.stopPropagation();
    }
    
    e.preventDefault();
    
    const dropTarget = e.currentTarget;
    const newDate = dropTarget.getAttribute('data-date');
    
    // Remove visual feedback
    dropTarget.classList.remove('drag-over');
    
    // Don't drop on same date
    if (newDate === originalDate) {
        console.log('Same date, no change needed');
        return false;
    }
    
    // Confirm reschedule
    const confirmed = confirm(
        `Reschedule this event to ${formatDate(newDate)}?\n\n` +
        `This will update the execution date and recalculate the next service date automatically.`
    );
    
    if (confirmed) {
        rescheduleEvent(draggedEventId, newDate, dropTarget);
    }
    
    return false;
}

// ============================================================
// RESCHEDULE EVENT (AJAX)
// ============================================================

function rescheduleEvent(eventId, newDate, dropTarget) {
    showNotification('Rescheduling event...', 'info');
    
    fetch('actions/reschedule_event.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            event_id: eventId,
            new_date: newDate
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Move the event dot to new date
            if (draggedEventElement) {
                const dayEvents = dropTarget.querySelector('.day-events');
                if (dayEvents) {
                    // Clone and move the event
                    const newEventDot = draggedEventElement.cloneNode(true);
                    dayEvents.appendChild(newEventDot);
                    
                    // Remove from original location
                    draggedEventElement.remove();
                    
                    // Reinitialize drag & drop for new element
                    initializeDragAndDrop();
                }
            }
            
            showNotification(
                `Event rescheduled successfully! ${data.next_service_info || ''}`, 
                'success'
            );
            
            // Show next service date if calculated
            if (data.next_service_date) {
                setTimeout(() => {
                    showNotification(
                        `Next service scheduled for: ${formatDate(data.next_service_date)}`,
                        'info'
                    );
                }, 2000);
            }
            
            // Optionally reload page to show updated data
            setTimeout(() => {
                window.location.reload();
            }, 3000);
            
        } else {
            showNotification(data.message || 'Error rescheduling event', 'error');
        }
    })
    .catch(error => {
        console.error('Reschedule error:', error);
        showNotification('Error rescheduling event', 'error');
    });
}

// ============================================================
// FREQUENCY CALCULATOR (Helper for UI)
// ============================================================

function parseFrequencyCode(code) {
    // Format: "03-01" = 3 months, 1 year
    if (!code || !code.includes('-')) {
        return { months: null, years: 1 };
    }
    
    const [months, years] = code.split('-').map(Number);
    return { months, years };
}

function calculateNextServiceDate(executionDate, frequencyMonths) {
    const date = new Date(executionDate);
    date.setMonth(date.getMonth() + frequencyMonths);
    return date.toISOString().split('T')[0];
}

function displayNextServiceInfo(eventId) {
    // This could be called to show next service info in a tooltip
    // or in the event detail modal
    console.log('Display next service info for event:', eventId);
}

// ============================================================
// ENHANCED EVENT MODAL (with scheduling fields)
// ============================================================

function openEventModalWithScheduling(eventId = null) {
    const modal = document.getElementById('eventModal');
    const form = document.getElementById('eventForm');
    const title = document.getElementById('modalTitle');
    
    // Reset form
    form.reset();
    
    // Show/hide scheduling fields based on event type
    toggleSchedulingFields(true);
    
    if (eventId) {
        // Edit mode
        title.textContent = 'Edit Work Item';
        document.getElementById('eventId').value = eventId;
        loadEventDataWithScheduling(eventId);
    } else {
        // Create mode
        title.textContent = 'New Work Item';
        document.getElementById('eventId').value = '';
        
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('eventStartDate').value = today;
        document.getElementById('eventEndDate').value = today;
        document.getElementById('documentDate').value = today;
    }
    
    openModal('eventModal');
}

function toggleSchedulingFields(show) {
    const schedulingFields = document.querySelectorAll('.scheduling-field');
    schedulingFields.forEach(field => {
        field.style.display = show ? 'block' : 'none';
    });
}

function loadEventDataWithScheduling(eventId) {
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
                document.getElementById('frequencyYears').value = data.frequency_years || 1;
            }
            
            // Display next service date if available
            if (data.next_service_date) {
                const nextServiceInfo = document.getElementById('nextServiceInfo');
                if (nextServiceInfo) {
                    nextServiceInfo.innerHTML = `
                        <strong>Next Service:</strong> ${formatDate(data.next_service_date)}
                        <br><small>(${data.days_until_next_service} days from now)</small>
                    `;
                    nextServiceInfo.style.display = 'block';
                }
            }
            
            toggleAllDay();
        })
        .catch(error => {
            console.error('Error loading event:', error);
            showNotification('Error loading event', 'error');
        });
}

// ============================================================
// AUTO-CALCULATE NEXT SERVICE DATE (in form)
// ============================================================

function calculateNextServiceInForm() {
    const executionDate = document.getElementById('executionDate')?.value;
    const frequencyMonths = document.getElementById('frequencyMonths')?.value;
    
    if (executionDate && frequencyMonths) {
        const nextDate = calculateNextServiceDate(executionDate, parseInt(frequencyMonths));
        
        const preview = document.getElementById('nextServicePreview');
        if (preview) {
            preview.innerHTML = `
                <small>Next service will be: <strong>${formatDate(nextDate)}</strong></small>
            `;
            preview.style.display = 'block';
        }
    }
}

// Add event listeners for auto-calculation
document.addEventListener('DOMContentLoaded', () => {
    const executionDateField = document.getElementById('executionDate');
    const frequencyMonthsField = document.getElementById('frequencyMonths');
    
    if (executionDateField) {
        executionDateField.addEventListener('change', calculateNextServiceInForm);
    }
    
    if (frequencyMonthsField) {
        frequencyMonthsField.addEventListener('change', calculateNextServiceInForm);
    }
});

// ============================================================
// FORMAT DATE (helper)
// ============================================================

function formatDate(dateString) {
    if (!dateString) return '';
    
    const date = new Date(dateString);
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return date.toLocaleDateString('en-US', options);
}

// ============================================================
// INITIALIZE ON PAGE LOAD
// ============================================================

document.addEventListener('DOMContentLoaded', () => {
    // Initialize drag & drop after a short delay to ensure DOM is ready
    setTimeout(() => {
        initializeDragAndDrop();
    }, 500);
    
    console.log('📅 Scheduling system initialized');
    console.log('💡 Drag events to reschedule them');
});

// ============================================================
// CONSOLE INFO
// ============================================================

console.log('%c📅 Intelligent Scheduling System', 'color: #10b981; font-size: 16px; font-weight: bold;');
console.log('%cFeatures:', 'color: #78716c; font-weight: bold;');
console.log('• Drag & drop to reschedule');
console.log('• Automatic next service calculation');
console.log('• Execution date tracking');
console.log('• Frequency-based scheduling');