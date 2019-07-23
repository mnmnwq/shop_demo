<!DOCTYPE html>
<html>
<head>
	<title>车库管理系统-计费</title>
</head>
<body>
	<center>
		<a href="">车库管理系统</a> | <a href=""></a> | <a href=""></a>
        <span>车牌号：{{$cart_num}}</span>
        <span>停车时长：{{$time_info}}</span>
        <span>累计：{{$pay_amount}}元</span>

        <button type="button" id="pay">缴费</button>
	</center>
    <script src="{{asset('mstore/js/jquery.min.js')}}"></script>
    <script>
        $(function(){
            $("#add_cart").click(function(){
                window.location.href="{{url('cart/add_cart')}}";
            });
            $("#del_Cart").click(function(){
                window.location.href="{{url('cart/del_cart')}}";
            });
            $("#pay").click(function(){
                window.location.href="{{url('cart/del_price')}}?id={{$cart_id}}&price={{$pay_amount}}";
            });
        });
    </script>
</body>
</html>