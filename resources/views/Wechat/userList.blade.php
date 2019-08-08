<!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>wechat-user-list</title>
</head>
<body>
<center>

    <a href="{{url('wechat/get_user_list')}}">刷新粉丝列表</a> |
    <a href="{{url('wechat/tag_list')}}">公众号标签列表</a>
    <h1>粉丝列表</h1>
    <form action="{{url('wechat/add_user_tag')}}" method="post">
        @csrf
        <input type="hidden" name="tagid" value="{{$tag_id}}">
        <table border="1">
            <tr>
                <td>选择</td>
                <td>ID</td>
                <td>appid</td>
                <td>添加时间</td>
                <td>操作</td>
            </tr>
            @foreach($openid_info as $v)
                <tr>
                    <td>
                        <input type="checkbox" name="id_list[]" value="{{$v->id}}">
                    </td>
                    <td>{{$v->id}}</td>
                    <td>{{$v->openid}}</td>
                    <td>{{$v->add_time}}</td>
                    <td>
                        <a href="{{url('wechat/get_user_info')}}?id={{$v->id}}">详情</a> |
                        <a href="{{url('wechat/get_user_tag')}}?openid={{$v->openid}}">获取标签</a>
                    </td>
                </tr>
            @endforeach
        </table>
        <input type="submit" value="提交">
    </form>


</center>
<script type="text/javascript">
    $(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({});
    });
</script>
</body>
</html>