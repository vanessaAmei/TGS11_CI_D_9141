<?php
use chriskacerguis\RestServer\RestController;

Class Layanan extends RestController{
 public function __construct(){
 header('Access-Control-Allow-Origin: *');
 header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
 header("Access-Control-Allow-Headers: Content-Type, ContentLength, Accept-Encoding");
 parent::__construct();
 $this->load->model('LayananModel');
 $this->load->library('form_validation');
 }
 public function index_get(){
 return $this->returnData($this->db->get('services')->result(), false);
 }

 public function index_post($id = null){
 $validation = $this->form_validation;
 $rule = $this->LayananModel->rules();
 
//  if($id == null){
//  array_push($rule,[
//  'field' => 'name',
//  'label' => 'name',
//  'rules' => 'required'
//  ],
//  [
//  'field' => 'price',
//  'label' => 'price',
//  'rules' => 'required'
//  ],
//  [
//     'field' => 'type',
//     'label' => 'type',
//     'rules' => 'required'
//  ],
// [
//     'field' => 'created_at',
//     'label' => 'created_at',
//     'rules' => 'required'
// ]
//  );
//  }
//  else{
//  array_push($rule,
// [
// 'field' => 'name',
// 'label' => 'name',
// 'rules' => 'required'
// ],
// [
// 'field' => 'price',
// 'label' => 'price',
// 'rules' => 'required'
// ],
// [
//     'field' => 'type',
//     'label' => 'type',
//     'rules' => 'required'
// ],
// [
//     'field' => 'created_at',
//     'label' => 'created_at',
//     'rules' => 'required'
// ]
//  );
//  }
 $validation->set_rules($rule);
 if (!$validation->run()) {
 return $this->returnData($this->form_validation->error_array(), true);
 }
 $layanan = new LayananData();
 $layanan->name = $this->post('name');
 $layanan->price = $this->post('price');
 $layanan->type = $this->post('type');
 $layanan->created_at = date('Y-m-d H:i:s');
 if($id == null){
 $response = $this->LayananModel->store($layanan);
 }else{
 $response = $this->LayananModel->update($layanan,$id);
 }
 return $this->returnData($response['msg'], $response['error']);
 }
 public function index_delete($id = null){
 if($id == null){
 return $this->returnData('Parameter Id Tidak Ditemukan', true);
 }
 $response = $this->LayananModel->destroy($id);
 return $this->returnData($response['msg'], $response['error']);
 }
 public function returnData($msg,$error){
 $response['error']=$error;
 $response['message']=$msg;
 return $this->response($response);
 }
}
Class LayananData{
 public $name;
 public $type;
 public $price;
 public $created_at;
}
