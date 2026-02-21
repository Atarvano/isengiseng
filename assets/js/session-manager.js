/**
 * Session Timeout Warning and Extension Manager
 * 
 * Manages session timeout warning modal and automatic extension.
 * Shows warning 5 minutes before session expiry.
 * Allows user to extend session before timeout.
 * 
 * Usage: Include on authenticated pages that use header.php
 * 
 * @requires Bootstrap 5 modal component
 */

// Session timeout configuration
const SESSION_TIMEOUT = 7200; // 2 hours in seconds
const WARNING_TIME = 300; // 5 minutes in seconds

// Timer references
let warningTimer = null;
let sessionTimer = null;

/**
 * Start session timers
 * Sets up warning timer and session expiry timer
 */
function startSessionTimers() {
    // Clear existing timers
    clearSessionTimers();
    
    // Calculate delay for warning (timeout - warning time)
    const warningDelay = (SESSION_TIMEOUT - WARNING_TIME) * 1000;
    const sessionDelay = SESSION_TIMEOUT * 1000;
    
    // Set warning timer - shows modal before expiry
    warningTimer = setTimeout(function() {
        showSessionWarningModal();
    }, warningDelay);
    
    // Set session expiry timer - redirects to login
    sessionTimer = setTimeout(function() {
        window.location.href = '/auth/login.php?timeout=1';
    }, sessionDelay);
}

/**
 * Clear all session timers
 */
function clearSessionTimers() {
    if (warningTimer) {
        clearTimeout(warningTimer);
        warningTimer = null;
    }
    if (sessionTimer) {
        clearTimeout(sessionTimer);
        sessionTimer = null;
    }
}

/**
 * Show session warning modal
 * Bootstrap modal that appears 5 minutes before session expiry
 */
function showSessionWarningModal() {
    // Create modal if it doesn't exist
    let modal = document.getElementById('sessionWarningModal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'sessionWarningModal';
        modal.className = 'modal fade';
        modal.tabIndex = '-1';
        modal.innerHTML = `
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-warning">
                        <h5 class="modal-title">Session Expiring Soon</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Your session will expire in 5 minutes due to inactivity.</p>
                        <p>Click "Extend Session" to continue working.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Logout</button>
                        <button type="button" class="btn btn-primary" onclick="extendSession()">Extend Session</button>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(modal);
    }
    
    // Show modal using Bootstrap
    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();
}

/**
 * Extend session
 * Calls server to reset session timeout and restarts timers
 */
async function extendSession() {
    try {
        const response = await fetch('/auth/extend_session.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({})
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('sessionWarningModal'));
            if (modal) {
                modal.hide();
            }
            
            // Restart timers
            startSessionTimers();
            
            console.log('Session extended successfully');
        } else {
            console.error('Failed to extend session:', result.message);
            // Redirect to login on failure
            window.location.href = '/auth/login.php?timeout=1';
        }
    } catch (error) {
        console.error('Error extending session:', error);
        window.location.href = '/auth/login.php?timeout=1';
    }
}

/**
 * Reset timers on user activity
 * Called on mousedown, keydown, scroll, touchstart
 */
function resetTimersOnActivity() {
    // Only reset if user is authenticated (session exists)
    if (document.cookie.includes('PHPSESSID')) {
        startSessionTimers();
    }
}

/**
 * Initialize session manager
 * Sets up event listeners and starts timers
 */
function initSessionManager() {
    // Only initialize on authenticated pages
    if (document.cookie.includes('PHPSESSID')) {
        // Start timers on page load
        startSessionTimers();
        
        // Reset timers on user activity
        document.addEventListener('mousedown', resetTimersOnActivity);
        document.addEventListener('keydown', resetTimersOnActivity);
        document.addEventListener('scroll', resetTimersOnActivity);
        document.addEventListener('touchstart', resetTimersOnActivity);
    }
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initSessionManager);
} else {
    initSessionManager();
}
