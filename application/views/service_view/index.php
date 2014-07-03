<div class="container" style="margin-top:-15px;">		
	<div class="row">		
		<ol class="breadcrumb" style="background:white;">
	       	<li><a href="/">Home</a></li>	       		       	
	       	<li class="akacolor">Service</li>   	       		       		       	
	    </ol> <!-- Navi end -->
	</div>		
	
	<ul class="nav nav-tabs" role="tablist" id="myTab">
	  <li class="tab_action active" id="tab_new"><a href="#tnew" role="tab" data-toggle="tab">New</a></li>
	  <li class="tab_action" id="tab_com"><a href="#com" role="tab" data-toggle="tab">Completed</a></li>	  
	</ul>

	<div class="tab-content">
    	<div class="tab-pane active" id="tnew" >    		  		
    		<br>   		
			<table class="table edge-table-hover">					
			  	<thead>
					<tr id="head" class="service_headlight">
						<th class="text-center" style="width:140px;"></th>										
						<th class="text-center">Prompt</th>																
						<th class="text-center" style="width:200px;">Re-Submit</th>									
						<th class="text-center" style="width:140px;">Service Type</th>			
					</tr>
				</thead>		
				<tbody id="newlist">
					<tr id="new" style="cursor:pointer;">

					</tr>				
				</tbody>	
			</table> 
    	</div>
    	<div class="tab-pane" id="com">
    		<table class="table table-hover">					
			  	<thead>
					<tr>
						<th class="text-center">No</th>
						<th class="text-center">Prompt</th>				
						<th class="text-center">Type</th>				
						<th class="text-center">Price Kind</th>				
						<th class="text-center">Re Submit</th>				
						<th class="text-center">Date</th>
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
var list = 20;
var editor_id = '<?=$usr_id;?>';

clearInterval(service_chk); //realtime service chk clear.	 
$(document).ready(function(){	

	ajaxPostNew();

	data ={
		page : 1,
		list : list,
		editor_id : editor_id,
		cate : 'com'		
	}
	
	url = '/writing/essay_list';

	ajaxPostCom(url, data); //page list.
}); //ready.


function ajaxPostNew(){

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
                $('tr#new').append('<td style="width:80px;"><h2><span class="label label-primary">'+result['data']['id']+'</span></h2></div></td>');

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
                //$("tr#new").append('<td><div class="text-center" id="timer" style="height:80px; margin-top:33px;"></div></td>');
                //$('tr#new').append('<td><div class="text-center" style="height:80px; margin-top:25px;"><button class="btn btn-danger" id="adjust">Adjust</button></div></td>');

                var re_submit = 'No';
                if(result['data']['orig_id'] != 0 && result['data']['id'] != result['data']['orig_id']) {
                	re_submit = 'Yes';
                }
                $("tr#new").append('<td><div class="text-center" style="height:80px; margin-top:25px;">'+re_submit+'</div></td>');
                $("tr#new").append('<td><div class="text-center" style="height:80px; margin-top:25px;">'+result['data']['price_kind']+'</div></td>');
                
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
}; //ready.


/**
$('ul.nav').delegate('li.tab_action', 'click', function(){		
	var tab = $(this).attr('id');
	cate = tab.substr(4);	
	console.log(cate);

	if (cate != 'com') {
		//$('#myTab a:last').tab('show');
		e.preventDefault();
		$("#com").tab('hide');
		$(this).tab('show');
		return true;
	}

	data ={
		page : 1,
		list : list,
		editor_id : editor_id,
		cate : cate		
	}
	
	url = '/writing/essay_list';	
	ajaxPost(url,data); //page list.
});
**/

$('tbody#newlist').delegate('tr.clickableRow', 'click', function()
{		
	console.log('a');
	token = access['data']['token'];
	w_id = result['data']['id'];
	kind = result['data']['kind_id'];
	title = result['data']['title'].replace('"','&quot');
	writing = result['data']['writing'].replace('"','&quot');		
	word_count = result['data']['words'];
	start_date = result['data']['date'];
	price_kind = result['data']['price_kind'];
	orig_id = result['data']['orig_id'];
	reason = result['data']['reason'].replace('"','&quot');

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
		        .append($('<input type="hidden" name="start_date" value="' + start_date + '">'))
		        .append($('<input type="hidden" name="price_kind" value="' + price_kind + '">'))
		        .append($('<input type="hidden" name="orig_id" value="' + orig_id + '">'))
		        .append($('<input type="hidden" name="reason" value="' + reason + '">'))
		        .appendTo($(document.body)) //it has to be added somewhere into the <body>
		        .submit();
			}else{
				alert('service -> auth : status false');
			}
		}
	});
});

$('tbody#list').delegate('tr.clickableRow', 'click', function()
{		
	window.document.location = $(this).attr("href");
});

// Timer
function formatTime(time) {
    var min = parseInt(time / 6000),
        sec = parseInt(time / 100) - (min * 60),
        hundredths = pad(time - (sec * 100) - (min * 6000), 2);
    //return (min > 0 ? pad(min, 2) : "00") + ":" + pad(sec, 2) + ":" + hundredths;
    return (min > 0 ? pad(min, 2) : "00") + ":" + pad(sec, 2);
}

