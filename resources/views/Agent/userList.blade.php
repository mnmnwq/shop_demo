<!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>微信添加标签</title>
</head>
<body>
<center>
    <button type="button" id="get_location">获取地理位置信息</button>

   <table border="1">
       <tr>
           <td>用户uid</td>
           <td>用户专属推广码</td>
           <td>用户专属二维码</td>
           <td>操作</td>
       </tr>
       @foreach($user_info as $v)
           <tr>
               <td>{{$v->id}}</td>
               <td>{{$v->agent_code}}</td>
               <td><img src="{{$v->qrcode_url}}" alt="" width="200" height="200"></td>
               <td>
                   <a href="{{url('/agent/create_qrcode')}}?uid={{$v->id}}">生成用户专属二维码</a> |
                   <a href="{{url('/agent/agent_list')}}?uid={{$v->id}}">用户推广用户列表</a> |
                   <button type="button" class="share_btn">分享</button>
               </td>
           </tr>
       @endforeach
   </table>

</center>
<script src="{{asset('mstore/js/jquery.min.js')}}"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.4.0.js"></script>
<script>
    $(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        wx.config({
            debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
            appId: "{{$jsconfig['appid']}}", // 必填，公众号的唯一标识
            timestamp: '{{$jsconfig['timestamp']}}', // 必填，生成签名的时间戳
            nonceStr: "{{$jsconfig['noncestr']}}", // 必填，生成签名的随机串
            signature: "{{$jsconfig['sign']}}",// 必填，签名
            jsApiList: ['chooseImage','uploadImage','getLocalImgData','startRecord'] // 必填，需要使用的JS接口列表
        });
        wx.ready(function(){
            $('.share_btn').click(function(){
                wx.updateAppMessageShareData({
                    title: '', // 分享标题
                    desc: '', // 分享描述
                    link: '', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                    imgUrl: '', // 分享图标
                    success: function () {
                        // 设置成功
                    }
                });
            });
            $("#get_location").click(function(){
                wx.getLocation({
                    type: 'wgs84', // 默认为wgs84的gps坐标，如果要返回直接给openLocation用的火星坐标，可传入'gcj02'
                    success: function (res) {
                        var latitude = res.latitude; // 纬度，浮点数，范围为90 ~ -90
                        var longitude = res.longitude; // 经度，浮点数，范围为180 ~ -180。
                        var speed = res.speed; // 速度，以米/每秒计
                        var accuracy = res.accuracy; // 位置精度
                    }
                });
            });
        });
    });
</script>
</body>
</html>