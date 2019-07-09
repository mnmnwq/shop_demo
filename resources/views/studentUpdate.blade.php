<!DOCTYPE html>
<html>
<head>
	<title>修改</title>
</head>
<body>
	<center>
		<form action="{{url('student/do_update')}}" method="post">
			@csrf
			<input type="hidden" name="id" value="{{$student_info->id}}">
			学生姓名：<input type="text" name="name" value="{{$student_info->name}}">
			<input type="submit" name="" value="提交">
		</form>
	</center>
</body>
</html>