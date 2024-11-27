function openEditModal(id_card, user_type) {
    document.getElementById("editIdCard").value = id_card;
    document.getElementById("editUserType").value = user_type;
    document.getElementById("editUserModal").style.display = "block";
}

function closeModal() {
    document.getElementById("editUserModal").style.display = "none";
}
