<?php
require_once 'api/config.php';
use App\Repository\RoomRepository;
use App\Repository\BookingRepository;

if (!isset($_SESSION['user_id'])) {
    exit('Unauthorized');
}

$roomRepo = new RoomRepository();
$bookingRepo = new BookingRepository();

$rooms = $roomRepo->getAll();
$today_start = date('Y-m-d 00:00:00');
$today_end = date('Y-m-d 23:59:59');

$today_bookings = $bookingRepo->getAll([
    'start' => $today_start,
    'end' => $today_end,
    'status' => 'approved'
]);

// Group bookings by room
$room_schedules = [];
foreach ($rooms as $room) {
    $room_schedules[$room['id']] = [];
}

foreach ($today_bookings as $b) {
    if ($b['room_id']) {
        $room_schedules[$b['room_id']][] = $b;
    }
}

$current_time = time();
?>

<div class="flex flex-col gap-6 w-full pb-10">
    <div class="flex justify-between items-center mb-2">
        <h2 class="text-2xl font-bold text-[#6A5243] flex items-center gap-2">
            <i class="fas fa-desktop text-[#D4B59D]"></i> สถานะห้องประชุมปัจจุบัน
        </h2>
        <div class="text-[#6A5243] font-bold bg-white px-6 py-3 rounded-2xl shadow-sm border border-[#EBE6DA] text-lg">
            <span id="current-time-display"></span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        <?php foreach ($rooms as $room): 
            $schedules = $room_schedules[$room['id']] ?? [];
            usort($schedules, function($a, $b) {
                return strtotime($a['start_time']) - strtotime($b['start_time']);
            });

            $current_meeting = null;
            $next_meeting = null;

            foreach ($schedules as $s) {
                $start = strtotime($s['start_time']);
                $end = strtotime($s['end_time']);
                
                if ($current_time >= $start && $current_time <= $end) {
                    $current_meeting = $s;
                    break;
                } elseif ($current_time < $start && !$next_meeting) {
                    $next_meeting = $s;
                }
            }

            $is_active = $current_meeting !== null;
            $card_bg = $is_active ? 'bg-white border-red-200' : 'bg-white border-[#EBE6DA]';
        ?>
        <div class="rounded-3xl p-8 border-2 <?= $card_bg ?> shadow-sm flex flex-col items-center text-center transition-all hover:shadow-md">
            <!-- Room Name -->
            <h3 class="text-xl font-black text-[#6A5243] mb-4"><?= htmlspecialchars($room['name']) ?></h3>
            
            <div class="w-full py-6 px-4 rounded-2xl <?= $is_active ? 'bg-red-50' : 'bg-gray-50' ?> mb-4 flex flex-col items-center justify-center min-h-[140px]">
                <?php if ($is_active): ?>
                    <div class="text-xs font-bold text-red-500 mb-2 flex items-center gap-1 uppercase tracking-widest">
                        <span class="animate-pulse w-2 h-2 rounded-full bg-red-500 block"></span> กำลังใช้งาน
                    </div>
                    <h4 class="text-lg font-bold text-[#6A5243] mb-4 leading-tight"><?= htmlspecialchars($current_meeting['title']) ?></h4>
                    
                    <!-- Timer -->
                    <div class="countdown-timer text-2xl font-black text-red-600 font-mono" data-target="<?= $current_meeting['end_time'] ?>" data-type="end">
                        00:00:00
                    </div>
                <?php elseif ($next_meeting): ?>
                    <div class="text-xs font-bold text-blue-500 mb-2 flex items-center gap-1 uppercase tracking-widest">
                        เตรียมประชุมถัดไป
                    </div>
                    <h4 class="text-lg font-bold text-[#6A5243] mb-4 leading-tight opacity-70"><?= htmlspecialchars($next_meeting['title']) ?></h4>
                    
                    <!-- Timer to Start -->
                    <div class="countdown-timer text-2xl font-black text-blue-600 font-mono" data-target="<?= $next_meeting['start_time'] ?>" data-type="start">
                        00:00:00
                    </div>
                <?php else: ?>
                    <div class="flex flex-col items-center opacity-30">
                        <i class="fas fa-calendar-check text-4xl mb-2"></i>
                        <p class="font-bold">ไม่มีการประชุม</p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="text-sm font-bold <?= $is_active ? 'text-red-500' : 'text-green-500' ?> uppercase tracking-widest">
                <?= $is_active ? 'Occupied' : 'Available' ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
// Sync server time with client time
const serverTimeAtLoad = <?= time() * 1000 ?>;
const clientTimeAtLoad = new Date().getTime();
const serverOffset = serverTimeAtLoad - clientTimeAtLoad;

function updateCountdowns() {
    const timers = document.querySelectorAll('.countdown-timer');
    const now = new Date().getTime() + serverOffset;
    
    const clockDisplay = document.getElementById('current-time-display');
    if (clockDisplay) {
        clockDisplay.innerText = new Date(now).toLocaleTimeString('th-TH', { 
            hour: '2-digit', minute: '2-digit', second: '2-digit' 
        });
    }

    timers.forEach(timer => {
        // Replace space with T for ISO format to ensure cross-browser compatibility
        const targetStr = timer.dataset.target.replace(' ', 'T');
        const targetDate = new Date(targetStr).getTime();
        const distance = targetDate - now;
        
        if (distance < 0) {
            timer.innerHTML = "00:00:00";
            if (timer.dataset.reloaded !== "true") {
                timer.dataset.reloaded = "true";
                setTimeout(() => location.reload(), 3000);
            }
            return;
        }
        
        const hours = Math.floor(distance / (1000 * 60 * 60));
        const mins = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
        const hStr = hours.toString().padStart(2, '0');
        const mStr = mins.toString().padStart(2, '0');
        const sStr = seconds.toString().padStart(2, '0');
        
        timer.innerHTML = `${hStr}:${mStr}:${sStr}`;
    });
}

setInterval(updateCountdowns, 1000);
updateCountdowns();
</script>
