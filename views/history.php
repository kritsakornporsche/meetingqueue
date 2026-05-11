<?php
require_once 'api/config.php';
use App\Repository\BookingRepository;

if (!isset($_SESSION['user_id'])) {
    exit('Unauthorized');
}

$repo = new BookingRepository();
$user = $_SESSION['user_data'];

$filters = [];
// Users see only their own history, admins see all
if ($user['role'] !== 'admin') {
    $filters['user_id'] = $_SESSION['user_id'];
}

$bookings = $repo->getAll($filters);

// Filter past or completed meetings
$past_bookings = array_filter($bookings ?: [], function($b) {
    $end_time = $b['end_time'] ?? null;
    return in_array($b['status'] ?? '', ['approved', 'completed']) && $end_time && strtotime($end_time) < time();
});

// Fetch user reviews
$db = \App\Core\Database::getInstance()->getConnection();
$reviews_stmt = $db->prepare("SELECT booking_id, rating, comment FROM meeting_reviews WHERE user_id = ?");
$reviews_stmt->execute([$_SESSION['user_id']]);
$user_reviews = [];
foreach ($reviews_stmt->fetchAll(PDO::FETCH_ASSOC) as $r) {
    $user_reviews[$r['booking_id']] = $r;
}

// Thai months mapping
$thai_months = [
    '01' => 'ม.ค.', '02' => 'ก.พ.', '03' => 'มี.ค.', '04' => 'เม.ย.',
    '05' => 'พ.ค.', '06' => 'มิ.ย.', '07' => 'ก.ค.', '08' => 'ส.ค.',
    '09' => 'ก.ย.', '10' => 'ต.ค.', '11' => 'พ.ย.', '12' => 'ธ.ค.'
];

function formatThaiDate($datetime, $months) {
    $time = strtotime($datetime);
    $d = date('j', $time);
    $m = $months[date('m', $time)];
    $y = (date('Y', $time) + 543) % 100;
    $t = date('H:i', $time);
    return "$d $m $y เวลา $t น.";
}
?>

