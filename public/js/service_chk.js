
function realtime_service_chk(){
    $.ajax({        
        url: '/service/get_writing',        
        type: 'post',               
        dataType: 'json',
        success: function(json){                        
            result = JSON.parse(json['result']);                                    
            console.log(result);
            if(result['status']){                
                $('li#service_chk').html('<a href="/service"><font color=red>Service</font></a>');
            }else{
                $('li#service_chk').html('<a href="/service">Service</a>');
            }
            
        }
    });    
}

$(document).ready(function(){      
    realtime_service_chk();        
});

var service_chk = setInterval(realtime_service_chk,60000); // 60초 마다 check.

function service_tab_new(){
     $.ajax({       
        url: '/service/get_writing',        
        type: 'post',               
        dataType: 'json',
        success: function(json){                        
            result = JSON.parse(json['result']);                        
            access = JSON.parse(json['access']); //delegate에서 data token 필요하기 때문에 access 필요!                
            console.log(result);
            $('tr#new').children().remove();
            $('tr#new').addClass('clickableRow');
            $('tr#new').attr('href=""')
            if(result['status']){            
                $('li#service_chk').html('<a href="/service"><font color=red>Service</font></a>');                                   
                
                // 24hr 안에 해결해야 하는경우!
                if(result['data']['is_24hr'] == 1){
                    $('tr#new').append('<td style="width:80px;"><div class="col-lg-1 circle2 parent"><h2><div class="child" style="margin-left:7px;"><font color="#f15e22">24</font></div></h2></div></td>');

                    if(result['data']['title'].length > 240){
                        $('tr#new').append('<td style="width:700px;"><div class="parent" style="height:80px;"><div class="child" style="margin-top:-19px;margin-left:15px;"><h5>'+result['data']['title'].substr(0,100)+'...'+'</h5></div></div></td>');                
                    }else{
                        $('tr#new').append('<td style="width:700px;"><div class="parent" style="height:80px;"><div class="child" style="margin-top:-19px;margin-left:15px;"><h5>'+result['data']['title']+'</h5></div></div></td>');                
                    }                    
                    var db_date = result['data']['date'];
                    // 2013-11-10 03:11:03
                    var month = db_date.substring(5,7);
                    var day = parseInt(db_date.substring(8,10))+1;                      
                    var year = db_date.substring(0,4);
                    var time = db_date.substring(11,19);                    
                    var mon ="";

                    switch(month)
                    {
                        case '01' : mon = "January"; break;
                        case '02' : mon = "February"; break;
                        case '03' : mon = "March"; break;
                        case '04' : mon = "April"; break;
                        case '05' : mon = "May"; break;
                        case '06' : mon = "June"; break;
                        case '07' : mon = "July"; break;
                        case '08' : mon = "August"; break;
                        case '09' : mon = "September"; break;
                        case '10' : mon = "October"; break;
                        case '11' : mon = "November"; break;
                        case '12' : mon = "December"; break;                                            
                    }                   
                    var countdate =  mon + ' ' + day +','+' '+ year +' '+ time;                                         
                    $("tr#new").append('<td style="width:150px;"><div class="text-center" id="timer" style="height:80px; margin-top:33px;"></div></td>');
                    $('tr#new').append('<td style="width:50px;"><div class="text-center" style="height:80px; margin-top:25px;"><button class="btn btn-danger" id="adjust">Adjust</button></div></td>');

                    //date: "November 7, 2013 16:03:26"
                    $('div#timer').countdown({date: countdate}); // 24시간 타이머.
                    
                }else{ // 24hr 이 아닌경우!!
                    $('tr#new').append('<td style="width:80px;"><div class="col-lg-1 circle3 parent"><h2><div class="child" style="margin-left:7px;"><font color="#BDBDBD">24</font></div></h2></div></td>');                   
                    if(result['data']['title'].length > 100){
                        $('tr#new').append('<td style="width:680px;"><div class="parent" style="height:80px;"><div class="child" style="margin-top:-19px;margin-left:15px;"><h5>'+result['data']['title'].substr(0,100)+'...'+'</h5></div></div></td>');                
                    }else{
                        $('tr#new').append('<td style="width:680px;"><div class="parent" style="height:80px;"><div class="child" style="margin-top:-19px;margin-left:15px;"><h5>'+result['data']['title']+'</h5></div></div></td>');                
                    }
                    $("tr#new").append('<td style="width:150px;"><div class="text-center" id="timer" style="height:80px; margin-top:33px;">-- : --</div></td>');
                    $('tr#new').append('<td style="width:50px;"><div class="text-center" style="height:80px; margin-top:25px;"><button class="btn btn-danger" id="adjust">Adjust</button></div></td>');
                }
            }else{ // 새로운 내용이 없을때, Tagging해야 할 정보를 보여준다!                
                $('li#service_chk').html('<a href="/service">Service</a>');

                $("tr#new").append('<td class="text-center" colspan="6">No new posts!</td>');               
            }
        }
    });// ajax End  
}

