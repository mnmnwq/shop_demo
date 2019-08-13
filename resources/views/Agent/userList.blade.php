<!DOCTYPE html>
<html>
<head>
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
                   <a href="{{url('/agent/agent_list')}}?uid={{$v->id}}">用户推广用户列表</a>
               </td>
           </tr>
       @endforeach
   </table>

</center>
</body>
</html>