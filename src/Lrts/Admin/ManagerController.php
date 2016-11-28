<?php
namespace Huwei1994\Test4\Src\Lrts\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Validator;

use App\Models\Lrts\LrtsModel;
use StructuredResponse\StructuredResponse;

/**
 * Created by PhpStorm.
 * User: Lynn
 * Date: 2016/11/14
 * Time: 17:12
 */
class ManagerController extends Controller
{
    use StructuredResponse;

    public function getList()
    {
//        $data = (new LrtsModel())->addSubNode('test');
//        $data = (new LrtsModel())->deleteNode(2);
//        $data = (new LrtsModel())->modifyNode(4, ['node_name' => 'test123']);
//        dump($data);
        $nodes = (new LrtsModel())->getNodes();
        return view('lrts/admin/list')->with(['nodes' => $nodes]);
    }

    public function add(Request $request)
    {
        $inputs = $request->all();
        $rule = array(
            'node_name' => 'required|min:1|max:40',
            'parent_node_id' => 'required',
        );
        $validate = Validator::make($inputs, $rule);
        if (!$validate->fails())
        {
            $node = (new LrtsModel())->addSubNode($inputs['node_name'], $inputs['parent_node_id']);
            if ($node)
            {
                $this->setResponseRetcode(1);
                $this->addResponseInfo('添加成功');
                $this->addResponseData('node', $node);
            }
            else
            {
                $this->setResponseRetcode(0);
                $this->addResponseInfo('添加失败');
            }
        } else {
            $errors = $validate->errors()->all();
            $this->addResponseInfo($errors);
        }
        echo $this->getResponse(TRUE);
    }

    public function edit(Request $request)
    {
        $inputs = $request->all();
        $rule = array(
            'node_id' => 'required|numeric',
            'node_name' => 'required|min:1|max:40',
        );
        $validate = Validator::make($inputs, $rule);
        if (!$validate->fails())
        {
            if ((new LrtsModel())->modifyNode([['node_name', '=', $inputs['node_name']]], [['node_id', '=', $inputs['node_id']]]))
            {
                $this->setResponseRetcode(1);
                $this->addResponseInfo('修改成功');
            }
            else
            {
                $this->setResponseRetcode(0);
                $this->addResponseInfo('修改失败');
            }
        } else {
            $errors = $validate->errors()->all();
            $this->addResponseInfo($errors);
        }
        echo $this->getResponse(TRUE);
    }

    public function delete(Request $request)
    {
        $inputs = $request->all();
        $rule = array(
            'node_id' => 'required|numeric',
        );
        $validate = Validator::make($inputs, $rule);
        if (!$validate->fails())
        {
            if ((new LrtsModel())->deleteNode($inputs['node_id']))
            {
                $this->setResponseRetcode(1);
                $this->addResponseInfo('删除成功');
            }
            else
            {
                $this->setResponseRetcode(0);
                $this->addResponseInfo('删除失败');
            }
        } else {
            $errors = $validate->errors()->all();
            $this->addResponseInfo($errors);
        }
        echo $this->getResponse(TRUE);
    }
}