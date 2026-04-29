<div class="hero-banner" style="background-image: url('assets/images/poster1.png');">
    <div class="hero-content">
        <h2>รายการอนุมัติห้องประชุม</h2>
        <p style="opacity: 0.9;">ติดตามและจัดการสถานะการจองห้องประชุมของคุณ</p>
    </div>
</div>

<div class="card">
    <div class="card-header" style="background: #fffdf2;">
        <div class="card-title">
            <i class="fas fa-desktop" style="color: #64748b;"></i>
            จองห้องประชุม > รายการอนุมัติ
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

        <div class="pagination" id="paginationContainer" style="display: none;">
            <!-- Pagination will be rendered here -->
        </div>
    </div>
</div>

<script>
    let allBookings = [];

    document.addEventListener('DOMContentLoaded', () => {
        loadApproveList();

        document.getElementById('searchInput').addEventListener('input', renderTable);
        document.getElementById('statusFilter').addEventListener('change', renderTable);
    });

    async function loadApproveList() {
        try {
            // Fetch bookings for the logged-in user
            const userId = <?php echo json_encode($_SESSION['user_id'] ?? null); ?>;
            const url = `api/bookings.php${userId ? '?user_id=' + userId : ''}`;
            
            const response = await fetch(url);
            const data = await response.json();
            
            if (data.success) {
                allBookings = data.bookings;
                renderTable();
            } else {
                document.getElementById('approveTableBody').innerHTML = `<tr><td colspan="7" style="text-align: center; color: red;">ข้อผิดพลาด: ${data.message}</td></tr>`;
            }
        } catch (error) {
            console.error('Error fetching bookings:', error);
            document.getElementById('approveTableBody').innerHTML = `<tr><td colspan="7" style="text-align: center; color: red;">เกิดข้อผิดพลาดในการโหลดข้อมูล</td></tr>`;
        }
    }

    function renderTable() {
        const tbody = document.getElementById('approveTableBody');
        const searchQuery = document.getElementById('searchInput').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value;

        // Filter data
        const filteredBookings = allBookings.filter(booking => {
            const matchesSearch = booking.title.toLowerCase().includes(searchQuery) || 
                                  (booking.room_name && booking.room_name.toLowerCase().includes(searchQuery));
            const matchesStatus = statusFilter === '' || booking.status === statusFilter;
            return matchesSearch && matchesStatus;
        });

        if (filteredBookings.length === 0) {
            tbody.innerHTML = `<tr><td colspan="7" style="text-align: center;">ไม่พบข้อมูล</td></tr>`;
            return;
        }

        tbody.innerHTML = filteredBookings.map((booking, index) => {
            // Format dates
            const startDate = new Date(booking.start_time);
            const endDate = new Date(booking.end_time);
            
            const dateStr = startDate.toLocaleDateString('th-TH', {
                year: 'numeric', month: 'short', day: 'numeric'
            });
            
            const timeStr = `${startDate.toLocaleTimeString('th-TH', {hour: '2-digit', minute:'2-digit'})} - ${endDate.toLocaleTimeString('th-TH', {hour: '2-digit', minute:'2-digit'})}`;
            
            // Format status badge
            let badgeClass = 'badge-primary';
            let statusText = 'ไม่ทราบสถานะ';
            
            switch (booking.status) {
                case 'pending': badgeClass = 'badge-warning'; statusText = 'รออนุมัติ'; break;
                case 'approved': badgeClass = 'badge-success'; statusText = 'อนุมัติแล้ว'; break;
                case 'rejected': badgeClass = 'badge-danger'; statusText = 'ไม่อนุมัติ'; break;
                case 'cancelled': badgeClass = 'badge-primary'; statusText = 'ยกเลิก'; break;
            }

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
    }

    function escapeHtml(unsafe) {
        if (!unsafe) return '';
        return unsafe
             .toString()
             .replace(/&/g, "&amp;")
             .replace(/</g, "&lt;")
             .replace(/>/g, "&gt;")
             .replace(/"/g, "&quot;")
             .replace(/'/g, "&#039;");
    }
</script>
