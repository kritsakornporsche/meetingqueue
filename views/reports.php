<?php
require_once 'api/config.php';

// Security Check
if (!isset($_SESSION['user_id'])) {
    exit('Unauthorized');
}

try {
    $pdo = getLocalDB();
    
    // Filter parameters
    $month = $_GET['month'] ?? date('m');
    $year = $_GET['year'] ?? date('Y');
    
    // Query all bookings for the selected month
    $sql = "SELECT b.id, b.start_time, b.end_time, b.title, b.participants_count, b.department_name,
            r.name as room_name, r.capacity, r.location,
            u.first_name, u.last_name, u.dept_name as user_dept
            FROM bookings b
            LEFT JOIN rooms r ON b.room_id = r.id
            LEFT JOIN users u ON b.user_id = u.id
            WHERE MONTH(b.start_time) = :month 
            AND YEAR(b.start_time) = :year 
            AND b.status != 'cancelled'
            ORDER BY b.start_time ASC";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':month' => $month, ':year' => $year]);
    $report_data = $stmt->fetchAll();
    
    // Thai months mapping
    $thai_months = [
        '01' => 'มกราคม', '02' => 'กุมภาพันธ์', '03' => 'มีนาคม', '04' => 'เมษายน',
        '05' => 'พฤษภาคม', '06' => 'มิถุนายน', '07' => 'กรกฎาคม', '08' => 'สิงหาคม',
        '09' => 'กันยายน', '10' => 'ตุลาคม', '11' => 'พฤศจิกายน', '12' => 'ธันวาคม'
    ];
    $thai_months_short = [
        '01' => 'ม.ค.', '02' => 'ก.พ.', '03' => 'มี.ค.', '04' => 'เม.ย.',
        '05' => 'พ.ค.', '06' => 'มิ.ย.', '07' => 'ก.ค.', '08' => 'ส.ค.',
        '09' => 'ก.ย.', '10' => 'ต.ค.', '11' => 'พ.ย.', '12' => 'ธ.ค.'
    ];
    
    $month_name = $thai_months[sprintf("%02d", $month)];
    $month_short = $thai_months_short[sprintf("%02d", $month)];
    $thai_year = $year + 543;
    $thai_year_short = substr($thai_year, 2, 2);
    
    // Calculate last day of month
    $last_day = date('t', strtotime("$year-$month-01"));
    
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<style>
    /* Report Styling */
    .report-card {
        background: white;
        border-radius: 1.5rem;
        padding: 2rem;
        border: 1px solid rgba(212, 181, 157, 0.3);
        box-shadow: 0 4px 15px rgba(106, 82, 67, 0.05);
    }
    .report-header {
        text-align: center;
        margin-bottom: 2rem;
    }
    .report-title {
        font-size: 1.25rem;
        font-weight: bold;
        color: #000;
        margin-bottom: 0.25rem;
    }
    .report-subtitle {
        color: #000;
        font-size: 1rem;
        font-weight: bold;
    }
    
    .report-table-wrapper {
        overflow-x: auto;
    }
    .report-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        font-size: 0.85rem;
    }
    .report-table th, .report-table td {
        border: 1px solid #000;
        padding: 0.5rem;
        color: #000;
    }
    .report-table th {
        text-align: center;
        font-weight: bold;
        background-color: #f8f9fa;
    }
    .report-table tr:hover td {
        background-color: #f9fafb;
    }
    .report-table tfoot th {
        background-color: #EBE6DA;
        color: #6A5243;
        padding: 1rem 1.5rem;
        font-weight: 700;
        border-top: 2px solid #D4B59D;
    }
    
    /* PDF Print Styles */
    @media print {
        @page {
            size: A4 landscape;
            margin: 1cm;
        }
        body {
            background: white !important;
        }
        aside, header, .no-print {
            display: none !important;
        }
        main, .app-container, .content-wrapper {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            max-width: none !important;
        }
        .report-card {
            border: none !important;
            box-shadow: none !important;
            padding: 0 !important;
            width: 100% !important;
        }
        .report-table {
            width: 100% !important;
        }
        .report-table th {
            background-color: #f3f4f6 !important;
            -webkit-print-color-adjust: exact;
            color: black !important;
        }
        .report-table tfoot th {
            background-color: #e5e7eb !important;
            -webkit-print-color-adjust: exact;
            color: black !important;
        }
        .report-title, .report-subtitle, .report-table td {
            color: black !important;
        }
    }
</style>

