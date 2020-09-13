$(document).on('ready',function(){

	$('#contenedor_evaluacion').change(function(){
		var idevaluacion1 = $('#evaluacion1').val();
		var idevaluacion2 = $('#evaluacion2').val();

		if(idevaluacion1 && idevaluacion2){
			$.post('comparacion/getpersonal',{
				idevaluacion1 : idevaluacion1,
				idevaluacion2 : idevaluacion2
			},function(data){
				//alert(data);
				$('#evaluado').html(data);
			});
		}		
	});

	$('#btn_consultar').click(function() {

		var idevaluacion1 = $('#evaluacion1').val();
		var idevaluacion2 = $('#evaluacion2').val();
		var idevaluado = $('#evaluado').val();

		var name_idevaluacion1 = $('#evaluacion1 option:selected').html();
		var name_idevaluacion2 = $('#evaluacion2 option:selected').html();

		if(idevaluacion1 && idevaluacion2 && idevaluado){
			getidevaluacion1(idevaluacion1, idevaluado);
			getidevaluacion2(idevaluacion2, idevaluado);
			$('#title_idevaluacion1').html(name_idevaluacion1);
			$('#title_idevaluacion2').html(name_idevaluacion2);
			$('#contenedor_comparacion').show();
		}else{
			$('#contenedor_comparacion').hide();
		}		
	});

	function getidevaluacion1(idevaluacion1, idevaluado) {
		$("#body_idevaluacion1").load('comparacion/getidevaluacion/'+idevaluacion1+'/'+idevaluado, 
			function(data) { //alert(data);
		});
	}

	function getidevaluacion2(idevaluacion2, idevaluado) {
		$("#body_idevaluacion2").load('comparacion/getidevaluacion/'+idevaluacion2+'/'+idevaluado, 
			function(data) { //alert(data); 
		});
	}

});