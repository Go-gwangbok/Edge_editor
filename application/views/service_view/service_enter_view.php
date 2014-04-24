<div class="container" style="margin-top:-15px;">	
	<div class="row">		
		<ol class="breadcrumb" style="background:white;">
        <li><a href="/">Home</a></li>        
        <li><a href="/service">Service</a></li>   
        <li><a href="/service/serviceType/<?=$service_name;?>"><?=ucfirst($service_name);?></a></li>   
        <li class="akacolor"><?=$year.' - '.$str_month?></li>   
      </ol>                  	
      	<h3 class="text-center"><?=$year.' - '.$str_month?></h3>  		        
    </div>
    <!-- <div class="col-md-12 text-right" style="margin-top:-33px;">
        
    </div>   -->
    <br>
    <div class="row" id="memlist">
    	<!-- Ajax -->   


    </div>    
</div>
<script>
var cate = '<?=$cate;?>';
var month = '<?=$int_month;?>';
var year = '<?=$year;?>';
var service_id = '<?=$service_id;?>';
var service_name = '<?=$service_name?>';

console.log(service_id);
$('div#memlist').css('opacity',0);
$('div').animate({opacity:100},9000);
$(document).ready(function(){   
    data = {
        service_id : service_id,
        year : year,
        month : month
    }
    console.log(data);
    $.post('/service/get_enter_users',data,function(json){        
        console.log(json['memlist']);
        var result = json['memlist'];

        $.each(result,function(i,value){
            var name = value['name'];
            var done = value['done_count'];           
            var usr_id = value['id'];
            var work_type = value['type'];

            if(work_type == 'partTime'){
                work_type = 'P.T';
            }else if(work_type == 'case'){
                work_type = 'Case';
            }

            $('div#memlist').append('<div class="col-lg-3" style="margin-bottom:20px;">'
                                    +'<a href="#"><div class="col-lg-12 line" style="height:40px; background-color:#df6c6e; border-bottom-color:#fff; padding-top:9px;">'
                                        +'<div class="col-md-10 font-white text-left">'+name+'</div>'
                                        +'<div class="col-md-2 font-white text-right">'+work_type+'</div>' // 에디터 근무 방법  t = part time  cc = case by case                                                                                    
                                        +'</div>'
                                    +'</a>'                                    
                                    +'<a href="#"><div class="col-lg-12 line clickpass" url="/service/member_enter/'+service_name+'/'+month+'/'+year+'/'+usr_id+'" style="height:100px; border-top-color:#fff; background-color:#df6c6e;  padding-top:7px;">'                                        
                                            +'<h2 class="font-white text-center" style="margin-top:25px;">'+done+'</h2>'
                                        +'</div>'
                                    +'</a>'                                    
                                    +'</div>'
                                    );
        })        
    });
});

$('div').delegate('div.clickpass','click',function(){
    console.log('a');
    var url = $(this).attr('url');
    window.location.href = url;
});
</script>