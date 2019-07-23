<!DOCTYPE html>
<html>
<head>
	<title>车库管理系统-车辆出库</title>
</head>
<body>
	<center>
    <form action="{{url('cart/do_del_cart')}}" method="post">
            @csrf
            <input type="text" name="cart_num" value="">
            <input type="submit" value="车辆出库">
        </form>
	</center>
    <script src="{{asset('mstore/js/jquery.min.js')}}"></script>
    <script>
        $(function(){
            
        });
    </script>
</body>
</html>