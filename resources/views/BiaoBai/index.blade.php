<!DOCTYPE html>
<html>
<head>
    <title>添加学生信息</title>
</head>
<body>
<center>
    <table border="1">
        <tr>
            <td>编号</td>
            <td>openid</td>
            <td>操作</td>
        </tr>
        @foreach($info as $k=>$v)
            <tr>
                <td>{{$k}}</td>
                <td>{{$v}}</td>
                <td><a href="{{url('biaobai/send')}}?openid={{$v}}">表白</a></td>
            </tr>
        @endforeach

    </table>
    <script src="{{asset('mstore/js/jquery.min.js')}}"></script>
    <script >
        $(function(){
            $.ajax({
                async : true,
                url : "http://shopdemo.18022480300.com/biaobai/notify_url",
                type : "GET",
                dataType : "jsonp", // 返回的数据类型，设置为JSONP方式
                jsonp : 'callback', //指定一个查询参数名称来覆盖默认的 jsonp 回调参数名 callback
                jsonpCallback: 'handleResponse', //设置回调函数名
                data : {
                    q : "javascript",
                    count : 1
                },
                success: function(response, status, xhr){
                    console.log('状态为：' + status + ',状态是：' + xhr.statusText);
                    console.log(response);
                }
            });
        });
    </script>
</center>
</body>
</html>