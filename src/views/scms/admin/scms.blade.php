<!DOCTYPE html>
<html lang="zh-CN">

<head>
    {{--test rebase--}}
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="renderer" content="webkit">
    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>资讯模块</title>
    <link rel="stylesheet" href="{{asset('assets/bootstrap/css/bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/newsInfo.css')}}">
    <!--[if lte IE 9]>
    <script src="{{asset('assets/bootstrap/js/respond.min.js')}}"></script>
    <script src="{{asset('assets/bootstrap/js/html5.js')}}"></script>
    <![endif]-->
    <script src="{{asset('assets/jquery/jquery-1.11.1.min.js')}}"></script>
    <script src="{{asset('assets/jquery/plugin/flieUploadJs/jquery.ui.widget.js')}}"></script>
    <script src="{{asset('assets/jquery/plugin/flieUploadJs/jquery.iframe-transport.js')}}"></script>
    <script src="{{asset('assets/jquery/plugin/flieUploadJs/jquery.fileupload.js')}}"></script>
    <script src="{{asset('assets/bootstrap/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('assets/jquery/plugin/timeConversion/timeConversion.js')}}"></script>
</head>
<body>
<!--列表展示页 start-->
<div class="container-fluid">
    <!--头部标题-->
    <h3 class="topic">资讯</h3>
    <!--搜索栏 start-->
    <div class="table-nav clearfix">
        <div class="col-xs-6">
            <button type="button" class="btn-add btn btn-primary">+ 添加</button>
        </div>
        <div class="col-xs-6">
            <div class="input-group col-xs-6 col-xs-push-6">
                <input type="text" class="form-control input-search" placeholder="请输入搜索内容">
                    <span class="input-group-btn">
                        <button class="btn-search btn btn-default" type="button">搜索</button>
                    </span>
            </div>
            <!-- /input-group -->
        </div>
        <div class="clear"></div>
    </div>
    <!--搜索栏 start-->
    <!--信息表格 start-->
    <table class="table table-bordered text-center" id="table">
        <thead class="thead">
        <tr>
            <th width="6%">ID</th>
            <th width="8.5%">预览图</th>
            <th width="10.5%">展示图</th>
            <th width="14.5%">标题</th>
            <th width="28%">摘要</th>
            <th width="8.5%">发布时间</th>
            <th width="7%">分类</th>
            <th width="17%">操作</th>
        </tr>
        </thead>
        <tbody>
        @if (isset($res) && !empty($res) && is_array($res) && count($res)>0)        
            @foreach($res as $k=>$re)
                <tr>
                    <td width="6%" class="listId">{{$re->id}}</td>
                    <td class="tablePreviewImg" width="8.5%">
                        <img class="tableImgsmall" src="{{$re->preview_image}}" alt="">
                    </td>
                    <td class="tablePlanImg" width="10.5%">
                        <img class="tableImgbig" src="{{$re->preview_big_image}}" alt="">
                    </td>
                    <td class="text-left tableTitle" width="14.5%">
                        <a class="tableTitlea" href="{{$re->original_link}}" target="_blank">{{$re->title}}</a>
                    </td>
                    <td class="text-left tableSummary" width="28%">{{$re->summary}}</td>

                    <td width="8.5%">
                        <p>{{date("Y-m-d",$re->published_time)}}</p>
                    </td>
                    <td class="tableCategory" width="7%" data-category-id="{{$re->category_id}}">{{$re->category_name}}</td>
                    <td width="17%" class="tableOperat">
                        @if($re->deleted_at > 0)
                            <button type="button" class="btn-recover btn btn-primary">恢复</button>
                        @else
                            <div class="btn-group">
                                <button type="button" class="btn-edit btn btn-default">编辑</button>
                                <button type="button" class="btn-del btn btn-danger">删除</button>
                            </div>
                        @endif
                    </td>
                </tr>
            @endforeach
            @else
        @endif

        </tbody>
    </table>
    <!--信息表格 end-->

    <!--分页组件 start-->
    <div class="page-nav text-right">
            @if($totalpage == 1)
                <a class="pageLast" href='{{url("scms/admin/scms/list?page=$totalpage&keywords=$keywords")}}'>尾页</a>
                <ul class="pagination">
                    <li><a href='#'>1</a></li>
                </ul> 
                <a class="pageHome" href='{{url("scms/admin/scms/list?page=1&keywords=$keywords")}}'>首页</a>
            @elseif($totalpage !== 1 && $keywords)
                <a class="pageLast" href='{{url("scms/admin/scms/list?page=$totalpage&keywords=$keywords")}}'>尾页</a>
                {{$pagelink->render()}}
                <a class="pageHome" href='{{url("scms/admin/scms/list?page=1&keywords=$keywords")}}'>首页</a>
            @elseif($totalpage !== 1 && !$keywords)
                <a class="pageLast" href='{{url("scms/admin/scms/list?page=$totalpage")}}'>尾页</a>
                {{$pagelink->render()}}
                <a class="pageHome" href="{{url('scms/admin/scms/list?page=1')}}">首页</a>
            @endif
    </div>
    <!--分页组件 end-->
