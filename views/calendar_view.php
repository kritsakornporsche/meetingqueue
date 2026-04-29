<?php
require_once 'api/config.php';
use App\Repository\BookingRepository;

$repo = new BookingRepository();
$rooms = \App\Core\Database::getInstance()->getConnection()->query("SELECT * FROM rooms")->fetchAll();

// Get recent bookings using Repository
$recent_bookings = $repo->getAll();
$recent_bookings = array_slice($recent_bookings, 0, 9);
?>

<style>
    /* Robust Layout CSS to prevent Tailwind CDN caching issues */
    .dash-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: var(--space-md);
        min-height: calc(100dvh - 80px);
        max-width: 100%;
        box-sizing: border-box;
    }
    .dash-grid > div {
        min-width: 0;
    }
    @media (min-width: 1280px) {
        .dash-grid { grid-template-columns: 320px minmax(0, 1fr); gap: var(--space-lg); }
    }
    
    /* Mobile-first Room Cards Layout */
    .room-card-wrapper {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(min(280px, 100%), 1fr));
        gap: 0.75rem;
        width: 100%;
        box-sizing: border-box;
    }
    
    @media (min-width: 1280px) {
        .room-card-wrapper {
            display: flex;
            flex-direction: column;
        }
    }
    .room-card {
        background: white;
        border-radius: 1rem;
        padding: 1rem;
        border: 1px solid rgba(212, 181, 157, 0.2);
        width: 100%;
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .room-card:hover {
        border-color: rgba(212, 181, 157, 0.6);
        box-shadow: 0 4px 12px rgba(106, 82, 67, 0.05);
        transform: translateY(-2px);
    }
    .room-card.active {
        border-color: #6A5243;
        box-shadow: 0 0 0 1px #6A5243, 0 4px 12px rgba(106, 82, 67, 0.1);
        background: #FDFBF7;
    }
    .room-card.active .room-card-icon {
        background: #6A5243;
        color: white;
    }
    .room-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        width: 100%;
        gap: 0.5rem;
    }
    .room-card-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        min-width: 0;
        flex: 1;
    }
    .room-card-icon {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 0.75rem;
        background: linear-gradient(to bottom right, #EBE6DA, #D4B59D);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6A5243;
        font-weight: bold;
        font-size: 0.875rem;
        flex-shrink: 0;
    }
    .room-card-text {
        flex: 1;
        min-width: 0;
        display: flex;
        flex-direction: column;
    }
    .room-card-title {
        font-weight: 700;
        color: #6A5243;
        font-size: 0.875rem;
        line-height: 1.25;
        white-space: normal;
        word-break: break-word;
        margin: 0 0 0.25rem 0;
    }
    .room-card-subtitle {
        font-size: 0.65rem;
        color: #A79A8B;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin: 0;
    }
    .room-card-badge {
        padding: 0.125rem 0.5rem;
        border-radius: 9999px;
        font-size: 0.65rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 0.25rem;
        flex-shrink: 0;
    }
    .room-card-footer {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.7rem;
        color: #A79A8B;
        font-weight: 500;
        flex-wrap: wrap;
    }
    
    .bottom-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(min(300px, 100%), 1fr));
        gap: var(--space-md);
        margin-top: var(--space-md);
        min-width: 0;
    }
</style>

