<?php
class Userdb extends CI_Model{

    private $_table = "user";
    
    function __contruct(){
        parent::__construct();
        //$this->load->database();
    }

    function checkLogin($email, $password)
    {
        $this->db->where("email", $email);
        $this->db->where("password", $password);
        $this->db->where("active", 1);
        $query = $this->db->get($this->_table);
        if($query->num_rows()==0)
            return array();
        return $query->row_array();        
    }
    
    function getalluser($offset , $limit, $outlet ='', $email = '', $active = -1)
    {
        $this->load->database();
        $this->db->from($this->_table);
        
        if($active > 0)
            $this->db->where("active", $active);
        
        if(!empty($outlet) && empty($email))
            $this->db->where('outlet', $outlet);

        if(!empty($email))
            $this->db->like('email', $email);

        $this->db->limit($offset, $limit);
        $this->db->order_by("active DESC, outlet DESC, chanel DESC, usergroup ASC, email ASC");
        $query = $this->db->get();
        $data = $query->result_array();
        return $data;
    }
    
    function getActiveSalesman($offset , $limit = 100, $outlet = '', $channel = '', $active = 1)
    {
        $this->load->database();
        $this->db->from($this->_table);
        
        $this->db->where('usergroup', USER_SALEMAN);

        if($active > 0)
            $this->db->where("active", $active);
        
        if(!empty($outlet))
            $this->db->where('outlet', $outlet);

        if(!empty($channel))
            $this->db->where('chanel', $channel);

        $this->db->limit($offset, $limit);
        $this->db->order_by("outlet DESC, chanel DESC, usergroup ASC, email ASC");
        $query = $this->db->get();
        $data = $query->result_array();
        return $data;
    }

    function getInfo($email, $active = 1)
    {
        $this->db->where("email", $email);
		if($active != -1)
			$this->db->where("active", $active);
        $query = $this->db->get($this->_table);
        
        if($query)
            return $query->row_array();
        else
            return array();
    }

    function addUser($data)
    {
        if($this->db->insert($this->_table,$data))
            return TRUE;
        else
            return FALSE;
    }

    function deleteUser($email)
    {
        if($id!='admin')
        {
            $this->db->where("email",$email);
            $this->db->delete($this->_table);
        }
    }

    function updateUser($data,$email){
        $this->db->where("email",$email);
        if($this->db->update($this->_table,$data))
            return TRUE;
        else
            return FALSE;
    }
    
    //--- Kiem tra userid và key
    function checkActive($userid,$key){
         if($userid!="" && $key!=""){
            
            $this->db->where("userid",$userid);
            $this->db->where("md5(salt)",$key);
            $query = $this->db->get($this->_table);
            if($query->num_rows()!=0){
                
                return $query->row_array();
                
            }else{
                return FALSE;
            }
            
        }else{
            return FALSE;
        }
    }
    
    function hasEmail($email)
    {
        $this->load->database();
        $this->db->where("email",$email);
        $query = $this->db->get($this->_table);   
        if($query->num_rows()!=0)
        {
            return TRUE;
        }
        return FALSE;
    }
    
    
}
?>
