<!DOCTYPE html>
<html>
<head>
    <title>添加学生信息</title>
</head>
<body>
<center>
    <table border="1">
        <tr>
            <td>编号</td>
            <td>openid</td>
            <td>操作</td>
        </tr>
        @foreach($info as $k=>$v)
            <tr>
                <td>{{$k}}</td>
                <td>{{$v}}</td>
                <td><a href="{{url('biaobai/send')}}?openid={{$v}}">表白</a></td>
            </tr>
        @endforeach

    </table>
</center>
</body>
</html>