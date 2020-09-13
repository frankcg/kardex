$(document).on('ready',function(){

    $('#wizard-basic').pxWizard();

	function tablaevaluacion(idevaluacion){
		$('#tablaevaluacion').dataTable().fnDestroy();		 	
		$('#tablaevaluacion').DataTable({

			//PARA EXPORTAR
			/*
			dom: "Bfrtip",
			buttons: [{
				extend: "copy",
				className: "btn-sm"
			}, {
				extend: "csv",
				className: "btn-sm"
			}, {
				extend: "excel",
				className: "btn-sm"
			}, {
				extend: "pdf",
				className: "btn-sm"
			}, {
				extend: "print",
				className: "btn-sm"
			}],
			responsive: !0,*/
			
			"order" : [ [ 0, "desc" ] ],
			"ajax" : "prueba/getpendientes/"+idevaluacion,
			"columns" : [
			{
				"data" : "NOMBRE"
			},{
				"data" : "CARGO"
			},{
				"data" : "FECHA_ASIGNACION"
			},{
				"data" : "ASIGNADO_POR"
			},{
				"data" : "OPCIONES"
			},		
			],
			"language": {
				"url": "/yichang/public/cdn/datatable.spanish.lang"
			} 
		});	
	}

	$('#btn_consultar').click(function(){

		var idevaluacion = $('#evaluacion').val();
		if(idevaluacion){
			tablaevaluacion(idevaluacion);
		}
	});

	$("#tablaevaluacion tbody").on('click','button.prueba',function(){

		var codigos = $(this).attr('id');
		$('#codigos').val(codigos);

		$.post('prueba/getevaluacion_cabecera',{
			codigos : codigos
		},function(data){
			//alert(data);
			$('#div_cabecera').html(data);			
		});

		$.post('prueba/getevaluacion_body',{
			codigos : codigos
		},function(data){
			//alert(data)			
			$('#div_body').html(data);			
		});

	});

	$("#div_body").on('click','button.next',function(){

		var next = $(this).attr('id2');
		var fila = $(this).attr('id3');
		var idcompetencia = $(this).attr('id4');
		//console.log(fila + ' -- ' +next+' -- '+idcompetencia);

		$('#'+next).attr('class','wizard-pane active');
		$('#'+fila).removeClass('active');
		$('#'+idcompetencia).attr('class','completed');
	});

	$("#div_body").on('click','button.prev',function(){

		var prev = $(this).attr('id2');
		var fila = $(this).attr('id3');
		var idcompetencia = $(this).attr('id4');
		//console.log(fila + ' -- ' +prev+' -- '+idcompetencia);

		$('#'+prev).attr('class','wizard-pane active');
		$('#'+fila).removeClass('active');
		$('#'+idcompetencia).removeClass('completed');
		
	});

	$("#div_body").on('click','button.finalizar',function(){

		var formData = new FormData($("#form_prueba")[0]);		

		$.ajax({
			url: 'prueba/addprueba',  
			type: 'POST',
			data: formData,
			cache: false,
			contentType: false,
			processData: false,
			success: function(data){
				if(data==1){

					toastr['success']('Se registro correctamente', 'Evaluacion', {
				          closeButton: true,
				          progressBar: true,
				          preventDuplicates: true,
				          newestOnTop: true,
				    });

				    var idevaluacion = $('#evaluacion').val();
					if(idevaluacion){
						tablaevaluacion(idevaluacion);
						$('#m_dar_prueba').modal('hide');
					}

				}else if(data==0){

					toastr['error']('Ocurrio un Error. Comunique a sistema', 'Evaluacion', {
				          closeButton: true,
				          progressBar: true,
				          preventDuplicates: true,
				          newestOnTop: true,
				    });

				}else{
					toastr['warning'](data, 'Evaluacion', {
				          closeButton: true,
				          progressBar: true,
				          preventDuplicates: true,
				          newestOnTop: true,
				    });
				}
			}				
		});

	});

});


