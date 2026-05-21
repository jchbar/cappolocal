// https://editor.datatables.net/examples/api/triggerButton.html
// https://stackoverflow.com/questions/44142398/jquery-data-table-action-button
$(document).ready(function() {

    // $('#table_socios tbody').on('click', 'button', function () {
    //     var data = table.row($(this).parents('tr')).data();
    //     alert(data[0] + "'s salary is: " + data[5]);
    // });

  	$('#table_socios').DataTable({
  		// dom: "Bfrtip",	
		ajax: 
		{
	        // processing: true,
	        // serverSide: true,
			url: API+"regsocios/regsocios_listado.php",
	        type: "POST"
	    },
	    columns: 
	    [
            {	
            	data: {},
	            render: function ( data, type, row ) {
			        if (data.cod_prof) {
			        	return data.cod_prof
			        }else{
			        	return "Sin cod_prof"
			        }
			    } 
			},
            {	
            	data: {},
	            render: function ( data, type, row ) {
			        if (data.ced_prof) {
			        	return data.ced_prof
			        }else{
			        	return "Sin ced_prof"
			        }
			    } 
			},
            {
            	data: {},
	            render: function ( data, type, row ) {
			        if (data.nombrecompleto) {
			        	return data.nombrecompleto
			        }else{
			        	return "Sin nombrecompleto"
			        }
			    } 
			},
            {
            	data: {},
	            render: function ( data, type, row ) {
	            	status = data.status
	            	if (data.status == 'ACTIVO')
	            	{
	            		boton="success"
	            		fa='fas fa-user'
	            	}
	            	else 
	            	if (data.status == 'JUBILA')
	            	{
	            		boton="primary"			            		
	            		fa='fas fa-user-tie'
	            	}
	            	else 
	            	{
	            		boton="danger"			            		
	            		// fa='fas fa-user-times'
	            		fa='far fa-user'
	            	}
                    return '<button type="button" class="btn btn-'+boton+' btn-sm btn-circle" title="'+status+'"><i class="'+fa+'"></i></button>'
			        }
			},
            {
            	data: {},
	            render: function ( data, type, row ) {
	            	codigo = data.cod_prof

				return `
					<div class="form-group form-inline">
					<form action="regsocios.php" method="post" class="form-inline">
						<input type="hidden" id="codigo" name="codigo" value="${codigo}">
						<button type="submit" name="accion" value="editar" class="btn-sm btn-warning btn-circle" title="Modificar"><i class="fas fa-user-edit"></i></button>
						<button type="submit" name="accion" value="consultar" class="btn-sm btn-info btn-circle" title="Consultar"><i class="fas fa-user-alt"></i></button>
						<button type="submit" name="accion" value="eliminar" class="btn-sm btn-danger btn-circle" title="Eliminar"><i class="fas fa-user-times"></i></button>
					</form>
					</div>
            	`;
						// <input type="hidden" id="codigo" name="codigo" value="${codigo}">
						// <button type="submit" name="accion" value="consultar" class="btn btn-circle btn-warning">Go</button>
						// <button type="submit" name="accion" value="editar" class="btn btn-circle btn-warning">Go</button>
            		// <a href="regsocios.php/${codigo}" class="text-danger text-decoration">Editar</a>
					// <a href="#" onClick="eliminarProducto(\'${codigo}\')" class="text-danger text-decoration">Eliminar</a>
	            	// return "defaultContent: '<button>Click!</button>'"
                    // return 'button type="button" class="btn btn-info btn-sm btn-circle" title="Consultar" data-socio-id="codigo"><i class="fa fa-check"></i></button>'
                    // <button type="button" class="btn btn-default edit-student-btn" data-student-id="@cl.StudentID"><i class="fa fa-edit"></i></button>
                    // <button type="button" class="btn btn-default"><i class="fa fa-trash"></i></button>

			        }
			},
     	]
	})
})

function consultar_socio(codigo)
{
	// alert('js consultar_socio'+codigo)
	var datos = {'codigo': codigo}
	// console.log('datos',datos, API)

	$.ajax({
		url: API+"regsocios/consulta.php",
        type: "POST",
		dataType: "json",
        data: datos,
		success: function(data){
			// console.log(data.data)
			if (data.codigo == 1)
			{
				// sweetjs('Pendiente con la falla', 'Saludos.... se presume que halla fallado la electricidad anoche por lo que el sistema estara un poco lento por la revision/sincronizacion de los discos duros', 'warning', '');
				colocar_informacion(data.data);
				// alert('success');
			}
			else
				sweet('Fallo la búsqueda', 'No se encontró la información que está solicitando', 'warning', '');
		},
		error: function(){
			sweet('Fallo la búsqueda', 'Error en la API, contacte al Developer', 'warning', '');
		}
	});

}

