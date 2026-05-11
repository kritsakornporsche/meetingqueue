<?php
require_once 'api/config.php';
use App\Repository\BookingRepository;

if (!isset($_SESSION['user_id'])) {
    exit('Unauthorized');
}

$repo = new BookingRepository();
$roomStats = $repo->getRoomUsageStats();
$deptStats = $repo->getDepartmentStats();

// Prepare data for Room Usage (Count)
$roomLabels = [];
$roomCounts = [];
$roomHours = [];
foreach ($roomStats as $stat) {
    $roomLabels[] = $stat['name'];
    $roomCounts[] = (int)$stat['total_bookings'];
    $roomHours[] = round((float)$stat['total_hours'], 1);
}

// Prepare data for Department Stats
$deptLabels = [];
$deptCounts = [];
foreach ($deptStats as $stat) {
    $deptLabels[] = $stat['department_name'];
    $deptCounts[] = (int)$stat['total_bookings'];
}

// Prepare data for Equipment Stats
$allBookings = $repo->getAll(['exclude_status' => 'cancelled']);
$eqStats = [
    'โปรเจกเตอร์' => 0,
    'ทีวี' => 0,
    'คอมพิวเตอร์' => 0,
    'ไมโครโฟน' => 0
];

foreach ($allBookings as $b) {
    $desc = strtolower($b['description'] ?? '');
    foreach ($eqStats as $key => &$count) {
        // Simple string matching to count equipment requests
        if (strpos($desc, $key) !== false || strpos($b['title'] ?? '', $key) !== false) {
            $count++;
        }
    }
}
$eqLabels = array_keys($eqStats);
$eqCounts = array_values($eqStats);

// Prepare data for Daily Gantt Chart (Today)
$today = date('Y-m-d');
$todayBookings = array_filter($allBookings, function($b) use ($today) {
    return strpos($b['start_time'], $today) === 0 && $b['status'] !== 'rejected' && $b['status'] !== 'cancelled';
});

// Group by room
$roomsForGantt = [];
foreach ($roomStats as $stat) {
    $roomsForGantt[$stat['name']] = []; // initialize
}

// Convert times to decimal hours (e.g. 09:30 -> 9.5)
foreach ($todayBookings as $b) {
    $roomName = $b['room_name'] ?? 'ภายนอก';
    if (!isset($roomsForGantt[$roomName])) {
        $roomsForGantt[$roomName] = [];
    }
    
    $startStr = date('H:i', strtotime($b['start_time']));
    $endStr = date('H:i', strtotime($b['end_time']));
    
    $startParts = explode(':', $startStr);
    $endParts = explode(':', $endStr);
    
    $startVal = (int)$startParts[0] + ((int)$startParts[1] / 60);
    $endVal = (int)$endParts[0] + ((int)$endParts[1] / 60);
    
    $roomsForGantt[$roomName][] = [
        'title' => $b['title'],
        'start' => $startVal,
        'end' => $endVal,
        'startStr' => $startStr,
        'endStr' => $endStr,
        'status' => $b['status']
    ];
}

$startHour = 8;
$endHour = 18;
$totalHours = $endHour - $startHour;
?>

