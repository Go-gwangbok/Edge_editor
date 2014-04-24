<div class="container" style="margin-top:-15px;">	
	<div class="row">		
		<ol class="breadcrumb" style="background:white;">
        <li><a href="/">Home</a></li>
        <li><a href="/musedata/project">Project</a></li>   
        <li class="akacolor">Stats</li>   
      </ol>                  	
      	<h3 class="text-center"><?=$pjName?></h3>  	        
    </div>    
    <br>
    <div class="col-md-12">
        <div class="row line" style="border: solid #D5D5D5 2px; border-radius: 8px;">
            <div class="col-md-2 " style="width:20%;"> 
                <div class="col-md-12 ">
                    <span class="text-center"><h5><b>To do</b></h5></span>
                </div>
                <div class="col-md-12 ">
                    <span class="text-center"><h4><b><?=$draft + $todo;?></b></h4></span>
                </div>
            </div>
            <div class="col-md-1 vertical_cus"> 
                <div class="vertical_solid"></div>            
            </div>
            <div class="col-md-2 " style="width:20%;"> 
                <div class="col-md-12 ">
                    <span class="text-center"><h5><b>Completed</b></h5></span>                
                </div>
                <div class="col-md-12 ">
                    <span class="text-center"><h4><b><?=$submit?><font size="2px;"> (<? if($total == 0){echo 0;}else{echo round(($submit/$total)*100,1);}?>%)</font></b></h4></span>
                </div>
            </div>
            <div class="col-md-1 vertical_cus"> 
                <div class="vertical_solid"></div>            
            </div>
            <div class="col-md-2 " style="width:20%;"> 
                <div class="col-md-12 ">
                    <span class="text-center"><h5><b>T.B.D</b></h5></span>                
                </div>
                <div class="col-md-12 ">
                    <span class="text-center"><h4><b><?=$discuss?><font size="2px;"> (<? if($total == 0){echo 0;}else{echo round(($discuss/$total)*100,1);}?>%)</font></b></h4></span>
                </div>
            </div>
            <div class="col-md-1 vertical_cus"> 
                <div class="vertical_solid"></div>            
            </div>
            <div class="col-md-2 " style="width:20.5%;"> 
                <div class="col-md-12 ">
                    <span class="text-center"><h5><b>Total</b></h5></span>                
                </div>
                <div class="col-md-12 error">
                    <span class="text-center"><h4><b><?=$total;?></b></h4></span>
                </div>
            </div>
        </div>
    </div> <!-- div col-md-12 -->
    <br>
    <br>
    <div class="row">
        <!-- Pie -->
        <div class="col-md-6">
            <div class="panel panel-primary ">
                <div class="panel-heading">Quick Stats</div>
                  <div class="panel-body">
                    <div class="col-md-8">
                        <canvas id="pie" width="300" height="300"></canvas>                    
                    </div>
                    <div class="col-md-4">
                        <canvas id="pie_data" width="300" height="300"></canvas>                    
                    </div>
                  </div>
            </div>
        </div>        

        <!-- T.B.D or Error -->
        <div class="col-md-6">
            <div class="panel panel-danger ">
                <div class="panel-heading">Members Error or T.B.D Stats</div>
                  <div class="panel-body">
                    <div class="col-md-12">
                        <div class="col-md-12">
                            <canvas id="bar_data" width="400" height="25" style="margin-top:15px;"></canvas>
                        </div>                    
                            <canvas id="bar" width="500" height="300"></canvas>                                        
                    </div>
                  </div>
            </div>
        </div>

        <!-- Time -->
        <div class="col-md-6">
            <div class="panel panel-warning ">
                <div class="panel-heading">Time Stats</div>
                  <div class="panel-body">
                    <div class="col-md-12">
                        <div class="col-md-12">
                            <canvas id="bar_time_info" width="400" height="20" ></canvas>
                        </div>                    
                            <canvas id="bar_time" width="500" height="300"></canvas>                                        
                    </div>
                  </div>
            </div>
        </div> 

    </div> <!-- row end -->
