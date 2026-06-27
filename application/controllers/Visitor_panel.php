<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Visitor_panel extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("Visitor_user_model", "vum", TRUE);
        $this->load->model("Visitor_model", "vm", TRUE);
        $this->require_visitor();
    }

    private function require_visitor()
    {
        if (!$this->session->userdata('visitor_id')) {
            redirect('visitor_auth/login');
        }
    }

    private function current_visitor()
    {
        $visitor = $this->vum->find_active($this->session->userdata('visitor_id'));
        if (!$visitor) {
            $this->session->unset_userdata(array('visitor_id', 'visitor_name', 'visitor_email', 'visitor_phone'));
            redirect('visitor_auth/login');
        }

        return $visitor;
    }

    public function dashboard()
    {
        $visitor = $this->current_visitor();

        $data['title'] = "Visitor Dashboard";
        $data['visitor'] = $visitor;
        $data['applications'] = $this->vm->by_visitor($visitor->id);
        $data['stats'] = $this->vm->visitor_stats($visitor->id);
        $data['content'] = $this->load->view('visitor_panel/dashboard', $data, TRUE);
        $this->load->view('layouts/master_dashboard', $data);
    }

    public function profile()
    {
        $visitor = $this->current_visitor();

        $data['title'] = "Visitor Profile";
        $data['visitor'] = $visitor;
        $data['content'] = $this->load->view('visitor_panel/profile', $data, TRUE);
        $this->load->view('layouts/master_dashboard', $data);
    }

    public function getProfile()
    {
        $res = array('success' => false, 'message' => '', 'profile' => null);
        try {
            $visitor = $this->current_visitor();
            $res = array('success' => true, 'message' => 'Profile loaded', 'profile' => $visitor);
        } catch (Exception $ex) {
            $res = array('success' => false, 'message' => $ex->getMessage(), 'profile' => null);
        }

        echo json_encode($res);
    }

    public function getApplications()
    {
        $res = array('success' => false, 'message' => '', 'applications' => array());
        try {
            $visitor = $this->current_visitor();
            $applications = $this->vm->by_visitor($visitor->id);

            foreach ($applications as $application) {
                $application->tracking_id = visitor_tracking_id($application);
                $application->card_url = $application->status === 'approved' ? site_url('visitor_panel/card/' . $application->id) : '';
            }

            $res = array('success' => true, 'message' => 'Applications loaded', 'applications' => $applications);
        } catch (Exception $ex) {
            $res = array('success' => false, 'message' => $ex->getMessage(), 'applications' => array());
        }

        echo json_encode($res);
    }

    public function card($id)
    {
        $visitor = $this->current_visitor();
        $data['application'] = $this->vm->find_for_logged_visitor((int) $id, $visitor->id);

        if (!$data['application'] || $data['application']->status !== 'approved') {
            show_404();
        }

        $data['title'] = "Visitor Card";
        $data['content'] = $this->load->view('visitor/card', $data, TRUE);
        $this->load->view('layouts/master_dashboard', $data);
    }

    public function updatePhoto()
    {
        $res = array('success' => false, 'message' => '');
        try {
            $visitor = $this->current_visitor();

            $photo_path = null;
            $request = json_decode($this->input->post('data'));

            // Handle base64 photo capture
            if ($request && !empty($request->photo_base64)) {
                $imgData = $request->photo_base64;
                if (preg_match('/^data:image\/(\w+);base64,/', $imgData, $type)) {
                    $imgData = substr($imgData, strpos($imgData, ',') + 1);
                    $type = strtolower($type[1]);
                    if (in_array($type, array('jpg', 'jpeg', 'png'))) {
                        $imgData = base64_decode($imgData);
                        if ($imgData !== false) {
                            $dirName = 'uploads/visitors';
                            if (!file_exists($dirName)) {
                                mkdir($dirName, 0777, true);
                            }
                            $fileNewName = 'visitor_user_' . uniqid() . '.' . $type;
                            $photo_path = $dirName . '/' . $fileNewName;
                            file_put_contents($photo_path, $imgData);
                        }
                    }
                }
            }

            // Handle uploaded file
            if (!$photo_path && !empty($_FILES['photo']['name'])) {
                $this->load->model('Visitor_model', 'vm', TRUE);
                $photo_path = $this->vm->uploadImage($_FILES, 'photo', 'uploads/visitors', 'visitor_user');
            }

            if (!$photo_path) {
                throw new Exception('No image file or camera capture received.');
            }

            // Delete old photo if exists
            if ($visitor->photo && file_exists($visitor->photo)) {
                @unlink($visitor->photo);
            }

            $this->vum->update($visitor->id, array('photo' => $photo_path));

            $res = array(
                'success' => true,
                'message' => 'Profile picture updated successfully.',
                'photo_url' => base_url($photo_path)
            );
        } catch (Exception $ex) {
            $res = array('success' => false, 'message' => $ex->getMessage());
        }

        echo json_encode($res);
    }
}
