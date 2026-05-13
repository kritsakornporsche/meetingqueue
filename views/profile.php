<?php
// Include config for database connection if not already included
require_once "api/config.php";

// Security check: ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Fetch fresh user data from database
try {
    $pdo = getLocalDB();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $_SESSION['user_data'] = $user;
} catch (Exception $e) {
    $user = $_SESSION['user_data'];
}

$role = $user['role'] ?? 'user';
$photo_url = $user['photo'] ? $user['photo'] : "https://192.168.9.7/auth_files/photo/" . $user['emp_code'] . ".jpg";
$fallback_avatar = 'https://ui-avatars.com/api/?name=' . urlencode($user['first_name']) . '&background=6A5243&color=fff&size=200';
?>

<div style="max-width: 1100px; margin: 0 auto; padding: 20px; font-family: 'Sarabun', sans-serif;" class="animate-fade">
    
    <!-- Profile Header Card -->
    <div style="background: white; border-radius: 30px; box-shadow: 0 10px 30px rgba(106, 82, 67, 0.1); border: 1px solid rgba(212, 181, 157, 0.3); overflow: hidden; margin-bottom: 40px !important;">
        <div style="background: linear-gradient(135deg, #FDFBF7 0%, #F3F0E6 100%); padding: 40px !important; border-bottom: 1px solid rgba(212, 181, 157, 0.2);">
            <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 40px;">
                <!-- Avatar -->
                <div style="position: relative; flex-shrink: 0;">
                    <div style="width: 140px; height: 140px; border-radius: 50%; overflow: hidden; border: 5px solid white; box-shadow: 0 8px 20px rgba(106, 82, 67, 0.15); background: white;">
                        <img src="<?php echo $photo_url; ?>" onerror="this.src='<?php echo $fallback_avatar; ?>'" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <button onclick="openEditModal()" style="position: absolute; bottom: 5px; right: 5px; width: 36px; height: 36px; background: #6A5243; color: white; border-radius: 50%; border: 3px solid white; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(0,0,0,0.2);">
                        <i class="fas fa-camera" style="font-size: 14px;"></i>
                    </button>
                </div>

                <!-- Basic Info -->
                <div style="flex: 1; min-width: 300px;">
                    <div style="display: flex; align-items: center; flex-wrap: wrap; gap: 15px; margin-bottom: 10px;">
                        <h1 style="font-size: clamp(28px, 4vw, 42px); font-weight: 900; color: #6A5243; margin: 0; line-height: 1.1;"><?php echo htmlspecialchars($user['first_name'] . ' ' . ($user['last_name'] ?? '')); ?></h1>
                        <span style="background: #6A5243; color: white; padding: 4px 15px; border-radius: 20px; font-size: 11px; font-weight: 900; text-transform: uppercase; letter-spacing: 1px;">
                            <?php echo strtoupper($role); ?>
                        </span>
                    </div>
                    <div style="display: flex; flex-wrap: wrap; gap: 20px; color: #A79A8B; font-weight: 700; font-size: 15px;">
                        <span><i class="fas fa-id-badge" style="color: #D4B59D; margin-right: 6px;"></i> รหัส: <?php echo htmlspecialchars($user['emp_code']); ?></span>
                        <span><i class="fas fa-at" style="color: #D4B59D; margin-right: 6px;"></i> <?php echo htmlspecialchars($user['username']); ?></span>
                    </div>
                    <div style="margin-top: 25px;">
                        <button onclick="openEditModal()" class="btn" style="background: white; border: 1.5px solid rgba(106, 82, 67, 0.2); color: #6A5243; border-radius: 30px; padding: 10px 25px; font-weight: 900; cursor: pointer; display: flex; align-items: center; gap: 10px; transition: all 0.3s;">
                            <i class="fas fa-user-edit"></i> แก้ไขข้อมูลส่วนตัว
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Sections -->
        <div style="padding: 40px !important; background: white;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 40px;">
                
                <!-- Professional Details -->
                <div style="display: flex; flex-direction: column; gap: 30px;">
                    <div>
                        <h3 style="font-size: 13px; font-weight: 900; color: #A79A8B; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 20px !important; border-left: 4px solid #D4B59D; padding-left: 12px;">ข้อมูลตำแหน่งงาน</h3>
                        <div style="display: grid; gap: 20px;">
                            <div style="background: #FDFBF7; padding: 25px !important; border-radius: 20px; border: 1px solid rgba(212, 181, 157, 0.15);">
                                <label style="display: block; font-size: 11px; font-weight: 900; color: #A79A8B; margin-bottom: 10px !important; text-transform: uppercase;">ตำแหน่งปัจจุบัน</label>
                                <div style="color: #6A5243; font-weight: 900; font-size: 18px; display: flex; align-items: center; gap: 12px;">
                                    <i class="fas fa-briefcase" style="color: #D4B59D; font-size: 16px;"></i>
                                    <?php echo htmlspecialchars($user['position_name'] ?? 'ไม่ระบุตำแหน่ง'); ?>
                                </div>
                            </div>
                            <div style="background: #FDFBF7; padding: 25px !important; border-radius: 20px; border: 1px solid rgba(212, 181, 157, 0.15);">
                                <label style="display: block; font-size: 11px; font-weight: 900; color: #A79A8B; margin-bottom: 10px !important; text-transform: uppercase;">สังกัด / หน่วยงาน</label>
                                <div style="color: #6A5243; font-weight: 900; font-size: 18px; display: flex; align-items: center; gap: 12px;">
                                    <i class="fas fa-hospital" style="color: #D4B59D; font-size: 16px;"></i>
                                    <?php echo htmlspecialchars($user['dept_name'] ?? 'ไม่ระบุแผนก'); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 style="font-size: 13px; font-weight: 900; color: #A79A8B; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 20px !important; border-left: 4px solid #D4B59D; padding-left: 12px;">ข้อมูลการติดต่อ</h3>
                        <div style="background: #FDFBF7; padding: 25px !important; border-radius: 20px; border: 1px solid rgba(212, 181, 157, 0.15);">
                            <label style="display: block; font-size: 11px; font-weight: 900; color: #A79A8B; margin-bottom: 10px !important; text-transform: uppercase;">อีเมล์ติดต่องาน</label>
                            <div style="color: #6A5243; font-weight: 900; font-size: 18px; display: flex; align-items: center; gap: 12px; word-break: break-all;">
                                <i class="fas fa-envelope-open-text" style="color: #D4B59D; font-size: 16px;"></i>
                                <?php echo htmlspecialchars($user['email'] ?? 'ยังไม่ได้ระบุอีเมล์'); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Access & Stats -->
                <div style="display: flex; flex-direction: column; gap: 30px;">
                    <div style="background: #6A5243; padding: 35px !important; border-radius: 25px; color: white; position: relative; overflow: hidden; box-shadow: 0 15px 35px rgba(106, 82, 67, 0.2);">
                        <i class="fas fa-shield-halved" style="position: absolute; right: -20px; bottom: -20px; font-size: 120px; opacity: 0.1;"></i>
                        <h3 style="font-size: 12px; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 25px !important; opacity: 0.8;">สิทธิ์การใช้งานระบบ</h3>
                        <div style="font-size: 42px; font-weight: 900; text-transform: uppercase; letter-spacing: -1px; margin-bottom: 10px !important; line-height: 1;"><?php echo $role; ?></div>
                        <div style="font-size: 11px; font-weight: 700; opacity: 0.7; text-transform: uppercase; letter-spacing: 1.5px;">ระดับการเข้าถึงสูงสุด</div>
                    </div>

                    <div style="background: #FDFBF7; padding: 30px !important; border-radius: 25px; border: 1px solid rgba(212, 181, 157, 0.2); text-align: center;">
                        <h3 style="font-size: 11px; font-weight: 900; color: #A79A8B; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 25px !important;">สถิติการใช้งาน</h3>
                        <div style="display: flex; gap: 20px;">
                            <div style="flex: 1;">
                                <div style="font-size: 28px; font-weight: 900; color: #6A5243;">0</div>
                                <div style="font-size: 10px; font-weight: 900; color: #A79A8B; text-transform: uppercase;">จองห้อง</div>
                            </div>
                            <div style="width: 1px; background: rgba(212, 181, 157, 0.3); height: 40px; margin-top: 5px;"></div>
                            <div style="flex: 1;">
                                <div style="font-size: 28px; font-weight: 900; color: #6A5243;">0</div>
                                <div style="font-size: 10px; font-weight: 900; color: #A79A8B; text-transform: uppercase;">อนุมัติ</div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Footer Note -->
    <div style="text-align: center; margin-top: 40px; opacity: 0.5;">
        <p style="font-size: 11px; font-weight: 900; color: #A79A8B; text-transform: uppercase; letter-spacing: 3px;">Meeting Room System v1.0</p>
    </div>
