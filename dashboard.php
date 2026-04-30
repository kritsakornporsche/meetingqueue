<?php
require_once 'api/config.php';

// Security Check: If not logged in, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$user = $_SESSION['user_data'];
$view = $_GET['view'] ?? 'calendar';
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบจองห้องประชุม รพ.พาน</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- FullCalendar CSS -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />
    
    <!-- Choices.js CSS for beautiful dropdowns -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Tailwind CSS v4 -->
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <style type="text/tailwindcss">
        @theme {
            --font-sans: 'Outfit', 'Sarabun', sans-serif;
            --color-primary: #6A5243;
            --color-secondary: #D4B59D;
            --color-accent: #E6D6BD;
            --color-background: #EBE6DA;
            --color-surface: #F3F0E6;
            --color-text-main: #6A5243;
            --color-text-muted: #A79A8B;
            --color-border: #D4B59D;
        }
    </style>
</head>
<body>
    <div class="app-container">
        <!-- Sidebar Overlay for Mobile -->
        <div id="sidebarOverlay" class="sidebar-overlay"></div>
        
        <?php include 'includes/sidebar.php'; ?>

        <main>
            <?php include 'includes/header.php'; ?>

            <div class="content-wrapper animate-fade">
                <?php
                switch ($view) {
                    case 'requests':
                    case 'results':
                    case 'approve_list':
                        include 'views/approve_list.php';
                        break;
                    case 'book':
                        include 'views/book_room.php';
                        break;
                    case 'external':
                        include 'views/external_meeting.php';
                        break;
                    case 'reports':
                        include 'views/reports.php';
                        break;
                    case 'history':
                        include 'views/history.php';
                        break;
                    case 'rooms':
                        include 'views/rooms_management.php';
                        break;
                    case 'statistics':
                        include 'views/statistics.php';
                        break;
                    case 'booking_result':
                        include 'views/booking_result.php';
                        break;
                    case 'calendar':
                    default:
                        include 'views/calendar_view.php';
                        break;
                }
                ?>
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <script src="js/app.js"></script>
</body>
</html>
