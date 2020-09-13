$(document).on('ready',function(){

	$("#btn_ingresar").click(function() {

		var user=$("#user").val()
		var pass=$("#pass").val()
		
		if(user==''){
			$('#msj').html('Ingrese Usuario');
		}else if(pass==''){
			$('#msj').html('Ingrese Password');
		}else{
			$('#msj').html('');
			$.post('index/login',{
                user : user,
                pass : pass				
			},function(data){
				if(data==1){
					window.location="panel";
				}else if(data==0){
					$('#msj').html('Usuario y/o Password incorrecto');
				}else{
					$('#msj').html(data);
					//$("#msj").focus();
				}
			})
		}
		
	})

	/*$("#usuario").keyup(function() {
		$("#user_tooltip").css('display','none')
	})
	
	$("#password").keyup(function() {
		$("#pswd_tooltip").css('display','none')
	})*/

    $(document).bind('keypress', function(e) {
        if(e.keyCode==13){
            $('#btn_ingresar').trigger('click');
        }
    });



});