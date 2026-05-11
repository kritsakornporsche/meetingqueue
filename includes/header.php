<?php
$user = $_SESSION['user_data'];
$photo_url = "https://192.168.9.7/auth_files/photo/" . $user['emp_code'] . ".jpg";
$fallback_avatar = 'https://ui-avatars.com/api/?name=' . urlencode($user['first_name']) . '&background=6A5243&color=fff&size=80';
$view = $_GET['view'] ?? 'calendar';
$titles = [
    'calendar' => 'หน้าหลัก > ปฏิทิน',
    'book' => 'หน้าหลัก > จองห้องประชุม',
    'results' => 'หน้าหลัก > สถานะการจอง',
    'reports' => 'หน้าหลัก > รายงานการใช้',
    'requests' => 'จองห้องประชุม > รายการขอใช้',
    'approve_list' => 'จองห้องประชุม > รายการอนุมัติ',
    'external' => 'หน้าหลัก > บันทึกประชุมภายนอก',
    'rooms' => 'หน้าหลัก > ข้อมูลห้องประชุม'
];
?>
<header>
    <div class="header-left">
        <div class="header-brand">
            <i class="fas fa-hospital-user"></i>
            <div class="header-brand-text">
                <span class="header-brand-title">จองห้องประชุม</span>
                <span class="header-brand-sub">โรงพยาบาลพาน</span>
            </div>
        </div>
        <div class="breadcrumb">
            <span><?php echo $titles[$view] ?? 'หน้าหลัก'; ?></span>
        </div>
    </div>
    <div class="header-right">
        <!-- User Profile -->
        <div class="header-profile" id="headerProfile">
            <img src="<?php echo $photo_url; ?>" onerror="this.src='<?php echo $fallback_avatar; ?>'" alt="Avatar" class="header-avatar">
            <div class="header-profile-info">
                <span class="header-profile-name"><?php echo htmlspecialchars(trim($user['first_name'] . ' ' . ($user['last_name'] ?? ''))); ?></span>
                <span class="header-profile-role"><?php echo htmlspecialchars($user['position_name'] ?? 'บุคลากร'); ?></span>
            </div>
        </div>
        <!-- Hamburger Menu -->
        <button id="hamburgerMenuBtn" class="hamburger-btn" aria-label="เปิดเมนู">
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
        </button>
    </div>
</header>

<!-- Popup Menu Overlay -->
<div id="popupMenuOverlay" class="popup-menu-overlay"></div>

