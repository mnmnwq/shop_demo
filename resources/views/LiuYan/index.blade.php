<!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>student</title>
</head>
<body>
<center>
    <h1>网站粉丝列表</h1>
    <table border="1">
        <tr>
            <td>uid</td>
            <td>用户昵称</td>
            <td>操作</td>
        </tr>
        @foreach($info as $v)
            <tr>
                <td>{{$v->uid}}</td>
                <td>{{$v->nick_name}}</td>
                <td>
                    <a href="{{url('liuyan/send')}}?uid={{$v->uid}}">发送留言</a>
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