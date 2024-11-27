document.addEventListener("DOMContentLoaded", function () {
    const items = document.querySelectorAll(".carousel-item");
    const dots = document.querySelectorAll(".dot");
    let currentIndex = 0;

    // ฟังก์ชั่นไปยังภาพที่เลือก
    function goToImage(index) {
        items.forEach((item, i) => {
            item.style.transform = `translateX(${(i - index) * 100}%)`;
        });
        
        dots.forEach((dot, i) => {
            dot.classList.toggle("active", i === index);  // เปลี่ยนแสง dot ที่ถูกเลือก
        });

        currentIndex = index;
    }

    // ฟังก์ชั่นเลื่อนภาพอัตโนมัติ
    function autoSlide() {
        setInterval(() => {
            const nextIndex = (currentIndex + 1) % items.length;  // หาค่าภาพถัดไป
            goToImage(nextIndex);
        }, 4000); // ทุก ๆ 5.5 วินาที
    }

    // เริ่มต้น
    goToImage(0); // ไปที่ภาพแรก
    autoSlide(); // เริ่มการเลื่อนอัตโนมัติ

    // ฟังก์ชันสำหรับการเปลี่ยนภาพเมื่อคลิก dot
    dots.forEach((dot, i) => {
        dot.addEventListener('click', function() {
            goToImage(i);  // ไปที่ภาพที่คลิก
        });
    });
});
