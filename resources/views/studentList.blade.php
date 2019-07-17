<!DOCTYPE html>
<html>
<head>
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>student</title>
</head>
<body>
	<center>
		<h1>学生列表</h1>
		{{Session::get('username')}}
		<form action="{{url('student/index')}}" method="get">
			
			姓名：<input type="text" name="search" value="{{$search}}">
			<input type="submit" name="" value="搜索">
		</form>
		<table border="`">
			<tr>
				<td>姓名</td>
				<td>id</td>
				<td>添加时间</td>
				<td>操作</td>
			</tr>
			@foreach($student as $key=>$item)
			<tr>
				<td>{{ $item->name }}</td>
				<td>{{ $item->id }}</td>
				<td>{{ date('Y-m-d H:i:s',$item->addtime) }}</td>
				<td><a href="{{url('student/update')}}?id={{$item->id}}">修改</a>|<a href="{{url('student/delete')}}?id={{$item->id}}">删除</a></td>
			</tr>
			@endforeach
		</table>
		{{ $student->appends(['search' => $search])->links() }}
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