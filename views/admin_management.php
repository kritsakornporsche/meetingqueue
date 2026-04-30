<?php
if (($_SESSION['user_data']['role'] ?? 'user') !== 'admin') {
    exit('Unauthorized access');
}

$page_title = 'จัดการข้อมูลการประชุม';
$page_subtitle = 'หน้าจอสำหรับผู้ดูแลระบบ เพื่อบริหารจัดการข้อมูลการจองห้องประชุมทั้งหมด';
?>

<div class="flex flex-col gap-6 w-full animate-fade">
    <!-- Header Section -->
    <div class="bg-white p-8 rounded-[3rem] border border-accent/30 shadow-sm relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-accent/5 rounded-full -mr-32 -mt-32"></div>
        <div class="flex items-center gap-6 relative z-10">
            <div class="w-16 h-16 rounded-[2rem] bg-primary flex items-center justify-center text-white shadow-xl shadow-primary/20">
                <i class="fas fa-tasks text-2xl"></i>
            </div>
            <div class="flex-grow">
                <h1 class="text-3xl font-black text-primary tracking-tight"><?= $page_title ?></h1>
                <p class="text-text-muted font-bold opacity-80"><?= $page_subtitle ?></p>
            </div>
            <div class="flex gap-3">
                <a href="dashboard.php?view=trash_management" class="px-4 py-2 bg-red-100 text-red-600 rounded-xl text-sm font-bold flex items-center gap-2 hover:bg-red-200 transition-all">
                    <i class="fas fa-trash-alt"></i> ดูถังขยะ
                </a>
                <button onclick="exportData('excel')" class="px-4 py-2 bg-green-500 text-white rounded-xl text-sm font-bold flex items-center gap-2 hover:bg-green-600 transition-all">
                    <i class="fas fa-file-excel"></i> Export Excel
                </button>
            </div>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="bg-white rounded-[3rem] shadow-sm border border-accent/30 overflow-hidden">
        <!-- Filter Bar -->
        <div class="p-6 border-b border-accent/10 bg-accent/5 flex flex-wrap gap-4 items-center">
            <div class="relative flex-grow max-w-md">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-text-muted"></i>
                <input type="text" id="adminSearch" placeholder="ค้นหาชื่อการประชุม, ผู้จอง, หรือห้องประชุม..." 
                       class="w-full pl-12 pr-4 py-3 rounded-2xl border border-accent/30 focus:outline-none focus:ring-2 focus:ring-primary/20 transition-all bg-white text-sm font-bold text-primary">
            </div>
            
            <select id="adminStatusFilter" class="px-4 py-3 rounded-2xl border border-accent/30 focus:outline-none focus:ring-2 focus:ring-primary/20 bg-white text-sm font-bold text-primary">
                <option value="">ทุกสถานะ</option>
                <option value="pending">รออนุมัติ</option>
                <option value="approved">อนุมัติแล้ว</option>
                <option value="rejected">ไม่อนุมัติ</option>
                <option value="completed">เสร็จสิ้น</option>
                <option value="cancelled">ยกเลิก</option>
            </select>

            <input type="date" id="adminDateFilter" class="px-4 py-3 rounded-2xl border border-accent/30 focus:outline-none focus:ring-2 focus:ring-primary/20 bg-white text-sm font-bold text-primary">
            
            <button onclick="resetFilters()" class="text-secondary font-bold text-sm hover:underline">ล้างค่า</button>
        </div>

        <!-- Table View -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-accent/5">
                        <th class="px-6 py-4 text-[0.65rem] font-black text-text-muted uppercase tracking-widest border-b border-accent/10">ID</th>
                        <th class="px-6 py-4 text-[0.65rem] font-black text-text-muted uppercase tracking-widest border-b border-accent/10">การประชุม</th>
                        <th class="px-6 py-4 text-[0.65rem] font-black text-text-muted uppercase tracking-widest border-b border-accent/10">ผู้จอง / ฝ่าย</th>
                        <th class="px-6 py-4 text-[0.65rem] font-black text-text-muted uppercase tracking-widest border-b border-accent/10">วัน-เวลา</th>
                        <th class="px-6 py-4 text-[0.65rem] font-black text-text-muted uppercase tracking-widest border-b border-accent/10 text-center">สถานะ</th>
                        <th class="px-6 py-4 text-[0.65rem] font-black text-text-muted uppercase tracking-widest border-b border-accent/10 text-center">จัดการ</th>
                    </tr>
                </thead>
                <tbody id="adminBookingTable" class="divide-y divide-accent/10">
                    <!-- Data will be loaded here -->
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-4 opacity-30">
                                <i class="fas fa-circle-notch fa-spin text-4xl"></i>
                                <p class="font-bold">กำลังโหลดข้อมูล...</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Footer / Stats -->
        <div class="p-6 bg-accent/5 border-t border-accent/10 flex justify-between items-center">
            <div id="tableStats" class="text-xs font-bold text-text-muted">
                แสดงทั้งหมด 0 รายการ
            </div>
            <div id="pagination" class="flex gap-2">
                <!-- Pagination Buttons -->
            </div>
        </div>
    </div>
