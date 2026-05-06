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
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#EBE6DA] flex items-center gap-4">
            <div class="w-14 h-14 rounded-xl bg-[#D4B59D]/20 flex items-center justify-center text-[#6A5243] text-2xl">
                <i class="fas fa-handshake"></i>
            </div>
            <div>
                <div class="text-sm font-bold text-[#A79A8B]">จำนวนการจองทั้งหมด</div>
                <div class="text-3xl font-black text-[#6A5243]"><?= array_sum($roomCounts) ?> <span class="text-base font-normal">ครั้ง</span></div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#EBE6DA] flex items-center gap-4">
            <div class="w-14 h-14 rounded-xl bg-[#D4B59D]/20 flex items-center justify-center text-[#6A5243] text-2xl">
                <i class="fas fa-clock"></i>
            </div>
            <div>
                <div class="text-sm font-bold text-[#A79A8B]">เวลาใช้งานรวม</div>
                <div class="text-3xl font-black text-[#6A5243]"><?= array_sum($roomHours) ?> <span class="text-base font-normal">ชั่วโมง</span></div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#EBE6DA] flex items-center gap-4">
            <div class="w-14 h-14 rounded-xl bg-[#D4B59D]/20 flex items-center justify-center text-[#6A5243] text-2xl">
                <i class="fas fa-sitemap"></i>
            </div>
            <div>
                <div class="text-sm font-bold text-[#A79A8B]">หน่วยงานที่ใช้งาน</div>
                <div class="text-3xl font-black text-[#6A5243]"><?= count($deptCounts) ?> <span class="text-base font-normal">แผนก</span></div>
            </div>
        </div>
    </div>

    <!-- Charts Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Chart 0: Daily Gantt Chart -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#EBE6DA] lg:col-span-2 overflow-hidden">
            <h3 class="text-lg font-bold text-[#6A5243] mb-6 flex justify-between items-center">
                <span class="flex items-center gap-2"><i class="fas fa-stream text-[#D4B59D]"></i> ตารางการใช้ห้องประชุมวันนี้ (<?= date('d/m/Y') ?>)</span>
                <div class="flex items-center gap-3 text-[0.65rem] font-bold uppercase tracking-widest text-[#A79A8B]">
                    <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-[#10b981]"></span> อนุมัติ</span>
                    <span class="flex items-center gap-1"><span class="w-3 h-3 rounded-full bg-[#f59e0b]"></span> รออนุมัติ</span>
                </div>
            </h3>
            <div class="overflow-x-auto pb-4">
                <div class="min-w-[800px]">
                    <!-- Timeline Header -->
                    <div class="flex border-b-2 border-[#EBE6DA] pb-2 mb-4 ml-[150px]">
                        <?php for($h = $startHour; $h <= $endHour; $h++): ?>
                            <div class="flex-1 text-xs font-black text-[#A79A8B] relative">
                                <span class="absolute -translate-x-1/2"><?= sprintf('%02d:00', $h) ?></span>
                            </div>
                        <?php endfor; ?>
                    </div>
                    
                    <!-- Rooms Rows -->
                    <?php foreach($roomsForGantt as $room => $bookings): ?>
                    <div class="flex items-center mb-4 group">
                        <div class="w-[150px] pr-4 font-bold text-[#6A5243] text-sm truncate group-hover:text-[#D4B59D] transition-colors" title="<?= $room ?>">
                            <?= $room ?>
                        </div>
                        <div class="flex-1 h-12 bg-[#F9F8F6] rounded-xl relative border border-[#EBE6DA]/50 group-hover:border-[#D4B59D]/30 transition-colors">
                            <!-- Grid Lines -->
                            <div class="absolute inset-0 flex pointer-events-none">
                                <?php for($h = $startHour; $h < $endHour; $h++): ?>
                                    <div class="flex-1 border-l border-[#EBE6DA]/30 h-full"></div>
                                <?php endfor; ?>
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
                            <div class="absolute top-1.5 bottom-1.5 rounded-lg border flex items-center px-3 shadow-md hover:shadow-lg hover:z-20 hover:-translate-y-0.5 transition-all cursor-pointer <?= $colorClass ?>" 
                                 style="left: <?= $leftPct ?>%; width: <?= $widthPct ?>%;">
                                <span class="text-white text-xs font-bold truncate">
                                    <?= htmlspecialchars($b['title']) ?>
                                </span>
                                
                                <!-- Tooltip -->
                                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden hover-target w-56 bg-white text-[#6A5243] text-xs p-4 rounded-2xl shadow-xl border border-[#EBE6DA] z-30 pointer-events-none">
                                    <div class="font-black mb-2 truncate text-sm"><?= htmlspecialchars($b['title']) ?></div>
                                    <div class="text-[#A79A8B] font-semibold flex items-center gap-2 mb-2">
                                        <i class="far fa-clock"></i> <?= $b['startStr'] ?> - <?= $b['endStr'] ?>
                                    </div>
                                    <div class="inline-block px-2 py-1 bg-[#F9F8F6] rounded border border-[#EBE6DA] text-[0.65rem] uppercase font-bold tracking-wider">
                                        สถานะ: <?= $b['status'] ?>
                                    </div>
                                    <!-- triangle -->
                                    <div class="absolute top-full left-1/2 -translate-x-1/2 border-8 border-transparent border-t-white drop-shadow-md"></div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    
                    <?php if(empty($roomsForGantt)): ?>
                        <div class="text-center py-8 text-[#A79A8B] font-bold">ไม่พบข้อมูลห้องประชุม</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Chart 1: Room Usage Count (Bar) -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#EBE6DA]">
            <h3 class="text-lg font-bold text-[#6A5243] mb-4">ความถี่การใช้งานรายห้อง (ครั้ง)</h3>
            <div class="relative h-[300px]">
                <canvas id="roomCountChart"></canvas>
            </div>
        </div>

        <!-- Chart 2: Room Usage Hours (Doughnut) -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#EBE6DA]">
            <h3 class="text-lg font-bold text-[#6A5243] mb-4">สัดส่วนเวลาการใช้งานรายห้อง (ชั่วโมง)</h3>
            <div class="relative h-[300px]">
                <canvas id="roomHoursChart"></canvas>
            </div>
        </div>

        <!-- Chart 3: Department Usage (Bar) -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#EBE6DA]">
            <h3 class="text-lg font-bold text-[#6A5243] mb-4">สถิติการใช้งานแยกตามหน่วยงาน (ครั้ง)</h3>
            <div class="relative h-[300px]">
                <canvas id="deptChart"></canvas>
            </div>
        </div>

        <!-- Chart 4: Equipment Usage (Polar Area) -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-[#EBE6DA]">
            <h3 class="text-lg font-bold text-[#6A5243] mb-4">สถิติการยืมอุปกรณ์ (ครั้ง)</h3>
            <div class="relative h-[300px]">
                <canvas id="equipmentChart"></canvas>
            </div>
        </div>
    </div>
</div>

<style>
/* CSS to handle tooltip hover correctly without clipping */
.hover-target { display: none; }
.absolute.top-1\\.5:hover .hover-target { display: block; }
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
                borderColor: '#6A5243',
                borderWidth: 1,
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } },
                x: { ticks: { display: false } } // Hide long room names on X axis for cleaner look
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
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'right', labels: { boxWidth: 12, font: { family: 'Outfit, Sarabun' } } }
            },
            cutout: '65%'
        }
    });

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
                borderColor: '#6A5243',
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            indexAxis: 'y', // Makes it horizontal
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { beginAtZero: true, ticks: { stepSize: 1 } },
                y: { ticks: { font: { family: 'Outfit, Sarabun' } } }
            }
        }
    });

    // 4. Equipment Chart (Polar Area or Bar)
    const ctxEq = document.getElementById('equipmentChart').getContext('2d');
    new Chart(ctxEq, {
        type: 'bar',
        data: {
            labels: <?= json_encode($eqLabels) ?>,
            datasets: [{
                label: 'จำนวนครั้งที่ถูกยืม',
                data: <?= json_encode($eqCounts) ?>,
                backgroundColor: ['#8C7462', '#C2A38A', '#A79A8B', '#6A5243'],
                borderWidth: 0,
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });
});
</script>
