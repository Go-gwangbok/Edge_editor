<div class="container" style="margin-top:-15px;">   
  <div class="row">    
      <ol class="breadcrumb" style="background:white;">
        <li><a href="/">Home</a></li>
        <li class="akacolor">Stat</li>   
      </ol>            
      <h3 style="margin-left:13px;">Statistics</h3>  
      <h3 class="text-center" style="margin-top:-35px;" id="title">Muse Data</h3>
  </div>  <!-- Nav or Head title End -->
  <br>    
  <div class="row" >
  	<div class="col-md-12">
	  	<!-- Nav tabs -->
		<ul class="nav nav-tabs">
		  <li class="active" id="musedata_title"><a href="#musedata" data-toggle="tab">Muse Data</a></li>
		  <li id="picto_title"><a href="#picto" data-toggle="tab">Picto</a></li>	  
		  <li id="speaking_title"><a href="#speaking" data-toggle="tab">Speaking</a></li>	  
		  <li id="writing_title"><a href="#writing" data-toggle="tab">Writing</a></li>
		  <li id="editor_title"><a href="#editor" data-toggle="tab">Editor</a></li>	  
		</ul>


		<!-- Tab panes -->
		<div class="tab-content">
			<!-- MuseData Stat -->
		  	<div class="tab-pane active" id="musedata">
		  		<br>
			  	<div class="row clearfix">
					<div class="col-md-10 col-md-offset-1 column">
						<div class="form-inline">
							<div class="form-group" id="musedata_gubun">
								<select class="form-control input-sm">
							         <option value="daily">daily</option>
							         <option value="monthly">monthly</option>
							      </select>
							</div>
							<!--
							<div class="form-group">
								 <label for="exampleInputPassword1">Password</label><input type="password" class="form-control" id="exampleInputPassword1" />
							</div>
							-->
							<div class="checkbox">
								 <label><input type="checkbox" id="musebase_cumm"  checked/> Cummulative</label>
							</div> <button type="button" class="btn btn-primary btn-sm" id="musebase_submit">Submit</button>
						</div>



		  		<br>	  			  		
				<div id='musedata_div' style='width: 1000px; height: 400px;'></div>
					</div>
				</div>
		  	</div>

		  	<!-- Picto Stat -->
		  	<div class="tab-pane" id="picto">
		  		<br>
			  	<div class="row clearfix">
					<div class="col-md-10 col-md-offset-1 column">
						<div class="form-inline">
							<div class="form-group" id="picto_gubun">
								<select class="form-control input-sm">
							         <option value="daily">daily</option>
							         <option value="monthly">monthly</option>
							      </select>
							</div>
							<!--
							<div class="form-group">
								 <label for="exampleInputPassword1">Password</label><input type="password" class="form-control" id="exampleInputPassword1" />
							</div>
							-->
							<div class="checkbox">
								 <label><input type="checkbox" id="picto_cumm"  checked/> Cummulative</label>
							</div> <button type="button" class="btn btn-primary btn-sm" id="picto_submit">Submit</button>
						</div>



		  		<br>	  			  		
				<div id='picto_div' style='width: 1000px; height: 400px;'></div>
					</div>
				</div>
			</div>	  

			<!-- EDGE Speaking Stat -->
			<div class="tab-pane" id="speaking">
		  		<br>
			  	<div class="row clearfix">
					<div class="col-md-10 col-md-offset-1 column">
						<div class="form-inline">
							<div class="form-group" id="spekaing_gubun">
								<select class="form-control input-sm">
							         <option value="daily">daily</option>
							         <option value="monthly">monthly</option>
							      </select>
							</div>
							<div class="checkbox">
								 <label><input type="checkbox" id="speaking_cumm"  checked/> Cummulative</label>
							</div> <button type="button" class="btn btn-primary btn-sm" id="spekaing_submit">Submit</button>
						</div>



		  		<br>	  			  		
				<div id='speaking_div' style='width: 1000px; height: 400px;'></div>
					</div>
				</div>
			</div>	  

			<div class="tab-pane" id="setting">
				<br/><br/>
				<div class="row">
					<?php
					foreach ($cateType as $rows) {
						$type_id = $rows->id;
						$name = $rows->name;
						$type = $rows->type;

						if($type == 'musedata'){
						?>
						<div class="col-md-3">						
							<div class="col-md-8 line font-white" id="set_type" set="<?=$name?>" setid="<?=$type_id;?>" style="height:150px; margin-left:40px; background-color:#9FC93C; cursor:pointer;">
								<br/>
								<h1 class="text-center"><span class="glyphicon glyphicon-inbox"></span></h1>
								<h4 class="text-center"><?=strtoupper($name);?></h4>
							</div>
						</div>
						<?php
						}else{
						?>
						<div class="col-md-3">						
							<div class="col-md-8 line font-white" id="set_type" set="<?=$name?>" setid="<?=$type_id;?>" style="height:150px; margin-left:40px; background-color:#f15e22; cursor:pointer;">
								<br/>
								<h1 class="text-center"><span class="glyphicon glyphicon-globe"></span></h1>
								<h4 class="text-center"><?=strtoupper($name);?></h4>
							</div>
						</div>
						<?php
						}
					} ?>			  		
				</div>
				<br><br>
				<!-- Add templete setting -->
				<!-- <button id="w_addbtn" taskid='' class="btn btn-success pull-right" style="display:none; margin-right:100px;">Add Kind</button> -->
				<br>
				<div class="row">			  		
					<div class="col-md-12" id="kind_list">						
						<br>
						<table class="table table-hover" id="table" style="width:953px; margin-left:90px;">
						  	<thead>
								<tr>
									<th class="text-center" style="width:7%;">Num</th>
									<th class="text-center">Kind</th>							
									<th class="text-center" style="width:20%;">Action</th>
								</tr>
							</thead>
							
							<tbody id="set">												
								<!-- Ajax -->
							</tbody>
						</table>
					</div>
				</div>
			</div>	  
			<!-- Tap setting End.-->

			<!-- Rubric setting -->
		  	<div class="tab-pane" id="tagscore">		  		
		  		<br>
				<table class="table table-hover">
				  	<thead>
						<tr>
							<th class="text-center">Num</th>
							<th class="text-center">Kind</th>							
							<th class="text-center">Action</th>
						</tr>
					</thead>
					
					<tbody id="tagscore_list">						
						<!-- Ajax -->
					</tbody>
				</table>
			</div>	
			<!-- Rubric setting End.-->
		</div>
	</div> <!-- col-md-12 -->
  </div> <!-- Row End -->

  <!-- Add Kind modal -->
  	<!-- Modal -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	        <h4 class="modal-title" id="myModalLabel">Modal title</h4>
	      </div>
	      <div class="modal-body">
	        ...
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        <button type="button" class="btn btn-primary">Save changes</button>
	      </div>
	    </div>
	  </div>
	</div>


	