// Common functions
function pad(number, length) {
    var str = '' + number;
    while (str.length < length) {str = '0' + str;}
    return str;
}

function ajaxPostCom(url,data){


	$.post(url,data,function(json) {				
		console.log(json['list']);
		page = json['page'];

		$('tbody#list').children().remove();
		var data_list = json['list'];	
		var num = (list * page) - (list-1);
		total_count = json['total_count'];
		$('li#count').remove();
		$('ul#pjboard').last().append('<li class="pull-right" id="count"><button class="btn btn-default disabled" style="border-color:#f15e22;">Count : '+total_count+'</button></li>');		

		// todo,com,tbd,history
		for(var i = 0; i < json['list'].length; i++) {
			var essay_id = data_list[i]['essay_id'];
			var orig_essay_id = data_list[i]['orig_essay_id'];
			var task = data_list[i]['type'];
			var timer = formatTime(data_list[i]['time']);			
			var prompt = data_list[i]['prompt'];
			var raw_txt = data_list[i]['raw_txt'];
			var kind_name = data_list[i]['kind_name'];
			var id = data_list[i]['id'];
			var price_kind = data_list[i]['price_kind'];
			if (orig_essay_id == 0 ||essay_id == orig_essay_id)
				var re_submit = 'No';
			else
				var re_submit = 'Yes';

			if($.isNumeric(prompt)){
				prompt = prompt+raw_txt.substr(0,120);
			}

			if(data_list[i]['draft'] == 0 || data_list[i]['draft'] == 1 ){
				var date = data_list[i]['start_date'];				
			}else if(data_list[i]['draft'] == 1 && data_list[i]['submit'] == 1){
				var date = data_list[i]['sub_date'];
			}
	
			var href = '/writing/view_essay/';					
			var status = '<button class="btn btn-success btn-sm">Completed</button>';
						
			$('tbody#list').append('<tr id='+i+' style="cursor:pointer;" class="clickableRow" href="'+href+essay_id+'/'+'"><td class="text-center">'+num+'</td><td>'
				+essay_id+"::"+prompt.replace(/"/gi,'')+'</td><td class="text-center">'
				+kind_name.toUpperCase()+'</td><td style="width:90px;">'
				+price_kind+'</td><td class="text-center">'
				+re_submit+'</td><td class="text-center">'
				+date+'</td><td class="text-center">'				
				+timer+'<td class="text-center">'+status+'</td></tr>');				
			num++;
		}		
		$('div#pageblock').children().remove();
		
		pageBlock(page,list,total_count); // page button.
	});

}

function pageBlock(page,list,total_count){

	var b_pageNum_list = 10; //블럭에 나타낼 페이지 번호 갯수
	var block = Math.ceil(page/b_pageNum_list); //현재 리스트의 블럭 구하기
    var b_start_page = ( (block - 1) * b_pageNum_list ) + 1; //현재 블럭에서 시작페이지 번호    
    var b_end_page = b_start_page + b_pageNum_list - 1; //현재 블럭에서 마지막 페이지 번호		 	
    var total_page =  Math.ceil(total_count/list); //총 페이지 수

    if (b_end_page > total_page) {
    	b_end_page = total_page;
    }    

    if(page <= 1){     	     
        $('div#pageblock').append('<button class="btn btn-default" disabled>&laquo;</button>');
    }else{
        $('div#pageblock').append('<button class="btn btn-default" id="p_button" page_num="1">&laquo;</button>');
    }

    if(block <=1) {
        //<font> </font>
    }else{ 
        $('div#pageblock').append('<button class="btn btn-default" id="p_button" page_num="'+(b_start_page-1)+'">Prev</button>');
    } 

    for(var j = b_start_page; j <= b_end_page; j++) {
        if(page == j) {
        	$('div#pageblock').append('<button class="btn btn-default active" id="p_button" page_num="1">'+j+'</button>');
        }else{
        	$('div#pageblock').append('<button class="btn btn-default" id="p_button" page_num="'+j+'">'+j+'</button>');            
		}              
    }

 	var total_block = Math.ceil(total_page/b_pageNum_list);
 
    if(block >= total_block) {
    	//<font> </font>
    }else{
    	$('div#pageblock').append('<button class="btn btn-default" id="p_button" page_num="'+(b_end_page+1)+'">Next</button>');        
    } 
    
    if(page >= total_page){
    	$('div#pageblock').append('<button class="btn btn-default" disabled>&raquo;</button>');        
    }else{ 
    	$('div#pageblock').append('<button class="btn btn-default" id="p_button" page_num="'+total_page+'">&raquo;</button>');        
    }
}

$('div#pageblock').delegate('button#p_button', 'click', function(){	
	var page_num = $(this).attr('page_num');
	
	data = {
		page : page_num,
		list : list,
		editor_id : editor_id,
		cate : 'com'
	}
	console.log(data);
	url = '/writing/essay_list';

	
	ajaxPostCom(url,data); //page list.
});
</script>

