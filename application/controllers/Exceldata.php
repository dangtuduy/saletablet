<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Exceldata extends VS_SaleController {
	public function __construct() 
  	{
    	parent::__construct();
    	$this->load->database();
    	$this->load->model('salesdb');
  	}

	public function import()
	{
		$customer = $this->uri->segment(3);

	    if(isset($_POST['upload']) && $_POST['upload'])
	    {
	      	$this->body['reading'] = $this->ExcelDataAdd();
	      	$this->session->set_userdata('excel', $this->body['reading']);
	    }

	    if(isset($_POST['ConfirmedExcel']) && $_POST['ConfirmedExcel'])
	    {
		    if(isset($customer['custid']))
		    {
		        $reading = $this->session->userdata('excel');
		        $sellingItems = array();

		        foreach ($reading as $key => $value) 
		        {
		        	if($value['qty'] > 0)
		        	{
		        		$sellingItems[$value['item']] = array('name'=>$value['name'], 'qty'=> $value['qty'], 'price'=>0, 'note'=>$value['focfile']);
		          	}
		        }
		        if(count($sellingItems) > 0 )
		        {
		        	$this->session->set_userdata('sellitem', $sellingItems);
		        	$this->session->set_userdata('excel', array());
		        	redirect(site_url('sales/ordernew/2/'.$customer));
		        }
			}
    	}
    	$this->data['content'] = $this->load->view('sub/excelupload', $this->body, TRUE);

    	$this->load->view('mobile', $this->data);
	}

	private function ExcelDataAdd() 
  	{  
	    $this->load->library('excel');//load PHPExcel library
	    //Path of files were you want to upload on localhost (C:/xampp/htdocs/ProjectName/uploads/excel/)  
	    $configUpload['upload_path'] = FCPATH.'uploads/excel/';
	    $configUpload['allowed_types'] = 'xlsx';
	    $configUpload['max_size'] = '5000';
	    $configUpload['file_name'] = generateOID(getChanel());
	    
	    $this->load->library('upload', $configUpload);
	    $this->upload->do_upload('userfile');//<input type="file" name="userfile" />
	    $upload_data = $this->upload->data(); //Returns array of containing all of the data related to the file you uploaded.
	    $file_name = $upload_data['file_name']; //uploaded file name
	    $extension = $upload_data['file_ext'];    // uploaded file extension

	    
	    if(!empty($file_name) && $extension == '.xlsx')
	    {
	      //$objReader =PHPExcel_IOFactory::createReader('Excel5');     //For excel 2003 
	      $objReader= PHPExcel_IOFactory::createReader('Excel2007'); // For excel 2007
	      //Set to read only
	      $objReader->setReadDataOnly(true);      
	      //Load excel file
	      $objPHPExcel=$objReader->load(FCPATH.'uploads/excel/'.$file_name);    
	      $totalrows=$objPHPExcel->setActiveSheetIndex(0)->getHighestRow();   //Count Numbe of rows avalable in excel         
	      $objWorksheet=$objPHPExcel->setActiveSheetIndex(0);

	      //loop from first data untill last data
	      $reading = array();
	      for($i=2;$i<=$totalrows;$i++)
	      {
	        $itemCode = $objWorksheet->getCellByColumnAndRow(0, $i)->getValue();
	        $barcode = $objWorksheet->getCellByColumnAndRow(1, $i)->getValue();

	        if(empty($itemCode) && empty($barcode))
	          break;

	        $qty = $objWorksheet->getCellByColumnAndRow(2, $i)->getValue();
	        $itemname = $objWorksheet->getCellByColumnAndRow(3, $i)->getValue();
	        $focnote = $objWorksheet->getCellByColumnAndRow(4, $i)->getValue();

	        $iteminfo = $this->salesdb->getItemInfo($itemCode);

	        $reading[] = array('item'=>$itemCode, 'barcode'=>$barcode, 'qty'=>$qty, 'namefile'=> $itemname, 'name'=>(isset($iteminfo['itemname']) ? $iteminfo['itemname'] : ''), 'focfile'=>$focnote);
	      }
	      return $reading;
	    }
	    if(file_exists(FCPATH.'uploads/excel/'.$file_name))
	    	unlink(FCPATH.'uploads/excel/'.$file_name); //File Deleted After uploading in database .
	    return array();
	    //redirect(base_url() . "put link were you want to redirect");
	}
}
