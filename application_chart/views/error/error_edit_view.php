<div class="container" style="margin-top:-15px;">		
	<div class="row">		
		<ol class="breadcrumb" style="background:white;">
	       	<li><a href="/">Home</a></li>	       		       	
		    <li><a href="/musedata/project/">Project</a></li>   
		    <li><a href="/musedata/project/export/<?=$pj_id;?>">Export</a></li>   
	    	<li><a href="/error/export_error/<?=$pj_id;?>">Error list</a></li>   	       	
	    	<li class="akacolor">Edit</li>
	    </ol>		
	</div>
	<div id = "error">
		<?		
		$i = 0;
		foreach ($error_list as $value) {
			if($i > 1){
		?>
		<font color="red"><span>Error message : </span></font>
		<?			
			}	
			echo  $value."<br>";
		$i++;
		}
		?>
	</div>
	<br>
	<textarea class="form-control" rows="20"><?=$editing;?></textarea>	
	<br>
	<button class="btn btn-danger pull-right" id="submit">Submit</button>
</div>
<script type="text/javascript">
var pj_id = '<?=$pj_id;?>';
var essay_id = '<?=$essay_id;?>';
console.log('<?=$cate?>');

$("#submit").click(function(){
	var editing = $('textarea').val();
	var data = {
		data : editing,
		essay_id : essay_id
	}
	console.log(data);
	$.post('/error/error_chk',data,function(json){				
		var result = json['result'].length;
		console.log(result);		
		if(result > 0){ // Error
			$('#error').children().remove()				
				$('#error').append('<br><span><font color="red">Error message : '+json['result']+'</span><br>');			
		}else{ // Done
			if(json['update']){				
				window.location = '/error/export_error/'+pj_id;						
			}else{
				alert('DB update Error --> editing_update');
			}			
		}		
	});
});
</script>