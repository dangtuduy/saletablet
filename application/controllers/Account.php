<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends VS_Controller {

	function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('userdb');
    }
    function usermng()
    {
        if(isLogin() && isAdmin())
        {
            $get = getURLString();
            $email = isset($get['user']) ? $get['user'] :'';    
            
            if(empty($email))
                $this->adduser();
            else
                $this->edituser($email);
        }
        else
        {
            $this->data['content'] = lang('<h4 class="center">You are not authorized on the function.</h4>');
            $this->load->view('backoffice', $this->data);
        }
    }
    private function adduser()
    {
        if(isset($_POST['AddUser']) && $_POST['AddUser'] == TRUE)
        {
            $email = $this->input->post('email');
            $fullname = $this->input->post('fullname');
            $pass = $this->input->post('pass');
            $repass = $this->input->post('repass');
            $group = isset($_POST['group']) ? $_POST['group'] : '';
            $salecode = $this->input->post('salecode');
			$outlet = isset($_POST['outlet']) ? $_POST['outlet'] : '';
			$chanel = isset($_POST['chanel']) ? $_POST['chanel'] : '';
			$managedby = isset($_POST['managedby']) ? $_POST['managedby'] : '';

            
            if(empty($pass) || $repass != $pass)
                $this->body['error'] = lang('Password is invalid');
            else if(empty($email) || empty($fullname) || empty($pass) || empty($group))
                $this->body['error'] = lang('Empty data');
                
            $user = array('email'=>$email, 'password'=> md5($pass), 'fullname'=> $fullname, 'usergroup'=>$group, 'outlet'=>$outlet, 'chanel'=>$chanel,
                                'managedby'=>$managedby, 'salecode'=>$salecode, 'active'=>true, 'createddate'=> nowdatetime());
            if($this->userdb->hasEmail($email))
                $this->body['error'] = lang('Email '.$email.' is existed.');   
            else
            {
                if($this->userdb->addUser($user))
                {
                    $this->body['ok'] = true;
                    goredirect(site_url('admin/user').(empty($outlet) ? '' : '?outlet='.$outlet), 'refresh', '3');
                }
                else
                    $this->body['ok'] = false;
            }
            $this->body['userinfo'] = $user;
        }

        $get = getURLString();
        $outlet = isset($get['outlet']) ? $get['outlet'] : 'hcm';
        $findEmail = isset($get['se']) ? $get['se'] : '';

        $this->body['alluser'] = $this->userdb->getalluser(0, 100, strtoupper($outlet), $findEmail);
        $this->body['sroutlet'] = $outlet;
        $this->body['usergroup'] = $this->config->item('usergroup');
        $this->data['content'] = $this->load->view('sub/mnguser', $this->body, true);
        $this->load->view('backoffice', $this->data);
    }
    
    private function edituser($urlemail)
    {
        $get = getURLString();
        $outlet = isset($get['outlet']) ? $get['outlet'] : 'hcm';
        $findEmail = isset($get['se']) ? $get['se'] : '';

        $urlemail = rawurldecode($urlemail);
        
        if(!empty($urlemail) && isset($_POST['UpdateUser']) && $_POST['UpdateUser'] == TRUE)
        {
            $email = $this->input->post('email');
            $fullname = $this->input->post('fullname');
            $pass = $this->input->post('pass');
            $repass = $this->input->post('repass');
            $group = isset($_POST['group']) ? $_POST['group'] : '';
			$outlet = isset($_POST['outlet']) ? $_POST['outlet'] : '';
			$chanel = isset($_POST['chanel']) ? $_POST['chanel'] : '';
			$managedby = isset($_POST['managedby']) ? $_POST['managedby'] : '';
            $salecode = $this->input->post('salecode');
			
            $active = $this->input->post('active');
			if(isset($active))
				$active = 1;
			else
				$active = 0;
			
            if($urlemail != $email)
                $this->body['error'] = lang('Email is invalid');
            else if($repass != $pass)
                $this->body['error'] = lang('Password is invalid');
            else if(empty($email) || empty($fullname) || empty($group))
                $this->body['error'] = lang('Empty data');
            else
            {
                $user = array('email'=>$email, 'fullname'=> $fullname, 'usergroup'=>$group, 'outlet'=>$outlet, 'chanel'=>$chanel,
                                'managedby'=>$managedby, 'salecode'=>$salecode, 'active'=>$active);
                if(!empty($pass))
                    $user['password'] = md5($pass);
                if($this->userdb->updateUser($user, $email))
                {
                    $this->body['ok'] = true;
                    goredirect(site_url('admin/user').(empty($outlet) ? '' : '?outlet='.$outlet), 'refresh', '3');
                }
                else
                    $this->body['ok'] = false;      
            }
        }

        $this->body['alluser'] = $this->userdb->getalluser(0, 100, strtoupper($outlet), $findEmail);
        $this->body['sroutlet'] = $outlet;
        $this->body['email'] = $urlemail;
        $this->body['userinfo'] = $this->userdb->getInfo($urlemail, -1);
        $this->body['usergroup'] = $this->config->item('usergroup');
        $this->data['content'] = $this->load->view('sub/mnguser', $this->body, true);
        $this->load->view('backoffice', $this->data);
    }
    function profile()
    {
        if(isLogin())
        {
            $email = $this->session->userdata('email');
            if(!empty($email))
            {
                $userinfo = $this->userdb->getInfo($email);
                $this->body['userinfo'] = $userinfo;
            }
            if(isset($userinfo['uid']) && isset($_POST['Update']) && $_POST['Update'] == true)
            {
                $curpass = xss_clean($this->input->post('curpass'));
                $newpass1 = xss_clean($this->input->post('newpass1'));
                $newpass2 = xss_clean($this->input->post('newpass2'));
            
                if($newpass1 == $newpass2 && !empty($newpass1))
                {
                    if(isset($userinfo['password']) && $userinfo['password'] == md5($curpass))
                    {
                        $user = array('password'=> md5($newpass1));
                        if($this->userdb->updateUser($user, $email))
                        {
                            $this->body['message'] = lang('Update success').'!!';
                            $sessdata = array('email'=>'', 'isLoggedIn'=>'false', 'fullname'=>'', 'userid'=>'', 'outlet'=>'', 'chanel'=>'', 'manageby'=>'', 'preurl'=>'');
                            $this->session->unset_userdata($sessdata);
                            goredirect(site_url('user/login'), 'refresh', '2');
                        }
                        else
                            $this->body['message'] = lang('Failed updating');
                    }
                    else
                        $this->body['message'] = lang('Current password incorrect');
                }
            }
            $this->data['content'] = $this->load->view('sub/profile', $this->body, TRUE);
            $this->load->view('mobile', $this->data);
        }
    }
}
