var ventanaCalendario=false

function mostrarCalendar(form, form2, formato, valor, fechai, fechaf, dom, ano){
	//funcion para abrir una ventana con un calendario.
	//Se deben indicar los datos del formulario y campos que se desean editar con el calendario, es decir, los campos donde va la fecha.
	if (typeof ventanaCalendario.document == "object") {
		ventanaCalendario.close()
	}
	ventanaCalendario=window.open("pop.php?formulario=" + form + "&nomcampo=" + form2 + "&formato=" + formato + "&valor=" + valor + "&fechai=" + fechai + "&fechaf=" + fechaf + "&dom=" + dom + "&ano=" + ano, "calendario","width=400,height=250,left=50,top=100,scrollbars=no,menubars=no,statusbar=NO,status=NO,resizable=YES,location=NO,toolbar=NO,personalbar=NO")
}