</div>

<script>
    let allBookings = [];
    let filteredBookings = [];

    document.addEventListener('DOMContentLoaded', () => {
        loadData();
        document.getElementById('adminSearch').addEventListener('input', applyFilters);
        document.getElementById('adminStatusFilter').addEventListener('change', applyFilters);
        document.getElementById('adminDateFilter').addEventListener('change', applyFilters);
    });

    async function loadData() {
        const response = await fetch('api/bookings.php');
        const data = await response.json();
        if (data.success) {
            allBookings = data.bookings;
            applyFilters();
        }
    }

    function applyFilters() {
        const search = document.getElementById('adminSearch').value.toLowerCase();
        const status = document.getElementById('adminStatusFilter').value;
        const date = document.getElementById('adminDateFilter').value;

        filteredBookings = allBookings.filter(b => {
            const matchesSearch = b.title.toLowerCase().includes(search) || 
                                b.first_name.toLowerCase().includes(search) || 
                                (b.room_name && b.room_name.toLowerCase().includes(search));
            const matchesStatus = status === '' || b.status === status;
            const matchesDate = date === '' || b.start_time.startsWith(date);
            
            return matchesSearch && matchesStatus && matchesDate;
        });

        renderTable();
    }

    function renderTable() {
        const tbody = document.getElementById('adminBookingTable');
        const stats = document.getElementById('tableStats');
        
        if (filteredBookings.length === 0) {
            tbody.innerHTML = `<tr><td colspan="6" class="px-6 py-12 text-center font-bold text-text-muted">ไม่พบข้อมูลที่ต้องการ</td></tr>`;
            stats.innerText = `แสดงทั้งหมด 0 รายการ`;
            return;
        }

        tbody.innerHTML = filteredBookings.map(b => {
            const statusClass = getStatusStyles(b.status);
            return `
                <tr class="hover:bg-primary/5 transition-colors group">
                    <td class="px-6 py-4 text-xs font-bold text-text-muted">#${b.id}</td>
                    <td class="px-6 py-4">
                        <div class="font-black text-primary leading-tight mb-1">${b.title}</div>
                        <div class="flex items-center gap-2 text-[0.65rem] font-bold text-secondary uppercase">
                            <i class="fas fa-door-open"></i> ${b.room_name || 'ภายนอก'}
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg overflow-hidden bg-accent/30 shadow-inner flex-shrink-0">
                                <img src="https://192.168.9.7/auth_files/photo/${b.emp_code}.jpg" 
                                     onerror="this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(b.first_name)}&background=E6D6BD&color=6A5243'" 
                                     class="w-full h-full object-cover">
                            </div>
                            <div>
                                <div class="text-xs font-bold text-primary">${b.first_name} ${b.last_name}</div>
                                <div class="text-[0.65rem] font-bold text-text-muted">${b.department_name || '-'}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-xs font-bold text-primary">${formatDate(b.start_time)}</div>
                        <div class="text-[0.65rem] font-bold text-text-muted">${formatTime(b.start_time)} - ${formatTime(b.end_time)}</div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-3 py-1 rounded-full text-[0.6rem] font-black uppercase tracking-widest ${statusClass.bg} ${statusClass.text} border ${statusClass.border}">
                            ${translateStatus(b.status)}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex justify-center gap-2">
                            <button onclick="viewDetail(${b.id})" class="w-8 h-8 rounded-lg bg-accent/20 text-primary hover:bg-primary hover:text-white transition-all shadow-sm" title="ดูรายละเอียด"><i class="fas fa-eye text-xs"></i></button>
                            <button onclick="deleteBooking(${b.id})" class="w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-all shadow-sm" title="ลบการจอง"><i class="fas fa-trash text-xs"></i></button>
                        </div>
                    </td>
                </tr>
            `;
        }).join('');

        stats.innerText = `แสดงทั้งหมด ${filteredBookings.length} รายการ`;
    }

    function resetFilters() {
        document.getElementById('adminSearch').value = '';
        document.getElementById('adminStatusFilter').value = '';
        document.getElementById('adminDateFilter').value = '';
        applyFilters();
    }

    function getStatusStyles(status) {
        switch(status) {
            case 'approved': return { bg: 'bg-green-50', text: 'text-green-600', border: 'border-green-200' };
            case 'pending': return { bg: 'bg-orange-50', text: 'text-orange-600', border: 'border-orange-200' };
            case 'rejected': return { bg: 'bg-red-50', text: 'text-red-600', border: 'border-red-200' };
            case 'completed': return { bg: 'bg-gray-50', text: 'text-gray-600', border: 'border-gray-200' };
            case 'cancelled': return { bg: 'bg-gray-100', text: 'text-gray-400', border: 'border-gray-300' };
            default: return { bg: 'bg-gray-50', text: 'text-gray-600', border: 'border-gray-200' };
        }
    }

    function translateStatus(status) {
        const trans = {
            'pending': 'รออนุมัติ',
            'approved': 'อนุมัติแล้ว',
            'rejected': 'ไม่อนุมัติ',
            'completed': 'เสร็จสิ้น',
            'cancelled': 'ยกเลิก'
        };
        return trans[status] || status;
    }

    function formatDate(dateStr) {
        return new Date(dateStr).toLocaleDateString('th-TH', { day: 'numeric', month: 'short', year: '2-digit' });
    }

    function formatTime(dateStr) {
        return new Date(dateStr).toLocaleTimeString('th-TH', { hour: '2-digit', minute: '2-digit' });
    }

    function viewDetail(id) {
        window.location.href = 'dashboard.php?view=booking_result&id=' + id;
    }

    async function deleteBooking(id) {
        const result = await Swal.fire({
            title: 'ยืนยันการลบ?',
            text: "ข้อมูลการจองนี้จะถูกลบออกจากระบบอย่างถาวร",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'ยืนยันลบ',
            cancelButtonText: 'ยกเลิก'
        });

        if (result.isConfirmed) {
            const response = await fetch('api/bookings.php', {
                method: 'DELETE',
                body: JSON.stringify({ booking_id: id })
            });
            const data = await response.json();
            if (data.success) {
                Swal.fire('ลบข้อมูลแล้ว!', '', 'success');
                loadData();
            } else {
                Swal.fire('ผิดพลาด!', data.message, 'error');
            }
        }
    }

    function exportData(type) {
        // Implement export logic (or just notify)
        Swal.fire('กำลังประมวลผล', 'ระบบกำลังส่งออกข้อมูลเป็น ' + type.toUpperCase(), 'info');
    }
</script>
