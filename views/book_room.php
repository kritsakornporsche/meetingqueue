<style>
    /* Premium Animations & Transitions */
    .step-container { display: none; animation: slideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1); }
    .step-container.active { display: block; }
    @keyframes slideUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    
    /* 2-Step Progress Bar */
    .progress-bar-premium { display: flex; justify-content: center; gap: 6rem; margin-bottom: 2rem; position: relative; max-width: 360px; margin-left: auto; margin-right: auto; }
    .progress-bar-premium::before { content: ''; position: absolute; top: 19px; left: 15%; width: 70%; height: 2px; background: #EBE6DA; z-index: 1; border-radius: 10px; }
    .progress-step-premium { width: 40px; height: 40px; border-radius: 14px; background: white; border: 2px solid #EBE6DA; display: flex; align-items: center; justify-content: center; z-index: 2; position: relative; transition: all 0.35s cubic-bezier(0.34, 1.56, 0.64, 1); font-weight: 800; color: #A79A8B; font-size: 0.95rem; }
    .progress-step-premium.active { border-color: #6A5243; background: #6A5243; color: white; transform: scale(1.08); box-shadow: 0 6px 16px rgba(106, 82, 67, 0.18); }
    .progress-step-premium.completed { border-color: #D4B59D; background: #D4B59D; color: white; }
    .progress-label-premium { position: absolute; top: 46px; font-size: 0.68rem; font-weight: 700; color: #A79A8B; white-space: nowrap; left: 50%; transform: translateX(-50%); letter-spacing: 0.02em; }
    .progress-step-premium.active .progress-label-premium { color: #6A5243; }

    .room-card-premium { border: 1.5px solid #EBE6DA; border-radius: 1rem; padding: 0.85rem 1rem; cursor: pointer; transition: all 0.25s; background: white; min-height: 90px; display: flex; flex-direction: column; justify-content: space-between; }
    .room-card-premium:hover { border-color: #D4B59D; transform: translateY(-2px); box-shadow: 0 8px 20px rgba(106,82,67,0.06); }
    .room-card-premium.active { border-color: #6A5243; background: #FDFBF7; box-shadow: 0 0 0 2px #6A5243; }
    .room-card-premium.active h4 { color: #6A5243; }

    .quick-btn-premium { border: 1.5px solid #EBE6DA; padding: 0.4rem 0.9rem; border-radius: 0.75rem; font-size: 0.8rem; font-weight: 600; transition: all 0.2s; background: white; color: #6A5243; }
    .quick-btn-premium:hover { border-color: #D4B59D; }
    .quick-btn-premium.active { border-color: #6A5243; background: #6A5243; color: white; }

    .premium-input { width: 100%; padding: 0.75rem 1rem; border-radius: 0.875rem; background: #F9F8F6; border: none; outline: none; transition: all 0.25s; color: #2D241E; font-weight: 500; font-size: 0.9rem; font-family: inherit; }
    .premium-input:focus { background: white; box-shadow: 0 0 0 2px rgba(212,181,157,0.5); }

    .label-premium { font-size: 0.85rem; font-weight: 700; color: #4A3A2F; margin-bottom: 0.65rem; display: flex; align-items: center; gap: 0.5rem; }
    .label-premium i { color: #D4B59D; font-size: 0.9rem; }

    /* Equipment Icon Cards */
    .equip-grid { display: flex; flex-wrap: wrap; gap: 1rem 0.75rem; padding-bottom: 0.5rem; }
    .equip-card { display: flex; flex-direction: column; align-items: center; gap: 0.35rem; padding: 0.75rem 1rem 0.65rem; border-radius: 0.875rem; background: #F9F8F6; border: 1.5px solid transparent; cursor: pointer; transition: all 0.2s; min-width: 72px; user-select: none; margin-bottom: 0.35rem; }
    .equip-card:hover { background: #F3EFE8; border-color: #D4B59D; }
    .equip-card.active { background: #6A5243; border-color: #6A5243; position: relative; }
    .equip-card.active i, .equip-card.active span { color: white !important; }
    .equip-card.active::after {
        content: '\2713';
        position: absolute;
        bottom: -9px;
        left: 50%;
        transform: translateX(-50%);
        background: #22c55e;
        color: white;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        font-size: 0.65rem;
        font-weight: 900;
        line-height: 18px;
        text-align: center;
        box-shadow: 0 2px 6px rgba(34,197,94,0.4);
        border: 2px solid white;
        z-index: 2;
    }
    .equip-card i { font-size: 1.1rem; color: #A79A8B; transition: color 0.2s; }
    .equip-card span { font-size: 0.7rem; font-weight: 700; color: #6A5243; white-space: nowrap; }
    .equip-other-input { margin-top: 0.5rem; width: 100%; padding: 0.55rem 0.8rem; border-radius: 0.75rem; background: #F9F8F6; border: none; font-size: 0.82rem; font-family: inherit; color: #2D241E; display: none; }
    .equip-other-input:focus { outline: none; box-shadow: 0 0 0 2px rgba(212,181,157,0.5); background: white; }

    /* Summary panel */
    .summary-panel { background: #6A5243; border-radius: 1.25rem; padding: 1.25rem; color: white; }
    .summary-row { margin-bottom: 0.85rem; }
    .summary-row:last-child { margin-bottom: 0; }
    .summary-label { font-size: 0.62rem; font-weight: 700; opacity: 0.55; text-transform: uppercase; letter-spacing: 0.12em; margin-bottom: 0.2rem; }
    .summary-value { font-size: 0.88rem; font-weight: 700; color: #F3DFC8; word-break: break-word; overflow-wrap: anywhere; line-height: 1.4; }

    /* Loading Overlay */
    .loading-overlay { position: fixed; inset: 0; background: rgba(235, 230, 218, 0.9); backdrop-filter: blur(10px); z-index: 9999; display: none; flex-direction: column; align-items: center; justify-content: center; }
    .loading-overlay.active { display: flex; animation: fadeIn 0.4s ease-out; }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

    /* ── Room Search Box ── */
    .room-search-box { margin-bottom: 0.75rem; }
    .room-search-input-wrap {
        position: relative; display: flex; align-items: center;
        background: #F9F8F6; border-radius: 0.875rem; border: 1.5px solid #EBE6DA;
        transition: all 0.25s;
    }
    .room-search-input-wrap:focus-within {
        background: white; border-color: #D4B59D;
        box-shadow: 0 0 0 3px rgba(212,181,157,0.18);
    }
    .room-search-icon { position: absolute; left: 0.85rem; color: #A79A8B; font-size: 0.85rem; pointer-events: none; }
    .room-search-input {
        width: 100%; padding: 0.7rem 2.5rem 0.7rem 2.5rem; border: none; background: transparent;
        font-size: 0.88rem; font-weight: 600; color: #2D241E; font-family: inherit; outline: none;
    }
    .room-search-input::placeholder { color: #C9BCB0; font-weight: 500; }
    .room-search-clear {
        position: absolute; right: 0.6rem; width: 1.6rem; height: 1.6rem;
        border-radius: 50%; border: none; background: #EBE6DA; color: #A79A8B;
        cursor: pointer; display: flex; align-items: center; justify-content: center;
        font-size: 0.65rem; transition: all 0.2s;
    }
    .room-search-clear:hover { background: #D4B59D; color: white; }

    /* ── Keyword Pills ── */
    .room-keywords-wrap {
        display: flex; flex-wrap: wrap; gap: 0.4rem; margin-top: 0.6rem;
        max-height: 72px; overflow-y: auto;
        scrollbar-width: thin; scrollbar-color: rgba(212,181,157,0.35) transparent;
    }
    .room-keywords-wrap::-webkit-scrollbar { height: 3px; width: 3px; }
    .room-keywords-wrap::-webkit-scrollbar-thumb { background: rgba(212,181,157,0.5); border-radius: 10px; }
    .room-keyword-pill {
        padding: 0.25rem 0.65rem; border-radius: 9999px; font-size: 0.68rem; font-weight: 700;
        background: #F3EFE8; color: #6A5243; border: 1px solid #EBE6DA;
        cursor: pointer; transition: all 0.2s; white-space: nowrap; user-select: none;
    }
    .room-keyword-pill:hover { background: #EBE6DA; border-color: #D4B59D; }
    .room-keyword-pill.active { background: #6A5243; color: white; border-color: #6A5243; }

    /* ── Scrollable Room Grid ── */
    .room-grid-scroll {
        max-height: 420px; overflow-y: auto; overflow-x: hidden;
        padding-right: 0.25rem;
        scrollbar-width: thin; scrollbar-color: rgba(212,181,157,0.4) transparent;
    }
    .room-grid-scroll::-webkit-scrollbar { width: 5px; }
    .room-grid-scroll::-webkit-scrollbar-track { background: transparent; }
    .room-grid-scroll::-webkit-scrollbar-thumb { background: rgba(212,181,157,0.5); border-radius: 10px; }
    .room-grid-scroll::-webkit-scrollbar-thumb:hover { background: rgba(212,181,157,0.8); }

    /* No Result */
    .room-no-result {
        text-align: center; padding: 2rem 1rem; color: #A79A8B;
    }
    .room-no-result i { font-size: 1.5rem; margin-bottom: 0.5rem; opacity: 0.4; }
    .room-no-result p { font-size: 0.85rem; font-weight: 600; }

    /* ── Searchable Department Dropdown ── */
    .dept-dropdown-wrap {
        position: relative;
    }
    .dept-input-wrap {
        position: relative;
        display: flex;
        align-items: center;
        background: #F9F8F6;
        border-radius: 0.875rem;
        border: 1.5px solid #EBE6DA;
        transition: all 0.25s;
    }
    .dept-input-wrap:focus-within {
        background: white;
        border-color: #D4B59D;
        box-shadow: 0 0 0 3px rgba(212,181,157,0.18);
    }
    .dept-input-wrap.has-value {
        border-color: #6A5243;
        background: white;
    }
    .dept-icon {
        position: absolute;
        left: 0.9rem;
        color: #A79A8B;
        font-size: 0.85rem;
        pointer-events: none;
        z-index: 1;
    }
    .dept-search-input {
        width: 100%;
        padding: 0.75rem 2.5rem 0.75rem 2.5rem;
        border: none;
        background: transparent;
        font-size: 0.9rem;
        font-weight: 600;
        color: #2D241E;
        font-family: inherit;
        outline: none;
        cursor: pointer;
    }
    .dept-search-input::placeholder { color: #C9BCB0; font-weight: 500; }
    .dept-clear-btn {
        position: absolute;
        right: 0.6rem;
        width: 1.6rem; height: 1.6rem;
        border-radius: 50%;
        border: none;
        background: #EBE6DA;
        color: #A79A8B;
        cursor: pointer;
        display: none;
        align-items: center;
        justify-content: center;
        font-size: 0.65rem;
        transition: all 0.2s;
    }
    .dept-clear-btn:hover { background: #D4B59D; color: white; }
    .dept-clear-btn.visible { display: flex; }

    .dept-dropdown-list {
        position: absolute;
        top: calc(100% + 6px);
        left: 0; right: 0;
        background: white;
        border: 1.5px solid #D4B59D;
        border-radius: 0.875rem;
        box-shadow: 0 12px 32px rgba(106,82,67,0.12);
        max-height: 240px;
        overflow-y: auto;
        z-index: 200;
        display: none;
        scrollbar-width: thin;
        scrollbar-color: rgba(212,181,157,0.4) transparent;
    }
    .dept-dropdown-list.open { display: block; }
    .dept-dropdown-list::-webkit-scrollbar { width: 5px; }
    .dept-dropdown-list::-webkit-scrollbar-thumb { background: rgba(212,181,157,0.5); border-radius: 10px; }

    .dept-option {
        padding: 0.65rem 1rem;
        font-size: 0.88rem;
        font-weight: 600;
        color: #4A3A2F;
        cursor: pointer;
        transition: background 0.15s;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .dept-option:first-child { border-radius: 0.7rem 0.7rem 0 0; }
    .dept-option:last-child { border-radius: 0 0 0.7rem 0.7rem; }
    .dept-option:hover { background: #F3EFE8; color: #6A5243; }
    .dept-option.selected { background: rgba(106,82,67,0.06); color: #6A5243; }
    .dept-option .dept-match { color: #6A5243; font-weight: 800; }
    .dept-no-result {
        padding: 1rem;
        text-align: center;
        font-size: 0.82rem;
        color: #A79A8B;
        font-weight: 600;
    }
</style>

<!-- Viewer.js CSS for Image Zoom/Pan -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.3/viewer.min.css" />

<?php $is_admin = ($_SESSION['user_data']['role'] ?? '') === 'admin'; ?>

<div id="bookingLoading" class="loading-overlay">
    <div class="w-24 h-24 rounded-full bg-white shadow-2xl flex items-center justify-center mb-6 relative">
        <div class="absolute inset-0 rounded-full border-4 border-[#D4B59D]/20"></div>
        <div class="absolute inset-0 rounded-full border-4 border-[#6A5243] border-t-transparent animate-spin"></div>
        <i class="fas fa-paper-plane text-2xl text-[#6A5243]"></i>
    </div>
    <h3 class="text-2xl font-black text-[#6A5243] mb-2">กำลังส่งข้อมูล...</h3>
    <p class="text-[#A79A8B] font-bold">กรุณารอสักครู่ ระบบกำลังประมวลผลคำขอของคุณ</p>
</div>

<div class="max-w-[1000px] mx-auto py-6 px-4 md:px-8">
    <!-- Header Section -->
    <div class="text-center mb-6">
        <div class="inline-flex items-center gap-3 px-4 py-2 rounded-full bg-[#6A5243]/5 text-[#6A5243] text-[0.75rem] font-bold uppercase tracking-[0.15em] mb-4 border border-[#6A5243]/10">
            <i class="fas fa-bolt text-[#D4B59D]"></i> Instant Booking System
        </div>
        <h2 class="text-2xl md:text-3xl font-bold text-[#6A5243] mb-3 tracking-tight">แบบฟอร์มการจอง</h2>
        <p class="text-sm text-[#A79A8B] font-medium max-w-xl mx-auto leading-relaxed opacity-80">กรุณาเลือกรายละเอียดตามขั้นตอนด้านล่าง เพื่อความรวดเร็วในการพิจารณาอนุมัติ</p>
    </div>

    <!-- Progress Indicator (2 steps) -->
    <div class="progress-bar-premium mb-10">
        <div class="progress-step-premium active" id="pstep-1">
            <i class="fas fa-calendar-check"></i>
            <span class="progress-label-premium">1. เลือกห้อง & วันเวลา</span>
        </div>
        <div class="progress-step-premium" id="pstep-2">
            <i class="fas fa-user-pen"></i>
            <span class="progress-label-premium">2. สรุปการจอง</span>
        </div>
    </div>

    <!-- Main Form Card -->
    <div class="bg-white rounded-[1.5rem] shadow-[0_10px_40px_rgba(106,82,67,0.07)] border border-[#EBE6DA]" style="padding: 1.5rem !important;">
        <form id="bookingForm" enctype="multipart/form-data">

            <!-- ═══ STEP 1: Room + Title + Date/Time + Participants + Equipment ═══ -->
            <div class="step-container active" id="step-1">
                <div style="display:flex; flex-direction:column; gap:1.25rem;">

                    <!-- Room selection -->
                    <div>
                        <label class="label-premium"><i class="fas fa-building"></i> เลือกห้องประชุม <span class="text-red-500">*</span></label>
                        
                        <!-- Search Box -->
                        <div class="room-search-box">
                            <div class="room-search-input-wrap">
                                <i class="fas fa-search room-search-icon"></i>
                                <input type="text" id="roomSearchBooking" class="room-search-input" placeholder="ค้นหาห้องประชุม..." autocomplete="off">
                                <button type="button" id="roomSearchClear" class="room-search-clear hidden" title="ล้างการค้นหา"><i class="fas fa-times"></i></button>
                            </div>
                            <!-- Keyword Pills -->
                            <div id="roomKeywords" class="room-keywords-wrap"><!-- generated by JS --></div>
                        </div>

                        <!-- Scrollable Room Grid -->
                        <div id="room-grid-scroll" class="room-grid-scroll">
                            <div id="room-grid" class="grid grid-cols-1 md:grid-cols-2 gap-3"><!-- loaded by JS --></div>
                            <div id="room-no-result" class="room-no-result hidden">
                                <i class="fas fa-search"></i>
                                <p>ไม่พบห้องประชุมที่ค้นหา</p>
                            </div>
                        </div>
                        <input type="hidden" id="room_id" required>
                    </div>

                    <!-- Title -->
                    <div>
                        <label class="label-premium"><i class="fas fa-quote-left"></i> หัวข้อการประชุมหรือกิจกรรม <span class="text-red-500">*</span></label>
                        <input type="text" id="title" class="premium-input" placeholder="ตัวอย่าง: ประชุมติดตามงานประจำสัปดาห์..." required>
                    </div>

                    <!-- Date + Time side by side -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="label-premium"><i class="fas fa-calendar-alt"></i> วันที่จัดประชุม <span class="text-red-500">*</span></label>
                            <input type="date" id="meeting_date" class="premium-input font-bold" required>
                            <p class="text-[0.68rem] text-[#A79A8B] mt-1.5 italic">* เฉพาะวันจันทร์ - ศุกร์ ยกเว้นวันหยุดนักขัตฤกษ์</p>
                        </div>
                        <div class="bg-[#F9F8F6] rounded-xl p-4">
                            <label class="label-premium"><i class="fas fa-clock"></i> ช่วงเวลาที่ต้องการ</label>
                            <div class="grid grid-cols-2 gap-3 mb-3">
                                <div>
                                    <span class="text-[0.62rem] font-bold text-[#A79A8B] uppercase tracking-widest block mb-1">เริ่มเวลา</span>
                                    <input type="time" id="start_time" value="08:30" class="premium-input text-center font-bold">
                                </div>
                                <div>
                                    <span class="text-[0.62rem] font-bold text-[#A79A8B] uppercase tracking-widest block mb-1">สิ้นสุดเวลา</span>
                                    <input type="time" id="end_time" value="16:30" class="premium-input text-center font-bold">
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button type="button" onclick="setQuickTimePremium('08:30','12:00',this)" class="quick-btn-premium">ช่วงเช้า</button>
                                <button type="button" onclick="setQuickTimePremium('13:00','16:30',this)" class="quick-btn-premium">ช่วงบ่าย</button>
                                <button type="button" onclick="setQuickTimePremium('08:30','16:30',this)" class="quick-btn-premium">ทั้งวัน</button>
                            </div>
                        </div>
                    </div>

                    <!-- Participants -->
                    <div>
                        <label class="label-premium"><i class="fas fa-users"></i> จำนวนผู้เข้าประชุม <span class="text-red-500">*</span></label>
                        <div class="flex items-center gap-3" style="max-width:220px;">
                            <button type="button" onclick="adjustValue(-5)" class="w-10 h-10 rounded-xl bg-[#F9F8F6] flex items-center justify-center text-[#6A5243] hover:bg-[#EBE6DA] transition-all flex-shrink-0"><i class="fas fa-minus text-sm"></i></button>
                            <input type="number" id="participants_count" value="10" class="premium-input text-center font-black text-lg" required>
                            <button type="button" onclick="adjustValue(5)" class="w-10 h-10 rounded-xl bg-[#F9F8F6] flex items-center justify-center text-[#6A5243] hover:bg-[#EBE6DA] transition-all flex-shrink-0"><i class="fas fa-plus text-sm"></i></button>
                        </div>
                    </div>

                    <!-- Equipment icon cards -->
                    <div>
                        <label class="label-premium"><i class="fas fa-tools"></i> ตัวเลือกอุปกรณ์ (Optional)</label>
                        <div class="equip-grid">
                            <div class="equip-card" onclick="toggleEquip(this,'โปรเจกเตอร์')">
                                <i class="fas fa-video"></i><span>โปรเจกเตอร์</span>
                            </div>
                            <div class="equip-card" onclick="toggleEquip(this,'ทีวี')">
                                <i class="fas fa-tv"></i><span>ทีวี</span>
                            </div>
                            <div class="equip-card" onclick="toggleEquip(this,'คอมพิวเตอร์')">
                                <i class="fas fa-desktop"></i><span>คอมพิวเตอร์</span>
                            </div>
                            <div class="equip-card" onclick="toggleEquip(this,'ไมโครโฟน')">
                                <i class="fas fa-microphone"></i><span>ไมโครโฟน</span>
                            </div>
                            <div class="equip-card" onclick="toggleEquipOther(this)" id="equipOtherCard">
                                <i class="fas fa-ellipsis-h"></i><span>อื่นๆ</span>
                            </div>
                        </div>
                        <input type="text" id="equipOtherText" class="equip-other-input" placeholder="ระบุอุปกรณ์ที่ต้องการ...">
                        <input type="hidden" id="equipments_hidden">
                    </div>

                    <!-- Department / Unit Searchable Dropdown -->
                    <div>
                        <label class="label-premium"><i class="fas fa-sitemap"></i> หน่วยงาน/ฝ่ายที่สังกัด <span class="text-red-500">*</span></label>
                        <div class="dept-dropdown-wrap" id="deptDropdownWrap">
                            <div class="dept-input-wrap" id="deptInputWrap">
                                <i class="fas fa-building dept-icon"></i>
                                <input
                                    type="text"
                                    id="deptSearchInput"
                                    class="dept-search-input"
                                    placeholder="พิมพ์เพื่อค้นหาหน่วยงาน..."
                                    autocomplete="off"
                                    aria-label="ค้นหาหน่วยงาน"
                                >
                                <button type="button" id="deptClearBtn" class="dept-clear-btn" title="ล้าง"><i class="fas fa-times"></i></button>
                            </div>
                            <div class="dept-dropdown-list" id="deptDropdownList" role="listbox"></div>
                        </div>
                        <input type="hidden" id="department_name" required>
                        <p class="text-[0.68rem] text-[#A79A8B] mt-1.5 italic">* หน่วยงานที่จะแสดงในใบจองห้องประชุม</p>
                    </div>

                </div>
            </div>

            <!-- ═══ STEP 2: Contact + File + Summary ═══ -->
            <div class="step-container" id="step-2">
                <div class="grid grid-cols-1 lg:grid-cols-5 gap-5">

                    <!-- Left: Contact & File -->
                    <div class="lg:col-span-3" style="display:flex; flex-direction:column; gap:1rem;">
                        <div>
                            <label class="label-premium"><i class="fas fa-phone"></i> เบอร์โทรศัพท์สำหรับติดต่อกลับ <span class="text-red-500">*</span></label>
                            <input type="text" id="phone" class="premium-input" placeholder="ตัวอย่าง: 081-234-5678" required>
                        </div>
                        <div>
                            <label class="label-premium"><i class="fas fa-clipboard-list"></i> หมายเหตุ (ความต้องการพิเศษ)</label>
                            <textarea id="description" rows="3" class="premium-input" style="resize:vertical;" placeholder="ระบุสิ่งที่ต้องการให้เจ้าหน้าที่เตรียม..."></textarea>
                        </div>
                        <div>
                            <label class="label-premium"><i class="fas fa-paperclip"></i> เอกสารแนบ (ประกาศ/กำหนดการ)</label>
                            <label for="attachment" class="block w-full cursor-pointer">
                                <div class="flex flex-col items-center justify-center w-full py-6 border-2 border-dashed border-[#D4B59D]/40 rounded-xl hover:bg-[#FDFBF7] hover:border-[#D4B59D] transition-all text-center">
                                    <div class="w-10 h-10 mx-auto rounded-full bg-[#D4B59D]/10 flex items-center justify-center mb-2">
                                        <i class="fas fa-upload text-[#D4B59D]"></i>
                                    </div>
                                    <p class="text-[#6A5243] font-bold text-sm w-full text-center">คลิกเพื่ออัปโหลดไฟล์</p>
                                    <p class="text-[0.62rem] text-[#A79A8B] font-bold mt-1 uppercase tracking-widest w-full text-center">Max 10MB · PDF, JPG, PNG</p>
                                    <input id="attachment" type="file" class="hidden">
                                </div>
                            </label>
                            <div id="file-status" class="hidden mt-2 px-3 py-2 bg-[#6A5243]/5 rounded-xl flex items-center justify-between border border-[#6A5243]/10">
                                <div class="flex items-center gap-2 min-w-0">
                                    <i class="fas fa-file-circle-check text-[#D4B59D] flex-shrink-0"></i>
                                    <span id="file-name" class="text-sm font-bold text-[#6A5243] truncate"></span>
                                </div>
                                <button type="button" onclick="clearFile()" class="text-red-400 hover:text-red-600 flex-shrink-0 ml-2"><i class="fas fa-times-circle"></i></button>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Summary Panel -->
                    <div class="lg:col-span-2">
                        <div class="summary-panel h-full" style="min-height:260px; display:flex; flex-direction:column; justify-content:space-between;">
                            <div>
                                <h3 style="font-size:0.9rem; font-weight:800; margin-bottom:1rem; display:flex; align-items:center; gap:0.5rem;">
                                    <i class="fas fa-check-double" style="color:#D4B59D;"></i> สรุปรายละเอียดการจอง
                                </h3>
                                <div class="summary-row">
                                    <div class="summary-label">ห้องประชุมที่เลือก</div>
                                    <div class="summary-value" id="summary-room">โปรดเลือกห้อง...</div>
                                </div>
                                <div class="summary-row">
                                    <div class="summary-label">หัวข้อการประชุม</div>
                                    <div class="summary-value" id="summary-title">-</div>
                                </div>
                                <div class="summary-row">
                                    <div class="summary-label">วันและเวลา</div>
                                    <div class="summary-value" id="summary-datetime">โปรดระบุ...</div>
                                </div>
                                <div class="summary-row">
                                    <div class="summary-label">จำนวนผู้เข้าร่วม</div>
                                    <div class="summary-value" id="summary-count">10 คน</div>
                                </div>
                                <div class="summary-row" id="summary-equip-row" style="display:none;">
                                    <div class="summary-label">อุปกรณ์</div>
                                    <div class="summary-value" id="summary-equip">-</div>
                                </div>
                            </div>
                            <div style="margin-top:0.85rem; padding:0.65rem 0.85rem; background:rgba(212,181,157,0.15); border-radius:0.75rem; border:1px solid rgba(212,181,157,0.3); font-size:0.68rem; color:#D4B59D; line-height:1.5;">
                                <i class="fas fa-info-circle"></i> ข้อมูลจะถูกส่งให้ผู้ดูแลระบบตรวจสอบ ติดตามสถานะได้ที่ "ผลการอนุมัติ"
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Wizard Controls -->
            <div class="flex justify-between items-center mt-8 pt-6 border-t border-[#F0EDE6]">
                <button type="button" id="prevBtn" onclick="moveStep(-1)" class="px-6 py-3 rounded-xl border-2 border-[#EBE6DA] bg-white text-[#A79A8B] text-sm font-bold hover:border-[#D4B59D] hover:text-[#6A5243] hover:bg-[#FDFBF7] transition-all flex items-center gap-2" style="opacity:0; pointer-events:none;">
                    <i class="fas fa-arrow-left text-xs"></i> ย้อนกลับ
                </button>
                
                <div class="flex gap-3">
                    <button type="button" id="nextBtn" onclick="moveStep(1)" class="px-8 py-3 rounded-xl bg-[#6A5243] text-white text-sm font-bold shadow-sm hover:bg-[#523E32] hover:shadow-md hover:-translate-y-0.5 active:scale-95 transition-all flex items-center justify-center gap-2 min-w-[120px]">
                        ถัดไป <i class="fas fa-chevron-right text-[0.7rem] opacity-80"></i>
                    </button>
                    <button type="submit" id="submitBtn" class="hidden px-8 py-3 rounded-xl bg-[#10b981] text-white text-sm font-bold shadow-sm hover:bg-[#059669] hover:shadow-md hover:-translate-y-0.5 active:scale-95 transition-all flex items-center justify-center gap-2 min-w-[150px]">
                        ยืนยันการส่งข้อมูล <i class="fas fa-check-circle text-[0.7rem] opacity-80"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>




<!-- Admin Room Image Manager Modal -->
<?php if($is_admin): ?>
<div id="imageManageModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[9999] hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-[2rem] w-full max-w-2xl overflow-hidden shadow-2xl flex flex-col max-h-[90vh]">
        <div class="p-6 border-b border-[#EBE6DA] flex justify-between items-center bg-[#FDFBF7]">
            <h3 class="text-xl font-bold text-[#6A5243]"><i class="fas fa-images text-[#D4B59D]"></i> จัดการรูปภาพห้อง <span id="imgModalRoomName"></span></h3>
            <button type="button" onclick="closeImageManageModal()" class="w-10 h-10 rounded-full bg-white border border-[#EBE6DA] text-[#A79A8B] hover:text-red-500 hover:border-red-200 transition-all flex items-center justify-center"><i class="fas fa-times"></i></button>
        </div>
        <div class="p-6 overflow-y-auto flex-grow bg-[#F9F8F6]">
            <!-- Current Images -->
            <div id="currentImagesGrid" class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-8">
                <!-- Images will be rendered here -->
            </div>
            
            <!-- Upload New -->
            <div class="bg-white p-6 rounded-2xl border-2 border-dashed border-[#D4B59D]/40 text-center">
                <h4 class="font-bold text-[#6A5243] mb-4">อัปโหลดรูปภาพใหม่</h4>
                <input type="file" id="newRoomImage" accept="image/*" class="hidden" onchange="uploadRoomImage()">
                <label for="newRoomImage" class="inline-flex items-center gap-2 px-6 py-3 bg-[#6A5243] text-white rounded-xl cursor-pointer hover:bg-[#523E32] transition-colors shadow-md">
                    <i class="fas fa-upload"></i> เลือกรูปภาพ (Max 5MB)
                </label>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Hidden Container for Viewer.js -->
<ul id="roomImagesViewer" class="hidden"></ul>

<script src="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.11.3/viewer.min.js"></script>
<script>
    const isAdmin = <?= json_encode($is_admin) ?>;
    let activeStep = 1;

    document.addEventListener('DOMContentLoaded', () => {
        initRoomSelection();
        initDeptDropdown();
        
        // Set default date to today
        const today = new Date().toLocaleDateString('en-CA'); // Gets YYYY-MM-DD in local timezone safely
        document.getElementById('meeting_date').value = today;
        
        // Attachment UI
        document.getElementById('attachment').addEventListener('change', (e) => {
            if (e.target.files[0]) {
                document.getElementById('file-name').textContent = e.target.files[0].name;
                document.getElementById('file-status').classList.remove('hidden');
                document.getElementById('file-status').classList.add('flex');
            }
        });

        // Summary Real-time Update
        ['meeting_date', 'start_time', 'end_time', 'participants_count'].forEach(id => {
            document.getElementById(id).addEventListener('change', updateSummary);
            document.getElementById(id).addEventListener('input', updateSummary);
        });

        document.getElementById('bookingForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = document.getElementById('submitBtn');
            const original = btn.innerHTML;
            
            try {
                const fd = new FormData();
                fd.append('room_id', document.getElementById('room_id').value);
                fd.append('title', document.getElementById('title').value);
                fd.append('meeting_date', document.getElementById('meeting_date').value);
                fd.append('start_time', document.getElementById('start_time').value);
                fd.append('end_time', document.getElementById('end_time').value);
                fd.append('participants_count', document.getElementById('participants_count').value);
                fd.append('description', document.getElementById('description').value);
                fd.append('phone', document.getElementById('phone').value);
                fd.append('department', document.getElementById('department_name').value);
                
                const file = document.getElementById('attachment').files[0];
                if (file) fd.append('attachment', file);
                
                const eqs = [document.getElementById('equipments_hidden').value].filter(Boolean);
                if (eqs.length > 0) fd.append('equipments', eqs[0]);

                MeetQueue.utils.loading(true, 'กำลังประมวลผลคำขอ...');

                const json = await MeetQueue.api.fetch('api/bookings.php', { method: 'POST', body: fd });
                
                MeetQueue.utils.loading(false);
                
                if (json.success) {
                    await MeetQueue.utils.notify('success', 'ส่งคำขอจองสำเร็จ!', 'เจ้าหน้าที่จะดำเนินการตรวจสอบโดยเร็วที่สุด');
                    window.location.href = 'dashboard.php?view=booking_result&id=' + json.id;
                } else {
                    throw new Error(json.message);
                }
            } catch (err) {
                MeetQueue.utils.loading(false);
                MeetQueue.utils.notify('error', 'ไม่สำเร็จ', err.message);
            }
        });
    });

    let allRoomCards = []; // track cards for search filtering

    async function initRoomSelection() {
        const data = await MeetQueue.api.fetch('api/rooms.php');
        if (!data.success) return;

        const grid = document.getElementById('room-grid');
        grid.innerHTML = '';
        allRoomCards = [];

        // ── Build keyword set from room names ──
        const keywordSet = new Set();
        data.rooms.forEach(room => {
            // Extract meaningful keywords from room name
            const name = room.name;
            // Full room name as keyword
            keywordSet.add(name);
            // Extract capacity text like "30-40 คน", "10-15คน"
            const capMatch = name.match(/\d+[-–]\d+\s*คน/);
            if (capMatch) keywordSet.add(capMatch[0].replace(/\s+/g, ' '));
            // Extract parenthetical descriptions
            const parenMatches = name.match(/\(([^)]+)\)/g);
            if (parenMatches) {
                parenMatches.forEach(m => {
                    const inner = m.replace(/[()]/g, '').trim();
                    if (inner.length > 2 && !/^\d+[-–]\d+\s*คน?$/.test(inner)) keywordSet.add(inner);
                });
            }
            // Extract main name part (before first parenthesis)
            const mainName = name.split('(')[0].trim();
            if (mainName.length > 2) keywordSet.add(mainName);
        });

        // Render keyword pills
        const kwContainer = document.getElementById('roomKeywords');
        kwContainer.innerHTML = '';
        const keywords = [...keywordSet].sort((a, b) => a.length - b.length);
        keywords.forEach(kw => {
            const pill = document.createElement('span');
            pill.className = 'room-keyword-pill';
            pill.textContent = kw;
            pill.onclick = () => {
                const searchInput = document.getElementById('roomSearchBooking');
                const isActive = pill.classList.contains('active');
                // Deactivate all pills
                kwContainer.querySelectorAll('.room-keyword-pill').forEach(p => p.classList.remove('active'));
                if (isActive) {
                    searchInput.value = '';
                } else {
                    pill.classList.add('active');
                    searchInput.value = kw;
                }
                filterRoomCards();
            };
            kwContainer.appendChild(pill);
        });

        // ── Build room cards ──
        data.rooms.forEach(room => {
            const card = document.createElement('div');
            card.className = 'room-card-premium group';
            card.dataset.roomName = room.name.toLowerCase();
            card.dataset.roomCapacity = room.capacity;
            card.onclick = () => {
                document.querySelectorAll('.room-card-premium').forEach(c => c.classList.remove('active'));
                card.classList.add('active');
                document.getElementById('room_id').value = room.id;
                document.getElementById('summary-room').textContent = room.name;
                updateSummary();
            };
            card.innerHTML = `
                <div class="flex items-start gap-6">
                    <div class="w-14 h-14 flex-shrink-0 rounded-2xl bg-[#F9F8F6] group-hover:bg-white border border-[#EBE6DA] flex items-center justify-center text-[#D4B59D] transition-all">
                        ${room.room_number ? `<span class="text-xl font-black">${room.room_number}</span>` : `<i class="fas fa-building text-2xl"></i>`}
                    </div>
                    <div class="flex-grow pt-1">
                        <h4 class="font-black text-[#6A5243] text-[1.05rem] leading-snug mb-2 line-clamp-2">${room.name}</h4>
                        <div class="text-[0.75rem] text-[#A79A8B] font-bold">ความจุ: ${room.capacity} ท่าน</div>
                        <div class="mt-3 flex flex-wrap gap-2">
                            <button type="button" onclick="event.stopPropagation(); viewRoomImages('${encodeURIComponent(room.images || '[]')}')" class="text-[0.65rem] font-bold px-3 py-1.5 bg-[#F9F8F6] text-[#6A5243] rounded-lg border border-[#EBE6DA] hover:bg-[#D4B59D]/20 transition-colors shadow-sm flex items-center gap-1.5 whitespace-nowrap"><i class="fas fa-search-plus text-[#D4B59D]"></i> ดูรูปภาพ</button>
                            ${isAdmin ? `<button type="button" onclick="event.stopPropagation(); openImageManageModal(${room.id}, '${room.name.replace(/'/g, "\\'")}', '${encodeURIComponent(room.images || '[]')}')" class="text-[0.65rem] font-bold px-3 py-1.5 bg-[#F9F8F6] text-[#6A5243] rounded-lg border border-[#EBE6DA] hover:bg-[#D4B59D]/20 transition-colors shadow-sm flex items-center gap-1.5 whitespace-nowrap"><i class="fas fa-cog text-[#A79A8B]"></i> จัดการรูป</button>` : ''}
                        </div>
                    </div>
                </div>
            `;
            grid.appendChild(card);
            allRoomCards.push(card);
        });

        // ── Attach search listeners ──
        const searchInput = document.getElementById('roomSearchBooking');
        const clearBtn = document.getElementById('roomSearchClear');
        searchInput.addEventListener('input', () => {
            filterRoomCards();
            // Deactivate pills when user types manually
            document.querySelectorAll('.room-keyword-pill').forEach(p => p.classList.remove('active'));
        });
        clearBtn.addEventListener('click', () => {
            searchInput.value = '';
            document.querySelectorAll('.room-keyword-pill').forEach(p => p.classList.remove('active'));
            filterRoomCards();
            searchInput.focus();
        });
    }

    function filterRoomCards() {
        const query = document.getElementById('roomSearchBooking').value.toLowerCase().trim();
        const clearBtn = document.getElementById('roomSearchClear');
        const noResult = document.getElementById('room-no-result');
        
        clearBtn.classList.toggle('hidden', !query);
        
        let visibleCount = 0;
        allRoomCards.forEach(card => {
            const name = card.dataset.roomName;
            const match = !query || name.includes(query);
            card.style.display = match ? '' : 'none';
            if (match) visibleCount++;
        });

        noResult.classList.toggle('hidden', visibleCount > 0);
    }

    // --- Image Viewer ---
    let roomViewer = null;
    function viewRoomImages(imagesJsonStr) {
        try {
            const images = JSON.parse(decodeURIComponent(imagesJsonStr));
            if (!images || images.length === 0) {
                Swal.fire({ icon: 'info', title: 'ไม่มีรูปภาพ', text: 'ห้องประชุมนี้ยังไม่ได้อัปโหลดรูปภาพ' });
                return;
            }
            
            const ul = document.getElementById('roomImagesViewer');
            ul.innerHTML = images.map(img => `<li><img src="${img}" alt="Room Image"></li>`).join('');
            
            if (roomViewer) {
                roomViewer.destroy();
            }
            
            roomViewer = new Viewer(ul, {
                inline: false,
                button: true,
                navbar: true,
                title: false,
                toolbar: {
                    zoomIn: 1,
                    zoomOut: 1,
                    oneToOne: 1,
                    reset: 1,
                    prev: 1,
                    play: {
                        show: 1,
                        size: 'large',
                    },
                    next: 1,
                    rotateLeft: 1,
                    rotateRight: 1,
                    flipHorizontal: 1,
                    flipVertical: 1,
                },
                viewed() {
                    roomViewer.zoomTo(1);
                }
            });
            roomViewer.show();
        } catch (e) {
            console.error(e);
        }
    }

    // --- Admin Image Management ---
    let currentManageRoomId = null;
    function openImageManageModal(roomId, roomName, imagesJsonStr) {
        currentManageRoomId = roomId;
        document.getElementById('imgModalRoomName').textContent = roomName;
        document.getElementById('imageManageModal').classList.remove('hidden');
        renderManageImages(imagesJsonStr);
    }
    
    function closeImageManageModal() {
        document.getElementById('imageManageModal').classList.add('hidden');
        currentManageRoomId = null;
        initRoomSelection(); // Refresh to update JSON str in buttons
    }

    function renderManageImages(imagesJsonStr) {
        try {
            const images = JSON.parse(decodeURIComponent(imagesJsonStr));
            const grid = document.getElementById('currentImagesGrid');
            if (!images || images.length === 0) {
                grid.innerHTML = '<div class="col-span-full text-center py-6 text-[#A79A8B] font-bold bg-white rounded-xl border-2 border-dashed border-[#EBE6DA]">ยังไม่มีรูปภาพ</div>';
                return;
            }
            
            grid.innerHTML = images.map(img => `
                <div class="relative group rounded-xl overflow-hidden border border-[#EBE6DA] bg-white shadow-sm aspect-video">
                    <img src="${img}" class="w-full h-full object-cover">
                    <button type="button" onclick="deleteRoomImage('${img}')" class="absolute top-2 right-2 w-8 h-8 rounded-full bg-red-500 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity shadow-lg hover:bg-red-600"><i class="fas fa-trash-alt text-xs"></i></button>
                </div>
            `).join('');
        } catch (e) {
            console.error(e);
        }
    }

    async function uploadRoomImage() {
        const fileInput = document.getElementById('newRoomImage');
        const file = fileInput.files[0];
        if (!file) return;
        if (!currentManageRoomId) return;

        const fd = new FormData();
        fd.append('room_id', currentManageRoomId);
        fd.append('image', file);

        MeetQueue.utils.loading(true, 'กำลังอัปโหลด...');
        try {
            const res = await MeetQueue.api.fetch('api/room_images.php', { method: 'POST', body: fd });
            MeetQueue.utils.loading(false);
            if (res.success) {
                // Fetch fresh room data to get new images array
                const data = await MeetQueue.api.fetch('api/rooms.php');
                const room = data.rooms.find(r => r.id == currentManageRoomId);
                if (room) {
                    renderManageImages(encodeURIComponent(room.images || '[]'));
                }
                fileInput.value = '';
                MeetQueue.utils.notify('success', 'อัปโหลดสำเร็จ');
            } else {
                throw new Error(res.message);
            }
        } catch (e) {
            MeetQueue.utils.loading(false);
            MeetQueue.utils.notify('error', 'อัปโหลดไม่สำเร็จ', e.message);
        }
    }

    async function deleteRoomImage(imagePath) {
        const confirm = await Swal.fire({
            title: 'ยืนยันการลบรูปภาพ?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'ลบ',
            cancelButtonText: 'ยกเลิก'
        });

        if (confirm.isConfirmed) {
            MeetQueue.utils.loading(true, 'กำลังลบ...');
            try {
                const res = await MeetQueue.api.fetch('api/room_images.php', {
                    method: 'DELETE',
                    body: JSON.stringify({ room_id: currentManageRoomId, image_path: imagePath })
                });
                MeetQueue.utils.loading(false);
                if (res.success) {
                    const data = await MeetQueue.api.fetch('api/rooms.php');
                    const room = data.rooms.find(r => r.id == currentManageRoomId);
                    if (room) {
                        renderManageImages(encodeURIComponent(room.images || '[]'));
                    }
                } else {
                    throw new Error(res.message);
                }
            } catch (e) {
                MeetQueue.utils.loading(false);
                MeetQueue.utils.notify('error', 'ลบไม่สำเร็จ', e.message);
            }
        }
    }

    function adjustValue(n) {
        const input = document.getElementById('participants_count');
        input.value = Math.max(1, parseInt(input.value) + n);
        updateSummary();
    }

    function setQuickTimePremium(s, e, btn) {
        document.getElementById('start_time').value = s;
        document.getElementById('end_time').value = e;
        document.querySelectorAll('.quick-btn-premium').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        updateSummary();
    }

    function clearFile() {
        document.getElementById('attachment').value = '';
        document.getElementById('file-status').classList.add('hidden');
    }

    // --- Equipment toggle ---
    const selectedEquip = new Set();
    let isOtherEquipActive = false;

    function toggleEquip(card, name) {
        if (selectedEquip.has(name)) {
            selectedEquip.delete(name);
            card.classList.remove('active');
        } else {
            selectedEquip.add(name);
            card.classList.add('active');
        }
        updateEquipmentsHidden();
    }
    
    function toggleEquipOther(card) {
        const inp = document.getElementById('equipOtherText');
        if (card.classList.contains('active')) {
            card.classList.remove('active');
            inp.style.display = 'none';
            isOtherEquipActive = false;
        } else {
            card.classList.add('active');
            inp.style.display = 'block';
            inp.focus();
            isOtherEquipActive = true;
        }
        updateEquipmentsHidden();
        
        // Use single listener reference
        inp.removeEventListener('input', updateEquipmentsHidden);
        inp.addEventListener('input', updateEquipmentsHidden);
    }

    function updateEquipmentsHidden() {
        let equips = [...selectedEquip];
        if (isOtherEquipActive) {
            const val = document.getElementById('equipOtherText').value.trim();
            if (val) {
                equips.push('อื่นๆ: ' + val);
            }
        }
        document.getElementById('equipments_hidden').value = equips.join(', ');
        updateSummary();
    }

    function updateSummary() {
        const date = document.getElementById('meeting_date').value;
        const start = document.getElementById('start_time').value;
        const end = document.getElementById('end_time').value;
        const count = document.getElementById('participants_count').value;
        const title = document.getElementById('title').value.trim();
        
        if (date) {
            const d = new Date(date + 'T00:00:00');
            const formattedDate = d.toLocaleDateString('th-TH', { day: 'numeric', month: 'long', year: 'numeric', calendar: 'buddhist' });
            document.getElementById('summary-datetime').textContent = `${formattedDate} (${start} - ${end})`;
        }
        document.getElementById('summary-count').textContent = `${count} คน`;
        if (title) document.getElementById('summary-title').textContent = title;
        
        const equipVal = document.getElementById('equipments_hidden').value;
        const equipRow = document.getElementById('summary-equip-row');
        if (equipVal) {
            document.getElementById('summary-equip').textContent = equipVal;
            equipRow.style.display = 'block';
        } else {
            equipRow.style.display = 'none';
        }
    }

    function moveStep(n) {
        if (n === 1 && !validateActiveStep()) return;
        updateSummary();
        document.getElementById(`step-${activeStep}`).classList.remove('active');
        activeStep += n;
        document.getElementById(`step-${activeStep}`).classList.add('active');
        refreshWizardProgress();
    }

    function validateActiveStep() {
        if (activeStep === 1) {
            if (!document.getElementById('room_id').value) {
                Swal.fire({ icon: 'warning', title: 'กรุณาเลือกห้องประชุม', confirmButtonColor: '#6A5243' }); return false;
            }
            if (!document.getElementById('title').value.trim()) {
                Swal.fire({ icon: 'warning', title: 'กรุณาระบุหัวข้อกิจกรรม', confirmButtonColor: '#6A5243' }); return false;
            }
            if (!document.getElementById('meeting_date').value) {
                Swal.fire({ icon: 'warning', title: 'กรุณาระบุวันที่จัดประชุม', confirmButtonColor: '#6A5243' }); return false;
            }
            if (!document.getElementById('department_name').value.trim()) {
                Swal.fire({ icon: 'warning', title: 'กรุณาระบุหน่วยงาน/ฝ่ายที่สังกัด', confirmButtonColor: '#6A5243' }); return false;
            }
        }
        return true;
    }

    function refreshWizardProgress() {
        const labels = ['1. เลือกห้อง & วันเวลา', '2. สรุปการจอง'];
        const icons = ['fa-calendar-check', 'fa-user-pen'];
        for (let i = 1; i <= 2; i++) {
            const dot = document.getElementById(`pstep-${i}`);
            if (!dot) continue;
            if (i < activeStep) {
                dot.className = 'progress-step-premium completed';
                dot.innerHTML = `<i class="fas fa-check"></i><span class="progress-label-premium">${labels[i-1]}</span>`;
            } else if (i === activeStep) {
                dot.className = 'progress-step-premium active';
                dot.innerHTML = `<i class="fas ${icons[i-1]}"></i><span class="progress-label-premium">${labels[i-1]}</span>`;
            } else {
                dot.className = 'progress-step-premium';
                dot.innerHTML = `<i class="fas ${icons[i-1]}"></i><span class="progress-label-premium">${labels[i-1]}</span>`;
            }
        }
        const prev = document.getElementById('prevBtn');
        const next = document.getElementById('nextBtn');
        const submit = document.getElementById('submitBtn');
        prev.style.opacity = activeStep === 1 ? '0' : '1';
        prev.style.pointerEvents = activeStep === 1 ? 'none' : 'auto';
        if (activeStep === 2) {
            next.classList.add('hidden');
            submit.classList.remove('hidden');
        } else {
            next.classList.remove('hidden');
            submit.classList.add('hidden');
        }
    }

    // ── Searchable Department Dropdown ──────────────────────────────────────
    let allDepartments = [];
    const sessionDept = <?php echo json_encode($_SESSION['user_data']['dept_name'] ?? ''); ?>;

    async function initDeptDropdown() {
        // Load departments from API
        try {
            const res = await MeetQueue.api.fetch('api/departments.php');
            if (res.success) allDepartments = res.departments;
        } catch(e) { allDepartments = []; }

        const input = document.getElementById('deptSearchInput');
        const hiddenInput = document.getElementById('department_name');
        const list = document.getElementById('deptDropdownList');
        const clearBtn = document.getElementById('deptClearBtn');
        const wrap = document.getElementById('deptInputWrap');

        // Pre-fill from session dept_name
        if (sessionDept) {
            input.value = sessionDept;
            hiddenInput.value = sessionDept;
            clearBtn.classList.add('visible');
            wrap.classList.add('has-value');
        }

        function highlight(text, query) {
            if (!query) return text;
            const esc = query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            return text.replace(new RegExp(`(${esc})`, 'gi'), '<span class="dept-match">$1</span>');
        }

        function renderList(query) {
            const q = query.toLowerCase().trim();
            const matches = q
                ? allDepartments.filter(d => d.toLowerCase().includes(q))
                : allDepartments;

            if (matches.length === 0) {
                list.innerHTML = `<div class="dept-no-result"><i class="fas fa-search" style="margin-right:0.4rem;opacity:0.4;"></i>ไม่พบหน่วยงาน "${query}"</div>`;
            } else {
                list.innerHTML = matches.map(d => `
                    <div class="dept-option${hiddenInput.value === d ? ' selected' : ''}" role="option" data-value="${d}">
                        <i class="fas fa-building" style="color:#D4B59D;font-size:0.75rem;flex-shrink:0;"></i>
                        <span>${highlight(d, q)}</span>
                    </div>
                `).join('');

                list.querySelectorAll('.dept-option').forEach(opt => {
                    opt.addEventListener('mousedown', e => {
                        e.preventDefault();
                        selectDept(opt.dataset.value);
                    });
                });
            }
        }

        function selectDept(val) {
            input.value = val;
            hiddenInput.value = val;
            clearBtn.classList.add('visible');
            wrap.classList.add('has-value');
            list.classList.remove('open');
        }

        function openList() {
            renderList(input.value);
            list.classList.add('open');
        }

        function closeList() {
            list.classList.remove('open');
        }

        // Events
        input.addEventListener('focus', () => openList());
        input.addEventListener('input', () => {
            const q = input.value;
            clearBtn.classList.toggle('visible', q.length > 0);
            wrap.classList.toggle('has-value', hiddenInput.value !== '');
            if (q === '') hiddenInput.value = '';
            renderList(q);
            list.classList.add('open');
        });
        input.addEventListener('blur', () => {
            // Delay to allow click to register
            setTimeout(() => {
                closeList();
                // If typed value doesn't match any option, clear the hidden field
                if (!allDepartments.includes(input.value)) {
                    hiddenInput.value = '';
                    wrap.classList.remove('has-value');
                }
            }, 150);
        });

        // Keyboard navigation
        input.addEventListener('keydown', e => {
            const opts = list.querySelectorAll('.dept-option');
            const focused = list.querySelector('.dept-option:focus, .dept-option.keyboard-focus');
            let idx = -1;
            opts.forEach((o, i) => { if (o === focused) idx = i; });

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                const next = opts[idx + 1] || opts[0];
                if (next) { opts.forEach(o => o.classList.remove('keyboard-focus')); next.classList.add('keyboard-focus'); next.scrollIntoView({ block: 'nearest' }); }
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                const prev = opts[idx - 1] || opts[opts.length - 1];
                if (prev) { opts.forEach(o => o.classList.remove('keyboard-focus')); prev.classList.add('keyboard-focus'); prev.scrollIntoView({ block: 'nearest' }); }
            } else if (e.key === 'Enter') {
                const kf = list.querySelector('.dept-option.keyboard-focus');
                if (kf) { e.preventDefault(); selectDept(kf.dataset.value); }
            } else if (e.key === 'Escape') {
                closeList();
            }
        });

        clearBtn.addEventListener('click', () => {
            input.value = '';
            hiddenInput.value = '';
            clearBtn.classList.remove('visible');
            wrap.classList.remove('has-value');
            input.focus();
            openList();
        });

        // Close when clicking outside
        document.addEventListener('click', e => {
            if (!document.getElementById('deptDropdownWrap').contains(e.target)) closeList();
        });
    }
</script>
