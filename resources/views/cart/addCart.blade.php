<!DOCTYPE html>
<html>
<head>
	<title>车库管理系统-车辆入库</title>
</head>
<body>
	<center>
    <a href="">车库管理系统</a> | <a href=""></a> | <a href=""></a>
		<form action="{{url('cart/do_add_cart')}}" method="post">
        @csrf
            <input type="text" name="cart_num" value="">
            <input type="submit" value="车辆进入">
        </form>
	</center>
    <script src="{{asset('mstore/js/jquery.min.js')}}"></script>
    <script>
        $(function(){
            
        });
    </script>
</body>
</html>