<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class LayananModel extends CI_Model
{
 private $table = 'services';
 public $id;
 public $name;
 public $price;
 public $type;
 public $created_at;
 public $rule = [
 [
    'field' => 'name',
    'label' => 'name',
    'rules' => 'required'
 ],
 [
    'field' => 'price',
    'label' => 'price',
    'rules' => 'required|numeric'
 ],
 [
    'field' => 'type',
    'label' => 'type',
    'rules' => 'required'
 ]
 ];
 public function Rules() { return $this->rule; }

 public function getAll() { return
 $this->db->get('services')->result();
 }
 public function store($request) {
 $this->name = $request->name;
 $this->price = $request->price;
 $this->type = $request->type;
 $this->created_at = $request->created_at;
//  $this->password = password_hash($request->password, PASSWORD_BCRYPT);
 if($this->db->insert($this->table, $this)){
 return ['msg'=>'Berhasil','error'=>false];
 }
 return ['msg'=>'Gagal','error'=>true];
 }
 public function update($request,$id) {
 $updateData = ['name' => $request->name, 
                'price' =>$request->price, 
                'type' =>$request->type, 
                'created_at' =>$request->created_at];

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
