<div class="container" style="margin-top:-15px;">	

    <h3 class="text-center">Pay</h3>        
    <div class="col-md-12">
        <div class="text-right">Detecting of Sentence Avg : <?= round(($replace_count / $total_word_count)*100,2).'%';?></div>    
    </div> <!-- div col-md-12 -->
    
    <br>
    <br>
    <div class="row" id="chart">
        <div class="col-md-12">
            <!-- Time -->
            <div class="col-md-6 line">            
                <div id="time" style="width: 600; height: 400px;"></div>
            </div> 

            <!-- Members Pay -->
            <div class="col-md-6 line">            
                <div  id="pay" style="width: 500px; height: 400px;"></div>            
            </div>
        </div>        
    </div> <!-- row end -->
    <br>
    <div class="row" id="chart">
        <div class="col-md-12">
            <div class="col-md-12 line">
                <br>
                <div  id="table"></div>
                <br>
            </div>
        </div>
    </div>

    <div class="row" id="chart">
        <div class="col-md-12">
            <div class="col-md-12 line">
                <br>
                <div class="text-right">Detecting Avg : 213</div>
                <div  id="table_member_pay"></div>
                <br>
            </div>
        </div>
    </div>
            <!-- <div class="col-md-6 line">            
                <div id="totalpay" style="width: 600; height: 400px;"></div>
            </div> 
            
            <div class="col-md-6 line">            
                <div  id="piechart" style="width: 600px; height: 400px;"></div>                    
            </div>        

            
            <div class="col-md-6 line">            
                <div  id="merbers_tbd" style="width: 500px; height: 400px;"></div>            
            </div>            

            
            <div class="col-md-6 line">            
                <div  id="bar" style="width: 500px; height: 400px;"></div>            
            </div>   -->          

            

            

            
        
</div> <!-- container -->
<script src="/public/js/Chart.js"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">

var total_word_count = <?=$total_word_count?>;
var replace_count = <?=$replace_count?>;


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

function set_comma(n) {
    var reg = /(^[+-]?\d+)(\d{3})/;
    n += '';
    while (reg.test(n))
     n = n.replace(reg, '$1' + ',' + '$2');

    return n;
} 

console.log(formatTime(18000));

google.load("visualization", "1", {packages:["corechart"]});      
google.load('visualization', '1', {packages:['table']});

