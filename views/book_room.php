<div class="hero-banner" style="background-image: url('assets/images/poster2.png'); background-position: top; max-width: 900px; margin: 0 auto 2rem;">
    <div class="hero-content">
        <h2 style="text-shadow: 0 2px 4px rgba(0,0,0,0.5);">แบบฟอร์มจองห้องประชุม</h2>
        <p style="opacity: 0.9; text-shadow: 0 1px 2px rgba(0,0,0,0.5);">กรุณากรอกข้อมูลให้ครบถ้วนเพื่อความรวดเร็วในการพิจารณา</p>
    </div>
</div>

<div class="card" style="max-width: 900px; margin: 0 auto;">
    <div class="card-header" style="background: #fffdf2;">
        <div class="card-title">
            <i class="fas fa-desktop" style="color: #64748b;"></i>
            จองห้องประชุม
        </div>
    </div>
    <div class="card-body" style="padding: 2.5rem;">
        <form id="bookingForm" enctype="multipart/form-data">
            <div class="form-group">
                <label>ห้องประชุม <span style="color: var(--danger);">*</span></label>
                <select id="room_id" required>
                    <option value="">เลือกรายการ</option>
                    <!-- Rooms will be loaded via API -->
                </select>
            </div>

            <div class="form-group">
                <label>กิจกรรม <span style="color: var(--danger);">*</span></label>
                <input type="text" id="title" placeholder="เรื่อง" required>
            </div>

            <div class="filter-bar" style="margin-bottom: 1.5rem;">
                <div class="form-group" style="flex: 1; margin-bottom: 0;">
                    <label>วันที่ <span style="color: var(--danger);">*</span></label>
                    <input type="date" id="meeting_date" required>
                </div>
                <div class="form-group" style="flex: 1; margin-bottom: 0;">
                    <label>เริ่มเวลา</label>
                    <input type="time" id="start_time" value="00:00" required>
                </div>
                <div class="form-group" style="flex: 1; margin-bottom: 0;">
                    <label>ถึงเวลา</label>
                    <input type="time" id="end_time" value="00:00" required>
                </div>
            </div>

            <div class="form-group" style="max-width: 200px;">
                <label>จำนวนผู้เข้าประชุม(คน) <span style="color: var(--danger);">*</span></label>
                <input type="number" id="participants_count" placeholder="คน" required>
            </div>

            <div class="form-group">
                <label>หมายเหตุ</label>
                <textarea id="description" rows="3" placeholder="หมายเหตุ"></textarea>
            </div>

            <div class="form-group" style="max-width: 200px;">
                <label>หมายเลขโทรศัพท์ <span style="color: var(--danger);">*</span></label>
                <input type="text" id="phone" placeholder="หมายเลขโทรศัพท์" required>
            </div>

            <div class="form-group">
                <label>ไฟล์แนบ <span style="font-size: 0.75rem; color: var(--text-muted);">(ประกาศ, กำหนดการ ฯลฯ)</span></label>
                <div style="display: flex; gap: 1rem; align-items: center;">
                    <input type="file" id="attachment" style="flex: 1; padding: 0.4rem;">
                </div>
            </div>

            <div style="margin-top: 2rem;">
                <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2.5rem;">
                    บันทึกข้อมูล
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        loadRoomsForSelect();
        
        document.getElementById('bookingForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const submitBtn = e.target.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> กำลังบันทึก...';
            submitBtn.disabled = true;

            try {
                const formData = new FormData();
                formData.append('room_id', document.getElementById('room_id').value);
                formData.append('title', document.getElementById('title').value);
                formData.append('meeting_date', document.getElementById('meeting_date').value);
                formData.append('start_time', document.getElementById('start_time').value);
                formData.append('end_time', document.getElementById('end_time').value);
                formData.append('participants_count', document.getElementById('participants_count').value);
                formData.append('description', document.getElementById('description').value);
                formData.append('phone', document.getElementById('phone').value);
                
                const attachment = document.getElementById('attachment').files[0];
                if (attachment) {
                    formData.append('attachment', attachment);
                }

                const res = await fetch('api/book_room.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await res.json();
                
                if (data.success) {
                    // Try to use SweetAlert if available, otherwise fallback
                    if (typeof Swal !== 'undefined') {
                        await Swal.fire({
                            icon: 'success',
                            title: 'บันทึกข้อมูลสำเร็จ',
                            text: 'ระบบได้ส่งคำขอจองห้องประชุมเรียบร้อยแล้ว กรุณารอการอนุมัติ',
                            confirmButtonColor: '#6A5243'
                        });
                        window.location.href = 'dashboard.php?view=calendar';
                    } else {
                        alert('บันทึกข้อมูลสำเร็จ! ระบบได้ส่งคำขอจองห้องประชุมเรียบร้อยแล้ว');
                        window.location.href = 'dashboard.php?view=calendar';
                    }
                } else {
                    throw new Error(data.error || 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
                }
            } catch (err) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'ผิดพลาด',
                        text: err.message,
                        confirmButtonColor: '#6A5243'
                    });
                } else {
                    alert('ข้อผิดพลาด: ' + err.message);
                }
            } finally {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        });
    });

    async function loadRoomsForSelect() {
        try {
            const res = await fetch('api/rooms.php');
            const data = await res.json();
            if (data.success) {
                const select = document.getElementById('room_id');
                data.rooms.forEach(room => {
                    const opt = document.createElement('option');
                    opt.value = room.id;
                    opt.textContent = room.name;
                    select.appendChild(opt);
                });
                
                // Initialize Choices.js
                if (typeof Choices !== 'undefined') {
                    new Choices(select, {
                        searchEnabled: true,
                        itemSelectText: 'กดเพื่อเลือก',
                        noResultsText: 'ไม่พบข้อมูล',
                        shouldSort: false
                    });
                }
            }
        } catch (e) {
            console.error(e);
        }
    }
</script>
