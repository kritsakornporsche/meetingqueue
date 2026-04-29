<?php
$current_page = basename($_SERVER['PHP_SELF']);
$user = $_SESSION['user_data'];
?>
<aside id="sidebar">
    <div class="sidebar-logo">
        <i class="fas fa-hospital-user"></i>
        <div style="display: flex; flex-direction: column; line-height: 1.2;">
            <span style="font-size: 1.1rem;">จองห้องประชุม</span>
            <span style="font-size: 0.8rem; font-weight: 500; opacity: 0.8;">โรงพยาบาลพาน</span>
        </div>
    </div>

    <div class="nav-section">
        <div class="nav-label">เมนูจองห้องประชุม</div>
        <ul class="nav-links">
            <li>
                <a href="dashboard.php?view=calendar" class="<?php echo (!isset($_GET['view']) || $_GET['view'] == 'calendar') ? 'active' : ''; ?>">
                    <i class="fas fa-calendar-days"></i>
                    <span>ปฏิทิน</span>
                </a>
            </li>
            <li>
                <a href="dashboard.php?view=book" class="<?php echo ($_GET['view'] ?? '') == 'book' ? 'active' : ''; ?>">
                    <i class="fas fa-calendar-plus"></i>
                    <span>จองห้องประชุม</span>
                </a>
            </li>
            <li>
                <a href="dashboard.php?view=results" class="<?php echo ($_GET['view'] ?? '') == 'results' ? 'active' : ''; ?>">
                    <i class="fas fa-clipboard-check"></i>
                    <span>สถานะการจอง</span>
                </a>
            </li>
            <li>
                <a href="dashboard.php?view=reports" class="<?php echo ($_GET['view'] ?? '') == 'reports' ? 'active' : ''; ?>">
                    <i class="fas fa-chart-line"></i>
                    <span>รายงานการใช้ห้องประชุม</span>
                </a>
            </li>
        </ul>
    </div>

    <?php if (($user['role'] ?? 'user') == 'admin'): ?>
    <div class="nav-section">
        <div class="nav-label">ผู้ดูแลระบบ</div>
        <ul class="nav-links">
            <li>
                <a href="dashboard.php?view=requests" class="<?php echo ($_GET['view'] ?? '') == 'requests' ? 'active' : ''; ?>">
                    <i class="fas fa-list-ul"></i>
                    <span>รายการขอใช้</span>
                </a>
            </li>
            <li>
                <a href="dashboard.php?view=approve_list" class="<?php echo ($_GET['view'] ?? '') == 'approve_list' ? 'active' : ''; ?>">
                    <i class="fas fa-user-check"></i>
                    <span>รายการอนุมัติ</span>
                </a>
            </li>
            <li>
                <a href="dashboard.php?view=external" class="<?php echo ($_GET['view'] ?? '') == 'external' ? 'active' : ''; ?>">
                    <i class="fas fa-file-signature"></i>
                    <span>บันทึกประชุมภายนอก</span>
                </a>
            </li>
            <li>
                <a href="dashboard.php?view=rooms" class="<?php echo ($_GET['view'] ?? '') == 'rooms' ? 'active' : ''; ?>">
                    <i class="fas fa-door-open"></i>
                    <span>ข้อมูลห้องประชุม</span>
                </a>
            </li>
        </ul>
    </div>
    <?php endif; ?>

    <div class="sidebar-footer">
        <div class="user-card">
            <?php 
            $photo_url = "https://192.168.9.7/auth_files/photo/" . $user['emp_code'] . ".jpg";
            ?>
            <img src="<?php echo $photo_url; ?>" onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($user['first_name']); ?>&background=4f46e5&color=fff'" alt="Avatar" class="user-avatar">
            <div class="user-info">
                <h4><?php echo htmlspecialchars($user['first_name'] . ' ' . ($user['last_name'] ?? '')); ?></h4>
                <p><?php echo htmlspecialchars($user['position_name'] ?? 'บุคลากร'); ?></p>
            </div>
            <a href="api/logout.php" title="ออกจากระบบ" style="margin-left: auto; color: var(--danger);"><i class="fas fa-sign-out-alt"></i></a>
        </div>
    </div>
</aside>