function stats(){

    $.post('/pay/pay/get_pay_data',{cate:'pay'},function(json){        
        console.log(json['data']);
        var result = json['data'];        

        //pay data
        var pay_array = [['Members', 'Actual','Deviation value']]
        var pay_data_array = []

        // Time data
        var time_array = [['Members', 'Words',{ role: 'annotation' }]]
        var time_data_array = []                

        //Total Pay
        var recent_actual_pay = 0;
        var todo_pay = 0;
        var replace_error_word_pay = 0; // submit을 했지만, replace가 되지 않은 위드수!       

        // Table
        var table_array = []
        var table_data_array = []

        // Table members pay
        var table_members_array = []
        var table_members_data_array = []

        /*[
              ['Mike',  {v: 10000, f: '$10,000'}, {v: 10000, f: '$10,000'},{v: 10000, f: '$10,000'},{v: 10000, f: '$10,000'}],
              ['Jim',   {v:8000,   f: '$8,000'},  {v: 10000, f: '$10,000'},{v: 10000, f: '$10,000'},{v: 10000, f: '$10,000'}],
              ['Alice', {v: 12500, f: '$12,500'}, {v: 10000, f: '$10,000'},{v: 10000, f: '$10,000'},{v: 10000, f: '$10,000'}],
              ['Bob',   {v: 7000,  f: '$7,000'},  {v: 10000, f: '$10,000'},{v: 10000, f: '$10,000'},{v: 10000, f: '$10,000'}]
            ]*/
        var money = 60;     
        var exchange = 1068 * 12;
        var fixing_agent = 10000;
        // Total AVG       
        var members_detecting_sum = 0;   
        var members_detecting_avg = 218;
        var avg = my_round((parseInt(replace_count)/parseInt(total_word_count))*100,0);               
        var basic_word = 400;
        console.log(avg/100);
        $.each(result,function(i,values){
            var name = values['name'];
            var sent_total =  parseInt(values['total']);
            var submit = parseInt(values['submit']);                
            var total_time = parseInt(values['total_time']);
            var word_count = parseInt(values['word_count']);
            var org_tag = parseInt(values['org_tag']);
            var actual_tag = parseInt(values['actual_tag']);
            
            var todo_word = parseInt(values['todo_word']);
            var replace_error_word = parseInt(values['replace_error_word']);                
            var avg_total_word = parseInt(values['avg_total_word']);                              

            var time = parseInt((word_count*10)/formatTime(total_time).slice(0,-3));

            if(name != 'clara'){            
                // pay
                pay_data_array = []            
                var pay_avg = my_round((org_tag - actual_tag)*money,-2);
                pay_data_array.push(name);            
                pay_data_array.push(my_round(((time*(avg/100))*money)*6,-2));            
                pay_data_array.push(my_round(((time*(avg/100))*money)*6,-2));            
                pay_array.push(pay_data_array);
                var each_member_pay = my_round(((time*(avg/100))*money)*6,-2);

                // time            
                time_data_array = []
                time_data_array.push(name);            
                time_data_array.push(time);                
                time_data_array.push(time);                
                time_array.push(time_data_array);                    

                //Table

                var words = time*6;
                console.log(words);

                table_data_array = []
                table_data_array =[name,{v:exchange, f:''+exchange},{v:each_member_pay, f:''+each_member_pay},{v:my_round(words/basic_word,0), f:''+my_round(words/basic_word,0)},{v:0, f:''+0},{v:0, f:''+0},{v:0, f:''+0}]
                table_array.push(table_data_array);

                //Table
                var each_member_detecting_pay = my_round(fixing_agent/((time*6)*(avg/100)),0);
                members_detecting_sum += (time*6)*(avg/100);            

                var mem_pay = my_round((((time*6)*(avg/100))-members_detecting_avg)/members_detecting_avg*money+money,0);
                // console.log((time*6)*(avg/100));
                // console.log(mem_pay);

                table_members_data_array = []
                table_members_data_array =[name,{v:fixing_agent, f:''+fixing_agent},{v:each_member_detecting_pay, f:''+each_member_detecting_pay},{v:(time*6)*(avg/100), f:''+(time*6)*(avg/100)},{v:mem_pay, f:''+mem_pay}]
                table_members_array.push(table_members_data_array);
                
                recent_actual_pay += actual_tag;
                todo_pay += todo_word;
                replace_error_word_pay += replace_error_word;
            }
        }); //Each     
console.log(members_detecting_sum/8);

        function pay() {
            var data = google.visualization.arrayToDataTable(pay_array);

            var options = {
                title: 'Members Pay Stats',
                vAxis: {title: "Won"},                                
                series: {1: {type: "line"}}
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

        function drawTable() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Name');
            data.addColumn('number', 'Part-time pay');
            data.addColumn('number', 'Case by case');
            data.addColumn('number', 'Essays');
            data.addColumn('number', 'Structure Tagging');
            data.addColumn('number', 'Critique');
            data.addColumn('number', 'Scoring');
            data.addRows(table_array);

            var table = new google.visualization.Table(document.getElementById('table'));
            table.draw(data, {showRowNumber: true});
          }

        function drawTable_members() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Name');
            data.addColumn('number', 'Part-time pay');
            data.addColumn('number', 'Case by case');
            data.addColumn('number', 'Detecting num');
            data.addColumn('number', 'Value');
            data.addRows(table_members_array);

            var table = new google.visualization.Table(document.getElementById('table_member_pay'));
            table.draw(data, {showRowNumber: true});
        }
        google.setOnLoadCallback(pay,true);
        google.setOnLoadCallback(time,true);
        google.setOnLoadCallback(drawTable,true);
        google.setOnLoadCallback(drawTable_members,true);
        
    }); // Post End  


} // Function End

$(document).ready(function(){
    $('div#chart').css('display','none');
    stats();  
    $('div').fadeIn(1500);          
    
}); // Ready function End
</script>
