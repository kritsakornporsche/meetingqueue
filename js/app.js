/**
 * MeetQueue Application Logic
 */

document.addEventListener('DOMContentLoaded', () => {
    // Check if we're on a view that needs data
    const urlParams = new URLSearchParams(window.location.search);
    const view = urlParams.get('view') || 'calendar';

    if (view === 'approve_list') {
        loadApproveList();
    } else if (view === 'book') {
        initBookingForm();
    } else if (view === 'external') {
        initExternalForm();
    }
});

/**
 * Load Approval List Table
 */
async function loadApproveList() {
    const tableBody = document.getElementById('approveTableBody');
    if (!tableBody) return;

    try {
        const res = await fetch('api/bookings.php');
        const data = await res.json();

        if (data.success && data.bookings.length > 0) {
            tableBody.innerHTML = data.bookings.map((b, index) => `
                <tr>
                    <td>${index + 1}</td>
                    <td>
                        <img src="https://192.168.9.7/auth_files/photo/${b.emp_code}.jpg" 
                             onerror="this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(b.first_name)}&background=f1f5f9&color=64748b'" 
                             style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 1px solid var(--border);">
                    </td>
                    <td><a href="#" class="view-detail" data-id="${b.id}" style="color: var(--primary); text-decoration: none;">${b.title}</a></td>
                    <td>${b.room_name || (b.is_external ? 'ภายนอก: ' + (b.external_org || '-') : '-')}</td>
                    <td>${formatDate(b.start_time)}</td>
                    <td>${formatTime(b.start_time)} - ${formatTime(b.end_time)}</td>
                    <td>${b.department_name || '-'}</td>
                    <td style="text-align: center;">
                        <span class="badge badge-${getStatusClass(b.status)}">${translateStatus(b.status)}</span>
                    </td>
                </tr>
            `).join('');
        } else {
            tableBody.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 2rem;">ไม่พบข้อมูลรายการอนุมัติ</td></tr>';
        }
    } catch (e) {
        console.error('Error loading list:', e);
    }
}

/**
 * Initialize Booking Form
 */
function initBookingForm() {
    const form = document.getElementById('bookingForm');
    if (!form) return;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const btn = form.querySelector('button[type="submit"]');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> กำลังบันทึก...';
        btn.disabled = true;

        const formData = new FormData();
        formData.append('room_id', document.getElementById('room_id').value);
        formData.append('title', document.getElementById('title').value);
        formData.append('start_time', `${document.getElementById('meeting_date').value} ${document.getElementById('start_time').value}`);
        formData.append('end_time', `${document.getElementById('meeting_date').value} ${document.getElementById('end_time').value}`);
        formData.append('participants_count', document.getElementById('participants_count').value);
        formData.append('phone', document.getElementById('phone').value);
        formData.append('description', document.getElementById('description').value);
        
        const attachment = document.getElementById('attachment').files[0];
        if (attachment) {
            formData.append('attachment', attachment);
        }

        try {
            const res = await fetch('api/bookings.php', {
                method: 'POST',
                body: formData
            });
            const result = await res.json();

            if (result.success) {
                alert('ส่งคำขอจองสำเร็จ!');
                window.location.href = 'dashboard.php?view=approve_list';
            } else {
                alert(result.message || 'เกิดข้อผิดพลาด');
            }
        } catch (e) {
            alert('เกิดข้อผิดพลาดในการเชื่อมต่อ');
        } finally {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    });
}

/**
 * Initialize External Meeting Form
 */
function initExternalForm() {
    const form = document.getElementById('externalMeetingForm');
    if (!form) return;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const btn = form.querySelector('button[type="submit"]');
        btn.disabled = true;

        const formData = new FormData();
        formData.append('is_external', 1);
        formData.append('external_org', document.getElementById('external_org').value);
        formData.append('title', document.getElementById('ext_title').value);
        formData.append('start_time', `${document.getElementById('ext_date').value} ${document.getElementById('ext_start_time').value}`);
        formData.append('end_time', `${document.getElementById('ext_date').value} ${document.getElementById('ext_end_time').value}`);
        formData.append('participants_count', document.getElementById('ext_participants_count').value);
        formData.append('phone', document.getElementById('ext_phone').value);
        formData.append('description', document.getElementById('ext_description').value);
        
        const attachment = document.getElementById('ext_attachment').files[0];
        if (attachment) {
            formData.append('attachment', attachment);
        }

        try {
            const res = await fetch('api/bookings.php', {
                method: 'POST',
                body: formData
            });
            const result = await res.json();

            if (result.success) {
                alert('บันทึกข้อมูลการประชุมภายนอกสำเร็จ!');
                window.location.href = 'dashboard.php?view=approve_list';
            } else {
                alert(result.message);
            }
        } catch (e) {
            alert('เกิดข้อผิดพลาด');
        } finally {
            btn.disabled = false;
        }
    });
}

/**
 * Helpers
 */
function formatDate(dateStr) {
    const d = new Date(dateStr);
    const months = ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];
    return `${d.getDate()} ${months[d.getMonth()]} ${d.getFullYear() + 543 % 100}`;
}

function formatTime(dateStr) {
    return dateStr.split(' ')[1].substring(0, 5);
}

function getStatusClass(status) {
    const classes = {
        'pending': 'warning',
        'approved': 'success',
        'rejected': 'danger',
        'cancelled': 'danger',
        'completed': 'primary'
    };
    return classes[status] || 'secondary';
}

function translateStatus(status) {
    const trans = {
        'pending': 'รออนุมัติ',
        'approved': 'อนุมัติ',
        'rejected': 'ไม่อนุมัติ',
        'cancelled': 'ยกเลิก',
        'completed': 'เสร็จสิ้น'
    };
    return trans[status] || status;
}
