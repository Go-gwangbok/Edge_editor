<div class="container" style="margin-top:-15px;">   
  	<div class="row">    
      	<ol class="breadcrumb" style="background:white;">
        	<li><a href="/">Home</a></li>
        	<li><a href="/setting/info">Setting</a></li>   
        	<li class="akacolor">Templet setting</li>   
    	</ol>                        
  	</div>  <!-- Nav or Head title End -->  
  	<h3 class="text-center" id="title"></h3>
  	<hr>
  	<br>
  	<div class="col-md-12">
            <h4>Active Tab Setting</h4>
	</div>   
	<div class="row">    
    	<div class="col-md-12" style="height:37px;" id="tabs">    		
    		<!-- Ajax -->       			
		</div>  
	</div>          

	<br>
    <div class="col-md-12">
    	<button class="btn btn-primary btn-sm pull-right" id="save">SAVE</button>
    </div>	      			
</div>
<script type="text/javascript">
var kind_id = <?=$kind_id;?>;
var type_id = '<?=$type_id?>';
console.log(kind_id);

function firstLowstring(value){
	var str = value.substring(0,1).toUpperCase() + value.substring(1);
	return str;
}

$(document).ready(function(){
	$.post('/setting/info/get_typevals',{kind_id : kind_id,type_id:type_id},function(json){						
		var tabs = json['tabs'];
		var kind_name = json['kind_name'];
		var task_name = json['task_name'];		
		console.log(task_name);
		
		$('#title').text(firstLowstring(task_name)+' '+ kind_name.toUpperCase()+' Templet setting');
		$.each(tabs,function(i,values){			
			var element_id = values['element_id'];
			var element = values['element'];
			var view_element = values['view_ele'];
			var active = values['active']

			if(active == 0){
				$('div#tabs').append('<div class="col-md-2" style="margin-top:12px;">'
    									+'<input type="checkbox" class="tabs" id="b'+element_id+'" checked="checked"><br>'
										+'<span id="tab'+element_id+'" style="margin-right:5px; margin-top:2px; color:black;" status="true">'+view_element.toUpperCase()+'</span>'										
										+'</div>')				
			}else{
				$('div#tabs').append('<div class="col-md-2" style="margin-top:12px;">'
    									+'<input type="checkbox" class="tabs" id="b'+element_id+'"><br>'
										+'<span id="tab'+element_id+'" style="margin-right:5px; margin-top:2px; color:black;" status="true">'+view_element.toUpperCase()+'</span>'										
										+'</div>')								
			}
		}); // each end			
	});  //post end
});

$(document).delegate('input[type=checkbox]','click',function(){	
	var conf = $(this).is(':checked');
	var tag_id = $(this).attr('id'); // t1,t2,t3...
	console.log(conf);
	if(conf){		
		$('div').find('button.'+tag_id).attr('disabled',false);		
	}else{
		$('div').find('button.'+tag_id).attr('disabled',true);		
	}	
});

$('button#save').click(function(){
	var checked_tabVals = $('input.tabs:checkbox:checked').map(function() {
    	return $(this).attr('id');
	}).get();
	console.log(checked_tabVals);	

	var data = {
		type_id : type_id,
		kind_id : kind_id,
		tabs_val : checked_tabVals.toString()		
	}
	console.log(data);
	$.post('/setting/info/saveSetting',data,function(json){
		console.log(json['result']);
		if(json['result']){
			alert('저장되었습니다');	
			window.location.href = '/setting/info';
		}else{
			alert('Error');
		}
	});
})
</script>