<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class UserModel extends CI_Model
{
 private $table = 'users';

 public $id;
 public $name;
 public $email;
 public $password;

 public $rule = [
        [
        'field' => 'name',
        'label' => 'name',
        'rules' => 'required'
        ],
    ];

 public function Rules() { return $this->rule; }

 public function getAll() { return
    $this->db->get('data_mahasiswa')->result();
 }

 public function store($request) {
    $this->name = $request->name;
    $this->email = $request->email;
    $this->password = password_hash($request->password, PASSWORD_BCRYPT);
    if($this->db->insert($this->table, $this)){
    return ['msg'=>'Berhasil','error'=>false];
    }
    return ['msg'=>'Gagal','error'=>true];
 }

 public function login($request){
    $email = $request->email;
    $password = $request->password;
   //  $query=$this->db->select('password')->where(array('email' => $this->email))->get($this->table)->row();
   $query=$this->db->get_where('users',array('email'=> $email))->result_array();
   // var_dump($query);
    if(!empty($query)){
        if(password_verify($password, $query[0]['password'])){
            $token = AUTHORIZATION::generateToken(['email' => $this->email]);
            return ['msg' => "Berhasil", 'error' => false, 'token' => $token];
        }
    }

    return ['msg' => 'Gagal', 'error' => true, 'token' => null];
}

 public function update($request,$id) {
    $updateData = ['email' => $request->email, 'name' =>$request->name];
    if($this->db->where('id',$id)->update($this->table, $updateData)){
        return ['msg'=>'Berhasil','error'=>false];
    }
    return ['msg'=>'Gagal','error'=>true];
 }

 public function destroy($id){
    if (empty($this->db->select('*')->where(array('id' => $id))->get($this->table)->row())) 
    return ['msg'=>'Id tidak ditemukan','error'=>true];

    if($this->db->delete($this->table, array('id' => $id))){
    return ['msg'=>'Berhasil','error'=>false];
    }
    return ['msg'=>'Gagal','error'=>true];
 }
}
?>
