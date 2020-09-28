$(document).on('ready',function(){

	//$( "#nombre" ).autocomplete( "option", "appendTo", ".eventInsForm" );
	$( "#nombre" ).autocomplete({
	 	source: function( request, response ) {
	   	
	  	$.ajax({
			url: "compra/autocomplete",
	    	type: 'post',
	    	dataType: "json",
	    	data: {
	     		search: request.term
	    	},
	    	success: function( data ) {
	    		response( data );
			}
		});
		},select: function (event, ui) {		
			$('#nombre').val(ui.item.label); // display the selected text
	  		//$('#selectuser_id').val(ui.item.value); // save selected id to input
	  		return false;
	  	}
	});

	/* ********************************************************************************************************************************
										MANTENIMIENTO COMPRA
	******************************************************************************************************************************** */	

	function tablaCompras(){
		$('#tablaCompra').dataTable().fnDestroy();		 	
		$('#tablaCompra').DataTable({

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
			
			//"order" : [ [ 0, "desc" ] ],
			"ajax" : "compra/getCompras",
			"columns" : [
			{
				"data" : "CONT"
			},{
				"data" : "FECHA_COMPRA"
			},{
				"data" : "IDCOMPRA"
			},{
				"data" : "IDPRODUCTO"
			},{
				"data" : "PRODUCTO"
			},{
				"data" : "ALIAS"
			},{
				"data" : "CANTIDAD"
			},{
				"data" : "PRECIO_UNIDAD"
			},{
				"data" : "PRECIO_TOTAL"
			},{
				"data" : "OPCIONES"
			},		
			],
			"language": {
				"url": "/kardex/public/cdn/datatable.spanish.lang"
			} 
		});	
	}
	
	tablaCompras();

	$('#btn_add_compra').click(function(){

		var nombre = $('#nombre').val();
		var cantidad = $('#cantidad').val();
		var precioCompra = $('#precioCompra').val();
		var aliasCompra = $('#aliasCompra').val();
		
		var formData = new FormData($("#formCompra")[0]);

		if(nombre == ''){ $('#msj_compra').html('Ingrese Nombre del Producto');}
		else if(cantidad == ''){ $('#msj_compra').html('Ingrese Cantidad');}
		else if(precioCompra == ''){ $('#msj_compra').html('Ingrese Compra');}
		else if(aliasCompra == ''){ $('#msj_compra').html('Ingrese Alias');}
		else{
			$('#msj_compra').html('');

			$.ajax({
				url: 'compra/addCompra',  
				type: 'POST',
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				success: function(data){
					
					$('#mAddCompra').modal('hide');
					if(data=='ok'){
						$('#formCompra')[0].reset();
						toastr['success']('Se registro correctamente', 'Compra', {
				          closeButton: true,
				          progressBar: true,
				          preventDuplicates: true,
				          newestOnTop: true,
				        });
				        //toastr['success']('Se registro correctamente', 'Usuario');
						tablaCompras();
						
					}else if(data=='error'){
						$('#msj_compra').html('Ha ocurrido un Error. Intente de Nuevo!!');
					}else{
						$('#msj_compra').html(data);
					}		
				}				
			});
		}
	});
	
	//BOTON NUEVO PRODUCTO
	$('#btnNuevaCompra').click(function(){
		$('#nombre').removeAttr("readonly");
		$('#btn_add_compra').show();
		$('#btn_update_compra').hide();
		$('#formCompra')[0].reset();
		$('#title_producto').html('Registrar Compra');		
	});


	//TRAER EL USUARIO AL MODAL
	//$("#tablausuarios tbody").on('click','button.editusuario',function(){
	$("#tablaCompra tbody").on('dblclick','tr',function(){
		$('#btn_add_compra').hide();
		$('#btn_update_compra').show();
		$('#title_producto').html('Editar Compra');
		
		var table = $('#tablaCompra').DataTable();	
		var objeto = table.row(this).data();

		$('#nombre').attr("readonly",true);
		$('#idCompra').val(objeto.IDCOMPRA);
		$('#nombre').val(objeto.PRODUCTO);
		$('#cantidad').val(objeto.CANTIDAD);
		$('#precioCompra').val(objeto.PRECIO_UNIDAD);
		$('#aliasCompra').val(objeto.ALIAS);
		$('#descripcion').val(objeto.OBSERVACION);
		$('#mAddCompra').modal('show');
		if(objeto.VENTA>=1){
			$('#btn_update_compra').hide();
		}
	});


	//DESHABILITAR USUARIO
	$("#tablaCompra tbody").on('click','button.eliminarCompra',function(){

		var idCompra = $(this).attr("idCompra");		

		$.confirm({
			title: 'Eliminar Compra !!',
			content: '¿ Desea Continuar ?',
			closeIcon: true,
			closeIconClass: 'fa fa-close' ,
			confirmButton: 'Continuar',
			confirmButtonClass: 'btn-primary',	
			cancelButton:'Cancelar',
			icon: 'fa fa-warning',
			animation: 'zoom', 
			confirm: function(){				
				$.post('compra/eliminarCompra',{
					idCompra : idCompra,
					estado : 0
				},function(data){		 	
					if(data == 'ok'){
						$.alert('Se Inactivo Correctamente !!');						
						tablaCompras();
					}else{
						$.alert('Ha ocurrido un Error. Intente de Nuevo !!');							
					}		 	
				});

			},cancel: function(){
				$.alert('Cancelado');		        
			}
		});
	});


	$('#btn_update_compra').click(function(){

		var nombre = $('#nombre').val();
		var cantidad = $('#cantidad').val();
		var precioCompra = $('#precioCompra').val();
		var aliasCompra = $('#aliasCompra').val();
		
		var formData = new FormData($("#formCompra")[0]);

		if(nombre == ''){ $('#msj_compra').html('Ingrese Nombre del Producto');}
		else if(cantidad == ''){ $('#msj_compra').html('Ingrese Cantidad');}
		else if(precioCompra == ''){ $('#msj_compra').html('Ingrese Compra');}
		else if(aliasCompra == ''){ $('#msj_compra').html('Ingrese Alias');}
		else{
			$('#msj_compra').html('');

			$.ajax({
				url: 'compra/updateCompra',  
				type: 'POST',
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				success: function(data){
					
					$('#mAddCompra').modal('hide');
					if(data=='ok'){
						$('#formCompra')[0].reset();
						toastr['success']('Se actualizo correctamente', 'Compra', {
				          closeButton: true,
				          progressBar: true,
				          preventDuplicates: true,
				          newestOnTop: true,
				        });
				        //toastr['success']('Se registro correctamente', 'Usuario');
						tablaCompras();
						
					}else if(data=='error'){
						$('#msj_compra').html('Ha ocurrido un Error. Intente de Nuevo!!');
					}else{
						$('#msj_compra').html(data);
					}		
				}				
			});
		}
	});	


});