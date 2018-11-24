<?php
/**
 * IronPHP : PHP Development Framework
 * Copyright (c) IronPHP (https://github.com/IronPHP/IronPHP)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @package       IronPHP
 * @copyright     Copyright (c) IronPHP (https://github.com/IronPHP/IronPHP)
 * @link          
 * @since         0.0.1
 * @license       MIT License (https://opensource.org/licenses/mit-license.php)
 * @auther        GaurangKumar Parmar <gaurangkumarp@gmail.com>
 */

namespace App\Model;

use Friday\Model\ModelService;

class IndexModel extends ModelService
{
    /**
     * Variable.
     *
     * @var array
     */
    public $data = ['name' => 'GK'];

    /**
     * Get data from table.
     *
     * @return void
     */
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

    /**
     * Add data to table.
     *
     * @return void
     */
    public function add()
    {
        return $this->table('user')->add(['id'=>3, 'name'=>'pihu', 'user'=>'pihu', 'password'=>'123', 'status'=>1, ]);
        //return $this->table('user')->add([3, 'pihu', 'pihu', '123', 1 ]);
        //return $this->table('user')->add([3, 'pihu', 'pihu', '123', 1]);
        //return $this->table('user')->add(3, 'pihu', 'pihu', '123', 1);
    }

    /**
     * Update data from table.
     *
     * @return void
     */
    public function update()
    {
        return $this->table('user')->where(['id'=>1])->update(['status'=>1, 'name'=>'illu']);
    }

    /**
     * Delete data from table.
     *
     * @return void
     */
    public function delete()
    {
        return $this->table('user')->where(['id'=>1])->delete();
    }
}