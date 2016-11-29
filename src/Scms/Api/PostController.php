<?php
namespace App\Http\Controllers\Scms\Admin;
use App\Models\Scms\CategoryModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Scms\ScmsModel;
use Illuminate\Support\Facades\Storage;
use StructuredResponse\StructuredResponse;
/**
 * Created by PhpStorm.
 * User: huwei
 * Date: 2016/11/14
 * Time: 15:12
 */
class ScmsController extends Controller
{
    //使用格式化返回值
    use StructuredResponse;

    //定义一个方法，用于显示首页
    public function index(Request $request)
    {
        //获取首页数据和分页链接（默认是从0开始，煤每页显示8条，关键词为空）
        $page = $request->input('page','');
        $length = $request->input('length', 8);
        $orderby = $request->input('orderby', 'desc');
        $keywords = $request->input('keywords', '');

        //调用模型方法，查找数据
        $options = [
            'order_by'=>$orderby,
            'keywords'=>$keywords
        ];
        $data = (new ScmsModel())->generateLink($page, $length, $options);
        return view('scms/admin/scms', [
            'res' => $data['res'],
            'pagelink' => $data['pagelink'],
            'length' => $length,
            'totalpage' => $data['totalpage'],
            'keywords' => $keywords,
            'categories'=>$data['categories']
        ]);
    }

    //定义一个add函数，用于添加数据
    public function addItem(Request $request)
    {
        //表单验证数据（图片要不要文件验证呢）
        $this->validate($request, [
            'title' => 'required|min:1|max:40',
            'original_link' => 'required',
        ]);
        //如果表单验证通过，获取其他数据
        $title = $request->input('title');
        $original_link = $request->input('original_link');
        $summary = $request->input('summary', '');
        $bigimage = $request->input('preview_big_image', '');
        $image = $request->input('preview_image', '');
        $time = $request->input('published_time', time());
        $cateid = $request->input('category_id', 1);
        $options = array(
            'summary' => $summary,
            'preview_big_image' => $bigimage,
            'preview_image'     => $image,
            'published_time'    => $time,
            'created_at'        => time(),
            'category_id'       => $cateid,
            'title'             => $title,
            'original_link'     => $original_link
        );

        //调用模型方法，插入数据
        $res = (new ScmsModel())->addItem($options);
        if ($res) {
            $this->setResponseRetcode(1);
            $this->addResponseInfo('添加成功');
            $this->addResponseData('res', $res);
        } else {
            $this->setResponseRetcode(0);
            $this->addResponseInfo('添加失败');
        }
        echo $this->getResponse(TRUE);
    }

    //文件上传方法，用来处理图像上传和文件上传的
    public function uploadBigFile(Request $request)
    {
        //接收文件
        $file = $request->file('upload_big_image');
        //判断文件是否上传成功
        if ($file->isValid())
        {
            //扩展名
            $ext = $file->getClientOriginalExtension();
            //临时绝对路径
            $realpath = $file->getRealPath();
            //给文件一个新名字，防止冲突
            $filename = date('Y-m-d-H-i-s') .uniqid(). '-' . 'big' . '.' . $ext;
            Storage::disk('scms_biguploads')->put($filename, file_get_contents($realpath));
            //返回图片资源路径
            $this->setResponseRetcode(1);
            $this->addResponseInfo('文件上传成功');
            $res = '/assets/scms/image/uploads/bigimg/'.$filename;
            $this->addResponseData('res', $res);
        }
        else
        {
            $this->setResponseRetcode(0);
            $this->addResponseInfo('文件上传失败');
        }
        echo $this->getResponse(TRUE);
    }