<div class="dash-grid">
    
    <!-- Left Panel: Room List (3 columns) -->
    <div class="flex flex-col gap-4" style="padding-right: 0.5rem;">
        <div class="flex items-center justify-between mb-2">
            <div>
                <h2 class="text-xl font-bold text-[#6A5243]">ห้องประชุม</h2>
                <p class="text-xs text-[#A79A8B]">ทั้งหมด <?= count($rooms) ?> ห้อง</p>
            </div>
            <button class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-[#6A5243] shadow-sm hover:bg-[#EBE6DA] transition-colors border border-[#D4B59D]/30">
                <i class="fas fa-sliders-h text-xs"></i>
            </button>
        </div>
        
        <!-- Filter Pills -->
        <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-hide">
            <button class="px-4 py-1.5 rounded-full bg-[#6A5243] text-white text-xs font-semibold whitespace-nowrap shadow-sm">ทั้งหมด</button>
            <button class="px-4 py-1.5 rounded-full bg-white text-[#6A5243] text-xs font-semibold whitespace-nowrap border border-[#D4B59D]/30 hover:bg-[#F3F0E6]">ว่าง</button>
            <button class="px-4 py-1.5 rounded-full bg-white text-[#6A5243] text-xs font-semibold whitespace-nowrap border border-[#D4B59D]/30 hover:bg-[#F3F0E6]">ไม่ว่าง</button>
        </div>
        
        <!-- Search -->
        <div class="relative mb-2 w-full box-border">
            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-[#A79A8B] text-xs"></i>
            <input type="text" placeholder="ค้นหาห้องประชุม..." class="w-full pl-9 pr-4 py-2.5 rounded-xl bg-white border border-[#D4B59D]/30 text-sm focus:outline-none focus:ring-2 focus:ring-[#D4B59D]/50 text-[#6A5243] shadow-sm placeholder-[#A79A8B] box-border">
        </div>
        
        <!-- Room Cards -->
        <div class="room-card-wrapper">
            <?php foreach($rooms as $index => $room): 
                $statusColors = ['color: #1E8E3E; background: #E6F4EA;', 'color: #D93025; background: #FCE8E6;', 'color: #F29900; background: #FEF7E0;'];
                $statusText = ['ว่าง', 'ไม่ว่าง', 'บางส่วน'];
                $rand = $index % 3;
            ?>
            <div class="room-card relative group" data-room-id="<?= $room['id'] ?>" onclick="filterCalendarByRoom(<?= $room['id'] ?>, this)">
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-gradient-to-b from-[#D4B59D] to-[#6A5243] opacity-0 group-hover:opacity-100 transition-opacity" style="border-radius: 1rem 0 0 1rem;"></div>
                
                <div class="room-card-header">
                    <div class="room-card-info">
                        <div class="room-card-icon text-lg font-black">
                            <?= !empty($room['room_number']) ? htmlspecialchars($room['room_number']) : '<i class="fas fa-building text-base"></i>' ?>
                        </div>
                        <div class="room-card-text">
                            <h4 class="room-card-title" title="<?= htmlspecialchars($room['name']) ?>"><?= htmlspecialchars($room['name']) ?></h4>
                            <p class="room-card-subtitle">อาคารหลัก ชั้น 1</p>
                        </div>
                    </div>
                    <span class="room-card-badge" style="<?= $statusColors[$rand] ?>">
                        <span style="width: 6px; height: 6px; border-radius: 50%; background-color: currentColor;"></span>
                        <?= $statusText[$rand] ?>
                    </span>
                </div>
                
                <div class="room-card-footer">
                    <span style="display: flex; align-items: center; gap: 0.25rem; white-space: nowrap;">
                        <i class="fas fa-users" style="color: #D4B59D;"></i> <?= $room['capacity'] ?> คน
                    </span>
                    <span style="display: flex; align-items: center; gap: 0.25rem; white-space: nowrap;">
                        <i class="fas fa-tv" style="color: #D4B59D;"></i> TV/Projector
                    </span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- Right Panel (9 columns) -->
    <div class="flex flex-col min-w-0 w-full">
        
        <!-- Top: Calendar Section -->
        <div class="dash-card flex-grow relative overflow-hidden mb-6 w-full box-border">
            <!-- Decorative accent -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-[#D4B59D]/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
            
            <div class="flex flex-wrap justify-between items-center mb-6 relative z-10 gap-4">
                <div class="min-w-0">
                    <h2 class="text-xl font-bold text-[#6A5243] flex items-center gap-2 truncate">
                        <i class="fas fa-calendar-alt text-[#D4B59D]"></i> ปฏิทินการจองรวม
                    </h2>
                    <p class="text-xs text-[#A79A8B] mt-1">อัปเดตแบบ Real-time</p>
                </div>
                <a href="dashboard.php?view=book" class="px-5 py-2.5 rounded-xl bg-[#6A5243] text-white text-sm font-bold shadow-md hover:bg-[#523E32] hover:-translate-y-0.5 transition-all flex items-center gap-2 flex-shrink-0 whitespace-nowrap" style="box-sizing: border-box;">
                    <i class="fas fa-plus"></i> จองห้องประชุม
                </a>
            </div>
            <div id="calendar" class="relative z-10"></div>
        </div>
        
        <!-- Bottom Cards -->
        <div class="grid grid-cols-1 gap-6 mt-6">
            <!-- Status / Timeline -->
            <div class="dash-card flex flex-col">
                <div class="flex justify-between items-center mb-5">
                    <h3 class="font-bold text-[#6A5243] text-lg">สถานะการจองล่าสุด</h3>
                    <a href="dashboard.php?view=approve_list" class="text-xs font-bold text-[#D4B59D] hover:text-[#6A5243] transition-colors">ดูทั้งหมด</a>
                </div>
                
                <div class="flex-grow">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-y-8 gap-x-12 relative pl-6 border-l-2 border-[#EBE6DA]">
                        <?php 
                        // Show more bookings since we have more space
                        $extended_recent = array_slice($recent_bookings, 0, 9);
                        foreach($extended_recent as $i => $rb): 
                            $statusColors = [
                                'approved' => ['bg-[#10b981]', 'text-[#10b981]'],
                                'pending' => ['bg-[#f59e0b]', 'text-[#f59e0b]'],
                                'rejected' => ['bg-[#ef4444]', 'text-[#ef4444]'],
                                'completed' => ['bg-[#6A5243]', 'text-[#6A5243]']
                            ];
                            $color = $statusColors[$rb['status']] ?? ['bg-gray-400', 'text-gray-400'];
                        ?>
                        <div class="relative">
                            <div class="absolute -left-[31px] top-1 w-4 h-4 rounded-full <?= $color[0] ?> ring-4 ring-white"></div>
                            <h4 class="text-sm font-bold text-[#6A5243]"><?= htmlspecialchars($rb['title']) ?></h4>
                            <p class="text-xs text-[#A79A8B] mt-0.5">
                                <?= $rb['room_name'] ?? 'ภายนอก' ?> • จองโดย <?= $rb['first_name'] ?>
                            </p>
                            <p class="text-[0.65rem] font-semibold <?= $color[1] ?> mt-1 uppercase tracking-wider">
                                <?= $rb['status'] ?>
                            </p>
                        </div>
                        <?php endforeach; ?>
                        
                        <?php if(empty($recent_bookings)): ?>
                            <p class="text-sm text-[#A79A8B]">ไม่มีประวัติการจอง</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Compact Calendar Styling for Dashboard */
    #calendar {
        min-height: 400px;
    }
    .fc .fc-toolbar-title {
        font-size: 1.25rem !important;
        font-weight: 700;
        color: var(--text-main);
    }
    .fc .fc-button-primary {
        background: #F9F8F6;
        border: 1px solid rgba(212, 181, 157, 0.3);
        color: var(--text-muted);
        text-transform: capitalize;
        border-radius: 0.5rem;
        font-size: 0.8rem;
        font-weight: 600;
        padding: 0.4rem 0.8rem;
    }
    .fc .fc-button-primary:not(:disabled):active,
    .fc .fc-button-primary:not(:disabled).fc-button-active {
        background: var(--primary) !important;
        border-color: var(--primary) !important;
        color: white !important;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.1) !important;
    }
    .fc-theme-standard td, .fc-theme-standard th, .fc-theme-standard .fc-scrollgrid {
        border-color: rgba(212, 181, 157, 0.2);
    }
    .fc-col-header-cell-cushion {
        color: #A79A8B;
        font-weight: 600;
        font-size: 0.8rem;
        padding: 0.5rem !important;
    }
    .fc-daygrid-day-number {
        font-weight: 700;
        color: #6A5243;
    }
    
    /* Weekend / Holiday Styling */
    .fc-day-sun, .fc-day-public-holiday {
        background-color: rgba(239, 68, 68, 0.03) !important;
    }
    .fc-day-sat {
        background-color: rgba(59, 130, 246, 0.03) !important;
    }
    .fc-day-sun .fc-col-header-cell-cushion,
    .fc-day-sun .fc-daygrid-day-number,
    .fc-day-public-holiday .fc-col-header-cell-cushion,
    .fc-day-public-holiday .fc-daygrid-day-number {
        color: #ef4444 !important; /* Red for Sunday and Holidays */
    }
    .fc-day-sat .fc-col-header-cell-cushion,
    .fc-day-sat .fc-daygrid-day-number {
        color: #3b82f6 !important; /* Blue for Saturday */
    }

    .fc-event {
        border: none !important;
        border-radius: 0.5rem !important;
        padding: 2px 4px;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
</style>

<!-- Event Detail Modal -->
<div id="eventModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-900/40 transition-opacity backdrop-blur-[2px]" id="modalBackdrop"></div>
    <div class="flex min-h-full items-center justify-center p-4 text-center" style="padding: 1.5rem;">
        <div class="relative transform overflow-hidden rounded-3xl bg-white/95 backdrop-blur-xl text-left shadow-2xl transition-all w-full max-w-2xl border border-white/60 ring-1 ring-black/5">
            <div style="padding: 2rem;">
                <div class="flex items-start justify-between border-b border-[#6A5243]/10 pb-6 mb-6" style="padding-bottom: 1.5rem; margin-bottom: 1.5rem;">
                    <div class="flex items-center gap-5" style="gap: 1.25rem;">
                        <div class="flex h-16 w-16 flex-shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-[#EBE6DA] to-[#D4B59D] shadow-inner" style="width: 4rem; height: 4rem;">
                            <i class="fas fa-calendar-check text-[#6A5243] text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold leading-tight text-[#6A5243] mb-2" id="modalTitle" style="margin-bottom: 0.5rem;">รายละเอียดการจอง</h3>
                            <span id="modalStatus" class="inline-block px-3 py-1 text-xs font-bold rounded-full tracking-wide">สถานะ</span>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8" style="row-gap: 1.5rem; column-gap: 2rem;">
                    <div class="flex items-start gap-4" style="gap: 1rem;">
                        <div class="mt-1 flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-[#EBE6DA]/50" style="width: 2.5rem; height: 2.5rem;">
                            <i class="fas fa-door-open text-[#A79A8B]"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-[#A79A8B] uppercase tracking-wider mb-1" style="margin-bottom: 0.25rem;">ห้องประชุม</p>
                            <p class="text-base font-semibold text-[#6A5243]" id="modalRoom">N/A</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4" style="gap: 1rem;">
                        <div class="mt-1 flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-[#EBE6DA]/50" style="width: 2.5rem; height: 2.5rem;">
                            <i class="fas fa-clock text-[#A79A8B]"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-[#A79A8B] uppercase tracking-wider mb-1" style="margin-bottom: 0.25rem;">วันและเวลา</p>
                            <p class="text-base font-semibold text-[#6A5243]" id="modalTime">N/A</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4" style="gap: 1rem;">
                        <div class="mt-1 flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-[#EBE6DA]/50" style="width: 2.5rem; height: 2.5rem;">
                            <i class="fas fa-users text-[#A79A8B]"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-[#A79A8B] uppercase tracking-wider mb-1" style="margin-bottom: 0.25rem;">จำนวนผู้เข้าร่วม</p>
                            <p class="text-base font-semibold text-[#6A5243]" id="modalParticipants">N/A</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4" style="gap: 1rem;">
                        <div class="mt-1 flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-[#EBE6DA]/50" style="width: 2.5rem; height: 2.5rem;">
                            <i class="fas fa-user-circle text-[#A79A8B]"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-[#A79A8B] uppercase tracking-wider mb-1" style="margin-bottom: 0.25rem;">ผู้จอง</p>
                            <p class="text-base font-semibold text-[#6A5243]" id="modalUser">N/A</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-[#EBE6DA]/30 px-8 py-5 border-t border-[#6A5243]/10 flex justify-end" style="padding: 1.25rem 2rem;">
                <button type="button" id="closeModalBtn" class="inline-flex justify-center rounded-xl bg-white px-8 py-2.5 text-sm font-bold text-[#6A5243] shadow-sm hover:bg-gray-50 transition-all duration-200 border border-[#D4B59D]/50 hover:shadow-md hover:-translate-y-0.5" style="padding: 0.625rem 2rem;">ปิดหน้าต่าง</button>
            </div>
        </div>
    </div>
</div>

<script>
    let calendarInstance = null;
    let currentRoomFilter = 'all';

    function filterCalendarByRoom(roomId, element) {
        if (currentRoomFilter === roomId) {
            // Toggle off (show all)
            currentRoomFilter = 'all';
            element.classList.remove('active');
        } else {
            // Toggle on
            currentRoomFilter = roomId;
            document.querySelectorAll('.room-card').forEach(c => c.classList.remove('active'));
            element.classList.add('active');
        }
        
        if (calendarInstance) {
            let eventSource = calendarInstance.getEventSources()[0];
            if (eventSource) eventSource.remove();
            calendarInstance.addEventSource('api/calendar_events.php?room_id=' + currentRoomFilter);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // รายการวันหยุดข้าราชการ (รูปแบบ MM-DD)
        const publicHolidays = {
            '01-01': 'วันขึ้นปีใหม่',
            '04-06': 'วันจักรี',
            '04-13': 'วันสงกรานต์',
            '04-14': 'วันสงกรานต์',
            '04-15': 'วันสงกรานต์',
            '05-01': 'วันแรงงานแห่งชาติ',
            '05-04': 'วันฉัตรมงคล',
            '06-03': 'วันเฉลิมฯ พระราชินี',
            '07-28': 'วันเฉลิมฯ ร.10',
            '08-12': 'วันแม่แห่งชาติ',
            '10-13': 'วันนวมินทรฯ',
            '10-23': 'วันปิยมหาราช',
            '12-05': 'วันพ่อแห่งชาติ',
            '12-10': 'วันรัฐธรรมนูญ',
            '12-31': 'วันสิ้นปี'
        };

        var calendarEl = document.getElementById('calendar');
        calendarInstance = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'th',
            height: 'auto',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            buttonText: {
                today: 'วันนี้',
                month: 'เดือน',
                week: 'สัปดาห์',
                day: 'วัน'
            },
            dayCellClassNames: function(arg) {
                let month = String(arg.date.getMonth() + 1).padStart(2, '0');
                let day = String(arg.date.getDate()).padStart(2, '0');
                let md = month + '-' + day;
                if (publicHolidays[md]) {
                    return ['fc-day-public-holiday'];
                }
                return [];
            },
            dayCellDidMount: function(arg) {
                let month = String(arg.date.getMonth() + 1).padStart(2, '0');
                let day = String(arg.date.getDate()).padStart(2, '0');
                let md = month + '-' + day;
                if (publicHolidays[md]) {
                    let label = document.createElement('div');
                    label.style.fontSize = '0.65rem';
                    label.style.color = '#ef4444';
                    label.style.padding = '0 4px';
                    label.style.whiteSpace = 'nowrap';
                    label.style.overflow = 'hidden';
                    label.style.textOverflow = 'ellipsis';
                    label.style.width = '100%';
                    label.style.textAlign = 'right';
                    label.innerText = publicHolidays[md];
                    
                    let frame = arg.el.querySelector('.fc-daygrid-day-top');
                    if (frame) {
                        frame.style.flexDirection = 'column';
                        frame.style.alignItems = 'flex-end';
                        frame.appendChild(label);
                    }
                }
            },
            datesSet: function() {
                var titleEl = document.querySelector('.fc-toolbar-title');
                if (titleEl) {
                    var text = titleEl.innerText;
                    var newText = text.replace(/\d{4}/g, function(match) {
                        var year = parseInt(match);
                        if (year < 2500) {
                            return year + 543;
                        }
                        return year;
                    });
                    if (text !== newText) {
                        titleEl.innerText = newText;
                    }
                }
            },
            events: 'api/calendar_events.php',
            eventClick: function(info) {
                const props = info.event.extendedProps;
                const start = info.event.start.toLocaleString('th-TH', { dateStyle: 'long', timeStyle: 'short' });
                const end = info.event.end ? info.event.end.toLocaleString('th-TH', { timeStyle: 'short' }) : '';
                
                document.getElementById('modalTitle').textContent = props.original_title || info.event.title;
                document.getElementById('modalRoom').textContent = props.room;
                document.getElementById('modalTime').textContent = start + (end ? ' - ' + end : '');
                document.getElementById('modalParticipants').textContent = (props.participants || 0) + ' คน';
                document.getElementById('modalUser').textContent = props.user || 'N/A';
                
                const statusBadge = document.getElementById('modalStatus');
                let statusText = 'รออนุมัติ';
                let statusColor = 'bg-[#EAE4D3] text-[#6E4B3A] border border-[#D2CAB7]';
                
                if (props.status === 'approved') {
                    statusText = 'อนุมัติแล้ว';
                    statusColor = 'bg-[#E6F4EA] text-[#1E8E3E] border border-[#1E8E3E]/20';
                } else if (props.status === 'rejected') {
                    statusText = 'ไม่อนุมัติ';
                    statusColor = 'bg-[#FCE8E6] text-[#D93025] border border-[#D93025]/20';
                } else if (props.status === 'completed') {
                    statusText = 'เสร็จสิ้น';
                    statusColor = 'bg-[#EBE6DA] text-[#6A5243] border border-[#D4B59D]/40';
                }
                
                statusBadge.textContent = statusText;
                statusBadge.className = `inline-block px-3 py-1 text-xs font-bold rounded-full mb-3 ${statusColor}`;
                
                document.getElementById('eventModal').classList.remove('hidden');
            }
        });
        calendarInstance.render();
        
        const closeModal = () => document.getElementById('eventModal').classList.add('hidden');
        document.getElementById('closeModalBtn').addEventListener('click', closeModal);
        document.getElementById('modalBackdrop').addEventListener('click', closeModal);
    });
</script>
