$(document).on('ready',function(){

	/*$('#toastr-show').click(function() {
		var msg   = 'Esto es una Prueba';
		var title = 'Titulo';
		var type  = 'success'; //success, info, warning, error        
    });*/

	/* ********************************************************************************************************************************
														MODULO USUARIO
	******************************************************************************************************************************** */
	// LISTA TODOS LOS USUARIOS DE LA BD
	function tablausuarios(){
		$('#tablausuarios').dataTable().fnDestroy();		 	
		$('#tablausuarios').DataTable({

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
			"ajax" : "usuario/getusuarios",
			"columns" : [
			{
				"data" : "CONT"
			},{
				"data" : "IDUSUARIO"
			},{
				"data" : "NUMERODOC"
			},{
				"data" : "NOMBRE"
			},{
				"data" : "ESTADO"
			},{
				"data" : "FECHA"
			},{
				"data" : "OPCIONES"
			},		
			]
		});	
	}
	
	tablausuarios();

	$('#btn_add_usuario').click(function(){

		var usuario = $('#usuario').val();
		var ndoc = $('#ndoc').val();
		var apepaterno = $('#apepaterno').val();
		var apematerno = $('#apematerno').val();
		var nombre = $('#nombre').val();
		var telefono = $('#telefono').val();
		var correo = $('#correo').val();
		var contrasenia = $('#contrasenia').val();
		var re_contrasenia = $('#re_contrasenia').val();
		var area = $('#area').val();
		var cargo = $('#cargo').val();

		var formData = new FormData($("#form_usuario")[0]);

		if(usuario == ''){ $('#msj_usuario').html('Ingrese Usuario');}
		else if(ndoc == ''){ $('#msj_usuario').html('Ingrese N° Doc');}
		else if(apepaterno == ''){ $('#msj_usuario').html('Ingrese Apellido Paterno');}
		else if(apematerno == ''){ $('#msj_usuario').html('Ingrese Apellido Materno');}
		else if(nombre == ''){ $('#msj_usuario').html('Ingrese Nombre');}
		//else if(area == null){ $('#msj_usuario').html('Seleccione Area');}
		//else if(cargo == null){ $('#msj_usuario').html('Seleccione Cargo');}
		else if(contrasenia == ''){ $('#msj_usuario').html('Ingrese Contraseña');}
		else if(re_contrasenia == ''){ $('#msj_usuario').html('Repita Contraseña');}
		else if(contrasenia != re_contrasenia){ $('#msj_usuario').html('Las contraseñas no coinciden');}
		else{
			$('#msj_usuario').html('');

			$.ajax({
				url: 'usuario/addusuario',  
				type: 'POST',
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				success: function(data){
					//alert(data);				
					if(data=='ok'){
						$('#form_usuario')[0].reset();
						toastr['success']('Se registro correctamente', 'Usuario', {
				          closeButton: true,
				          progressBar: true,
				          preventDuplicates: true,
				          newestOnTop: true,
				        });
				        //toastr['success']('Se registro correctamente', 'Usuario');
						tablausuarios();
					}else if(data=='error'){
						$('#msj_usuario').html('Ha ocurrido un Error. Intente de Nuevo!!');
					}else{
						$('#msj_usuario').html(data);
					}		
				}				
			});
		}
	})
	
	//BOTON NUEVO USUARIO
	$('#btn_nuevo_usuario').click(function(){
		$('#usuario').removeAttr("readonly")
		$('#btn_add_usuario').show();
		$('#btn_update_usuario').hide();
		$('#btn_acceso_usuario').hide();		
		$('#form_usuario')[0].reset();
		$('#title_user').html('Añadir Nuevo Usuario');
	});



	//TRAER EL USUARIO AL MODAL
	//$("#tablausuarios tbody").on('click','button.editusuario',function(){
	$("#tablausuarios tbody").on('dblclick','tr',function(){
		$('#btn_add_usuario').hide();
		$('#btn_update_usuario').show();
		$('#btn_acceso_usuario').show();
		$('#title_user').html('Editar Usuario');
		
		var table = $('#tablausuarios').DataTable();	
		var objeto = table.row(this).data();
		var idusuario = objeto.IDUSUARIO;
		var datos = { 'idusuario' : idusuario}

		$.ajax({
			url: 'usuario/getusuario',  
			type: 'POST',
			data:  datos, 
			cache: false,
			dataType:'json',				
			//dataType:'text', comprobar errores
			success: function(data){
				//alert(data);
				$('#addusuario').modal('show');
				$('#usuario').attr("readonly","readonly");
				$('#usuario').val(data.IDUSUARIO);
				$('#ndoc').val(data.NUMERODOC);
				$('#apepaterno').val(data.AP_PATERNO);
				$('#apematerno').val(data.AP_MATERNO);
				$('#nombre').val(data.NOMBRE);
				$('#telefono').val(data.TELEFONO);
				$('#correo').val(data.CORREO);
				//$('#area').val(data.IDAREA).change();
				//$('#cargo').val(data.IDCARGO).change();
				$('#contrasenia').val(data.CONTRASENIA);
				$('#re_contrasenia').val(data.CONTRASENIA);
			}				
		});
	});


	//DESHABILITAR USUARIO
	$("#tablausuarios tbody").on('click','button.desactivarusuario',function(){

		var idusuario =  $(this).attr("id");

		$.confirm({
			title: 'Inactivar Usuario !!',
			content: '¿ Desea Continuar ?',
			closeIcon: true,
			closeIconClass: 'fa fa-close' ,
			confirmButton: 'Continuar',
			confirmButtonClass: 'btn-primary',	
			cancelButton:'Cancelar',
			icon: 'fa fa-warning',
			animation: 'zoom', 
			confirm: function(){
				
				$.post('usuario/delusuario',{
					idusuario : idusuario,
					estado : 0
				},function(data){		 	
					if(data == 'ok'){
						$.alert('Se Inactivo Correctamente !!');						
						tablausuarios();
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
	$("#tablausuarios tbody").on('click','button.activarusuario',function(){

		var idusuario =  $(this).attr("id");

		$.confirm({
			title: 'Habilitar Usuario !!',
			content: '¿ Desea Continuar ?',
			closeIcon: true,
			closeIconClass: 'fa fa-close' ,
			confirmButton: 'Continuar',
			confirmButtonClass: 'btn-primary',	
			cancelButton:'Cancelar',
			icon: 'fa fa-warning',
			animation: 'zoom', 
			confirm: function(){
				
				$.post('usuario/delusuario',{
					idusuario : idusuario,
					estado : 1
				},function(data){		 	
					if(data == 'ok'){
						$.alert('Se habilito Correctamente !!');						
						tablausuarios();
					}else{
						$.alert('Ha ocurrido un Error. Intente de Nuevo !!');							
					}		 	
				});

			},cancel: function(){
				$.alert('Cancelado');		        
			}
		});
	});

	

	//ACTUALIZAR USUARIO
	$('#btn_update_usuario').click(function(){

		var usuario = $('#usuario').val();
		var ndoc = $('#ndoc').val();
		var apepaterno = $('#apepaterno').val();
		var apematerno = $('#apematerno').val();
		var nombre = $('#nombre').val();
		var telefono = $('#telefono').val();
		var correo = $('#correo').val();
		var contrasenia = $('#contrasenia').val();
		var re_contrasenia = $('#re_contrasenia').val();

		var formData = new FormData($("#form_usuario")[0]);

		if(usuario == ''){ $('#msj_usuario').html('Ingrese Usuario');}
		else if(ndoc == ''){ $('#msj_usuario').html('Ingrese N° Doc');}
		else if(apepaterno == ''){ $('#msj_usuario').html('Ingrese Apellido Paterno');}
		else if(apematerno == ''){ $('#msj_usuario').html('Ingrese Apellido Materno');}
		else if(nombre == ''){ $('#msj_usuario').html('Ingrese Nombre');}
		else if(contrasenia == ''){ $('#msj_usuario').html('Ingrese Contraseña');}
		else if(re_contrasenia == ''){ $('#msj_usuario').html('Repita Contraseña');}
		else if(contrasenia != re_contrasenia){ $('#msj_usuario').html('Las contraseñas no coinciden');}
		else{
			$('#msj_usuario').html('');

			$.ajax({
				url: 'usuario/updateusuario',  
				type: 'POST',
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				success: function(data){
					//alert(data);				
					if(data){						
				        toastr['success']('Se actualizo correctamente', 'Usuario', {
				          closeButton: true,
				          progressBar: true,
				          preventDuplicates: true,
				          newestOnTop: true,
				        });
						tablausuarios();
					}else{
						$('#msj_usuario').html('Ha ocurrido un Error. Intente de Nuevo!!');
					}	
				}				
			});
		}
		
	});

	

	/* ********************************************************************************************************************************
																MODULO PERMISOS
	******************************************************************************************************************************** */

	$('#btn_acceso_usuario').click(function(){		
		var usuario = $('#usuario').val();		
		$.post('usuario/getprofilexusuario',{
			usuario : usuario
		},function(data){
			//alert(data);
			$('#select_idperfil').html(data);
		})
	});

	//GRABAR Y/O ACTUALIZAR PERMISOS DEL USUARIO
	$('#btn_save_acceso').click(function(){

		var idperfil = $('#select_idperfil').val();
		var usuario = $('#usuario').val();

		if(select_idperfil == null){ $('#msj_acceso').html('Seleccione Perfil');}
		else{
			$.post('usuario/addacceso',{
				idperfil : idperfil,
				usuario : usuario
			},function(data){
				//alert(data)
				if(data){
					toastr['success']('Se actualizo correctamente', 'Acceso', {
				       closeButton: true,
				       progressBar: true,
				       preventDuplicates: true,
				       newestOnTop: true,
				    });					  
				}else{
					$('#msj_acceso').html('Ha ocurrido un Error. Intente de Nuevo!!');
				}				
			});
		}
	});	

	/* ********************************************************************************************************************************
																MODULO PERFIL
	******************************************************************************************************************************** */

	function tablaprofiles(){
		$('#tablaprofile').dataTable().fnDestroy();		 	
		$('#tablaprofile').DataTable({

			//PARA EXPORTAR
			/*dom: "Bfrtip",
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

			"order" : [ [ 0, "asc" ] ],
			"ajax" : "usuario/getprofiles",
			"columns" : [{
				"data" : "IDPERFIL"
			},{
				"data" : "NOMBRE_PERFIL"
			},{
				"data" : "ESTADO"
			},{
				"data" : "CANTMODULOS"
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
	
	tablaprofiles();
	
	function comoboperfil(){
		$.post('usuario/getcombomodulo',{
		},function(data){
			//alert(data);
			$('#idmodulo').html(data);
		});
	}
	// NUEVO PERFIL
	$('#btn_nuevo_perfil').click(function(){		
		$('#btn_add_profile').show();
		$('#btn_update_profile').hide();
		$('#title_profile').html('Agregar Perfil');
		$('#form_profile')[0].reset();
		$('#nom_profile').removeAttr("readonly")
		comoboperfil();
	});

	// GUARDAR PERFIL
	$('#btn_add_profile').click(function(){
		var nomperfil = $('#nom_profile').val();
		var idmodulo = $('#idmodulo').val();
		var formData = new FormData($("#form_profile")[0]);

		if(nomperfil == ''){ $('#msj_profile').html('Ingrese nombre del Perfil');}
		else if(idmodulo == null){ $('#msj_profile').html('Seleccione Modulos');}
		else{
			$('#msj_profile').html('');

			$.ajax({
				url: 'usuario/addprofile',  
				type: 'POST',
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				success: function(data){									
					if(data=='ok'){
						toastr['success']('Se registro correctamente', 'Perfil', {
				          closeButton: true,
				          progressBar: true,
				          preventDuplicates: true,
				          newestOnTop: true,
				        });
						$('#form_profile')[0].reset();
						$('#m_addperfil').modal('hide');
						tablaprofiles();
					}else if(data=='error'){
						$('#msj_profile').html('Ha ocurrido un Error. Intente de Nuevo!!');
					}else{
						$('#msj_profile').html(data);
					}				
				}				
			});
		}
	})	

	

	// TRAE DATOS DEL PERFIL
	$("#tablaprofile tbody").on('dblclick','tr',function(){

		$('#title_profile').html('Editar Perfil');
		$('#btn_add_profile').hide();
		$('#btn_update_profile').show();

		var table = $('#tablaprofile').DataTable();	
		var objeto = table.row(this).data();
		console.log(objeto);
		var idperfil = objeto.IDPERFIL;
		var nomperfil = objeto.NOMBRE_PERFIL;

		$('#nom_profile').attr("readonly","readonly");
		$('#nom_profile').val(nomperfil);
		$('#idperfil').val(idperfil);
		$('#m_addperfil').modal('show');		

		// TRAE LOS MODULOS RELACIONADOS AL PERFIL
		$.post('usuario/getcombomoduloxperfil',{
			idperfil : idperfil
		},function(data){
			//alert(data)
			$('#idmodulo').html(data);
		});
	});	

	//ACTUALIZAR PERFIL
	$('#btn_update_profile').click(function(){
		
		var idmodulo = $('#idmodulo').val();
		var formData = new FormData($("#form_profile")[0]);

		if(idmodulo == null){ $('#msj_profile').html('Seleccione Modulos');}
		else{
			$('#msj_profile').html('');

			$.ajax({
				url: 'usuario/updateprofile',  
				type: 'POST',
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				success: function(data){
					if(data=='ok'){
						toastr['success']('Se registro correctamente', 'Perfil', {
				          closeButton: true,
				          progressBar: true,
				          preventDuplicates: true,
				          newestOnTop: true,
				        });
						$('#form_profile')[0].reset();
						$('#m_addperfil').modal('hide');
						tablaprofiles();
					}else{
						$('#msj_profile').html('Ha ocurrido un Error. Intente de Nuevo!!');
					}			
				}				
			});
		}
	});



	// INHABILITAR PERFIL
	$("#tablaprofile tbody").on('click','button.desactivarperfil',function(){
		
		var idperfil =  $(this).attr("id");
		$.confirm({
			title: 'Inactivar Perfil !!',
			content: '¿ Desea Continuar ?',
			closeIcon: true,
			closeIconClass: 'fa fa-close' ,
			confirmButton: 'Continuar',
			confirmButtonClass: 'btn-primary',	
			cancelButton:'Cancelar',
			icon: 'fa fa-warning',
			animation: 'zoom', 
			confirm: function(){
				
				$.post('usuario/deleteprofile',{
					idperfil : idperfil,
					estado : 0
				},function(data){		 	
					if(data){
						$.alert('Se desactivo Correctamente !!');						
						tablaprofiles();
					}else{
						$.alert('Ha ocurrido un Error. Intente de Nuevo !!');							
					}		 	
				});

			},cancel: function(){
				$.alert('Cancelado');		        
			}
		});	
	});

	// HABILITAR PERFIL
	$("#tablaprofile tbody").on('click','button.activarperfil',function(){
		
		var idperfil =  $(this).attr("id");
		$.confirm({
			title: 'Activar Perfil !!',
			content: '¿ Desea Continuar ?',
			closeIcon: true,
			closeIconClass: 'fa fa-close' ,
			confirmButton: 'Continuar',
			confirmButtonClass: 'btn-primary',	
			cancelButton:'Cancelar',
			icon: 'fa fa-warning',
			animation: 'zoom', 
			confirm: function(){
				
				$.post('usuario/deleteprofile',{
					idperfil : idperfil,
					estado : 1
				},function(data){		 	
					if(data){
						$.alert('Se Activo Correctamente !!');						
						tablaprofiles();
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