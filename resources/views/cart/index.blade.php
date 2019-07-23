<!DOCTYPE html>
<html>
<head>
	<title>车库管理系统</title>
</head>
<body>
	<center>
		<a href="">车库管理系统</a> | <a href=""></a> | <a href=""></a>
        <span>小区车位：400</span>
        <span>剩余车位：{{$cart_left_num}}</span>
        <button type="button" id="add_cart">车辆入库</button>
        <button type="button" id="del_cart">车辆出库</button>
	</center>
    <script src="{{asset('mstore/js/jquery.min.js')}}"></script>
    <script>
        $(function(){
            $("#add_cart").click(function(){
                window.location.href="{{url('cart/add_cart')}}";
            });
            $("#del_cart").click(function(){
                window.location.href="{{url('cart/del_cart')}}";
            });
        });
    </script>
</body>
</html>