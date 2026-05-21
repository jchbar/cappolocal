	select 
		ubic_prof as ubicacion, cod_prof as codigo, ced_prof as cedula, 
		concat(trim(ape_prof), ' ', trim(nombr_prof)) as nombre, 
		hab_f_prof as ret_inicial, hab_f_empr as aporte_inicial, hab_f_extr as extra_inicial, 
	(
		select sum(hab_prof)
		from fhis200 
		where 
			fhis200.cod_prof = iniciof200.cod_prof and 
			(pago > '2021-10-31' and pago <= '2023-04-30') and 
			descri <> 'Ahorro Voluntario'
	) as mas_retencion,
	(
		select sum(hab_ucla)
		from fhis200 
		where 
			fhis200.cod_prof = iniciof200.cod_prof and 
			(pago > '2021-10-31' and pago <= '2023-04-30')
	) as mas_aporte, 
	(
		select sum(hab_prof)
		from fhis200 
		where 
			fhis200.cod_prof = iniciof200.cod_prof and 
			(pago > '2021-10-31' and pago <= '2023-04-30') and 
			descri = 'Ahorro Voluntario'
	) as mas_extra,

	(
		select sum(ret_ucla)
		from sgcaf700
		where 
			sgcaf700.codsoc = iniciof200.cod_prof and 
			(fechareti >= '2021-10-31' and fechareti <= '2023-04-30') 
	) as menos_retencion,
	(
		select sum(ret_capu)
		from sgcaf700
		where 
			sgcaf700.codsoc = iniciof200.cod_prof and 
			(fechareti >= '2021-10-31' and fechareti <= '2023-04-30') 
	) as menos_aporte,
	(
		select sum(ret_volu)
		from sgcaf700
		where 
			sgcaf700.codsoc = iniciof200.cod_prof and 
			(fechareti >= '2021-10-31' and fechareti <= '2023-04-30') 
	) as menos_extra,
	(select upper(statu_prof) from sgcaf200 where iniciof200.cod_prof = sgcaf200.cod_prof) as estatus

	from iniciof200 
	where f_ing_capu <= '2021-10-31' 