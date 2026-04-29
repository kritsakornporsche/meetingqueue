<div class="card p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-primary">จัดการรายชื่อผู้ใช้งาน</h2>
            <p class="text-text-muted">ข้อมูลรายชื่อบุคลากรในระบบ (Local Database)</p>
        </div>
        <div class="flex gap-2">
            <a href="seed_users.php" target="_blank" class="px-4 py-2 bg-secondary/20 text-primary rounded-lg hover:bg-secondary/30 transition-colors flex items-center gap-2 text-sm font-medium">
                <i class="fas fa-seedling"></i> เติมข้อมูลทดสอบ
            </a>
            <button onclick="syncUsers()" id="syncBtn" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-hover transition-all flex items-center gap-2 text-sm font-medium">
                <i class="fas fa-sync-alt"></i> ซิงค์จาก ZK BioTime
            </button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-border bg-surface">
                    <th class="p-3 font-semibold text-primary">รูปภาพ</th>
                    <th class="p-3 font-semibold text-primary">ชื่อ-นามสกุล</th>
                    <th class="p-3 font-semibold text-primary">รหัสพนักงาน</th>
                    <th class="p-3 font-semibold text-primary">ตำแหน่ง</th>
                    <th class="p-3 font-semibold text-primary">แผนก</th>
                    <th class="p-3 font-semibold text-primary">สิทธิ์</th>
                </tr>
            </thead>
            <tbody id="userTableBody">
                <tr id="loadingRow">
                    <td colspan="6" class="p-8 text-center text-text-muted">
                        <i class="fas fa-spinner fa-spin mr-2"></i> กำลังโหลดข้อมูล...
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
async function loadUsers() {
    try {
        const response = await fetch('api/users.php');
        const result = await response.json();
        
        if (result.success) {
            const tableBody = document.getElementById('userTableBody');
            if (result.data.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="6" class="p-8 text-center text-text-muted">ไม่พบข้อมูลผู้ใช้งาน กรุณากดปุ่มเติมข้อมูลทดสอบ</td></tr>';
                return;
            }
            
            tableBody.innerHTML = result.data.map(user => `
                <tr class="border-b border-border hover:bg-primary/5 transition-colors">
                    <td class="p-3">
                        <img src="https://ui-avatars.com/api/?name=${encodeURIComponent(user.first_name)}&background=4f46e5&color=fff" 
                             class="w-10 h-10 rounded-full shadow-sm" alt="avatar">
                    </td>
                    <td class="p-3">
                        <div class="font-medium text-primary">${user.first_name} ${user.last_name || ''}</div>
                        <div class="text-xs text-text-muted">Username: ${user.username}</div>
                    </td>
                    <td class="p-3 text-sm font-mono text-secondary">${user.emp_code}</td>
                    <td class="p-3 text-sm">${user.position_name || '-'}</td>
                    <td class="p-3 text-sm">${user.dept_name || '-'}</td>
                    <td class="p-3">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold ${user.role === 'admin' ? 'bg-primary/10 text-primary' : 'bg-secondary/10 text-secondary'}">
                            ${user.role.toUpperCase()}
                        </span>
                    </td>
                </tr>
            `).join('');
        } else {
            alert('โหลดข้อมูลล้มเหลว: ' + result.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('เกิดข้อผิดพลาดในการเชื่อมต่อ');
    }
}

async function syncUsers() {
    const btn = document.getElementById('syncBtn');
    const originalContent = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> กำลังซิงค์...';
    btn.disabled = true;

    try {
        const response = await fetch('api/sync_users.php');
        const result = await response.json();
        alert(result.message);
        if (result.success) {
            loadUsers();
        }
    } catch (error) {
        alert('เกิดข้อผิดพลาดในการซิงค์ข้อมูล');
    } finally {
        btn.innerHTML = originalContent;
        btn.disabled = false;
    }
}

// Initial load
document.addEventListener('DOMContentLoaded', loadUsers);
</script>
