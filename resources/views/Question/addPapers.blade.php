<!DOCTYPE html>
<html>
<head>
    <title>生成试卷</title>
</head>
<body>
<center>
    <form action="{{url('question/insert_papers')}}" method="post">
        @csrf
        <h1>{{$title}}</h1>
        <input type="hidden" value="{{$title}}" name="title">
        @foreach($info as $v)
            <input type="checkbox" name="problem[]" value="{{$v->id}}">
            {{$v->problem}}<br/>
        @endforeach
        <br/>
        <input type="submit" value="提交">
    </form>
</center>
</body>
</html>