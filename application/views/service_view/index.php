<div class="container" style="margin-top:-15px;">		
	<div class="row">		
		<ol class="breadcrumb" style="background:white;">
	       	<li><a href="/">Home</a></li>	       		       	
	       	<li class="akacolor">Service</li>   	       		       		       	
	    </ol> <!-- Navi end -->
	</div>		
	
	<ul class="nav nav-tabs">
	  <li class="tab_action active" id="tab_new"><a href="#tnew" data-toggle="tab">New</a></li>
	  <li class="tab_action" id="tab_com"><a href="#com" data-toggle="tab">Completed</a></li>	  
	</ul>

	<div class="tab-content">
    	<div class="tab-pane active" id="tnew" >    		  		
    		<br>   		
			<table class="table edge-table-hover">					
			  	<thead>
					<tr id="head" class="service_headlight">
						<th class="text-center" style="width:140px;"></th>										
						<th class="text-center">Prompt</th>																
						<th class="text-center" style="width:200px;">Timer</th>									
						<th class="text-center" style="width:140px;">Status</th>			
					</tr>
				</thead>		
				<tbody id="list">
					<tr id="new" style="cursor:pointer;">

					</tr>				
				</tbody>	
			</table> 
    	</div>
    	<div class="tab-pane" id="com">
    		<table class="table table-hover">					
			  	<thead>
					<tr>
						<th class="text-center">Prompt</th>				
						<th class="text-center">Type</th>				
						<th class="text-center">Date</th>				
						<th class="text-center">Task</th>				
						<th class="text-center">Timer</th>				
						<th class="text-center">Status</th>			
					</tr>
				</thead>		
				<tbody id="list">
				<!-- ajax list -->
				</tbody>	
			</table>    		
    	</div> <!-- com div -->
    </div> <!-- tab content -->


	<div class="text-center" id="pageblock">
		<!-- ajax list -->
	</div>	
</div> <!-- container -->

<script type="text/javascript">

clearInterval(service_chk); //realtime service chk clear.	 
$(document).ready(function(){				
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
            if(result['status']){            
                $('li#service_chk').html('<a href="/service"><font color=red>Service</font></a>');
                $('tr#new').append('<td style="width:80px;"><div class="col-lg-1 circle2 parent"><h2><div class="child" style="margin-left:7px;"><font color="#f15e22">48</font></div></h2></div></td>');

                if(result['data']['title'].length > 240){
                    $('tr#new').append('<td><div class="parent" style="height:80px;"><div class="child" style="margin-top:-19px;margin-left:15px;"><h5>'+result['data']['title'].substr(0,100)+'...'+'</h5></div></div></td>');                
                }else{
                    $('tr#new').append('<td><div class="parent" style="height:80px;"><div class="child" style="margin-top:-19px;margin-left:15px;"><h5>'+result['data']['title']+'</h5></div></div></td>');                
                }                    
                var db_date = result['data']['date'];
                // 2013-11-10 03:11:03
                var month = db_date.substring(5,7);
                var day = parseInt(db_date.substring(8,10))+2;
                var year = db_date.substring(0,4);
                var time = db_date.substring(11,19);                    
                var mon ="";
                //console.log(day);
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
                $("tr#new").append('<td><div class="text-center" id="timer" style="height:80px; margin-top:33px;"></div></td>');
                $('tr#new').append('<td><div class="text-center" style="height:80px; margin-top:25px;"><button class="btn btn-danger" id="adjust">Adjust</button></div></td>');

                //date: "November 7, 2013 16:03:26"
                $('div#timer').countdown({date: countdate}); // 24시간 타이머.
            }else{ // 새로운 내용이 없을때, Tagging해야 할 정보를 보여준다!                
            	$('tr#head').removeClass('service_headlight');
            	$('table.edge-table-hover').addClass('table-hover');
            	$('table.edge-table-hover').removeClass('edge-table-hover');

                $('li#service_chk').html('<a href="/service">Service</a>');
                $("tr#new").append('<td class="text-center" colspan="6">No new posts!</td>');               
            }
        }
    });// ajax End  
}); //ready.

$('tbody#list').delegate('tr.clickableRow', 'click', function()
{		
	console.log('a');
	token = access['data']['token'];
	w_id = result['data']['id'];
	kind = result['data']['kind_id'];
	title = result['data']['title'];
	writing = result['data']['writing'];		
	word_count = result['data']['words'];

	var data = {
		token: token,
		w_id: w_id
	};
	console.log(data);
	$.ajax({			
			url:"/service/auth",
			type: 'POST',		
			data: data,
			dataType: 'json',
			success: function(json){
			var	access = JSON.parse(json['result']);						
			console.log(access);
			console.log(token);
			//console.log(access['status']);									
			if(access['status']){											
			    $('<form action="/service/writing" method="POST"/>')
		        .append($('<input type="hidden" name="token" value="' + token + '">'))
		        .append($('<input type="hidden" name="w_id" value="' + w_id + '">'))
		        .append($('<input type="hidden" name="title" value="' + title + '">'))
		        .append($('<input type="hidden" name="writing" value="' + writing + '">'))						        	
		        .append($('<input type="hidden" name="kind_id" value="' + kind + '">'))						        
		        .append($('<input type="hidden" name="word_count" value="' + word_count + '">'))
		        .appendTo($(document.body)) //it has to be added somewhere into the <body>
		        .submit();
			}else{
				alert('service -> auth : status false');
			}
		}
	});
});
</script>
