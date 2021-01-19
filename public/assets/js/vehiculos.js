$(document).ready(function () {
    $('#form_agg_conductor').submit(function () {
        $.ajax({
            url: '/vehiculos/agg_conductor',
            type: 'POST',
            data: $('#form_agg_conductor').serialize(),
            success: function (data) {
                cargar_conductores(data)
                $('#alerta_success').removeClass('d-none');
                $('#alerta_success').html('Se ha Creado el Conductor Correctamente');
                window.setTimeout(function() {
                    $('#alerta_success').addClass('d-none');
                }, 3000);
                $("#form_agg_conductor")[0].reset();
            }
        })

        return false;
    })

    $('#agg_targeta_propiedad').submit(function () {
        var form = document.getElementById('agg_targeta_propiedad');
        var formData = new FormData(form);
        $.ajax({
            url: '/vehiculos/agg_targeta_propiedad',
            type: 'POST',
            data: formData,
            cache: false,
            contentType: false,
	        processData: false,
            success: function (data) {
                $('#agg_targeta_propiedad')[0].reset();
                $('#agg_doc_legal').modal('hide');
                documentos_legales(data.tipo, data.vehiculo_id, data.id_table);
            }
        })

        return false;
    })

})

function cargar_conductores(id) {
    $.ajax({
        url: '/vehiculos/cargar_conductores',
        type: 'POST',
        data: {id:id},
        success: function (data) {
            content = '';
            data.forEach( function (conductor, indice) {
                fecha = new Date(conductor.fecha_final);
                content += `
                <tr>
                    <td scope="row">${ indice+1 }</td>
                    <td>${ conductor.personal.nombres } ${ conductor.personal.primer_apellido } ${ conductor.personal.segundo_apellido ?? '' }</td>
                    `;

                //verifica que este activo o no
                if((fecha.getDate() >= new Date().getDate()) && (fecha.getFullYear() >= new Date().getFullYear()) && (fecha.getMonth() >= new Date().getMonth())){
                    content += `<td>Activo</td>`;
                }else{
                    content += `<td>Inactivo</td>`;
                }
                    
                content +=`<td class="text-center"><button type="button" onclick="ver_historial_conductor(${ conductor.personal_id}, ${conductor.vehiculo_id})" class="btn btn-info waves-effect waves-light" data-toggle="modal" data-target="#modal_ver_historial_conductor"><i class="fa fa-eye"></i></button></td></tr> `;
            });
            
           if(content!=''){
            $('#content_table_conductores').html(content);
            }else{
                $('#content_table_conductores').html(`
                <tr>
                    <td colspan="8" class="text-center">
                        <p>No Existen Conductores</p>
                    </td>
                </tr>`);
            }

        }
    })
}

function eliminar_conductor(id, personal_id, vehiculo_id, btn) {
    $(btn).parent('td').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
    $.ajax({
        url: '/vehiculos/eliminar_conductor',
        type: 'POST',
        data: {id:id, personal_id:personal_id, vehiculo_id:vehiculo_id},
        success: function (data) {
            console.log(data);
            ver_historial_conductor(data.personal_id, data.vehiculo_id);
        }
    })
}

function ver_historial_conductor(id, vehiculo_id) {
    $.ajax({
        url: '/vehiculos/ver_conductor_historial',
        type: 'POST',
        data: {id:id, vehiculo_id:vehiculo_id},
        success: function (data) {
            var content = '';
            contado=0;
            data.forEach( function (conductor, indice) {
                $('#modal_ver_historial_conductor_title').html('Historial del conductor ' + conductor.personal.nombres + ' '+ conductor.personal.primer_apellido)
                fecha = new Date(conductor.fecha_final);
                contado++;
                estado='inactivo';
                if((fecha.getDate() >= new Date().getDate()) && (fecha.getFullYear() >= new Date().getFullYear()) && (fecha.getMonth() >= new Date().getMonth())){
                    estado='activo';
                }
                content += `<tr>
                <th class="text-center">${contado}</th>
                <th class="text-center">${formatoFecha(conductor.fecha_inicial)}</th>
                <th class="text-center">${formatoFecha(conductor.fecha_final)}</th>
                <th class="text-center">${restaFechas(conductor.fecha_inicial, conductor.fecha_final)} dias</th>
                <th class="text-center">${estado}</th>
                <th class="text-center"><button type="button" onclick="eliminar_conductor(${ conductor.id }, ${conductor.personal_id}, ${conductor.vehiculo_id}, this)" class="btn btn-danger waves-effect waves-light"><i class="fa fa-trash"></i></button></th>
            </tr>`;
            });
            
            $('#table_ver_historial_vehiculo').html(content);
            
        }
    })
}



function restaFechas(fechaa,fechab){
    let fecha1 = new Date(fechaa);
    let fecha2 = new Date(fechab);
    
    let resta = fecha2.getTime() - fecha1.getTime();
    return Math.round(resta/ (1000*60*60*24));
  }

  function formatoFecha(texto){
      if(texto == '' || texto == null){
        return null;
      }
      return texto.replace(/^(\d{4})-(\d{2})-(\d{2})$/g,'$3/$2/$1');
    
  }


function agg_documento_legal(tipo_documento, id_table) {
    $('#agg_doc_legal').modal('show')
    $('#agg_doc_legal_title').text('Agregar '+tipo_documento)
    $('#consecutivo_title').text('Consecutivo '+tipo_documento)
    $('#tipo').val(tipo_documento)
    $('#id_table').val(id_table)

    $('#consecutivo').val('');
    $('#fecha_expedicion').val('');
    $('#fecha_inicio_vigencia').val('');
    $('#fecha_fin_vigencia').val('');
    $('#id').val('');
    $('#estado').val('');

    $('#entidad_expide').html(call_method_select_entidad(tipo_documento))
}

function documentos_legales(tipo, vehiculo_id, id_table) {
    $.ajax({
        url: '/vehiculos/cargar_tarjeta_propiedad',
        type: 'POST',
        data: {tipo:tipo, vehiculo_id:vehiculo_id},
        success: function (data) {
            content = '';
            data.forEach(documento => {
                content += `
                <tr>
                    <td scope="row">${ documento.consecutivo }</td>
                    <td>${ formatoFecha(documento.fecha_expedicion) }</td>
                    <td>${ formatoFecha(documento.fecha_fin_vigencia) ?? 'No aplica' }</td>
                    <td>${ formatoFecha(documento.fecha_inicio_vigencia) ?? 'No aplica' }</td>
                    <td>${ (formatoFecha(documento.fecha_inicio_vigencia)) ? restaFechas(documento.fecha_fin_vigencia, documento.fecha_inicio_vigencia)  : 'No aplica' }</td>
                    <td width="250px">${ documento.entidad_expide }</td>
                    <td>${ documento.estado }</td>
                    <td width="180px" class="text-center">
                        <button type="button" onclick="editar_documento_legal(${ documento.id }, ${ documento.vehiculo_id }, '${ documento.tipo }')" class="btn btn-sm btn-info waves-effect waves-light"><i class="fa fa-edit"></i></button>
                        <button type="button" onclick="ver_documento_legal('${ documento.documento_file }', '${ documento.tipo }')" ${ (documento.documento_file) ? '' : 'disabled' } class="btn btn-sm btn-success waves-effect waves-light"><i class="fa fa-eye"></i></button>
                        <a href="/storage/${ documento.documento_file }" download class="${ (documento.documento_file) ? '' : 'disabled' } btn btn-sm btn-primary waves-effect waves-light"><i class="fa fa-download"></i></a>
                        <button type="button" onclick="eliminar_documento_legal(${ documento.id }, ${ documento.vehiculo_id }, '${ documento.tipo }', '${ id_table }')" class="btn btn-sm btn-danger waves-effect waves-light"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>
                `;
            });

            $('#'+id_table).html(content);
        }
    })
}

function eliminar_documento_legal(id, vehiculo_id, tipo, id_table) {
    $.ajax({
        url: '/vehiculos/eliminar_documento_legal',
        type: 'POST',
        data: {id:id, vehiculo_id:vehiculo_id, tipo:tipo},
        success: function (data) {
            documentos_legales(data.tipo, data.vehiculo_id, id_table);
        }
    });
}

