<!DOCTYPE html>
<html>
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>student</title>
</head>
<body>
<center>
    <h1>火车票列表</h1>

    <form action="{{url('ticket/index')}}" method="get">
        出发地：<input type="text" name="start_place" value="{{$start_place}}">
        目的地：<input type="text" name="end_place" value="{{$end_place}}">
        <input type="submit" name="" value="搜索">
    </form>
    <br>
    <br>
    <br>
    <br>
    <table border="`">
        <tr>
            <td>出发地</td>
        </tr>

        @foreach($list as $v)
            <td>{{$v['place']}}</td>
        @endforeach

    </table>

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