<div class="flex flex-col gap-6 w-full animate-fade">
    <div class="flex flex-wrap justify-between items-center gap-4">
        <h2 class="text-2xl font-bold text-[#6A5243] flex items-center gap-2">
            <i class="fas fa-chart-pie text-[#D4B59D]"></i> สถิติการใช้งานห้องประชุม
        </h2>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-[1.5rem] p-8 shadow-sm border border-[#EBE6DA]/60 flex items-center gap-6 transition-all hover:shadow-md hover:border-[#D4B59D]/40">
            <div class="w-16 h-16 rounded-2xl bg-[#D4B59D]/15 flex items-center justify-center text-[#6A5243] text-2xl shadow-inner">
                <i class="fas fa-handshake"></i>
            </div>
            <div>
                <div class="text-[0.65rem] font-black text-[#A79A8B] uppercase tracking-widest mb-1">จำนวนการจองทั้งหมด</div>
                <div class="text-3xl font-black text-[#6A5243] leading-none"><?= array_sum($roomCounts) ?> <span class="text-sm font-bold text-[#A79A8B] ml-1">ครั้ง</span></div>
            </div>
        </div>
        
        <div class="bg-white rounded-[1.5rem] p-8 shadow-sm border border-[#EBE6DA]/60 flex items-center gap-6 transition-all hover:shadow-md hover:border-[#D4B59D]/40">
            <div class="w-16 h-16 rounded-2xl bg-[#D4B59D]/15 flex items-center justify-center text-[#6A5243] text-2xl shadow-inner">
                <i class="fas fa-clock"></i>
            </div>
            <div>
                <div class="text-[0.65rem] font-black text-[#A79A8B] uppercase tracking-widest mb-1">เวลาใช้งานรวม</div>
                <div class="text-3xl font-black text-[#6A5243] leading-none"><?= array_sum($roomHours) ?> <span class="text-sm font-bold text-[#A79A8B] ml-1">ชั่วโมง</span></div>
            </div>
        </div>

        <div class="bg-white rounded-[1.5rem] p-8 shadow-sm border border-[#EBE6DA]/60 flex items-center gap-6 transition-all hover:shadow-md hover:border-[#D4B59D]/40">
            <div class="w-16 h-16 rounded-2xl bg-[#D4B59D]/15 flex items-center justify-center text-[#6A5243] text-2xl shadow-inner">
                <i class="fas fa-sitemap"></i>
            </div>
            <div>
                <div class="text-[0.65rem] font-black text-[#A79A8B] uppercase tracking-widest mb-1">หน่วยงานที่ใช้งาน</div>
                <div class="text-3xl font-black text-[#6A5243] leading-none"><?= count($deptCounts) ?> <span class="text-sm font-bold text-[#A79A8B] ml-1">แผนก</span></div>
            </div>
        </div>
    </div>

    <!-- Charts Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Chart 0: Daily Gantt Chart -->
        <div class="bg-white rounded-[1.5rem] p-8 shadow-sm border border-[#EBE6DA]/60 lg:col-span-2 overflow-hidden">
            <div class="flex flex-wrap justify-between items-center gap-4 mb-8">
                <div class="flex items-center gap-4 p-2 pl-0">
                    <div class="w-12 h-12 rounded-2xl bg-[#D4B59D]/10 flex items-center justify-center text-[#D4B59D] text-xl shadow-sm border border-[#D4B59D]/20">
                        <i class="fas fa-stream"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-[#6A5243] leading-none">ตารางการใช้ห้องประชุมวันนี้</h3>
                        <p class="text-[0.65rem] font-bold text-[#A79A8B] mt-1.5 flex items-center gap-1">
                            <i class="far fa-calendar-alt opacity-50"></i> ประจำวันที่ <?= date('d/m/') . (date('Y') + 543) ?>
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-5 px-5 py-3 bg-[#F9F8F6] rounded-[1.25rem] border border-[#EBE6DA]/60 shadow-inner-light">
                    <div class="flex items-center gap-2.5">
                        <span class="w-3.5 h-3.5 rounded-full bg-[#10b981] shadow-sm border border-white"></span>
                        <span class="text-[0.7rem] font-black text-[#6A5243] uppercase tracking-wider">อนุมัติแล้ว</span>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <span class="w-3.5 h-3.5 rounded-full bg-[#f59e0b] shadow-sm border border-white"></span>
                        <span class="text-[0.7rem] font-black text-[#6A5243] uppercase tracking-wider">รออนุมัติ</span>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto custom-scrollbar select-none">
                <div id="ganttTimeline" class="relative pb-6" style="--sidebar-w: 240px; min-w: calc(var(--sidebar-w) + 700px);">
                    <!-- Sidebar Header & Resizer -->
                    <div class="absolute left-0 top-0 z-30 h-10 border-b border-r border-[#EBE6DA]/60 bg-[#FDFBF7] rounded-tl-xl flex items-center px-6" style="width: var(--sidebar-w);">
                        <span class="text-[0.65rem] font-black text-[#A79A8B] uppercase tracking-widest flex items-center gap-2">
                            <i class="fas fa-door-open opacity-50"></i> รายชื่อห้องประชุม
                        </span>
                    </div>
                    
                    <!-- Draggable Handle -->
                    <div id="sidebarResizer" class="absolute top-0 bottom-0 z-40 w-1.5 cursor-col-resize hover:bg-[#D4B59D]/40 transition-colors group" style="left: calc(var(--sidebar-w) - 3px);">
                        <div class="absolute inset-y-0 left-1/2 w-px bg-[#EBE6DA] group-hover:bg-[#D4B59D]"></div>
                    </div>

                    <!-- Timeline Header -->
                    <div class="flex border-b border-[#EBE6DA] pb-3 mb-6" style="margin-left: var(--sidebar-w);">
                        <?php for($h = $startHour; $h <= $endHour; $h++): ?>
                            <div class="flex-1 text-center relative">
                                <span class="text-[0.7rem] font-black text-[#A79A8B]"><?= sprintf('%02d:00', $h) ?></span>
                                <div class="absolute top-full left-1/2 w-px h-2 bg-[#EBE6DA]/60"></div>
                            </div>
                        <?php endfor; ?>
                    </div>
                    
                    <!-- Current Time Line Indicator -->
                    <?php 
                        $nowHour = (int)date('H');
                        $nowMin = (int)date('i');
                        $nowDecimal = $nowHour + ($nowMin / 60);
                        if ($nowDecimal >= $startHour && $nowDecimal <= $endHour):
                            $nowPct = (($nowDecimal - $startHour) / $totalHours) * 100;
                    ?>
                        <div class="absolute top-0 bottom-0 z-10 pointer-events-none" style="left: calc(var(--sidebar-w) + (100% - var(--sidebar-w)) * <?= $nowPct / 100 ?>);">
                            <div class="w-px h-full bg-red-400 opacity-50 dashed-line"></div>
                            <div class="absolute -top-1 -left-1 w-2 h-2 rounded-full bg-red-500 shadow-md"></div>
                        </div>
                    <?php endif; ?>

                    <!-- Rooms Rows -->
                    <div class="space-y-3">
                        <?php foreach($roomsForGantt as $room => $bookings): ?>
                        <div class="flex items-center group">
                            <div class="font-bold text-[#6A5243] text-sm group-hover:text-[#D4B59D] flex items-center gap-3 pr-6 overflow-hidden" title="<?= $room ?>" style="width: var(--sidebar-w); flex-shrink: 0;">
                                <div class="w-8 h-8 rounded-lg bg-[#F3EFE8] flex items-center justify-center text-[#A79A8B] group-hover:bg-[#D4B59D]/20 group-hover:text-[#D4B59D] transition-all flex-shrink-0">
                                    <i class="fas fa-door-open text-[0.8rem]"></i>
                                </div>
                                <span class="flex-1 sidebar-text-target truncate"><?= $room ?></span>
                            </div>
                            <div class="flex-1 h-12 bg-[#F9F8F6] rounded-xl relative border border-[#EBE6DA]/30 group-hover:bg-white group-hover:border-[#D4B59D]/40 transition-all shadow-inner-light">
                                <!-- Grid Lines -->
                                <div class="absolute inset-0 flex pointer-events-none">
                                    <?php for($h = $startHour; $h < $endHour; $h++): ?>
                                        <div class="flex-1 border-l border-[#EBE6DA]/20 h-full"></div>
                                    <?php endfor; ?>
                                    <div class="border-l border-[#EBE6DA]/20 h-full"></div>
                                </div>
                                
                                <?php foreach($bookings as $b): 
                                    $s = max($startHour, $b['start']);
                                    $e = min($endHour, $b['end']);
                                    if ($e <= $s) continue;
                                    
                                    $leftPct = (($s - $startHour) / $totalHours) * 100;
                                    $widthPct = (($e - $s) / $totalHours) * 100;
                                    
                                    $statusColors = [
                                        'approved' => 'bg-[#10b981] border-[#059669]',
                                        'pending' => 'bg-[#f59e0b] border-[#d97706]',
                                        'completed' => 'bg-[#6A5243] border-[#523E32]',
                                    ];
                                    $colorClass = $statusColors[$b['status']] ?? 'bg-[#D4B59D] border-[#C2A38A]';
                                ?>
                                <div class="gantt-bar absolute top-2 bottom-2 rounded-lg border flex items-center px-3 shadow-sm hover:shadow-md hover:z-20 hover:-translate-y-0.5 transition-all cursor-pointer <?= $colorClass ?>" 
                                     style="left: <?= $leftPct ?>%; width: <?= $widthPct ?>%;">
                                    <span class="text-white text-[0.65rem] font-bold truncate">
                                        <?= htmlspecialchars($b['title']) ?>
                                    </span>
                                    
                                    <!-- Tooltip Card (Premium) -->
                                    <div class="gantt-tooltip absolute bottom-full left-1/2 -translate-x-1/2 mb-3 hidden w-64 bg-white/95 backdrop-blur-md text-[#6A5243] p-5 rounded-[1.5rem] shadow-2xl border border-[#D4B59D]/30 z-30 pointer-events-none scale-95 opacity-0 transition-all duration-300">
                                        <div class="font-black mb-3 text-sm leading-tight"><?= htmlspecialchars($b['title']) ?></div>
                                        <div class="space-y-2">
                                            <div class="text-[#A79A8B] font-bold text-[0.7rem] flex items-center gap-2">
                                                <i class="far fa-clock text-[#D4B59D]"></i> <?= $b['startStr'] ?> - <?= $b['endStr'] ?>
                                            </div>
                                            <div class="flex items-center justify-between mt-3 pt-3 border-t border-[#EBE6DA]/50">
                                                <div class="inline-block px-3 py-1 bg-[#F9F8F6] rounded-full border border-[#EBE6DA] text-[0.6rem] uppercase font-black tracking-widest text-[#A79A8B]">
                                                    <?= $b['status'] ?>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- triangle -->
                                        <div class="absolute top-full left-1/2 -translate-x-1/2 border-[10px] border-transparent border-t-white/95"></div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        
                        <?php if(empty($roomsForGantt)): ?>
                            <div class="text-center py-12 bg-[#F9F8F6] rounded-3xl border-2 border-dashed border-[#EBE6DA] text-[#A79A8B] font-bold">
                                <i class="fas fa-inbox text-3xl mb-3 block opacity-20"></i>
                                ไม่พบข้อมูลการใช้งานในวันนี้
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart 1: Room Usage Count (Bar) -->
        <div class="bg-white rounded-[1.5rem] p-10 shadow-sm border border-[#EBE6DA]/60 transition-all hover:shadow-md overflow-hidden">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-10 h-10 rounded-xl bg-[#D4B59D]/10 flex items-center justify-center text-[#D4B59D]">
                    <i class="fas fa-chart-column"></i>
                </div>
                <h3 class="text-lg font-black text-[#6A5243]">ความถี่การใช้งานรายห้อง</h3>
            </div>
            <div class="relative h-[320px]">
                <canvas id="roomCountChart"></canvas>
            </div>
        </div>

        <!-- Chart 2: Room Usage Hours (Doughnut) -->
        <div class="bg-white rounded-[1.5rem] p-10 shadow-sm border border-[#EBE6DA]/60 transition-all hover:shadow-md overflow-hidden">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-10 h-10 rounded-xl bg-[#D4B59D]/10 flex items-center justify-center text-[#D4B59D]">
                    <i class="fas fa-chart-pie"></i>
                </div>
                <h3 class="text-lg font-black text-[#6A5243]">สัดส่วนเวลาการใช้งานรายห้อง</h3>
            </div>
            <div class="relative h-[320px]">
                <canvas id="roomHoursChart"></canvas>
            </div>
        </div>

        <!-- Chart 3: Department Usage (Bar) -->
        <div class="bg-white rounded-[1.5rem] p-10 shadow-sm border border-[#EBE6DA]/60 transition-all hover:shadow-md overflow-hidden">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-10 h-10 rounded-xl bg-[#D4B59D]/10 flex items-center justify-center text-[#D4B59D]">
                    <i class="fas fa-building-user"></i>
                </div>
                <h3 class="text-lg font-black text-[#6A5243]">สถิติการใช้งานแยกตามหน่วยงาน</h3>
            </div>
            <div class="relative h-[320px]">
                <canvas id="deptChart"></canvas>
            </div>
        </div>

        <!-- Chart 4: Equipment Usage (Bar) -->
        <div class="bg-white rounded-[1.5rem] p-10 shadow-sm border border-[#EBE6DA]/60 transition-all hover:shadow-md overflow-hidden">
            <div class="flex items-center gap-4 mb-8">
                <div class="w-10 h-10 rounded-xl bg-[#D4B59D]/10 flex items-center justify-center text-[#D4B59D]">
                    <i class="fas fa-box-archive"></i>
                </div>
                <h3 class="text-lg font-black text-[#6A5243]">สถิติการยืมอุปกรณ์</h3>
            </div>
            <div class="relative h-[320px]">
                <canvas id="equipmentChart"></canvas>
            </div>
        </div>
    </div>
</div>

<style>
/* CSS to handle tooltip hover correctly without clipping */
.gantt-bar:hover .gantt-tooltip { 
    display: block; 
    animation: slideUp 0.3s forwards;
}

@keyframes slideUp {
    from { transform: translate(-50%, 10px); opacity: 0; scale: 0.95; }
    to { transform: translate(-50%, 0); opacity: 1; scale: 1; }
}

.dashed-line {
    background-image: linear-gradient(to bottom, #f87171 50%, transparent 50%);
    background-size: 1px 10px;
    background-repeat: repeat-y;
}

.shadow-inner-light {
    box-shadow: inset 0 2px 4px 0 rgba(106, 82, 67, 0.02);
}

.custom-scrollbar::-webkit-scrollbar {
    height: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: #F9F8F6;
    border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #EBE6DA;
    border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #D4B59D;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Shared Colors (Earth Tone)
    const earthColors = [
        '#6A5243', '#D4B59D', '#A79A8B', '#8C7462', '#E6D6BD', '#523E32', '#C2A38A', '#9B8C7D'
    ];

    // 1. Room Count Chart (Bar)
    const ctxRoomCount = document.getElementById('roomCountChart').getContext('2d');
    new Chart(ctxRoomCount, {
        type: 'bar',
        data: {
            labels: <?= json_encode($roomLabels) ?>,
            datasets: [{
                label: 'จำนวนครั้งที่ใช้งาน',
                data: <?= json_encode($roomCounts) ?>,
                backgroundColor: '#D4B59D',
                hoverBackgroundColor: '#6A5243',
                borderRadius: 12,
                barThickness: 32
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(106, 82, 67, 0.9)',
                    titleFont: { size: 13, weight: 'bold', family: 'Outfit' },
                    bodyFont: { size: 12, family: 'Outfit' },
                    padding: 12,
                    cornerRadius: 12,
                    displayColors: false
                }
            },
            scales: {
                y: { 
                    beginAtZero: true, 
                    grid: { color: '#F3F0E6' },
                    ticks: { font: { family: 'Outfit', weight: 'bold' }, color: '#A79A8B' }
                },
                x: { 
                    grid: { display: false },
                    ticks: { 
                        font: { family: 'Outfit', size: 10, weight: 'bold' }, 
                        color: '#A79A8B',
                        maxRotation: 45,
                        minRotation: 45
                    } 
                }
            }
        }
    });

    // 2. Room Hours Chart (Doughnut)
    const ctxRoomHours = document.getElementById('roomHoursChart').getContext('2d');
    new Chart(ctxRoomHours, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode($roomLabels) ?>,
            datasets: [{
                data: <?= json_encode($roomHours) ?>,
                backgroundColor: earthColors.slice(0, <?= count($roomLabels) ?>),
                borderWidth: 0,
                hoverOffset: 15
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { 
                    position: 'right', 
                    labels: { 
                        usePointStyle: true,
                        pointStyle: 'circle',
                        padding: 20,
                        font: { family: 'Outfit, Sarabun', size: 11, weight: 'bold' },
                        color: '#6A5243'
                    } 
                },
                tooltip: {
                    backgroundColor: 'rgba(106, 82, 67, 0.9)',
                    padding: 12,
                    cornerRadius: 12,
                    bodyFont: { family: 'Outfit' }
                }
            },
            cutout: '72%'
        }
    });

    function closeUsageStats() {
    document.getElementById('usageStatsModal').style.display = 'none';
}

function toggleGanttSidebar() {
    // Legacy function removed
}

// Draggable Sidebar Implementation
(function() {
    let isResizing = false;
    const resizer = document.getElementById('sidebarResizer');
    const timeline = document.getElementById('ganttTimeline');

    if (!resizer) return;

    resizer.addEventListener('mousedown', function(e) {
        isResizing = true;
        document.body.style.cursor = 'col-resize';
        document.body.classList.add('select-none');
    });

    document.addEventListener('mousemove', function(e) {
        if (!isResizing) return;
        
        const rect = timeline.getBoundingClientRect();
        let newWidth = e.clientX - rect.left;
        
        // Constraints
        if (newWidth < 150) newWidth = 150;
        if (newWidth > 600) newWidth = 600;
        
        timeline.style.setProperty('--sidebar-w', newWidth + 'px');
        
        // Dynamic truncation handling
        const textElements = document.querySelectorAll('.sidebar-text-target');
        textElements.forEach(span => {
            if (newWidth > 400) {
                span.classList.remove('truncate');
                span.style.whiteSpace = 'normal';
            } else {
                span.classList.add('truncate');
                span.style.whiteSpace = 'nowrap';
            }
        });
    });

    document.addEventListener('mouseup', function() {
        if (isResizing) {
            isResizing = false;
            document.body.style.cursor = 'default';
            document.body.classList.remove('select-none');
        }
    });
})();

    // 3. Department Chart (Horizontal Bar)
    const ctxDept = document.getElementById('deptChart').getContext('2d');
    new Chart(ctxDept, {
        type: 'bar',
        data: {
            labels: <?= json_encode($deptLabels) ?>,
            datasets: [{
                label: 'จำนวนครั้งที่ใช้งาน',
                data: <?= json_encode($deptCounts) ?>,
                backgroundColor: '#A79A8B',
                hoverBackgroundColor: '#6A5243',
                borderRadius: 8,
                barThickness: 20
            }]
        },
        options: {
            indexAxis: 'y', // Makes it horizontal
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(106, 82, 67, 0.9)',
                    padding: 12,
                    cornerRadius: 12
                }
            },
            scales: {
                x: { 
                    beginAtZero: true, 
                    grid: { color: '#F3F0E6' },
                    ticks: { font: { family: 'Outfit', weight: 'bold' }, color: '#A79A8B' }
                },
                y: { 
                    grid: { display: false },
                    ticks: { font: { family: 'Outfit, Sarabun', size: 11, weight: 'bold' }, color: '#6A5243' }
                }
            }
        }
    });

    // 4. Equipment Chart (Bar)
    const ctxEq = document.getElementById('equipmentChart').getContext('2d');
    new Chart(ctxEq, {
        type: 'bar',
        data: {
            labels: <?= json_encode($eqLabels) ?>,
            datasets: [{
                label: 'จำนวนครั้งที่ถูกยืม',
                data: <?= json_encode($eqCounts) ?>,
                backgroundColor: ['#8C7462', '#C2A38A', '#A79A8B', '#6A5243'],
                borderRadius: 12,
                barThickness: 40
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(106, 82, 67, 0.9)',
                    padding: 12,
                    cornerRadius: 12
                }
            },
            scales: {
                y: { 
                    beginAtZero: true, 
                    grid: { color: '#F3F0E6' },
                    ticks: { font: { family: 'Outfit', weight: 'bold' }, color: '#A79A8B' }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { family: 'Outfit, Sarabun', weight: 'bold' }, color: '#6A5243' }
                }
            }
        }
    });
});
</script>
