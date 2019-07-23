<!DOCTYPE html>
<html>
<head>
    <title>考试卷</title>
</head>
<body>
<center>
    <form action="{{url('question/do_add_papers')}}" method="post">
        @csrf
        试卷名称：<input type="text" value="" name="title">
        <input type="submit" value="提交">
    </form>
</center>
</body>
</html>