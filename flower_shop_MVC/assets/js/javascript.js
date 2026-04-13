{
    //Mở hoặc đóng thanh tìm kiếm
    function toggleSearch() {
        let search = document.getElementById("searchInput");

        if (search.style.display === "none") {
            search.style.display = "block";
        } else {
            search.style.display = "none";
        }
    }
    let totalSeconds = 3600;
    //Đếm lùi thời gian sale
    function updateCountdown() {
        const countdownElement = document.getElementById("countdown");
        if (!countdownElement) return;
        totalSeconds--;
        if (totalSeconds < 0) totalSeconds = 0;
        let hours = Math.floor(totalSeconds / 3600);
        let minutes = Math.floor((totalSeconds % 3600) / 60);
        let seconds = totalSeconds % 60;
        countdownElement.innerText =
            String(hours).padStart(2, '0') + " : " +
            String(minutes).padStart(2, '0') + " : " +
            String(seconds).padStart(2, '0');
    }

    setInterval(updateCountdown, 1000);
    //Số lượng sản phẩm
    function changeQty(amount) {
        let input = document.querySelector('.qty-input');
        let value = parseInt(input.value) || 1;
        value += amount;
        if (value < 1) value = 1;
        input.value = value;
    }
    //Modal xóa sản phẩm
    function openDeleteModal(id, name) {
        document.getElementById('deleteProductId').value = id;
        document.getElementById('deleteProductName').textContent = name;
        new bootstrap.Modal(document.getElementById('modalDelete')).show();
    }
    //Modal hủy đơn hàng
    function openCancelModal(id) {
        document.getElementById('cancelOrderId').textContent = '#' + id;
        document.getElementById('cancelOrderIdInput').value = id;
        new bootstrap.Modal(document.getElementById('modalCancel')).show();
    }
    //Modal xóa đơn hàng admin
    function openDeleteOrderModal(id) {
        document.getElementById('deleteOrderId').textContent = '#' + id;
        document.getElementById('deleteOrderIdInput').value = id;
        new bootstrap.Modal(document.getElementById('modalDeleteOrder')).show();
    }
    //Upload dữ liệu sản phẩm
    function openEditModal(id, name, category, price, description, image) {
        document.getElementById("edit_id").value = id;
        document.getElementById("edit_name").value = name;
        document.getElementById("edit_category").value = category;
        document.getElementById("edit_price").value = price;
        document.getElementById("edit_description").value = description;
        //Hiển thị ảnh cũ
        document.getElementById("old_image").src = "assets/images/image_products/" + image;
        // reset preview
        document.getElementById("preview_image").classList.add("d-none");
    }
    //Hiển ảnh sẽ thêm vào
    function previewImage(event) {
        const img = document.getElementById("preview_image");
        img.src = URL.createObjectURL(event.target.files[0]);
        img.classList.remove("d-none");
    }
    const host = "https://provinces.open-api.vn/api/v2/";
    //Khai báo các hàm dùng chung lên đầu
    const renderData = (array, selectId) => {
        const element = document.getElementById(selectId);
        if (!element) return;

        let options = `<option value="">Chọn...</option>`;
        array.forEach(item => {
            options += `<option value="${item.code}">${item.name}</option>`;
        });
        element.innerHTML = options;
    };
    //Hàm gọi API chung
    const callAPI = async (api, selectId, groupId) => {
        try {
            const response = await fetch(api);
            const data = await response.json();

            // Lấy mảng dữ liệu tùy theo cấp độ API
            const array = Array.isArray(data) ? data : (data.districts || data.wards || []);

            renderData(array, selectId);

            // Hiện ô tiếp theo nếu có groupId
            if (groupId) {
                const group = document.getElementById(groupId);
                if (group) group.classList.add('group-show');
            }
        } catch (error) {
            console.error("Lỗi lấy dữ liệu:", error);
        }
    };
    //Lấy phần tử select của Tỉnh, Quận và xã
    const provinceEl = document.getElementById("province");
    const districtEl = document.getElementById("district");
    const wardEl = document.getElementById("ward");
    //Xử lý tỉnh
    if (provinceEl) {
        // Khởi tạo danh sách tỉnh
        callAPI(host + "?depth=1", "province");

        provinceEl.addEventListener("change", function () {
            const provinceCode = this.value;

            // Reset tất cả các cấp con khi tỉnh thay đổi
            ["district", "ward"].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.innerHTML = '<option value="">Chọn...</option>';
                document.getElementById(`${id}-group`)?.classList.remove('group-show');
            });

            if (provinceCode) {
                callAPI(`${host}p/${provinceCode}?depth=2`, "district", "district-group");
            }
        });
    }

    //Xử lý quận
    if (districtEl) {
        districtEl.addEventListener("change", function () {
            const districtCode = this.value;

            // Reset cấp phường xã khi quận thay đổi
            const wardSelect = document.getElementById("ward");
            const wardGroup = document.getElementById("ward-group");

            if (wardSelect) wardSelect.innerHTML = '<option value="">Chọn...</option>';
            wardGroup?.classList.remove('group-show');

            if (districtCode) {
                callAPI(`${host}d/${districtCode}?depth=2`, "ward", "ward-group");
            }
        });
    }

    //Xử lý phường xã
    if (wardEl) {
        wardEl.addEventListener("change", function () {
            // Cấp cuối cùng thường không gọi API nữa
            console.log("Người dùng đã chọn Phường/Xã mã số:", this.value);
        });
    }
    //Hàm thay đổi phương thức thanh toán
    function changeActivePayment(element) {
        //Tìm tất cả các thẻ label có class 'js-payment-card'
        const cards = document.querySelectorAll('.js-payment-card');

        //Duyệt qua từng thẻ và xóa class 'active'
        cards.forEach(card => {
            card.classList.remove('active');
        });

        //Thêm class 'active' vào thẻ vừa được click
        element.classList.add('active');

        //Tự động tích vào input radio bên trong label đó
        const radio = element.querySelector('input[type="radio"]');
        if (radio) {
            radio.checked = true;
        }
    }
    //Hàm xác nhận xóa đơn hàng
    function confirmDelete(id) {
        console.log("Đang gọi hàm xóa đơn hàng ID:", id); // Kiểm tra xem nó có chạy vào đây không

        var result = confirm("Bạn có chắc chắn muốn xóa đơn hàng #" + id + " không?");
        if (result) {
            window.location.href = "index.php?action=delete_order&id=" + id;
        }
    }
    //Tự động ẩn thông báo sau 3 giây
    document.addEventListener('DOMContentLoaded', function () {
        const alerts = document.querySelectorAll('.alert-box');
        alerts.forEach(alert => {
            //Sau 3 giây sẽ thêm class fade-out để chạy hiệu ứng biến mất
            setTimeout(() => {
                alert.classList.add('fade-out');
                //Sau khi hiệu ứng chạy xong (0.5s) thì xóa hẳn khỏi HTML
                setTimeout(() => {
                    alert.remove();
                }, 500);
            }, 3000);
        });
    });
}

