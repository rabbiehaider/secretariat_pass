<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Products extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->brunch = $this->session->userdata('BRANCHid');
        $access = $this->session->userdata('userId');
        if ($access == '') {
            redirect("Login");
        }
        $this->load->model("Model_myclass", "mmc", TRUE);
        $this->load->model('Model_table', "mt", TRUE);
        $this->load->model('Billing_model');
    }

    public function index()
    {
        $access = $this->mt->userAccess();
        if (!$access) {
            redirect(base_url());
        }
        $data['title'] = "Product";
        $data['productCode'] = $this->mt->generateProductCode();
        $data['content'] = $this->load->view('Administrator/products/add_product', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function addProduct()
    {
        $res = ['success' => false, 'message' => ''];
        try {
            // $productObj = json_decode($this->input->raw_input_stream);
            $productObj = json_decode($this->input->post('data'));

            $productNameCount = $this->db->query("SELECT * FROM tbl_product WHERE Product_Name = ?", $productObj->Product_Name)->num_rows();
            if ($productNameCount > 0) {
                $res = ['success' => false, 'message' => 'Product name already exists'];
                echo json_encode($res);
                exit;
            }

            $productCodeCount = $this->db->query("SELECT * FROM tbl_product WHERE Product_Code = ?", $productObj->Product_Code)->num_rows();
            if ($productCodeCount > 0) {
                $res = ['success' => false, 'message' => 'Product code already exists'];
                echo json_encode($res);
                exit;
            }

            $product = (array)$productObj;

            $unique_id = uniqid();
            $string = strtolower(trim($product['Product_Name'] . '-' . $unique_id));
            $string = str_replace(' ', '-', $string);
            $slug = preg_replace('/[^a-z0-9-]/', '', $string);

            $product['slug']           = $slug;
            $product['is_website']     = $productObj->is_website == true ? 'true' : 'false';
            $product['is_service']     = $productObj->is_service == true ? 'true' : 'false';
            $product['is_offer']       = $productObj->is_offer   == true ? 'true' : 'false';
            $product['is_popular']     = $productObj->is_popular == true ? 'true' : 'false';
            $product['is_arrival']     = $productObj->is_arrival == true ? 'true' : 'false';
            $product['status']         = 'a';
            $product['AddBy']          = $this->session->userdata("userId");
            $product['AddTime']        = date('Y-m-d H:i:s');
            $product['last_update_ip'] = get_client_ip();
            $product['branch_id']      = $this->brunch;

            $this->db->insert('tbl_product', $product);
            $productId = $this->db->insert_id();

            if (!empty($_FILES['image'])) {
                $imagePath = $this->mt->uploadImage($_FILES, 'image', 'uploads/products', rand(111111, 999999));
                $this->db->query("UPDATE tbl_product SET Product_Image = ? WHERE Product_SlNo = ?", [$imagePath, $productId]);
            }

            if (!empty($_FILES['sizeImage'])) {
                $sImagePath = $this->mt->uploadImage($_FILES, 'sizeImage', 'uploads/products/sizes', rand(111111, 999999));
                $this->db->query("UPDATE tbl_product SET Product_SizeImage = ? WHERE Product_SlNo = ?", [$sImagePath, $productId]);
            }

            // Product Image Gallery Upload
            if (!empty($_FILES['images'])) {
                $images = $_FILES['images'] ?? [];

                $otherImages = [];

                for ($i = 0; $i < count($images['name']); $i++) {
                    $arr = [
                        'name' => $images['name'][$i],
                        'type' => $images['type'][$i],
                        'tmp_name' => $images['tmp_name'][$i],
                        'error' => $images['error'][$i],
                        'size' => $images['size'][$i]
                    ];
                    array_push($otherImages, $arr);
                }
                if (count($otherImages) > 0) {
                    foreach ($otherImages as $value) {
                        $data = array(
                            'Product_ID'    => $productId,
                            'Gallery_Image' => $this->imageUpload($value),
                            'AddBy'         => $this->session->userdata("userId"),
                            'AddTime'       => date('Y-m-d H:i:s')
                        );
                        $this->db->insert('tbl_product_gallery', $data);
                    }
                }
            }

            $res = ['success' => true, 'message' => 'Product added successfully', 'productId' => $this->mt->generateProductCode()];
        } catch (Exception $ex) {
            $res = ['success' => false, 'message' => $ex->getMessage()];
        }

        echo json_encode($res);
    }

    public function updateProduct()
    {
        $res = ['success' => false, 'message' => ''];
        try {
            // $productObj = json_decode($this->input->raw_input_stream);
            $productObj = json_decode($this->input->post('data'));
            $proRow = $this->db->query("SELECT * FROM tbl_product WHERE Product_SlNo = ?", $productObj->Product_SlNo)->row();

            $productNameCount = $this->db->query("SELECT * FROM tbl_product WHERE Product_Name = ? and Product_SlNo != ?", [$productObj->Product_Name, $productObj->Product_SlNo])->num_rows();
            if ($productNameCount > 0) {
                $res = ['success' => false, 'message' => 'Product name already exists'];
                echo json_encode($res);
                exit;
            }

            $productCodeCount = $this->db->query("SELECT * FROM tbl_product WHERE Product_Code = ? and Product_SlNo != ?", [$productObj->Product_Code, $productObj->Product_SlNo])->num_rows();
            if ($productCodeCount > 0) {
                $res = ['success' => false, 'message' => 'Product code already exists'];
                echo json_encode($res);
                exit;
            }

            $product = (array)$productObj;
            unset($product['Product_SlNo']);

            // make product slug
            if ($proRow->Product_Name != $productObj->Product_Name) {
                $unique_id       = uniqid();
                $string          = strtolower(trim($product['Product_Name'] . '-' . $unique_id));
                $string          = str_replace(' ', '-', $string);
                $slug            = preg_replace('/[^a-z0-9-]/', '', $string);
                $product['slug'] = $slug;
            }
            
            $product['is_website']     = $productObj->is_website == true ? 'true' : 'false';
            $product['is_service']     = $productObj->is_service == true ? 'true' : 'false';
            $product['is_offer']       = $productObj->is_offer   == true ? 'true' : 'false';
            $product['is_popular']     = $productObj->is_popular == true ? 'true' : 'false';
            $product['is_arrival']     = $productObj->is_arrival == true ? 'true' : 'false';
            $product['UpdateBy']       = $this->session->userdata("userId");
            $product['UpdateTime']     = date('Y-m-d H:i:s');
            $product['last_update_ip'] = get_client_ip();

            $this->db->WHERE('Product_SlNo', $productObj->Product_SlNo)->update('tbl_product', $product);

            if (!empty($_FILES['image'])) {
                $oldImgFile = $proRow->Product_Image;
                if (file_exists($oldImgFile)) {
                    unlink($oldImgFile);
                }
                $imagePath = $this->mt->uploadImage($_FILES, 'image', 'uploads/products', rand(111111, 999999));
                $this->db->query("UPDATE tbl_product SET Product_Image = ? where Product_SlNo = ?", [$imagePath, $productObj->Product_SlNo]);
            }

            if (!empty($_FILES['sizeImage'])) {
                $oldSImgFile = $proRow->Product_SizeImage;
                if (file_exists($oldSImgFile)) {
                    unlink($oldSImgFile);
                }
                $sImagePath = $this->mt->uploadImage($_FILES, 'sizeImage', 'uploads/products/sizes', rand(111111, 999999));
                $this->db->query("UPDATE tbl_product SET Product_SizeImage = ? where Product_SlNo = ?", [$sImagePath, $productObj->Product_SlNo]);
            }

            if (!empty($_FILES['images'])) {

                // $query = $this->db->query("SELECT * from tbl_product_gallery where Product_ID = '$productObj->Product_SlNo'");
                // if ($query->num_rows() > 0) {
                //     unlink(base_url() . '/uploads/product_gallery/' . $query->row()->Product_Image);
                //     $this->db->query("DELETE FROM tbl_product_gallery WHERE Product_ID = ?", $productObj->Product_SlNo);
                // }

                $images = $_FILES['images'] ?? [];
                $otherImages = [];

                for ($i = 0; $i < count($images['name']); $i++) {
                    $arr = [
                        'name' => $images['name'][$i],
                        'type' => $images['type'][$i],
                        'tmp_name' => $images['tmp_name'][$i],
                        'error' => $images['error'][$i],
                        'size' => $images['size'][$i]
                    ];
                    array_push($otherImages, $arr);
                }
                if (count($otherImages) > 0) {
                    foreach ($otherImages as $value) {
                        $data = array(
                            'Product_ID'    => $productObj->Product_SlNo,
                            'Gallery_Image' => $this->imageUpload($value),
                            'AddBy'         => $this->session->userdata("userId"),
                            'AddTime'       => date('Y-m-d H:i:s')
                        );
                        $this->db->insert('tbl_product_gallery', $data);
                    }
                }
            }

            $res = ['success' => true, 'message' => 'Product updated successfully', 'productId' => $this->mt->generateProductCode()];
        } catch (Exception $ex) {
            $res = ['success' => false, 'message' => $ex->getMessage()];
        }

        echo json_encode($res);
    }

    public function deleteProduct()
    {
        $res = ['success' => false, 'message' => ''];
        try {
            $data = json_decode($this->input->raw_input_stream);
            $updateData = array(
                'status'         => 'd',
                'DeletedBy'      => $this->session->userdata("userId"),
                'DeletedTime'    => date('Y-m-d H:i:s'),
                'last_update_ip' => get_client_ip()
            );
            $this->db->set($updateData)->WHERE('Product_SlNo', $data->productId)->update('tbl_product');

            $res = ['success' => true, 'message' => 'Product deleted successfully'];
        } catch (Exception $ex) {
            $res = ['success' => false, 'message' => $ex->getMessage()];
        }

        echo json_encode($res);
    }

    public function getProducts()
    {
        $data = json_decode($this->input->raw_input_stream);

        $clauses = "";
        $limit = "";
        $status = "a";
        if (isset($data->status) && $data->status != '') {
            $status = $data->status;
        }

        if (isset($data->categoryId) && $data->categoryId != '') {
            $clauses .= " and p.ProductCategory_ID = '$data->categoryId'";
        }

        if (isset($data->subCategoryId) && $data->subCategoryId != '') {
            $clauses .= " and p.ProductSubCategory_ID = '$data->subCategoryId'";
        }

        if (isset($data->isWebsite) && $data->isWebsite != null && $data->isWebsite != '') {
            $clauses .= " and p.is_website = '$data->isWebsite'";
        }

        if (isset($data->isService) && $data->isService != null && $data->isService != '') {
            $clauses .= " and p.is_service = '$data->isService'";
        }

        if (isset($data->forSearch) && $data->forSearch != '') {
            $limit .= " limit 20";
        }
        if (isset($data->name) && $data->name != '') {
            $clauses .= " and p.Product_Code like '$data->name%'";
            $clauses .= " or p.Product_Name like '$data->name%'";
        }

        $products = $this->db->query("SELECT
                p.*,
                concat(p.Product_Name, ' - ', p.Product_Code) as display_text,
                pc.Category_Name,
                psc.SubCategory_Name,
                br.brand_name,
                c.color_name,
                u.Unit_Name,
                ua.User_Name as added_by,
                ud.User_Name as deleted_by
            FROM tbl_product p
            LEFT JOIN tbl_category pc on pc.Category_SlNo = p.ProductCategory_ID
            LEFT JOIN tbl_sub_category psc on psc.SubCategory_SlNo = p.ProductSubCategory_ID
            LEFT JOIN tbl_brand br on br.brand_SiNo = p.Brand_ID
            LEFT JOIN tbl_color c on c.color_SiNo = p.Color_ID
            LEFT JOIN tbl_unit u on u.Unit_SlNo = p.Unit_ID
            LEFT JOIN tbl_user ua on ua.User_SlNo = p.AddBy
            LEFT JOIN tbl_user ud on ud.User_SlNo = p.DeletedBy
            WHERE p.status = '$status'
            $clauses
            order by p.Product_SlNo desc
            $limit
        ")->result();

        echo json_encode($products);
    }

    public function checkGalleryImage()
    {
        $res = ['found' => false];
        $data = json_decode($this->input->raw_input_stream);
        $imageCount = $this->db->query("SELECT * FROM tbl_product_gallery WHERE Product_ID = ? and Gallery_Image = ? and status = 'a'", [$data->productId, $data->productImage])->num_rows();
        if ($imageCount != 0) {
            $res = ['found' => true];
        }
        echo json_encode($res);
    }

    public function deletePGalleryImage()
    {
        $res = ['success' => false, 'message' => ''];
        try {
            $data = json_decode($this->input->raw_input_stream);

            $query = $this->db->query("SELECT * FROM tbl_product_gallery WHERE Product_ID = '$data->productId' AND Gallery_Image = '$data->productImage' ");
            if ($query->num_rows() > 0) {

                $oldImgFile = $query->row()->Gallery_Image;
                if (file_exists($oldImgFile)) {
                    unlink('/uploads/product_gallery/' . $oldImgFile);
                }


                // unlink(base_url() . 'uploads/product_gallery/' . $query->row()->Gallery_Image);
                $this->db->query("DELETE FROM tbl_product_gallery WHERE Product_ID = ? AND Gallery_Image = ? ", [$data->productId, $data->productImage]);
            }

            $res = ['success' => true, 'message' => 'Image deleted successfully'];
        } catch (Exception $ex) {
            $res = ['success' => false, 'message' => $ex->getMessage()];
        }

        echo json_encode($res);
    }

    public function getProductImages()
    {
        $data = json_decode($this->input->raw_input_stream);
        $images = $this->db->query("SELECT * FROM tbl_product_gallery WHERE status = 'a' AND Product_ID = ? ", $data->productId)->result();
        echo json_encode($images);
    }

    public function getTransferProductStock()
    {
        $inputs = json_decode($this->input->raw_input_stream);
        $stock = $this->mt->transferBranchStock($inputs->productId, $inputs->branchId);
        echo $stock;
    }
    public function getProductStock()
    {
        $inputs = json_decode($this->input->raw_input_stream);
        $stock = $this->mt->productStock($inputs->productId);
        echo $stock;
    }

    public function getCurrentStock()
    {
        $data = json_decode($this->input->raw_input_stream);

        $clauses = "";
        if (isset($data->stockType) && $data->stockType == 'low') {
            $clauses .= " and current_quantity <= Product_ReOrederLevel";
        }

        if (isset($data->categoryId) && $data->categoryId != '') {
            $clauses .= " and ProductCategory_ID = '$data->categoryId'";
        }

        $stock = $this->mt->currentStock($clauses);
        $res['stock'] = $stock;
        $res['totalValue'] = array_sum(
            array_map(function ($product) {
                return $product->stock_value;
            }, $stock)
        );

        echo json_encode($res);
    }

    public function getTotalStock()
    {
        $data = json_decode($this->input->raw_input_stream);

        $branchId = $this->session->userdata('BRANCHid');
        $clauses = "";
        if (isset($data->categoryId) && $data->categoryId != null) {
            $clauses .= " and p.ProductCategory_ID = '$data->categoryId'";
        }

        if (isset($data->productId) && $data->productId != null) {
            $clauses .= " and p.Product_SlNo = '$data->productId'";
        }

        if (isset($data->brandId) && $data->brandId != null) {
            $clauses .= " and p.Brand_ID = '$data->brandId'";
        }

        $stock = $this->db->query("SELECT
                p.*,
                pc.Category_Name,
                b.brand_name,
                u.Unit_Name,
                (SELECT ifnull(sum(pd.PurchaseDetails_TotalQuantity), 0) 
                    FROM tbl_purchase_details pd 
                    join tbl_purchase_master pm on pm.PurchaseMaster_SlNo = pd.PurchaseMaster_IDNo
                    WHERE pd.Product_IDNo = p.Product_SlNo
                    and pd.branch_id = '$branchId'
                    and pd.status = 'a'
                    " . (isset($data->date) && $data->date != null ? " and pm.PurchaseMaster_OrderDate <= '$data->date'" : "") . "
                ) as purchased_quantity,
                        
                (SELECT ifnull(sum(prd.PurchaseReturnDetails_ReturnQuantity), 0) 
                    FROM tbl_purchase_return_details prd 
                    join tbl_purchase_return pr on pr.PurchaseReturn_SlNo = prd.PurchaseReturn_SlNo
                    WHERE prd.PurchaseReturnDetailsProduct_SlNo = p.Product_SlNo
                    and prd.branch_id= '$branchId'
                    and prd.status = 'a'
                    " . (isset($data->date) && $data->date != null ? " and pr.PurchaseReturn_ReturnDate <= '$data->date'" : "") . "
                ) as purchase_returned_quantity,
                        
                (SELECT ifnull(sum(sd.SaleDetails_TotalQuantity), 0) 
                    FROM tbl_sale_details sd
                    join tbl_sale_master sm on sm.SaleMaster_SlNo = sd.SaleMaster_IDNo
                    WHERE sd.Product_IDNo = p.Product_SlNo
                    and sd.branch_id  = '$branchId'
                    and sd.status = 'a'
                    " . (isset($data->date) && $data->date != null ? " and sm.SaleMaster_SaleDate <= '$data->date'" : "") . "
                ) as sold_quantity,
                        
                (SELECT ifnull(sum(srd.SaleReturnDetails_ReturnQuantity), 0)
                    FROM tbl_sale_return_details srd 
                    join tbl_sale_return sr on sr.SaleReturn_SlNo = srd.SaleReturn_IdNo
                    WHERE srd.SaleReturnDetailsProduct_SlNo = p.Product_SlNo
                    and srd.branch_id = '$branchId'
                    " . (isset($data->date) && $data->date != null ? " and sr.SaleReturn_ReturnDate <= '$data->date'" : "") . "
                ) as sales_returned_quantity,
                        
                (SELECT ifnull(sum(dmd.DamageDetails_DamageQuantity), 0) 
                    FROM tbl_damage_details dmd
                    join tbl_damage dm on dm.Damage_SlNo = dmd.Damage_SlNo
                    WHERE dmd.Product_SlNo = p.Product_SlNo
                    and dmd.status = 'a'
                    and dm.branch_id = '$branchId'
                    " . (isset($data->date) && $data->date != null ? " and dm.Damage_Date <= '$data->date'" : "") . "
                ) as damaged_quantity,
            
                (SELECT ifnull(sum(trd.quantity), 0)
                    FROM tbl_transfer_details trd
                    join tbl_transfer_master tm on tm.transfer_id = trd.transfer_id
                    WHERE trd.product_id = p.Product_SlNo
                    and tm.transfer_from = '$branchId'
                    and tm.status != 'd'
                    " . (isset($data->date) && $data->date != null ? " and tm.transfer_date <= '$data->date'" : "") . "
                ) as transferred_from_quantity,

                (SELECT ifnull(sum(trd.quantity), 0)
                    FROM tbl_transfer_details trd
                    join tbl_transfer_master tm on tm.transfer_id = trd.transfer_id
                    WHERE trd.product_id = p.Product_SlNo
                    and tm.transfer_to = '$branchId'
                    and tm.status = 'a'
                    " . (isset($data->date) && $data->date != null ? " and tm.transfer_date <= '$data->date'" : "") . "
                ) as transferred_to_quantity,
                        
                (SELECT (purchased_quantity + sales_returned_quantity + transferred_to_quantity) - (sold_quantity + purchase_returned_quantity + damaged_quantity + transferred_from_quantity)) as current_quantity,
                (SELECT p.Product_Purchase_Rate * current_quantity) as stock_value
            FROM tbl_product p
            LEFT JOIN tbl_category pc on pc.Category_SlNo = p.ProductCategory_ID
            LEFT JOIN tbl_brand b on b.brand_SiNo = p.Brand_ID
            LEFT JOIN tbl_unit u on u.Unit_SlNo = p.Unit_ID
            WHERE p.status = 'a' 
            AND p.is_service = 'false' 
            $clauses
        ")->result();

        $res['stock'] = $stock;
        $res['totalValue'] = array_sum(
            array_map(function ($product) {
                return $product->stock_value;
            }, $stock)
        );

        echo json_encode($res);
    }

    public function current_stock()
    {
        $access = $this->mt->userAccess();
        if (!$access) {
            redirect(base_url());
        }
        $data['title'] = "Current Stock";
        $data['categories'] = $this->Other_model->branch_wise_category();
        $data['brands'] = $this->Other_model->branch_wise_brand();
        $data['products'] = $this->Product_model->products_by_brunch();
        $data['content'] = $this->load->view('Administrator/stock/current_stock', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function productlist()
    {
        $access = $this->mt->userAccess();
        if (!$access) {
            redirect(base_url());
        }
        $data['title']  = 'Product';
        $this->load->view('Administrator/products/productList', $data);
    }

    public function product_name()
    {
        $this->load->view('Administrator/products/product_name', $data);
    }

    public function barcodeGenerate($productId)
    {
        $data['title'] = "Barcode Generate";
        $product = $this->db->query("SELECT * FROM tbl_product WHERE status = 'a' AND Product_SlNo = ? ", $productId)->row();
        $data['product'] = $product;
        $data['content'] = $this->load->view('Administrator/products/barcode/barcode', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function multibarcodeGenerate()
    {
        $data['title'] = "Multi Barcode Generate";
        $data['content'] = $this->load->view('Administrator/products/barcode/multibarcode', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function multibarcodeStore()
    {
        $data = json_decode($this->input->raw_input_stream);
        if ($this->session->has_userdata('products')) {
            $this->session->unset_userdata('products');
            $this->session->unset_userdata('xAxis');
            $this->session->unset_userdata('yAxis');
            $this->session->unset_userdata('single');
        }

        $this->session->set_userdata('products', $data->products);
        $this->session->set_userdata('xAxis', $data->xAxis);
        $this->session->set_userdata('yAxis', $data->yAxis);
        $this->session->set_userdata('single', $data->single);

        $res = ['status' => true];
        echo json_encode($res);
    }

    public function multibarcodePrint()
    {
        if ($this->session->has_userdata('products')) {
            $data['title'] = "Multi Barcode Generate";
            $data['products'] = $this->session->userdata('products');
            $data['content'] = $this->load->view('Administrator/products/barcode/multibarcodePrint', $data, TRUE);
            $this->load->view('Administrator/index', $data);
        } else {
            redirect("/module/dashboard");
        }
    }

    public function productLedger()
    {
        $access = $this->mt->userAccess();
        if (!$access) {
            redirect(base_url());
        }
        $data['title']  = 'Product Ledger';

        $data['content'] = $this->load->view('Administrator/products/product_ledger', $data, true);
        $this->load->view('Administrator/index', $data);
    }

    public function getProductLedger()
    {
        $data = json_decode($this->input->raw_input_stream);
        $result = $this->db->query("
            SELECT
                'a' as sequence,
                pd.PurchaseDetails_SlNo as id,
                pm.PurchaseMaster_OrderDate as date,
                concat('Purchase - ', pm.PurchaseMaster_InvoiceNo, ' - ', ifnull(s.Supplier_Name, pm.supplierName)) as description,
                pd.PurchaseDetails_Rate as rate,
                pd.PurchaseDetails_TotalQuantity as in_quantity,
                0 as out_quantity
            FROM tbl_purchase_details pd
            join tbl_purchase_master pm on pm.PurchaseMaster_SlNo = pd.PurchaseMaster_IDNo
            LEFT JOIN tbl_supplier s on s.Supplier_SlNo = pm.Supplier_SlNo
            WHERE pd.status = 'a'
            and pd.Product_IDNo = " . $data->productId . "
            and pd.branch_id = " . $this->brunch . "
            
            UNION
            SELECT 
                'b' as sequence,
                sd.SaleDetails_SlNo as id,
                sm.SaleMaster_SaleDate as date,
                concat('Sale - ', sm.SaleMaster_InvoiceNo, ' - ', ifnull(c.Customer_Name, sm.customerName)) as description,
                sd.SaleDetails_Rate as rate,
                0 as in_quantity,
                sd.SaleDetails_TotalQuantity as out_quantity
            FROM tbl_sale_details sd
            join tbl_sale_master sm on sm.SaleMaster_SlNo = sd.SaleMaster_IDNo
            LEFT JOIN tbl_customer c on c.Customer_SlNo = sm.Customer_IDNo
            WHERE sd.status = 'a'
            and sd.Product_IDNo = " . $data->productId . "
            and sd.branch_id = " . $this->brunch . "
            
            UNION
            SELECT 
                'c' as sequence,
                prd.PurchaseReturnDetails_SlNo as id,
                pr.PurchaseReturn_ReturnDate as date,
                concat('Purchase Return - ', pr.PurchaseMaster_InvoiceNo, ' - ', s.Supplier_Name) as description,
                (prd.PurchaseReturnDetails_ReturnAmount / prd.PurchaseReturnDetails_ReturnQuantity) as rate,
                0 as in_quantity,
                prd.PurchaseReturnDetails_ReturnQuantity as out_quantity
            FROM tbl_purchase_return_details prd
            join tbl_purchase_return pr on pr.PurchaseReturn_SlNo = prd.PurchaseReturn_SlNo
            LEFT JOIN tbl_supplier s on s.Supplier_SlNo = pr.Supplier_IDdNo
            WHERE prd.status = 'a'
            and prd.PurchaseReturnDetailsProduct_SlNo = " . $data->productId . "
            and prd.branch_id= " . $this->brunch . "
            
            UNION
            SELECT
                'd' as sequence, 
                srd.SaleReturnDetails_SlNo as id,
                sr.SaleReturn_ReturnDate as date,
                concat('Sale Return - ', sr.SaleMaster_InvoiceNo, ' - ', c.Customer_Name) as description,
                (srd.SaleReturnDetails_ReturnAmount / srd.SaleReturnDetails_ReturnQuantity) as rate,
                srd.SaleReturnDetails_ReturnQuantity as in_quantity,
                0 as out_quantity
            FROM tbl_sale_return_details srd
            join tbl_sale_return sr on sr.SaleReturn_SlNo = srd.SaleReturn_IdNo
            join tbl_sale_master sm on sm.SaleMaster_InvoiceNo = sr.SaleMaster_InvoiceNo
            LEFT JOIN tbl_customer c on c.Customer_SlNo = sm.Customer_IDNo
            WHERE srd.status = 'a'
            and srd.SaleReturnDetailsProduct_SlNo = " . $data->productId . "
            and srd.branch_id = " . $this->brunch . "
            
            UNION
            SELECT
                'e' as sequence, 
                trd.transferdetails_id as id,
                tm.transfer_date as date,
                concat('Transferred From: ', b.Branch_name, ' - ', tm.note) as description,
                0 as rate,
                trd.quantity as in_quantity,
                0 as out_quantity
            FROM tbl_transfer_details trd
            join tbl_transfer_master tm on tm.transfer_id = trd.transfer_id
            join tbl_outlet b on b.branch_id = tm.transfer_from
            WHERE trd.product_id = " . $data->productId . "
            and tm.transfer_to = " . $this->brunch . "
            
            UNION
            SELECT 
                'f' as sequence,
                trd.transferdetails_id as id,
                tm.transfer_date as date,
                concat('Transferred To: ', b.Branch_name, ' - ', tm.note) as description,
                0 as rate,
                0 as in_quantity,
                trd.quantity as out_quantity
            FROM tbl_transfer_details trd
            join tbl_transfer_master tm on tm.transfer_id = trd.transfer_id
            join tbl_outlet b on b.branch_id = tm.transfer_to
            WHERE trd.product_id = " . $data->productId . "
            and tm.transfer_from = " . $this->brunch . "
            
            UNION
            SELECT 
                'g' as sequence,
                dmd.DamageDetails_SlNo as id,
                d.Damage_Date as date,
                concat('Damaged - ', d.Damage_Description) as description,
                0 as rate,
                0 as in_quantity,
                dmd.DamageDetails_DamageQuantity as out_quantity
            FROM tbl_damage_details dmd
            join tbl_damage d on d.Damage_SlNo = dmd.Damage_SlNo
            WHERE dmd.Product_SlNo = " . $data->productId . "
            and d.branch_id = " . $this->brunch . "

            order by date, sequence, id
        ")->result();

        $ledger = array_map(function ($key, $row) use ($result) {
            $row->stock = $key == 0 ? $row->in_quantity - $row->out_quantity : ($result[$key - 1]->stock + ($row->in_quantity - $row->out_quantity));
            return $row;
        }, array_keys($result), $result);

        $previousRows = array_filter($ledger, function ($row) use ($data) {
            return $row->date < $data->dateFrom;
        });

        $previousStock = empty($previousRows) ? 0 : end($previousRows)->stock;

        $ledger = array_filter($ledger, function ($row) use ($data) {
            return $row->date >= $data->dateFrom && $row->date <= $data->dateTo;
        });

        echo json_encode(['ledger' => $ledger, 'previousStock' => $previousStock]);
    }

    public function imageUpload($file_name_get)
    {
        $file_name = $file_name_get['name'];
        $file_temp = $file_name_get['tmp_name'];

        $div = explode('.', $file_name);
        $get_last_e = end($div);
        $new_name =  rand() . '.' . $get_last_e;
        move_uploaded_file($file_temp, 'uploads/product_gallery/' . $new_name);
        return $new_name;
    }
}
