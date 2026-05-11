<?php
$bookingId = $_GET['id'] ?? null;

if (!$bookingId) {
    echo "<div class='text-center py-20 text-red-500 font-bold'>ไม่พบรหัสการจอง</div>";
    return;
}
?>

<div class="max-w-[720px] mx-auto py-10 px-4 md:px-8">
    <!-- Result Header & Timeline Tracker -->
    <div class="text-center mb-8">
        <div id="statusIconContainer" class="w-20 h-20 rounded-full bg-[#EBE6DA] text-[#A79A8B] flex items-center justify-center text-3xl mx-auto mb-5 shadow-md transition-all duration-500">
            <i class="fas fa-circle-notch fa-spin"></i>
        </div>
        <h2 id="resultTitle" class="text-2xl font-extrabold text-[#6A5243] mb-2">กำลังโหลดข้อมูล...</h2>
        <p id="resultSubtitle" class="text-sm text-[#A79A8B] font-medium mb-7">กรุณารอสักครู่</p>

        <!-- Status Timeline Tracker -->
        <div id="timelineContainer" class="hidden max-w-lg mx-auto">
            <div class="flex items-center justify-between relative">
                <div class="absolute left-[10%] right-[10%] top-[20px] h-[3px] bg-[#EBE6DA] z-0 rounded-full"></div>
                <div id="timelineProgress" class="absolute left-[10%] top-[20px] h-[3px] bg-[#6A5243] z-0 rounded-full transition-all duration-1000 w-0"></div>
                <div class="relative z-10 flex flex-col items-center w-1/4">
                    <div id="step1-icon" class="w-10 h-10 rounded-full flex items-center justify-center text-white bg-[#A79A8B] border-[3px] border-[#F3F0E6] shadow-sm transition-colors duration-500">
                        <i class="fas fa-paper-plane text-xs"></i>
                    </div>
                    <span class="mt-1.5 text-[0.6rem] font-bold text-[#A79A8B] leading-tight text-center" id="step1-text">ยังไม่อนุมัติ</span>
                </div>
                <div class="relative z-10 flex flex-col items-center w-1/4">
                    <div id="step2-icon" class="w-10 h-10 rounded-full flex items-center justify-center text-white bg-[#EBE6DA] border-[3px] border-[#F3F0E6] shadow-sm transition-colors duration-500">
                        <i class="fas fa-check-double text-xs"></i>
                    </div>
                    <span class="mt-1.5 text-[0.6rem] font-bold text-[#A79A8B] leading-tight text-center" id="step2-text">อนุมัติแล้ว</span>
                </div>
                <div class="relative z-10 flex flex-col items-center w-1/4">
                    <div id="step3-icon" class="w-10 h-10 rounded-full flex items-center justify-center text-white bg-[#EBE6DA] border-[3px] border-[#F3F0E6] shadow-sm transition-colors duration-500">
                        <i class="fas fa-door-closed text-xs"></i>
                    </div>
                    <span class="mt-1.5 text-[0.6rem] font-bold text-[#A79A8B] leading-tight text-center" id="step3-text">เสร็จสิ้น<br>การประชุม</span>
                </div>
                <div class="relative z-10 flex flex-col items-center w-1/4">
                    <div id="step4-icon" class="w-10 h-10 rounded-full flex items-center justify-center text-white bg-[#EBE6DA] border-[3px] border-[#F3F0E6] shadow-sm transition-colors duration-500">
                        <i class="fas fa-star text-xs"></i>
                    </div>
                    <span class="mt-1.5 text-[0.6rem] font-bold text-[#A79A8B] leading-tight text-center" id="step4-text">ประเมินแล้ว</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Ticket / Receipt Card -->
    <div id="ticketCard" class="bg-white rounded-2xl shadow-[0_8px_30px_rgba(106,82,67,0.08)] border border-[#EBE6DA] overflow-hidden opacity-0 translate-y-6 transition-all duration-700">
        <div class="h-1.5 w-full bg-gradient-to-r from-[#D4B59D] to-[#6A5243]"></div>

        <!-- Card Body -->
        <div style="padding: 2rem 2.25rem;">

            <!-- Reference ID & Status -->
            <div style="display:flex; justify-content:space-between; align-items:center; padding-bottom:1.5rem; margin-bottom:1.75rem; border-bottom:2px dashed #EBE6DA; flex-wrap:wrap; gap:0.75rem;">
                <div>
                    <div style="font-size:0.6rem; font-weight:700; color:#A79A8B; letter-spacing:0.15em; text-transform:uppercase; margin-bottom:0.4rem;">รหัสอ้างอิงการจอง</div>
                    <div style="font-size:1.25rem; font-weight:800; color:#6A5243; font-family:monospace; letter-spacing:0.05em;">#<span id="displayId">...</span></div>
                </div>
                <span id="displayStatus" style="display:inline-flex; align-items:center; gap:0.4rem; padding:0.4rem 1rem; border-radius:9999px; font-weight:700; font-size:0.7rem; text-transform:uppercase;">...</span>
            </div>

            <!-- Topic -->
            <div style="margin-bottom:1.75rem;">
                <div style="font-size:0.6rem; font-weight:700; color:#A79A8B; letter-spacing:0.15em; text-transform:uppercase; margin-bottom:0.5rem;">หัวข้อ/เรื่องการประชุม</div>
                <div id="displayTitle" style="font-size:1rem; font-weight:700; color:#6A5243; line-height:1.5;">...</div>
            </div>

            <!-- Date & Room -->
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1.75rem;">
                <div style="background:#F9F8F6; border:1px solid #EBE6DA; border-radius:0.875rem; padding:1.1rem 1.25rem;">
                    <div style="display:flex; align-items:flex-start; gap:0.875rem;">
                        <div style="width:2.5rem; height:2.5rem; background:white; border-radius:0.625rem; display:flex; align-items:center; justify-content:center; color:#D4B59D; flex-shrink:0; box-shadow:0 1px 4px rgba(0,0,0,0.06);">
                            <i class="fas fa-calendar-day" style="font-size:0.9rem;"></i>
                        </div>
                        <div>
                            <div style="font-size:0.58rem; font-weight:700; color:#A79A8B; letter-spacing:0.12em; text-transform:uppercase; margin-bottom:0.5rem;">วันและเวลาที่จอง</div>
                            <div id="displayDate" style="font-weight:700; font-size:0.875rem; color:#6A5243;">...</div>
                            <div id="displayTime" style="font-size:0.8rem; font-weight:500; color:#A79A8B; margin-top:0.3rem;">...</div>
                        </div>
                    </div>
                </div>
                <div style="background:#F9F8F6; border:1px solid #EBE6DA; border-radius:0.875rem; padding:1.1rem 1.25rem;">
                    <div style="display:flex; align-items:flex-start; gap:0.875rem;">
                        <div style="width:2.5rem; height:2.5rem; background:white; border-radius:0.625rem; display:flex; align-items:center; justify-content:center; color:#D4B59D; flex-shrink:0; box-shadow:0 1px 4px rgba(0,0,0,0.06);">
                            <i class="fas fa-door-open" style="font-size:0.9rem;"></i>
                        </div>
                        <div>
                            <div style="font-size:0.58rem; font-weight:700; color:#A79A8B; letter-spacing:0.12em; text-transform:uppercase; margin-bottom:0.5rem;">ห้องประชุม</div>
                            <div id="displayRoom" style="font-weight:700; font-size:0.875rem; color:#6A5243; line-height:1.4;">...</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Divider -->
            <hr style="border:none; border-top:1px solid #EBE6DA; margin-bottom:1.75rem;">

            <!-- Extra Info -->
            <div style="display:grid; grid-template-columns:1fr 1fr 1fr 1fr; gap:1.25rem; margin-bottom:1.75rem;">
                <div>
                    <div style="font-size:0.58rem; font-weight:700; color:#A79A8B; letter-spacing:0.1em; text-transform:uppercase; margin-bottom:0.5rem;">ผู้จอง</div>
                    <div id="displayUser" style="font-weight:700; font-size:0.875rem; color:#6A5243; line-height:1.4;">...</div>
                </div>
                <div>
                    <div style="font-size:0.58rem; font-weight:700; color:#A79A8B; letter-spacing:0.1em; text-transform:uppercase; margin-bottom:0.5rem;">หน่วยงาน</div>
                    <div id="displayDept" style="font-weight:700; font-size:0.875rem; color:#6A5243; line-height:1.4;">...</div>
                </div>
                <div>
                    <div style="font-size:0.58rem; font-weight:700; color:#A79A8B; letter-spacing:0.1em; text-transform:uppercase; margin-bottom:0.5rem;">จำนวนผู้เข้าใช้</div>
                    <div id="displayCount" style="font-weight:700; font-size:0.875rem; color:#6A5243;">...</div>
                </div>
                <div>
                    <div style="font-size:0.58rem; font-weight:700; color:#A79A8B; letter-spacing:0.1em; text-transform:uppercase; margin-bottom:0.5rem;">เบอร์ติดต่อ</div>
                    <div id="displayPhone" style="font-weight:700; font-size:0.875rem; color:#6A5243;">...</div>
                </div>
            </div>

            <!-- Description (Optional) -->
            <div id="descContainer" style="display:none; margin-bottom:1.25rem;">
                <div style="font-size:0.58rem; font-weight:700; color:#A79A8B; letter-spacing:0.1em; text-transform:uppercase; margin-bottom:0.5rem;">หมายเหตุเพิ่มเติม</div>
                <div id="displayDesc" style="padding:0.875rem 1.1rem; background:#FDFBF7; border-radius:0.75rem; font-size:0.875rem; color:#6A5243; border:1px solid #EBE6DA; line-height:1.6;"></div>
            </div>

            <!-- Equipment (Optional) -->
            <div id="equipContainer" style="display:none;">
                <div style="font-size:0.58rem; font-weight:700; color:#A79A8B; letter-spacing:0.1em; text-transform:uppercase; margin-bottom:0.5rem;">อุปกรณ์ที่ต้องการ</div>
                <div id="displayEquip" style="padding:0.875rem 1.1rem; background:#FDFBF7; border-radius:0.75rem; font-size:0.875rem; color:#6A5243; border:1px solid #EBE6DA; line-height:1.6;"></div>
            </div>

        </div>

        <!-- Footer -->
        <div style="background:#F9F8F6; padding:1.1rem 2.25rem; display:flex; align-items:center; justify-content:space-between; gap:1rem; border-top:1px solid #EBE6DA; flex-wrap:wrap;">
            <p style="font-size:0.65rem; font-weight:600; color:#A79A8B; display:flex; align-items:center; gap:0.5rem;">
                <i class="fas fa-info-circle" style="color:#D4B59D;"></i> โปรดเก็บรหัสอ้างอิงไว้เพื่อใช้สำหรับติดตามสถานะการจอง
            </p>
            <div style="display:flex; gap:0.625rem;">
                <button onclick="window.print()" style="padding:0.5rem 1.1rem; border-radius:0.625rem; background:white; border:1px solid #EBE6DA; color:#6A5243; font-weight:700; font-size:0.75rem; display:flex; align-items:center; gap:0.4rem; cursor:pointer;">
                    <i class="fas fa-print"></i> พิมพ์เอกสาร
                </button>
                <a href="dashboard.php?view=approve_list" style="padding:0.5rem 1.1rem; border-radius:0.625rem; background:#6A5243; color:white; font-weight:700; font-size:0.75rem; display:flex; align-items:center; gap:0.4rem; text-decoration:none;">
                    <i class="fas fa-list"></i> ดูรายการทั้งหมด
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', async () => {
    const bookingId = <?php echo json_encode($bookingId); ?>;
    try {
        const data = await MeetQueue.api.fetch(`api/bookings.php?booking_id=${bookingId}`);
        if (data.success && data.bookings && data.bookings.length > 0) {
            renderBookingResult(data.bookings[0]);
        } else {
            showError('ไม่พบข้อมูลการจองนี้ หรือคุณไม่มีสิทธิ์เข้าถึง');
        }
    } catch (err) {
        showError('เกิดข้อผิดพลาดในการดึงข้อมูล');
    }
});

