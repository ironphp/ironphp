<?php

namespace App\Model;

use Friday\Model\ModelService;

class IndexModel extends ModelService
{
    public $data = ['name' => 'GK'];

    public function get($field = null, $id = null)
    {
        if($id == null || (is_array($id) && count($id) == 0)) {
            if($field == null) {
                return $this->table('user')->get();
            }
            else {
                return $this->table('user')->get('name');
            }
        }
        else {
            if($field == null) {
                return $this->table('user')->where($id)->get();
            }
            else {
                return $this->table('user')->where($id)->get('name');
            }
        }
    }
    public function add()
    {
        //return $this->table('user')->add(['id'=>3, 'name'=>'pihu', 'user'=>'pihu', 'password'=>'123', 'status'=>1, ]);
        //return $this->table('user')->add([3, 'pihu', 'pihu', '123', 1 ]);
        //return $this->table('user')->add([3, 'pihu', 'pihu', '123', 1]);
        //return $this->table('user')->add(3, 'pihu', 'pihu', '123', 1);
    }
    public function update()
    {
        return $this->table('user')->where(['id'=>1])->update(['status'=>1, 'name'=>'illu']);
    }
    public function delete()
    {
        return $this->table('user')->where(['id'=>1])->delete();
    }
}
