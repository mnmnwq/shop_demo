<!DOCTYPE html>
<html>
<head>
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>student</title>
</head>
<body>
<center>
	<h1>login</h1>
	<form action="{{url('student/do_login')}}" method="post">
		@csrf
		<input type="text" name="name">
		<input type="password" name="password">
		<input type="submit" value="提交">
	</form>
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