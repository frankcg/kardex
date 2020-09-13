$(document).on('ready',function(){


	function tablaevaluciones(){
		$('#tablaevaluciones').dataTable().fnDestroy();		 	
		$('#tablaevaluciones').DataTable({

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
			"ajax" : "evaluacion/getevaluaciones",
			"columns" : [
			{
				"data" : "IDEVALUACION"
			},{
				"data" : "NOMBRE"
			},{
				"data" : "FECHA_APERTURA"
			},{
				"data" : "FECHA_CIERRE"
			},{
				"data" : "CANT"
			},{
				"data" : "ESTADO"
			},{
				"data" : "FECHA"
			},{
				"data" : "OPCIONES"
			},
			],
			"language": {
				"url": "/yichang/public/cdn/datatable.spanish.lang"
			} 
		});	
	}

	tablaevaluciones();

	function listcompetencia(idevaluacion){
		$.post('evaluacion/listcompetencia',{
			idevaluacion : idevaluacion
		},function(data){
			//alert(data);
			$('#competencia').html(data);
		});
	}

	//BOTON NUEVA COMPETENCIA
	$('#btn_nuevo_evaluacion').click(function(){		
		$('#btn_add_evaluacion').show();
		$('#btn_update_evaluacion').hide();		
		$('#form_evaluacion')[0].reset();
		$('#title_evaluacion').html('Añadir Evaluacion');
		listcompetencia(0);
	});


	$('#btn_add_evaluacion').click(function(){

		var nombre = $('#nombre').val();
		var fecha_apertura = $('#fecha_apertura').val();
		var fecha_cierre = $('#fecha_cierre').val();
		var competencia = $('#competencia').val();		
		var observacion = $('#observacion').val();

		var formData = new FormData($("#form_evaluacion")[0]);

		if(nombre == ''){ $('#msj_evaluacion').html('Ingrese Nombre Competencia');}
		else if(fecha_apertura == ''){ $('#msj_evaluacion').html('Ingrese Fecha de Apertura');}
		else if(fecha_cierre == ''){ $('#msj_evaluacion').html('Ingrese Fecha de Cierre');}
		else if(fecha_apertura > fecha_cierre){ $('#msj_evaluacion').html('Fechas incorrectas');}
		else if(competencia == null){ $('#msj_evaluacion').html('Seleccione Competencia');}		
		else{

			$('#msj_evaluacion').html('');

			$.ajax({
				url: 'evaluacion/addevaluacion',  
				type: 'POST',
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				success: function(data){
					//alert(data);
					if(data){
						$('#form_evaluacion')[0].reset();
						toastr['success']('Se registro correctamente', 'Evaluacion', {
				          closeButton: true,
				          progressBar: true,
				          preventDuplicates: true,
				          newestOnTop: true,
				        });
				        $('#m_addevaluacion').modal('hide');
						tablaevaluciones();
					}else{
						$('#msj_evaluacion').html('Ha ocurrido un Error. Intente de Nuevo!!');
					}
				}				
			});
		}
	});



	$("#tablaevaluciones tbody").on('dblclick','tr',function(){
		$('#btn_add_evaluacion').hide();
		$('#btn_update_evaluacion').show();		
		$('#title_evaluacion').html('Actualizar Evaluacion');
		
		var table = $('#tablaevaluciones').DataTable();	
		var objeto = table.row(this).data();
		var idevaluacion = objeto.IDEVALUACION;
		var datos = { 'idevaluacion' : idevaluacion }

		if(objeto.ESTADO == 'CONCLUIDO'){
			$('#btn_update_evaluacion').attr("disabled","disabled");
		}else{
			$('#btn_update_evaluacion').removeAttr("disabled");
		} 

		$.ajax({
			url: 'evaluacion/getevaluacion',  
			type: 'POST',
			data:  datos, 
			cache: false,
			dataType:'json',				
			//dataType:'text', comprobar errores
			success: function(data){
				//alert(data);
				console.log(data)
				$('#m_addevaluacion').modal('show');				
				$('#idevaluacion').val(data.IDEVALUACION);
				$('#nombre').val(data.NOMBRE);
				$('#fecha_apertura').val(data.FECHA_APERTURA);
				$('#fecha_cierre').val(data.FECHA_CIERRE);				
				$('#observacion').val(data.OBSERVACION);								
			}				
		});

		listcompetencia(idevaluacion);

	});


	$('#btn_update_evaluacion').click(function(){

		var nombre = $('#nombre').val();
		var fecha_apertura = $('#fecha_apertura').val();
		var fecha_cierre = $('#fecha_cierre').val();
		var competencia = $('#competencia').val();

		var formData = new FormData($("#form_evaluacion")[0]);

		if(nombre == ''){ $('#msj_evaluacion').html('Ingrese Nombre Competencia');}
		else if(fecha_apertura == ''){ $('#msj_evaluacion').html('Ingrese Fecha de Apertura');}
		else if(fecha_cierre == ''){ $('#msj_evaluacion').html('Ingrese Fecha de Cierre');}
		else if(fecha_apertura > fecha_cierre){ $('#msj_evaluacion').html('Fechas incorrectas');}
		else if(competencia == null){ $('#msj_evaluacion').html('Seleccione Competencia');}		
		else{

			$('#msj_evaluacion').html('');

			$.ajax({
				url: 'evaluacion/updateevaluacion',  
				type: 'POST',
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				success: function(data){
					//alert(data);
					if(data){
						$('#form_evaluacion')[0].reset();
						toastr['success']('Se actualizo correctamente', 'Evaluacion', {
				          closeButton: true,
				          progressBar: true,
				          preventDuplicates: true,
				          newestOnTop: true,
				        });
				        $('#m_addevaluacion').modal('hide');
						tablaevaluciones();
					}else{
						$('#msj_evaluacion').html('Ha ocurrido un Error. Intente de Nuevo!!');
					}
				}				
			});
		}
	});


	//DESHABILITAR USUARIO
	$("#tablaevaluciones tbody").on('click','button.desactivarevaluacion',function(){

		var idevaluacion =  $(this).attr("id");
		
		$.confirm({
			title: 'Inactivar Evaluacion !!',
			content: '¿ Desea Continuar ?',
			closeIcon: true,
			closeIconClass: 'fa fa-close' ,
			confirmButton: 'Continuar',
			confirmButtonClass: 'btn-primary',	
			cancelButton:'Cancelar',
			icon: 'fa fa-warning',
			animation: 'zoom', 
			confirm: function(){
				
				$.post('evaluacion/delevaluacion',{
					idevaluacion : idevaluacion,
					estado : 0
				},function(data){		 	
					if(data == 'ok'){
						$.alert('Se Inactivo Correctamente !!');						
						tablaevaluciones();
					}else{
						$.alert('Ha ocurrido un Error. Intente de Nuevo !!');							
					}		 	
				});

			},cancel: function(){
				$.alert('Cancelado');		        
			}
		});
	});

	//Habilitar
	$("#tablaevaluciones tbody").on('click','button.activarevaluacion',function(){

		var idevaluacion =  $(this).attr("id");
		
		$.confirm({
			title: 'Habilitar Evaluacion !!',
			content: '¿ Desea Continuar ?',
			closeIcon: true,
			closeIconClass: 'fa fa-close' ,
			confirmButton: 'Continuar',
			confirmButtonClass: 'btn-primary',	
			cancelButton:'Cancelar',
			icon: 'fa fa-warning',
			animation: 'zoom', 
			confirm: function(){
				
				$.post('evaluacion/delevaluacion',{
					idevaluacion : idevaluacion,
					estado : 1
				},function(data){		 	
					if(data == 'ok'){
						$.alert('Se habilito Correctamente !!');						
						tablaevaluciones();
					}else{
						$.alert('Ha ocurrido un Error. Intente de Nuevo !!');							
					}		 	
				});

			},cancel: function(){
				$.alert('Cancelado');		        
			}
		});
	});

	$("#tablaevaluciones tbody").on('click','button.concluir',function(){

		var idevaluacion =  $(this).attr("id");
		
		$.confirm({
			title: 'Concluir Evaluacion !!',
			content: '¿ Desea Continuar ?',
			closeIcon: true,
			closeIconClass: 'fa fa-close' ,
			confirmButton: 'Continuar',
			confirmButtonClass: 'btn-primary',	
			cancelButton:'Cancelar',
			icon: 'fa fa-warning',
			animation: 'zoom', 
			confirm: function(){
				
				$.post('evaluacion/concluirevaluacion',{
					idevaluacion : idevaluacion
				},function(data){		 	
					if(data == 'ok'){
						$.alert('Se Concluyo Correctamente !!');						
						tablaevaluciones();
					}else{
						$.alert('Ha ocurrido un Error. Intente de Nuevo !!');							
					}		 	
				});

			},cancel: function(){
				$.alert('Cancelado');		        
			}
		});
	});








})