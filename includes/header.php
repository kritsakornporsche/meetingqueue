<header>
    <div class="header-left">
        <button id="mobileMenuBtn" class="icon-btn"><i class="fas fa-bars"></i></button>
        <div class="breadcrumb">
            <i class="fas fa-hospital"></i> <strong>:: โรงพยาบาลพาน ::</strong>
            <span style="margin-left: 1rem;">
                <?php
                $view = $_GET['view'] ?? 'calendar';
                $titles = [
                    'calendar' => 'หน้าหลัก > ปฏิทิน',
                    'book' => 'หน้าหลัก > จองห้องประชุม',
                    'results' => 'หน้าหลัก > ผลการอนุมัติ',
                    'reports' => 'หน้าหลัก > รายงานการใช้',
                    'requests' => 'จองห้องประชุม > รายการขอใช้',
                    'approve_list' => 'จองห้องประชุม > รายการอนุมัติ',
                    'external' => 'หน้าหลัก > บันทึกประชุมภายนอก',
                    'rooms' => 'หน้าหลัก > ข้อมูลห้องประชุม'
                ];
                echo $titles[$view] ?? 'หน้าหลัก';
                ?>
            </span>
        </div>
    </div>
    <div class="header-right">
        <!-- Action items removed -->
    </div>
</header>
