<?php

namespace App\Http\Controllers\Scms\Admin;

use App\Models\Scms\CategoryModel;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use StructuredResponse\StructuredResponse;
use Validator;

class CategoryController extends Controller
{
    use StructuredResponse;

    public function getList()
    {
        $nodes = (new CategoryModel())->getNodes();
        return view('scms/admin/category')->with(['nodes' => $nodes]);
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
            $node = (new CategoryModel())->addSubNode($inputs['node_name'], $inputs['parent_node_id']);
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
            if ((new CategoryModel())->modifyNode([['node_name', '=', $inputs['node_name']]], [['node_id', '=', $inputs['node_id']]]))
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
            if ((new CategoryModel())->deleteNode($inputs['node_id']))
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
