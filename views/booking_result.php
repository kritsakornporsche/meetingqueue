<?php
$bookingId = $_GET['id'] ?? null;

if (!$bookingId) {
    echo "<div class='text-center py-20 text-red-500 font-bold'>ไม่พบรหัสการจอง</div>";
    return;
}
?>

<div class="max-w-[720px] mx-auto py-10 px-4 md:px-8 animate-fade">
    <!-- Result Header & Timeline Tracker -->
    <div class="text-center mb-8">
        <div id="statusIconContainer" class="w-20 h-20 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center text-3xl mx-auto mb-5 shadow-md transition-all duration-500">
            <i class="fas fa-circle-notch fa-spin"></i>
        </div>
        <h2 id="resultTitle" class="text-2xl font-black text-slate-800 mb-2">กำลังโหลดข้อมูล...</h2>
        <p id="resultSubtitle" class="text-sm text-slate-500 font-bold mb-7">กรุณารอสักครู่</p>

        <!-- Status Timeline Tracker -->
        <div id="timelineContainer" class="hidden w-full flex justify-center mt-2 mb-2">
            <div class="w-full max-w-md relative">
                <div class="flex items-center justify-between relative">
                    <div class="absolute left-[10%] right-[10%] top-[20px] h-[3px] bg-slate-200 z-0 rounded-full"></div>
                    <div id="timelineProgress" class="absolute left-[10%] top-[20px] h-[3px] bg-blue-600 z-0 rounded-full transition-all duration-1000 w-0"></div>
                    <div class="relative z-10 flex flex-col items-center w-1/4">
                        <div id="step1-icon" class="w-10 h-10 rounded-full flex items-center justify-center text-white bg-slate-400 border-[3px] border-slate-50 shadow-sm transition-colors duration-500">
                            <i class="fas fa-paper-plane text-xs"></i>
                        </div>
                        <span class="mt-1.5 text-[0.68rem] font-bold text-slate-400 leading-tight text-center" id="step1-text">ยังไม่อนุมัติ</span>
                    </div>
                    <div class="relative z-10 flex flex-col items-center w-1/4">
                        <div id="step2-icon" class="w-10 h-10 rounded-full flex items-center justify-center text-white bg-slate-200 border-[3px] border-slate-50 shadow-sm transition-colors duration-500">
                            <i class="fas fa-check-double text-xs"></i>
                        </div>
                        <span class="mt-1.5 text-[0.68rem] font-bold text-slate-400 leading-tight text-center" id="step2-text">อนุมัติแล้ว</span>
                    </div>
                    <div class="relative z-10 flex flex-col items-center w-1/4">
                        <div id="step3-icon" class="w-10 h-10 rounded-full flex items-center justify-center text-white bg-slate-200 border-[3px] border-slate-50 shadow-sm transition-colors duration-500">
                            <i class="fas fa-door-closed text-xs"></i>
                        </div>
                        <span class="mt-1.5 text-[0.68rem] font-bold text-slate-400 leading-tight text-center" id="step3-text">เสร็จสิ้น<br>การประชุม</span>
                    </div>
                    <div class="relative z-10 flex flex-col items-center w-1/4">
                        <div id="step4-icon" class="w-10 h-10 rounded-full flex items-center justify-center text-white bg-slate-200 border-[3px] border-slate-50 shadow-sm transition-colors duration-500">
                            <i class="fas fa-star text-xs"></i>
                        </div>
                        <span class="mt-1.5 text-[0.68rem] font-bold text-slate-400 leading-tight text-center" id="step4-text">ประเมินแล้ว</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ticket / Receipt Card -->
    <div id="ticketCard" class="bg-white rounded-[2rem] shadow-[0_10px_40px_rgba(15,23,42,0.06)] border border-slate-200 overflow-hidden opacity-0 translate-y-6 transition-all duration-700">
        <div class="h-1.5 w-full bg-gradient-to-r from-blue-500 to-blue-700"></div>

        <!-- Card Body -->
        <div class="p-8 md:p-10">

            <!-- Reference ID & Status -->
            <div class="flex justify-between items-center pb-6 mb-7 border-b-2 border-dashed border-slate-200 flex-wrap gap-3">
                <div>
                    <div class="text-[0.72rem] font-extrabold text-slate-400 letter-spacing:0.15em text-transform:uppercase mb-1 flex items-center gap-1.5"><i class="fas fa-hashtag text-blue-500"></i> รหัสอ้างอิงการจอง</div>
                    <div class="text-xl font-black text-slate-800 font-mono tracking-wider">#<span id="displayId">...</span></div>
                </div>
                <span id="displayStatus" class="inline-flex items-center gap-2 px-4 py-2 rounded-full font-bold text-[0.75rem] text-transform:uppercase shadow-sm">...</span>
            </div>

            <!-- Topic -->
            <div class="mb-7">
                <div class="text-[0.72rem] font-extrabold text-slate-400 tracking-wider text-transform:uppercase mb-2 flex items-center gap-1.5"><i class="fas fa-quote-left text-blue-500"></i> หัวข้อ/เรื่องการประชุม</div>
                <div id="displayTitle" class="text-[1.1rem] font-black text-slate-800 line-clamp-2 leading-relaxed">...</div>
            </div>

            <!-- Date & Room -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-7">
                <div class="bg-slate-50/60 border border-slate-200/80 rounded-[1.25rem] p-5 shadow-sm hover:shadow transition-shadow">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-blue-500 flex-shrink-0 shadow-sm">
                            <i class="fas fa-calendar-alt text-[1.1rem]"></i>
                        </div>
                        <div>
                            <div class="text-[0.7rem] font-extrabold text-slate-400 tracking-wider text-transform:uppercase mb-1.5">วันและเวลาที่จอง</div>
                            <div id="displayDate" class="font-black text-[0.92rem] text-slate-800 leading-snug">...</div>
                            <div id="displayTime" class="text-[0.8rem] font-bold text-slate-500 mt-1">...</div>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50/60 border border-slate-200/80 rounded-[1.25rem] p-5 shadow-sm hover:shadow transition-shadow">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-blue-500 flex-shrink-0 shadow-sm">
                            <i class="fas fa-door-open text-[1.1rem]"></i>
                        </div>
                        <div>
                            <div class="text-[0.7rem] font-extrabold text-slate-400 tracking-wider text-transform:uppercase mb-1.5">ห้องประชุม</div>
                            <div id="displayRoom" class="font-black text-[0.92rem] text-slate-800 leading-snug">...</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Divider -->
            <hr class="border-slate-100 mb-7">

            <!-- Extra Info -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-7">
                <div>
                    <div class="text-[0.7rem] font-extrabold text-slate-400 tracking-wider text-transform:uppercase mb-1.5 flex items-center gap-1.5"><i class="fas fa-user text-blue-500"></i> ผู้จอง</div>
                    <div id="displayUser" class="font-black text-[0.92rem] text-slate-800 leading-snug">...</div>
                </div>
                <div>
                    <div class="text-[0.7rem] font-extrabold text-slate-400 tracking-wider text-transform:uppercase mb-1.5 flex items-center gap-1.5"><i class="fas fa-sitemap text-blue-500"></i> หน่วยงาน</div>
                    <div id="displayDept" class="font-black text-[0.92rem] text-slate-800 leading-snug">...</div>
                </div>
                <div>
                    <div class="text-[0.7rem] font-extrabold text-slate-400 tracking-wider text-transform:uppercase mb-1.5 flex items-center gap-1.5"><i class="fas fa-users text-blue-500"></i> จำนวนผู้เข้าใช้</div>
                    <div id="displayCount" class="font-black text-[0.92rem] text-slate-800 leading-snug">...</div>
                </div>
                <div>
                    <div class="text-[0.7rem] font-extrabold text-slate-400 tracking-wider text-transform:uppercase mb-1.5 flex items-center gap-1.5"><i class="fas fa-phone text-blue-500"></i> เบอร์ติดต่อ</div>
                    <div id="displayPhone" class="font-black text-[0.92rem] text-slate-800 leading-snug">...</div>
                </div>
            </div>

            <!-- Description (Optional) -->
            <div id="descContainer" class="hidden mb-5">
                <div class="text-[0.7rem] font-extrabold text-slate-400 tracking-wider text-transform:uppercase mb-2 flex items-center gap-1.5"><i class="fas fa-clipboard-list text-blue-500"></i> หมายเหตุเพิ่มเติม</div>
                <div id="displayDesc" class="p-4 bg-slate-50 border border-slate-200 rounded-2xl text-[0.88rem] text-slate-700 font-bold whitespace-pre-line leading-relaxed shadow-inner"></div>
            </div>

            <!-- Equipment (Optional) -->
            <div id="equipContainer" class="hidden">
                <div class="text-[0.7rem] font-extrabold text-slate-400 tracking-wider text-transform:uppercase mb-2 flex items-center gap-1.5"><i class="fas fa-tools text-blue-500"></i> อุปกรณ์ที่ต้องการ</div>
                <div id="displayEquip" class="p-4 bg-slate-50 border border-slate-200 rounded-2xl text-[0.88rem] text-slate-700 font-bold whitespace-pre-line leading-relaxed shadow-inner"></div>
            </div>

        </div>

        <!-- Footer -->
        <div class="bg-slate-50/80 p-6 md:p-8 flex items-center justify-between gap-4 border-t border-slate-200/80 flex-wrap">
            <p class="text-[0.72rem] font-extrabold text-slate-500 flex items-center gap-2 margin:0">
                <i class="fas fa-info-circle text-blue-500"></i> โปรดเก็บรหัสอ้างอิงไว้เพื่อใช้สำหรับติดตามสถานะการจอง
            </p>
            <div class="flex gap-3 ml-auto flex-wrap justify-end">
                <button onclick="window.print()" class="px-4 py-2.5 rounded-xl bg-white border border-slate-200 text-blue-600 hover:text-blue-700 hover:border-blue-300 font-black text-[0.78rem] flex items-center gap-2 cursor-pointer shadow-sm transition-all hover:-translate-y-0.5">
                    <i class="fas fa-print text-blue-500"></i> พิมพ์เอกสาร
                </button>
                <a href="dashboard.php" class="px-4 py-2.5 rounded-xl bg-white border border-blue-500 text-blue-600 hover:bg-blue-50 font-black text-[0.78rem] flex items-center gap-2 text-decoration:none shadow-sm transition-all hover:-translate-y-0.5">
                    <i class="fas fa-home text-blue-500"></i> กลับหน้าหลัก
                </a>
                <a href="dashboard.php?view=approve_list" class="px-4 py-2.5 rounded-xl bg-blue-600 text-white hover:bg-blue-700 font-black text-[0.78rem] flex items-center gap-2 text-decoration:none shadow-md shadow-blue-500/20 transition-all hover:-translate-y-0.5">
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

    let badgeStyle = 'background:#f1f5f9; color:#64748b; border:1px solid #cbd5e1;';
    let statusText = 'ไม่ทราบสถานะ';
    let iconHtml = '<i class="fas fa-question"></i>';
    let iconBg = 'bg-slate-50';
    let iconColor = 'text-slate-500';

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
        case 'completed':
            badgeStyle = 'background:#dcfce7; color:#166534; border:1px solid #bbf7d0;';
            statusText = '<i class="fas fa-check-circle"></i> เสร็จสิ้น';
            iconHtml = '<i class="fas fa-calendar-check"></i>';
            iconBg = 'bg-emerald-100'; iconColor = 'text-emerald-600';
            title.textContent = 'การประชุมเสร็จสิ้น';
            subtitle.textContent = 'ขอบคุณที่ใช้บริการห้องประชุม';
            break;
    }

    iconContainer.className = `w-20 h-20 rounded-full flex items-center justify-center text-3xl mx-auto mb-5 shadow-md transition-all duration-500 ${iconBg} ${iconColor}`;
    iconContainer.innerHTML = iconHtml;
    statusBadge.setAttribute('style', badgeStyle);
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
        document.getElementById('descContainer').classList.remove('hidden');
        document.getElementById('displayDesc').textContent = booking.description;
    }
    if (booking.equipments) {
        document.getElementById('equipContainer').classList.remove('hidden');
        document.getElementById('displayEquip').textContent = booking.equipments;
    }

    if (booking.status !== 'rejected' && booking.status !== 'cancelled') {
        document.getElementById('timelineContainer').classList.remove('hidden');
        let progressPercent = 0;
        const now = new Date();
        const endTime = new Date(booking.end_time);
        const isCompleted = (booking.status === 'completed') || (booking.status === 'approved' && endTime < now);

        checkEvaluationStatus(booking.id).then(isEvaluated => {
            document.getElementById('step1-icon').className = 'w-10 h-10 rounded-full flex items-center justify-center text-white bg-blue-600 border-[3px] border-blue-50 shadow-sm transition-colors duration-500';
            document.getElementById('step1-text').className = 'mt-1.5 text-[0.68rem] font-bold text-blue-600 leading-tight text-center';
            if (booking.status === 'approved' || isCompleted || isEvaluated) {
                progressPercent = 33;
                setTimeout(() => {
                    document.getElementById('step2-icon').className = 'w-10 h-10 rounded-full flex items-center justify-center text-white bg-blue-600 border-[3px] border-blue-50 shadow-sm transition-colors duration-500';
                    document.getElementById('step2-text').className = 'mt-1.5 text-[0.68rem] font-bold text-blue-600 leading-tight text-center';
                }, 300);
            }
            if (isCompleted || isEvaluated) {
                progressPercent = 66;
                setTimeout(() => {
                    document.getElementById('step3-icon').className = 'w-10 h-10 rounded-full flex items-center justify-center text-white bg-blue-600 border-[3px] border-blue-50 shadow-sm transition-colors duration-500';
                    document.getElementById('step3-text').className = 'mt-1.5 text-[0.68rem] font-bold text-blue-600 leading-tight text-center';
                }, 600);
            }
            if (isEvaluated) {
                progressPercent = 100;
                setTimeout(() => {
                    document.getElementById('step4-icon').className = 'w-10 h-10 rounded-full flex items-center justify-center text-white bg-blue-600 border-[3px] border-blue-50 shadow-sm transition-colors duration-500';
                    document.getElementById('step4-text').className = 'mt-1.5 text-[0.68rem] font-bold text-blue-600 leading-tight text-center';
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
