{
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

        //Mở hoặc đóng QR code dựa trên phương thức thanh toán
        const qrSection = document.getElementById('qr-code-section');
        if (radio && radio.value === 'bank_transfer') {
            qrSection.style.display = 'block';
        } else {
            qrSection.style.display = 'none';
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
    //Hàm validate form checkout
    function validateCheckout() {
        const name = document.querySelector('input[name="name"]').value.trim();
        const email = document.querySelector('input[name="email"]').value.trim();
        const phone = document.querySelector('input[name="phone"]').value.trim();
        const address = document.querySelector('input[name="address"]').value.trim();
        if (!name) {
            alert('Vui lòng nhập họ và tên người nhận.');
            return false;
        }
        if (!email) {
            alert('Vui lòng nhập email.');
            return false;
        }
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email)) {
            alert('Email không hợp lệ.');
            return false;
        }
        if (!/^[0-9]{10}$/.test(phone)) {
            alert('Số điện thoại phải gồm đúng 10 chữ số.');
            return false;
        }
        if (!address) {
            alert('Vui lòng nhập địa chỉ giao hàng.');
            return false;
        }
        return true;
    }
    //Hàm tự động format số điện thoại khi người dùng nhập
    const phoneInput = document.querySelector('input[name="phone"]');
    if (phoneInput) {
        phoneInput.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '').slice(0, 10);
        });
    }
    //Hàm xử lý checkbox chọn tất cả và chọn từng sản phẩm
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const selectedCheckboxes = document.querySelectorAll('.selected-item-checkbox');
    //Hàm định dạng số thành định dạng tiền tệ Việt Nam
    function formatCurrency(value) {
        return value.toLocaleString('vi-VN') + ' đ';
    }
    //Hàm cập nhật tổng tiền khi chọn sản phẩm
    const subtotalAmountEl = document.getElementById('subtotalAmount');
    const grandTotalAmountEl = document.getElementById('grandTotalAmount');
    const totalPriceInput = document.querySelector('input[name="total_price"]');
    const fullTotal = totalPriceInput ? parseInt(totalPriceInput.dataset.fullTotal, 10) : 0;
    //Hàm tính tổng tiền dựa trên các checkbox được chọn
    function updateTotals() {
        const checkedBoxes = Array.from(selectedCheckboxes).filter(cb => cb.checked);
        const selectedSum = checkedBoxes.reduce((sum, cb) => sum + parseInt(cb.dataset.itemTotal || 0, 10), 0);
        const displayTotal = checkedBoxes.length === 0 ? fullTotal : selectedSum;

        if (subtotalAmountEl) subtotalAmountEl.textContent = formatCurrency(displayTotal);
        if (grandTotalAmountEl) grandTotalAmountEl.textContent = formatCurrency(displayTotal);
        if (totalPriceInput) totalPriceInput.value = displayTotal;
    }
    //Cập nhật trạng thái của checkbox "Chọn tất cả" và tổng tiền
    function updateSelectAllState() {
        const checkedCount = Array.from(selectedCheckboxes).filter(cb => cb.checked).length;
        if (selectAllCheckbox) {
            selectAllCheckbox.checked = checkedCount === selectedCheckboxes.length && checkedCount > 0;
        }
    }
    //Lưu trạng thái checkbox vào localStorage khi refresh trang 
    const storageKey = 'cart_selected_items';
    const checkboxes = Array.from(document.querySelectorAll('.selected-item-checkbox'));
    const getSavedSelection = () => {
        try {
            return JSON.parse(localStorage.getItem(storageKey)) || [];
        } catch (e) {
            return [];
        }
    };
    //Lưu trạng thái checkbox vào localStorage
    const saveSelection = ids => {
        localStorage.setItem(storageKey, JSON.stringify(ids));
    };
    //Đồng bộ trạng thái checkbox với localStorage mỗi khi có thay đổi
    const syncStorage = () => {
        const selectedIds = checkboxes.filter(cb => cb.checked).map(cb => cb.value);
        saveSelection(selectedIds);
    };
    //Khôi phục trạng thái checkbox từ localStorage khi trang được tải lại
    const restoreSelection = () => {
        const saved = getSavedSelection();
        if (saved.length === 0) {
            checkboxes.forEach(cb => cb.checked = true);
        } else {
            checkboxes.forEach(cb => cb.checked = saved.includes(cb.value));
        }
        updateSelectAllState();
        updateTotals();
    };
    //Gắn sự kiện cho checkbox "Chọn tất cả" và các checkbox sản phẩm
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function () {
            checkboxes.forEach(cb => cb.checked = this.checked);
            syncStorage();
            updateTotals();
        });
    }
    //Gắn sự kiện cho từng checkbox sản phẩm để cập nhật tổng tiền và trạng thái "Chọn tất cả"
    selectedCheckboxes.forEach(cb => {
        cb.addEventListener('change', function () {
            updateSelectAllState();
            syncStorage();
            updateTotals();
        });
    });

    updateTotals();
    restoreSelection();

    // AJAX for quantity update (Lụm trên github)
    document.querySelectorAll('.cart-qty-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const id = this.dataset.id;
            const op = this.dataset.op;
            fetch(`index.php?action=update_cart&id=${id}&op=${op}&ajax=1`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (data.removed) {
                            document.getElementById(`cart-row-${id}`).remove();
                            updateTotals();
                            if (document.querySelectorAll('.selected-item-checkbox').length === 0) {
                                location.reload();
                            }
                        } else {
                            const qtyValue = document.querySelector(`#cart-row-${id} .cart-qty-value`);
                            qtyValue.value = data.quantity;
                            const itemTotal = document.querySelector(`#cart-row-${id} .item-total`);
                            itemTotal.textContent = formatCurrency(data.item_total);
                            const checkbox = document.querySelector(`#cart-row-${id} .selected-item-checkbox`);
                            checkbox.dataset.itemTotal = data.item_total;
                            updateTotals();
                        }
                    }
                });
        });
    });
    //Xử lý sự kiện show của Modal xóa wishlist
    document.addEventListener('DOMContentLoaded', function () {
        const modalDelete = document.getElementById('modalDelete');

        modalDelete.addEventListener('show.bs.modal', function (event) {
            // Nút kích hoạt modal
            const button = event.relatedTarget;

            // Lấy dữ liệu từ các thuộc tính data-id và data-name
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');

            // Đổ dữ liệu vào các thẻ trong Modal
            const inputId = modalDelete.querySelector('#deleteProductId');
            const textName = modalDelete.querySelector('#deleteProductName');

            inputId.value = id;
            textName.textContent = name;
        });
    });
}