<div class="flex flex-col gap-6 w-full">
    
    <!-- Controls (No Print) -->
    <div class="flex flex-wrap justify-between items-center gap-4 no-print">
        <h2 class="text-2xl font-bold text-[#6A5243] flex items-center gap-2">
            <i class="fas fa-chart-line text-[#D4B59D]"></i> ระบบรายงาน
        </h2>
        
        <form method="GET" action="dashboard.php" class="flex gap-3">
            <input type="hidden" name="view" value="reports">
            <select name="month" class="px-4 py-2 rounded-xl border border-[#D4B59D]/30 focus:outline-none focus:border-[#D4B59D] bg-white text-[#6A5243] shadow-sm">
                <?php foreach($thai_months as $num => $name): ?>
                    <option value="<?= $num ?>" <?= ($month == $num) ? 'selected' : '' ?>><?= $name ?></option>
                <?php endforeach; ?>
            </select>
            <select name="year" class="px-4 py-2 rounded-xl border border-[#D4B59D]/30 focus:outline-none focus:border-[#D4B59D] bg-white text-[#6A5243] shadow-sm">
                <?php for($y = date('Y')-2; $y <= date('Y')+1; $y++): ?>
                    <option value="<?= $y ?>" <?= ($year == $y) ? 'selected' : '' ?>><?= $y + 543 ?></option>
                <?php endfor; ?>
            </select>
            <button type="submit" class="px-5 py-2 rounded-xl bg-white border border-[#D4B59D]/50 text-[#6A5243] font-bold shadow-sm hover:bg-[#FDFBF7] transition-all">
                ดูรายงาน
            </button>
            <button type="button" onclick="window.print()" class="px-5 py-2 rounded-xl bg-gradient-to-r from-[#D4B59D] to-[#6A5243] text-white font-bold shadow-md hover:shadow-lg transition-all flex items-center gap-2">
                <i class="fas fa-file-pdf"></i> ออกรายงาน PDF
            </button>
        </form>
    </div>

    <!-- Printable Report Container -->
    <div id="printable-report" class="report-card">
        <div class="report-header">
            <h1 class="report-title">รายงานการใช้ห้องประชุม</h1>
            <p class="report-subtitle">ช่วงเวลาตั้งแต่วันที่ 1 <?= $month_short ?> <?= $thai_year_short ?> ถึงวันที่ <?= $last_day ?> <?= $month_short ?> <?= $thai_year_short ?></p>
        </div>
        
        <div class="report-table-wrapper">
            <table class="report-table">
                <thead>
                    <tr>
                        <th style="width: 40px;">#</th>
                        <th style="width: 80px;">วันที่ใช้ห้อง</th>
                        <th style="width: 120px;">ช่วงเวลา</th>
                        <th style="width: 200px;">ห้องประชุม</th>
                        <th>กิจกรรม</th>
                        <th style="width: 60px;">จำนวน<br>(คน)</th>
                        <th style="width: 150px;">หน่วยงาน</th>
                        <th style="width: 100px;">ผู้จอง</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($report_data) > 0): ?>
                        <?php foreach($report_data as $index => $row): 
                            $date = date('j', strtotime($row['start_time'])) . ' ' . $month_short . ' ' . $thai_year_short;
                            $time_range = date('H:i', strtotime($row['start_time'])) . '-' . date('H:i', strtotime($row['end_time'])) . ' น.';
                            $room = $row['room_name'] ?? 'ภายนอกสถานที่';
                            if ($row['location']) {
                                $room .= '<br><span style="font-size: 0.75rem;">(' . htmlspecialchars($row['location']) . ')</span>';
                            }
                            $dept = $row['department_name'] ?: $row['user_dept'];
                            $user_name = $row['first_name'] . ' ' . $row['last_name'];
                        ?>
                        <tr>
                            <td style="text-align: center;"><?= $index + 1 ?></td>
                            <td style="text-align: center; white-space: nowrap;"><?= $date ?></td>
                            <td style="text-align: center; white-space: nowrap;"><?= $time_range ?></td>
                            <td><?= $room ?></td>
                            <td><?= htmlspecialchars($row['title']) ?></td>
                            <td style="text-align: center;"><?= $row['participants_count'] ?></td>
                            <td style="text-align: center;"><?= htmlspecialchars($dept ?? '-') ?></td>
                            <td style="text-align: center;"><?= htmlspecialchars($user_name) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 2rem;">ไม่พบข้อมูลการใช้งานในเดือนนี้</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div style="text-align: right; margin-top: 1rem; font-size: 0.75rem;">
            <?= date('d') ?> <?= $thai_months[date('m')] ?> <?= date('Y') + 543 ?>
        </div>
    </div>
</div>
