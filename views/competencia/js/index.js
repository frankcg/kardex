$(document).on('ready',function(){


	function tablacompetencias(){
		$('#tablacompetencias').dataTable().fnDestroy();		 	
		$('#tablacompetencias').DataTable({

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
			"ajax" : "competencia/getcompetencias",
			"columns" : [
			{
				"data" : "IDCOMPETENCIA"
			},{
				"data" : "NOMBRE"
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

	tablacompetencias();

	//BOTON NUEVA COMPETENCIA
	$('#btn_nuevo_competencia').click(function(){		
		$('#btn_add_competencia').show();
		$('#btn_update_competencia').hide();		
		$('#form_competencia')[0].reset();
		$('#title_competencia').html('Añadir Competencia');
	});


	$('#btn_add_competencia').click(function(){

		var nombre = $('#nombre').val();
		var conducta1 = $('#conducta1').val();
		var conducta2 = $('#conducta2').val();
		var conducta3 = $('#conducta3').val();
		var conducta4 = $('#conducta4').val();

		var formData = new FormData($("#form_competencia")[0]);

		if(nombre == ''){ $('#msj_competencia').html('Ingrese Nombre Competencia');}
		else if(conducta1 == ''){ $('#msj_competencia').html('Ingrese Conducta 1');}
		else if(conducta2 == ''){ $('#msj_competencia').html('Ingrese Conducta 2');}
		else if(conducta3 == ''){ $('#msj_competencia').html('Ingrese Conducta 3');}
		else if(conducta4 == ''){ $('#msj_competencia').html('Ingrese Conducta 4');}
		else{

			$('#msj_competencia').html('');

			$.ajax({
				url: 'competencia/addcompetencia',  
				type: 'POST',
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				success: function(data){
					//alert(data);
					if(data){
						$('#form_competencia')[0].reset();
						toastr['success']('Se registro correctamente', 'Competencia', {
				          closeButton: true,
				          progressBar: true,
				          preventDuplicates: true,
				          newestOnTop: true,
				        });				        
						tablacompetencias();
					}else{
						$('#msj_competencia').html('Ha ocurrido un Error. Intente de Nuevo!!');
					}
				}				
			});
		}
	});



	$("#tablacompetencias tbody").on('dblclick','tr',function(){
		$('#btn_add_competencia').hide();
		$('#btn_update_competencia').show();		
		$('#title_competencia').html('Actualizar Competencia');
		
		var table = $('#tablacompetencias').DataTable();	
		var objeto = table.row(this).data();
		var idcompetencia = objeto.IDCOMPETENCIA;
		var datos = { 'idcompetencia' : idcompetencia}

		$.ajax({
			url: 'competencia/getcompetencia',  
			type: 'POST',
			data:  datos, 
			cache: false,
			dataType:'json',				
			//dataType:'text', comprobar errores
			success: function(data){
				//alert(data);
				console.log(data)
				$('#m_addcompetencia').modal('show');
				$('#nombre').val(data.NOMBRE);
				$('#conducta1').val(data.CONDUCTA1);
				$('#conducta2').val(data.CONDUCTA2);
				$('#conducta3').val(data.CONDUCTA3);
				$('#conducta4').val(data.CONDUCTA4);
				$('#observacion').val(data.OBSERVACION);
				$('#idcompetencia').val(data.IDCOMPETENCIA);
								
			}				
		});
	});


	$('#btn_update_competencia').click(function(){

		var nombre = $('#nombre').val();
		var conducta1 = $('#conducta1').val();
		var conducta2 = $('#conducta2').val();
		var conducta3 = $('#conducta3').val();
		var conducta4 = $('#conducta4').val();

		var formData = new FormData($("#form_competencia")[0]);

		if(nombre == ''){ $('#msj_competencia').html('Ingrese Nombre Competencia');}
		else if(conducta1 == ''){ $('#msj_competencia').html('Ingrese Conducta 1');}
		else if(conducta2 == ''){ $('#msj_competencia').html('Ingrese Conducta 2');}
		else if(conducta3 == ''){ $('#msj_competencia').html('Ingrese Conducta 3');}
		else if(conducta4 == ''){ $('#msj_competencia').html('Ingrese Conducta 4');}
		else{

			$('#msj_competencia').html('');

			$.ajax({
				url: 'competencia/updatecompetencia',  
				type: 'POST',
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				success: function(data){
					//alert(data);
					if(data){						
						toastr['success']('Se actualizo correctamente', 'Competencia', {
				          closeButton: true,
				          progressBar: true,
				          preventDuplicates: true,
				          newestOnTop: true,
				        });				        
						tablacompetencias();
					}else{
						$('#msj_competencia').html('Ha ocurrido un Error. Intente de Nuevo!!');
					}
				}				
			});
		}
	});


	//DESHABILITAR USUARIO
	$("#tablacompetencias tbody").on('click','button.desactivarcompetencia',function(){

		var idcompetencia =  $(this).attr("id");

		$.confirm({
			title: 'Inactivar Competencia !!',
			content: '¿ Desea Continuar ?',
			closeIcon: true,
			closeIconClass: 'fa fa-close' ,
			confirmButton: 'Continuar',
			confirmButtonClass: 'btn-primary',	
			cancelButton:'Cancelar',
			icon: 'fa fa-warning',
			animation: 'zoom', 
			confirm: function(){
				
				$.post('competencia/delcompetencia',{
					idcompetencia : idcompetencia,
					estado : 0
				},function(data){		 	
					if(data == 'ok'){
						$.alert('Se Inactivo Correctamente !!');						
						tablacompetencias();
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
	$("#tablacompetencias tbody").on('click','button.activarcompetencia',function(){

		var idcompetencia =  $(this).attr("id");

		$.confirm({
			title: 'Habilitar Competencia !!',
			content: '¿ Desea Continuar ?',
			closeIcon: true,
			closeIconClass: 'fa fa-close' ,
			confirmButton: 'Continuar',
			confirmButtonClass: 'btn-primary',	
			cancelButton:'Cancelar',
			icon: 'fa fa-warning',
			animation: 'zoom', 
			confirm: function(){
				
				$.post('competencia/delcompetencia',{
					idcompetencia : idcompetencia,
					estado : 1
				},function(data){		 	
					if(data == 'ok'){
						$.alert('Se habilito Correctamente !!');						
						tablacompetencias();
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