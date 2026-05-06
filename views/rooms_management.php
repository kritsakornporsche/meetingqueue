<?php
require_once 'api/config.php';
use App\Repository\RoomRepository;

if (!isset($_SESSION['user_id']) || $_SESSION['user_data']['role'] !== 'admin') {
    exit('Unauthorized');
}

$repo = new RoomRepository();
$rooms = $repo->getAll();
?>

<div class="flex flex-col gap-6 w-full animate-fade">
    <div class="flex flex-wrap justify-between items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-[#6A5243] flex items-center gap-2">
                <i class="fas fa-door-open text-[#D4B59D]"></i> ระบบจัดการห้องประชุม
            </h2>
            <p class="text-[#A79A8B] text-sm mt-1">จัดการข้อมูล เพิ่ม แก้ไข และลบห้องประชุมในระบบ</p>
        </div>
        <button onclick="openRoomModal()" class="px-5 py-2.5 rounded-xl bg-[#6A5243] text-white font-bold shadow-md hover:bg-[#523E32] transition-colors flex items-center gap-2">
            <i class="fas fa-plus"></i> เพิ่มห้องประชุมใหม่
        </button>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-[#EBE6DA] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F9F8F6] border-b border-[#EBE6DA]">
                        <th class="p-4 text-[#A79A8B] font-bold text-sm w-16 text-center">#</th>
                        <th class="p-4 text-[#A79A8B] font-bold text-sm">ชื่อห้องประชุม</th>
                        <th class="p-4 text-[#A79A8B] font-bold text-sm">สถานที่ตั้ง</th>
                        <th class="p-4 text-[#A79A8B] font-bold text-sm text-center">ความจุ (คน)</th>
                        <th class="p-4 text-[#A79A8B] font-bold text-sm text-center">สถานะ</th>
                        <th class="p-4 text-[#A79A8B] font-bold text-sm text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(count($rooms) > 0): ?>
                        <?php foreach($rooms as $index => $room): ?>
                        <tr class="border-b border-[#EBE6DA] hover:bg-[#FDFBF7] transition-colors group">
                            <td class="p-4 text-center text-[#A79A8B]"><?= $index + 1 ?></td>
                            <td class="p-4 font-bold text-[#6A5243]">
                                <?= htmlspecialchars($room['name']) ?>
                            </td>
                            <td class="p-4 text-[#6A5243]">
                                <?= htmlspecialchars($room['location'] ?: '-') ?>
                            </td>
                            <td class="p-4 text-center text-[#6A5243] font-bold">
                                <?= htmlspecialchars($room['capacity']) ?>
                            </td>
                            <td class="p-4 text-center">
                                <?php if($room['status'] === 'available'): ?>
                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">พร้อมใช้งาน</span>
                                <?php else: ?>
                                    <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold">ปิดปรับปรุง</span>
                                <?php endif; ?>
                            </td>
                            <td class="p-4 text-center">
                                <div class="flex justify-center gap-2 opacity-100 sm:opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button onclick="editRoom(<?= htmlspecialchars(json_encode($room), ENT_QUOTES, 'UTF-8') ?>)" class="w-8 h-8 rounded-lg bg-[#F3F0E6] text-[#D4B59D] hover:bg-[#D4B59D] hover:text-white flex items-center justify-center transition-colors" title="แก้ไข">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="deleteRoom(<?= $room['id'] ?>, '<?= htmlspecialchars($room['name'], ENT_QUOTES, 'UTF-8') ?>')" class="w-8 h-8 rounded-lg bg-red-50 text-red-400 hover:bg-red-500 hover:text-white flex items-center justify-center transition-colors" title="ลบ">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="p-8 text-center text-[#A79A8B]">ไม่พบข้อมูลห้องประชุม</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function openRoomModal(room = null) {
    const isEdit = room !== null;
    Swal.fire({
        title: isEdit ? 'แก้ไขห้องประชุม' : 'เพิ่มห้องประชุมใหม่',
        html: `
            <div class="text-left space-y-4 mt-2">
                <div>
                    <label class="block text-sm font-bold text-[#6A5243] mb-1">ชื่อห้องประชุม <span class="text-red-500">*</span></label>
                    <input type="text" id="room-name" class="w-full px-4 py-2.5 rounded-xl border border-[#D4B59D]/30 focus:outline-none focus:border-[#D4B59D] bg-[#F9F8F6] text-[#6A5243]" placeholder="เช่น ห้องประชุมเอื้องผึ้ง" value="${isEdit ? escapeHtml(room.name) : ''}">
                </div>
                <div>
                    <label class="block text-sm font-bold text-[#6A5243] mb-1">สถานที่ตั้ง</label>
                    <input type="text" id="room-location" class="w-full px-4 py-2.5 rounded-xl border border-[#D4B59D]/30 focus:outline-none focus:border-[#D4B59D] bg-[#F9F8F6] text-[#6A5243]" placeholder="เช่น อาคาร 1 ชั้น 2" value="${isEdit ? escapeHtml(room.location || '') : ''}">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-[#6A5243] mb-1">ความจุ (คน) <span class="text-red-500">*</span></label>
                        <input type="number" id="room-capacity" class="w-full px-4 py-2.5 rounded-xl border border-[#D4B59D]/30 focus:outline-none focus:border-[#D4B59D] bg-[#F9F8F6] text-[#6A5243]" value="${isEdit ? room.capacity : '10'}">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-[#6A5243] mb-1">สถานะ</label>
                        <select id="room-status" class="w-full px-4 py-2.5 rounded-xl border border-[#D4B59D]/30 focus:outline-none focus:border-[#D4B59D] bg-[#F9F8F6] text-[#6A5243]">
                            <option value="available" ${isEdit && room.status === 'available' ? 'selected' : ''}>พร้อมใช้งาน</option>
                            <option value="maintenance" ${isEdit && room.status === 'maintenance' ? 'selected' : ''}>ปิดปรับปรุง</option>
                        </select>
                    </div>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'บันทึก',
        cancelButtonText: 'ยกเลิก',
        confirmButtonColor: '#6A5243',
        cancelButtonColor: '#A79A8B',
        preConfirm: () => {
            const name = document.getElementById('room-name').value.trim();
            const location = document.getElementById('room-location').value.trim();
            const capacity = document.getElementById('room-capacity').value;
            const status = document.getElementById('room-status').value;

            if (!name || !capacity) {
                Swal.showValidationMessage('กรุณากรอกข้อมูลที่จำเป็นให้ครบถ้วน');
                return false;
            }

            return { name, location, capacity, status, id: isEdit ? room.id : null };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            saveRoom(result.value, isEdit ? 'PATCH' : 'POST');
        }
    });
}

function editRoom(room) {
    openRoomModal(room);
}

function deleteRoom(id, name) {
    Swal.fire({
        title: 'ยืนยันการลบ?',
        text: `คุณต้องการลบห้อง "${name}" ใช่หรือไม่?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#A79A8B',
        confirmButtonText: 'ใช่, ลบเลย',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            saveRoom({ id: id }, 'DELETE');
        }
    });
}

async function saveRoom(data, method) {
    MeetQueue.utils.loading(true);
    try {
        const response = await fetch('api/rooms.php', {
            method: method,
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        const result = await response.json();
        MeetQueue.utils.loading(false);
        
        if (result.success) {
            Swal.fire({
                icon: 'success',
                title: 'สำเร็จ',
                text: 'ดำเนินการเสร็จสิ้น',
                confirmButtonColor: '#6A5243'
            }).then(() => {
                window.location.reload();
            });
        } else {
            throw new Error(result.message || 'เกิดข้อผิดพลาด');
        }
    } catch (err) {
        MeetQueue.utils.loading(false);
        Swal.fire({
            icon: 'error',
            title: 'ข้อผิดพลาด',
            text: err.message,
            confirmButtonColor: '#6A5243'
        });
    }
}

function escapeHtml(unsafe) {
    if (!unsafe) return '';
    return unsafe
         .replace(/&/g, "&amp;")
         .replace(/</g, "&lt;")
         .replace(/>/g, "&gt;")
         .replace(/"/g, "&quot;")
         .replace(/'/g, "&#039;");
}
</script>
