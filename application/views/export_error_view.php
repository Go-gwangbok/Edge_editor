<div class="container">
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

$("#submit").click(function(){
	var editing = $('textarea').val();
	var data = {
		data : editing,
		essay_id : essay_id
	}
	console.log(data);
	$.post('/text/error_chk',data,function(json){		
		console.log(json['update']);		
		console.log(json['result']);		
		console.log(json['essay_id']);		
		var result = json['result'].length;
		console.log(result);		
		if(result > 0){ // Error
			$('#error').children().remove()
			
				//$('#error').children().remove();
				$('#error').append('<br><span><font color="red">Error message : '+json['result']+'</span><br>');
			
		}else{ // Done
			if(json['update']){				
				window.location = '/project/error_list/'+pj_id;						
			}else{
				alert('DB update Error --> editing_update');
			}			
		}		
	});
});
</script>