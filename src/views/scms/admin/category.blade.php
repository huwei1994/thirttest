<!DOCTYPE html>
<html>
<head>
    <title>添加分类</title>

    <script src="{{ asset('assets/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/bootstrap/js/bootstrap.min.js') }}"></script>

    <link href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
</head>
<body>
<div class="container">
    <div class="content">
        <div class="row table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <td>分类编号</td>
                        <td>分类左值</td>
                        <td>分类右值</td>
                        <td>分类层级</td>
                        <td>分类名称</td>
                        <td>分类操作</td>
                    </tr>
                </thead>
                <tbody>
                    @if (is_array($nodes))
                        @foreach($nodes as $node)
                            <tr>
                                <td class="lrts-node-id">{{ $node->node_id }}</td>
                                <td class="lrts-node-left-hander">{{ $node->node_left_hander }}</td>
                                <td class="lrts-node-right-hander">{{ $node->node_right_hander }}</td>
                                <td class="lrts-node-level">{{ $node->node_level }}</td>
                                <td class="lrts-node-name">{{ $node->node_name }}</td>
                                <td>
                                    <button class="btn btn-primary event-lrts-add">添加子分类</button>
                                    <button class="btn btn-primary event-lrts-edit">修改</button>
                                    <button class="btn btn-danger event-lrts-delete">删除</button>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="lrts-add-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">添加</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">分类名称</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="lrts-add-modal-node-name" placeholder="请输入分类名称">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="event-lrts-add-modal-save">保存</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="lrts-edit-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">修改</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form">
                    <div class="form-group">
                        <label for="inputEmail3" class="col-sm-2 control-label">分类名称</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="lrts-edit-modal-node-name" placeholder="请输入分类名称">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="event-lrts-edit-modal-save">保存</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="lrts-delete-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">删除</h4>
            </div>
            <div class="modal-body">
                <p>您确定要删除此分类吗?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="event-lrts-delete-modal-save">确定</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // 添加
        $('.event-lrts-add').unbind('click').bind('click', function() {
            var tr = $(this).parents('tr');
            var parent_node_id = parseInt(tr.find('.lrts-node-id').text());
            $('#event-lrts-add-modal-save').unbind('click').bind('click', function() {
                var node_name = $('#lrts-add-modal-node-name').val().trim();
                if (node_name == '') {
                    alert('请填写分类名称');
                    return ;
                }
                $.ajax({
                    url : 'add',
                    type: 'POST',
                    data: {
                        parent_node_id : parent_node_id,
                        node_name : node_name,
                    },
                    dataType: 'JSON',
                    success: function(response) {
                        alert(response.info.join('\n'));
                        if (response.retcode == 1) {
                            window.location.reload();
                        }
                        $('#lrts-add-modal').modal('hide');
                    },
                    error: function() {
                        alert('添加失败');
                    }
                });
            });
            $('#lrts-add-modal').modal('show');
        });

        // 修改
        $('.event-lrts-edit').unbind('click').bind('click', function() {
            var tr = $(this).parents('tr');
            var node_id = parseInt(tr.find('.lrts-node-id').text());
            var node_name = tr.find('.lrts-node-name').text();
            $('#lrts-edit-modal-node-name').val(node_name);
            $('#event-lrts-edit-modal-save').unbind('click').bind('click', function() {
                var node_name = $('#lrts-edit-modal-node-name').val().trim();
                if (node_name == '') {
                    alert('请填写分类名称');
                    return ;
                }
                $.ajax({
                    url : 'edit',
                    type: 'POST',
                    data: {
                        node_id : node_id,
                        node_name : node_name,
                    },
                    dataType: 'JSON',
                    success: function(response) {
                        alert(response.info.join('\n'));
                        if (response.retcode == 1) {
                            window.location.reload();
                        }
                        $('#lrts-edit-modal').modal('hide');
                    },
                    error: function() {
                        alert('修改失败');
                    }
                });
            });
            $('#lrts-edit-modal').modal('show');
        });

        // 删除
        $('.event-lrts-delete').unbind('click').bind('click', function() {
            var tr = $(this).parents('tr');
            var node_id = parseInt(tr.find('.lrts-node-id').text());
            $('#event-lrts-delete-modal-save').unbind('click').bind('click', function() {
                $.ajax({
                    url : 'delete',
                    type: 'POST',
                    data: {
                        node_id : node_id,
                    },
                    dataType: 'JSON',
                    success: function(response) {
                        alert(response.info.join('\n'));
                        if (response.retcode == 1) {
                            window.location.reload();
                        }
                        $('#lrts-edit-modal').modal('hide');
                    },
                    error: function() {
                        alert('删除失败');
                    }
                });
            });
            $('#lrts-delete-modal').modal('show');
        });
    });
</script>
</body>
</html>
