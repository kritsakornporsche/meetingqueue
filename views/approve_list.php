<?php
$current_view = $_GET['view'] ?? '';
$is_admin_view = ($current_view === 'approve_list' || $current_view === 'requests') && (($_SESSION['user_data']['role'] ?? 'user') === 'admin');
$force_user_filter = ($current_view === 'results' || $current_view === 'status');

// Page Metadata
$page_title = 'สถานะการประชุม';
$page_subtitle = 'ติดตามสถานะการประชุมของคุณ';
$icon = 'fa-clipboard-check';

if ($is_admin_view) {
    if ($current_view === 'requests') {
        $page_title = 'รายการขอใช้ทั้งหมด';
        $page_subtitle = 'ดูและตรวจสอบประวัติการขอใช้ห้องประชุมทั้งหมดในระบบ';
        $icon = 'fa-list-ul';
        $breadcrumb = 'รายการขอใช้';
    } else {
        $page_title = 'รายการรออนุมัติ';
        $page_subtitle = 'จัดการคำขอจองที่อยู่ระหว่างการรออนุมัติ';
        $icon = 'fa-user-check';
        $breadcrumb = 'รายการอนุมัติ';
    }
} else {
    $breadcrumb = 'สถานะการประชุม';
}
?>

<div class="flex flex-col gap-6 w-full animate-fade">
    <!-- Header Section -->
    <div class="bg-white p-8 rounded-[3rem] border border-accent/30 shadow-sm relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-accent/5 rounded-full -mr-32 -mt-32"></div>
        <div class="flex items-center gap-6 relative z-10">
            <div class="w-16 h-16 rounded-[2rem] bg-primary flex items-center justify-center text-white shadow-xl shadow-primary/20">
                <i class="fas <?= $icon ?> text-2xl"></i>
            </div>
            <div>
                <h1 class="text-3xl font-black text-primary tracking-tight"><?= $page_title ?></h1>
                <p class="text-text-muted font-bold opacity-80"><?= $page_subtitle ?></p>
            </div>
        </div>
    </div>

<div class="card">
    <div class="card-header" style="background: #fffdf2;">
        <div class="card-title">
            <i class="fas fa-desktop" style="color: #64748b;"></i>
            จองห้องประชุม > <?php echo $breadcrumb; ?>
        </div>
    </div>
    <div class="card-body">
        <div class="filter-bar">
            <div class="search-input" style="position: relative;">
                <i class="fas fa-search" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                <input type="text" id="searchInput" placeholder="ค้นหา..." style="padding-left: 2.5rem;">
            </div>
            <select id="statusFilter" class="select-filter">
                <option value="">สถานะทั้งหมด</option>
                <option value="approved">อนุมัติ</option>
                <option value="pending">รออนุมัติ</option>
                <option value="rejected">ไม่อนุมัติ</option>
                <option value="cancelled">ยกเลิก</option>
            </select>
        </div>

<<<<<<< HEAD
        <div class="table-responsive">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#FAEDCD]">
                        <th class="p-4 font-bold text-[#6A5243] border-b border-[#EBE6DA] w-[60px]">ลำดับ</th>
                        <th class="p-4 font-bold text-[#6A5243] border-b border-[#EBE6DA]">หัวข้อการประชุม</th>
                        <th class="p-4 font-bold text-[#6A5243] border-b border-[#EBE6DA]">ห้องประชุม</th>
                        <th class="p-4 font-bold text-[#6A5243] border-b border-[#EBE6DA]">วันที่ใช้งาน</th>
                        <th class="p-4 font-bold text-[#6A5243] border-b border-[#EBE6DA]">ช่วงเวลา</th>
                        <th class="p-4 font-bold text-[#6A5243] border-b border-[#EBE6DA]">หน่วยงาน</th>
                        <th class="p-4 font-bold text-[#6A5243] border-b border-[#EBE6DA] text-center">สถานะ</th>
                    </tr>
                </thead>
                <tbody id="approveTableBody">
                    <tr>
                        <td colspan="7" class="p-10 text-center text-[#A79A8B]">กำลังโหลดข้อมูล...</td>
                    </tr>
                </tbody>
            </table>
        </div>
