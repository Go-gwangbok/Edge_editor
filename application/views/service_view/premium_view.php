<div class="container" style="margin-top:-15px;">		
	<div class="row">		
		<ol class="breadcrumb" style="background:white;">
	       	<li><a href="/">Home</a></li>
	       	<li><a href="/service/">Service</a></li>	       		       	
    	   	<li class="akacolor"><a href="/writing/premium/">Premium</a></li>
	    </ol> <!-- Navi end -->
	</div>					
	<h3 class="text-center"> Writing Premium </h3>	
	<!--button class="btn btn-default pull-right" style="border-color:#f15e22; margin-top:-40px;" id="total" disabled></button--> 
	<br>
  
	<div class="btn-toolbar">
	    <div class="btn-group">
	        <button type="button" class="btn btn-primary" id="draft" disabled>Mail to Editor</button>
	    </div>
	    <div class="btn-group">
	        <button type="button" class="btn btn-primary" id="submit" <?php if ($premium['status'] != "In Progress" || $premium['filename'] == "" ) { ?>disabled<?php } ?>>Submit</button>
	    </div>
	</div>
	<br>

	<div class="div-box-line-promp">
	    <dl>
	        <dt style="margin:0 10px 0 10px">Prompt</dt>              
	        <dd style="margin:0 15px 0 25px" id="prompt"><?=$premium['title']?></dd>
	    </dl>       
	</div>
	<br>

	<table class="table table-striped table-bordered">					
		<tbody>
		<tr>
			<td width="100"><b>KIND</b></td>
			<td width="200"><?=$premium['kind']?></td>
			<td width="120"><b>Word Count</b></td>
			<td><?=$premium['word_count']?></td>
			<td width="100"><b>Date</b></td>
			<td><?=$premium['start_date']?></td>
		</tr>
		<tr>
			<td><b>Editor</b></td>
			<td>	<select class="form-control" id="usr_id">
					<option value="0">Select</option>
					<?php foreach ($editor_list as $editor) { ?>	
					<option value="<?=$editor['id'];?>" <?php if ($editor['id'] == $premium['usr_id']) echo "selected"; ?>><?=$editor['name'];?></option>";
					<?php } ?>
				</select>
			</td>
			<td><b>Re-Submit</b></td>
			<td><?=$premium['re_submit']?></td>
			<td><b>Status</b></td>
			<td><?=$premium['status']?></td>
		</tr>
		<?php if ($premium['status'] != "ToDo") { ?>
		<tr>
			<td><b>DocFile</b></td>
			<td colspan="5">
				<?php
				   if ($premium['filename'] != "") {
				?>
				    <a href="/writing/download/<?=$service_id;?>/<?=$premium['essay_id'];?>/<?=$premium['filename'];?>/"><?=$premium['filename'];?></a>
			         <?php
			            }             
			          ?>   
				<form action="/upload/upload_docfile" method="post" enctype="multipart/form-data">
				<input type="file" name="userfile" id="input" size="20" />
				<input type="hidden" name="essay_id" id="essay_id" value="<?=$premium['essay_id'];?>">
				<input type="hidden" name="kind" id="kind" value="<?=$service_id;?>">
				<button class="btn btn-danger btn-xs" type="submit" value="upload" id="upload" " style="margin-top:5px;"/>Upload</button>				
				<span class="text-danger" style="margin-left:10px;"></span>			
			</form>

				<!--div class="col-md-12" style="margin-top:15px;">                

			        
			        <div>          
			          <?php
			            echo trim($premium['reason']);             
			          ?>      
			        </div> 
			        <br>      
			      </div-->  <!-- col-md-12 -->
			</td>
		</tr>
		<?php } ?>
		<tr>
			<td><b>Writing</b></td>
			<td colspan="5">
			      <div class="col-md-12" style="margin-top:15px;">                
			        
			        <div>          
			          <?php
			            echo trim($premium['re_raw_writing']);             
			          ?>      
			        </div> 
			        <br>      
			      </div>  <!-- col-md-12 -->
			</td>
		</tr>
		<?php if ($premium['re_submit'] == "Yes") { ?>
		<tr>
			<td><b>Reason</b></td>
			<td colspan="5">
				<div class="col-md-12" style="margin-top:15px;">                
			        
			        <div>          
			          <?php
			            echo trim($premium['reason']);             
			          ?>      
			        </div> 
			        <br>      
			      </div>  <!-- col-md-12 -->
			</td>
		</tr>
		<?php } ?>
		</tbody>
	</table>	

