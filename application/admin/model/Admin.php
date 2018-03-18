<?php

namespace app\admin\model;

use think\Model;

class Admin extends Model
{
    //获取器，实现时间转换
    public function getLastTimeAttr($val)
    {
        return date('Y/m/d', $val);
    }
}