</div>

<script src="/public/js/Chart.js"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">

var kinds_array = new Array();

$('div#set_type').click(function(){	
	var task_id = $(this).attr('setid');	
	//console.log(task_id);
	$('button#w_addbtn').css('display','inline-block').attr('taskid',task_id);

	$('div#set_type').css('opacity',0.4);
	$(this).css('opacity',1);

	if(task_id == 1){ // Musedata
		from_table = 'adjust_data';
	}else{ // Service
		from_table = 'service_data';
	}

	data = {task : task_id,from_table : from_table};
	//console.log(data);
	$.post('/setting/info/getType_data_kind',data,function(json){
		//console.log(json['data_kind']);
		//console.log(set_type);
		var data_kinds = json['data_kind'];
		console.log(data_kinds);

		$('table#table').show();
		$('tbody#set').empty();
		$('div#seltype').empty();
		kinds_array = [];

		$.each(data_kinds,function(i,values){
			var kind = values['kind'];
			var data_kind_id = values['id'];
			kinds_array.push(data_kind_id);
			$('tbody#set').append('<tr href="/setting/info/templet/'+task_id+'/'+data_kind_id+'" style="cursor:pointer;" id="kindhref">'
				+'<td class="text-center">'+(i+1)+'</td>'
				+'<td class="text-center">'+kind.toUpperCase()+'</td>'
				+'<td class="text-center">'						
					+'<button class="btn btn-primary btn-sm">&nbsp;&nbsp;&nbsp;&nbsp;Edit&nbsp;&nbsp;&nbsp;&nbsp;</button>'
				+'</td>'
				+'</tr>');	
		}); // Each end.		
	}); // post End.
});



