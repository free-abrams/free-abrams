<?php
namespace FreeAbrams\Functions\Services;

/**
 * 纯属测试
 *
 * @author Mckee
 * @link http://www.phpddt.com
 */
class Category
{
    public function view()
    {
        $lists = $this->db->order_by('lft', 'asc')->get('category')->result_array();

        // 相邻的两条记录的右值第一条的右值比第二条的大那么就是他的父类
        // 我们用一个数组来存储上一条记录的右值，再把它和本条记录的右值比较，如果前者比后者小，说明不是父子关系，
        // 就用array_pop弹出数组，否则就保留

        // 两个循环而已，没有递归
        $parent   = array();
        $arr_list = array();
        foreach ($lists as $item) {

            if (count($parent)) {
                while (count($parent) - 1 > 0 && $parent[count($parent) - 1]['rgt'] < $item['rgt']) {
                    array_pop($parent);
                }
            }

            $item['depath'] = count($parent);
            $parent[]       = $item;
            $arr_list[]     = $item;
        }

        //显示树状结构
        foreach ($arr_list as $a) {
            echo str_repeat('--', $a['depath']) . $a['title'] . '<br />';
        }

    }

    /**
     *
     * 插入操作很简单找到其父节点，之后把左值和右值大于父节点左值的节点的左右值加上2，之后再插入本节点，左右值分别为父节点左值加一和加二
     */
    public function add()
    {
        //获取到父级分类的id
        $parent_id       = 10;
        $parent_category = $this->db->where('id', $parent_id)->get('category')->row_array();

        #1.左值和右值大于父节点左值的节点的左右值加上2
        $this->db->set('lft', 'lft + 2', false)->where(array('lft >' => $parent_category['lft']))->update('category');
        $this->db->set('rgt', 'rgt + 2', false)->where(array('rgt >' => $parent_category['lft']))->update('category');

        #2.插入新的节点
        $this->db->insert('category', array(
            'title'       => '新的分类的子分类',
            'lft'         => $parent_category['lft'] + 1,
            'rgt'         => $parent_category['lft'] + 2,
            'order'       => 0,
            'create_time' => time(),
        ));

        echo 'add success';
    }

    /**
     * 删除
     *
     * #1.得到删除的节点，将右值减去左值然后加1，得到值$width = $rgt - $lft + 1;
     * #2.删除左右值之间的所有节点
     * #3.修改条件为大于本节点右值的所有节点，操作为把他们的左右值都减去$width
     */
    public function delete()
    {
        //通过分类id获取分类
        $id       = 3;
        $category = $this->db->where('id', $id)->get('category')->row_array();

        //计算$width
        $width = $category['rgt'] - $category['lft'] + 1;

        #1.删除该条分类
        $this->db->delete('category', array('id' => $id));

        #2.删除左右值之间的所有分类
        $this->db->delete('category', array('lft >' => $category['lft'], 'lft <' => $category['rgt']));

        #3.修改其它节点的值
        $this->db->set('lft', "lft - {$width}", false)->where(array('lft >' => $category['rgt']))->update('category');
        $this->db->set('rgt', "rgt - {$width}", false)->where(array('rgt >' => $category['rgt']))->update('category');

        echo 'delete success';

    }

    //编辑，
    public function edit()
    {
        //不用说了， 直接通过id编辑
        $id = 2;

        $this->db->update('category', array(
            'title' => '编辑后的分类',
        ), array(
            'id' => $id,
        ));

        echo 'edit success';
    }

}
