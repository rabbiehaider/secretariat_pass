<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Website extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
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
        $data['title'] = "Update About";
        $data['content'] = $this->load->view('Administrator/website/about', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function websiteContent()
    {
        $access = $this->mt->userAccess();
        if (!$access) {
            redirect(base_url());
        }
        $data['title'] = "Website Profile";
        $data['content'] = $this->load->view('Administrator/website/website_profile', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function getWebsiteProfile()
    {
        $profiles =  $this->db->query("SELECT * FROM tbl_website_profile WHERE UpdateBy IS NOT NULL")->result();
        echo json_encode($profiles);
    }

    public function updateWebsiteProfile()
    {
        $res = ['success' => false, 'message' => ''];
        try {
            $websiteObj = json_decode($this->input->post('profile'));

            $profile = (array)$websiteObj;
            $websiteId = $websiteObj->Website_SlNo;

            unset($profile["Website_SlNo"]);
            $profile["branch_id"] = $this->session->userdata("BRANCHid");
            $profile["UpdateBy"] = $this->session->userdata("userId");
            $profile["UpdateTime"] = date("Y-m-d H:i:s");
            $profile["last_update_ip"] = get_client_ip();

            $this->db->where('Website_SlNo', $websiteId)->update('tbl_website_profile', $profile);


            $webRow = $this->db->query("SELECT * FROM tbl_website_profile WHERE Website_SlNo = ?", $websiteId)->row();
            if (!empty($_FILES['hLogo'])) {
                $oldHFile = $webRow->Header_Logo;
                if (file_exists($oldHFile)) {
                    unlink($oldHFile);
                }
                $hLogoPath = $this->mt->uploadImage($_FILES, 'hLogo', 'uploads/websites', rand(111111, 999999));
                $this->db->query("UPDATE tbl_website_profile SET Header_Logo = ? WHERE Website_SlNo = ?", [$hLogoPath, $websiteId]);
            }

            if (!empty($_FILES['ftLogo'])) {
                $oldFtFile = $webRow->Footer_Logo;
                if (file_exists($oldFtFile)) {
                    unlink($oldFtFile);
                }
                $ftLogoPath = $this->mt->uploadImage($_FILES, 'ftLogo', 'uploads/websites', rand(111111, 999999));
                $this->db->query("UPDATE tbl_website_profile SET Footer_Logo = ? WHERE Website_SlNo = ?", [$ftLogoPath, $websiteId]);
            }

            if (!empty($_FILES['mLogo'])) {
                $oldMFile = $webRow->Mobile_Logo;
                if (file_exists($oldMFile)) {
                    unlink($oldMFile);
                }
                $mLogoPath = $this->mt->uploadImage($_FILES, 'mLogo', 'uploads/websites', rand(111111, 999999));
                $this->db->query("UPDATE tbl_website_profile SET Mobile_Logo = ? WHERE Website_SlNo = ?", [$mLogoPath, $websiteId]);
            }

            if (!empty($_FILES['fLogo'])) {
                $oldFFile = $webRow->Fav_Logo;
                if (file_exists($oldFFile)) {
                    unlink($oldFFile);
                }
                $fLogoPath = $this->mt->uploadImage($_FILES, 'fLogo', 'uploads/websites', rand(111111, 999999));
                $this->db->query("UPDATE tbl_website_profile SET Fav_Logo = ? WHERE Website_SlNo = ?", [$fLogoPath, $websiteId]);
            }

            $res = ['success' => true, 'message' => 'Website Profile updated successfully!'];
        } catch (Exception $ex) {
            $res = ['success' => false, 'message' => $ex->getMessage()];
        }

        echo json_encode($res);
    }

    public function webPageContent()
    {
        $access = $this->mt->userAccess();
        if (!$access) {
            redirect(base_url());
        }
        $data['title'] = "Website Page Content";
        $data['content'] = $this->load->view('Administrator/website/web_page_content', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }









    public function getAbout()
    {
        $about =  $this->db->query("select * from tbl_abouts")->row();
        echo json_encode($about);
    }

    // image upload
    public function image_upload($file_name_get)
    {
        $file_name = $file_name_get['name'];
        $file_temp = $file_name_get['tmp_name'];

        $div = explode('.', $file_name);
        $get_last_e = end($div);
        $new_name =  rand() . '.' . $get_last_e;
        move_uploaded_file($file_temp, 'uploads/About/' . $new_name);
        return $new_name;
    }

    public function update()
    {
        $res = ['success' => false, 'message' => ''];
        try {
            $about = json_decode($this->input->post('about'));
            $aboutId = $about->id;

            // image update
            $image = '';
            $oldImage = $this->db->query("SELECT * FROM tbl_abouts WHERE id = ?
            ", $aboutId)->row();
            if (isset($_FILES['image'])) {
                $image = $this->image_upload($_FILES['image']);
                if ($oldImage->image != null) {
                    $img_unlink = 'uploads/About/' . $oldImage->image;
                    unlink($img_unlink);
                }
            } else {
                $image = $oldImage->image;
            }

            $aboutData = array(
                'title' => $about->title,
                'about_us' => $about->about_us,
                'mission' => $about->mission,
                'vision' => $about->vision,
                'value' => $about->value,
                'video_url' => $about->video_url,
                'image' => $image,
                'update_by' => $this->session->userdata("FullName"),
                'update_time' => date('Y-m-d H:i:s'),
            );
            $this->db->set($aboutData)->where('id', $aboutId)->update('tbl_abouts');
            $res = ['success' => true, 'message' => 'About Information Update'];
        } catch (\Exception $e) {
            $res = ['success' => false, 'message' => $e->getMessage()];
        }

        echo json_encode($res);
    }

    public function contact()
    {
        $access = $this->mt->userAccess();
        if (!$access) {
            redirect(base_url());
        }
        $data['title'] = "Contact List";
        $data['content'] = $this->load->view('Administrator/website/contact', $data, TRUE);
        $this->load->view('Administrator/index', $data);
    }

    public function getContacts()
    {
        $contacts =  $this->db->query("select * from tbl_contacts where status = 'a'")->result();
        echo json_encode($contacts);
    }

    public function deleteContact()
    {
        $res = ['success' => false, 'message' => ''];
        try {
            $data = json_decode($this->input->raw_input_stream);

            $this->db->set(['status' => 'd'])->where('id', $data->contactId)->update('tbl_contacts');

            $res = ['success' => true, 'message' => 'Message delete successfully'];
        } catch (Exception $e) {
            $res = ['success' => false, 'message' => $e->getMessage()];
        }

        echo json_encode($res);
    }

    public function getContent()
    {
        $content =  $this->db->query("select * from tbl_website_profile")->row();
        echo json_encode($content);
    }

    public function updateContent()
    {
        $res = ['success' => false, 'message' => ''];
        try {

            $content = json_decode($this->input->post('content'));
            $contentId = $content->id;

            // image update
            $image = '';
            $oldImage = $this->db->query("SELECT * FROM tbl_website_profile WHERE id = ?", $contentId)->row();
            if (isset($_FILES['image'])) {
                $image = $this->image_upload($_FILES['image']);
                if ($oldImage->logo != null) {
                    $img_unlink = 'uploads/About/' . $oldImage->logo;
                    unlink($img_unlink);
                }
            } else {
                $image = $oldImage->logo;
            }

            $contentData = array(
                'name' => $content->name,
                'email' => $content->email,
                'phone' => $content->phone,
                'address' => $content->address,
                'soft_url' => $content->soft_url,
                'details' => $content->details,
                'logo' => $image,
                'opening' => $content->opening,
                'vacation' => $content->vacation,
                'facebook' => $content->facebook,
                'instagram' => $content->instagram,
                'twitter' => $content->twitter,
                'update_by' => $this->session->userdata("FullName"),
                'update_time' => date('Y-m-d H:i:s'),
            );
            $this->db->set($contentData)->where('id', $contentId)->update('tbl_website_profile');
            $res = ['success' => true, 'message' => 'Content Information Update'];
        } catch (\Exception $e) {
            $res = ['success' => false, 'message' => $e->getMessage()];
        }

        echo json_encode($res);
    }
}
