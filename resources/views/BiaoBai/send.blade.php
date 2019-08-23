<!DOCTYPE html>
<html>
<head>
    <title>添加学生信息</title>
</head>
<body>
<center>
    <form action="{{url('biaobai/do_send')}}" method="post">
        @csrf
        <input type="hidden" name="openid" value="{{$openid}}">
        用户类型：
        <select name="user_type" id="">
            <option value="1">实名</option>
            <option value="2">匿名</option>
        </select>
        内容：
        <textarea name="content" id="" cols="30" rows="10"></textarea>
        <input type="submit" value="提交">
    </form>
</center>
</body>
</html>