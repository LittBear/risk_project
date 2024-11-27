// แสดงโมดอลล็อกอินเมื่อเปิดหน้าเว็บ
document.addEventListener("DOMContentLoaded", () => {
    showLoginModal();
});

// ฟังก์ชันแสดงโมดอล
function showLoginModal() {
    document.getElementById("loginModal").style.display = "flex"; // แสดงโมดอล
}

// ลบฟังก์ชันซ่อนโมดอลไป เพื่อไม่ให้ปิดโมดอล
// ฟังก์ชันนี้จะถูกลบออกเพื่อไม่ให้สามารถปิดโมดอลได้
// function hideLoginModal() {
//     const modal = document.getElementById("loginModal");
//     modal.style.display = "none";
// }


function validateLogin() {
    const idCard = document.getElementById('id_card').value.trim();
    const password = document.getElementById('password').value.trim();

    // ตรวจสอบเลขบัตรประชาชน (ต้องมี 13 หลักและเป็นตัวเลข)
    const idCardRegex = /^\d{13}$/;
    if (!idCardRegex.test(idCard)) {
        alert('กรุณากรอกเลขบัตรประชาชนให้ถูกต้อง (13 หลัก)');
        return false;
    }

    // ตรวจสอบว่ารหัสผ่านไม่ว่างเปล่า
    if (password === '') {
        alert('กรุณากรอกรหัสผ่าน');
        return false;
    }

    return true; // ผ่านการตรวจสอบ
}
