<?php
/**
 * Created by Green Studio.
 * File: PostsLogic.class.php
 * User: TianShuo
 * Date: 14-1-15
 * Time: 下午11:59
 */

namespace Common\Logic;
use Think\Model\RelationModel;

/**
 * Class PostsLogic
 * @package Home\Logic
 */
class PostsLogic extends RelationModel
{

    /**
     * @param $id 文章id或者其他识别符
     * @param bool $relation 是否关联其他信息
     * @param array $info_with 强制传入的判断条件
     *
     * @return mixed 如果找到返回数组
     */
    public function detail($id, $relation = true, $info_with = array())
    {
        $info = $info_with;
        $info['post_id|post_name'] = $id;
        if (!array_key_exists('post_status', $info)) $info['post_status'] = 'publish';

        $post_res = D('Posts')->where($info)->relation($relation)->find();
        return $post_res;
    }

    /**
     * @param string $type 文章类型默认是single
     * @param string $order 排序方式默认是id
     * @param int $limit 数量限制 默认20 一般我们会传入(string)limit用于分页
     * @param bool $relation 是否关联其他信息
     * @param array $ids 需要限制的id
     * @param array $info_with 强制传入的判断条件
     *
     * @return mixed 返回文章列表
     */
    public function getList($limit = 20, $type = 'single', $order = 'post_id desc',
                            $relation = true, $info_with = array(),$ids = array())
    {
        $info = $info_with;
        if ($type != 'all') $info['post_type'] = $type;
        if (!array_key_exists('post_status', $info)) $info['post_status'] = 'publish';
        if (!empty($ids)) $info['post_id'] = array('in', $ids);

        $post_list = D('Posts')->where($info)->order('post_top desc ,' . $order)->limit($limit)->relation($relation)->select();
        return $post_list;
    }

    /**
     * @param string $type
     * @param array $info_with
     * @param array $ids 需要限制的id
     *
     * @return int
     */
    public function countAll($type = 'single', $info_with = array(), $ids = array())
    {
        $info = $info_with;
        if (!array_key_exists('post_status', $info)) $info['post_status'] = 'publish';
        if ($type != 'all') $info['post_type'] = $type;
        if (!empty($ids)) $info['post_id'] = array('in', $ids);

        $count = (int)$this->where($info)->count();
        return $count;
    }

    /**
     * @param $id 需要预删除的id
     *
     * @return bool 是否删除成功
     */
    public function preDel($id)
    {
        $info['post_id'] = $id;

        $data = array('post_status' => 'preDel');
        if ($this->where($info)->setField($data))
            return true;
        else
            return false;
    }

    /**
     * @param $id 需要预删除的id
     * @param string $relation 是否删除关联(脑残才不删除关联呢。。。。)
     *
     * @return bool 是否删除成功
     */
    public function del($id, $relation = 'true')
    {
        $info['post_id'] = $id;

        if ($this->where($info)->relation($relation)->delete())
            return true;
        else
            return false;
    }

    public function verify($id)
    {
        $info['post_id'] = $id;
        $data = array('post_status' => 'unverified');
        if ($this->where($info)->setField($data))
            return true;
        else
            return false;

    }

    public function unverify($id)
    {
        $info['post_id'] = $id;
        $data = array('post_status' => 'publish');
        if ($this->where($info)->setField($data))
            return true;
        else
            return false;

    }

    /**
     * @param $id 需要计数的id
     *
     * @return bool 返回是否成功
     */
    public function viewInc($id)
    {
        $info['post_id'] = $id;

        if ($this->where($info)->setInc('post_view_count'))
            return true;
        else
            return false;
    }
}