</div>
<!--列表展示页 end-->

<!--添加 dialog start-->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">关闭</span></button>
                <h4 class="modal-title" id="myModalLabel">添加</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal addForm" role="form">
                    <div class="form-group">
                        <label for="msgTitle" class="col-md-3 col-lg-3 control-label"><span class="noticeColor"> * </span> 文章标题</label>
                        <div class="col-md-7 col-lg-7">
                            <input type="text" class="msgTitle form-control" id="msgTitle" placeholder="必填（最多40个字）">
                        </div>
                        <span class="col-md-2 col-lg-2 formNotice">必填</span>
                        <span class="col-md-2 col-lg-2 formNotice1">最多40个字</span>
                    </div>
                    <div class="form-group">
                        <label for="msgSummary" class="col-md-3 col-lg-3 control-label">文章摘要</label>
                        <div class="col-md-7 col-lg-7">
                            <textarea rows="" cols="" class="msgSummary form-control" id="msgSummary" placeholder="最多170个字"></textarea>
                        </div>
                        <span class="col-md-2 col-lg-2 formNotice">最多170个字</span>
                    </div>
                    <div class="form-group">
                        <label for="msgLink" class="col-md-3 col-lg-3 control-label"><span class="noticeColor"> * </span>原文链接</label>
                        <div class="col-md-7 col-lg-7">
                            <input type="text" class="msgLink form-control" id="msgLink" placeholder="http://.......">
                        </div>
                        <span class="col-md-2 col-lg-2 formNotice">必填</span>
                        <span class="col-md-2 col-lg-2 formNotice1">格式错误</span>
                    </div>
                    
                           
                    <div class="form-group">
                        <label for="msgCategory" class="col-md-3 col-lg-3 control-label">分类</label>
                        <div class="col-md-7 col-lg-7">
                            <select class="form-control addMsgCategory"> 
                                <option value="0">请选择</option>                        
                                @foreach($categories as $category)
                                <option value="{{$category->node_id}}">{{$category->node_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <span class="col-md-2 col-lg-2 formNotice">必填</span>
                    </div>
                    <div class="form-group">
                        <label for="msgImg" class="col-md-3 col-lg-3 control-label">展示图</label>
                        <div class="col-md-9 col-lg-9">
                            <div class="msgPlanbg addMsgPlanbg">
                                <input type="file" class="form-control addMsgImgPlan" id="add_preview_big_image"  name="upload_big_image" data-imgUrl="">
                                <p class="clickUploadImgNotice">点击上传图片</p>
                            </div>
                            <p class="msgImgNotice">建议大小146*110</p>
                        </div>

                    </div>
                    <div class="form-group">
                        <label for="msgImg" class="col-md-3 col-lg-3 control-label">预览图</label>
                        <div class="col-md-9 col-lg-9">
                            <div class="msgPreviewbg addMsgPreviewbg">
                                <input type="file" class="form-control addMsgImgPre" id="add_preview_image" name="upload_small_image" data-imgUrl="">
                            </div>
                            <p class="msgImgNotice">建议大小75*54</p>
                        </div>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-add-cancel btn btn-default" data-dismiss="modal">取消</button>
                <button type="submit" class="btn-submit btn btn-primary">提交</button>
            </div>
        </div>
    </div>
</div>
<!--添加 dialog end-->

<!--确认 dialog start-->
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">关闭</span></button>
                <h4 class="modal-title" id="myModalLabel">删除提醒</h4>
            </div>
            <div class="modal-body">
                <h4 class="text-center">确定删除这条资讯吗？</h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-confirm" data-dismiss="modal">确定</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>
<!--确认 dialog end-->

<!--编辑 dialog start-->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">关闭</span></button>
                <h4 class="modal-title" id="myModalLabel">编辑</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal addForm" role="form">
                    <div class="form-group">
                        <label for="msgTitle" class="col-md-3 col-lg-3 control-label"><span class="noticeColor"> * </span> 文章标题</label>
                        <div class="col-md-7 col-lg-7">
                            <input type="text" class="msgTitle editMsgTitle form-control col-md-8" id="msgTitle" placeholder="必填（最多40个字）">
                        </div>
                        <span class="col-md-2 col-lg-2 formNotice">必填</span>
                        <span class="col-md-2 col-lg-2 formNotice1">最多40个字</span>
                    </div>
                    <div class="form-group">
                        <label for="msgSummary" class="col-md-3 col-lg-3 control-label">文章摘要</label>
                        <div class="col-md-7 col-lg-7">
                            <textarea rows="" cols="" class="msgSummary editMsgSummary form-control" id="msgSummary" placeholder="最多170个字"></textarea>
                        </div>
                        <span class="col-md-2 col-lg-2 formNotice">最多170个字</span>
                    </div>
                    <div class="form-group">
                        <label for="msgLink" class="col-md-3 col-lg-3 control-label"><span class="noticeColor"> * </span>原文链接</label>
                        <div class="col-md-7 col-lg-7">
                            <input type="text" class="msgLink editMsgLink form-control" id="msgLink" placeholder="http://.......">
                        </div>
                        <span class="col-md-2 col-lg-2 formNotice">必填</span>
                        <span class="col-md-2 col-lg-2 formNotice1">格式错误</span>
                    </div>
                    <div class="form-group">
                        <label for="msgCategory" class="col-md-3 col-lg-3 control-label">分类</label>
                        <div class="col-md-7 col-lg-7">
                            <select class="editMsgselected form-control">
                                <option value="0">请选择</option>
                                @foreach($categories as $category)
                                <option class="editoption" value="{{$category->node_id}}">{{$category->node_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <span class="col-md-2 col-lg-2 formNotice">必填</span>
                    </div>
                    <div class="form-group">
                        <label for="msgImg" class="col-md-3 col-lg-3 control-label">展示图</label>
                        <div class="col-md-9 col-lg-9">
                            <div class="msgPlanbg editMsgPlanbg">
                                <input type="file" class="form-control editMsgImgPlan" id="edit_preview_big_image" name='upload_big_image' data-imgUrl="">
                                <p class="uploadImgText">点击上传图片</p>
                            </div>
                            <p class="msgImgNotice">建议大小146*110</p>
                        </div>

                    </div>
                    <div class="form-group">
                        <label for="msgImg" class="col-md-3 col-lg-3 control-label">预览图</label>
                        <div class="col-md-9 col-lg-9">
                            <div class="msgPreviewbg editMsgPreviewbg">
                                <input type="file" class="form-control editMsgImgPre" id="edit_preview_image" name='upload_small_image' data-imgUrl="">
                            </div>
                            <p class="msgImgNotice">建议大小75*54</p>
                        </div>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-edit-cancel btn btn-default" data-dismiss="modal">取消</button>
                <button type="submit" class="btn-save btn btn-primary">保存</button>
            </div>
        </div>
    </div>
</div>
<!--编辑 dialog end-->

<!--提示 dialog start-->
<div class="modal fade" id="noticeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">关闭</span></button>
                <h4 class="modal-title" id="myModalLabel">操作提醒</h4>
            </div>
            <div class="modal-body">
                <h4 class="text-center">恭喜，操作已成功！</h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-notice" data-dismiss="modal">关闭</button>
            </div>
        </div>
    </div>
</div>
<!--提示 dialog end-->
</body>
<script>
$(document).ready(function() {
    var btnRecoverTr=$('.btn-recover').parents('tr');
    btnRecoverTr.addClass('del-color');
    btnRecoverTr.find('a').addClass('del-color');
    //表单验证
    //表单验证效果
    function FormValidationStyle(blurSelf,tag){
        var blurParentFromGroup=blurSelf.parents('.form-group');
        if(tag==1){
                blurParentFromGroup.addClass('has-error');
                blurParentFromGroup.find('.formNotice1').hide();
                blurParentFromGroup.find('.formNotice').show();
        }else if(tag==2){
                blurParentFromGroup.addClass('has-error');
                blurParentFromGroup.find('.formNotice').hide();
                blurParentFromGroup.find('.formNotice1').show();            
        }else if(tag==3){
                blurParentFromGroup.removeClass('has-error');
                blurParentFromGroup.find('.formNotice').hide();
                blurParentFromGroup.find('.formNotice1').hide();
        }else if(tag==4){
                blurParentFromGroup.addClass('has-error');
                blurParentFromGroup.find('.formNotice').show();
        }else if(tag==5){
                blurParentFromGroup.removeClass('has-error');
                blurParentFromGroup.find('.formNotice').hide();        
        }
    };
    var msgLinkReg=/^(http|https|ftp):\/\/([\w-]+\.)+[\w-]+(\/[\w-./?%&=]*)?$/;
    //表单验证
    function FormValidation(blurSelf){
        var msgFormValue=blurSelf.val();
        var msgFormValueLen=msgFormValue.length;
        if(blurSelf.hasClass('msgSummary')){
            if(msgFormValueLen>170){
                FormValidationStyle(blurSelf,4);
            }else{
                FormValidationStyle(blurSelf,5);
            }
        };
        if(blurSelf.hasClass("msgTitle") || blurSelf.hasClass('msgLink')){
            if(msgFormValue==''){
                FormValidationStyle(blurSelf,1);
            }else{
                if(blurSelf.hasClass('msgTitle')){
                    if(msgFormValueLen>40){
                        FormValidationStyle(blurSelf,2);
                    }else{
                        FormValidationStyle(blurSelf,3);
                    }
                };
                if(blurSelf.hasClass('msgLink')){
                    var result=msgLinkReg.test(msgFormValue);
                    if(result==false){
                        FormValidationStyle(blurSelf,2);
                    }else{
                        FormValidationStyle(blurSelf,3);
                    };
                }
            }
        };
    };

    // 搜索
    function SearchTr(){
        var searchValue=$('.input-search').val();
        var localLink=window.location.href;
        var localLinks=localLink.split('?'); 
        var searchLink=localLinks[0]+'?keywords='+searchValue;
        window.location.href=searchLink;
    };

    //清空表单
    function ClearForm(){
        //清空表单
        $('.form-group').removeClass('has-error');
        $('.formNotice,.formNotice1').hide();
        $('.msgTitle').val('');
        $('.msgSummary').val('');
        $('.msgLink').val('');
        $('.addMsgPlanbg,.addMsgPreviewbg').css({  
            backgroundColor:'#f8f8f8',
            backgroundImage:' url(/assets/scms/image/backimage/tupain.png)',
            backgroundPosition:'center 30%',
            backgroundRepeat:'no-repeat',
            backgroundSize:'36%',
            });
        $('.addMsgPreviewbg').css({backgroundSize:'60%'});
        $('.clickUploadImgNotice').show();
        $('.addMsgCategory option:first').attr('selected',true);
        $('.addMsgImgPlan').attr('data-imgUrl','');
        $('.addMsgImgPre').attr('data-imgUrl','');
    };
    //上传图片
    function UploadImg(uploadId,ajaxUrl,uploadbg,ImgUrl){
        $(uploadId).fileupload({
            url:ajaxUrl,
            dataType:'text',
            done: function (e, data) {
                console.log(data);
                var res=JSON.parse(data.result);
                // var res=data.result;
                console.log(res);
                var imgsrc=res.data.res;
                $(uploadbg).css({
                        background:'url('+imgsrc+')',
                        backgroundSize:'100% 100%'
                });
                $(this).next().hide();
                $(ImgUrl).attr('data-imgUrl',imgsrc)
                // $.each(data.result.files, function (index, file) {// });
            }
        });
    };
    //提示框
    function NoticeDailog(text){
        $('body').css({paddingRight:'0',overflow:'hidden'});
        $('#noticeModal .modal-body h4').text(text);
        $('#noticeModal').modal('show');     
    };

    //添加
    function AddTr(){
        ClearForm();
        //显示添加表单
        $('#addModal').modal('show');
        // 添加部分的上传图片  
        UploadImg('#add_preview_big_image','{{url('scms/admin/scms/uploadbigfile')}}','.addMsgPlanbg','.addMsgImgPlan');
        UploadImg('#add_preview_image','{{url('scms/admin/scms/uploadsmallfile')}}','.addMsgPreviewbg','.addMsgImgPre');

        //提交事件
        $('.btn-submit').unbind('click').bind('click',function(){
            if(($('.msgTitle').val() !='') && ($('.msgLink').val() !='') && (msgLinkReg.test($('.msgLink').val())==true)){
                var title=$('.msgTitle').val();
                var original_link=$('.msgLink').val();
                var Summary=$('.msgSummary').val();
                var preview_big_image=$('.addMsgImgPlan').attr('data-imgUrl');                 
                var preview_image=$('.addMsgImgPre').attr('data-imgUrl');
                var category_id=$('.addMsgCategory').val();
                $.ajax({
                    url:'{{url('scms/admin/scms/add')}}',
                    type:'POST',
                    data:{title:title,original_link:original_link,summary:Summary,preview_big_image:preview_big_image,preview_image:preview_image,category_id:category_id},
                    dataType:'JSON',
                    success:function(response,status,xhr){
                        if(response.retcode==1){
                            // console.log(response);
                            var addData=response.data.res[0];
                            var aId=addData.id;
                            var atitle=addData.title;
                            var aoriginal_link=addData.original_link;
                            var asummary=addData.summary;
                            var asmall_image=addData.preview_image;
                            var abig_image=addData.preview_big_image; 
                            var acategory_id=addData.category_id;
                            var acategory_name=addData.category_name; 
                            //时间戳转换成日期
                            var aptime=$.myTime.UnixToDate(addData.published_time); 
                            // $.myTime.DateToUnix('2014-5-15 20:20:20')//日期转换时间戳                            
                            
                            // console.log(aId,atitle,aoriginal_link,asummary,asmall_image,abig_image,aptime,acategory_id);
                            //向第一行 插入行 
                            var localLink=window.location.href;
                            var linkPage=localLink.split('?')[1];
                            // var searchReg=/keywords=/g;
                            // var result=searchReg.test(localLink);
                            if(linkPage =='page=1'|| linkPage=='' || linkPage==undefined){
                                var recoverBtn='<button type="button" class="btn-recover btn btn-primary">恢复</button>';
                                var editDelBtn='<div class="btn-group"><button type="button" class="btn-edit btn btn-default">编辑</button><button type="button" class="btn-del btn btn-danger">删除</button></div>';
                                var newRow='<tr><td width="4%" class="listId">'+aId+'</td>'
                                +'<td class="tablePreviewImg" width="8.5%"><img class="tableImgsmall" src="'+asmall_image+'" alt=""></td>'
                                +'<td class="tablePlanImg" width="10.5%"><img class="tableImgbig" src="'+abig_image+'" alt=""></td>'
                                +'<td class="text-left tableTitle" width="16.5%"><a class="tableTitlea" href="'+aoriginal_link+'" target="_blank">'+atitle+'</a></td>'
                                +'<td class="text-left tableSummary" width="31%">'+asummary+'</td>'
                                +'<td width="8.5%"><p>'+aptime+'</p></td>'
                                +'<td class="tableCategory" width="4%" data-category-id="'+acategory_id+'">'+acategory_name+'</td>'
                                +'<td width="17%" class="tableOperat">';
                                if(addData.deleted_at > 0){
                                    newRow=newRow+recoverBtn+'</td></tr>';   
                                }else{
                                    newRow=newRow+editDelBtn+'</td></tr>';
                                }
                                //判断表格body部分是否有tr
                                if($('.table tbody:has(tr)').length==0){
                                    $('.table tbody').append(newRow);
                                }else{
                                    $('.table tbody tr:first').before(newRow);
                                };
                                //绑定事件
                                $('.editMsgImgPlan').attr('data-imgUrl',abig_image);
                                $('.editMsgImgPre').attr('data-imgUrl',asmall_image);
                                $('.btn-del').unbind('click').bind('click',function(e){
                                    var ev=e || window.event;
                                    ev.stopPropagation();
                                    var $delSelf=$(this);
                                    DelTr($delSelf);
                                });
                                $('.btn-edit').unbind('click').bind('click',function(e){
                                    var ev=e || window.event;
                                    ev.stopPropagation();
                                    var $editSelf=$(this);
                                    EditTr($editSelf);
                                });
                                $('.btn-recover').unbind('click').bind('click',function(e){
                                    var ev=e || window.event;
                                    ev.stopPropagation();
                                    $recoverSelf=$(this);
                                    RecoverTr($recoverSelf);
                                });
                            };
                            $('#addModal').modal('hide');
                            // setTimeout(3); 
                            NoticeDailog('添加成功！');
                            ClearForm();
                        }else{
                                NoticeDailog('添加失败!');
                        }
                    },
                    error:function(xhr,errorText,errorType){
                        NoticeDailog('请求失败!');
                    },
                    timeout:3000
                });
            }else{
                NoticeDailog('请正确填写表单');
            };
        });
    };

    //删除
    function DelTr($delSelf){
        $('#confirmModal').modal('show');
        var delSelfParentTr=$delSelf.parents('tr');
        var listId=delSelfParentTr.find('.listId').text();
        // console.log(listId);
        $('.btn-confirm').unbind('click').bind('click',function(e){
            var ev=e || window.event;
            ev.stopPropagation();
            $.ajax({
                url:'{{url('scms/admin/scms/del')}}',
                type:'POST',
                data:{id:listId},
                dataType:'JSON',
                success:function(response,status,xhr){
                    if(response.retcode==1){
                        // console.log(response);
                        $('#confirmModal').modal('hide');
                        // setTimeout(3);
                        NoticeDailog('删除成功！');
                        //页面加载成功后也需要判断是否为删除状态
                        delSelfParentTr.addClass('del-color');
                        delSelfParentTr.find('a').addClass('del-color');
                        delSelfParentTr.find('.btn-group').hide();
                        var recoverBtn='<button type="button" class="btn-recover btn btn-primary">恢复</button>';
                        delSelfParentTr.find('.tableOperat').append(recoverBtn);
                        //绑定事件
                        $('.btn-del').unbind('click').bind('click',function(e){
                            var ev=e || window.event;
                            ev.stopPropagation();
                            var $delSelf=$(this);
                            DelTr($delSelf);
                        });
                        $('.btn-edit').unbind('click').bind('click',function(e){
                            var ev=e || window.event;
                            ev.stopPropagation();
                            var $editSelf=$(this);
                            EditTr($editSelf);
                        });
                        $('.btn-recover').unbind('click').bind('click',function(e){
                            var ev=e || window.event;
                            ev.stopPropagation();
                            $recoverSelf=$(this);
                            RecoverTr($recoverSelf);
                        });                        
                    }else {
                        //删除失败
                        NoticeDailog('删除失败！');
                    }
                },
                error:function(xhr,errorText,errorType){

                    alert('请求失败后执行'+errorText+errorType);
                },
                timeout:3000
            });
        });
    };

    //获取表格数据
    function GetTableData($editSelf){
            //获取当前行中的值
            var editSelfParentTr=$editSelf.parents('tr');
            var tablePreviewImg=editSelfParentTr.find('.tablePreviewImg .tableImgsmall').attr('src');
            var tablePlanImg=editSelfParentTr.find('.tablePlanImg .tableImgbig').attr('src');
            var tableTitleUrl=editSelfParentTr.find('.tableTitle .tableTitlea').attr('href');
            var tableTitleText=editSelfParentTr.find('.tableTitle .tableTitlea').text();
            var tableSummary=editSelfParentTr.find('.tableSummary').text();
            var tableCategory=editSelfParentTr.find('.tableCategory').data('category-id');
            // console.log(tablePreviewImg,tablePlanImg,tableTitleUrl,tableTitleText,tableSummary,tableCategory);
            SetEditFormData(tablePreviewImg,tablePlanImg,tableTitleUrl,tableTitleText,tableSummary,tableCategory);
    };
    //设置编辑表单的初始数据
    function SetEditFormData(tablePreviewImg,tablePlanImg,tableTitleUrl,tableTitleText,tableSummary,tableCategory){
    //把表格中的值赋值给编辑表单
        $('.editMsgTitle').val(tableTitleText);
        $('.editMsgSummary').val(tableSummary);
        $('.editMsgLink').val(tableTitleUrl);
        $(".editMsgselected").val(tableCategory);
        //$(".editMsgselected").find("option[value="+tableCategory+"]").attr("selected",true);
        if(tablePlanImg !=''){
            $('.uploadImgText').hide();
            $('.editMsgPlanbg').css({
                'background-image':'url('+tablePlanImg+')',                    
                'background-size':'100% 100%'
            });
        };
        if(tablePreviewImg!=''){
            $('.editMsgPreviewbg').css({
                'background':'url('+tablePreviewImg+')',
                'background-size':'100% 100%'
            });
        };        
    };

    //编辑
    function EditTr($editSelf){        
        // 编辑部分的上传图片   
        UploadImg('#edit_preview_big_image','{{url('scms/admin/scms/uploadbigfile')}}','.editMsgPlanbg','.editMsgImgPlan'); 
        UploadImg('#edit_preview_image','{{url('scms/admin/scms/uploadsmallfile')}}','.editMsgPreviewbg','.editMsgImgPre'); 
        //清空select selected项
        $('.form-group').removeClass('has-error');
        $('.formNotice,.formNotice1').hide();
        $(".editMsgselected option").attr('selected',false);
        //获取当前行中的值 初始化表单数据
        GetTableData($editSelf);
        $('#editModal').modal('show');
        var editSelfParentTr=$editSelf.parents('tr');              
        var editListId=editSelfParentTr.find('.listId').text();
        //编辑保存操作
        $('.btn-save').unbind('click').bind('click',function(e){
            if(($('.editMsgTitle').val() !='') && ($('.editMsgLink').val() !='') && (msgLinkReg.test($('.editMsgLink').val())==true)){
                var ev=e || window.event;
                ev.stopPropagation();
                //获取编辑表单中的值
                var title=$('.editMsgTitle').val();
                var original_link=$('.editMsgLink').val();     
                var summary=$('.editMsgSummary').val();
                var category_id=$(".editMsgselected").val();              
                var published_time=Date();
                if($('.editMsgImgPlan').attr('data-imgUrl') ==''){
                    var preview_big_image=editSelfParentTr.find('.tablePlanImg .tableImgbig').attr('src');                       
                }else{
                    var preview_big_image=$('.editMsgImgPlan').attr('data-imgUrl');                       
                };
                if($('.editMsgImgPre').attr('data-imgUrl')==''){
                    var preview_image=editSelfParentTr.find('.tablePreviewImg .tableImgsmall').attr('src');
                }else{
                    var preview_image=$('.editMsgImgPre').attr('data-imgUrl');
                };
                // console.log(title,original_link,summary,preview_big_image,preview_image,category_id,published_time);
                $.ajax({
                    url:'{{url('scms/admin/scms/update')}}',
                    type:'POST',
                    data:{id:editListId,title:title,original_link:original_link,summary:summary,preview_big_image:preview_big_image,preview_image:preview_image,category_id:category_id,published_time:published_time},
                    dataType:'JSON',
                    success:function(response,status,xhr){
                        // console.log(response);
                        if(response.retcode==1){
                            //获取返回的数据
                            var edata=response.data.res[0];
                            var ecategory_id=edata.category_id;
                            var ecategory_name=edata.category_name;
                            var etitle=edata.title;
                            var esummary=edata.summary;
                            var epreview_big_image=edata.preview_big_image;
                            var epreview_image=edata.preview_image;
                            var eoriginal_link=edata.original_link;
                            //数据插入表格
                            editSelfParentTr.find('.tableCategory').data('category-id',ecategory_id);
                            editSelfParentTr.find('.tableCategory').text(ecategory_name);
                            editSelfParentTr.find('.tableTitle .tableTitlea').text(etitle);
                            editSelfParentTr.find('.tableTitle .tableTitlea').attr('href',eoriginal_link);                           
                            editSelfParentTr.find('.tableSummary').text(esummary);
                            editSelfParentTr.find('.tablePlanImg .tableImgbig').attr('src',epreview_big_image);
                            editSelfParentTr.find('.tablePreviewImg .tableImgsmall').attr('src',epreview_image);
                            $('#editModal').modal('hide');
                            // setTimeout(3);
                            NoticeDailog('编辑成功！');   
                        }else{
                            NoticeDailog('编辑失败！');
                        }
                    },
                    error:function(xhr,errorText,errorType){
                        NoticeDailog('请求失败!');
                    },
                    timeout:3000
                });
            }else{
                NoticeDailog('请正确填写表单！');
            };

        })
    };

    //恢复
    function RecoverTr($recoverSelf){
        var recoverSelfParentTr=$recoverSelf.parents('tr');
        var relis=recoverSelfParentTr.find('.listId').text();
        $.ajax({
            url:'{{url('scms/admin/scms/reset')}}',
            type:'POST',
            data:{id:relis},
            dataType:'JSON',
            success:function(response,status,xhr){
                if(response.retcode==1){
                    recoverSelfParentTr.removeClass('del-color');
                    recoverSelfParentTr.find('a').removeClass('del-color');
                    recoverSelfParentTr.find('.btn-recover').addClass('hide');
                    var editDelBtn='<div class="btn-group"><button type="button" class="btn-edit btn btn-default">编辑</button><button type="button" class="btn-del btn btn-danger">删除</button></div>';
                    recoverSelfParentTr.find('.tableOperat').append(editDelBtn);  
                    $('.btn-del').unbind('click').bind('click',function(e){
                        var ev=e || window.event;
                        ev.stopPropagation();
                        var $delSelf=$(this);
                        DelTr($delSelf);
                    });
                    $('.btn-edit').unbind('click').bind('click',function(e){
                        var ev=e || window.event;
                        ev.stopPropagation();
                        var $editSelf=$(this);
                        EditTr($editSelf);
                    });
                    $('.btn-recover').unbind('click').bind('click',function(e){
                        var ev=e || window.event;
                        ev.stopPropagation();
                        $recoverSelf=$(this);
                        RecoverTr($recoverSelf);
                    });                    
                }
            },
            error:function(xhr,errorText,errorType){
                alert('请求失败后执行'+errorText+errorType);
            },
            timeout:3000
        });
    };
    //调用函数

    $('.msgTitle').blur(function(){
        var blurSelf=$(this);
        FormValidation(blurSelf);
    });
    $('.msgSummary').blur(function(){
        var blurSelf=$(this);
        FormValidation(blurSelf);
    });
    $('.msgLink').blur(function(){
        var blurSelf=$(this);
        FormValidation(blurSelf);
    });
    
    $('.btn-search').unbind('click').bind('click',function(){
        SearchTr();
    });
    //取消
    $('.btn-add-cancel').click(function(){
        ClearForm();
    });
    $('.btn-add').unbind('click').bind('click',function(){          
        AddTr();
    });
    $('.btn-del').unbind('click').bind('click',function(e){
        var ev=e || window.event;
        ev.stopPropagation();
        var $delSelf=$(this);
        DelTr($delSelf);
    });
    $('.btn-edit').unbind('click').bind('click',function(e){
        var ev=e || window.event;
        ev.stopPropagation();
        var $editSelf=$(this);
        EditTr($editSelf);
    });
    $('.btn-recover').unbind('click').bind('click',function(e){
        var ev=e || window.event;
        ev.stopPropagation();
        $recoverSelf=$(this);
        RecoverTr($recoverSelf);
    });


});
</script>

</html>