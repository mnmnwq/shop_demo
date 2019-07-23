<!DOCTYPE html>
<html>
<head>
    <title>考试卷</title>
</head>
<body>
<center>
    @foreach($info as $v)
        链接: <a href="{{url('question/test_detail')}}?id={{$v->id}}">访问链接</a><br/>
    @endforeach
</center>
</body>
</html>