=======
            <!-- Desktop Table -->
            <div class="hidden md:block table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th class="w-16">ลำดับ</th>
                            <th>ผู้จอง</th>
                            <th>หัวข้อการประชุม</th>
                            <th>ห้องประชุม/สถานที่</th>
                            <th>วัน-เวลา</th>
                            <th>ฝ่าย/งาน</th>
                            <th class="text-center">สถานะ</th>
                            <?php if ($current_view === 'approve_list'): ?>
                            <th class="text-center">การจัดการ</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody id="approveTableBody">
                        <!-- Data will be loaded via JS -->
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card List -->
            <div id="mobileCardList" class="md:hidden space-y-4">
                <!-- Data will be loaded via JS -->
            </div>
>>>>>>> f2fbaf64a5040b047b58efcc47c17af94761a996

        <div class="pagination" id="paginationContainer" style="display: none;">
            <!-- Pagination will be rendered here -->
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        loadApproveList();
        document.getElementById('searchInput').addEventListener('input', renderTable);
        document.getElementById('statusFilter').addEventListener('change', renderTable);
    });

    async function loadApproveList() {
        const isAdminView = <?php echo json_encode($is_admin_view); ?>;
        const forceUserFilter = <?php echo json_encode($force_user_filter); ?>;
        const view = <?php echo json_encode($current_view); ?>;
        const userId = <?php echo json_encode($_SESSION['user_id'] ?? null); ?>;
        
        let url = 'api/bookings.php';
        if (forceUserFilter) {
            url += `?user_id=${userId}`;
        }
        
        const data = await MeetQueue.api.fetch(url);
        
        if (data.success) {
            MeetQueue.setState({ bookings: data.bookings });
            
            // Auto-filter pending for approve_list view
            if (view === 'approve_list') {
                document.getElementById('statusFilter').value = 'pending';
            }
            
            renderTable();
        } else {
            document.getElementById('approveTableBody').innerHTML = `<tr><td colspan="7" class="text-center text-red-500 font-bold py-10">${data.message}</td></tr>`;
        }
    }

    function renderTable() {
        const tableBody = document.getElementById('approveTableBody');
        const mobileCardList = document.getElementById('mobileCardList');
        const searchQuery = document.getElementById('searchInput').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value;
        const currentView = <?php echo json_encode($current_view); ?>;
        const { bookings } = MeetQueue.getState();

        const filtered = bookings.filter(b => {
            const matchesSearch = b.title.toLowerCase().includes(searchQuery) || 
                                 (b.room_name?.toLowerCase().includes(searchQuery)) ||
                                 (b.first_name?.toLowerCase().includes(searchQuery));
            const matchesStatus = statusFilter === '' || b.status === statusFilter;
            return matchesSearch && matchesStatus;
        });

        if (filtered.length === 0) {
            const emptyHtml = '<div class="text-center py-20 bg-white/50 rounded-[3rem] border-2 border-dashed border-accent"><i class="fas fa-folder-open text-4xl text-accent mb-4 block"></i><p class="font-bold text-text-muted">ไม่พบข้อมูลที่ค้นหา</p></div>';
            tableBody.innerHTML = `<tr><td colspan="${currentView === 'approve_list' ? 8 : 7}" class="text-center py-20 font-bold text-text-muted">ไม่พบข้อมูล</td></tr>`;
            mobileCardList.innerHTML = emptyHtml;
            return;
        }

        // Desktop
        tableBody.innerHTML = filtered.map((b, i) => `
            <tr class="hover:bg-primary/5 transition-colors cursor-pointer group">
                <td class="font-bold text-text-muted" onclick="viewDetail(${b.id})">${i + 1}</td>
                <td onclick="viewDetail(${b.id})">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl overflow-hidden bg-accent/30 flex-shrink-0">
                            <img src="https://192.168.9.7/auth_files/photo/${b.emp_code}.jpg" 
                                 onerror="this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(b.first_name)}&background=E6D6BD&color=6A5243'" 
                                 class="w-full h-full object-cover">
                        </div>
                        <div class="flex flex-col">
                            <span class="font-bold text-primary">${b.first_name}</span>
                            <span class="text-[0.65rem] text-text-muted font-bold uppercase">${b.emp_code}</span>
                        </div>
                    </div>
                </td>
                <td onclick="viewDetail(${b.id})">
                    <div class="font-bold text-primary">${MeetQueue.utils.escapeHtml(b.title)}</div>
                    <div class="text-[0.7rem] text-text-muted italic">${b.description ? MeetQueue.utils.escapeHtml(b.description).substring(0, 30) + '...' : ''}</div>
                </td>
                <td onclick="viewDetail(${b.id})">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-lg bg-accent/20 flex items-center justify-center text-primary"><i class="fas fa-door-open text-xs"></i></div>
                        <span class="font-semibold">${b.room_name || 'ภายนอก'}</span>
                    </div>
                </td>
                <td onclick="viewDetail(${b.id})">
                    <div class="font-bold text-primary">${MeetQueue.utils.formatDate(b.start_time)}</div>
                    <div class="text-xs text-text-muted font-semibold">${MeetQueue.utils.formatTime(b.start_time)} - ${MeetQueue.utils.formatTime(b.end_time)}</div>
                </td>
                <td onclick="viewDetail(${b.id})"><span class="text-xs font-bold px-2 py-1 bg-accent/20 rounded-md text-primary">${b.department_name || '-'}</span></td>
                <td class="text-center" onclick="viewDetail(${b.id})">
                    <span class="badge badge-${MeetQueue.utils.getStatusClass(b.status)}">${MeetQueue.utils.translateStatus(b.status)}</span>
                </td>
                ${currentView === 'approve_list' ? `
                <td class="text-center">
                    <div class="flex justify-center gap-2">
                        ${b.status === 'pending' ? `
                            <button onclick="updateStatus(${b.id}, 'approved')" class="w-8 h-8 rounded-lg bg-green-100 text-green-600 hover:bg-green-600 hover:text-white transition-all shadow-sm" title="อนุมัติ"><i class="fas fa-check text-xs"></i></button>
                            <button onclick="updateStatus(${b.id}, 'rejected')" class="w-8 h-8 rounded-lg bg-red-100 text-red-600 hover:bg-red-600 hover:text-white transition-all shadow-sm" title="ปฏิเสธ"><i class="fas fa-times text-xs"></i></button>
                        ` : `
                            <button onclick="viewDetail(${b.id})" class="text-xs font-bold text-text-muted hover:text-primary">เรียกดู</button>
                        `}
                    </div>
                </td>
                ` : ''}
            </tr>
        `).join('');

<<<<<<< HEAD
            const roomDisplay = booking.is_external ? `(ภายนอก) ${booking.external_org || ''}` : (booking.room_name || '-');

            return `
                <tr class="hover:bg-white/40 transition-colors border-b border-[#EBE6DA]">
                    <td class="p-4 text-[#A79A8B] font-bold text-sm">${index + 1}</td>
                    <td class="p-4">
                        <div class="font-bold text-[#6A5243]">${escapeHtml(booking.title)}</div>
                        ${booking.is_external ? '<span class="inline-block mt-1 px-2 py-0.5 bg-[#D4B59D]/20 text-[#6A5243] text-[0.65rem] font-bold rounded-lg uppercase">ภายนอก</span>' : ''}
                    </td>
                    <td class="p-4 text-sm font-medium text-[#6A5243]">${escapeHtml(roomDisplay)}</td>
                    <td class="p-4 text-sm text-[#6A5243]">${dateStr}</td>
                    <td class="p-4 text-sm font-mono text-[#A79A8B]">${timeStr}</td>
                    <td class="p-4 text-sm text-[#A79A8B]">${escapeHtml(booking.department_name || '-')}</td>
                    <td class="p-4 text-center">
                        <span class="badge ${badgeClass}">${statusText}</span>
                    </td>
                </tr>
            `;
        }).join('');
