<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Master extends MX_Controller {
	function __construct() {
        parent::__construct();
        $this->load->model('Loginmodel');
    }
    function index(){
        $params = $this->input->get();
        $data['redir'] = isset($params['redir']) ? $params['redir'] : '';

        $this->load->view('Login/view', $data);
    }
    
    function do_login(){
        $response['status'] = 0;
        $params = $this->input->post();
        if(isset($params['email']) && !empty($params['password'])) {
            $user = $this->check_userpass($params['email'], $params['password']);
            $id_pegawai_level = !empty($user->id_pegawai_level) ? $user->id_pegawai_level : 0;
            $user_level = $this->get_user_permission($id_pegawai_level);
            
            if($user) {
                $data_session = array(
                        "id_user" => $user->id,
                        "nama_user" => $user->nama,
                        "id_user_level" => $user_level->id,
                        "user_permission" => json_decode($user_level->permission),
                        "is_logged_in" => 1
                    );
                $this->session->set_userdata($data_session);
                $response['status'] = 1;
            }
        } 
        echo json_encode($response);
    }
    function do_logout(){
        unset($_SESSION['id_user']);
        unset($_SESSION['id_user_level']);
        unset($_SESSION['nama_user']);
        unset($_SESSION['user_permission']);
        $_SESSION['is_logged_in'] = 0; 

        redirect('index/login');
    }

    private function check_userpass($email, $password){
        $condition = array(
                'deleted' => 1,
                'email' => $email,
                'password' => hash('sha512', $password),
                );
        $data = $this->Loginmodel->select($condition, 'm_pegawai')->row();
        return $data;
    }
    private function get_user_permission($id_pegawai_level=0){
        $condition = array(
                'deleted'   => 1,
                'id'        => $id_pegawai_level,
                );
        $data = $this->Loginmodel->select($condition, 'm_pegawai_level')->row();
        return $data;
    }
    
}