@extends('layouts.vk')
@section('content')
<div id="main" style="width: 600px;height:400px;"></div>
<form  id="testform">
    <div class="form-group">
        <label>
            <input type="text" name="daterange" value="" style="width:200px" />
        </label>
    </div>
    @csrf
    <button type="button" id="formButton" class="btn btn-primary">Submit</button>
</form>
<script  src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script  src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script  src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"></link>
<script src="https://cdn.jsdelivr.net/npm/echarts@4.9.0/dist/echarts.min.js"></script>
<script>
    $.ajaxSetup({headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                 }
    });
    $('#main').remove();
    document.body.insertAdjacentHTML("afterbegin", `<div id="main" style="width: 1600px;height:400px;"></div>`);
    let myChart = echarts.init(document.getElementById('main'));
    $('input[name="daterange"]').daterangepicker({
        opens: 'left',
        locale: {
            format: 'YYYY-MM-DD'
        }
    });
    $.get(
        '/vk-posts',
        onAjaxSuccess
    );

    function onAjaxSuccess(data)
    {
        let postDate=[];
        let postText=[];
        let postLikesCount=[];

        console.log(data);
        for(let i=0;  i<data.posts.length; i++ ){
            postDate[i]=data.posts[i].date;
            postText[i]=data.posts[i].text;
            postLikesCount[i]=data.posts[i].likesCount
        }

        let option = {
            title: {
                text: 'Vk Wall Likes Times Posts'
            },
            tooltip: {},
            legend: {
                data: ['Vk Wall']
            },
            xAxis: {
                data: postDate
            },
            yAxis: {},
            series: [{
                name: 'LikesCount',
                type: 'bar',
                data: postLikesCount
            }]
        };
        myChart.setOption(option);
    }
            $('#formButton').on('click', function(e) {

                const testForm=$( "#testform" ).serialize();

                $.post(
                    '/vk-range',
                    testForm
                    ,onAjaxSuccess
                );

                function onAjaxSuccess(data)
                {
                    if(data.posts[0]===undefined){
                        return;
                    }
                    $('#main').remove();
                    document.body.insertAdjacentHTML("afterbegin", `<div id="main" style="width: 600px;height:400px;"></div>`);
                    let myChart = echarts.init(document.getElementById('main'));

                    let postDate=[];
                    let postText=[];
                    let postLikesCount=[];

                    for(let i=0;  i<data.posts.length; i++ ){
                        postDate[i]=data.posts[i].date;
                        postText[i]=data.posts[i].text;
                        postLikesCount[i]=data.posts[i].likesCount
                    }
                    let option = {
                        title: {
                            text: 'Vk Wall Likes Times Posts'
                        },
                        tooltip: {},
                        legend: {
                            data: ['Vk Wall']
                        },
                        xAxis: {
                            data: postDate
                        },
                        yAxis: {},
                        series: [{
                            name: 'LikesCount',
                            type: 'bar',
                            data: postLikesCount
                        }]
                    };
                    myChart.setOption(option);
                }
            })
</script>
@endsection