<div class="flex flex-col gap-6 w-full animate-fade">
    <div class="flex flex-wrap justify-between items-center gap-4">
        <h2 class="text-2xl font-bold text-[#6A5243] flex items-center gap-2">
            <i class="fas fa-history text-[#D4B59D]"></i> ประวัติการประชุม & แบบประเมิน
        </h2>
        <div class="relative w-full sm:w-64">
            <input type="text" id="searchHistory" onkeyup="filterHistory()" placeholder="ค้นหาการประชุม..." 
                   class="w-full pl-10 pr-4 py-2 rounded-xl border border-[#D4B59D]/30 focus:outline-none focus:border-[#D4B59D] bg-white text-[#6A5243] shadow-sm">
            <i class="fas fa-search absolute left-3.5 top-3 text-[#A79A8B]"></i>
        </div>
    </div>

    <?php if (count($past_bookings) > 0): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach($past_bookings as $booking): 
                $room_id = $booking['room_id'] ?? 1;
                // Alternate between the two generated images
                $image_file = ($room_id % 2 == 0) ? 'room2.png' : 'room1.png';
                $image_url = 'assets/images/' . $image_file;
            ?>
            <div class="history-card bg-white rounded-2xl overflow-hidden shadow-sm border border-[#EBE6DA] hover:shadow-md transition-shadow flex flex-col group"
                 data-search="<?= htmlspecialchars(strtolower($booking['title'] . ' ' . ($booking['room_name'] ?? 'ภายนอกสถานที่') . ' ' . formatThaiDate($booking['start_time'], $thai_months))) ?>">
                <div class="relative h-48 overflow-hidden bg-[#EBE6DA]">
                    <img src="<?= $image_url ?>" alt="บรรยากาศห้องประชุม" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-bold text-[#6A5243] shadow-sm">
                        <i class="fas fa-check-circle text-green-500 mr-1"></i> เสร็จสิ้น
                    </div>
                </div>
                
                <div class="p-5 flex flex-col flex-grow">
                    <div class="mb-2 text-xs font-semibold text-[#A79A8B] flex items-center gap-1.5">
                        <i class="far fa-calendar-alt"></i>
                        <?= formatThaiDate($booking['start_time'], $thai_months) ?>
                    </div>
                    
                    <h3 class="text-lg font-bold text-[#6A5243] mb-1 line-clamp-2" title="<?= htmlspecialchars($booking['title']) ?>">
                        <?= htmlspecialchars($booking['title']) ?>
                    </h3>
                    
                    <div class="flex items-start gap-2 mb-4 text-sm text-[#A79A8B]">
                        <i class="fas fa-map-marker-alt mt-1 text-[#D4B59D]"></i>
                        <span><?= $booking['room_name'] ?? 'ภายนอกสถานที่' ?></span>
                    </div>

                    <div class="mt-auto pt-4 border-t border-[#EBE6DA] flex justify-between items-center">
                        <div class="flex items-center gap-2 text-sm text-[#6A5243]">
                            <i class="fas fa-users text-[#D4B59D]"></i> <?= $booking['participants_count'] ?> คน
                        </div>
                        <?php 
                        $existingReview = $user_reviews[$booking['id']] ?? null;
                        $reviewJson = json_encode($existingReview);
                        ?>
                        <button class="px-3 py-1.5 rounded-lg <?= $existingReview ? 'bg-[#D4B59D] text-white' : 'bg-[#F3F0E6] text-[#6A5243]' ?> text-sm font-medium hover:bg-[#D4B59D] hover:text-white transition-colors" onclick='reviewMeeting(<?= $booking['id'] ?>, <?= htmlspecialchars($reviewJson, ENT_QUOTES, "UTF-8") ?>)'>
                            <i class="fas fa-star mr-1"></i> <?= $existingReview ? 'ดู/แก้ไขการประเมิน' : 'ทำแบบประเมิน' ?>
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-2xl p-10 text-center border border-[#EBE6DA] shadow-sm">
            <div class="w-20 h-20 bg-[#F3F0E6] rounded-full flex items-center justify-center mx-auto mb-4 text-[#D4B59D]">
                <i class="fas fa-box-open text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold text-[#6A5243] mb-2">ยังไม่มีประวัติการประชุม</h3>
            <p class="text-[#A79A8B]">การประชุมที่เสร็จสิ้นแล้วจะแสดงที่นี่พร้อมรูปบรรยากาศห้อง</p>
        </div>
    <?php endif; ?>
</div>

<script>
function reviewMeeting(id, existingReview) {
    const defaultRating = existingReview ? existingReview.rating : 0;
    const defaultComment = existingReview && existingReview.comment ? MeetQueue.utils.escapeHtml(existingReview.comment) : '';

    Swal.fire({
        title: 'แบบประเมินหลังการประชุม',
        html: `
            <div class="text-left mb-4">
                <label class="block text-sm font-medium text-[#6A5243] mb-1">ระดับความพึงพอใจ</label>
                <div class="flex gap-2 text-2xl text-gray-300 cursor-pointer justify-center my-3" id="star-rating">
                    <i class="fas fa-star hover:text-yellow-400" onclick="setRating(1)"></i>
                    <i class="fas fa-star hover:text-yellow-400" onclick="setRating(2)"></i>
                    <i class="fas fa-star hover:text-yellow-400" onclick="setRating(3)"></i>
                    <i class="fas fa-star hover:text-yellow-400" onclick="setRating(4)"></i>
                    <i class="fas fa-star hover:text-yellow-400" onclick="setRating(5)"></i>
                </div>
                <input type="hidden" id="rating-value" value="${defaultRating}">
                
                <label class="block text-sm font-medium text-[#6A5243] mb-1 mt-4">ข้อเสนอแนะเพิ่มเติม</label>
                <textarea id="review-comment" class="w-full border border-[#D4B59D]/30 rounded-xl p-3 focus:outline-none focus:border-[#D4B59D] bg-white text-[#6A5243]" rows="3" placeholder="ข้อเสนอแนะเกี่ยวกับการใช้บริการห้องประชุม...">${defaultComment}</textarea>
            </div>
        `,
        didOpen: () => {
            if (defaultRating > 0) setRating(defaultRating);
        },
        showCancelButton: true,
        confirmButtonText: 'บันทึกการประเมิน',
        cancelButtonText: 'ยกเลิก',
        confirmButtonColor: '#6A5243',
        cancelButtonColor: '#A79A8B',
        preConfirm: () => {
            const rating = document.getElementById('rating-value').value;
            const comment = document.getElementById('review-comment').value;
            if (rating == 0) {
                Swal.showValidationMessage('กรุณาให้คะแนนความพึงพอใจ');
                return false;
            }
            return { booking_id: id, rating, comment };
        }
    }).then(async (result) => {
        if (result.isConfirmed) {
            MeetQueue.utils.loading(true, 'กำลังบันทึก...');
            try {
                const res = await fetch('api/reviews.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify(result.value)
                });
                const data = await res.json();
                MeetQueue.utils.loading(false);
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'บันทึกสำเร็จ',
                        text: 'ขอบคุณสำหรับแบบประเมิน',
                        confirmButtonColor: '#6A5243'
                    }).then(() => window.location.reload());
                } else {
                    throw new Error(data.message || 'เกิดข้อผิดพลาด');
                }
            } catch (err) {
                MeetQueue.utils.loading(false);
                Swal.fire({ icon: 'error', title: 'ผิดพลาด', text: err.message, confirmButtonColor: '#6A5243' });
            }
        }
    });
}

// Global function for setting rating
window.setRating = function(rating) {
    document.getElementById('rating-value').value = rating;
    const stars = document.getElementById('star-rating').children;
    for (let i = 0; i < stars.length; i++) {
        if (i < rating) {
            stars[i].classList.remove('text-gray-300');
            stars[i].classList.add('text-yellow-400');
        } else {
            stars[i].classList.add('text-gray-300');
            stars[i].classList.remove('text-yellow-400');
        }
    }
};

function filterHistory() {
    let input = document.getElementById('searchHistory').value.toLowerCase();
    let cards = document.querySelectorAll('.history-card');
    
    cards.forEach(card => {
        let searchText = card.getAttribute('data-search');
        if (searchText.includes(input)) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });
}
</script>