<?php
	if ($premium['re_submit'] == 'Yes') {
?>
<div class="panel-group" id="related_essay">

  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#related_essay" href="#collapse1">
          RE-SUBMITED ESSAY
        </a>
      </h4>
    </div>
    <div id="collapse1" class="panel-collapse collapse">
      <div class="panel-body" style="color:#333333;">
        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
      </div>
    </div>
  </div>

  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#related_essay" href="#collapse3">
          <b>ORIGIN ESSAY</b>
        </a>
      </h4>
    </div>
    <div id="collapse3" class="panel-collapse collapse">
      <div class="panel-body" style="color:#333333;">
                  <table class="table table-striped table-bordered">					
		<tbody>
		<tr>
			<td width="100"><b>KIND</b></td>
			<td width="200"><?=$premium['kind']?></td>
			<td width="120"><b>Word Count</b></td>
			<td><?=$premium['word_count']?></td>
			<td width="100"><b>Date</b></td>
			<td><?=$premium['start_date']?></td>
		</tr>
		<tr>
			<td><b>Editor</b></td>
			<td>	<select class="form-control" id="usr_id">
					<option value="0">Select</option>
					<?php foreach ($editor_list as $editor) { ?>	
					<option value="<?=$editor['id'];?>" <?php if ($editor['id'] == $premium['usr_id']) echo "selected"; ?>><?=$editor['name'];?></option>";
					<?php } ?>
				</select>
			</td>
			<td><b>Re-Submit</b></td>
			<td><?=$premium['re_submit']?></td>
			<td><b>Status</b></td>
			<td><?=$premium['status']?></td>
		</tr>
		</tbody>
		</table>
      </div>
    </div>
  </div>

</div>
<?php
	}
?>

</div>

<script>

var service_id = '<?=$service_id;?>';
var service_name = '<?=$service_name;?>';
var essay_id = <?=$premium['essay_id'];?>;
var orig_essay_id = <?=$premium['orig_essay_id'];?>;
var orig_usr_id = <?=$premium['usr_id'];?>;
var status = '<?=$premium['status']?>';

var url = '';

/***
function formatTime(time) {
    var min = parseInt(time / 6000),
        sec = parseInt(time / 100) - (min * 60),
        hundredths = pad(time - (sec * 100) - (min * 6000), 2);
    //return (min > 0 ? pad(min, 2) : "00") + ":" + pad(sec, 2) + ":" + hundredths;
    return (min > 0 ? pad(min, 2) : "00") + ":" + pad(sec, 2);
}

function pad(number, length) {
    var str = '' + number;
    while (str.length < length) {str = '0' + str;}
    return str;
}
***/

$('select#usr_id').change(function() {
	//alert($(this).val());
	//alert($(this).children("option:selected").text());
	if (status == "Done") return false;
	
	var usr_id = $('select#usr_id option:selected').val();

	if (usr_id == "0") {
		$('#draft').prop('disabled', true);
	} 
	else if (orig_usr_id != usr_id) {
		$('#draft').prop('disabled', false);  
	} 
	else {
		$('#draft').prop('disabled', true);  
	}
});

// 결과 전송
$('button#draft').click(function(){ 
	var usr_id = $('select#usr_id option:selected').val();
	if (usr_id == "0") {
		alert ("Editor sould be seleted!!!");
		return false;
	}
	var usr_name = $('select#usr_id option:selected').text();
	if (!confirm("Would you like to send " + usr_name + " a mail?")) {
		return false;
	}
  
  	data = {
		essay_id : essay_id,
		usr_id : usr_id,
		service_id : service_id
	}
	console.log(data);
	
	$.ajax(
	{
		url: '/writing/assign_editor_to_premium',
		type: 'POST',         
		data: data,
		dataType: 'json',
		success: function(json)
		{      
			if(json['status'])
			{
				// 정상적으로 처리됨
				alert('It’s been successfully processed!');
				//window.history.back();
				//location.reload();
				window.location.replace('/writing/premium/'); // 리다이렉트할 주소
			}
			else {
				alert(json['error_msg']);
			}
		}
	});

});  

