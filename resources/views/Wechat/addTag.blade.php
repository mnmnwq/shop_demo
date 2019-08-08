<!DOCTYPE html>
<html>
<head>
    <title>微信添加标签</title>
</head>
<body>
<center>

    <form action="{{url('/wechat/do_add_tag')}}" method="get">
        标签名：<input type="text" name="name" value=""><br/><br/>
        <input type="submit" value="提交">
    </form>

</center>
</body>
</html>