function editar_documento_legal(id) {
    $.ajax({
        url: '/vehiculos/get_documento_legal',
        type: 'POST',
        data: {id:id},
        success: function (data) {
            $('#consecutivo').val(data.consecutivo);
            $('#fecha_expedicion').val(data.fecha_expedicion);
            $('#fecha_inicio_vigencia').val(data.fecha_inicio_vigencia);
            $('#fecha_fin_vigencia').val(data.fecha_fin_vigencia);
            $('#id').val(data.id);
            $('#estado').val(data.estado);
            $('#entidad_expide').html(call_method_select_entidad(data.tipo, entidad = data.entidad_expide));
            $('#agg_doc_legal_title').text('Editar Tarjeta de Propiedad');
            $('#agg_doc_legal').modal('show');
        }
    })
}

function ver_documento_legal(documento_file, tipo) {
    $('#modal_ver_documento_title').text(tipo)
    $('#modal_ver_documento_content').html(`<iframe src="/storage/${ documento_file }" width="100%" height="810px" frameborder="0"></iframe>`)
    $('#modal_ver_documento').modal('show')
}

function call_method_select_entidad(tipo_documento, entidad = '') {
    switch (tipo_documento) {
        case 'Tarjeta de Propiedad':
            $('#fecha_inicio_vigencia_div').addClass('d-none')
            $('#fecha_fin_vigencia_div').addClass('d-none')
            return `
                <option value=""></option>
                <option value="UND MCPAL TTOyTTE PALERMO" ${ (entidad == "UND MCPAL TTOyTTE PALERMO") ? 'selected' : '' }>UND MCPAL TTOyTTE PALERMO</option>
                <option value="MINISTERIO DE TRANSPORTE" ${ (entidad == "MINISTERIO DE TRANSPORTE") ? 'selected' : '' }>MINISTERIO DE TRANSPORTE</option>
                <option value="INSTITUTO DPTAL DE TRANSITO Y TRANSPORTE DEL CAQUETA/EL PAUJIL" ${ (entidad == "INSTITUTO DPTAL DE TRANSITO Y TRANSPORTE DEL CAQUETA/EL PAUJIL") ? 'selected' : '' }>INSTITUTO DPTAL DE TRANSITO Y TRANSPORTE DEL CAQUETA/EL PAUJIL</option>
                <option value="INSP TTOyTTE BARRANCABERMEJA" ${ (entidad == "INSP TTOyTTE BARRANCABERMEJA") ? 'selected' : '' }>INSP TTOyTTE BARRANCABERMEJA</option>
                <option value="CENTRO DE DIAGNOSTICO AUTOMOTRIZ C.D.A. LOS DUJOS LTDA." ${ (entidad == "CENTRO DE DIAGNOSTICO AUTOMOTRIZ C.D.A. LOS DUJOS LTDA.") ? 'selected' : '' }>CENTRO DE DIAGNOSTICO AUTOMOTRIZ C.D.A. LOS DUJOS LTDA.</option>
                <option value="SECRETARIA DE TRANSITO Y TRANSPORTE MUNICIPAL FUNZA" ${ (entidad == "SECRETARIA DE TRANSITO Y TRANSPORTE MUNICIPAL FUNZA") ? 'selected' : '' }>SECRETARIA DE TRANSITO Y TRANSPORTE MUNICIPAL FUNZA</option>
                <option value="STRIA TTEMOV CUND/SOACHA" ${ (entidad == "STRIA TTEMOV CUND/SOACHA") ? 'selected' : '' }>STRIA TTEMOV CUND/SOACHA </option>
                <option value="SECRETARIA DE TRANSITO DE CUNDINAMARCA/COTA" ${ (entidad == "SECRETARIA DE TRANSITO DE CUNDINAMARCA/COTA") ? 'selected' : '' }>SECRETARIA DE TRANSITO DE CUNDINAMARCA/COTA</option>
                <option value="SECRETARIA DE TRANSITO Y TRANSPORTE DE NEIVA" ${ (entidad == "SECRETARIA DE TRANSITO Y TRANSPORTE DE NEIVA") ? 'selected' : '' }>SECRETARIA DE TRANSITO Y TRANSPORTE DE NEIVA</option>
                <option value="DIRECCION DE TRANSITO DE BUCARAMANGA" ${ (entidad == "DIRECCION DE TRANSITO DE BUCARAMANGA") ? 'selected' : '' }>DIRECCION DE TRANSITO DE BUCARAMANGA</option>
                <option value="DIRECCION TERRITORIAL MAGDALENA" ${ (entidad == "DIRECCION TERRITORIAL MAGDALENA") ? 'selected' : '' }>DIRECCION TERRITORIAL MAGDALENA</option>
                <option value="INSTITUTO DE TRANSPORTES Y TRANSITO DEL HUILA" ${ (entidad == "INSTITUTO DE TRANSPORTES Y TRANSITO DEL HUILA") ? 'selected' : '' }>INSTITUTO DE TRANSPORTES Y TRANSITO DEL HUILA</option>
            `;
            break;

        case 'Tarjeta Operación':
            $('#fecha_inicio_vigencia_div').removeClass('d-none')
            $('#fecha_fin_vigencia_div').removeClass('d-none')
            return `
                <option value=""></option>
                <option value="SEGUROS GENERALES SURAMERICANA S. A" ${ (entidad == "SEGUROS GENERALES SURAMERICANA S. A") ? 'selected' : '' }>SEGUROS GENERALES SURAMERICANA S. A.</option>
                <option value="MINISTERIO DE TRANSPORTE" ${ (entidad == "MINISTERIO DE TRANSPORTE") ? 'selected' : '' }>MINISTERIO DE TRANSPORTE.</option>
                <option value="AXA COLPATRIA SEGUROS S.A." ${ (entidad == "AXA COLPATRIA SEGUROS S.A.") ? 'selected' : '' }>AXA COLPATRIA SEGUROS S.A.</option>
                <option value="TECNOSUR LOCALIZACIÓN Y RASTREO" ${ (entidad == "TECNOSUR LOCALIZACIÓN Y RASTREO") ? 'selected' : '' }>TECNOSUR LOCALIZACIÓN Y RASTREO </option>
                <option value="COMPAÑIA DE SEGUROS BOLIVAR S.A." ${ (entidad == "COMPAÑIA DE SEGUROS BOLIVAR S.A.") ? 'selected' : '' }>COMPAÑIA DE SEGUROS BOLIVAR S.A.</option>
                <option value="ASEGURADORA SOLIDARIA DE COLOMBIA ENTIDAD COOPERATIVA" ${ (entidad == "ASEGURADORA SOLIDARIA DE COLOMBIA ENTIDAD COOPERATIVA") ? 'selected' : '' }>ASEGURADORA SOLIDARIA DE COLOMBIA ENTIDAD COOPERATIVA</option>
                <option value="COMPAÑIA MUNDIAL DE SEGUROS S.A." ${ (entidad == "COMPAÑIA MUNDIAL DE SEGUROS S.A.") ? 'selected' : '' }>COMPAÑIA MUNDIAL DE SEGUROS S.A.</option>
                <option value="SBS SEGUROS COLOMBIA S.A." ${ (entidad == "SBS SEGUROS COLOMBIA S.A.") ? 'selected' : '' }>SBS SEGUROS COLOMBIA S.A.</option>
                <option value="SEGUROS DEL ESTADO SA" ${ (entidad == "SEGUROS DEL ESTADO SA") ? 'selected' : '' }>SEGUROS DEL ESTADO SA</option>
                <option value="MAPFRE SEGUROS GENERALES DE COLOMBIA S A" ${ (entidad == "MAPFRE SEGUROS GENERALES DE COLOMBIA S A") ? 'selected' : '' }>MAPFRE SEGUROS GENERALES DE COLOMBIA S A</option>
                <option value="LA EQUIDAD SEGUROS  DE VIDA OC" ${ (entidad == "LA EQUIDAD SEGUROS  DE VIDA OC") ? 'selected' : '' }>LA EQUIDAD SEGUROS  DE VIDA OC</option>
                <option value="LA PREVISORA S A COMPAÑIA DE SEGUROS" ${ (entidad == "LA PREVISORA S A COMPAÑIA DE SEGUROS") ? 'selected' : '' }>LA PREVISORA S A COMPAÑIA DE SEGUROS</option>
                <option value="LIBERTY SEGUROS S.A." ${ (entidad == "LIBERTY SEGUROS S.A.") ? 'selected' : '' }>LIBERTY SEGUROS S.A.</option>
            `;
            break;

        case 'SOAT':
            $('#fecha_inicio_vigencia_div').removeClass('d-none')
            $('#fecha_fin_vigencia_div').removeClass('d-none')
            return `
                <option value=""></option>
                <option value="AMAZONIA CONSULTORIA & LOGISTICA SAS" ${ (entidad == 'AMAZONIA CONSULTORIA & LOGISTICA SAS') ? 'selected' : '' }>AMAZONIA CONSULTORIA & LOGISTICA SAS</option>
                <option value="AXA COLPATRIA SEGUROS S.A." ${ (entidad == 'AXA COLPATRIA SEGUROS S.A.') ? 'selected' : '' }>AXA COLPATRIA SEGUROS S.A.</option>
                <option value="TECNOSUR LOCALIZACIÓN Y RASTREO" ${ (entidad == 'TECNOSUR LOCALIZACIÓN Y RASTREO') ? 'selected' : '' }>TECNOSUR LOCALIZACIÓN Y RASTREO </option>
                <option value="BANCO CAJA  SOCIAL" ${ (entidad == 'BANCO CAJA  SOCIAL') ? 'selected' : '' }>BANCO CAJA  SOCIAL </option>
                <option value="SEGUROS GENERALES SURAMERICANA S. A." ${ (entidad == 'SEGUROS GENERALES SURAMERICANA S. A.') ? 'selected' : '' }>SEGUROS GENERALES SURAMERICANA S. A.</option>
                <option value="LA EQUIDAD SEGUROS  DE VIDA OC" ${ (entidad == 'LA EQUIDAD SEGUROS  DE VIDA OC') ? 'selected' : '' }>LA EQUIDAD SEGUROS  DE VIDA OC</option>
                <option value="LA PREVISORA S A COMPAÑIA DE SEGUROS" ${ (entidad == 'LA PREVISORA S A COMPAÑIA DE SEGUROS') ? 'selected' : '' }>LA PREVISORA S A COMPAÑIA DE SEGUROS</option>
                <option value="LIBERTY SEGUROS S.A." ${ (entidad == 'LIBERTY SEGUROS S.A.') ? 'selected' : '' }>LIBERTY SEGUROS S.A.</option>
                <option value="SBS SEGUROS COLOMBIA S.A." ${ (entidad == 'SBS SEGUROS COLOMBIA S.A.') ? 'selected' : '' }>SBS SEGUROS COLOMBIA S.A.</option>
                <option value="SEGUROS DEL ESTADO SA" ${ (entidad == 'SEGUROS DEL ESTADO SA') ? 'selected' : '' }>SEGUROS DEL ESTADO SA</option>
                <option value="MAPFRE SEGUROS GENERALES DE COLOMBIA S A" ${ (entidad == 'MAPFRE SEGUROS GENERALES DE COLOMBIA S A') ? 'selected' : '' }>MAPFRE SEGUROS GENERALES DE COLOMBIA S A</option>
                <option value="ASEGURADORA SOLIDARIA DE COLOMBIA ENTIDAD COOPERATIVA" ${ (entidad == 'ASEGURADORA SOLIDARIA DE COLOMBIA ENTIDAD COOPERATIVA') ? 'selected' : '' }>ASEGURADORA SOLIDARIA DE COLOMBIA ENTIDAD COOPERATIVA</option>
                <option value="COMPAÑIA DE SEGUROS BOLIVAR S.A." ${ (entidad == 'COMPAÑIA DE SEGUROS BOLIVAR S.A.') ? 'selected' : '' }>COMPAÑIA DE SEGUROS BOLIVAR S.A.</option>
                <option value="COMPAÑIA MUNDIAL DE SEGUROS S.A." ${ (entidad == 'COMPAÑIA MUNDIAL DE SEGUROS S.A.') ? 'selected' : '' }>COMPAÑIA MUNDIAL DE SEGUROS S.A.</option>
            `;
            break;

        case 'Técnico Mecánica':
            $('#fecha_inicio_vigencia_div').removeClass('d-none')
            $('#fecha_fin_vigencia_div').removeClass('d-none')
            return `
                <option value=""></option>
                <option value="CDA DE NEIVA S.A.S" ${ (entidad == 'CDA DE NEIVA S.A.S') ? 'selected' : '' }>CDA DE NEIVA S.A.S</option>
                <option value="CDA DEL CAQUETA" ${ (entidad == 'CDA DEL CAQUETA') ? 'selected' : '' }>CDA DEL CAQUETA </option>
                <option value="CENTRO DE DIAGNOSTICO AUTOMOTOR OPTIMO S.A.S" ${ (entidad == 'CENTRO DE DIAGNOSTICO AUTOMOTOR OPTIMO S.A.S') ? 'selected' : '' }>CENTRO DE DIAGNOSTICO AUTOMOTOR OPTIMO S.A.S</option>
                <option value="GARCIA &amp; GARCIA CDA DE NEIVA S.A.S. - CDA DE NEIVA SAS" ${ (entidad == 'GARCIA &amp; GARCIA CDA DE NEIVA S.A.S. - CDA DE NEIVA SAS') ? 'selected' : '' }>GARCIA &amp; GARCIA CDA DE NEIVA S.A.S. - CDA DE NEIVA SAS</option>
                <option value="CENTRO DE DIAGNOSTICO AUTOMOTRIZ C.D.A. LOS DUJOS LTDA." ${ (entidad == 'CENTRO DE DIAGNOSTICO AUTOMOTRIZ C.D.A. LOS DUJOS LTDA.') ? 'selected' : '' }>CENTRO DE DIAGNOSTICO AUTOMOTRIZ C.D.A. LOS DUJOS LTDA.</option>
                <option value="CDA CARLLANOS VILLAVICENCIO S.A.S" ${ (entidad == 'CDA CARLLANOS VILLAVICENCIO S.A.S') ? 'selected' : '' }>CDA CARLLANOS VILLAVICENCIO S.A.S</option>
                <option value="C.D.A. MAXITEC S.A.S" ${ (entidad == 'C.D.A. MAXITEC S.A.S') ? 'selected' : '' }>C.D.A. MAXITEC S.A.S</option>
                <option value="CENTRO DE DIAGNOSTICO AUTOMOTOR LOS CENTAUROS S.A.S" ${ (entidad == 'CENTRO DE DIAGNOSTICO AUTOMOTOR LOS CENTAUROS S.A.S') ? 'selected' : '' }>CENTRO DE DIAGNOSTICO AUTOMOTOR LOS CENTAUROS S.A.S</option>
                <option value="CENTRO NACIONAL DE DIAGNOSTICO AUTOMOTOR SEGURA LIMITADA" ${ (entidad == 'CENTRO NACIONAL DE DIAGNOSTICO AUTOMOTOR SEGURA LIMITADA') ? 'selected' : '' }>CENTRO NACIONAL DE DIAGNOSTICO AUTOMOTOR SEGURA LIMITADA</option>
                <option value="CENTRO DE DIAGNOSTICO AUTOMOTOR OPTIMO S.A.S" ${ (entidad == 'CENTRO DE DIAGNOSTICO AUTOMOTOR OPTIMO S.A.S') ? 'selected' : '' }>CENTRO DE DIAGNOSTICO AUTOMOTOR OPTIMO S.A.S</option>
                <option value="CDA SUPERCARS" ${ (entidad == 'CDA SUPERCARS') ? 'selected' : '' }>CDA SUPERCARS</option>
                <option value="CENTRO DE DIAGNOSTICO AUTOMOTOR MAXITEC S.A.S." ${ (entidad == 'CENTRO DE DIAGNOSTICO AUTOMOTOR MAXITEC S.A.S.') ? 'selected' : '' }>CENTRO DE DIAGNOSTICO AUTOMOTOR MAXITEC S.A.S.</option>
                <option value="C.D.A. REIMAR LTDA." ${ (entidad == 'C.D.A. REIMAR LTDA.') ? 'selected' : '' }>C.D.A. REIMAR LTDA.</option>
                <option value="NO APLICA" ${ (entidad == 'NO APLICA') ? 'selected' : '' }>NO APLICA </option>
                <option value="INVERSIONES FLOTA HUILA S.A" ${ (entidad == 'INVERSIONES FLOTA HUILA S.A') ? 'selected' : '' }>INVERSIONES FLOTA HUILA S.A</option>
                <option value="CDA DEL PUTUMAYO E.U" ${ (entidad == 'CDA DEL PUTUMAYO E.U') ? 'selected' : '' }>CDA DEL PUTUMAYO E.U</option>
            `;
            break;

        case 'Seguro Todo Riesgo':
            $('#fecha_inicio_vigencia_div').removeClass('d-none')
            $('#fecha_fin_vigencia_div').removeClass('d-none')
            return `
                <option value=""></option>
                <option value="SEGUROS GENERALES SURAMERICANA S. A." ${ (entidad == 'SEGUROS GENERALES SURAMERICANA S. A.') ? 'selected' : '' }>SEGUROS GENERALES SURAMERICANA S. A.</option>
                <option value="AXA COLPATRIA SEGUROS S.A." ${ (entidad == 'AXA COLPATRIA SEGUROS S.A.') ? 'selected' : '' }>AXA COLPATRIA SEGUROS S.A.</option>
                <option value="TECNOSUR LOCALIZACIÓN Y RASTREO" ${ (entidad == 'TECNOSUR LOCALIZACIÓN Y RASTREO') ? 'selected' : '' }>TECNOSUR LOCALIZACIÓN Y RASTREO </option>
                <option value="ASEGURADORA SOLIDARIA DE COLOMBIA ENTIDAD COOPERATIVA" ${ (entidad == 'ASEGURADORA SOLIDARIA DE COLOMBIA ENTIDAD COOPERATIVA') ? 'selected' : '' }>ASEGURADORA SOLIDARIA DE COLOMBIA ENTIDAD COOPERATIVA</option>
                <option value="COMPAÑIA DE SEGUROS BOLIVAR S.A." ${ (entidad == 'COMPAÑIA DE SEGUROS BOLIVAR S.A.') ? 'selected' : '' }>COMPAÑIA DE SEGUROS BOLIVAR S.A.</option>
                <option value="COMPAÑIA MUNDIAL DE SEGUROS S.A." ${ (entidad == 'COMPAÑIA MUNDIAL DE SEGUROS S.A.') ? 'selected' : '' }>COMPAÑIA MUNDIAL DE SEGUROS S.A.</option>
                <option value="SBS SEGUROS COLOMBIA S.A." ${ (entidad == 'SBS SEGUROS COLOMBIA S.A.') ? 'selected' : '' }>SBS SEGUROS COLOMBIA S.A.</option>
                <option value="SEGUROS DEL ESTADO SA" ${ (entidad == 'SEGUROS DEL ESTADO SA') ? 'selected' : '' }>SEGUROS DEL ESTADO SA</option>
                <option value="MAPFRE SEGUROS GENERALES DE COLOMBIA S A" ${ (entidad == 'MAPFRE SEGUROS GENERALES DE COLOMBIA S A') ? 'selected' : '' }>MAPFRE SEGUROS GENERALES DE COLOMBIA S A</option>
                <option value="LA EQUIDAD SEGUROS  DE VIDA OC" ${ (entidad == 'LA EQUIDAD SEGUROS  DE VIDA OC') ? 'selected' : '' }>LA EQUIDAD SEGUROS  DE VIDA OC</option>
                <option value="LA PREVISORA S A COMPAÑIA DE SEGUROS" ${ (entidad == 'LA PREVISORA S A COMPAÑIA DE SEGUROS') ? 'selected' : '' }>LA PREVISORA S A COMPAÑIA DE SEGUROS</option>
                <option value="LIBERTY SEGUROS S.A." ${ (entidad == 'LIBERTY SEGUROS S.A.') ? 'selected' : '' }>LIBERTY SEGUROS S.A.</option>
            `;
            break;

        case 'Certificado GPS':
            $('#fecha_inicio_vigencia_div').removeClass('d-none')
            $('#fecha_fin_vigencia_div').removeClass('d-none')
            return `
                <option value=""></option>
                <option value="ROCA GPS S.A.S" ${ (entidad == 'ROCA GPS S.A.S') ? 'selected' : '' }>ROCA GPS S.A.S</option>
                <option value="COTRANSHUILA LTDA." ${ (entidad == 'COTRANSHUILA LTDA.') ? 'selected' : '' }>COTRANSHUILA LTDA.</option>
            `;
            break;

        case 'RUNT':
            $('#fecha_inicio_vigencia_div').removeClass('d-none')
            $('#fecha_fin_vigencia_div').removeClass('d-none')
            return `
                <option value=""></option>
                <option value="SECRETARIA DE TRANSITO DE CUNDINAMARCA/COTA" ${ (entidad == 'SECRETARIA DE TRANSITO DE CUNDINAMARCA/COTA') ? 'selected' : '' }>SECRETARIA DE TRANSITO DE CUNDINAMARCA/COTA</option>
                <option value="SECRETARIA DE TRANSITO Y TRANSPORTE DE NEIVA" ${ (entidad == 'SECRETARIA DE TRANSITO Y TRANSPORTE DE NEIVA') ? 'selected' : '' }>SECRETARIA DE TRANSITO Y TRANSPORTE DE NEIVA</option>
                <option value="DIRECCION DE TRANSITO DE BUCARAMANGA" ${ (entidad == 'DIRECCION DE TRANSITO DE BUCARAMANGA') ? 'selected' : '' }>DIRECCION DE TRANSITO DE BUCARAMANGA</option>
                <option value="DIRECCION TERRITORIAL MAGDALENA" ${ (entidad == 'DIRECCION TERRITORIAL MAGDALENA') ? 'selected' : '' }>DIRECCION TERRITORIAL MAGDALENA</option>
                <option value="INSTITUTO DE TRANSPORTES Y TRANSITO DEL HUILA" ${ (entidad == 'INSTITUTO DE TRANSPORTES Y TRANSITO DEL HUILA') ? 'selected' : '' }>INSTITUTO DE TRANSPORTES Y TRANSITO DEL HUILA</option>
                <option value="CENTRO DE DIAGNOSTICO AUTOMOTRIZ C.D.A. LOS DUJOS LTDA." ${ (entidad == 'CENTRO DE DIAGNOSTICO AUTOMOTRIZ C.D.A. LOS DUJOS LTDA.') ? 'selected' : '' }>CENTRO DE DIAGNOSTICO AUTOMOTRIZ C.D.A. LOS DUJOS LTDA.</option>
                <option value="SECRETARIA DE TRANSITO Y TRANSPORTE MUNICIPAL FUNZA" ${ (entidad == 'SECRETARIA DE TRANSITO Y TRANSPORTE MUNICIPAL FUNZA') ? 'selected' : '' }>SECRETARIA DE TRANSITO Y TRANSPORTE MUNICIPAL FUNZA</option>
                <option value="STRIA TTEMOV CUND/SOACHA" ${ (entidad == 'STRIA TTEMOV CUND/SOACHA') ? 'selected' : '' }>STRIA TTEMOV CUND/SOACHA </option>
                <option value="INSP TTOyTTE BARRANCABERMEJA" ${ (entidad == 'INSP TTOyTTE BARRANCABERMEJA') ? 'selected' : '' }>INSP TTOyTTE BARRANCABERMEJA</option>
                <option value="UND MCPAL TTOyTTE PALERMO" ${ (entidad == 'UND MCPAL TTOyTTE PALERMO') ? 'selected' : '' }>UND MCPAL TTOyTTE PALERMO</option>
                <option value="MINISTERIO DE TRANSPORTE" ${ (entidad == 'MINISTERIO DE TRANSPORTE') ? 'selected' : '' }>MINISTERIO DE TRANSPORTE</option>
                <option value="AMAZONIA CONSULTORIA &amp; LOGISTICA SAS" ${ (entidad == 'AMAZONIA CONSULTORIA &amp; LOGISTICA SAS') ? 'selected' : '' }>AMAZONIA CONSULTORIA &amp; LOGISTICA SAS</option>
                <option value="BANCO CAJA  SOCIAL" ${ (entidad == 'BANCO CAJA  SOCIAL') ? 'selected' : '' }>BANCO CAJA  SOCIAL </option>
                <option value="INSTITUTO DPTAL DE TRANSITO Y TRANSPORTE DEL CAQUETA/EL PAUJIL" ${ (entidad == 'INSTITUTO DPTAL DE TRANSITO Y TRANSPORTE DEL CAQUETA/EL PAUJIL') ? 'selected' : '' }>INSTITUTO DPTAL DE TRANSITO Y TRANSPORTE DEL CAQUETA/EL PAUJIL</option>
            `;
            break;

        case 'Póliza contractual':
            $('#fecha_inicio_vigencia_div').removeClass('d-none')
            $('#fecha_fin_vigencia_div').removeClass('d-none')
            return `
                <option value=""></option>
                <option value="COMPAÑIA MUNDIAL DE SEGUROS S.A." ${ (entidad == 'COMPAÑIA MUNDIAL DE SEGUROS S.A.') ? 'selected' : '' }>COMPAÑIA MUNDIAL DE SEGUROS S.A.</option>
                <option value="COMPAÑIA DE SEGUROS BOLIVAR S.A." ${ (entidad == 'COMPAÑIA DE SEGUROS BOLIVAR S.A.') ? 'selected' : '' }>COMPAÑIA DE SEGUROS BOLIVAR S.A.</option>
                <option value="ASEGURADORA SOLIDARIA DE COLOMBIA ENTIDAD COOPERATIVA" ${ (entidad == 'ASEGURADORA SOLIDARIA DE COLOMBIA ENTIDAD COOPERATIVA') ? 'selected' : '' }>ASEGURADORA SOLIDARIA DE COLOMBIA ENTIDAD COOPERATIVA</option>
                <option value="SEGUROS DEL ESTADO SA" ${ (entidad == 'SEGUROS DEL ESTADO SA') ? 'selected' : '' }>SEGUROS DEL ESTADO SA</option>
                <option value="MAPFRE SEGUROS GENERALES DE COLOMBIA S A" ${ (entidad == 'MAPFRE SEGUROS GENERALES DE COLOMBIA S A') ? 'selected' : '' }>MAPFRE SEGUROS GENERALES DE COLOMBIA S A</option>
                <option value="SBS SEGUROS COLOMBIA S.A." ${ (entidad == 'SBS SEGUROS COLOMBIA S.A.') ? 'selected' : '' }>SBS SEGUROS COLOMBIA S.A.</option>
                <option value="LA EQUIDAD SEGUROS  DE VIDA OC" ${ (entidad == 'LA EQUIDAD SEGUROS  DE VIDA OC') ? 'selected' : '' }>LA EQUIDAD SEGUROS  DE VIDA OC</option>
                <option value="LA PREVISORA S A COMPAÑIA DE SEGUROS" ${ (entidad == 'LA PREVISORA S A COMPAÑIA DE SEGUROS') ? 'selected' : '' }>LA PREVISORA S A COMPAÑIA DE SEGUROS</option>
                <option value="LIBERTY SEGUROS S.A." ${ (entidad == 'LIBERTY SEGUROS S.A.') ? 'selected' : '' }>LIBERTY SEGUROS S.A.</option>
                <option value="SEGUROS GENERALES SURAMERICANA S. A." ${ (entidad == 'SEGUROS GENERALES SURAMERICANA S. A.') ? 'selected' : '' }>SEGUROS GENERALES SURAMERICANA S. A.</option>
                <option value="TECNOSUR LOCALIZACIÓN Y RASTREO" ${ (entidad == 'TECNOSUR LOCALIZACIÓN Y RASTREO') ? 'selected' : '' }>TECNOSUR LOCALIZACIÓN Y RASTREO </option>
                <option value="AXA COLPATRIA SEGUROS S.A." ${ (entidad == 'AXA COLPATRIA SEGUROS S.A.') ? 'selected' : '' }>AXA COLPATRIA SEGUROS S.A.</option>
            `;
            break;

        case 'Póliza extracontractual':
            $('#fecha_inicio_vigencia_div').removeClass('d-none')
            $('#fecha_fin_vigencia_div').removeClass('d-none')
            return `
                <option value=""></option>
                <option value="SEGUROS GENERALES SURAMERICANA S. A." ${ (entidad == 'SEGUROS GENERALES SURAMERICANA S. A.') ? 'selected' : '' }>SEGUROS GENERALES SURAMERICANA S. A.</option>
                <option value="AXA COLPATRIA SEGUROS S.A." ${ (entidad == 'AXA COLPATRIA SEGUROS S.A.') ? 'selected' : '' }>AXA COLPATRIA SEGUROS S.A.</option>
                <option value="TECNOSUR LOCALIZACIÓN Y RASTREO" ${ (entidad == 'TECNOSUR LOCALIZACIÓN Y RASTREO') ? 'selected' : '' }>TECNOSUR LOCALIZACIÓN Y RASTREO </option>
                <option value="ASEGURADORA SOLIDARIA DE COLOMBIA ENTIDAD COOPERATIVA" ${ (entidad == 'ASEGURADORA SOLIDARIA DE COLOMBIA ENTIDAD COOPERATIVA') ? 'selected' : '' }>ASEGURADORA SOLIDARIA DE COLOMBIA ENTIDAD COOPERATIVA</option>
                <option value="COMPAÑIA DE SEGUROS BOLIVAR S.A." ${ (entidad == 'COMPAÑIA DE SEGUROS BOLIVAR S.A.') ? 'selected' : '' }>COMPAÑIA DE SEGUROS BOLIVAR S.A.</option>
                <option value="COMPAÑIA MUNDIAL DE SEGUROS S.A." ${ (entidad == 'COMPAÑIA MUNDIAL DE SEGUROS S.A.') ? 'selected' : '' }>COMPAÑIA MUNDIAL DE SEGUROS S.A.</option>
                <option value="SBS SEGUROS COLOMBIA S.A." ${ (entidad == 'SBS SEGUROS COLOMBIA S.A.') ? 'selected' : '' }>SBS SEGUROS COLOMBIA S.A.</option>
                <option value="SEGUROS DEL ESTADO SA" ${ (entidad == 'SEGUROS DEL ESTADO SA') ? 'selected' : '' }>SEGUROS DEL ESTADO SA</option>
                <option value="MAPFRE SEGUROS GENERALES DE COLOMBIA S A" ${ (entidad == 'MAPFRE SEGUROS GENERALES DE COLOMBIA S A') ? 'selected' : '' }>MAPFRE SEGUROS GENERALES DE COLOMBIA S A</option>
                <option value="LA EQUIDAD SEGUROS  DE VIDA OC" ${ (entidad == 'LA EQUIDAD SEGUROS  DE VIDA OC') ? 'selected' : '' }>LA EQUIDAD SEGUROS  DE VIDA OC</option>
                <option value="LA PREVISORA S A COMPAÑIA DE SEGUROS" ${ (entidad == 'LA PREVISORA S A COMPAÑIA DE SEGUROS') ? 'selected' : '' }>LA PREVISORA S A COMPAÑIA DE SEGUROS</option>
                <option value="LIBERTY SEGUROS S.A." ${ (entidad == 'LIBERTY SEGUROS S.A.') ? 'selected' : '' }>LIBERTY SEGUROS S.A.</option>
            `;
            break;

        case 'Certificado de desvinculación':
            $('#fecha_inicio_vigencia_div').removeClass('d-none')
            $('#fecha_fin_vigencia_div').removeClass('d-none')
            return `
                <option value=""></option>
                <option value="AMAZONIA CONSULTORIA & LOGISTICA SAS" ${ (entidad == 'AMAZONIA CONSULTORIA & LOGISTICA SAS') ? 'selected' : '' }>AMAZONIA CONSULTORIA & LOGISTICA SAS</option>
                <option value="BANCO CAJA  SOCIAL" ${ (entidad == 'BANCO CAJA  SOCIAL') ? 'selected' : '' }>BANCO CAJA  SOCIAL </option>
            `;
            break;

        case 'Solicitud de cambio de empresa en la tarjeta de operación':
            $('#fecha_inicio_vigencia_div').removeClass('d-none')
            $('#fecha_fin_vigencia_div').removeClass('d-none')
            return `
                <option value=""></option>
                <option value="AMAZONIA CONSULTORIA & LOGISTICA SAS" ${ (entidad == 'AMAZONIA CONSULTORIA & LOGISTICA SAS') ? 'selected' : '' }>AMAZONIA CONSULTORIA & LOGISTICA SAS</option>
                <option value="BANCO CAJA  SOCIAL" ${ (entidad == 'BANCO CAJA  SOCIAL') ? 'selected' : '' }>BANCO CAJA  SOCIAL </option>
            `;
            break;

        case 'Solicitud y/o certificado de disponibilidad':
            $('#fecha_inicio_vigencia_div').removeClass('d-none')
            $('#fecha_fin_vigencia_div').removeClass('d-none')
            return `
                <option value=""></option>
                <option value="INSP TTOyTTE BARRANCABERMEJA" ${ (entidad == 'INSP TTOyTTE BARRANCABERMEJA') ? 'selected' : '' }>INSP TTOyTTE BARRANCABERMEJA</option>
                <option value="INSTITUTO DPTAL DE TRANSITO Y TRANSPORTE DEL CAQUETA/EL PAUJIL" ${ (entidad == 'INSTITUTO DPTAL DE TRANSITO Y TRANSPORTE DEL CAQUETA/EL PAUJIL') ? 'selected' : '' }>INSTITUTO DPTAL DE TRANSITO Y TRANSPORTE DEL CAQUETA/EL PAUJIL</option>
                <option value="MINISTERIO DE TRANSPORTE" ${ (entidad == 'MINISTERIO DE TRANSPORTE') ? 'selected' : '' }>MINISTERIO DE TRANSPORTE</option>
                <option value="UND MCPAL TTOyTTE PALERMO" ${ (entidad == 'UND MCPAL TTOyTTE PALERMO') ? 'selected' : '' }>UND MCPAL TTOyTTE PALERMO</option>
                <option value="INSTITUTO DE TRANSPORTES Y TRANSITO DEL HUILA" ${ (entidad == 'INSTITUTO DE TRANSPORTES Y TRANSITO DEL HUILA') ? 'selected' : '' }>INSTITUTO DE TRANSPORTES Y TRANSITO DEL HUILA</option>
                <option value="DIRECCION DE TRANSITO DE BUCARAMANGA" ${ (entidad == 'DIRECCION DE TRANSITO DE BUCARAMANGA') ? 'selected' : '' }>DIRECCION DE TRANSITO DE BUCARAMANGA</option>
                <option value="DIRECCION TERRITORIAL MAGDALENA" ${ (entidad == 'DIRECCION TERRITORIAL MAGDALENA') ? 'selected' : '' }>DIRECCION TERRITORIAL MAGDALENA</option>
                <option value="SECRETARIA DE TRANSITO Y TRANSPORTE DE NEIVA" ${ (entidad == 'SECRETARIA DE TRANSITO Y TRANSPORTE DE NEIVA') ? 'selected' : '' }>SECRETARIA DE TRANSITO Y TRANSPORTE DE NEIVA</option>
                <option value="SECRETARIA DE TRANSITO DE CUNDINAMARCA/COTA" ${ (entidad == 'SECRETARIA DE TRANSITO DE CUNDINAMARCA/COTA') ? 'selected' : '' }>SECRETARIA DE TRANSITO DE CUNDINAMARCA/COTA</option>
                <option value="SECRETARIA DE TRANSITO Y TRANSPORTE MUNICIPAL FUNZA" ${ (entidad == 'SECRETARIA DE TRANSITO Y TRANSPORTE MUNICIPAL FUNZA') ? 'selected' : '' }>SECRETARIA DE TRANSITO Y TRANSPORTE MUNICIPAL FUNZA</option>
                <option value="STRIA TTEMOV CUND/SOACHA" ${ (entidad == 'STRIA TTEMOV CUND/SOACHA') ? 'selected' : '' }>STRIA TTEMOV CUND/SOACHA </option>
                <option value="2019050214181390455" ${ (entidad == 'CENTRO DE DIAGNOSTICO AUTOMOTRIZ C.D.A. LOS DUJOS LTDA.') ? 'selected' : '' }>CENTRO DE DIAGNOSTICO AUTOMOTRIZ C.D.A. LOS DUJOS LTDA.</option>
            `;
            break;

        case 'Certificado de seción de derechos (SIG-CA-F-21)':
            $('#fecha_inicio_vigencia_div').removeClass('d-none')
            $('#fecha_fin_vigencia_div').removeClass('d-none')
            return `
                <option value=""></option>
                <option value="AMAZONIA CONSULTORIA & LOGISTICA SAS" ${ (entidad == 'AMAZONIA CONSULTORIA & LOGISTICA SAS') ? 'selected' : '' }>AMAZONIA CONSULTORIA & LOGISTICA SAS</option>
                <option value="BANCO CAJA  SOCIAL" ${ (entidad == 'BANCO CAJA  SOCIAL') ? 'selected' : '' }>BANCO CAJA  SOCIAL </option>
            `;
            break;

        case 'Carta de aceptación (SIG-CA-F-21)':
            $('#fecha_inicio_vigencia_div').removeClass('d-none')
            $('#fecha_fin_vigencia_div').removeClass('d-none')
            return `
                <option value=""></option>
                <option value="AMAZONIA CONSULTORIA & LOGISTICA SAS" ${ (entidad == 'AMAZONIA CONSULTORIA & LOGISTICA SAS') ? 'selected' : '' }>AMAZONIA CONSULTORIA & LOGISTICA SAS</option>
                <option value="BANCO CAJA  SOCIAL" ${ (entidad == 'BANCO CAJA  SOCIAL') ? 'selected' : '' }>BANCO CAJA  SOCIAL </option>
            `;
            break;

        case 'Contrato de vinculación (SIG-CA-F-75)':
            $('#fecha_inicio_vigencia_div').removeClass('d-none')
            $('#fecha_fin_vigencia_div').removeClass('d-none')
            return `
                <option value=""></option>
                <option value="AMAZONIA CONSULTORIA & LOGISTICA SAS" ${ (entidad == 'AMAZONIA CONSULTORIA & LOGISTICA SAS') ? 'selected' : '' }>AMAZONIA CONSULTORIA & LOGISTICA SAS</option>
                <option value="BANCO CAJA  SOCIAL" ${ (entidad == 'BANCO CAJA  SOCIAL') ? 'selected' : '' }>BANCO CAJA  SOCIAL </option>
            `;
            break;

        case 'Pagare-carta de instrucciones (SIG-F-80)':
            $('#fecha_inicio_vigencia_div').removeClass('d-none')
            $('#fecha_fin_vigencia_div').removeClass('d-none')
            return `
                <option value=""></option>
                <option value="AMAZONIA CONSULTORIA & LOGISTICA SAS" ${ (entidad == 'AMAZONIA CONSULTORIA & LOGISTICA SAS') ? 'selected' : '' }>AMAZONIA CONSULTORIA & LOGISTICA SAS</option>
                <option value="BANCO CAJA  SOCIAL" ${ (entidad == 'BANCO CAJA  SOCIAL') ? 'selected' : '' }>BANCO CAJA  SOCIAL </option>
            `;
            break;

        case 'Compraventa':
            $('#fecha_inicio_vigencia_div').removeClass('d-none')
            $('#fecha_fin_vigencia_div').removeClass('d-none')
            return `
                <option value=""></option>
                <option value="AMAZONIA CONSULTORIA & LOGISTICA SAS" ${ (entidad == 'AMAZONIA CONSULTORIA & LOGISTICA SAS') ? 'selected' : '' }>AMAZONIA CONSULTORIA & LOGISTICA SAS</option>
                <option value="CDA DE NEIVA S.A.S" ${ (entidad == 'CDA DE NEIVA S.A.S') ? 'selected' : '' }>CDA DE NEIVA S.A.S</option>
                <option value="CDA DEL CAQUETA" ${ (entidad == 'CDA DEL CAQUETA') ? 'selected' : '' }>CDA DEL CAQUETA </option>
                <option value="BANCO CAJA  SOCIAL" ${ (entidad == 'BANCO CAJA  SOCIAL') ? 'selected' : '' }>BANCO CAJA  SOCIAL </option>
                <option value="CENTRO DE DIAGNOSTICO AUTOMOTRIZ C.D.A. LOS DUJOS LTDA." ${ (entidad == 'CENTRO DE DIAGNOSTICO AUTOMOTRIZ C.D.A. LOS DUJOS LTDA.') ? 'selected' : '' }>CENTRO DE DIAGNOSTICO AUTOMOTRIZ C.D.A. LOS DUJOS LTDA.</option>
                <option value="CDA CARLLANOS VILLAVICENCIO S.A.S" ${ (entidad == 'CDA CARLLANOS VILLAVICENCIO S.A.S') ? 'selected' : '' }>CDA CARLLANOS VILLAVICENCIO S.A.S</option>
                <option value="GARCIA & GARCIA CDA DE NEIVA S.A.S. - CDA DE NEIVA SAS" ${ (entidad == 'GARCIA & GARCIA CDA DE NEIVA S.A.S. - CDA DE NEIVA SAS') ? 'selected' : '' }>GARCIA & GARCIA CDA DE NEIVA S.A.S. - CDA DE NEIVA SAS</option>
                <option value="CENTRO DE DIAGNOSTICO AUTOMOTOR OPTIMO S.A.S" ${ (entidad == 'CENTRO DE DIAGNOSTICO AUTOMOTOR OPTIMO S.A.S') ? 'selected' : '' }>CENTRO DE DIAGNOSTICO AUTOMOTOR OPTIMO S.A.S</option>
                <option value="CENTRO NACIONAL DE DIAGNOSTICO AUTOMOTOR SEGURA LIMITADA" ${ (entidad == 'CENTRO NACIONAL DE DIAGNOSTICO AUTOMOTOR SEGURA LIMITADA') ? 'selected' : '' }>CENTRO NACIONAL DE DIAGNOSTICO AUTOMOTOR SEGURA LIMITADA</option>
                <option value="CENTRO DE DIAGNOSTICO AUTOMOTOR OPTIMO S.A.S" ${ (entidad == 'CENTRO DE DIAGNOSTICO AUTOMOTOR OPTIMO S.A.S') ? 'selected' : '' }>CENTRO DE DIAGNOSTICO AUTOMOTOR OPTIMO S.A.S</option>
                <option value="CDA SUPERCARS" ${ (entidad == 'CDA SUPERCARS') ? 'selected' : '' }>CDA SUPERCARS</option>
                <option value="C.D.A. MAXITEC S.A.S" ${ (entidad == 'C.D.A. MAXITEC S.A.S') ? 'selected' : '' }>C.D.A. MAXITEC S.A.S</option>
                <option value="CENTRO DE DIAGNOSTICO AUTOMOTOR LOS CENTAUROS S.A.S" ${ (entidad == 'CENTRO DE DIAGNOSTICO AUTOMOTOR LOS CENTAUROS S.A.S') ? 'selected' : '' }>CENTRO DE DIAGNOSTICO AUTOMOTOR LOS CENTAUROS S.A.S</option>
                <option value="CDA DEL PUTUMAYO E.U" ${ (entidad == 'CDA DEL PUTUMAYO E.U') ? 'selected' : '' }>CDA DEL PUTUMAYO E.U</option>
                <option value="C.D.A. REIMAR LTDA." ${ (entidad == 'C.D.A. REIMAR LTDA.') ? 'selected' : '' }>C.D.A. REIMAR LTDA.</option>
                <option value="NO APLICA" ${ (entidad == 'NO APLICA') ? 'selected' : '' }>NO APLICA </option>
                <option value="INVERSIONES FLOTA HUILA S.A" ${ (entidad == 'INVERSIONES FLOTA HUILA S.A') ? 'selected' : '' }>INVERSIONES FLOTA HUILA S.A</option>
                <option value="CENTRO DE DIAGNOSTICO AUTOMOTOR MAXITEC S.A.S." ${ (entidad == 'CENTRO DE DIAGNOSTICO AUTOMOTOR MAXITEC S.A.S.') ? 'selected' : '' }>CENTRO DE DIAGNOSTICO AUTOMOTOR MAXITEC S.A.S.</option>
            `;
            break;

        case 'Convenios colaboración empresarial (SIG-F-73)':
            $('#fecha_inicio_vigencia_div').removeClass('d-none')
            $('#fecha_fin_vigencia_div').removeClass('d-none')
            return `
                <option value=""></option>
                <option value="GRUPO EMPRESARIAL MONTAÑA S.A.S" ${ (entidad == 'GRUPO EMPRESARIAL MONTAÑA S.A.S') ? 'selected' : '' }>GRUPO EMPRESARIAL MONTAÑA S.A.S</option>
                <option value="TRANSVITUR S.A.S" ${ (entidad == 'TRANSVITUR S.A.S') ? 'selected' : '' }>TRANSVITUR S.A.S</option>
                <option value="EXPRESO LOS SAMANES S.A.S" ${ (entidad == 'EXPRESO LOS SAMANES S.A.S') ? 'selected' : '' }>EXPRESO LOS SAMANES S.A.S</option>
                <option value="MEGA TRANS S.A.S." ${ (entidad == 'MEGA TRANS S.A.S.') ? 'selected' : '' }>MEGA TRANS S.A.S.</option>
                <option value="INTERAMAZONICA LTDA" ${ (entidad == 'INTERAMAZONICA LTDA') ? 'selected' : '' }>INTERAMAZONICA LTDA </option>
                <option value="CARLOS CALLE EXPRESOS S.A.S" ${ (entidad == 'CARLOS CALLE EXPRESOS S.A.S') ? 'selected' : '' }>CARLOS CALLE EXPRESOS S.A.S</option>
                <option value="SERVITRANS DEL PUTUMAYO S.A.S" ${ (entidad == 'SERVITRANS DEL PUTUMAYO S.A.S') ? 'selected' : '' }>SERVITRANS DEL PUTUMAYO S.A.S</option>
                <option value="LOGISTICA Y SERVICIOS EMPRESARIALES S.A.S" ${ (entidad == 'LOGISTICA Y SERVICIOS EMPRESARIALES S.A.S') ? 'selected' : '' }>LOGISTICA Y SERVICIOS EMPRESARIALES S.A.S</option>
                <option value="SOTRANSVEGA SAS" ${ (entidad == 'SOTRANSVEGA SAS') ? 'selected' : '' }>SOTRANSVEGA SAS</option>
                <option value="TRANSCODOR SAS" ${ (entidad == 'TRANSCODOR SAS') ? 'selected' : '' }>TRANSCODOR SAS</option>
                <option value="TRANSSERVICIOS CJ S.A.S" ${ (entidad == 'TRANSSERVICIOS CJ S.A.S') ? 'selected' : '' }>TRANSSERVICIOS CJ S.A.S</option>
                <option value="TRANSPORTES MULTIMODAL GROUP S.A.S" ${ (entidad == 'TRANSPORTES MULTIMODAL GROUP S.A.S') ? 'selected' : '' }>TRANSPORTES MULTIMODAL GROUP S.A.S</option>
                <option value="ESTURIVANNS S.A.S" ${ (entidad == 'ESTURIVANNS S.A.S') ? 'selected' : '' }>ESTURIVANNS S.A.S</option>
                <option value="TRANSPORTES ESPECIALES GOLDEN" ${ (entidad == 'TRANSPORTES ESPECIALES GOLDEN') ? 'selected' : '' }>TRANSPORTES ESPECIALES GOLDEN</option>
            `;
            break;

        case 'Contrarto civil de prestación de servicios de transporte (SIG-F-49)':
            $('#fecha_inicio_vigencia_div').removeClass('d-none')
            $('#fecha_fin_vigencia_div').removeClass('d-none')
            return `
                <option value=""></option>
                <option value="TECNOSUR LOCALIZACIÓN Y RASTREO" ${ (entidad == 'TECNOSUR LOCALIZACIÓN Y RASTREO') ? 'selected' : '' }>TECNOSUR LOCALIZACIÓN Y RASTREO </option>
                <option value="BANCO CAJA  SOCIAL" ${ (entidad == 'BANCO CAJA  SOCIAL') ? 'selected' : '' }>BANCO CAJA  SOCIAL </option>
                <option value="AMAZONIA CONSULTORIA & LOGISTICA SAS" ${ (entidad == 'AMAZONIA CONSULTORIA & LOGISTICA SAS') ? 'selected' : '' }>AMAZONIA CONSULTORIA & LOGISTICA SAS</option>
                <option value="MASSER S.A.S" ${ (entidad == 'MASSER S.A.S') ? 'selected' : '' }>MASSER S.A.S</option>
            `;
            break;

        case 'Ultima inspección mensual (SIG-F-89)':
            $('#fecha_inicio_vigencia_div').removeClass('d-none')
            $('#fecha_fin_vigencia_div').removeClass('d-none')
            return `
                <option value=""></option>
                <option value="AMAZONIA CONSULTORIA & LOGISTICA SAS" ${ (entidad == 'AMAZONIA CONSULTORIA & LOGISTICA SAS') ? 'selected' : '' }>AMAZONIA CONSULTORIA & LOGISTICA SAS</option>
                <option value="BANCO CAJA  SOCIAL" ${ (entidad == 'BANCO CAJA  SOCIAL') ? 'selected' : '' }>BANCO CAJA  SOCIAL </option>
            `;
            break;

        case 'Ultima acta entrega y/o recibido (SIG-F-47)':
            $('#fecha_inicio_vigencia_div').removeClass('d-none')
            $('#fecha_fin_vigencia_div').removeClass('d-none')
            return `
                <option value=""></option>
                <option value="AMAZONIA CONSULTORIA & LOGISTICA SAS" ${ (entidad == 'AMAZONIA CONSULTORIA & LOGISTICA SAS') ? 'selected' : '' }>AMAZONIA CONSULTORIA & LOGISTICA SAS</option>
                <option value="BANCO CAJA  SOCIAL" ${ (entidad == 'BANCO CAJA  SOCIAL') ? 'selected' : '' }>BANCO CAJA  SOCIAL </option>
            `;
            break;

        case 'Ultima bimestarl CDA':
            $('#fecha_inicio_vigencia_div').removeClass('d-none')
            $('#fecha_fin_vigencia_div').removeClass('d-none')
            return `
                <option value=""></option>
                <option value="CENTRO DE DIAGNOSTICO AUTOMOTOR MAXITEC S.A.S." ${ (entidad == 'CENTRO DE DIAGNOSTICO AUTOMOTOR MAXITEC S.A.S.') ? 'selected' : '' }>CENTRO DE DIAGNOSTICO AUTOMOTOR MAXITEC S.A.S.</option>
                <option value="INVERSIONES FLOTA HUILA S.A" ${ (entidad == 'INVERSIONES FLOTA HUILA S.A') ? 'selected' : '' }>INVERSIONES FLOTA HUILA S.A</option>
                <option value="C.D.A. REIMAR LTDA." ${ (entidad == 'C.D.A. REIMAR LTDA.') ? 'selected' : '' }>C.D.A. REIMAR LTDA.</option>
                <option value="CDA DEL PUTUMAYO E.U" ${ (entidad == 'CDA DEL PUTUMAYO E.U') ? 'selected' : '' }>CDA DEL PUTUMAYO E.U</option>
                <option value="CENTRO DE DIAGNOSTICO AUTOMOTOR LOS CENTAUROS S.A.S" ${ (entidad == 'CENTRO DE DIAGNOSTICO AUTOMOTOR LOS CENTAUROS S.A.S') ? 'selected' : '' }>CENTRO DE DIAGNOSTICO AUTOMOTOR LOS CENTAUROS S.A.S</option>
                <option value="C.D.A. MAXITEC S.A.S" ${ (entidad == 'C.D.A. MAXITEC S.A.S') ? 'selected' : '' }>C.D.A. MAXITEC S.A.S</option>
                <option value="CENTRO DE DIAGNOSTICO AUTOMOTOR OPTIMO S.A.S" ${ (entidad == 'CENTRO DE DIAGNOSTICO AUTOMOTOR OPTIMO S.A.S') ? 'selected' : '' }>CENTRO DE DIAGNOSTICO AUTOMOTOR OPTIMO S.A.S</option>
                <option value="CDA SUPERCARS" ${ (entidad == 'CDA SUPERCARS') ? 'selected' : '' }>CDA SUPERCARS</option>
                <option value="CENTRO NACIONAL DE DIAGNOSTICO AUTOMOTOR SEGURA LIMITADA" ${ (entidad == 'CENTRO NACIONAL DE DIAGNOSTICO AUTOMOTOR SEGURA LIMITADA') ? 'selected' : '' }>CENTRO NACIONAL DE DIAGNOSTICO AUTOMOTOR SEGURA LIMITADA</option>
                <option value="CENTRO DE DIAGNOSTICO AUTOMOTOR OPTIMO S.A.S" ${ (entidad == 'CENTRO DE DIAGNOSTICO AUTOMOTOR OPTIMO S.A.S') ? 'selected' : '' }>CENTRO DE DIAGNOSTICO AUTOMOTOR OPTIMO S.A.S</option>
                <option value="GARCIA & GARCIA CDA DE NEIVA S.A.S. - CDA DE NEIVA SAS" ${ (entidad == 'GARCIA & GARCIA CDA DE NEIVA S.A.S. - CDA DE NEIVA SAS') ? 'selected' : '' }>GARCIA & GARCIA CDA DE NEIVA S.A.S. - CDA DE NEIVA SAS</option>
                <option value="CENTRO DE DIAGNOSTICO AUTOMOTRIZ C.D.A. LOS DUJOS LTDA." ${ (entidad == 'CENTRO DE DIAGNOSTICO AUTOMOTRIZ C.D.A. LOS DUJOS LTDA.') ? 'selected' : '' }>CENTRO DE DIAGNOSTICO AUTOMOTRIZ C.D.A. LOS DUJOS LTDA.</option>
                <option value="CDA CARLLANOS VILLAVICENCIO S.A.S" ${ (entidad == 'CDA CARLLANOS VILLAVICENCIO S.A.S') ? 'selected' : '' }>CDA CARLLANOS VILLAVICENCIO S.A.S</option>
                <option value="CDA DEL CAQUETA" ${ (entidad == 'CDA DEL CAQUETA') ? 'selected' : '' }>CDA DEL CAQUETA </option>
                <option value="CDA DE NEIVA S.A.S" ${ (entidad == 'CDA DE NEIVA S.A.S') ? 'selected' : '' }>CDA DE NEIVA S.A.S</option>
                <option value="NO APLICA" ${ (entidad == 'NO APLICA') ? 'selected' : '' }>NO APLICA </option>
            `;
            break;

        case 'Ultimo soporte de mantenimiento':
            $('#fecha_inicio_vigencia_div').removeClass('d-none')
            $('#fecha_fin_vigencia_div').removeClass('d-none')
            return `
                <option value=""></option>
                <option value="AMAZONIA CONSULTORIA & LOGISTICA SAS" ${ (entidad == 'AMAZONIA CONSULTORIA & LOGISTICA SAS') ? 'selected' : '' }>AMAZONIA CONSULTORIA & LOGISTICA SAS</option>
                <option value="BANCO CAJA  SOCIAL" ${ (entidad == 'BANCO CAJA  SOCIAL') ? 'selected' : '' }>BANCO CAJA  SOCIAL </option>
            `;
            break;

    }
}

function select_tipo_vinculacion(tipo) {
    console.log(tipo);
    if (tipo == 1) {
        $('#div_empresa_convenio').removeClass('d-none');
        $('#div_item1').removeClass('col-sm-4').addClass('col-sm-3');
        $('#div_item2').removeClass('col-sm-4').addClass('col-sm-3');
        $('#div_item3').removeClass('col-sm-4').addClass('col-sm-3');
    } else {
        $('#div_empresa_convenio').addClass('d-none');
        $('#div_item1').removeClass('col-sm-3').addClass('col-sm-4');
        $('#div_item2').removeClass('col-sm-3').addClass('col-sm-4');
        $('#div_item3').removeClass('col-sm-3').addClass('col-sm-4');
    }
}
