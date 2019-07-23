<!DOCTYPE html>
<html>
<head>
    <title>添加问题</title>
</head>
<body>
<center>
    <form action="{{url('question/do_add')}}" method="post">
        @csrf
        <select name="type" id="question_type">
            <option value="">--选择--</option>
            <option value="1">单选题</option>
            <option value="2">多选题</option>
            <option value="3">判断</option>
        </select>
        <div id="single">
            <span>单选题</span><br/>
            题目：<input type="text" name="single" value=""><br/>
            A:<input type="radio" name="single_answer" value="1">
            <input type="text" name="single_a" value=""><br/>
            B:<input type="radio" name="single_answer" value="2">
            <input type="text" name="single_b" value=""><br/>
            C:<input type="radio" name="single_answer" value="3">
            <input type="text" name="single_c" value=""><br/>
            D:<input type="radio" name="single_answer" value="4">
            <input type="text" name="single_d" value=""><br/>
        </div>

        <div id="box">
            <span>多选</span><br/>
            题目：<input type="text" name="box" value=""><br/>
            A:<input type="checkbox" name="box_answer[]" value="1">
            <input type="text" name="box_a"><br/>
            B:<input type="checkbox" name="box_answer[]" value="2">
            <input type="text" name="box_c"><br/>
            C:<input type="checkbox" name="box_answer[]" value="3">
            <input type="text" name="box_b"><br/>
            D:<input type="checkbox" name="box_answer[]" value="4">
            <input type="text" name="box_d"><br/>
        </div>

        <div id="judge">
            <span>判断</span>
            <br/>
            题目：<input type="text" name="judge" value=""><br/>
            对:<input type="radio" name="judge_answer" value="1">

            错:<input type="radio" name="judge_answer" value="2">
        </div>
        <br/>
        <br/>
        <input type="submit" name="sub" value="提交">
    </form>
</center>
<script src="{{asset('mstore/js/jquery.min.js')}}"></script>
<script>
    $(function(){
        $("#single").hide();
        $("#box").hide();
        $("#judge").hide();
        $("input[name=sub]").hide();
        $("#question_type").change(function(){
            var type = $(this).val();
           if(type == 1){
               $("#box").hide();
               $("#judge").hide();
               $("#single").show();
           }else if(type == 2){
               $("#single").hide();
               $("#judge").hide();
               $("#box").show();
           }else if(type == 3){
               $("#judge").show();
               $("#single").hide();
               $("#box").hide();
           }
            $("input[name=sub]").show();
        });
    });
</script>
</body>
</html>