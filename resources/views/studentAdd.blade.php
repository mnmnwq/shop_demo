<!DOCTYPE html>
<html>
<head>
	<title>添加学生信息</title>
</head>
<body>
	<center>
		@if($errors->any())
            @foreach ($errors->all() as $error)
                {{ $error }}
            @endforeach
		@endif
		<form method="post" action="{{url('student/do_add')}}">
			@csrf
			学生姓名：<input type="text" name="name"><br/>
			<input type="submit" name="" value="提交">
		</form>
	</center>
</body>
</html>