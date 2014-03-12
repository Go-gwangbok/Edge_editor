<div class="container">
	<div id = "error">
		<?		
		foreach ($error_list as $value) {
			echo $value."<br>";
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
		console.log(json['result']);		
		console.log(json['essay_id']);		
		var result = json['result'].length;
		console.log(result);		
		if(result > 0){ // Error
			$('#error').children().remove()
			for(var i = 0; i < result; i++){
				$('#error').append("<span>"+json['result'][i]+"</span><br>");
			}			
		}else{ // Done
			if(json['update']){
				//window.location = '/project/export/'+pj_id;		
				window.history.back();
			}else{
				alert('DB update Error --> editing_update');
			}			
		}		
	});
});
</script>