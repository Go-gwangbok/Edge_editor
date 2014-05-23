<div class="container" style="margin-top:-15px;">		
	<div class="row">		
		<ol class="breadcrumb" style="background:white;">
	       	<li><a href="/">Home</a></li>	       		       	
		    <li><a href="/musedata/project/">Project</a></li>   
		    <li><a href="/musedata/project/export/<?=$pj_id;?>">Export</a></li>   
	    	<li><a href="/errordata/export_error/<?=$pj_id;?>">Error list</a></li>   	       	
	    	<li class="akacolor">Edit</li>
	    </ol>		
	</div>
	<br>
	<!-- Nav tabs -->
	<ul class="nav nav-tabs">
	  <li class="active"><a href="#org" data-toggle="tab">Original</a></li>
	  <li><a href="#error_text" data-toggle="tab">Error</a></li>	  
	</ul>

	<!-- Tab panes -->
	<div class="tab-content">		
	  	<div class="tab-pane active" id="org"><br><?=nl2br($raw);?></div>
	  	<div class="tab-pane" id="error_text">
	  	<br>
	  	<div id = "error">
			<!-- Ajax -->
		</div>
			<br>
			<textarea class="form-control" rows="20" id="editing"><?=$editing;?></textarea>	
	  	</div>	  
	</div>	
	<br>
	<button class="btn btn-danger pull-right" id="submit">Submit</button>	
	<!-- <button class="btn btn-danger pull-right" id="mistakes">mistakes</button> -->
</div>
<script type="text/javascript">
var pj_id = '<?=$pj_id;?>';
var data_id = '<?=$data_id;?>';
var type = '<?=$type;?>';
console.log('<?=$cate?>');

$(document).ready(function(){
	var editing = $('#editing').val();
	if(editing == ''){
		alert("Not found Data");
		window.history.back(-1);
	}
	console.log(editing);
	var data = {
		data : editing,
		data_id : data_id,
		type : type
	}
	
	$.post('/errorchk/error_chk_post',data,function(json){
		var result = json['result'];
		console.log(result);		
		
		$('#error').empty();
		var error_list = '';
		
		if(result.length != 0){
			$.each(result,function(i,value){
				if(value != 'Replace Error' && value != '// Slash count Error'){
					error_list += value+' : ';
				}				
			});			

			$('#error').append('<span><font color="red">U tag count : </span></font>'+json['u_tag']+'<br>'
								+'<span><font color="red">// Slash count : </span></font>'+json['slash_tag']+'<br>'
								+'<span><font color="red">Error message : </span></font>'+error_list.slice(0,-2)+'<br>'
								);		
		}		
	});
});

$("#submit").click(function(){
	var editing = $('textarea').val();
	var data = {
		data : editing,
		data_id : data_id,
		type : type
	}
	//console.log(data);
	$.post('/errorchk/error_chk_post',data,function(json){				
		
		var result = json['result'];
		console.log(result);		
		if(result == true){ // Done
			window.location = '/errordata/export_error/'+pj_id;									
		}else{ // Error		
			
			$('#error').empty();							
			var error_list = '';

			if(result.length != 0){			
				$.each(result,function(i,value){
					if(value != 'Replace Error' && value != '// Slash count Error'){
						error_list += value+' : ';
					}				
				});
				console.log(error_list.slice(0,-2));

				$('#error').append('<span><font color="red">U tag count : </span></font>'+json['u_tag']+'<br>'
									+'<span><font color="red">// Slash count : </span></font>'+json['slash_tag']+'<br>'
									+'<span><font color="red">Error message : </span></font>'+error_list.slice(0,-2)+'<br>'
									);					
			}
		}		
	});
});

$("#mistakes").click(function(){
	var editing = $('textarea').val();
	var data = {
		essay : editing,				
	}
	console.log(data);
	$.post('/upload/get_mistakes',data,function(json){				
		console.log(json['mistakes']);
			
	});
});
</script>