<!-- Popup Menu -->
<div id="popupMenu" class="popup-menu">
    <div class="popup-menu-header">
        <div class="popup-menu-profile">
            <img src="<?php echo $photo_url; ?>" onerror="this.src='<?php echo $fallback_avatar; ?>'" alt="Avatar" class="popup-menu-avatar">
            <div>
                <div class="popup-menu-name"><?php echo htmlspecialchars(trim($user['first_name'] . ' ' . ($user['last_name'] ?? ''))); ?></div>
                <div class="popup-menu-role"><?php echo htmlspecialchars($user['position_name'] ?? 'บุคลากร'); ?></div>
            </div>
        </div>
        <button id="popupMenuClose" class="popup-menu-close" aria-label="ปิดเมนู"><i class="fas fa-times"></i></button>
    </div>
    
    <div class="popup-menu-body">
        <div class="popup-menu-section">
            <div class="popup-menu-label">เมนูจองห้องประชุม</div>
            <nav class="popup-nav">
                <a href="dashboard.php?view=calendar" class="popup-nav-link <?php echo (!isset($_GET['view']) || $_GET['view'] == 'calendar') ? 'active' : ''; ?>">
                    <i class="fas fa-calendar-days"></i><span>ปฏิทิน</span>
                </a>
                <a href="dashboard.php?view=book" class="popup-nav-link <?php echo ($_GET['view'] ?? '') == 'book' ? 'active' : ''; ?>">
                    <i class="fas fa-calendar-plus"></i><span>จองห้องประชุม</span>
                </a>
                <a href="dashboard.php?view=results" class="popup-nav-link <?php echo ($_GET['view'] ?? '') == 'results' ? 'active' : ''; ?>">
                    <i class="fas fa-clipboard-check"></i><span>สถานะการประชุมของฉัน</span>
                </a>
                <a href="dashboard.php?view=room_status" class="popup-nav-link <?php echo ($_GET['view'] ?? '') == 'room_status' ? 'active' : ''; ?>">
                    <i class="fas fa-door-open"></i><span>สถานะการประชุม</span>
                </a>
                <a href="dashboard.php?view=statistics" class="popup-nav-link <?php echo ($_GET['view'] ?? '') == 'statistics' ? 'active' : ''; ?>">
                    <i class="fas fa-chart-pie"></i><span>สถิติการใช้งาน</span>
                </a>
                <a href="dashboard.php?view=reports" class="popup-nav-link <?php echo ($_GET['view'] ?? '') == 'reports' ? 'active' : ''; ?>">
                    <i class="fas fa-chart-line"></i><span>รายงานการใช้ห้องประชุม</span>
                </a>
                <a href="dashboard.php?view=history" class="popup-nav-link <?php echo ($_GET['view'] ?? '') == 'history' ? 'active' : ''; ?>">
                    <i class="fas fa-history"></i><span>ประวัติการประชุม</span>
                </a>
            </nav>
        </div>

        <?php if (($user['role'] ?? 'user') == 'admin'): ?>
        <div class="popup-menu-section">
            <div class="popup-menu-label">ผู้ดูแลระบบ</div>
            <nav class="popup-nav">
                <a href="dashboard.php?view=admin_management" class="popup-nav-link <?php echo ($_GET['view'] ?? '') == 'admin_management' ? 'active' : ''; ?>">
                    <i class="fas fa-tasks"></i><span>จัดการการประชุม</span>
                </a>
                <a href="dashboard.php?view=trash_management" class="popup-nav-link <?php echo ($_GET['view'] ?? '') == 'trash_management' ? 'active' : ''; ?>">
                    <i class="fas fa-trash-alt"></i><span>ถังขยะ</span>
                </a>
                <a href="dashboard.php?view=requests" class="popup-nav-link <?php echo ($_GET['view'] ?? '') == 'requests' ? 'active' : ''; ?>">
                    <i class="fas fa-list-ul"></i><span>รายการขอใช้</span>
                </a>
                <a href="dashboard.php?view=approve_list" class="popup-nav-link <?php echo ($_GET['view'] ?? '') == 'approve_list' ? 'active' : ''; ?>">
                    <i class="fas fa-user-check"></i><span>รายการอนุมัติ</span>
                </a>
                <a href="dashboard.php?view=external" class="popup-nav-link <?php echo ($_GET['view'] ?? '') == 'external' ? 'active' : ''; ?>">
                    <i class="fas fa-file-signature"></i><span>บันทึกประชุมภายนอก</span>
                </a>
                <a href="dashboard.php?view=rooms" class="popup-nav-link <?php echo ($_GET['view'] ?? '') == 'rooms' ? 'active' : ''; ?>">
                    <i class="fas fa-door-open"></i><span>ข้อมูลห้องประชุม</span>
                </a>
                <a href="dashboard.php?view=users" class="popup-nav-link <?php echo ($_GET['view'] ?? '') == 'users' ? 'active' : ''; ?>">
                    <i class="fas fa-users-gear"></i><span>ข้อมูลผู้ใช้งาน</span>
                </a>
            </nav>
        </div>
        <?php endif; ?>
    </div>
    
    <div class="popup-menu-footer">
        <a href="api/logout.php" class="popup-logout-btn">
            <i class="fas fa-sign-out-alt"></i> ออกจากระบบ
        </a>
    </div>
</div>