$('button#submit').click(function(){ 
  	data = {
		essay_id : essay_id,
		service_id : service_id
	}
	console.log(data);
	
	$.ajax(
	{
		url: '/writing/submit_premium',
		type: 'POST',         
		data: data,
		dataType: 'json',
		success: function(json)
		{      
			if(json['status'])
			{
				// 정상적으로 처리됨
				alert('It’s been successfully processed!');
				window.location.replace('/writing/premium/'); // 리다이렉트할 주소
			}
			else
			{
				alert('all_list --> submit DB Error');
			}
		}
	});

});  

<?php
	if ($premium['re_submit'] == 'Yes') {
?>
function ajaxPost(url,data){
	$.post(url,data,function(json) {				
		console.log(json);
		var essay_count = json['count'];
		var essay_list = json['list'];

		$('#related_essay').children().remove();
		for(var i = 0; i < json['list'].length; i++) {
			var r_prompt = essay_list[i].prompt;
			var r_essay_id = essay_list[i].essay_id;
			var r_orig_essay_id = essay_list[i].orig_essay_id;
			if (r_essay_id == r_orig_essay_id) {
				var r_title = "ORIGIN ESSAY";
				var r_re_submit = "No";
			} else {
				var r_title = "RE-SUBMITED ESSAY";
				var r_re_submit = "Yes";
			}
			var r_raw_txt = essay_list[i].raw_txt;
			var r_kind = essay_list[i].kind;
			var r_start_date = essay_list[i].start_date;
			var r_word_count = essay_list[i].word_count;
			var r_editor_name = essay_list[i].usr_name;
			var r_filename = essay_list[i].filename;
			var r_draft = essay_list[i].draft;
			var r_submit = essay_list[i].submit;
			var r_status = "ToDo";
			if (r_submit > 0) {
				r_status = "Done";
			} else if (r_draft == 1) {
				r_status = "In Progress";
			}
			var r_reason = essay_list[i].reason;
			
			console.log(r_prompt);


			var html = '<div class="panel panel-default">\n'
				    +'<div class="panel-heading">\n'
				      +'<h4 class="panel-title">\n'
				      +'<a data-toggle="collapse" data-parent="#related_essay" href="#collapse' + i +'">\n'
				      +'    <b>' + r_title + '</b>\n'
				      +'  </a>\n'
				      +'</h4>\n'
				    +'</div>\n'
				    +'<div id="collapse' + i + '" class="panel-collapse collapse">\n'
				    +'  <div class="panel-body" style="color:#333333;">\n'
				    +'    <table class="table table-striped table-bordered">\n'
				    +'<tbody>\n'
				    +'<tr>\n'
			             +'  <td><b>Prompt</b></td>\n'
			 	    +'  <td  colspan="5"><a href="/writing/view_premium/'+r_essay_id+'/'+'">'  + r_prompt + '</a></td>\n'
				    +'</tr>\n'
				    +'<tr>\n'
				     +' <td width="100"><b>Editor</b></td>\n'
				     +' <td width="200">' + r_editor_name + '</td>\n'
				     +' <td width="120"><b>Date</b></td>\n'
				     +' <td>'+ r_start_date + '</td>\n'
				     +' <td width="100"><b>Status</b></td>\n'
				     +' <td>' + r_status + '</td>\n'
				    +'</tr>\n'
				    +'<tr>\n'
			             +'  <td><b>DocFile</b></td>\n'
			 	    +'  <td  colspan="5">'  + r_filename + '</td>\n'
				    +'</tr>\n'
				    +'<tr>\n'
			             +'  <td><b>Writing</b></td>\n'
			 	    +'  <td  colspan="5">'  + r_raw_txt + '</td>\n'
				    +'</tr>\n'
				    +'<tr>\n'
			             +'  <td><b>Reason</b></td>\n'
			 	    +'  <td  colspan="5">'  + r_reason + '</td>\n'
				    +'</tr>\n'


				    +'</tbody>\n'
				    +'</table>\n'
				    +'</div>\n'
				    +'</div>\n';

			$('#related_essay').append(html);
			/***
			$('#related_essay').append(' \
				  <div class="panel panel-default">\
				    <div class="panel-heading">\
				      <h4 class="panel-title">\
				        <a data-toggle="collapse" data-parent="#related_essay" href="#collapse3">\
				          <b>ORIGIN ESSAY</b>\
				        </a>\
				      </h4>\
				    </div>\
				    <div id="collapse3" class="panel-collapse collapse">\
				      <div class="panel-body" style="color:#333333;">\
				        <table class="table table-striped table-bordered">\
				    <tbody>\
				    <tr>\
				      <td width="100"><b>KIND</b></td>\
				      <td width="200"><?=$premium['kind']?></td>\
				      <td width="120"><b>Word Count</b></td>\
				      <td><?=$premium['word_count']?></td>\
				      <td width="100"><b>Date</b></td>\
				      <td><?=$premium['start_date']?></td>\
				    </tr>\
				    </tbody>\
				    </table>\

				          </div>\
				    </div>\
				      ');
			***/

		}



/**
		$('tbody#list').children().remove();
		var data_list = json['list'];	
		var num = (list * page) - (list-1);
		total_count = json['count'];
		$('button#total').empty();
		$('button#total').append('<font color="red">Count : '+total_count+'</font>');
		//$('li#tab_history').next().append('<p>');	

		for(var i = 0; i < json['list'].length; i++) {
			var id = data_list[i]['essay_id'];
			var task = data_list[i]['type'];
			//var timer = formatTime(data_list[i]['time']);	
			var prompt = data_list[i]['prompt'];
			//var raw_txt = data_list[i]['raw_txt'];
			var kind = data_list[i]['kind'];
			var editor = data_list[i]['name'];
			if (editor == "") editor = "-";
			var word_count = data_list[i]['word_count'];
			var draft = data_list[i]['draft'];
			var submit = data_list[i]['submit'];
			var orig_essay_id = data_list[i]['orig_essay_id'];
			var re_submit = "No";
			if (orig_essay_id != id) {
				re_submit = "Yes";
			}
			date = data_list[i]['start_date'];
			var status = "ToDo";
			var button_style = "btn-default";
			if (submit == 1) {
				status = "Done";
				button_style = "btn-success";
			} else if (draft == 1) {
				status = "In Progress";
				button_style = "btn-success";
			}

			if($.isNumeric(prompt)){
				prompt = prompt+raw_txt.substr(0,120);
			}
			
			$('tbody#list').append('<tr id='+i+' class="clickableRow" style="cursor:pointer;" href="/writing/view_premium/'+id+'/'+'"><td class="text-center">'+num+'</td><td>'																				 
				+prompt.replace(/"/gi,'')+'</td><td class="text-center">'
				+editor+'</td><td class="text-center">'
				+word_count+'</td><td class="text-center">'
				+kind.toUpperCase()+'</td><td class="text-center">'
				+date+'</td><td class="text-center">'
				+re_submit+'</td>'
				+'<td class="text-center"><button class="btn ' + button_style + ' btn-sm">&nbsp;&nbsp;'
				+ status + '&nbsp;&nbsp;</button></td>');				
			num++;			
		}	
**/			
			
	});
}
<?php
	}
?>

$(document).ready(function(){		

<?php
	if ($premium['re_submit'] == 'Yes') {
?>
	data = {
		orig_essay_id : orig_essay_id,
		essay_id : essay_id,
		service_id : service_id
	}
	console.log(data);
	url = '/writing/get_related_servicedata';

	ajaxPost(url,data);
<?php
	}
?>
});

</script>
