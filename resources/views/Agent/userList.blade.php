<!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>微信添加标签</title>
</head>
<body>
<center>

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
                wx.openLocation({
                    latitude: 0, // 纬度，浮点数，范围为90 ~ -90
                    longitude: 0, // 经度，浮点数，范围为180 ~ -180。
                    name: '', // 位置名
                    address: '', // 地址详情说明
                    scale: 1, // 地图缩放级别,整形值,范围从1~28。默认为最大
                    infoUrl: '' // 在查看位置界面底部显示的超链接,可点击跳转
                });
            });
        });
    });
</script>
</body>
</html>