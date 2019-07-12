<!DOCTYPE html>
<html>
<head>
	<title>添加货物</title>
</head>
<body>
	<center>
		
		<form method="post" action="{{url('/admin/do_add_goods')}}" enctype="multipart/form-data">
			@csrf
			图片：<input type="file" name="goods_pic"><br/>
			<input type="submit" name="" value="提交">
		</form>
	</center>
</body>
</html>