<?php
/**
 * Created by Green Studio.
 * File: SystemController.class.php
 * User: TianShuo
 * Date: 14-2-20
 * Time: 下午5:44
 */

namespace Weixin\Controller;


class SystemController extends WeixinBaseController
{
    public function index()
    {
         $this->display();
    }


    public function indexHandle()
    {
        $this->saveConfig();
        $this->success('修改成功');
    }


}