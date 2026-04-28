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
        <div style="font-size: 0.8125rem; color: var(--success); font-weight: 600;">
            <i class="fas fa-circle" style="font-size: 0.5rem; vertical-align: middle;"></i> ออนไลน์: 29
        </div>
        <button class="icon-btn"><i class="fas fa-bell"></i><span class="notification-dot"></span></button>
        <button class="icon-btn"><i class="fas fa-gear"></i></button>
        <button class="icon-btn"><i class="fas fa-sun"></i></button>
    </div>
</header>
