// รายชื่อแผนก
const departments = [
    "แพทย์", "OPD", "เปล", "WARD", "OR-LR", "ER", "LAB", "NCD",
    "เภสัชกรรม", "ทันตกรรม", "รังสี", "ปฐมภูมิ", "กายภาพ", "แผนไทย",
    "จิตเวช", "โภชนาการ", "บริหาร", "ซักฟอก", "Supply", "แม่บ้าน", "ประกัน-เวชระเบียน"
];

// รายชื่อหัวข้อและหัวข้อย่อย
const topics = {
    "#1 การระบุตัวผู้ป่วยผิดพลาด (Patient Identification)": [
        "OPD", "IPD", "ทารกแรกเกิด", "การทำหัตถการ"
    ],
    "#2 การติดเชื้อที่สำคัญ": [
        "CA-UTI", "HAP"
    ],
    "#3 บุคลากรติดเชื้อจากการปฏิบัติหน้าที่": [
        "MDR", "Sharp Injury เลือด สารคัดหลั่ง", "การทำความสะอาดมือ", "Isolation Precautions"
    ],
    "#4 การเกิด Medication Error และ Adverse Drug Event": [
        "แพ้ยาซ้ำ", "การสั่งยา", "การจ่ายยา", "การบริหารยา", "การจัดยา"
    ],
    "#5 การให้เลือดผิดคน ผิดหมู่ ผิดชนิด": [
        "การเตรียมส่วนประกอบเลือด", "การให้เลือดในหอผู้ป่วย", "การปฏิบัติกรณีผู้ป่วยแพ้เลือด"
    ],
    "#6 การรายงานผลการตรวจทาง ห้องปฏิบัติการ/พยาธิวิทยา คลาดเคลื่อน": [
        "การรายงานผลการตรวจ"
    ]
};

// เพิ่มรายชื่อแผนกใน dropdown
const departmentSelect = document.getElementById("department-admin");
departments.forEach(dept => {
    const option = document.createElement("option");
    option.value = dept;
    option.textContent = dept;
    departmentSelect.appendChild(option);
});

// เมื่อเลือกแผนก
departmentSelect.addEventListener("change", function () {
    const selectedDept = this.value;
    const topicsContainer = document.getElementById("topics-admin-container");
    const topicsList = document.getElementById("topics-admin-list");

    topicsList.innerHTML = ""; // ล้างรายการหัวข้อเดิม

    if (selectedDept) {
        Object.entries(topics).forEach(([mainTopic, subtopics]) => {
            const li = document.createElement("li");
            li.innerHTML = `
                <strong>${mainTopic}</strong>
                <ul>
                    ${subtopics.map(sub => `
                        <li>
                            <label>
                                <input type="checkbox" data-main-topic="${mainTopic}" data-subtopic="${sub}">
                                ${sub}
                            </label>
                            <input type="text" class="link-input" placeholder="ฝังลิงก์ URL">
                        </li>
                    `).join("")}
                </ul>
            `;
            topicsList.appendChild(li);
        });
        topicsContainer.style.display = "block";
    } else {
        topicsContainer.style.display = "none";
    }
});

// เมื่อกดปุ่มบันทึก
document.getElementById("admin-form").addEventListener("submit", function (event) {
    event.preventDefault();
    const selectedDept = departmentSelect.value;
    const selectedTopics = [];

    // เก็บข้อมูลหัวข้อที่เลือกพร้อมลิงก์
    document.querySelectorAll('input[type="checkbox"]:checked').forEach(checkbox => {
        const mainTopic = checkbox.getAttribute("data-main-topic");
        const subtopic = checkbox.getAttribute("data-subtopic");
        const link = checkbox.parentElement.nextElementSibling.value;

        selectedTopics.push({
            mainTopic,
            subtopic,
            link
        });
    });

    // ส่งข้อมูลไปยัง server
    fetch("../php_process/save_topics.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ department: selectedDept, topics: selectedTopics })
    })
    .then(response => response.json())
    .then(data => alert(data.message))
    .catch(err => console.error(err));
});
