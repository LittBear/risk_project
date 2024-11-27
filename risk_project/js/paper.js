// รายชื่อแผนก
const departments = [
    "แพทย์", "OPD", "เปล", "WARD", "OR-LR", "ER", "LAB", "NCD",
    "เภสัชกรรม", "ทันตกรรม", "รังสี", "ปฐมภูมิ", "กายภาพ", "แผนไทย",
    "จิตเวช", "โภชนาการ", "บริหาร", "ซักฟอก", "Supply", "แม่บ้าน", "ประกัน-เวชระเบียน"
];

// DOM Elements
const departmentSelect = document.getElementById("department-user");
const topicsContainer = document.getElementById("topics-user-container");
const topicsList = document.getElementById("topics-user-list");

// เพิ่มแผนกใน dropdown
departments.forEach(dept => {
    const option = document.createElement("option");
    option.value = dept;
    option.textContent = dept;
    departmentSelect.appendChild(option);
});

// เมื่อเลือกแผนก
departmentSelect.addEventListener("change", function () {
    const selectedDept = this.value;

    // ดึงข้อมูลจากไฟล์ JSON
    fetch("../php_process/topics.json")
        .then(response => response.json())
        .then(data => {
            const deptTopics = data[selectedDept] || [];
            topicsList.innerHTML = "";

            deptTopics.forEach(({ mainTopic, subtopic, link }) => {
                const li = document.createElement("li");
                li.innerHTML = `<strong>${mainTopic}</strong>: <a href="${link}" target="_blank">${subtopic}</a>`;
                topicsList.appendChild(li);
            });

            topicsContainer.style.display = deptTopics.length ? "block" : "none";
        })
        .catch(err => console.error(err));
});
