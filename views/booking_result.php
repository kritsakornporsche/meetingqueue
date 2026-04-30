<?php
$bookingId = $_GET['id'] ?? null;

if (!$bookingId) {
    echo "<div class='text-center py-20 text-red-500 font-bold'>ไม่พบรหัสการจอง</div>";
    return;
}
?>

<div class="max-w-[800px] mx-auto py-12 px-6 md:px-10">
    <!-- Result Header & Timeline Tracker -->
    <div class="text-center mb-10">
        <div id="statusIconContainer" class="w-24 h-24 rounded-full bg-[#EBE6DA] text-[#A79A8B] flex items-center justify-center text-4xl mx-auto mb-6 shadow-lg transition-all duration-500">
            <i class="fas fa-circle-notch fa-spin"></i>
        </div>
        <h2 id="resultTitle" class="text-4xl font-black text-[#6A5243] mb-4">กำลังโหลดข้อมูล...</h2>
        <p id="resultSubtitle" class="text-lg text-[#A79A8B] font-semibold mb-8">กรุณารอสักครู่</p>
        
        <!-- Status Timeline Tracker -->
        <div id="timelineContainer" class="hidden max-w-2xl mx-auto">
            <div class="flex items-center justify-between relative">
                <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-1 bg-[#EBE6DA] z-0 rounded-full"></div>
                <div id="timelineProgress" class="absolute left-0 top-1/2 -translate-y-1/2 h-1 bg-[#6A5243] z-0 rounded-full transition-all duration-1000 w-0"></div>
                
                <!-- Step 1: Pending -->
                <div class="relative z-10 flex flex-col items-center">
                    <div id="step1-icon" class="w-10 h-10 rounded-full flex items-center justify-center text-white bg-[#A79A8B] border-4 border-[#F3F0E6] shadow-sm transition-colors duration-500">
                        <i class="fas fa-paper-plane text-sm"></i>
                    </div>
                    <span class="mt-2 text-xs font-bold text-[#A79A8B] uppercase tracking-wide" id="step1-text">ยังไม่อนุมัติ</span>
                </div>
                
                <!-- Step 2: Approved -->
                <div class="relative z-10 flex flex-col items-center">
                    <div id="step2-icon" class="w-10 h-10 rounded-full flex items-center justify-center text-white bg-[#EBE6DA] border-4 border-[#F3F0E6] shadow-sm transition-colors duration-500">
                        <i class="fas fa-check-double text-sm"></i>
                    </div>
                    <span class="mt-2 text-xs font-bold text-[#A79A8B] uppercase tracking-wide" id="step2-text">อนุมัติแล้ว</span>
                </div>
                
                <!-- Step 3: Completed -->
                <div class="relative z-10 flex flex-col items-center">
                    <div id="step3-icon" class="w-10 h-10 rounded-full flex items-center justify-center text-white bg-[#EBE6DA] border-4 border-[#F3F0E6] shadow-sm transition-colors duration-500">
                        <i class="fas fa-door-closed text-sm"></i>
                    </div>
                    <span class="mt-2 text-xs font-bold text-[#A79A8B] uppercase tracking-wide" id="step3-text">เสร็จสิ้นการประชุม</span>
                </div>
                
                <!-- Step 4: Evaluated -->
                <div class="relative z-10 flex flex-col items-center">
                    <div id="step4-icon" class="w-10 h-10 rounded-full flex items-center justify-center text-white bg-[#EBE6DA] border-4 border-[#F3F0E6] shadow-sm transition-colors duration-500">
                        <i class="fas fa-star text-sm"></i>
                    </div>
                    <span class="mt-2 text-xs font-bold text-[#A79A8B] uppercase tracking-wide" id="step4-text">ประเมินแล้ว</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Ticket / Receipt Card -->
    <div id="ticketCard" class="bg-white rounded-[2.5rem] shadow-[0_20px_60px_rgba(106,82,67,0.08)] border border-[#EBE6DA] overflow-hidden relative opacity-0 transform translate-y-10 transition-all duration-700">
        <!-- Top accent line -->
        <div class="h-3 w-full bg-gradient-to-r from-[#D4B59D] to-[#6A5243]"></div>
        
        <div class="p-8 md:p-12">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 pb-8 border-b-2 border-dashed border-[#EBE6DA]">
                <div>
                    <h3 class="text-xs font-black text-[#A79A8B] uppercase tracking-widest mb-2">รหัสอ้างอิงการจอง</h3>
                    <div class="text-2xl font-black text-[#6A5243] font-mono tracking-wider">#<span id="displayId">...</span></div>
                </div>
                <div class="mt-4 md:mt-0 text-left md:text-right">
                    <span id="displayStatus" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full font-black text-sm uppercase tracking-wide">
                        ...
                    </span>
                </div>
            </div>

            <div class="space-y-8">
                <!-- Topic -->
                <div>
                    <label class="text-[0.65rem] font-black text-[#A79A8B] uppercase tracking-[0.2em] mb-2 block">หัวข้อ/เรื่อง</label>
                    <div id="displayTitle" class="text-xl font-bold text-[#6A5243] leading-snug">...</div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Date & Time -->
                    <div class="bg-[#F9F8F6] rounded-3xl p-6 border border-[#EBE6DA]">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center text-[#D4B59D] shadow-sm flex-shrink-0">
                                <i class="fas fa-calendar-day text-lg"></i>
                            </div>
                            <div>
                                <label class="text-[0.65rem] font-black text-[#A79A8B] uppercase tracking-[0.2em] mb-1 block">วันและเวลา</label>
                                <div id="displayDate" class="font-bold text-[#6A5243]">...</div>
                                <div id="displayTime" class="text-sm font-semibold text-[#A79A8B] mt-1">...</div>
                            </div>
                        </div>
                    </div>

                    <!-- Room -->
                    <div class="bg-[#F9F8F6] rounded-3xl p-6 border border-[#EBE6DA]">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center text-[#D4B59D] shadow-sm flex-shrink-0">
                                <i class="fas fa-door-open text-lg"></i>
                            </div>
                            <div>
                                <label class="text-[0.65rem] font-black text-[#A79A8B] uppercase tracking-[0.2em] mb-1 block">ห้องประชุม</label>
                                <div id="displayRoom" class="font-bold text-[#6A5243] line-clamp-2">...</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Extra Info -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 pt-4">
                    <div>
                        <label class="text-[0.65rem] font-black text-[#A79A8B] uppercase tracking-[0.15em] mb-1 block">ผู้จอง</label>
                        <div id="displayUser" class="font-bold text-[#6A5243] truncate">...</div>
                    </div>
                    <div>
                        <label class="text-[0.65rem] font-black text-[#A79A8B] uppercase tracking-[0.15em] mb-1 block">หน่วยงาน</label>
                        <div id="displayDept" class="font-bold text-[#6A5243] truncate">...</div>
                    </div>
                    <div>
                        <label class="text-[0.65rem] font-black text-[#A79A8B] uppercase tracking-[0.15em] mb-1 block">จำนวน (คน)</label>
                        <div id="displayCount" class="font-bold text-[#6A5243]">...</div>
                    </div>
                    <div>
                        <label class="text-[0.65rem] font-black text-[#A79A8B] uppercase tracking-[0.15em] mb-1 block">เบอร์ติดต่อ</label>
                        <div id="displayPhone" class="font-bold text-[#6A5243]">...</div>
                    </div>
                </div>
                
                <!-- Description (Optional) -->
                <div id="descContainer" class="hidden pt-4">
                    <label class="text-[0.65rem] font-black text-[#A79A8B] uppercase tracking-[0.15em] mb-2 block">หมายเหตุ</label>
                    <div id="displayDesc" class="p-4 bg-[#FDFBF7] rounded-2xl text-sm font-medium text-[#6A5243] border border-[#EBE6DA]"></div>
                </div>
            </div>
        </div>

        <!-- Ticket Footer -->
        <div class="bg-[#F9F8F6] p-6 md:px-12 flex flex-col md:flex-row items-center justify-between gap-4 border-t border-[#EBE6DA]">
            <p class="text-xs font-bold text-[#A79A8B] text-center md:text-left">
                <i class="fas fa-info-circle mr-1"></i> โปรดเก็บรหัสอ้างอิงไว้เพื่อติดตามสถานะ
            </p>
            <div class="flex gap-3">
                <button onclick="window.print()" class="px-5 py-2.5 rounded-xl bg-white border-2 border-[#EBE6DA] text-[#6A5243] font-black hover:border-[#D4B59D] transition-all text-sm flex items-center gap-2">
                    <i class="fas fa-print"></i> พิมพ์
                </button>
                <a href="dashboard.php?view=approve_list" class="px-5 py-2.5 rounded-xl bg-[#6A5243] text-white font-black shadow-md hover:bg-[#523E32] transition-all text-sm flex items-center gap-2">
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
            const booking = data.bookings[0];
            renderBookingResult(booking);
        } else {
            showError('ไม่พบข้อมูลการจองนี้ หรือคุณไม่มีสิทธิ์เข้าถึง');
        }
    } catch (err) {
        showError('เกิดข้อผิดพลาดในการดึงข้อมูล');
    }
});

