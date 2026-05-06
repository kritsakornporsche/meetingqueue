<style>
    /* Premium Animations & Transitions */
    .step-container { display: none; animation: slideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1); }
    .step-container.active { display: block; }
    @keyframes slideUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    
    /* Enhanced Progress Bar */
    .progress-bar-premium { display: flex; justify-content: space-between; margin-bottom: 4rem; position: relative; max-width: 600px; margin-left: auto; margin-right: auto; }
    .progress-bar-premium::before { content: ''; position: absolute; top: 22px; left: 0; width: 100%; height: 4px; background: #EBE6DA; z-index: 1; border-radius: 10px; }
    .progress-step-premium { width: 48px; height: 48px; border-radius: 18px; background: white; border: 3px solid #EBE6DA; display: flex; align-items: center; justify-content: center; z-index: 2; position: relative; transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1); font-weight: 800; color: #A79A8B; font-size: 1.125rem; }
    .progress-step-premium.active { border-color: #6A5243; background: #6A5243; color: white; transform: scale(1.1); box-shadow: 0 10px 25px rgba(106, 82, 67, 0.15); }
    .progress-step-premium.completed { border-color: #D4B59D; background: #D4B59D; color: white; }
    .progress-label-premium { position: absolute; top: 60px; font-size: 0.875rem; font-weight: 700; color: #A79A8B; white-space: nowrap; left: 50%; transform: translateX(-50%); letter-spacing: 0.05em; text-transform: uppercase; }
    .progress-step-premium.active .progress-label-premium { color: #6A5243; }

    /* Interactive Elements */
    .room-card-premium { border: 2px solid #EBE6DA; border-radius: 2rem; padding: 1.75rem 2rem; cursor: pointer; transition: all 0.3s; background: white; border-bottom-width: 6px; min-height: 140px; display: flex; flex-direction: column; justify-content: space-between; }
    .room-card-premium:hover { border-color: #D4B59D; transform: translateY(-4px); box-shadow: 0 15px 30px rgba(106, 82, 67, 0.05); }
    .room-card-premium.active { border-color: #6A5243; background: #FDFBF7; border-bottom-color: #4a3a2f; }
    .room-card-premium.active h4 { color: #6A5243; }
    
    .quick-btn-premium { border: 2px solid #EBE6DA; padding: 0.75rem 1.25rem; border-radius: 1.25rem; font-size: 0.9rem; font-weight: 600; transition: all 0.2s; background: white; color: #6A5243; border-bottom-width: 4px; min-width: 100px; }
    .quick-btn-premium:hover { border-color: #D4B59D; transform: translateY(-2px); }
    .quick-btn-premium.active { border-color: #6A5243; background: #6A5243; color: white; border-bottom-color: #4a3a2f; }

    .premium-input { width: 100%; padding: 1.25rem 1.5rem; border-radius: 1.5rem; background: #F9F8F6; border: 2px solid #F0EDE6; outline: none; transition: all 0.3s; color: #2D241E; font-weight: 600; font-size: 1.0625rem; }
    .premium-input:focus { border-color: #D4B59D; background: white; box-shadow: 0 0 0 5px rgba(212, 181, 157, 0.15); }
    
<<<<<<< HEAD
    .label-premium { font-size: 1rem; font-weight: 700; color: #4A3A2F; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 1rem; }
    .label-premium i { color: #D4B59D; font-size: 1.25rem; }
=======
    .label-premium { font-size: 0.9375rem; font-weight: 800; color: #4A3A2F; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem; }
    .label-premium i { color: #D4B59D; font-size: 1.1rem; }

    /* Loading Overlay */
    .loading-overlay { position: fixed; inset: 0; background: rgba(235, 230, 218, 0.9); backdrop-filter: blur(10px); z-index: 9999; display: none; flex-direction: column; align-items: center; justify-content: center; }
    .loading-overlay.active { display: flex; animation: fadeIn 0.4s ease-out; }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
>>>>>>> f2fbaf64a5040b047b58efcc47c17af94761a996
</style>

<div id="bookingLoading" class="loading-overlay">
    <div class="w-24 h-24 rounded-full bg-white shadow-2xl flex items-center justify-center mb-6 relative">
        <div class="absolute inset-0 rounded-full border-4 border-[#D4B59D]/20"></div>
        <div class="absolute inset-0 rounded-full border-4 border-[#6A5243] border-t-transparent animate-spin"></div>
        <i class="fas fa-paper-plane text-2xl text-[#6A5243]"></i>
    </div>
    <h3 class="text-2xl font-black text-[#6A5243] mb-2">กำลังส่งข้อมูล...</h3>
    <p class="text-[#A79A8B] font-bold">กรุณารอสักครู่ ระบบกำลังประมวลผลคำขอของคุณ</p>
</div>

<div class="max-w-[1100px] mx-auto py-12 px-6 md:px-10">
    <!-- Header Section -->
    <div class="text-center mb-48">
        <div class="inline-flex items-center gap-6 px-7 py-3 rounded-full bg-[#6A5243]/5 text-[#6A5243] text-[0.85rem] font-bold uppercase tracking-[0.25em] mb-12 border border-[#6A5243]/10">
            <i class="fas fa-bolt text-[#D4B59D]"></i> Instant Booking System
        </div>
        <h2 class="text-4xl md:text-5xl font-bold text-[#6A5243] mb-12 tracking-tight leading-[1.4]">แบบฟอร์มการจอง</h2>
        <p class="text-lg md:text-xl text-[#A79A8B] font-medium max-w-3xl mx-auto leading-[2.2] opacity-75">กรุณาเลือกรายละเอียดตามขั้นตอนด้านล่าง เพื่อความรวดเร็วในการพิจารณาอนุมัติ</p>
    </div>

    <!-- Progress Indicator -->
    <div class="progress-bar-premium mb-32">
        <div class="progress-step-premium active" id="pstep-1">
            <i class="fas fa-door-open"></i>
            <span class="progress-label-premium">1. เลือกห้อง</span>
        </div>
        <div class="progress-step-premium" id="pstep-2">
            <i class="fas fa-clock"></i>
            <span class="progress-label-premium">2. วันและเวลา</span>
        </div>
        <div class="progress-step-premium" id="pstep-3">
            <i class="fas fa-user-pen"></i>
            <span class="progress-label-premium">3. สรุปข้อมูล</span>
        </div>
    </div>

    <!-- Main Form Card -->
    <div class="bg-white rounded-[4rem] shadow-[0_40px_100px_rgba(106,82,67,0.1)] border border-[#EBE6DA] relative" style="padding: 60px 50px !important; min-height: 800px;">
        <div class="absolute -top-32 -right-32 w-80 h-80 bg-[#D4B59D]/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-32 -left-32 w-80 h-80 bg-[#6A5243]/5 rounded-full blur-3xl pointer-events-none"></div>

        <form id="bookingForm" enctype="multipart/form-data" class="relative z-10">
            <div style="height: 40px !important;"></div> <!-- Subtler Spacer -->
            
            <!-- Step 1: Room Selection & Title -->
            <div class="step-container active" id="step-1">
                <div class="space-y-12">
                    <div style="margin-top: 10px !important;">
                        <label class="label-premium" style="font-size: 1.1rem !important; margin-bottom: 2rem !important;"><i class="fas fa-building"></i> เลือกห้องประชุมที่ต้องการใช้งาน <span class="text-red-500">*</span></label>
                        <div id="room-grid" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Premium Room Cards -->
                        </div>
                        <input type="hidden" id="room_id" required>
                    </div>
                    
                    <div class="pt-20">
                        <label class="label-premium"><i class="fas fa-quote-left" style="padding: 8px"></i> หัวข้อการประชุมหรือกิจกรรม <span class="text-red-500">*</span></label>
                        <input type="text" id="title" class="premium-input placeholder:text-[#A79A8B]/50" placeholder="ตัวอย่าง: ประชุมติดตามงานประจำสัปดาห์..." required>
                    </div>
                </div>
            </div>

            <!-- Step 2: Date, Time & Capacity -->
            <div class="step-container" id="step-2">
                <div class="space-y-12">
                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                        <!-- Date Selection -->
                        <div class="lg:col-span-5">
                            <label class="label-premium"><i class="fas fa-calendar-alt"></i> วันที่จัดประชุม <span class="text-red-500">*</span></label>
                            <input type="date" id="meeting_date" class="premium-input text-center text-2xl font-black py-6" required>
                            <p class="text-xs text-[#A79A8B] mt-4 font-bold text-center italic">* เฉพาะวันจันทร์ - ศุกร์ ยกเว้นวันหยุดนักขัตฤกษ์</p>
                        </div>

                        <!-- Time Selection -->
                        <div class="lg:col-span-7 bg-[#F9F8F6] p-8 rounded-[2.5rem] border border-[#EBE6DA]">
                            <label class="text-[#6A5243] font-black mb-6 flex items-center gap-2">
                                <i class="fas fa-hourglass-half text-[#D4B59D]"></i> ช่วงเวลาที่ต้องการ
                            </label>
                            
                            <div class="grid grid-cols-2 gap-6 mb-8">
                                <div>
                                    <span class="text-[0.65rem] font-black text-[#A79A8B] uppercase tracking-widest mb-2 block">เริ่มเวลา</span>
                                    <input type="time" id="start_time" value="08:30" class="premium-input py-4 text-center text-xl font-black">
                                </div>
                                <div>
                                    <span class="text-[0.65rem] font-black text-[#A79A8B] uppercase tracking-widest mb-2 block">สิ้นสุดเวลา</span>
                                    <input type="time" id="end_time" value="16:30" class="premium-input py-4 text-center text-xl font-black">
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-3">
                                <button type="button" onclick="setQuickTimePremium('08:30', '12:00', this)" class="quick-btn-premium">ช่วงเช้า</button>
                                <button type="button" onclick="setQuickTimePremium('13:00', '16:30', this)" class="quick-btn-premium">ช่วงบ่าย</button>
                                <button type="button" onclick="setQuickTimePremium('08:30', '16:30', this)" class="quick-btn-premium">ทั้งวัน</button>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="label-premium"><i class="fas fa-users"></i> จำนวนผู้เข้าประชุมที่คาดการณ์ <span class="text-red-500">*</span></label>
                        <div class="flex items-center gap-6 max-w-[300px] mb-8">
                            <button type="button" onclick="adjustValue(-5)" class="w-16 h-16 rounded-2xl bg-white border-2 border-[#EBE6DA] flex items-center justify-center text-[#6A5243] hover:border-[#6A5243] hover:bg-[#FDFBF7] transition-all"><i class="fas fa-minus text-xl"></i></button>
                            <input type="number" id="participants_count" value="10" class="premium-input text-center text-3xl font-black py-4" required>
                            <button type="button" onclick="adjustValue(5)" class="w-16 h-16 rounded-2xl bg-white border-2 border-[#EBE6DA] flex items-center justify-center text-[#6A5243] hover:border-[#6A5243] hover:bg-[#FDFBF7] transition-all"><i class="fas fa-plus text-xl"></i></button>
                        </div>
                    </div>
                    
                    <div>
                        <label class="label-premium"><i class="fas fa-tv"></i> ตัวเลือกอุปกรณ์ (Optional)</label>
                        <div class="flex flex-wrap gap-4">
                            <?php 
                            $equipments = ['โปรเจกเตอร์' => 'fa-video', 'ทีวี' => 'fa-tv', 'คอมพิวเตอร์' => 'fa-desktop', 'ไมโครโฟน' => 'fa-microphone', 'อื่นๆ' => 'fa-ellipsis-h'];
                            foreach($equipments as $name => $icon): 
                            ?>
                            <label class="flex items-center gap-3 cursor-pointer px-5 py-3 bg-white border-2 border-[#EBE6DA] rounded-2xl hover:border-[#6A5243] hover:bg-[#FDFBF7] transition-all group select-none">
                                <input type="checkbox" name="equipments" value="<?php echo $name; ?>" class="w-5 h-5 accent-[#6A5243]"> 
                                <span class="flex items-center gap-2">
                                    <i class="fas <?php echo $icon; ?> text-[#A79A8B] group-hover:text-[#D4B59D] transition-colors"></i>
                                    <span class="text-[0.95rem] font-bold text-[#6A5243]"><?php echo $name; ?></span>
                                </span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 3: Contact & Summary -->
            <div class="step-container" id="step-3">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
                    <!-- Contact Info -->
                    <div class="lg:col-span-7 space-y-8">
                        <div>
                            <label class="label-premium"><i class="fas fa-phone"></i> เบอร์โทรศัพท์สำหรับติดต่อกลับ <span class="text-red-500">*</span></label>
                            <input type="text" id="phone" placeholder="ตัวอย่าง: 081-234-5678" class="premium-input" required>
                        </div>
                        <div>
                            <label class="label-premium"><i class="fas fa-clipboard-list"></i> หมายเหตุเพิ่มเติม (ความต้องการพิเศษ)</label>
                            <textarea id="description" rows="3" class="premium-input py-4 px-6" placeholder="ระบุสิ่งที่ต้องการให้เจ้าหน้าที่เตรียมความพร้อม..."></textarea>
                        </div>
                        <div>
                            <label class="label-premium"><i class="fas fa-file-pdf"></i> เอกสารแนบ (ประกาศ/กำหนดการ)</label>
                            <div class="relative group">
                                <label for="attachment" class="flex flex-col items-center justify-center w-full h-40 border-3 border-dashed border-[#D4B59D]/40 rounded-[2.5rem] cursor-pointer hover:bg-[#FDFBF7] hover:border-[#6A5243] transition-all group">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <div class="w-14 h-14 rounded-full bg-[#D4B59D]/10 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                            <i class="fas fa-upload text-[#D4B59D] text-xl"></i>
                                        </div>
                                        <p class="text-[#6A5243] font-black">คลิกเพื่ออัปโหลดไฟล์</p>
                                        <p class="text-[0.65rem] text-[#A79A8B] font-bold mt-2 tracking-widest uppercase">Max Size 10MB (PDF, JPG, PNG)</p>
                                    </div>
                                    <input id="attachment" type="file" class="hidden" />
                                </label>
                                <div id="file-status" class="hidden mt-4 p-4 bg-[#6A5243]/5 rounded-2xl flex items-center justify-between border border-[#6A5243]/10">
                                    <div class="flex items-center gap-3">
                                        <i class="fas fa-file-circle-check text-[#D4B59D]"></i>
                                        <span id="file-name" class="text-sm font-black text-[#6A5243] truncate max-w-[200px]"></span>
                                    </div>
                                    <button type="button" onclick="clearFile()" class="text-red-400 hover:text-red-600"><i class="fas fa-times-circle"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Booking Summary Panel -->
                    <div class="lg:col-span-5 bg-[#6A5243] rounded-[3rem] p-12 text-white shadow-2xl relative overflow-hidden flex flex-col justify-between min-h-[450px]">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16"></div>
                        <div>
                            <h3 class="text-xl font-bold mb-10 flex items-center gap-4">
                                <i class="fas fa-check-double text-[#D4B59D]"></i> สรุปรายละเอียดการจอง
                            </h3>
                            
                            <div class="space-y-8">
                                <div class="bg-white/5 p-6 rounded-[1.5rem] border border-white/10">
                                    <span class="text-[0.65rem] font-bold text-white/50 uppercase tracking-[0.2em] block mb-2">ห้องประชุมที่เลือก</span>
                                    <div id="summary-room" class="font-bold text-lg text-[#D4B59D] leading-tight">โปรดเลือกห้อง...</div>
                                </div>
                                <div class="bg-white/5 p-6 rounded-[1.5rem] border border-white/10">
                                    <span class="text-[0.65rem] font-bold text-white/50 uppercase tracking-[0.2em] block mb-2">วันและเวลาที่เลือก</span>
                                    <div id="summary-datetime" class="font-bold text-lg leading-tight">โปรดระบุวันและเวลา...</div>
                                </div>
                                <div class="bg-white/5 p-6 rounded-[1.5rem] border border-white/10">
                                    <span class="text-[0.65rem] font-bold text-white/50 uppercase tracking-[0.2em] block mb-2">จำนวนผู้เข้าร่วมประชุม</span>
                                    <div id="summary-count" class="font-bold text-lg">10 คน</div>
                                </div>
                            </div>
                        </div>

<<<<<<< HEAD
                        <div class="mt-10 p-6 rounded-[1.5rem] bg-[#D4B59D]/20 text-[#D4B59D] text-[0.75rem] font-medium leading-relaxed border border-[#D4B59D]/30">
                            <i class="fas fa-info-circle mr-1"></i> ข้อมูลทั้งหมดจะถูกส่งให้ผู้ดูแลระบบตรวจสอบ คุณสามารถติดตามสถานะได้ในเมนู "ผลการอนุมัติ"
=======
                        <div class="mt-8 p-4 rounded-2xl bg-[#D4B59D]/20 text-[#D4B59D] text-xs font-bold leading-relaxed border border-[#D4B59D]/30">
                            * ข้อมูลทั้งหมดจะถูกส่งให้ผู้ดูแลระบบตรวจสอบ คุณสามารถติดตามสถานะได้ในเมนู "สถานะการจอง"
>>>>>>> f2fbaf64a5040b047b58efcc47c17af94761a996
                        </div>
                    </div>
                </div>
            </div>

            <!-- Wizard Controls -->
            <div class="flex justify-between items-center mt-20 pt-12 border-t-2 border-[#F9F8F6]">
                <button type="button" id="prevBtn" onclick="moveStep(-1)" class="px-10 py-4 rounded-2xl border-2 border-[#EBE6DA] text-[#A79A8B] font-bold hover:border-[#D4B59D] hover:text-[#6A5243] transition-all opacity-0 pointer-events-none flex items-center gap-3">
                    <i class="fas fa-arrow-left"></i> ย้อนกลับ
                </button>
                
                <button type="button" id="nextBtn" onclick="moveStep(1)" class="px-14 py-5 rounded-2xl bg-gradient-to-br from-[#D4B59D] to-[#6A5243] text-white font-bold shadow-xl hover:shadow-2xl hover:-translate-y-1 active:scale-95 transition-all duration-300 flex items-center gap-4 min-w-[180px] justify-center">
                    ถัดไป <i class="fas fa-chevron-right"></i>
                </button>
                
                <button type="submit" id="submitBtn" class="hidden px-16 py-5 rounded-2xl bg-[#22c55e] text-white font-bold shadow-xl hover:shadow-2xl hover:-translate-y-1 active:scale-95 transition-all duration-300 flex items-center gap-4 min-w-[220px] justify-center">
                    ยืนยันการส่งข้อมูล <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let activeStep = 1;

    document.addEventListener('DOMContentLoaded', () => {
        initRoomSelection();
        
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
                
                const file = document.getElementById('attachment').files[0];
                if (file) fd.append('attachment', file);
                
                const eqs = [];
                document.querySelectorAll('input[name="equipments"]:checked').forEach(cb => eqs.push(cb.value));
                if (eqs.length > 0) fd.append('equipments', eqs.join(', '));

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

    async function initRoomSelection() {
        const data = await MeetQueue.api.fetch('api/rooms.php');
        if (data.success) {
            const grid = document.getElementById('room-grid');
            data.rooms.forEach(room => {
                const card = document.createElement('div');
                card.className = 'room-card-premium group';
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
                        </div>
                    </div>
                `;
                grid.appendChild(card);
            });
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

    function updateSummary() {
        const date = document.getElementById('meeting_date').value;
        const start = document.getElementById('start_time').value;
        const end = document.getElementById('end_time').value;
        const count = document.getElementById('participants_count').value;
        
        if (date) {
            const formattedDate = new Date(date).toLocaleDateString('th-TH', { day: 'numeric', month: 'long', year: 'numeric' });
            document.getElementById('summary-datetime').textContent = `${formattedDate} (${start} - ${end})`;
        }
        document.getElementById('summary-count').textContent = `${count} คน`;
    }

    function moveStep(n) {
        if (n === 1 && !validateActiveStep()) return;

        document.getElementById(`step-${activeStep}`).classList.remove('active');
        activeStep += n;
        document.getElementById(`step-${activeStep}`).classList.add('active');

        refreshWizardProgress();
    }

    function validateActiveStep() {
        if (activeStep === 1) {
            if (!document.getElementById('room_id').value) {
                Swal.fire({ icon: 'warning', title: 'กรุณาเลือกห้องประชุม', confirmButtonColor: '#6A5243' });
                return false;
            }
            if (!document.getElementById('title').value.trim()) {
                Swal.fire({ icon: 'warning', title: 'กรุณาระบุหัวข้อกิจกรรม', confirmButtonColor: '#6A5243' });
                return false;
            }
        }
        if (activeStep === 2) {
            if (!document.getElementById('meeting_date').value) {
                Swal.fire({ icon: 'warning', title: 'กรุณาระบุวันที่จัดประชุม', confirmButtonColor: '#6A5243' });
                return false;
            }
        }
        return true;
    }

    function refreshWizardProgress() {
        for (let i = 1; i <= 3; i++) {
            const dot = document.getElementById(`pstep-${i}`);
            if (i < activeStep) {
                dot.className = 'progress-step-premium completed';
                dot.innerHTML = '<i class="fas fa-check"></i><span class="progress-label-premium">' + (i==1?'1. เลือกห้อง':(i==2?'2. วันเวลา':'3. สรุป')) + '</span>';
            } else if (i === activeStep) {
                dot.className = 'progress-step-premium active';
                dot.innerHTML = '<i class="fas ' + (i==1?'fa-door-open':(i==2?'fa-clock':'fa-user-pen')) + '"></i><span class="progress-label-premium">' + (i==1?'1. เลือกห้อง':(i==2?'2. วันเวลา':'3. สรุป')) + '</span>';
            } else {
                dot.className = 'progress-step-premium';
                dot.innerHTML = '<i class="fas ' + (i==1?'fa-door-open':(i==2?'fa-clock':'fa-user-pen')) + '"></i><span class="progress-label-premium">' + (i==1?'1. เลือกห้อง':(i==2?'2. วันเวลา':'3. สรุป')) + '</span>';
            }
        }

        const prev = document.getElementById('prevBtn');
        const next = document.getElementById('nextBtn');
        const submit = document.getElementById('submitBtn');

        prev.style.opacity = activeStep === 1 ? '0' : '1';
        prev.style.pointerEvents = activeStep === 1 ? 'none' : 'auto';

        if (activeStep === 3) {
            next.classList.add('hidden');
            submit.classList.remove('hidden');
        } else {
            next.classList.remove('hidden');
            submit.classList.add('hidden');
        }
    }
</script>
