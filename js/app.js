/**
 * MeetQueue Modern Core
 * Using Module Pattern for better organization
 */
const MeetQueue = (() => {
    // Private State
    let _state = {
        bookings: [],
        rooms: [],
        currentUser: null
    };

    // Shared Utilities
    const utils = {
        formatDate: (dateStr) => {
            if (!dateStr) return '-';
            const d = new Date(dateStr + (dateStr.length === 10 ? 'T00:00:00' : ''));
            if (isNaN(d)) return dateStr;
            // th-TH uses Buddhist Era (พ.ศ.) — year is CE+543
            return d.toLocaleDateString('th-TH', { day: 'numeric', month: 'short', year: 'numeric', calendar: 'buddhist' });
        },
        formatDateLong: (dateStr) => {
            if (!dateStr) return '-';
            const d = new Date(dateStr + (dateStr.length === 10 ? 'T00:00:00' : ''));
            if (isNaN(d)) return dateStr;
            return d.toLocaleDateString('th-TH', { day: 'numeric', month: 'long', year: 'numeric', calendar: 'buddhist' });
        },
        formatTime: (dateStr) => {
            if (!dateStr) return '-';
            return dateStr.split(' ')[1]?.substring(0, 5) || dateStr.substring(0, 5);
        },
        translateStatus: (status) => {
            const trans = { 
                'pending': 'รออนุมัติ', 
                'approved': 'อนุมัติแล้ว', 
                'rejected': 'ไม่อนุมัติ', 
                'cancelled': 'ยกเลิก', 
                'completed': 'เสร็จสิ้น' 
            };
            return trans[status] || status;
        },
        getStatusClass: (status) => {
            const classes = { 
                'pending': 'warning', 
                'approved': 'success', 
                'rejected': 'danger', 
                'cancelled': 'primary', 
                'completed': 'primary' 
            };
            return classes[status] || 'primary';
        },
        escapeHtml: (unsafe) => {
            if (!unsafe) return '';
            return unsafe.toString().replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
        },
        notify: (icon, title, text) => {
            return Swal.fire({
                icon, title, text,
                confirmButtonColor: '#6A5243',
                background: '#fff',
                customClass: { popup: 'rounded-[2rem]', confirmButton: 'rounded-xl px-8 py-3' }
            });
        },
        loading: (show = true, text = 'กำลังโหลด...') => {
            if (show) {
                Swal.fire({
                    title: text,
                    allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading(); }
                });
            } else {
                Swal.close();
            }
        }
    };

    // UI Management
    const ui = {
        toggleMenu: () => {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const isOpen = sidebar?.classList.toggle('open');
            overlay?.classList.toggle('show');
            document.body.style.overflow = isOpen ? 'hidden' : '';
        },
        initDatePickers: () => {
            if (typeof flatpickr !== 'undefined') {
                flatpickr('input[type="date"]', {
                    locale: "th",
                    dateFormat: "Y-m-d", // Value stored
                    altInput: true,
                    altFormat: "d/m/Y", // Base format, replaced below
                    disableMobile: true, // Force flatpickr on mobile
                    formatDate: (date, format, locale) => {
                        let str = flatpickr.formatDate(date, format);
                        // Apply Buddhist Era for 'Y' or 'y' formats
                        if (format.indexOf('Y') !== -1) {
                            str = str.replace(date.getFullYear().toString(), (date.getFullYear() + 543).toString());
                        }
                        return str;
                    },
                    onReady: function(selectedDates, dateStr, instance) {
                        const yearInput = instance.currentYearElement;
                        if (yearInput) {
                            // 1. Intercept DOM value setter to prevent Flatpickr from writing CE year
                            const nativeInputValue = Object.getOwnPropertyDescriptor(HTMLInputElement.prototype, 'value');
                            Object.defineProperty(yearInput, 'value', {
                                get: function() {
                                    return nativeInputValue.get.call(this);
                                },
                                set: function(val) {
                                    let newVal = parseInt(val, 10);
                                    // If Flatpickr tries to write a CE year (1900-2400), convert to BE
                                    if (newVal > 1900 && newVal < 2400) {
                                        newVal += 543;
                                    }
                                    nativeInputValue.set.call(this, newVal);
                                }
                            });

                            // Initialize with BE year (triggers our setter)
                            yearInput.value = instance.currentYear;

                            // 2. Intercept Flatpickr's changeYear to handle user typing BE year
                            const origChangeYear = instance.changeYear;
                            instance.changeYear = function(year, jump, step) {
                                // If the user types a Buddhist year (e.g., 2569)
                                if (year > 2400) {
                                    year -= 543;
                                }
                                origChangeYear.call(instance, year, jump, step);
                            };
                        }
                    }
                });
            }
        }
    };

    // API Wrapper
    const api = {
        fetch: async (url, options = {}) => {
            try {
                const res = await fetch(url, options);
                let data;
                try {
                    data = await res.json();
                } catch (e) {
                    if (!res.ok) throw new Error(`HTTP ${res.status}`);
                    throw e;
                }
                
                if (!res.ok) {
                    return { success: false, message: data.message || `Error ${res.status}` };
                }
                return data;
            } catch (err) {
                console.error('API Error:', err);
                return { success: false, message: 'การเชื่อมต่อผิดพลาด: ' + err.message };
            }
        }
    };

    // Public Methods
    return {
        utils,
        ui,
        api,
        setState: (newState) => { _state = { ..._state, ...newState }; },
        getState: () => _state
    };
})();

// Global init
document.addEventListener('DOMContentLoaded', () => {
    document.body.classList.add('ready');
    
    // Bind global events
    document.getElementById('mobileMenuBtn')?.addEventListener('click', MeetQueue.ui.toggleMenu);
    document.getElementById('sidebarOverlay')?.addEventListener('click', MeetQueue.ui.toggleMenu);
    
    // Initialize custom date pickers
    MeetQueue.ui.initDatePickers();
});
