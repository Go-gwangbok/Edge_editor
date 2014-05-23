service_tab_new();    
var service_chk = setInterval(service_tab_new,60000); // 60초 마다 check.

function service_tab_new(){
    $.ajax({       
        url: '/service/get_writing',        
        type: 'post',               
        dataType: 'json',
        success: function(json){                        
            result = JSON.parse(json['result']);            
            console.log(result);            
            if(result['status']){            
                $('li#service_chk').html('<a href="/service"><font color=red>Service</font></a>');
            }else{ // 새로운 내용이 없을때, Tagging해야 할 정보를 보여준다!                
                $('li#service_chk').html('<a href="/service">Service</a>');                
            }
        }
    });// ajax End  
}