function colocar_informacion(data)
{
 	console.log(data)
	cedula = data.ced_prof
	cedula = cedula.substring(2,10)
	elareatelefonoh = data.telf_prof.substr(0,4)
	eltelefonoh = data.telf_prof.substr(4,11)
	elareacelular1 = data.celn_prof.substr(0,4)
	elcelular1 = data.celn_prof.substr(4,11)
	elareacelular2 = data.cel2n_prof.substr(0,4)
	elcelular2 = data.cel2n_prof.substr(4,11)

	$("#cedula").val(cedula)
	$("#codigo").val(data.cod_prof)
	$("#elapellido").val(data.ape_prof)
	$("#elnombre").val(data.nombr_prof)
	$("#ladireccionh1").val(data.dire1_prof)
	$("#ladireccionh2").val(data.dire2_prof)
	$("#elareatelefonoh").val(elareatelefonoh)
	$("#eltelefonoh").val(eltelefonoh)
	$("#elareacelular1").val(elareacelular1)
	$("#elcelular1").val(elcelular1)
	$("#elareacelular2").val(elareacelular2)
	$("#elcelular2").val(elcelular2)
	$("#elemail").val(data.mail_prof)
	$("#lnacimiento").val(data.lnaci_prof)
	$("#lafechanac").val(data.nacimiento)
	$("#optsexo").val(2)
	$("#optsexo").attr(2).attr('selected', true)
	// $("#optsexo option[value='2']").attr("selected", true);

	elareatelefonot = data.tele_empr.substr(0,4)
	eltelefonot = data.tele_empr.substr(4,11)
	laextension = data.ext_empre

	$("#elareatelefonot").val(elareatelefonot)
	$("#eltelefonot").val(eltelefonot)
	$("#laextension").val(laextension)
	$("#lacondicion").val(data.condi_prof)
	$("#elcargo").val(data.cargo)
	$("#eldpto").val(data.dpto_prof)
	$("#laubicacion").val(data.ubic_prof)
	$("#fing_ucla").val(data.f_ing_ucla)

	$("#eltipoafiliado").val(data.tipo_prof)
	$("#f_ing_capu").val(data.f_ing_capu)
	$("#lafecharetiro").val(data.f_ret_capu)
	$("#elestatus").val(data.statu_prof)
	$("#eltipocuenta").val(data.tipo_cuenta)
	$("#elnrocuenta").val(data.nro_cuenta)
	$("#fejubilacion").val(data.jubilado)
	$("#elsueldo").val(data.sueld_prof)
	$("#fejubilacion").val(data.jubilado)
	/*
	aport_empr
	
	
	hab_f_extr
	
	hab_opsu
	ultapm_div
	
	
	*/
	// $("#por_socio").val(data.aport_prof)
	$("#fa_socio").val(data.ultap_prof)
	// $("#ul_socio").val(data.ultapm_prof)
	$("#ult_socio").val(data.hab_f_prof)
	$("#AfectanDisponibilidad").html('<span class="badge bg-warning">Saldos Afectan <strong>'+data.hab_f_prof+'</strong></span>')
	// $("#por_patrono").val(data.aport_empr)
	$("#fa_patrono").val(data.ultap_emp)
	// $("#ul_patrono").val(data.ultapm_emp)
	$("#ult_patrono").val(data.hab_f_empr)
	$("#NoAfectanDisponibilidad").html('<span class="badge bg-info">Saldos NO Afectan <strong>'+data.hab_f_prof+'</strong></span>')
	$("#por_voluntario").val(data.aport_extr)
	$("#fa_voluntario").val(data.ultap_extr)
	// $("#ul_voluntario").val(data.ultapm_extr)
	$("#ult_voluntario").val(data.hab_f_empr)
	$("#FianzasOtorgadas").html('<span class="badge bg-warning">Fianzas <strong>'+data.hab_f_prof+'</strong></span>')
	$("#fa_capitalizable").val(data.ultap_div)
	$("#ul_capitalizable").val(data.ultapm_div)
	$("#ult_capitalizable").val(data.hab_f_capi)

	// $("#totalpAhorros").val(parseFloat(data.aport_extr)+parseFloat(data.aport_prof)+parseFloat(data.aport_empr))
	$("#totalmAhorros").val(parseFloat(data.hab_f_capi)+parseFloat(data.hab_f_prof)+parseFloat(data.hab_f_empr)+parseFloat(data.hab_f_extr))
	$("#DisponibilidadNeta").html('<span class="badge bg-success">Disponibilidad Neta<strong>'+data.hab_f_prof+'</strong></span>')

	$("#por_socio").html('<span class="badge bg-info text-right">'+data.aport_prof+'</span>')
	$("#por_patrono").html('<span class="badge bg-info text-right">'+data.aport_empr+'</span>')
	$("#por_voluntario").html('<span class="badge bg-info text-right">'+data.aport_extr+'</span>')
	t1 = parseFloat(data.aport_extr) +parseFloat(data.aport_prof) +parseFloat(data.aport_empr)
	$("#totalpAhorros").html('<span class="badge bg-info text-right">'+t1.toFixed(2)+'</span>')


	$("#ul_socio").html('<span class="badge bg-info text-right">'+parseFloat(data.ultapm_prof).toFixed(2)+'</span>')
	$("#ult_socio").html('<span class="badge bg-info text-right">'+parseFloat(data.hab_f_prof).toFixed(2)+'</span>')


	$("#ul_patrono").html('<span class="badge bg-info text-right">'+parseFloat(data.ultapm_emp).toFixed(2)+'</span>')
	$("#ult_patrono").html('<span class="badge bg-info text-right">'+parseFloat(data.hab_f_empr).toFixed(2)+'</span>')

	$("#ul_voluntario").html('<span class="badge bg-info text-right">'+parseFloat(data.ultapm_extr).toFixed(2)+'</span>')

	$("#ult_voluntario").html('<span class="badge bg-info text-right">'+parseFloat(data.hab_f_extr).toFixed(2)+'</span>')


	/*
	$("#por_socio").val(data.aport_prof)
	$("#por_socio").val(data.aport_prof)
	$("#por_socio").val(data.aport_prof)
	$("#por_socio").val(data.aport_prof)
	*/

}