</div>

<script>
// Helper function to resize image before upload
function resizeImage(file, maxWidth, maxHeight) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = (event) => {
            const img = new Image();
            img.src = event.target.result;
            img.onload = () => {
                const canvas = document.createElement('canvas');
                let width = img.width;
                let height = img.height;

                if (width > height) {
                    if (width > maxWidth) {
                        height *= maxWidth / width;
                        width = maxWidth;
                    }
                } else {
                    if (height > maxHeight) {
                        width *= maxHeight / height;
                        height = maxHeight;
                    }
                }

                canvas.width = width;
                canvas.height = height;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, width, height);
                
                // Convert to blob (JPEG with 0.8 quality)
                canvas.toBlob((blob) => {
                    resolve(new File([blob], file.name, { type: 'image/jpeg' }));
                }, 'image/jpeg', 0.8);
            };
        };
        reader.onerror = error => reject(error);
    });
}

function openEditModal() {
    Swal.fire({
        title: '<div style="font-size: 24px; font-weight: 900; color: #6A5243; padding-top: 10px;">แก้ไขข้อมูลโปรไฟล์</div>',
        html: `
            <div style="text-align: left; padding: 10px 15px;">
                <div style="margin-bottom: 25px;">
                    <label style="display: block; font-size: 12px; font-weight: 900; color: #A79A8B; text-transform: uppercase; margin-bottom: 10px !important;">เปลี่ยนรูปโปรไฟล์</label>
                    <input type="file" id="edit-photo" accept="image/*" style="width: 100%; padding: 12px; border-radius: 12px; border: 1px solid rgba(212, 181, 157, 0.3); background: #FDFBF7; font-size: 12px;">
                </div>
                <div style="margin-bottom: 25px;">
                    <label style="display: block; font-size: 12px; font-weight: 900; color: #A79A8B; text-transform: uppercase; margin-bottom: 10px !important;">อีเมล์ติดต่อ <span style="color: #991b1b;">*</span></label>
                    <input type="email" id="edit-email" style="width: 100%; padding: 15px; border-radius: 15px; border: 1px solid rgba(212, 181, 157, 0.3); background: #FDFBF7; font-size: 14px; font-weight: 700; color: #6A5243;" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>">
                </div>
                <?php if($role === 'admin'): ?>
                <div style="margin-bottom: 10px;">
                    <label style="display: block; font-size: 12px; font-weight: 900; color: #A79A8B; text-transform: uppercase; margin-bottom: 10px !important;">ระดับสิทธิ์</label>
                    <select id="edit-role" style="width: 100%; padding: 15px; border-radius: 15px; border: 1px solid rgba(212, 181, 157, 0.3); background: #FDFBF7; font-size: 14px; font-weight: 700; color: #6A5243;">
                        <option value="admin" <?php echo $role === 'admin' ? 'selected' : ''; ?>>ADMIN</option>
                        <option value="user" <?php echo $role === 'user' ? 'selected' : ''; ?>>USER</option>
                    </select>
                </div>
                <?php endif; ?>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'บันทึกข้อมูล',
        cancelButtonText: 'ยกเลิก',
        confirmButtonColor: '#6A5243',
        cancelButtonColor: '#A79A8B',
        width: '500px',
        padding: '2rem',
        borderRadius: '30px',
        preConfirm: async () => {
            const email = document.getElementById('edit-email').value;
            const photoInput = document.getElementById('edit-photo');
            const roleSelect = document.getElementById('edit-role');
            const role = roleSelect ? roleSelect.value : '<?php echo $role; ?>';
            
            if (!email) {
                Swal.showValidationMessage('กรุณากรอกอีเมล์ให้ครบถ้วน');
                return false;
            }

            const formData = new FormData();
            formData.append('email', email);
            formData.append('role', role);
            
            if (photoInput.files[0]) {
                try {
                    // Resize to max 500x500 to prevent 'max_allowed_packet' error
                    const resizedPhoto = await resizeImage(photoInput.files[0], 500, 500);
                    formData.append('photo', resizedPhoto);
                } catch (e) {
                    console.error('Resize error:', e);
                    formData.append('photo', photoInput.files[0]); // Fallback to original
                }
            }

            try {
                const response = await fetch('api/update_profile.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();
                if (!result.success) throw new Error(result.message);
                return result;
            } catch (error) {
                Swal.showValidationMessage(`เกิดข้อผิดพลาด: ${error.message}`);
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                icon: 'success',
                title: 'สำเร็จ!',
                text: 'อัปเดตข้อมูลโปรไฟล์เรียบร้อยแล้ว',
                confirmButtonColor: '#6A5243',
                borderRadius: '25px'
            }).then(() => {
                window.location.reload();
            });
        }
    });
}
</script>