$('button#w_addbtn').click(function(){	
	task_id = $(this).attr('taskid');

	kindArray = kinds_array.toString();
	// console.log(task_id);
	// console.log(kinds_array);

	data = {task_id : task_id,kind_array : kindArray};

	$.post('/setting/info/addKind',data,function(json){
		console.log(json['result']);

	});
	//$('div#myModal').modal('show');

});


var cate = '<?=$cate?>';


var chartdata_muse;
var chartdata_picto;
var chartdata_speaking;

var chart_muse;
var chart_picto;
var chart_speaking;

//console.log(cate);
$('li#musedata_title').click(function(){
	$('#title').html('Muse Data');	
});

$('li#picto_title').click(function(){
	$('#title').html('Picto');
	var url = '/stat/info/get_pictodata/';
	data = {
		gubun : 'daily'
	};

	/**
	if ( chart_muse ) {
		chart_muse.clearChart();
	}
	if ( chart_speaking ) {
		chart_speaking.clearChart();
	}
	**/

	ajaxMuseDataPost(data, url, chartdata_picto,  chart_picto, true);
});

$('li#speaking_title').click(function(){
	$('#title').html('EDGE Speaking');
	var url = '/stat/info/get_speakingdata/';
	data = {
		gubun : 'daily'
	};

	/**	if ( chart_muse ) {
			chart_muse.clearChart();
		}
		if ( chart_picto ) {
			chart_picto.clearChart();
		}
		**/

	ajaxMuseDataPost(data, url, chartdata_speaking,  chart_speaking, true);
});

$('li#rubric_title').click(function(){
	$('#title').html('Rubric Setting');
});




function init_chart() {
	chartdata_muse = new google.visualization.DataTable();
	chartdata_picto = new google.visualization.DataTable();
	chartdata_speaking = new google.visualization.DataTable();

	chartdata_muse.addColumn('date', 'Date');
	chartdata_muse.addColumn('number', 'Eaasy Count');
	chartdata_muse.addColumn('number', 'Sentence Count');

	chartdata_picto.addColumn('date', 'Date');
	chartdata_picto.addColumn('number', 'Eaasy Count');
	chartdata_picto.addColumn('number', 'Sentence Count');

	chartdata_speaking.addColumn('date', 'Date');
	chartdata_speaking.addColumn('number', 'Eaasy Count');
	chartdata_speaking.addColumn('number', 'Sentence Count');

	chart_muse = new google.visualization.AnnotationChart(document.getElementById('musedata_div'));
	chart_picto = new google.visualization.AnnotationChart(document.getElementById('picto_div'));
	chart_speaking = new google.visualization.AnnotationChart(document.getElementById('speaking_div'));

	data = {
		gubun : 'daily'
	};

	var url = '/stat/info/get_musedata/';

	ajaxMuseDataPost(data, url, chartdata_muse,  chart_muse, true);
}


