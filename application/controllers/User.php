<?php
use chriskacerguis\RestServer\RestController;

Class User extends RestController{
 public function __construct(){
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");
    header("Access-Control-Allow-Headers: Authorization, Content-Type, ContentLength, Accept-Encoding");
    parent::__construct();
    $this->load->model('UserModel');
    $this->load->library('form_validation');
    $this->load->helper(['jwt', 'authorization']);
 }

 public function index_get(){
    return $this->returnData($this->db->get('users')->result(), false);
 }

 public function login_post(){
    $user = new UserData();
    $user->password = $this->post('password');
    $user->email = $this->post('email');
    
    $response = $this->UserModel->login($user);
   

    return $this->returnData($response['msg'], $response['error'], $response['token']);
}

 public function index_post($id = null){
    $validation = $this->form_validation;
    $rule = $this->UserModel->rules();

    if($id == null){
    array_push($rule,[
            'field' => 'password',
            'label' => 'password',
            'rules' => 'required'
        ],
        [
            'field' => 'email',
            'label' => 'email',
            'rules' => 'required|valid_email|is_unique[users.email]'
        ]
        );
    }
    else{
        array_push($rule,
        [
        'field' => 'email',
        'label' => 'email',
        'rules' => 'required|valid_email'
        ]
        );
    }
    $validation->set_rules($rule);
    if (!$validation->run()) {
    return $this->returnData($this->form_validation->error_array(), true, null);
    }

    $user = new UserData();
    $user->name = $this->post('name');
    $user->password = $this->post('password');
    $user->email = $this->post('email');
    if($id == null){
    $response = $this->UserModel->store($user);
    }else{
    $response = $this->UserModel->update($user,$id);
    }
    return $this->returnData($response['msg'], $response['error'], $response['null']);
 }

 public function index_delete($id = null){
    if($id == null){
    return $this->returnData('Parameter Id Tidak Ditemukan', true);
    }
    $response = $this->UserModel->destroy($id);
    return $this->returnData($response['msg'], $response['error'], $response['null']);
 }

//  public function returnData($msg,$error, $token)
 public function returnData($msg,$error, $token){
 $response['error']=$error;
 $response['message']=$msg;
 $response['token'] = $token;
 
 return $this->response($response);
 }
}
Class UserData{
 public $name;
 public $password;
 public $email;
}