function renderBookingResult(booking) {
    const iconContainer = document.getElementById('statusIconContainer');
    const title = document.getElementById('resultTitle');
    const subtitle = document.getElementById('resultSubtitle');
    const statusBadge = document.getElementById('displayStatus');

    let badgeStyle = 'background:#F3F0E9; color:#A79A8B;';
    let statusText = 'ไม่ทราบสถานะ';
    let iconHtml = '<i class="fas fa-question"></i>';
    let iconBg = 'bg-[#F9F8F6]';
    let iconColor = 'text-[#A79A8B]';

    switch (booking.status) {
        case 'pending':
            badgeStyle = 'background:#fef3c7; color:#b45309; border:1px solid #fde68a;';
            statusText = '<i class="fas fa-clock"></i> รออนุมัติ';
            iconHtml = '<i class="fas fa-paper-plane"></i>';
            iconBg = 'bg-amber-100'; iconColor = 'text-amber-600';
            title.textContent = 'ส่งคำขอสำเร็จ';
            subtitle.textContent = 'ระบบได้รับข้อมูลการจองของคุณแล้ว และกำลังรอการพิจารณา';
            break;
        case 'approved':
            badgeStyle = 'background:#d1fae5; color:#065f46; border:1px solid #a7f3d0;';
            statusText = '<i class="fas fa-check-circle"></i> อนุมัติแล้ว';
            iconHtml = '<i class="fas fa-check-double"></i>';
            iconBg = 'bg-emerald-100'; iconColor = 'text-emerald-600';
            title.textContent = 'การจองได้รับการอนุมัติ';
            subtitle.textContent = 'ห้องประชุมพร้อมสำหรับคุณแล้ว';
            break;
        case 'rejected':
            badgeStyle = 'background:#fee2e2; color:#991b1b; border:1px solid #fca5a5;';
            statusText = '<i class="fas fa-times-circle"></i> ไม่อนุมัติ';
            iconHtml = '<i class="fas fa-times"></i>';
            iconBg = 'bg-red-100'; iconColor = 'text-red-600';
            title.textContent = 'การขอประชุมถูกปฏิเสธ';
            subtitle.textContent = 'ขออภัย ไม่สามารถอนุมัติการประชุมนี้ได้';
            break;
        case 'cancelled':
            badgeStyle = 'background:#f1f5f9; color:#475569; border:1px solid #cbd5e1;';
            statusText = '<i class="fas fa-ban"></i> ยกเลิก';
            iconHtml = '<i class="fas fa-ban"></i>';
            iconBg = 'bg-slate-100'; iconColor = 'text-slate-600';
            title.textContent = 'การประชุมถูกยกเลิก';
            subtitle.textContent = 'รายการนี้ได้ถูกยกเลิกแล้ว';
            break;
    }

    iconContainer.className = `w-20 h-20 rounded-full flex items-center justify-center text-3xl mx-auto mb-5 shadow-md transition-all duration-500 ${iconBg} ${iconColor}`;
    iconContainer.innerHTML = iconHtml;
    statusBadge.setAttribute('style', statusBadge.getAttribute('style') + badgeStyle);
    statusBadge.innerHTML = statusText;

    document.getElementById('displayId').textContent = String(booking.id).padStart(6, '0');
    document.getElementById('displayTitle').textContent = booking.title;
    document.getElementById('displayDate').textContent = MeetQueue.utils.formatDateLong(booking.start_time);
    document.getElementById('displayTime').textContent = `${MeetQueue.utils.formatTime(booking.start_time)} - ${MeetQueue.utils.formatTime(booking.end_time)} น.`;
    document.getElementById('displayRoom').textContent = booking.is_external ? `(ภายนอก) ${booking.external_org}` : booking.room_name;
    document.getElementById('displayUser').textContent = (booking.first_name + ' ' + (booking.last_name || '')).trim();
    document.getElementById('displayDept').textContent = booking.department_name || '-';
    document.getElementById('displayCount').textContent = booking.participants_count;
    document.getElementById('displayPhone').textContent = booking.phone;

    if (booking.description) {
        document.getElementById('descContainer').style.display = 'block';
        document.getElementById('displayDesc').textContent = booking.description;
    }
    if (booking.equipments) {
        document.getElementById('equipContainer').style.display = 'block';
        document.getElementById('displayEquip').textContent = booking.equipments;
    }

    if (booking.status !== 'rejected' && booking.status !== 'cancelled') {
        document.getElementById('timelineContainer').classList.remove('hidden');
        let progressPercent = 0;
        const now = new Date();
        const endTime = new Date(booking.end_time);
        const isCompleted = (booking.status === 'completed' || booking.status === 'approved') && endTime < now;

        checkEvaluationStatus(booking.id).then(isEvaluated => {
            document.getElementById('step1-icon').className = 'w-10 h-10 rounded-full flex items-center justify-center text-white bg-[#6A5243] border-[3px] border-[#F3F0E6] shadow-sm transition-colors duration-500';
            document.getElementById('step1-text').className = 'mt-1.5 text-[0.6rem] font-bold text-[#6A5243] leading-tight text-center';
            if (booking.status === 'approved' || isCompleted || isEvaluated) {
                progressPercent = 33;
                setTimeout(() => {
                    document.getElementById('step2-icon').className = 'w-10 h-10 rounded-full flex items-center justify-center text-white bg-[#6A5243] border-[3px] border-[#F3F0E6] shadow-sm transition-colors duration-500';
                    document.getElementById('step2-text').className = 'mt-1.5 text-[0.6rem] font-bold text-[#6A5243] leading-tight text-center';
                }, 300);
            }
            if (isCompleted || isEvaluated) {
                progressPercent = 66;
                setTimeout(() => {
                    document.getElementById('step3-icon').className = 'w-10 h-10 rounded-full flex items-center justify-center text-white bg-[#6A5243] border-[3px] border-[#F3F0E6] shadow-sm transition-colors duration-500';
                    document.getElementById('step3-text').className = 'mt-1.5 text-[0.6rem] font-bold text-[#6A5243] leading-tight text-center';
                }, 600);
            }
            if (isEvaluated) {
                progressPercent = 100;
                setTimeout(() => {
                    document.getElementById('step4-icon').className = 'w-10 h-10 rounded-full flex items-center justify-center text-white bg-[#6A5243] border-[3px] border-[#F3F0E6] shadow-sm transition-colors duration-500';
                    document.getElementById('step4-text').className = 'mt-1.5 text-[0.6rem] font-bold text-[#6A5243] leading-tight text-center';
                }, 900);
            }
            setTimeout(() => {
                document.getElementById('timelineProgress').style.width = ((progressPercent / 100) * 80) + '%';
            }, 100);
        });
    }

    document.getElementById('ticketCard').classList.remove('opacity-0', 'translate-y-6');
}

async function checkEvaluationStatus(bookingId) {
    try {
        const res = await fetch(`api/reviews.php?booking_id=${bookingId}`);
        const result = await res.json();
        return result.has_review;
    } catch(e) { return false; }
}

function showError(msg) {
    document.getElementById('resultTitle').textContent = 'เกิดข้อผิดพลาด';
    document.getElementById('resultSubtitle').textContent = msg;
    document.getElementById('resultTitle').classList.add('text-red-500');
    const icon = document.getElementById('statusIconContainer');
    icon.className = 'w-20 h-20 rounded-full flex items-center justify-center text-3xl mx-auto mb-5 shadow-md bg-red-100 text-red-500';
    icon.innerHTML = '<i class="fas fa-exclamation-triangle"></i>';
}
</script>

<style>
@media print {
    body * { visibility: hidden; }
    #ticketCard, #ticketCard * { visibility: visible; }
    #ticketCard { position: absolute; left: 0; top: 0; width: 100%; box-shadow: none !important; border: none !important; }
    aside, header, .hero-banner { display: none !important; }
}
</style>
