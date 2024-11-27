function deleteImage(imageId) {
    if (confirm("คุณแน่ใจหรือไม่ว่าต้องการลบรูปภาพนี้?")) {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "../php_process/delete_image.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                console.log(xhr.responseText); // แสดงข้อความจาก PHP
                if (xhr.responseText == "ลบรูปภาพสำเร็จ") {
                    alert("ลบรูปภาพเรียบร้อยแล้ว");
                    location.reload();
                } else {
                    alert("ไม่สามารถลบรูปภาพ: " + xhr.responseText);
                    location.reload()
                }
            }
        };
        xhr.send("id=" + imageId);
    }
}


document.getElementById('noteForm').addEventListener('submit', function (event) {
    event.preventDefault(); // หยุดการทำงานแบบ default ของฟอร์ม

    // ส่งข้อมูลไปยัง server
    const noteText = document.getElementById('noteTextarea').value;
    console.log('noteText:', noteText);  // ตรวจสอบค่าที่จะส่งไป

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "../php_process/update_note.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            console.log(xhr.responseText);  // ตรวจสอบข้อความตอบกลับจาก PHP
            if (xhr.responseText === "บันทึกสำเร็จ") {
                document.getElementById('noteText').textContent = noteText; // แสดงข้อความใหม่
                alert("บันทึกสำเร็จ!");
                document.getElementById('noteForm').style.display = "none"; // ซ่อนฟอร์ม
                document.getElementById('editNoteBtn').style.display = "block"; // แสดงปุ่มแก้ไข
                location.reload()
            } else {
                alert("ไม่สามารถบันทึกได้: " + xhr.responseText);
                location.reload()
            }
        }
    };
    xhr.send("noteText=" + encodeURIComponent(noteText));
    
});
document.addEventListener("DOMContentLoaded", function () {
    const editNoteBtn = document.getElementById("editNoteBtn");
    const noteForm = document.getElementById("noteForm");
    const cancelEditBtn = document.getElementById("cancelEditBtn");

    // ตรวจสอบการคลิกปุ่มแก้ไข
    editNoteBtn.addEventListener("click", function() {
        console.log("คลิกปุ่มแก้ไข"); // เพิ่ม log เพื่อตรวจสอบว่าได้กดปุ่มหรือไม่
        noteForm.style.display = "flex"; // แสดงฟอร์ม
        editNoteBtn.style.display = "none"; // ซ่อนปุ่มแก้ไข
    });

    // ยกเลิกการแก้ไข
    cancelEditBtn.addEventListener("click", function() {
        noteForm.style.display = "none"; // ซ่อนฟอร์ม
        editNoteBtn.style.display = "block"; // แสดงปุ่มแก้ไข
    });
});





