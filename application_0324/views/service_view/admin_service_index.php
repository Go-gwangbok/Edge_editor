<!-- 
    Model 에서 service_all_year_data($yen) 함수 Where문 에 type = 'writing' 를 추가 할것!
    현재 service 데이터가 없어서 musedata 데이터로 테스트 하고 있음!
-->

<div class="container" style="margin-top:-15px;">	
	<div class="row">		
		<ol class="breadcrumb" style="background:white;">
        <li><a href="/">Home</a></li>        
        <li class="akacolor">Service</li>   
      </ol>
      <h3 style="margin-left:13px;">Service</h3>
    </div> 
    <div class="col-md-12 text-center" style="margin-top:-43px;">
        <select>   
        <?
        foreach ($all_year as $value) {
            $year = $value->year;
        ?>
        <option value="<?=$year;?>"><?=$year?></option>
        <?
        }
        ?>  
        </select>           
    </div>
    <br>
    <!-- <span class="stat-count">1000</span> -->
    <div class="row" id="month">
        <!-- Ajax -->
    </div>
</div>
<script type="text/javascript">

// $(function() {
//     function count($this){
//         var current = parseInt($this.html(), 10);
//         current = current + 50; /* Where 50 is increment */

//         $this.html(++current);
//         if(current > $this.data('count')){
//             $this.html($this.data('count'));
//         } else {    
//             setTimeout(function(){count($this)}, 5);
//         }
//     }        

//     $(".stat-count").each(function() {
//         $(this).data('count', parseInt($(this).html(), 10));
//         $(this).html('0');
//         count($(this));
//     });
// });

function months_list(yen){
    $.post('/service/get_service_yen',{yen:yen},function(json){        
        console.log(json['data']);
        var data = json['data'];
        $('div#month').empty();
        $.each(data,function(i,value){
            var month = value['month'].substr(5); // 2014-01                      
            var int_month = value['month'].substr(5); // 2014-01                      
            var month_total = value[month+'month'];
            var year = value['month'].substr(0,4); // 2014-01           

            switch(month){
                case '01' : month = 'January'; break;
                case '02' : month = 'February'; break;
                case '03' : month = 'March'; break;
                case '04' : month = 'April'; break;
                case '05' : month = 'May'; break;
                case '06' : month = 'June'; break;
                case '07' : month = 'July'; break;
                case '08' : month = 'August'; break;
                case '09' : month = 'September'; break;
                case '10' : month = 'October'; break;
                case '11' : month = 'November'; break;
                case '12' : month = 'December'; break;
            }

            $('div#month').append('<div class="col-lg-3" style="margin-bottom:20px;">'
                                    +'<div class="col-lg-12 line">'
                                        +'<h4 style="margin-top:10px;" class="service_font"><span class="glyphicon glyphicon-calendar"></span> '+month+'</h4>'
                                    +'</div>'
                                    +'<div class="col-lg-12 service_bg service_btn" href="/service/enter/'+int_month+'/'+year+'" id="enter">'
                                        +'<font class="font-white" id="stat-count">'+month_total+'</font>'
                                    +'</div>'
                                    +'<div class="col-lg-6 service_bg service_btn-export" href="/service/export/'+int_month+'/'+year+'" id="export">'
                                        +'<font class="font-white">Export</font>'
                                    +'</div>'
                                    +'<div class="col-lg-6 service_bg service_btn-stats" href="#" id="stats">'
                                        +'<font class="font-white">Stats</font>'
                                    +'</div>'
                                +'</div>');            

        }); // Esch End        
    }); // Post End
}

$(document).ready(function(){
    // var now = new Date();
    // year = now.getFullYear();    
    $('select').selectpicker(); // select box view.
    var first_yen = $('select').children().first().val()    
    //console.log(first_yen);
    months_list(first_yen);    
});

$('select').change(function() {
    //console.log($(this).val());
    var yen = $(this).val();
    months_list(yen);    
});

$('div').delegate('div#enter', 'click', function(){  
    console.log('enter');
    window.document.location = $(this).attr('href');
});

$('div').delegate('div#export', 'click', function(){  
    console.log('export');
    window.document.location = $(this).attr('href');
});


</script>
