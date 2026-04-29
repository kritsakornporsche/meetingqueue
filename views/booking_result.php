<?php
$bookingId = $_GET['id'] ?? null;

if (!$bookingId) {
    echo "<div class='text-center py-20 text-red-500 font-bold'>ไม่พบรหัสการจอง</div>";
    return;
}
?>

<div class="max-w-[800px] mx-auto py-12 px-6 md:px-10">
    <!-- Result Header -->
    <div class="text-center mb-12">
        <div id="statusIconContainer" class="w-24 h-24 rounded-full bg-[#EBE6DA] text-[#A79A8B] flex items-center justify-center text-4xl mx-auto mb-6 shadow-lg transition-all duration-500">
            <i class="fas fa-circle-notch fa-spin"></i>
        </div>
        <h2 id="resultTitle" class="text-4xl font-black text-[#6A5243] mb-4">กำลังโหลดข้อมูล...</h2>
        <p id="resultSubtitle" class="text-lg text-[#A79A8B] font-semibold">กรุณารอสักครู่</p>
    </div>

    <!-- Ticket / Receipt Card -->
    <div id="ticketCard" class="bg-white rounded-[2.5rem] shadow-[0_20px_60px_rgba(106,82,67,0.08)] border border-[#EBE6DA] overflow-hidden relative opacity-0 transform translate-y-10 transition-all duration-700">
        <!-- Top accent line -->
        <div class="h-3 w-full bg-gradient-to-r from-[#D4B59D] to-[#6A5243]"></div>
        
        <div class="p-10 md:p-14">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 pb-8 border-b-2 border-dashed border-[#EBE6DA]">
                <div>
                    <h3 class="text-[0.7rem] font-bold text-[#A79A8B] uppercase tracking-widest mb-2">รหัสอ้างอิงการจอง</h3>
                    <div class="text-3xl font-bold text-[#6A5243] font-mono tracking-wider">#<span id="displayId">...</span></div>
                </div>
                <div class="mt-4 md:mt-0 text-left md:text-right">
                    <span id="displayStatus" class="inline-flex items-center gap-2 px-6 py-3 rounded-full font-bold text-sm uppercase tracking-wide shadow-sm">
                        ...
                    </span>
                </div>
            </div>

            <div class="space-y-10">
                <!-- Topic -->
                <div>
                    <label class="text-[0.7rem] font-bold text-[#A79A8B] uppercase tracking-[0.2em] mb-3 block">หัวข้อ/เรื่องการประชุม</label>
                    <div id="displayTitle" class="text-2xl font-bold text-[#6A5243] leading-tight">...</div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <!-- Date & Time -->
                    <div class="bg-[#F9F8F6] rounded-[2rem] p-8 border border-[#EBE6DA]">
                        <div class="flex items-start gap-5">
                            <div class="w-14 h-14 rounded-2xl bg-white flex items-center justify-center text-[#D4B59D] shadow-sm flex-shrink-0">
                                <i class="fas fa-calendar-day text-xl"></i>
                            </div>
                            <div>
                                <label class="text-[0.65rem] font-bold text-[#A79A8B] uppercase tracking-[0.2em] mb-2 block">วันและเวลาที่จอง</label>
                                <div id="displayDate" class="font-bold text-lg text-[#6A5243]">...</div>
                                <div id="displayTime" class="text-base font-semibold text-[#A79A8B] mt-1">...</div>
                            </div>
                        </div>
                    </div>

                    <!-- Room -->
                    <div class="bg-[#F9F8F6] rounded-[2rem] p-8 border border-[#EBE6DA]">
                        <div class="flex items-start gap-5">
                            <div class="w-14 h-14 rounded-2xl bg-white flex items-center justify-center text-[#D4B59D] shadow-sm flex-shrink-0">
                                <i class="fas fa-door-open text-xl"></i>
                            </div>
                            <div>
                                <label class="text-[0.65rem] font-bold text-[#A79A8B] uppercase tracking-[0.2em] mb-2 block">ห้องประชุม</label>
                                <div id="displayRoom" class="font-bold text-lg text-[#6A5243] line-clamp-2">...</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Extra Info -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-8 pt-4">
                    <div>
                        <label class="text-[0.65rem] font-bold text-[#A79A8B] uppercase tracking-[0.15em] mb-2 block">ผู้จอง</label>
                        <div id="displayUser" class="font-bold text-[#6A5243]">...</div>
                    </div>
                    <div>
                        <label class="text-[0.65rem] font-bold text-[#A79A8B] uppercase tracking-[0.15em] mb-2 block">หน่วยงาน</label>
                        <div id="displayDept" class="font-bold text-[#6A5243]">...</div>
                    </div>
                    <div>
                        <label class="text-[0.65rem] font-bold text-[#A79A8B] uppercase tracking-[0.15em] mb-2 block">จำนวนผู้เข้าใช้</label>
                        <div id="displayCount" class="font-bold text-[#6A5243]">...</div>
                    </div>
                    <div>
                        <label class="text-[0.65rem] font-bold text-[#A79A8B] uppercase tracking-[0.15em] mb-2 block">เบอร์ติดต่อ</label>
                        <div id="displayPhone" class="font-bold text-[#6A5243]">...</div>
                    </div>
                </div>
                
                <!-- Description (Optional) -->
                <div id="descContainer" class="hidden pt-6">
                    <label class="text-[0.65rem] font-bold text-[#A79A8B] uppercase tracking-[0.15em] mb-3 block">หมายเหตุเพิ่มเติม</label>
                    <div id="displayDesc" class="p-6 bg-[#FDFBF7] rounded-[1.5rem] text-sm font-medium text-[#6A5243] border border-[#EBE6DA] leading-relaxed"></div>
                </div>
            </div>
        </div>

        <!-- Ticket Footer -->
        <div class="bg-[#F9F8F6] p-8 md:px-14 flex flex-col md:flex-row items-center justify-between gap-6 border-t border-[#EBE6DA]">
            <p class="text-[0.7rem] font-bold text-[#A79A8B] text-center md:text-left flex items-center gap-2">
                <i class="fas fa-info-circle text-[#D4B59D]"></i> โปรดเก็บรหัสอ้างอิงไว้เพื่อใช้สำหรับติดตามสถานะการจอง
            </p>
            <div class="flex gap-4">
                <button onclick="window.print()" class="px-6 py-3 rounded-xl bg-white border-2 border-[#EBE6DA] text-[#6A5243] font-bold hover:border-[#D4B59D] transition-all text-sm flex items-center gap-2 shadow-sm">
                    <i class="fas fa-print"></i> พิมพ์เอกสาร
                </button>
                <a href="dashboard.php?view=approve_list" class="px-6 py-3 rounded-xl bg-[#6A5243] text-white font-bold shadow-lg hover:bg-[#523E32] transition-all text-sm flex items-center gap-2">
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
        const res = await fetch(`api/bookings.php?booking_id=${bookingId}`);
        const data = await res.json();
        
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
            title.textContent = 'การจองถูกปฏิเสธ';
            subtitle.textContent = 'ขออภัย ไม่สามารถอนุมัติการจองนี้ได้';
            break;
        case 'cancelled': 
            badgeClass = 'bg-slate-100 text-slate-700 border border-slate-200';
            statusText = '<i class="fas fa-ban"></i> ยกเลิก';
            iconHtml = '<i class="fas fa-ban"></i>';
            iconBg = 'bg-slate-100';
            iconColor = 'text-slate-600';
            title.textContent = 'การจองถูกยกเลิก';
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
    const start = new Date(booking.start_time);
    const end = new Date(booking.end_time);
    
    document.getElementById('displayDate').textContent = start.toLocaleDateString('th-TH', {
        weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
    });
    document.getElementById('displayTime').textContent = `${start.toLocaleTimeString('th-TH', {hour:'2-digit', minute:'2-digit'})} - ${end.toLocaleTimeString('th-TH', {hour:'2-digit', minute:'2-digit'})} น.`;
    
    // Room
    document.getElementById('displayRoom').textContent = booking.is_external ? `(ภายนอก) ${booking.external_org}` : booking.room_name;
    
    // User details
    document.getElementById('displayUser').textContent = `${booking.first_name} ${booking.last_name}`;
    document.getElementById('displayDept').textContent = booking.department_name || '-';
    document.getElementById('displayCount').textContent = booking.participants_count;
    document.getElementById('displayPhone').textContent = booking.phone;

    // Optional Desc
    if (booking.description) {
        document.getElementById('descContainer').classList.remove('hidden');
        document.getElementById('displayDesc').textContent = booking.description;
    }

    // Reveal animation
    const card = document.getElementById('ticketCard');
    card.classList.remove('opacity-0', 'translate-y-10');
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
