<div class="container" style="margin-top:-15px;">	
	<div class="row">		
		<ol class="breadcrumb" style="background:white;">
        <li><a href="/">Home</a></li>
        <li><a href="/musedata/project">Project</a></li>   
        <li class="akacolor">Stats</li>   
      </ol>                  	
      	<h3 class="text-center"><?=$pjName?></h3>
        <!-- <button class="btn btn-default" id="wordcount">Word_count</button>
        <button class="btn btn-default" id="detec">Detecting_count</button> -->
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
    <div class="text-right">Detecting of Sentence Avg: <?= round(($total_word_count / $replace_count),2).'%';?></div>
    <div class="row">
        <div class="col-md-12">
            <!-- Pie -->
            <div class="col-md-6 line">            
                <div  id="piechart" style="width: 600px; height: 400px;"></div>                    
            </div>        

            <!-- T.B.D or Error -->
            <div class="col-md-6 line">            
                <div  id="merbers_tbd" style="width: 500px; height: 400px;"></div>            
            </div>            

            <!-- T.B.D or Error -->
            <div class="col-md-6 line">            
                <div  id="bar" style="width: 500px; height: 400px;"></div>            
            </div>            

            <!-- Pay -->
            <div class="col-md-6 line">            
                <div  id="pay" style="width: 500px; height: 400px;"></div>            
            </div>

            <!-- Time -->
            <div class="col-md-6 line">            
                <div id="time" style="width: 600; height: 400px;"></div>
            </div> 
        </div>        
    </div> <!-- row end -->
</div> <!-- container -->
<script src="/public/js/Chart.js"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
var pj_id = <?=$pj_id;?>;
var cate = '<?=$cate;?>';
var total = <?=$total;?>;
var discuss = <?=$discuss;?>;
var total_word = <?=$total_word?>;
var total_word_count = <?=$total_word_count?>;
var replace_count = <?=$replace_count?>;
console.log(cate);

function formatTime(time) {
    var min = parseInt(time / 6000),
        sec = parseInt(time / 100) - (min * 60),
        hundredths = pad(time - (sec * 100) - (min * 6000), 2);
    //return (min > 0 ? pad(min, 2) : "00") + ":" + pad(sec, 2) + ":" + hundredths;
    return (min > 0 ? pad(min, 2) : "00") + ":" + pad(sec, 2);
}

function pad(number, length) {
    var str = '' + number;
    while (str.length < length) {str = '0' + str;}
    return str;
}

function my_round(num, round_num){ 
// 반올림할 위치와 소숫점을 맞추기 위해 숫자를 알맞게 가공 
tmp_num1=num*Math.pow(10, round_num); 

// 가공된 숫자를 반올림 
tmp_num2=Math.round(tmp_num1); 

// 역순으로 다시 가공 
result=tmp_num2/Math.pow(10, round_num); 

return result; 
} 

google.load("visualization", "1", {packages:["corechart"]});      


