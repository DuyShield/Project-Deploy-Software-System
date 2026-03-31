<?php
require_once "model/M_Product.php";
require_once "model/M_Category.php";
class C_Product
{

    public function home()
    {
        $model = new M_Product();
        $products = $model->getAllProducts();
        include "views/home.php";
    }

    public function detail()
    {
        $id = $_GET['id'];
        $model = new M_Product();
        $product = $model->getProductById($id);
        include "views/product_detail.php";
    }
    public function search()
    {
        $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : "";
        $model = new M_Product();
        $products = $model->searchProducts($keyword);
        $isSearch = true;
        include "views/home.php";
    }
    public function home_admin()
    {
        $model = new M_Product();
        $products = $model->getAllProducts();
        $categoryModel = new M_Category();
        $categories = $categoryModel->getAllCategories();
        include "views/admin_home.php";
    }
    public function search_admin_home()
    {
        $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : "";
        $model = new M_Product();
        $products = $model->searchProducts($keyword);
        $isSearch = true;
        include "views/admin_home.php";
    }
    public function save_product()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $category = $_POST['category'];

            // Xử lý Upload ảnh
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $image = $_FILES['image']['name'];
                $tmp_name = $_FILES['image']['tmp_name'];
                $file_ext = strtolower(pathinfo($image, PATHINFO_EXTENSION));
                $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

                if (in_array($file_ext, $allowed_ext)) {
                    $new_image_name = uniqid() . "_" . time() . "." . $file_ext;
                    echo "Tên file mới: " . $new_image_name . "<br>"; // Debug
                    $target = $_SERVER['DOCUMENT_ROOT'] . "/Project_Alpha_FlowerShop/flower_shop_MVC/assets/images/image_products/" . $new_image_name;

                    if (move_uploaded_file($tmp_name, $target)) {
                        $model = new M_Product();
                        $model->addProduct($name, $description, $price, $new_image_name, $category);
                        header("Location: index.php?action=home_admin");
                        exit();
                    } else {
                        echo "Lỗi upload ảnh! Kiểm tra quyền thư mục.";
                    }
                } else {
                    echo "Chỉ chấp nhận file JPG, PNG, GIF.";
                }
            } else {
                echo "Vui lòng chọn ảnh.";
            }
        }
    }
    public function del_product()
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $id = $_POST['id_product'];
            $model = new M_Product();
            $product = $model->getProductById($id);
            if ($product) {
                // Xóa file ảnh
                $image_path = $_SERVER['DOCUMENT_ROOT'] . "/Project_Alpha_FlowerShop/flower_shop_MVC/assets/images/image_products/" . $product['image'];
                if (file_exists($image_path)) {
                    unlink($image_path);
                }
                $model->deleteProduct($id);
            }
            header("Location: index.php?action=home_admin");
        }
    }
    public function update_product()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id_product'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $category = $_POST['category'];

            $model = new M_Product();
            $oldProduct = $model->getProductById($id);
            $oldImage = $oldProduct['image'];
            //Kiểm tra có chỉnh sửa ảnh mới không
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $image = $_FILES['image']['name'];
                $tmp_name = $_FILES['image']['tmp_name'];
                $file_ext = strtolower(pathinfo($image, PATHINFO_EXTENSION));
                $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
                //Kiểm tra định dạng ảnh
                if (in_array($file_ext, $allowed_ext)) {
                    $new_image_name = uniqid() . "_" . time() . "." . $file_ext;
                    $target = $_SERVER['DOCUMENT_ROOT'] . "/Project_Alpha_FlowerShop/flower_shop_MVC/assets/images/image_products/" . $new_image_name;
                    if (move_uploaded_file($tmp_name, $target)) {
                        $oldPath = $_SERVER['DOCUMENT_ROOT'] . "/Project_Alpha_FlowerShop/flower_shop_MVC/assets/images/image_products/" . $oldImage;
                        if (!empty($oldImage) && file_exists($oldPath)) {
                            unlink($oldPath);
                        }
                        //Update sản phẩm
                        $model->updateProduct($id, $name, $description, $price, $new_image_name, $category);
                    } else {
                        echo "Lỗi upload ảnh!";
                        return;
                    }
                } else {
                    echo "Chỉ chấp nhận JPG, PNG, GIF";
                    return;
                }
            } else {
                //Update sản phẩm khi không có thêm ảnh mới
                $model->updateProductNoImage($id, $name, $description, $price, $category);
            }
            header("Location: index.php?action=home_admin");
            exit();
        }
    }
    
}