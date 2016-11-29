<?php
namespace App\Models\Scms;
use DB;
use ScmsContentManagement\ScmsCore;
use App\Models\Scms\CategoryModel;
/**
 * Created by PhpStorm.
 * User: huwei
 * Date: 2016/11/14
 * Time: 15:54
 */
class ScmsModel
{
    use ScmsCore;

    protected $category;
    /**
     * ScmsModel constructor.
     */
    public function __construct(){
        //设置table
        //$this->setTable('test_list');
        //设置数据库有哪些字段
        //$this->setTableFields(['id' => 'id',]);
        //搜索时匹配哪些字段
        //$this->setEnableSearchField(['title']);
        $this->category = new CategoryModel();
    }

    /**
     * @param $data
     * @return Array
     */
    public function insert($sets)
    {
        $db = DB::table($this->getTable());
        $id = $db->insertGetId($sets);
        $categoryTbl = $this->category->getTable();
        $categoryNameField = $this->category->getNodeNameField();
        $scmsTbl = $this->getTable();
        $res = $db->where('id', $id)
            ->leftJoin($categoryTbl, $scmsTbl.'.category_id', '=', $categoryTbl.'.category_id')
            ->select($scmsTbl.'.*', $categoryTbl.'.'.$categoryNameField)
            ->selectRaw("ifnull({$categoryTbl}.{$categoryNameField}, '无') as {$categoryNameField}")
            ->get();
        return $res;
    }

    /**
     * @param $wheres
     * @return Boolean
     */
    public function delete($wheres)
    {
        $db = DB::table($this->getTable());
        foreach($wheres as $where)
        {
            if (count($where) == 3)
            {
                $db->where($where[0], $where[1], $where[2]);
            }
        }
        return !! $db->delete();
    }

    /**
     * @param $sets
     * @param $wheres
     * @return Boolean
     * 软删除或者更新数据
     */
    public function update($options, $wheres)
    {
        $db = DB::table($this->getTable());
        foreach ($wheres as $where){
            if (count($where) == 3) {
                $db->where($where[0], $where[1], $where[2]);
            }
        }
        $db->update($options);
        $categoryTbl = $this->category->getTable();
        $categoryNameField = $this->category->getNodeNameField();
        $scmsTbl = $this->getTable();
        $db->leftJoin($categoryTbl, $scmsTbl.'.category_id', '=', $categoryTbl.'.category_id')
            ->select($scmsTbl.'.*', $categoryTbl.'.'.$categoryNameField)
            ->selectRaw("ifnull({$categoryTbl}.{$categoryNameField}, '无') as {$categoryNameField}");
        return $db->get();
    }

    /**
     * 获取某条记录
     * @param $wheres
     * @return Array
     */
    public function get($wheres)
    {
        $db = DB::table($this->getTable());
        foreach ($wheres as $where){
            if (count($where) == 3) {
                $db->where($where[0], $where[1], $where[2]);
            }
        }
        return (array) $db->first();
    }

    /**
     * 获取记录列表数据（包含关键字搜索）
     * @param $wheres
     * @return Array
     */
    public function gets($offset, $length, $options, $wheres, $orWheres=[])
    {
        //获取参数
        $order_id = isset($options['order_id'])?$options['order_id']:'id';
        $orderBy = isset($options['order_by'])?$options['order_by']:'DESC';
        $db = DB::table($this->getTable());
        if (count($orWheres)) {
            //模糊匹配搜索
            $db->where(function ($query) use ($orWheres){
                foreach ($orWheres as $where){
                    if (count($where) == 3) {
                        $query->orWhere($where[0], $where[1], $where[2]);
                    }
                }
            });
        }
        //额外的查询条件
        foreach ($wheres as $where){
            if (count($where) == 3) {
                $db->where($where[0], $where[1], $where[2]);
            }
        }
        $categoryTbl = $this->category->getTable();
        $categoryNameField = $this->category->getNodeNameField();
        $scmsTbl = $this->getTable();
        $db->leftJoin($categoryTbl, $scmsTbl.'.category_id', '=', $categoryTbl.'.category_id')
            ->select($scmsTbl.'.*', $categoryTbl.'.'.$categoryNameField)
            ->selectRaw("ifnull({$categoryTbl}.{$categoryNameField}, '无') as {$categoryNameField}");
        return $db->orderBy($order_id, $orderBy)->skip($offset)->take($length)->get();
    }

    /**
     * 生成分页链接的模型方法（包含关键字分页链接）
     * @return Array
     */
    public function generateLink($page, $length, $options, $wheres=[])
    {
        //参数检查
        if (!is_numeric($page) || $page < 0) {
            $page = 1;
        }
        if (!is_numeric($length) || $length < 0) {
            $length = 10;
        }
        $offset = ($page - 1)*$length;
        $keywords = isset($options['keywords'])?$options['keywords']:'';
        $orWheres = [];
        if ($keywords) {
            //需要添加关键字搜索
            foreach ($this->search_where_enable as $key){
                $orWheres[] = [$key, 'like', '%'."$keywords".'%'];
            }
        }
        //获取参数
        $db = DB::table($this->getTable());
        if (count($orWheres)) {
            //有搜索条件的分页
            //模糊匹配搜索
            $db->where(function ($query) use ($orWheres){
                foreach ($orWheres as $where){
                    if (count($where) == 3) {
                        $query->orWhere($where[0], $where[1], $where[2]);
                    }
                }
            });
            $pagelink = $db->paginate($length);
            $pagelink->setPath('list')->appends(['keywords'=>isset($options['keywords'])?$options['keywords']:''])->render();
            $total = $db->count();
        }else{
            //普通分页
            //分页链接
            $pagelink = $db->paginate($length);
            $pagelink->setPath('list');
            $total = $db->count();
        }
        //按照偏移量，每页显示数量，查询数据，
        $res = $this->gets($offset, $length, $options, $wheres, $orWheres);

        //所有分组分类
        $categories = $this->category->gets([['category_level', '>=', 1]]);
        //获取总页数，向上取整
        $totalpage = ceil($total/$length);
        $linkarray = array(
            'res'=>$res,
            'pagelink'=>$pagelink,
            'totalpage'=>$totalpage,
            'categories'=>$categories
        );
        return $linkarray;
    }

    /*
     * 将category id转换为name显示
     * @param $result从数据库查询出来的结果
     * @param $category CategoryModel实例
     * */
    public function convertCategoryId($result, $category=null){
        if (is_null($category)) {
            $category = new CategoryModel();
        }
        foreach ($result as $item){
            $categoryItem = $category->get([['category_id', '=', $item->category_id]]);
            $item->category_id = "无";
            if (count($categoryItem)) {
                $category_name = $categoryItem['node_name'];
                if ($category_name) {
                    $item->category_id = $category_name;
                }
            }
        }
        return $result;
    }
}