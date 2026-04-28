<div class="hero-banner" style="background-image: url('assets/images/poster1.png'); background-position: center; max-width: 900px; margin: 0 auto 2rem;">
    <div class="hero-content">
        <h2 style="text-shadow: 0 2px 4px rgba(0,0,0,0.5);">บันทึกประชุมภายนอก</h2>
        <p style="opacity: 0.9; text-shadow: 0 1px 2px rgba(0,0,0,0.5);">บันทึกข้อมูลการประชุมนอกสถานที่ของบุคลากร</p>
    </div>
</div>

<div class="card" style="max-width: 900px; margin: 0 auto;">
    <div class="card-header" style="background: #fffdf2;">
        <div class="card-title">
            <i class="fas fa-desktop" style="color: #64748b;"></i>
            บันทึกประชุมภายนอก
        </div>
    </div>
    <div class="card-body" style="padding: 2.5rem;">
        <form id="externalMeetingForm">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div class="form-group">
                    <label>ห้องประชุม <span style="color: var(--danger);">*</span></label>
                    <select id="ext_room_id" required>
                        <option value="">เลือกรายการ</option>
                        <option value="external">ภายนอกหน่วยงาน</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>หน่วยงาน <span style="color: var(--danger);">*</span></label>
                    <input type="text" id="external_org" placeholder="หน่วยงาน" required>
                </div>
            </div>

            <div class="form-group">
                <label>กิจกรรม <span style="color: var(--danger);">*</span></label>
                <input type="text" id="ext_title" placeholder="เรื่อง" required>
            </div>

            <div class="filter-bar" style="margin-bottom: 1.5rem;">
                <div class="form-group" style="flex: 1; margin-bottom: 0;">
                    <label>วันที่ <span style="color: var(--danger);">*</span></label>
                    <input type="date" id="ext_date" required>
                </div>
                <div class="form-group" style="flex: 1; margin-bottom: 0;">
                    <label>เริ่มเวลา</label>
                    <input type="time" id="ext_start_time" value="00:00" required>
                </div>
                <div class="form-group" style="flex: 1; margin-bottom: 0;">
                    <label>ถึงเวลา</label>
                    <input type="time" id="ext_end_time" value="00:00" required>
                </div>
            </div>

            <div class="form-group" style="max-width: 200px;">
                <label>จำนวนผู้เข้าประชุม(คน) <span style="color: var(--danger);">*</span></label>
                <input type="number" id="ext_participants_count" placeholder="คน" required>
            </div>

            <div class="form-group">
                <label>หมายเหตุ</label>
                <textarea id="ext_description" rows="3" placeholder="หมายเหตุ"></textarea>
            </div>

            <div class="form-group" style="max-width: 200px;">
                <label>หมายเลขโทรศัพท์ <span style="color: var(--danger);">*</span></label>
                <input type="text" id="ext_phone" placeholder="หมายเลขโทรศัพท์" required>
            </div>

            <div class="form-group">
                <label>ไฟล์แนบ <span style="font-size: 0.75rem; color: var(--text-muted);">(ประกาศ, กำหนดการ ฯลฯ)</span></label>
                <input type="file" id="ext_attachment" style="padding: 0.4rem;">
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
    document.getElementById('externalMeetingForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        alert('บันทึกข้อมูลการประชุมภายนอกสำเร็จ!');
    });
</script>
