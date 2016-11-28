<?php

namespace Huwei1994\Test4\Src\Scms\Api;

use Huwei1994\Test4\Src\Models\Scms\ScmsModel;
use Huwei1994\Test4\Src\Models\Scms\TestModel;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use StructuredResponse\StructuredResponse;
use Validator;

class PostController extends Controller
{
    use StructuredResponse;

    public function index(){
        return view('scms/api/post');
    }

    //获取前端列表数据
    public function getList(Request $request){
        $inputs = $request->all();
        $rule = array(
            'page' => 'required|integer|min:1',
            'length' => 'required|integer|min:0',
            'post_id'=>'integer|min:0',
        );
        $validate = Validator::make($inputs, $rule);
        if ($validate->fails())
        {
            $errors = $validate->errors()->all();
            return $this->genResponse(0,$errors);
        }
        //验证通过
        $page = $request->input('page', 0);
        $length = $request->input('length', 10);
        $keywords = $request->input('keywords', "");
        $post_id = $request->input('post_id', 0);
        $options=array('keywords' => $keywords,
        );
        if ($post_id > 0 && $page == 1) {
            //下拉刷新
            $offset = 0;
            $wheres = ([
                ['id', '>', $post_id],
                ['deleted_at', '=', 0]]);
            $posts = (new ScmsModel())->getList($offset, $length, $options, $wheres);
        }else{
            $offset = ($page - 1)*$length;
            $wheres = ([
                ['deleted_at', '=', 0]]);
            $posts = (new ScmsModel())->getList($offset, $length, $options, $wheres);
        }
        return $this->genResponse(1, ['获取成功'], ['posts' => $posts]);
    }
}