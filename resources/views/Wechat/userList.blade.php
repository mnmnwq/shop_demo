<!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>wechat-user-list</title>
</head>
<body>
<center>
    <a href="{{url('wechat/get_user_list')}}">刷新粉丝列表</a>
    <h1>粉丝列表</h1>

    <table border="`">
        <tr>
            <td>ID</td>
            <td>appid</td>
            <td>添加时间</td>
            <td>操作</td>
        </tr>
        @foreach($openid_info as $v)
        <tr>
            <td>{{$v->id}}</td>
            <td>{{$v->openid}}</td>
            <td>{{$v->add_time}}</td>
            <td>
                <a href="{{url('wechat/get_user_info')}}?id={{$v->id}}">详情</a>
            </td>
        </tr>
        @endforeach
        

    </table>

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