</div> <!-- container -->
<script src="/public/js/Chart.js"></script>
<script type="text/javascript">
var pj_id = <?=$pj_id;?>;
var cate = '<?=$cate;?>';
var total = <?=$total;?>;
var discuss = <?=$discuss;?>;
console.log(cate);

function formatTime(time) {
    var min = parseInt(time / 6000),
        sec = parseInt(time / 100) - (min * 60),
        hundredths = pad(time - (sec * 100) - (min * 6000), 2);
    //return (min > 0 ? pad(min, 2) : "00") + ":" + pad(sec, 2) + ":" + hundredths;
    return (min > 0 ? pad(min, 2) : "00") + "." + pad(sec, 2);
}

function pad(number, length) {
    var str = '' + number;
    while (str.length < length) {str = '0' + str;}
    return str;
}


$(document).ready(function(){
    //data{pj_id:pj_id}

    $.post('/stats/stats/get_data',{pj_id:pj_id},function(json){        
        console.log(json['data']);
        var result = json['data'];
        var pie_intro = document.getElementById("pie_data").getContext("2d");    
        var pie_colors = ['#5F00FF','#D941C5','#E0E4CC','#69D2E7','#2F9D27','#0000ED','#F15F5F'];
        var y = 40;
        var text_y = 50;                       
        
        // Pie
        var ctx = document.getElementById("pie").getContext("2d");   
        var pie_data = []
        // bar
        var bar = document.getElementById("bar").getContext("2d");   
        var bar_intro = document.getElementById("bar_data").getContext("2d");    
        var bar_data = []
        var labels = []        
        var tbd_data = []
        var error_data = []
        var time_data = []

        var bartime = document.getElementById("bar_time").getContext("2d");          
        var bar_time_info = document.getElementById("bar_time_info").getContext("2d");          

        var error_total = 0;
        $.each(result,function(i,values){
            //pie
            pie_intro.fillStyle = pie_colors[i];
            pie_intro.fillRect(0,y,12,12);
            pie_intro.font = 'bolder';
            
            var name = values['name'];
            //console.log(name);
            if(name.length > 6){
                name = name.substr(0,6)+'..';
            }
            //var name = (values['name'].substr(0,7))+'...';
            var member_total = values['total'];  
            var tbd = parseInt(values['tbd']);
            var error_count = values['error_count'];
            if(error_count == null){
                error_count = 0;
            }
            error_count = parseInt(error_count); 
            error_avg = (error_count/member_total)*100;
            tbd_avg = (tbd/member_total)*100;
            var avg = (member_total/total)*100; // 멤버별 essay %
            var submit = values['submit'];
            //var time = Math.round(values['total_time']/submit);
            var time = values['total_time']/submit;
            //console.log(parseInt(formatTime(time)));
            time_data.push(parseInt(formatTime(time)));

            avg = avg.toFixed(1);             
            pie_intro.fillText(name+'('+avg+'%)',20,text_y)                      
            pie_data.push({value: parseInt(member_total),color:pie_colors[i]});
            labels.push(name);  // bar data          


            tbd_data.push(tbd_avg);  // bar data                 
            error_data.push(error_avg);  // bar data
            error_total += parseInt(error_count);
            
            y += 25;
            text_y += 25;            
        });   

        //bar TBD or Error
        bar_intro.fillStyle = '#FF0000'; // Error
        bar_intro.fillRect(190,2,12,12);            
        bar_intro.fillText('Error ('+error_total+')',207,12)                      
        bar_intro.fillStyle = '#F38630'; // T.B.D
        bar_intro.fillRect(280,2,12,12);          
        bar_intro.fillText('T.B.D ('+discuss+')',297,12)
        bar_intro.fillStyle = '#191919'; // 퍼센트 표시.
        bar_intro.fillText('( % )',15,22);

        // Bar time
        bar_time_info.fillStyle = '#191919'; // 퍼센트 표시.
        bar_time_info.fillText('( min . sec )',0,19);
        
        if(tbd_data.length == 1){
            tbd_data.push(0); // bar chart 데이터가 1개 일때는 표시를 못한다! 그렇기 때문에 0값을 넣어 준다!            
        }
        // fillColor : "rgba(224,228,204,1)",
        var bar_data = {
            labels : labels,
            datasets : [
                            {
                                fillColor : "rgba(255,0,0,0.8)",
                                strokeColor : "rgba(220,220,220,1)",   
                                data : error_data
                            },
                            {
                                fillColor : "rgba(234,134,48,0.8)",
                                strokeColor : "rgba(220,220,220,1)",   
                                data : tbd_data
                            }
                        ]
        }    

        var bartime_data = {
            labels : labels,
            datasets : [
                            {
                                fillColor : "rgba(241,95,95,0.8)",
                                strokeColor : "rgba(220,220,220,1)",   
                                data : time_data
                            }
                        ]
        }        

        var pie_options = {
            //Boolean - Whether we should show a stroke on each segment
            segmentShowStroke : true,
            
            //String - The colour of each segment stroke
            segmentStrokeColor : "#fff",
            
            //Number - The width of each segment stroke
            segmentStrokeWidth : 3,
            
            //Boolean - Whether we should animate the chart 
            animation : true,
            
            //Number - Amount of animation steps
            animationSteps : 100,
            
            //String - Animation easing effect
            animationEasing : "easeOutBounce",
            
            //Boolean - Whether we animate the rotation of the Pie
            animateRotate : true,

            //Boolean - Whether we animate scaling the Pie from the centre
            animateScale : false,
            
            //Function - Will fire on animation completion.
            onAnimationComplete : null
        }
        new Chart(ctx).Pie(pie_data,pie_options);

        var bar_options = {
                
            //Boolean - If we show the scale above the chart data           
            scaleOverlay : false,
            
            //Boolean - If we want to override with a hard coded scale
            scaleOverride : false,
            
            //** Required if scaleOverride is true **
            //Number - The number of steps in a hard coded scale
            scaleSteps : null,
            //Number - The value jump in the hard coded scale
            scaleStepWidth : null,
            //Number - The scale starting value
            scaleStartValue : null,

            //String - Colour of the scale line 
            scaleLineColor : "rgba(0,0,0,.1)",
            
            //Number - Pixel width of the scale line    
            scaleLineWidth : 2,

            //Boolean - Whether to show labels on the scale 
            scaleShowLabels : true,
            
            //Interpolated JS string - can access value
            scaleLabel : "<%=value%>",
            
            //String - Scale label font declaration for the scale label
            scaleFontFamily : "'Arial'",
            
            //Number - Scale label font size in pixels  
            scaleFontSize : 12,
            
            //String - Scale label font weight style    bolder
            scaleFontStyle : "bolder",
            
            //String - Scale label font colour  
            scaleFontColor : "#666",    
            
            ///Boolean - Whether grid lines are shown across the chart
            scaleShowGridLines : true,
            
            //String - Colour of the grid lines
            scaleGridLineColor : "rgba(0,0,0,.10)",
            
            //Number - Width of the grid lines
            scaleGridLineWidth : 1, 

            //Boolean - If there is a stroke on each bar    
            barShowStroke : true,
            
            //Number - Pixel width of the bar stroke    
            barStrokeWidth : 2,
            
            //Number - Spacing between each of the X value sets
            barValueSpacing : 10,
            
            //Number - Spacing between data sets within X values
            barDatasetSpacing : 1,
            
            //Boolean - Whether to animate the chart
            animation : true,

            //Number - Number of animation steps
            animationSteps : 60,
            
            //String - Animation easing effect
            animationEasing : "easeOutQuart",

            //Function - Fires when the animation is complete
            onAnimationComplete : null
            
        }
        new Chart(bar).Bar(bar_data,bar_options);

        // Bar Time        
        new Chart(bartime).Bar(bartime_data,bar_options);
    }); // Post End       

}); // Ready function End
</script>