=======
        // Mobile
        mobileCardList.innerHTML = filtered.map(b => `
            <div class="card p-5 space-y-4 hover:border-primary/50 transition-all">
                <div class="flex justify-between items-start" onclick="viewDetail(${b.id})">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-2xl overflow-hidden bg-accent/30 shadow-inner">
                            <img src="https://192.168.9.7/auth_files/photo/${b.emp_code}.jpg" 
                                 onerror="this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(b.first_name)}&background=E6D6BD&color=6A5243'" 
                                 class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h4 class="font-black text-primary">${b.first_name}</h4>
                            <span class="text-[0.7rem] font-bold text-text-muted uppercase tracking-wider">${b.department_name || '-'}</span>
                        </div>
                    </div>
                    <span class="badge badge-${MeetQueue.utils.getStatusClass(b.status)}">${MeetQueue.utils.translateStatus(b.status)}</span>
                </div>
                <div class="space-y-3 pt-2" onclick="viewDetail(${b.id})">
                    <h3 class="font-black text-lg leading-tight text-primary">${MeetQueue.utils.escapeHtml(b.title)}</h3>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="flex items-center gap-2 text-text-muted"><i class="fas fa-door-open text-xs w-4"></i><span class="text-xs font-bold">${b.room_name || 'ภายนอก'}</span></div>
                        <div class="flex items-center gap-2 text-text-muted"><i class="fas fa-clock text-xs w-4"></i><span class="text-xs font-bold">${MeetQueue.utils.formatTime(b.start_time)} - ${MeetQueue.utils.formatTime(b.end_time)}</span></div>
                        <div class="flex items-center gap-2 text-text-muted col-span-2"><i class="fas fa-calendar-alt text-xs w-4"></i><span class="text-xs font-bold">${MeetQueue.utils.formatDate(b.start_time)}</span></div>
                    </div>
                </div>
                ${currentView === 'approve_list' && b.status === 'pending' ? `
                <div class="pt-4 flex gap-3">
                    <button onclick="updateStatus(${b.id}, 'approved')" class="flex-grow py-3 rounded-xl bg-green-500 text-white font-black text-sm shadow-lg shadow-green-200">อนุมัติ</button>
                    <button onclick="updateStatus(${b.id}, 'rejected')" class="flex-grow py-3 rounded-xl bg-red-500 text-white font-black text-sm shadow-lg shadow-red-200">ปฏิเสธ</button>
                </div>
                ` : `
                <div class="pt-2 flex justify-end" onclick="viewDetail(${b.id})">
                    <span class="text-[0.65rem] font-black text-secondary uppercase tracking-widest flex items-center gap-1">ดูรายละเอียด <i class="fas fa-chevron-right text-[0.5rem]"></i></span>
                </div>
                `}
            </div>
        `).join('');
>>>>>>> f2fbaf64a5040b047b58efcc47c17af94761a996
    }

    async function updateStatus(id, status) {
        const confirmMsg = status === 'approved' ? 'ยืนยันการอนุมัติ?' : 'ยืนยันการปฏิเสธ?';
        const result = await Swal.fire({
            title: confirmMsg,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: status === 'approved' ? '#22c55e' : '#ef4444',
            confirmButtonText: 'ตกลง',
            cancelButtonText: 'ยกเลิก'
        });

        if (result.isConfirmed) {
            MeetQueue.utils.loading(true);
            const data = await MeetQueue.api.fetch('api/bookings.php', {
                method: 'PATCH',
                body: JSON.stringify({ booking_id: id, status: status })
            });
            MeetQueue.utils.loading(false);

            if (data.success) {
                MeetQueue.utils.notify('success', 'ดำเนินการสำเร็จ');
                loadApproveList();
            } else {
                MeetQueue.utils.notify('error', 'ผิดพลาด', data.message);
            }
        }
    }

    function viewDetail(id) {
        window.location.href = 'dashboard.php?view=booking_result&id=' + id;
    }
</script>