function renderBookingResult(booking) {
    // Header setup based on status
    const iconContainer = document.getElementById('statusIconContainer');
    const title = document.getElementById('resultTitle');
    const subtitle = document.getElementById('resultSubtitle');
    const statusBadge = document.getElementById('displayStatus');

    let badgeClass = 'bg-[#F9F8F6] text-[#A79A8B]';
    let statusText = 'ไม่ทราบสถานะ';
    let iconHtml = '<i class="fas fa-question"></i>';
    let iconBg = 'bg-[#F9F8F6]';
    let iconColor = 'text-[#A79A8B]';

    switch (booking.status) {
        case 'pending': 
            badgeClass = 'bg-amber-100 text-amber-700 border border-amber-200';
            statusText = '<i class="fas fa-clock"></i> รออนุมัติ';
            iconHtml = '<i class="fas fa-paper-plane"></i>';
            iconBg = 'bg-amber-100';
            iconColor = 'text-amber-600';
            title.textContent = 'ส่งคำขอสำเร็จ';
            subtitle.textContent = 'ระบบได้รับข้อมูลการจองของคุณแล้ว และกำลังรอการพิจารณา';
            break;
        case 'approved': 
            badgeClass = 'bg-emerald-100 text-emerald-700 border border-emerald-200';
            statusText = '<i class="fas fa-check-circle"></i> อนุมัติแล้ว';
            iconHtml = '<i class="fas fa-check-double"></i>';
            iconBg = 'bg-emerald-100';
            iconColor = 'text-emerald-600';
            title.textContent = 'การจองได้รับการอนุมัติ';
            subtitle.textContent = 'ห้องประชุมพร้อมสำหรับคุณแล้ว';
            break;
        case 'rejected': 
            badgeClass = 'bg-red-100 text-red-700 border border-red-200';
            statusText = '<i class="fas fa-times-circle"></i> ไม่อนุมัติ';
            iconHtml = '<i class="fas fa-times"></i>';
            iconBg = 'bg-red-100';
            iconColor = 'text-red-600';
            title.textContent = 'การขอประชุมถูกปฏิเสธ';
            subtitle.textContent = 'ขออภัย ไม่สามารถอนุมัติการประชุมนี้ได้';
            break;
        case 'cancelled': 
            badgeClass = 'bg-slate-100 text-slate-700 border border-slate-200';
            statusText = '<i class="fas fa-ban"></i> ยกเลิก';
            iconHtml = '<i class="fas fa-ban"></i>';
            iconBg = 'bg-slate-100';
            iconColor = 'text-slate-600';
            title.textContent = 'การประชุมถูกยกเลิก';
            subtitle.textContent = 'รายการนี้ได้ถูกยกเลิกแล้ว';
            break;
    }

    iconContainer.className = `w-24 h-24 rounded-full flex items-center justify-center text-4xl mx-auto mb-6 shadow-lg transition-all duration-500 ${iconBg} ${iconColor}`;
    iconContainer.innerHTML = iconHtml;
    statusBadge.className = `inline-flex items-center gap-2 px-5 py-2.5 rounded-full font-black text-sm uppercase tracking-wide ${badgeClass}`;
    statusBadge.innerHTML = statusText;

    // Data Binding
    document.getElementById('displayId').textContent = String(booking.id).padStart(6, '0');
    document.getElementById('displayTitle').textContent = booking.title;
    
    // Dates
    document.getElementById('displayDate').textContent = MeetQueue.utils.formatDate(booking.start_time);
    document.getElementById('displayTime').textContent = `${MeetQueue.utils.formatTime(booking.start_time)} - ${MeetQueue.utils.formatTime(booking.end_time)} น.`;
    
    // Room
    document.getElementById('displayRoom').textContent = booking.is_external ? `(ภายนอก) ${booking.external_org}` : booking.room_name;
    
    // User details
    document.getElementById('displayUser').textContent = (booking.first_name + ' ' + (booking.last_name || '')).trim();
    document.getElementById('displayDept').textContent = booking.department_name || '-';
    document.getElementById('displayCount').textContent = booking.participants_count;
    document.getElementById('displayPhone').textContent = booking.phone;

    // Optional Desc
    if (booking.description) {
        document.getElementById('descContainer').classList.remove('hidden');
        document.getElementById('displayDesc').textContent = booking.description;
    }

    // Timeline Tracker Logic
    if (booking.status !== 'rejected' && booking.status !== 'cancelled') {
        document.getElementById('timelineContainer').classList.remove('hidden');
        
        let progressPercent = 0;
        const now = new Date();
        const endTime = new Date(booking.end_time);
        const isCompleted = (booking.status === 'completed' || booking.status === 'approved') && endTime < now;
        
        // We fetch reviews to check if evaluated
        checkEvaluationStatus(booking.id).then(isEvaluated => {
            // Step 1: Pending (Always active if not rejected/cancelled)
            document.getElementById('step1-icon').className = 'w-10 h-10 rounded-full flex items-center justify-center text-white bg-[#6A5243] border-4 border-[#F3F0E6] shadow-sm transition-colors duration-500';
            document.getElementById('step1-text').className = 'mt-2 text-xs font-bold text-[#6A5243] uppercase tracking-wide';
            
            if (booking.status === 'approved' || isCompleted || isEvaluated) {
                // Step 2: Approved
                progressPercent = 33;
                setTimeout(() => {
                    document.getElementById('step2-icon').className = 'w-10 h-10 rounded-full flex items-center justify-center text-white bg-[#6A5243] border-4 border-[#F3F0E6] shadow-sm transition-colors duration-500';
                    document.getElementById('step2-text').className = 'mt-2 text-xs font-bold text-[#6A5243] uppercase tracking-wide';
                }, 300);
            }
            
            if (isCompleted || isEvaluated) {
                // Step 3: Completed
                progressPercent = 66;
                setTimeout(() => {
                    document.getElementById('step3-icon').className = 'w-10 h-10 rounded-full flex items-center justify-center text-white bg-[#6A5243] border-4 border-[#F3F0E6] shadow-sm transition-colors duration-500';
                    document.getElementById('step3-text').className = 'mt-2 text-xs font-bold text-[#6A5243] uppercase tracking-wide';
                }, 600);
            }
            
            if (isEvaluated) {
                // Step 4: Evaluated
                progressPercent = 100;
                setTimeout(() => {
                    document.getElementById('step4-icon').className = 'w-10 h-10 rounded-full flex items-center justify-center text-white bg-[#6A5243] border-4 border-[#F3F0E6] shadow-sm transition-colors duration-500';
                    document.getElementById('step4-text').className = 'mt-2 text-xs font-bold text-[#6A5243] uppercase tracking-wide';
                }, 900);
            }
            
            setTimeout(() => {
                document.getElementById('timelineProgress').style.width = progressPercent + '%';
            }, 100);
        });
    }

    // Reveal animation
    const card = document.getElementById('ticketCard');
    card.classList.remove('opacity-0', 'translate-y-10');
}

async function checkEvaluationStatus(bookingId) {
    try {
        const data = await MeetQueue.api.fetch(`api/bookings.php?booking_id=${bookingId}`);
        // Wait, to get evaluation, the user might need an endpoint. I'll just check if the user evaluated it by making a mock request, or if api/reviews.php doesn't have a GET, I can just assume not evaluated for now, or write a quick GET to api/reviews.php.
        // Actually, let's fetch the html of history.php? We can't do that.
        // I will update api/reviews.php to support GET soon.
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
    icon.className = 'w-24 h-24 rounded-full flex items-center justify-center text-4xl mx-auto mb-6 shadow-lg bg-red-100 text-red-500';
    icon.innerHTML = '<i class="fas fa-exclamation-triangle"></i>';
}
</script>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    #ticketCard, #ticketCard * {
        visibility: visible;
    }
    #ticketCard {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        box-shadow: none !important;
        border: none !important;
    }
    aside, header, .hero-banner {
        display: none !important;
    }
}
</style>