function stats(pj_id){
    $.post('/stats/stats/get_data',{pj_id:pj_id},function(json){        
        console.log(json['data']);
        var result = json['data'];
        
        // Pie data
        var pie_array = [['Members', 'essay_stats']]
        var name_array = []

        // Members_tbd
        var members_tbd_array = [['Members', 'Completed', 'T.B.D', 'To do']]
        var members_tbd_data_array = []

        // Bar data
        var bar_array = [['Members', 'Sentence', 'Error']]
        var bar_data_array = []

        // Time data
        var time_array = [['Members', 'Words']]
        var time_data_array = []

        //pay data
        var pay_array = [['Members', 'Actual', 'Error','Deviation value']]
        var pay_data_array = []

        $.each(result,function(i,values){
            var name = values['name'];
            var sent_total =  parseInt(values['total']);
            var submit = parseInt(values['submit']);
            var error_count = parseInt(values['error_count']);
            var total_time = parseInt(values['total_time']);
            var word_count = parseInt(values['word_count']);
            var org_tag = parseInt(values['org_tag']);
            var actual_tag = parseInt(values['actual_tag']);
            var tbd = parseInt(values['tbd']);

            //pie
            var avg_member_total = sent_total/total*100;
            name_array = []
            name_array.push(name);            
            name_array.push(sent_total);
            pie_array.push(name_array);

            if(name != 'admin'){
                //Members T.B.D
                members_tbd_data_array = []
                members_tbd_data_array.push(name);            
                members_tbd_data_array.push(submit);
                members_tbd_data_array.push(tbd);
                members_tbd_data_array.push(sent_total - submit);
                members_tbd_array.push(members_tbd_data_array);

                // bar
                bar_data_array = []
                bar_data_array.push(name);
                bar_data_array.push(submit);
                bar_data_array.push(error_count);
                bar_array.push(bar_data_array);

                // time
                var time = parseInt((word_count*10)/formatTime(total_time).slice(0,-3));
                time_data_array = []
                time_data_array.push(name);            
                time_data_array.push(time);                
                time_array.push(time_data_array);

                // pay
                pay_data_array = []            
                var pay_avg = my_round((org_tag - actual_tag)*60,-2);
                pay_data_array.push(name);            
                pay_data_array.push(my_round(actual_tag*60,-2));
                pay_data_array.push(my_round(org_tag*60,-2));                
                pay_data_array.push(pay_avg);            
                pay_array.push(pay_data_array);
            }

            
        }); //Each     
        //console.log(284/formatTime(18030).slice(0,-3));

        function members() {
            var data = google.visualization.arrayToDataTable(pie_array);
            var options = {                
             title: 'Members Stats'             
            };
            var chart = new google.visualization.PieChart(document.getElementById('piechart'));
            chart.draw(data, options);
        } 

        function merbers_tbd(){
            var data = google.visualization.arrayToDataTable(members_tbd_array);

            var options = {
                width: 550,
                height: 400,
                legend: { position: 'top', maxLines: 3 },
                bar: { groupWidth: '60%' },
                isStacked: true,
            };

            var chart = new google.visualization.BarChart(document.getElementById('merbers_tbd'));
            chart.draw(data, options);
        }

        function bar() {
            var data = google.visualization.arrayToDataTable(bar_array);

            var options = {
                title: 'Members Completed or Error count'
            };

            var chart = new google.visualization.ColumnChart(document.getElementById('bar'));
            chart.draw(data, options);
        }        

        function pay() {
            var data = google.visualization.arrayToDataTable(pay_array);

            var options = {
                title: 'Members Pay Stats',
                vAxis: {title: "Won"},                                
                series: {2: {type: "line"}}
            };

            var chart = new google.visualization.ColumnChart(document.getElementById('pay'));
            chart.draw(data, options);
        }

        function time() {
            var data = google.visualization.arrayToDataTable(time_array);

            var options = {
              title: 'Time of Sentence',              
              hAxis: {title: '10  minute words'},
              bubble: {textStyle: {fontSize: 12}}
            };

            var chart = new google.visualization.BarChart(document.getElementById('time'));
            chart.draw(data, options);
        }


        
        google.setOnLoadCallback(members,true);           
        google.setOnLoadCallback(merbers_tbd,true);           
        google.setOnLoadCallback(bar,true);
        google.setOnLoadCallback(time,true);
        google.setOnLoadCallback(pay,true);

    }); // Post End  
} // Function End

$(document).ready(function(){
    stats(pj_id);          
    setInterval(function(){stats(pj_id)},10000); // 60초 마다 check.
}); // Ready function End

$('#wordcount').click(function(){
    console.log('a');
    var data = 0;
    $.post('/musedata/project/word_count',{data:data},function(json){
        console.log(json['result']);
    });
});

$('#detec').click(function(){
    console.log('a');
    var data = 0;
    $.post('/musedata/project/detecting_count',{data:data},function(json){
        console.log(json['result']);
    });
});

</script>
