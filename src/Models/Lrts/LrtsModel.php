<?php
namespace Huwei1994\Test4\Src\Models\Lrts;

use DB;

use LeftRightTreeStructure\TLrtsCore;

/**
 * Created by PhpStorm.
 * User: Lynn
 * Date: 2016/11/15
 * Time: 14:54
 */
class LrtsModel
{
    use TLrtsCore;

    public function __construct()
    {
//        $this->setTable('left_right_tree_structure1');
//        $this->setNodeIdField('lrts_node_id');
//        $this->setNodeLeftHanderField('lrts_node_left_hander');
//        $this->setNodeRightHanderField('lrts_node_right_hander');
//        $this->setNodeLevelField('lrts_node_level');
//        $this->setNodeNameField('lrts_node_name');
    }

    public function insert($sets)
    {
        $_sets = [];
        foreach($sets as $key => $set)
        {
            if (count($set) == 3)
            {
                if ($set[1] == '=')
                {
                    $_sets[$set[0]] = $set[2];
                }
            }
        }
        $id = DB::table($this->getTable())->insertGetId($_sets);
        return (array) DB::table($this->getTable())->where($this->getNodeIdField(), $id)->first();
    }

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

    public function update($sets, $wheres)
    {
        $db = DB::table($this->getTable());
        foreach($wheres as $where)
        {
            if (count($where) == 3)
            {
                $db->where($where[0], $where[1], $where[2]);
            }
        }

        $_sets = [];
        foreach($sets as $key => $set)
        {
            if (count($set) == 3)
            {
                $operator = $set[1];
                switch ($operator)
                {
                    case '=' :
                        $_sets[$set[0]] = $set[2];
                        break;
                    case '+=' :
                        $grammar = $db->getGrammar();
                        $_sets[$set[0]] = $db->raw($grammar->wrap($set[0]).' + '.$set[2]);
                        break;
                    case '-=' :
                        $grammar = $db->getGrammar();
                        $_sets[$set[0]] = $db->raw($grammar->wrap($set[0]).' - '.$set[2]);
                        break;
                    default :

                }
            }
        }
        return !! $db->update($_sets);
    }

    public function get($wheres)
    {
        $db = DB::table($this->getTable());
        $fields = array_flip($this->getFields());
        foreach($fields as $key => $value)
        {
            $db->addSelect("{$key} AS {$value}");
        }
        foreach($wheres as $where)
        {
            if (count($where) == 3)
            {
                $db->where($where[0], $where[1], $where[2]);
            }
        }
        return (array) $db->first();
    }

    public function gets($wheres=[])
    {
        $db = DB::table($this->getTable());
        $fields = array_flip($this->getFields());
        foreach($fields as $key => $value)
        {
            $db->addSelect("{$key} AS {$value}");
        }
        foreach($wheres as $where)
        {
            if (count($where) == 3)
            {
                $db->where($where[0], $where[1], $where[2]);
            }
        }
        return (array) $db->get();
    }
}