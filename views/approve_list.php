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
                <input type="text" placeholder="ค้นหา..." style="padding-left: 2.5rem;">
            </div>
            <select class="select-filter">
                <option value="">-- ห้องประชุม --</option>
            </select>
            <select class="select-filter">
                <option value="">สถานะทั้งหมด</option>
                <option value="approved">อนุมัติ</option>
                <option value="pending">รออนุมัติ</option>
            </select>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th style="width: 60px;">รูปภาพ</th>
                        <th>เพื่อ</th>
                        <th>ห้องประชุม</th>
                        <th>วันที่</th>
                        <th>ช่วงเวลา</th>
                        <th>หน่วยงาน</th>
                        <th style="text-align: center;">สถานะ</th>
                    </tr>
                </thead>
                <tbody id="approveTableBody">
                    <!-- Dummy Data for Preview -->
                    <tr>
                        <td>1</td>
                        <td><a href="#" style="color: var(--primary); text-decoration: none;">อบรม เวที สมัชชาสุขภาพจังหวัดเชียงราย ประเด็น Long Will</a></td>
                        <td>ห้องประชุมเนื้อตาลชั้นกลาง (ห้องพึ่งตนเอง) (40-50 คน)</td>
                        <td>6 พ.ค. 69</td>
                        <td>กำหนดเอง</td>
                        <td>งานเทคโนโลยีสารสนเทศและพัฒนาระบบสุขภาพดิจิทัล</td>
                        <td style="text-align: center;"><span class="badge badge-success">อนุมัติ</span></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td><a href="#" style="color: var(--primary); text-decoration: none;">ประชุม PCT PED</a></td>
                        <td>ห้องประชุมเนื้อตาลชั้นล่าง (องค์กรแพทย์) (10-15 คน)</td>
                        <td>29 เม.ย. 69</td>
                        <td>กำหนดเอง</td>
                        <td>งานการพยาบาลผู้ป่วยกุมารเวชกรรม</td>
                        <td style="text-align: center;"><span class="badge badge-success">อนุมัติ</span></td>
                    </tr>
                    <!-- More rows will be loaded via API -->
                </tbody>
            </table>
        </div>

        <div class="pagination">
            <div>แสดงหน้า 1 จาก 257 รายการทั้งหมด 2,569 รายการ</div>
            <div class="page-links">
                <a href="#" class="page-link">ย้อนกลับ</a>
                <a href="#" class="page-link active">1</a>
                <a href="#" class="page-link">2</a>
                <a href="#" class="page-link">3</a>
                <a href="#" class="page-link">4</a>
                <a href="#" class="page-link">5</a>
                <span>...</span>
                <a href="#" class="page-link">257</a>
                <a href="#" class="page-link">ถัดไป</a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        loadApproveList();
    });

    async function loadApproveList() {
        // Implement API call here
    }
</script>
