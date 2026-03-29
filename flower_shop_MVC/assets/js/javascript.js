//Mở hoặc đóng thanh tìm kiếm
function toggleSearch() {
    let search = document.getElementById("searchInput");

    if (search.style.display === "none") {
        search.style.display = "block";
    } else {
        search.style.display = "none";
    }
}
//Đếm lùi thời gian sale
let totalSeconds = 7 * 3600 + 4 * 60 + 29;
function updateCountdown() {
    totalSeconds--;
    if (totalSeconds < 0) totalSeconds = 0;
    let hours = Math.floor(totalSeconds / 3600);
    let minutes = Math.floor((totalSeconds % 3600) / 60);
    let seconds = totalSeconds % 60;
    document.getElementById("countdown").innerText =
        String(hours).padStart(2, '0') + " : " +
        String(minutes).padStart(2, '0') + " : " +
        String(seconds).padStart(2, '0');
}

setInterval(updateCountdown, 1000);
//Số lượng sản phẩm
function changeQty(delta) {
    const qtyInput = document.getElementById('quantity');
    let qty = parseInt(qtyInput.value) + delta;
    if (qty < 1) qty = 1;
    qtyInput.value = qty;
}
//Modal xóa sản phẩm
function openDeleteModal(id, name) {
    document.getElementById('deleteProductId').value = id;
    document.getElementById('deleteProductName').textContent = name;
    new bootstrap.Modal(document.getElementById('modalDelete')).show();
}
//Upload dữ liệu sản phẩm
function openEditModal(id, name, category, price, description, image) {
    document.getElementById("edit_id").value = id;
    document.getElementById("edit_name").value = name;
    document.getElementById("edit_category").value = category;
    document.getElementById("edit_price").value = price;
    document.getElementById("edit_description").value = description;
    //Hiển thị ảnh cũ
    document.getElementById("old_image").src = "assets/images/" + image;
    // reset preview
    document.getElementById("preview_image").classList.add("d-none");
}
//Hiển ảnh sẽ thêm vào
function previewImage(event) {
    const img = document.getElementById("preview_image");
    img.src = URL.createObjectURL(event.target.files[0]);
    img.classList.remove("d-none");
}