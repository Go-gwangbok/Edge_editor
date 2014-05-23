<div class="container">
    <div class="row">
	    <div class="col-md-4 col-md-offset-4">
            <h4>Sign Up</h4>
            <form class="form-signin">        
                <input type="text" class="form-control" id="inputName" placeholder="Name" autofocus>
                <input type="text" class="form-control" id="inputEmail" placeholder="Email address">
                <input type="password" class="form-control" id="inputPassword" placeholder="Password">          
                <button class="btn btn-lg btn-primary btn-block" type="submit" id="SignUp">Sign up</button>
            </form>
        </div>
    </div>
</div>
<!-- javascript -->		
<script src="http://code.jquery.com/jquery-latest.js"></script>	
<script src="/public/js/bootstrap.js"></script>
<script type="text/javascript">
	$('#SignUp').click(function()
	{        
        var form_data = {
                name: $("#inputName").val(),                        	
            	email: $("#inputEmail").val(),                         	                        	
            	pass: $("#inputPassword").val()            	
        };
        console.log(form_data);                
        $.ajax({
                type: "POST",
                url: '/signup/sign_up',                
                data: form_data,
                dataType: 'json',
                success: function(json) {
                        if(json['status'] == 'true') {
                                alert("Joining has been done successfully! Please wait for approval.");
                                window.location.replace('/'); // 리다이렉트할 주소
                        }else if(json['status'] == 'empty'){
                            alert("You must enter all the blanks!");
                        }else if(json['status'] == 'email'){
                            alert("Please enter the correct email format!");
                        }else if(json['status'] == 'false'){
                        	alert("JoinDb --> sign_up Error");
                        }else if(json['status'] == 'exist'){
                            alert("exist");
                        }
                }
        });
        return false;
	});		
</script>
