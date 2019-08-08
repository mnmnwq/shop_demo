<!DOCTYPE html>
<html>
<head>
    <title>微信用户标签列表</title>
</head>
<body>
<center>
    <a href="{{url('wechat/add_tag')}}">添加标签</a> |
    <a href="{{url('wechat/user_list')}}">粉丝列表</a>
    <br/>
    <br/>
    <br/>
    <table border="1" width="50%">
        <tr>
            <td>id</td>
            <td>标签名称</td>
            <td>标签下粉丝数</td>
            <td>操作</td>
        </tr>
        @foreach($info as $v)
        <tr>
            <td>{{$v['id']}}</td>
            <td>{{$v['name']}}</td>
            <td>{{$v['count']}}</td>
            <td>
                <a href="{{url('wechat/del_tag')}}?id={{$v['id']}}">删除</a> |
                <a href="{{url('wechat/tag_user')}}?id={{$v['id']}}">粉丝列表</a> |
                <a href="{{url('wechat/user_list')}}?tag_id={{$v['id']}}">为粉丝打标签</a> |
                <a href="{{url('wechat/push_tag_message')}}?tag_id={{$v['id']}}">推送消息</a>
            </td>
        </tr>
        @endforeach
    </table>

</center>
</body>
</html>