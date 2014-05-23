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
			<table class="table table-hover">					
			  	<thead>
					<tr>
						<th class="text-center"></th>										
						<th class="text-center">Prompt</th>																
						<th class="text-center">Timer</th>									
						<th class="text-center">Status</th>			
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
	// public/js/service_chk.js 안에 아래 함수가 있음!
	service_tab_new();	
	setInterval(service_tab_new,60000); // 60초 마다 check.		
}); //ready.

$('tbody#list').delegate('tr.clickableRow', 'click', function()
{		
	token = access['data']['token'];
	w_id = result['data']['id'];
	title = result['data']['title'];
	writing = result['data']['writing'];
	is_critique = result['data']['is_critique'];
	kind = result['data']['kind'];

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
			console.log(access['status']);									
			if(access['status']){											
			    $('<form action="/service/writing" method="POST"/>')
		        .append($('<input type="hidden" name="token" value="' + token + '">'))
		        .append($('<input type="hidden" name="w_id" value="' + w_id + '">'))
		        .append($('<input type="hidden" name="title" value="' + title + '">'))
		        .append($('<input type="hidden" name="writing" value="' + writing + '">'))						        
		        .append($('<input type="hidden" name="critique" value="' + is_critique + '">'))						        
		        .append($('<input type="hidden" name="kind" value="' + kind + '">'))						        
		        .appendTo($(document.body)) //it has to be added somewhere into the <body>
		        .submit();
			}else{
				alert('status false');
			}
		}
	});
});
</script>