    //文件上传方法，用来处理图像上传和文件上传的
    public function uploadSmallFile(Request $request)
    {
        //接收文件
        $file = $request->file('upload_small_image');
        //判断文件是否上传成功
        if ($file->isValid())
        {
            //扩展名
            $ext = $file->getClientOriginalExtension();
            //临时绝对路径
            $realpath = $file->getRealPath();
            //给文件一个新名字，防止冲突
            $filename = date('Y-m-d-H-i-s') .uniqid(). '-' . 'small' . '.' . $ext;
            Storage::disk('scms_smalluploads')->put($filename, file_get_contents($realpath));
            //返回图片资源路径
            $this->setResponseRetcode(1);
            $this->addResponseInfo('文件上传成功');
            $res = '/assets/scms/image/uploads/smallimg/'.$filename;
            $this->addResponseData('res', $res);
        } else
        {
            $this->setResponseRetcode(0);
            $this->addResponseInfo('文件上传失败');
        }
        echo $this->getResponse(TRUE);
    }

    //del函数，用于删除数据
    public function delItem(Request $request)
    {
        //表单验证
        $this->validate($request, [
            'id' => 'required',
        ]);
        //如果表单验证成功
        $id = $request->input('id');
        //调用模型方法，插入数据
        $res = (new ScmsModel())->modifyItem(['deleted_at' => time()], [['id', '=', $id]]);
        if ($res) {
            $this->setResponseRetcode(1);
            $this->addResponseInfo('删除成功');
            $this->addResponseData('res', $res);
        } else {
            $this->setResponseRetcode(0);
            $this->addResponseInfo('删除失败');
        }
        echo $this->getResponse(TRUE);

    }

    //update函数，用于修改数据
    public function update(Request $request)
    {
        //表单验证
        $this->validate($request, [
            'id' => 'required',
            'title' => 'required|min:1|max:40',
            'original_link' => 'required',
        ]);
        //如果表单验证通过的话
        $id = $request->input('id');
        $title = $request->input('title');
        $original_link = $request->input('original_link');
        $summary = $request->input('summary', '');
        $bigimage = $request->input('preview_big_image', '');
        $image = $request->input('preview_image', '');
        $modifytime = time();
        $cateid = $request->input('category_id', 0);
        $data = array(
            'title' => $title,
            'original_link' => $original_link,
            'summary' => $summary,
            'preview_big_image' => $bigimage,
            'preview_image' => $image,
            'category_id' => $cateid,
            'modified_at' => $modifytime
        );
        $wheres = [
            ['id', '=', $id]];
        //调用模型方法，插入数据
        $res = (new ScmsModel())->modifyItem($data, $wheres);
        if ($res) {
            $this->setResponseRetcode(1);
            $this->addResponseInfo('修改成功');
            $this->addResponseData('res', $res);
        } else {
            $this->setResponseRetcode(0);
            $this->addResponseInfo('修改失败');
        }
        echo $this->getResponse(TRUE);
    }

    //getdata函数，用于查询记录详细数据
    public function getData(Request $request)
    {
        //表单验证
        $this->validate($request, [
            'id' => 'required',
        ]);
        //表单验证成功
        $id = $request->input('id');
        //调用模型方法，插入数据
        $res = (new ScmsModel())->getItem([['id', '=', $id]]);
        if ($res) {
            $this->setResponseRetcode(1);
            $this->addResponseInfo('查找详细记录成功');
            $this->addResponseData('res', $res);
        } else {
            $this->setResponseRetcode(0);
            $this->addResponseInfo('查找详细记录失败');
        }
        echo $this->getResponse(TRUE);
    }

    //恢复被删除了的数据的方法
    public function resetItem(Request $request)
    {
        //表单验证
        $this->validate($request, [
            'id' => 'required',
        ]);
        //表单验证成功
        $id = $request->input('id');
        $options = ['deleted_at' => 0];
        $wheres = [
            ['id', '=', $id]];
        $res = (new ScmsModel())->modifyItem($options, $wheres);
        if ($res) {
            $this->setResponseRetcode(1);
            $this->addResponseInfo('恢复记录成功');
            $this->addResponseData('res', $res);
        } else {
            $this->setResponseRetcode(0);
            $this->addResponseInfo('恢复记录失败');
        }
        echo $this->getResponse(TRUE);
    }
}