function ajaxMuseDataPost(data, url, chartdata, chart, cumm){
	

	//alert(data);

	$.post(url,data,function(json) {
		var stat_list = json['stat_list'];

		if (stat_list.length < 1) {
			alert('no stat info!!!');
			return false;
		}

		console.log(stat_list);

		chartdata.removeRows(0, chartdata.getNumberOfRows());

		essay_sum = 0;
		word_sum = 0;
		sentence_sum = 0;
		$.each(stat_list,function(i,values){
			var regdate = values['date'];
			var essay_count = parseInt(values['essay_count']);
			var word_count = parseInt(values['word_count']);
			var sentence_count = parseInt(values['sentence_count']);

			essay_sum += essay_count;
			word_sum += word_count;
			sentence_sum += sentence_count;

			/**
			data.addRows([
				[new Date(regdate), (essay_count * 1), undefined, undefined, (sentence_count * 1), undefined, undefined]]);
			**/
			if (cumm) {
				chartdata.addRows([
					[new Date(regdate), essay_sum, sentence_sum] ]);
			} else {
				chartdata.addRows([
					[new Date(regdate), essay_count, sentence_count] ]);
			}
			

		}); // End of Each

		drawChart(chartdata, chart);

	}); // Post End
}

function drawChart(chartdata, chart) {
	

	var options = {
		displayAnnotations: false,
		
	};

	chart.draw(chartdata, options);
}

/***
$('table#table').hide();
data = {
	gubun : 'daily'
}

google.setOnLoadCallback(function() { ajaxMuseDataPost(data, false); });
****/
google.load('visualization', '1.1', {'packages':['annotationchart']});
google.setOnLoadCallback(init_chart);
	
/***
$(document).ready(function(){
	$('table#table').hide();

	google.load('visualization', '1.1', {'packages':['annotationchart']});
	alert('bbb');
	google.setOnLoadCallback(init_chart);
}); // Document End
***/

$('button#musebase_submit').click(function(){ 

	var gubun = $("#musedata_gubun option:selected").val();

	if ($("input:checkbox[id='musebase_cumm']").is(":checked")) {
		var cumm = true;
	} else {
		var cumm = false;
	}

	data = {
		gubun : gubun
	}

	//google.load('visualization', '1.1', {'packages':['annotationchart']});

	var url = '/stat/info/get_musedata/';

	ajaxMuseDataPost(data, url, chartdata_muse,  chart_muse, cumm);


//google.setOnLoadCallback(function() { ajaxMuseDataPost(url, data); });
});

$('button#picto_submit').click(function(){ 

	var gubun = $("#picto_gubun option:selected").val();

	if ($("input:checkbox[id='picto_cumm']").is(":checked")) {
		var cumm = true;
	} else {
		var cumm = false;
	}

	data = {
		gubun : gubun
	}

	//google.load('visualization', '1.1', {'packages':['annotationchart']});

	var url = '/stat/info/get_pictodata/';

	ajaxMuseDataPost(data, url, chartdata_picto,  chart_picto, cumm);


//google.setOnLoadCallback(function() { ajaxMuseDataPost(url, data); });
});



// Tab Kind href
$(document).delegate('tr#kindhref','click',function(){			
	window.document.location = $(this).attr("href");      
});

$(document).delegate('tr#active_mem','click',function(){		
	//console.log($(this).attr('href'));
	window.document.location = $(this).attr("href");      
});

// Tag & Score Setting Button
$(document).delegate('tr#tagscore_action','click',function(){			
	window.document.location = $(this).attr("href");      
});

$(document).delegate('button#accept','click',function(){	
	var usr_id = $(this).attr('usrid');
	var href = '/setting/info/member_edit/'+usr_id;
	console.log(usr_id);

	$.post('/setting/info/accept',{usr_id : usr_id},function(json){
		accept = json['result'];
		if(accept){
			window.document.location = href;
		}
	});	
});

$(document).delegate('button#decline','click',function(){
	var usr_id = $(this).attr('usrid');
	//console.log(usr_id);
	$.post('/setting/info/decline',{usr_id:usr_id},function(json){
		var result = json['result'];
		console.log(result);
		location.reload();
	});
});

</script>
