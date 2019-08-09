<!DOCTYPE html>
<html>
<head>
    <title>微信修改标签</title>
</head>
<body>
<center>

    <form action="{{url('/wechat/do_update_tag')}}" method="post">
        @csrf
        <input type="hidden" name="tag_id" value="{{$tag_id}}">
        标签名：<input type="text" name="name" value="{{$tag_name}}"><br/><br/>
        <input type="submit" value="提交">
    </form>

</center>
</body>
</html>