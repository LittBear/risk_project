document.addEventListener("DOMContentLoaded", function () {
    const items = document.querySelectorAll(".carousel-item");
    const dots = document.querySelectorAll(".dot");
    let currentIndex = 0;

    // ฟังก์ชันไปยังภาพที่เลือก
    function goToImage(index) {
        items.forEach((item, i) => {
            item.style.transform = `translateX(${(i - index) * 100}%)`;
        });

        dots.forEach((dot, i) => {
            dot.classList.toggle("active", i === index); // เปลี่ยนสถานะ dot
        });

        currentIndex = index; // อัปเดต index ปัจจุบัน
    }

    // ฟังก์ชันเลื่อนภาพอัตโนมัติ
    function autoSlide() {
        setInterval(() => {
            const nextIndex = (currentIndex + 1) % items.length; // วนไปยังภาพถัดไป
            goToImage(nextIndex);
        }, 3000); // ทุก 3 วินาที
    }

    // ตั้งค่าเริ่มต้น
    goToImage(0); // แสดงภาพแรก
    autoSlide(); // เริ่มการเลื่อนอัตโนมัติ

    // เพิ่ม Event Listener ให้ dots
    dots.forEach((dot, i) => {
        dot.addEventListener("click", function () {
            goToImage(i); // แสดงภาพที่ตรงกับ dot ที่คลิก
        });
    });
});
