<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Page extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->brunch = $this->session->userdata('BRANCHid');
        $access = $this->session->userdata('userId');
        if ($access == '') {
            redirect("Login");
        }
        $this->load->model('Billing_model');
        $this->load->model("Model_myclass", "mmc", TRUE);
        $this->load->model('Model_table', "mt", TRUE);
        $this->load->model('Billing_model');
        date_default_timezone_set('Asia/Dhaka');

        if ($this->session->has_userdata('products')) {
            $this->session->unset_userdata('products');
            $this->session->unset_userdata('xAxis');
            $this->session->unset_userdata('yAxis');
            $this->session->unset_userdata('single');
        }
    }
    public function index()
    {
        $data['title'] = "Dashboard";
        $data['content'] = $this->load->view('Administrator/dashboard', $data, TRUE);
        $this->load->view('Administrator/master_dashboard', $data);
    }
    public function module($value)
    {
        $data['title'] = "Dashboard";

        $sdata['module'] = $value;
        $this->session->set_userdata($sdata);

        $data['content'] = $this->load->view('Administrator/dashboard', $data, TRUE);
        $this->load->view('Administrator/master_dashboard', $data);
    }

    // Product Category 
    public function getCategories()
    {
        $categories = $this->db->query("SELECT * FROM tbl_category WHERE status = 'a' ORDER BY Category_SlNo DESC")->result();
        echo json_encode($categories);
    }
    public function category()
    {
        $access = $this->mt->userAccess();
        if (!$access) {
            redirect(base_url());
        }
        $data['title'] = "Add Category";
        $data['content'] = $this->load->view('Administrator/add_category', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }
    public function addCategory()
    {
        $res = ['status' => false];
        try {
            // $data = json_decode($this->input->raw_input_stream);
            $data = json_decode($this->input->post('data'));
            $query = $this->db->query("SELECT * from tbl_category where Category_Name = '$data->Category_Name'")->row();
            if (!empty($query)) {
                $categoryId = $query->Category_SlNo;
                $category = array(
                    'status'         => 'a',
                    "UpdateBy"       => $this->session->userdata("userId"),
                    "UpdateTime"     => date("Y-m-d H:i:s"),
                    "last_update_ip" => get_client_ip()
                );
                $this->db->where('Category_SlNo', $categoryId);
                $this->db->update('tbl_category', $category);
            } else {
                $category = array(
                    "Category_Name"  => $data->Category_Name,
                    "is_home"        => $data->is_home == true ? 'true' : 'false',
                    "route"          => $data->route,
                    "status"         => 'a',
                    "AddBy"          => $this->session->userdata("userId"),
                    "AddTime"        => date("Y-m-d H:i:s"),
                    "last_update_ip" => get_client_ip(),
                    "branch_id"      => $this->brunch
                );
                $this->db->insert('tbl_category', $category);
                $categoryId = $this->db->insert_id();
            }

            if (!empty($_FILES['image'])) {
                $imagePath = $this->mt->uploadImage($_FILES, 'image', 'uploads/categories', rand(111111, 999999));
                $this->db->query("UPDATE tbl_category SET Category_Image = ? where Category_SlNo = ?", [$imagePath, $categoryId]);
            }

            if (!empty($_FILES['icon'])) {
                $iconPath = $this->mt->uploadImage($_FILES, 'icon', 'uploads/categories', rand(111111, 999999));
                $this->db->query("UPDATE tbl_category SET Category_Icon = ? where Category_SlNo = ?", [$iconPath, $categoryId]);
            }

            $res = ['status' => true, 'message' => 'Category added successfully'];
        } catch (\Throwable $th) {
            $res = ['status' => false, 'message' => $th->getMessage()];
        }

        echo json_encode($res);
    }
    public function updateCategory()
    {
        $res = ['status' => false];
        try {
            // $data = json_decode($this->input->raw_input_stream);
            $data = json_decode($this->input->post('data'));

            $query = $this->db->query("SELECT * FROM tbl_category where Category_Name =  ? AND Category_SlNo != ? ", [$data->Category_Name,  $data->Category_SlNo])->row();
            if (!empty($query)) {
                $categoryId = $query->Category_SlNo;
                $category = array(
                    'status'         => 'a',
                    "UpdateBy"       => $this->session->userdata("userId"),
                    "UpdateTime"     => date("Y-m-d H:i:s"),
                    "last_update_ip" => get_client_ip()
                );
                $this->db->where('Category_SlNo', $categoryId);
                $this->db->update('tbl_category', $category);
            } else {
                $category = array(
                    "Category_Name"  => $data->Category_Name,
                    "is_home"        => $data->is_home == true ? 'true' : 'false',
                    "route"          => $data->route,
                    "UpdateBy"       => $this->session->userdata("userId"),
                    "UpdateTime"     => date("Y-m-d H:i:s"),
                    "last_update_ip" => get_client_ip()
                );
                $this->db->where('Category_SlNo', $data->Category_SlNo);
                $this->db->update('tbl_category', $category);
            }

            $catRow = $this->db->query("SELECT * FROM tbl_category WHERE Category_SlNo = ?", $data->Category_SlNo)->row();
            if (!empty($_FILES['image'])) {
                $oldImgFile = $catRow->Category_Image;
                if (file_exists($oldImgFile)) {
                    unlink($oldImgFile);
                }
                $imagePath = $this->mt->uploadImage($_FILES, 'image', 'uploads/categories', rand(111111, 999999));
                $this->db->query("UPDATE tbl_category SET Category_Image = ? WHERE Category_SlNo = ?", [$imagePath, $data->Category_SlNo]);
            }

            if (!empty($_FILES['icon'])) {
                $oldIconFile = $catRow->Category_Icon;
                if (file_exists($oldIconFile)) {
                    unlink($oldIconFile);
                }
                $iconPath = $this->mt->uploadImage($_FILES, 'icon', 'uploads/categories', rand(111111, 999999));
                $this->db->query("UPDATE tbl_category SET Category_Icon = ? WHERE Category_SlNo = ?", [$iconPath, $data->Category_SlNo]);
            }

            $res = ['status' => true, 'message' => 'Category update successfully'];
        } catch (\Throwable $th) {
            $res = ['status' => false, 'message' => $th->getMessage()];
        }
        echo json_encode($res);
    }
    public function deleteCategory()
    {
        $data = json_decode($this->input->raw_input_stream);

        $subCatCount = $this->db->query("SELECT * from tbl_sub_category where Category_SlNo = ? and status = 'a'", $data->categoryId)->num_rows();

        if ($subCatCount != 0) {
            $res = ['success' => false, 'message' => 'Unable to delete. Sub-Category found.'];
            echo json_encode($res);
            exit;
        }

        $category = array(
            'status'         => 'd',
            "DeletedBy"      => $this->session->userdata("userId"),
            "DeletedTime"    => date("Y-m-d H:i:s"),
            "last_update_ip" => get_client_ip()
        );
        $this->db->where('Category_SlNo', $data->categoryId);
        $this->db->update('tbl_category', $category);
        echo json_encode(['status' => true, 'message' => 'Category delete successfully']);
    }
    //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

    // Product Sub-Category
    public function getSubCategories()
    {
        $subcategories = $this->db->query("SELECT 
                sc.*, 
                c.Category_Name
            FROM tbl_sub_category sc
            JOIN tbl_category c ON c.Category_SlNo = sc.Category_SlNo
            WHERE sc.status = 'a' 
            ORDER BY sc.SubCategory_SlNo DESC")->result();
        echo json_encode($subcategories);
    }
    public function subCategory()
    {
        $access = $this->mt->userAccess();
        if (!$access) {
            redirect(base_url());
        }
        $data['title'] = "Add Sub-Category";
        $data['content'] = $this->load->view('Administrator/add_sub_category', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }
    public function addSubCategory()
    {
        $res = ['status' => false];
        try {
            $data = json_decode($this->input->raw_input_stream);
            $query = $this->db->query("SELECT * FROM tbl_sub_category where Category_SlNo = ? AND SubCategory_Name =  ?", [$data->Category_SlNo, $data->SubCategory_Name])->row();
            if (!empty($query)) {
                $subCategoryId = $query->SubCategory_SlNo;
                $subCategory = array(
                    'status'         => 'a',
                    "UpdateBy"       => $this->session->userdata("userId"),
                    "UpdateTime"     => date("Y-m-d H:i:s"),
                    "last_update_ip" => get_client_ip()
                );
                $this->db->where('SubCategory_SlNo', $subCategoryId);
                $this->db->update('tbl_sub_category', $subCategory);
            } else {
                $subCategory = array(
                    "Category_SlNo"    => $data->Category_SlNo,
                    "SubCategory_Name" => $data->SubCategory_Name,
                    "route"            => $data->route,
                    "status"           => 'a',
                    "AddBy"            => $this->session->userdata("userId"),
                    "AddTime"          => date("Y-m-d H:i:s"),
                    "last_update_ip"   => get_client_ip(),
                    "branch_id"        => $this->brunch
                );
                $this->db->insert('tbl_sub_category', $subCategory);
            }

            $res = ['status' => true, 'message' => 'Sub-Category added successfully'];
        } catch (\Throwable $th) {
            $res = ['status' => false, 'message' => $th->getMessage()];
        }

        echo json_encode($res);
    }
    public function updateSubCategory()
    {
        $res = ['status' => false];
        try {
            $data = json_decode($this->input->raw_input_stream);

            $query = $this->db->query("SELECT * FROM tbl_sub_category where Category_SlNo = ? AND SubCategory_Name =  ? AND SubCategory_SlNo != ? ", [$data->Category_SlNo, $data->SubCategory_Name, $data->SubCategory_SlNo])->row();
            if (!empty($query)) {
                $subCategoryId = $query->SubCategory_SlNo;
                $subCategory = array(
                    'status'         => 'a',
                    "UpdateBy"       => $this->session->userdata("userId"),
                    "UpdateTime"     => date("Y-m-d H:i:s"),
                    "last_update_ip" => get_client_ip()
                );
                $this->db->where('SubCategory_SlNo', $subCategoryId);
                $this->db->update('tbl_sub_category', $subCategory);
            } else {
                $subCategory = array(
                    "Category_SlNo"    => $data->Category_SlNo,
                    "SubCategory_Name" => $data->SubCategory_Name,
                    "route"            => $data->route,
                    "UpdateBy"         => $this->session->userdata("userId"),
                    "UpdateTime"       => date("Y-m-d H:i:s"),
                    "last_update_ip"   => get_client_ip()
                );
                $this->db->where('SubCategory_SlNo', $data->SubCategory_SlNo);
                $this->db->update('tbl_sub_category', $subCategory);
            }

            $res = ['status' => true, 'message' => 'Sub-Category update successfully'];
        } catch (\Throwable $th) {
            $res = ['status' => false, 'message' => $th->getMessage()];
        }
        echo json_encode($res);
    }
    public function deleteSubCategory()
    {
        $data = json_decode($this->input->raw_input_stream);
        $subCategory = array(
            'status'         => 'd',
            "DeletedBy"      => $this->session->userdata("userId"),
            "DeletedTime"    => date("Y-m-d H:i:s"),
            "last_update_ip" => get_client_ip()
        );
        $this->db->where('SubCategory_SlNo', $data->subCategoryId);
        $this->db->update('tbl_sub_category', $subCategory);
        echo json_encode(['status' => true, 'message' => 'Sub-Category delete successfully']);
    }
    //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
    // unit 
    public function unit()
    {
        $access = $this->mt->userAccess();
        if (!$access) {
            redirect(base_url());
        }
        $data['title'] = "Add Unit";
        $data['content'] = $this->load->view('Administrator/unit', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }
    public function insert_unit()
    {
        $res = ['status' => false];
        try {
            $data = json_decode($this->input->raw_input_stream);
            $query = $this->db->query("SELECT * from tbl_unit where Unit_Name = '$data->Unit_Name'")->row();
            if (!empty($query)) {
                $unit = array(
                    'status' => 'a',
                    "UpdateBy" => $this->session->userdata("userId"),
                    "UpdateTime" => date("Y-m-d H:i:s"),
                    "last_update_ip" => get_client_ip()
                );
                $this->db->where('Unit_SlNo', $query->Unit_SlNo);
                $this->db->update('tbl_unit', $unit);
            } else {
                $unit = array(
                    "Unit_Name" => $data->Unit_Name,
                    "status" => 'a',
                    "AddBy" => $this->session->userdata("userId"),
                    "AddTime" => date("Y-m-d H:i:s"),
                    "last_update_ip" => get_client_ip()
                );
                $this->db->insert('tbl_unit', $unit);
            }

            $res = ['status' => true, 'message' => 'Unit added successfully'];
        } catch (\Throwable $th) {
            $res = ['status' => false, 'message' => $th->getMessage()];
        }

        echo json_encode($res);
    }
    public function unitupdate()
    {
        $res = ['status' => false];
        try {
            $data = json_decode($this->input->raw_input_stream);
            $unit = array(
                "Unit_Name" => $data->Unit_Name,
                "UpdateBy" => $this->session->userdata("userId"),
                "UpdateTime" => date("Y-m-d H:i:s"),
                "last_update_ip" => get_client_ip()
            );
            $this->db->where('Unit_SlNo', $data->Unit_SlNo);
            $this->db->update('tbl_unit', $unit);

            $res = ['status' => true, 'message' => 'Unit update successfully'];
        } catch (\Throwable $th) {
            $res = ['status' => false, 'message' => $th->getMessage()];
        }
        echo json_encode($res);
    }
    public function unitdelete()
    {
        $data = json_decode($this->input->raw_input_stream);
        $unit = array(
            'status' => 'd',
            "DeletedBy" => $this->session->userdata("userId"),
            "DeletedTime" => date("Y-m-d H:i:s"),
            "last_update_ip" => get_client_ip()
        );
        $this->db->where('Unit_SlNo', $data->unitId);
        $this->db->update('tbl_unit', $unit);
        echo json_encode(['status' => true, 'message' => 'Unit delete successfully']);
    }

    public function getUnits()
    {
        $units = $this->db->query("SELECT * from tbl_unit where status = 'a' order by Unit_SlNo desc")->result();
        echo json_encode($units);
    }
    //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
    //District 
    public function district()
    {
        $access = $this->mt->userAccess();
        if (!$access) {
            redirect(base_url());
        }
        $data['title'] = "District Entry";
        $data['content'] = $this->load->view('Administrator/add_district', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }
    public function insert_district()
    {
        $res = ['status' => false];
        try {
            $data = json_decode($this->input->raw_input_stream);
            $query = $this->db->query("SELECT * from tbl_district where District_Name = '$data->District_Name'")->row();
            if (!empty($query)) {
                $district = array(
                    'status' => 'a',
                    "UpdateBy" => $this->session->userdata("userId"),
                    "UpdateTime" => date("Y-m-d H:i:s"),
                    "last_update_ip" => get_client_ip()
                );
                $this->db->where('District_SlNo', $query->District_SlNo);
                $this->db->update('tbl_district', $district);
            } else {
                $district = array(
                    "District_Name" => $data->District_Name,
                    "AddBy" => $this->session->userdata("userId"),
                    "AddTime" => date("Y-m-d H:i:s"),
                    "last_update_ip" => get_client_ip()
                );
                $this->db->insert('tbl_district', $district);
            }

            $res = ['status' => true, 'message' => 'District added successfully'];
        } catch (\Throwable $th) {
            $res = ['status' => false, 'message' => $th->getMessage()];
        }

        echo json_encode($res);
    }

    public function districtupdate()
    {
        $res = ['status' => false];
        try {
            $data = json_decode($this->input->raw_input_stream);
            $district = array(
                "District_Name" => $data->District_Name,
                "UpdateBy" => $this->session->userdata("userId"),
                "UpdateTime" => date("Y-m-d H:i:s"),
                "last_update_ip" => get_client_ip()
            );
            $this->db->where('District_SlNo', $data->District_SlNo);
            $this->db->update('tbl_district', $district);

            $res = ['status' => true, 'message' => 'District update successfully'];
        } catch (\Throwable $th) {
            $res = ['status' => false, 'message' => $th->getMessage()];
        }
        echo json_encode($res);
    }
    public function districtdelete()
    {
        $data = json_decode($this->input->raw_input_stream);
        $district = array(
            'status' => 'd',
            "DeletedBy" => $this->session->userdata("userId"),
            "DeletedTime" => date("Y-m-d H:i:s"),
            "last_update_ip" => get_client_ip()
        );
        $this->db->where('District_SlNo', $data->districtId);
        $this->db->update('tbl_district', $district);
        echo json_encode(['status' => true, 'message' => 'District delete successfully']);
    }

    public function getDistricts()
    {
        $districts = $this->db->query("SELECT * from tbl_district d where d.status = 'a' order by District_SlNo desc")->result();
        echo json_encode($districts);
    }
    
    //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
    // Thana Entry
    public function getThanas()
    {
        $thanas = $this->db->query("SELECT 
                th.*, 
                d.District_Name
            FROM tbl_thana th
            JOIN tbl_district d ON d.District_SlNo = th.District_SlNo
            WHERE th.status = 'a' 
            ORDER BY th.Thana_SlNo DESC")->result();
        echo json_encode($thanas);
    }
    public function thana()
    {
        $access = $this->mt->userAccess();
        if (!$access) {
            redirect(base_url());
        }
        $data['title'] = "Thana Entry";
        $data['content'] = $this->load->view('Administrator/add_thana', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }
    public function insert_thana()
    {
        $res = ['status' => false];
        try {
            $data = json_decode($this->input->raw_input_stream);
            $query = $this->db->query("SELECT * FROM tbl_thana where District_SlNo = ? AND Thana_Name =  ?", [$data->District_SlNo, $data->Thana_Name])->row();
            if (!empty($query)) {
                $thanaId = $query->Thana_SlNo;
                $thanaData = array(
                    'status'         => 'a',
                    "UpdateBy"       => $this->session->userdata("userId"),
                    "UpdateTime"     => date("Y-m-d H:i:s"),
                    "last_update_ip" => get_client_ip()
                );
                $this->db->where('Thana_SlNo', $thanaId);
                $this->db->update('tbl_thana', $thanaData);
            } else {
                $thanaData = array(
                    "District_SlNo"  => $data->District_SlNo,
                    "Thana_Name"     => $data->Thana_Name,
                    "status"         => 'a',
                    "AddBy"          => $this->session->userdata("userId"),
                    "AddTime"        => date("Y-m-d H:i:s"),
                    "last_update_ip" => get_client_ip(),
                    "branch_id"      => $this->brunch
                );
                $this->db->insert('tbl_thana', $thanaData);
            }

            $res = ['status' => true, 'message' => 'Thana added successfully'];
        } catch (\Throwable $th) {
            $res = ['status' => false, 'message' => $th->getMessage()];
        }

        echo json_encode($res);
    }
    public function thanaupdate()
    {
        $res = ['status' => false];
        try {
            $data = json_decode($this->input->raw_input_stream);

            $query = $this->db->query("SELECT * FROM tbl_thana where District_SlNo = ? AND Thana_Name =  ? AND Thana_SlNo != ? ", [$data->District_SlNo, $data->Thana_Name, $data->Thana_SlNo])->row();
            if (!empty($query)) {
                $thanaId = $query->Thana_SlNo;
                $thanaData = array(
                    'status'         => 'a',
                    "UpdateBy"       => $this->session->userdata("userId"),
                    "UpdateTime"     => date("Y-m-d H:i:s"),
                    "last_update_ip" => get_client_ip()
                );
                $this->db->where('Thana_SlNo', $thanaId);
                $this->db->update('tbl_thana', $thanaData);
            } else {
                $thanaData = array(
                    "District_SlNo"  => $data->District_SlNo,
                    "Thana_Name"     => $data->Thana_Name,
                    "UpdateBy"       => $this->session->userdata("userId"),
                    "UpdateTime"     => date("Y-m-d H:i:s"),
                    "last_update_ip" => get_client_ip()
                );
                $this->db->where('Thana_SlNo', $data->Thana_SlNo);
                $this->db->update('tbl_thana', $thanaData);
            }

            $res = ['status' => true, 'message' => 'Thana update successfully'];
        } catch (\Throwable $th) {
            $res = ['status' => false, 'message' => $th->getMessage()];
        }
        echo json_encode($res);
    }
    public function thanadelete()
    {
        $data = json_decode($this->input->raw_input_stream);
        $thanaData = array(
            'status'         => 'd',
            "DeletedBy"      => $this->session->userdata("userId"),
            "DeletedTime"    => date("Y-m-d H:i:s"),
            "last_update_ip" => get_client_ip()
        );
        $this->db->where('Thana_SlNo', $data->thanaId);
        $this->db->update('tbl_thana', $thanaData);
        echo json_encode(['status' => true, 'message' => 'Thana delete successfully']);
    }
    // Country 
    public function add_country()
    {
        $data['title'] = "Add Country";
        $data['content'] = $this->load->view('Administrator/add_country', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function insert_country()
    {
        $mail = $this->input->post('Country');
        $query = $this->db->query("SELECT CountryName from tbl_country where CountryName = '$mail'");

        if ($query->num_rows() > 0) {
            echo "F";
        } else {
            $data = array(
                "CountryName" => $this->input->post('Country', TRUE),
                "AddBy" => $this->session->userdata("userId"),
                "AddTime" => date("Y-m-d H:i:s")
            );
            $this->mt->save_data('tbl_country', $data);
            $this->load->view('Administrator/ajax/Country');
        }
    }

    public function countryedit($id)
    {
        $data['title'] = "Edit Country";
        $fld = 'Country_SlNo';
        $data['selected'] = $this->mt->select_by_id('tbl_country', $id, $fld);
        $data['content'] = $this->load->view('Administrator/edit/country_edit', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }
    public function countryupdate()
    {
        $id = $this->input->post('id');
        $fld = 'Country_SlNo';
        $data = array(
            "CountryName" => $this->input->post('Country', TRUE),
            "UpdateBy" => $this->session->userdata("userId"),
            "UpdateTime" => date("Y-m-d H:i:s")
        );
        $this->mt->update_data("tbl_country", $data, $id, $fld);
        $this->load->view('Administrator/ajax/Country');
    }
    public function countrydelete()
    {
        $id = $this->input->post('deleted');
        $fld = 'Country_SlNo';
        $this->mt->delete_data("tbl_country", $id, $fld);
        $this->load->view('Administrator/ajax/Country');
    }
    //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
    //Company Profile
    public function getCompanyProfile()
    {
        $companyProfile = $this->db->query("SELECT * from tbl_company order by Company_SlNo desc limit 1")->row();
        echo json_encode($companyProfile);
    }

    public function company_profile()
    {
        $access = $this->mt->userAccess();
        if (!$access) {
            redirect(base_url());
        }
        $data['title'] = "Company Profile";
        $data['selected'] = $this->db->query("
            SELECT * from tbl_company order by Company_SlNo desc limit 1
        ")->row();
        $data['content'] = $this->load->view('Administrator/company_profile', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function company_profile_insert()
    {
        $id = $this->brunch;
        $inpt = $this->input->post('inpt', true);
        $fld = 'branch_id';
        $this->load->library('upload');
        $config['upload_path'] = './uploads/company_profile_org/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size'] = '10000';
        $config['image_width'] = '4000';
        $config['image_height'] = '4000';
        $this->upload->initialize($config);

        $data['Company_Name'] = $this->input->post('Company_name', true);
        $data['Repot_Heading'] = $this->input->post('Description', true);

        $xx = $this->mt->select_by_id("tbl_company", $id, $fld);

        $image = $this->upload->do_upload('companyLogo');
        $images = $this->upload->data();

        if ($image != "") {
            if ($xx['Company_Logo_thum'] && $xx['Company_Logo_thum']) {
                unlink("./uploads/company_profile_thum/" . $xx['Company_Logo_thum']);
                unlink("./uploads/company_profile_org/" . $xx['Company_Logo_thum']);
            }
            $data['Company_Logo_thum'] = $images['file_name'];

            $config['image_library'] = 'gd2';
            $config['source_image'] = $this->upload->upload_path . $this->upload->file_name;
            $config['new_image'] = 'uploads/' . 'company_profile_thum/' . $this->upload->file_name;
            $config['maintain_ratio'] = FALSE;
            $config['width'] = 165;
            $config['height'] = 175;
            $this->load->library('image_lib', $config);
            $this->image_lib->resize();
            $data['Company_Logo_thum'] = $this->upload->file_name;
        } else {

            $data['Company_Logo_thum'] = $xx['Company_Logo_thum'];
            $data['Company_Logo_thum'] = $xx['Company_Logo_thum'];
        }
        $data['print_type'] = $inpt;
        $data['branch_id'] = $this->brunch;
        $this->mt->save_data("tbl_company", $data, $id, $fld);
        $id = '1';
        redirect('Administrator/Page/company_profile');
    }

    public function company_profile_Update()
    {
        $inpt = $this->input->post('inpt', true);
        $data['Company_Name'] = $this->input->post('Company_name', true);
        $data['InvoiceHeder'] = $this->input->post('InvoiceHeder', true);
        $data['Currency_Name'] = $this->input->post('Currency_Name', true);
        $data['SubCurrency_Name'] = $this->input->post('SubCurrency_Name', true);
        $data['InvoiceNote'] = $this->input->post('InvoiceNote', true);
        $data['Repot_Heading'] = $this->input->post('Description', true);
        $data['dueStatus'] = $this->input->post('dueStatus', true);
        $data['last_update_ip'] = get_client_ip();

        $xx = $this->db->query("SELECT * from tbl_company order by Company_SlNo desc limit 1")->row();


        if (!isset($_FILES['companyLogo']) || $_FILES['companyLogo']['error'] == UPLOAD_ERR_NO_FILE) {
            $data['print_type'] = $inpt;
            $this->db->update('tbl_company', $data);
            $id = '1';
            redirect('Administrator/Page/company_profile');
        } else {
            if (file_exists($xx->Company_Logo_thum)) {
                unlink($xx->Company_Logo_thum);
            }
            if (file_exists($xx->Company_Logo_thum)) {
                unlink($xx->Company_Logo_thum);
            }
            $thumPath = $this->mt->uploadImage($_FILES, 'companyLogo', 'uploads/company_profile_thum', "");
            $data['Company_Logo_thum'] = $thumPath;
            $orgPath = $this->mt->uploadImage($_FILES, 'companyLogo', 'uploads/company_profile_org', "");
            $data['Company_Logo_org'] = $orgPath;

            $data['print_type'] = $inpt;
            $this->db->update('tbl_company', $data);
            $id = '1';
            redirect('Administrator/Page/company_profile');
        }
    }
    //^^^^^^^^^^^^^^^^^^^^^
    // Brunch Name

    public function getBranches()
    {
        $branches = $this->db->query("
            SELECT 
            *,
            case status
                when 'a' then 'Active'
                else 'Inactive'
            end as active_status
            from tbl_outlet
        ")->result();
        echo json_encode($branches);
    }

    public function getCurrentBranch()
    {
        $branch = $this->Billing_model->company_branch_profile($this->brunch);
        echo json_encode($branch);
    }

    public function changeBranchstatus()
    {
        $res = ['success' => false, 'message' => ''];
        try {
            $data = json_decode($this->input->raw_input_stream);
            $status = $this->db->query("SELECT * from tbl_outlet where branch_id = ?", $data->branchId)->row()->status;
            $status = $status == 'a' ? 'd' : 'a';
            $this->db->set('status', $status)->where('branch_id', $data->branchId)->update('tbl_outlet');
            $res = ['success' => true, 'message' => 'status changed'];
        } catch (Exception $ex) {
            $res = ['success' => false, 'message' => $ex->getMessage()];
        }

        echo json_encode($res);
    }

    public function branch()
    {
        $access = $this->mt->userAccess();
        if (!$access) {
            redirect(base_url());
        }
        $data['title'] = "Add Brunch";
        $data['content'] = $this->load->view('Administrator/brunch/add_branch', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }
    public function addBranch()
    {
        $res = ['success' => false, 'message' => ''];
        try {
            $branch = json_decode($this->input->raw_input_stream);

            $nameCount = $this->db->query("SELECT * from tbl_outlet where Branch_name = ?", $branch->name)->num_rows();
            if ($nameCount > 0) {
                $res = ['success' => false, 'message' => $branch->name . ' already exists'];
                echo json_encode($res);
                exit;
            }

            $newBranch = array(
                'Branch_name' => $branch->name,
                'Branch_title' => $branch->title,
                'Branch_phone' => $branch->phone,
                'Branch_address' => $branch->address,
                'Branch_sales' => '2',
                'AddBy' => $this->session->userdata("userId"),
                'AddTime' => date('Y-m-d H:i:s'),
                'status' => 'a',
                'last_update_ip' => get_client_ip(),
            );

            $this->db->insert('tbl_outlet', $newBranch);
            $res = ['success' => true, 'message' => 'Branch added'];
        } catch (Exception $ex) {
            $res = ['success' => false, 'message' => $ex->getMessage()];
        }

        echo json_encode($res);
    }

    public function updateBranch()
    {
        $res = ['success' => false, 'message' => ''];
        try {
            $branch = json_decode($this->input->raw_input_stream);

            $nameCount = $this->db->query("SELECT * from tbl_outlet where Branch_name = ? and branch_id != ?", [$branch->name, $branch->branchId])->num_rows();
            if ($nameCount > 0) {
                $res = ['success' => false, 'message' => $branch->name . ' already exists'];
                echo json_encode($res);
                exit;
            }

            $newBranch = array(
                'Branch_name' => $branch->name,
                'Branch_title' => $branch->title,
                'Branch_phone' => $branch->phone,
                'Branch_address' => $branch->address,
                'UpdateBy' => $this->session->userdata("userId"),
                'UpdateTime' => date('Y-m-d H:i:s'),
                'last_update_ip' => get_client_ip(),
            );

            $this->db->set($newBranch)->where('branch_id', $branch->branchId)->update('tbl_outlet');
            $res = ['success' => true, 'message' => 'Branch updated'];
        } catch (Exception $ex) {
            $res = ['success' => false, 'message' => $ex->getMessage()];
        }

        echo json_encode($res);
    }

    //^^^^^^^^^^^^^^^^^^^^^^^^

    public function getColors()
    {
        $colors = $this->db->query("SELECT * FROM tbl_color WHERE status = 'a'")->result();
        echo json_encode($colors);
    }

    public function add_color()
    {
        $access = $this->mt->userAccess();
        if (!$access) {
            redirect(base_url());
        }
        $data['title'] = "Add Color";
        $data['content'] = $this->load->view('Administrator/add_color', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function insert_color()
    {
        $res = ['status' => false];
        try {
            $data = json_decode($this->input->raw_input_stream);
            $query = $this->db->query("SELECT * FROM tbl_color WHERE color_name = '$data->color_name' and branch_id = ?", [$this->session->userdata('BRANCHid')])->row();
            if (!empty($query)) {
                $color = array(
                    'status'     => 'a',
                    'UpdateBy'   => $this->session->userdata("userId"),
                    'UpdateTime' => date("Y-m-d H:i:s"),
                );
                $this->db->where('color_SiNo', $query->color_SiNo);
                $this->db->update('tbl_color', $color);
            } else {
                $color = array(
                    'color_name' => $data->color_name,
                    'status'     => 'a',
                    'AddBy'      => $this->session->userdata("userId"),
                    'AddTime'    => date('Y-m-d H:i:s'),
                    'branch_id'  => $this->session->userdata('BRANCHid')
                );
                $this->db->insert('tbl_color', $color);
            }

            $res = ['status' => true, 'message' => 'Color added successfully'];
        } catch (\Throwable $th) {
            $res = ['status' => false, 'message' => $th->getMessage()];
        }

        echo json_encode($res);
    }

    public function colorupdate()
    {
        $res = ['status' => false];
        try {
            $data = json_decode($this->input->raw_input_stream);
            $color = array(
                'color_name' => $data->color_name,
                'UpdateBy'   => $this->session->userdata("userId"),
                'UpdateTime' => date("Y-m-d H:i:s"),
            );
            $this->db->where('color_SiNo', $data->color_SiNo);
            $this->db->update('tbl_color', $color);

            $res = ['status' => true, 'message' => 'Color update successfully'];
        } catch (\Throwable $th) {
            $res = ['status' => false, 'message' => $th->getMessage()];
        }
        echo json_encode($res);
    }

    public function colordelete()
    {
        $data = json_decode($this->input->raw_input_stream);
        $color = array(
            'status'     => 'd',
            'UpdateBy'   => $this->session->userdata("userId"),
            'UpdateTime' => date("Y-m-d H:i:s"),
        );
        $this->db->where('color_SiNo', $data->colorId);
        $this->db->update('tbl_color', $color);
        echo json_encode(['status' => true, 'message' => 'Color delete successfully']);
    }
    //^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

    public function getBrands()
    {
        $brands = $this->db->query("SELECT * from tbl_brand where status = 'a'")->result();
        echo json_encode($brands);
    }

    public function add_brand()
    {
        $access = $this->mt->userAccess();
        if (!$access) {
            redirect(base_url());
        }
        $data['title'] = "Add Brand";
        $data['content'] = $this->load->view('Administrator/add_brand', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function insert_brand()
    {
        $res = ['status' => false];
        try {
            $data = json_decode($this->input->raw_input_stream);
            $query = $this->db->query("SELECT * from tbl_brand where brand_name = '$data->brand_name' and branch_id = ?", [$this->session->userdata('BRANCHid')])->row();
            if (!empty($query)) {
                $brand = array(
                    'status' => 'a'
                );
                $this->db->where('brand_SiNo', $query->brand_SiNo);
                $this->db->update('tbl_brand', $brand);
            } else {
                $brand = array(
                    "brand_name" => $data->brand_name,
                    "status" => 'a',
                    'branch_id' => $this->session->userdata('BRANCHid')
                );
                $this->db->insert('tbl_brand', $brand);
            }

            $res = ['status' => true, 'message' => 'Brand added successfully'];
        } catch (\Throwable $th) {
            $res = ['status' => false, 'message' => $th->getMessage()];
        }

        echo json_encode($res);
    }

    public function Update_brand()
    {
        $res = ['status' => false];
        try {
            $data = json_decode($this->input->raw_input_stream);
            $brand = array(
                "brand_name" => $data->brand_name,
            );
            $this->db->where('brand_SiNo', $data->brand_SiNo);
            $this->db->update('tbl_brand', $brand);

            $res = ['status' => true, 'message' => 'Brand update successfully'];
        } catch (\Throwable $th) {
            $res = ['status' => false, 'message' => $th->getMessage()];
        }
        echo json_encode($res);
    }

    public function branddelete()
    {
        $data = json_decode($this->input->raw_input_stream);
        $brand = array(
            'status' => 'd'
        );
        $this->db->where('brand_SiNo', $data->brandId);
        $this->db->update('tbl_brand', $brand);
        echo json_encode(['status' => true, 'message' => 'Brand delete successfully']);
    }

    public function databaseBackup()
    {
        $access = $this->mt->userAccess();
        if (!$access) {
            redirect(base_url());
        }
        $data['title'] = "Database Backup";
        $data['content'] = $this->load->view('Administrator/database_backup', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function getMotherApiContent()
    {
        echo 'Welcome to Farabie Group.';
    }
}
