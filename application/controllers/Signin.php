<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Signin extends CI_Controller {

	public function index()
	{
        $this->load->database();
        $this->load->model('userdb');
        $this->lang->load('common', getLang());
        
        if( $this->session->userdata('isLoggedIn') ) 
        {
            $preurl = $this->session->userdata('preurl');
            redirect(empty($preurl) ? site_url('sales') : $preurl);
        } 
        else 
        {
			$this->load->helper('cookie');
            $data = array();
            if(isset($_POST['Login']))
            {
                $email = $this->input->post('email');
                $pass  = $this->input->post('password');
                if(!empty($email) && !empty($pass))
                {
                    $user = $this->userdb->checkLogin($email,md5($pass));
                    if(count($user) > 0 && isset($user['email']))
                    {
                        $this->set_session($user);
                        $preurl = $this->session->userdata('preurl');
						$remember = $_POST['remember'];
						//print_r($_POST);
						//print $remember;
						if($remember == 'on')
						{ 
							set_cookie('aff-saletablet-email', $email, time()+3600, $_SERVER['HTTP_HOST']);
							set_cookie('aff-saletablet-pass', $pass, time()+3600, $_SERVER['HTTP_HOST']);
						}
						if($user['usergroup'] == USER_SALEMAN)
							redirect(empty($preurl) ? site_url('sales/ordernew') : $preurl);
						else
							redirect(site_url('backoffice'));
                    }
                    else
                        $data['error'] = $this->lang->line('login_failed');
                }
                else
                    $data['error'] = $this->lang->line('login_blank');
            }
			else
			{
				$email = get_cookie('aff-saletablet-email');
				$pass = get_cookie('aff-saletablet-pass');
				$data['email'] = $email;
				$data['pass'] = $pass;
				//print_r($data);
			}
            $this->load->view('signin', $data);    
        }
	}
    
    function logout()
    {
        //log_message('debug','Userauth: Logout: '.$this->object->session->userdata('username'));
		$sessdata = array('email'=>'', 'isLoggedIn'=>'false', 'fullname'=>'', 'userid'=>'', 'outlet'=>'', 'chanel'=>'', 'managedby'=>'', 'preurl'=>'');
		$this->session->unset_userdata($sessdata);
		$this->session->sess_destroy();
        redirect(site_url('user/login'));
    }
    
    private function set_session($user = array()) 
    {
        $this->session->set_userdata( array(
                'userid'=> $user['uid'],
                'fullname'=> $user['fullname'],
                'email'=> $user['email'],
                'salecode'=> $user['salecode'],
                'group'=> isset($user['usergroup'])?$user['usergroup']:'',
                'isLoggedIn'=>true,
				'outlet'=> (isset($user['outlet']) ? $user['outlet'] : ''),
				'chanel'=> (isset($user['chanel']) ? $user['chanel'] : ''),
				'managedby'=> (isset($user['managedby']) ? $user['managedby'] : '')
            )
        );
    }
    
 }