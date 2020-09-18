$(document).ready(function () {
    $('#form_agg_conductor').submit(function () {
        $.ajax({
            url: '/vehiculos/agg_conductor',
            type: 'POST',
            data: $('#form_agg_conductor').serialize(),
            success: function (data) {
                cargar_conductores(data)
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
                cargar_tarjeta_propiedad(data.tipo, data.vehiculo_id);
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
                fecha = new Date(conductor.created_at)
                content += `
                <tr>
                    <td scope="row">${ indice+1 }</td>
                    <td>${ conductor.personal.nombres } ${ conductor.personal.primer_apellido } ${ conductor.personal.segundo_apellido ?? '' }</td>
                    <td>${ fecha.getDate() }/${ parseInt(fecha.getMonth()) + 1 }/${ fecha.getFullYear() }</td>
                    <td>${ fecha.getDate() }/${ parseInt(fecha.getMonth()) + 1 }/${ fecha.getFullYear() }</td>
                    <td>${ conductor.personal.estado }</td>
                    <td class="text-center"><button type="button" onclick="eliminar_conductor(${ conductor.id }, ${ conductor.vehiculo_id })" class="btn btn-danger waves-effect waves-light"><i class="fa fa-trash"></i></button></td>
                </tr>
                `;
            });
            $('#content_table_conductores').html(content)
        }
    })
}

function eliminar_conductor(id, vehiculo_id) {
    $.ajax({
        url: '/vehiculos/eliminar_conductor',
        type: 'POST',
        data: {id:id, vehiculo_id:vehiculo_id},
        success: function (data) {
            cargar_conductores(data)
        }
    })
}

function agg_documento_legal(tipo_documento) {
    $('#agg_doc_legal').modal('show')
    $('#agg_doc_legal_title').text('Agregar '+tipo_documento)
    $('#consecutivo_title').text('Consecutivo '+tipo_documento)
    $('#tipo').val(tipo_documento)

    $('#consecutivo').val('');
    $('#fecha_expedicion').val('');
    $('#fecha_inicio_vigencia').val('');
    $('#fecha_fin_vigencia').val('');
    $('#id').val('');
    $('#estado').val('');

    $('#entidad_expide').html(call_method_select_entidad(tipo_documento))
}

function cargar_tarjeta_propiedad(tipo, vehiculo_id) {
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
                    <td>${ documento.fecha_expedicion }</td>
                    <td>${ documento.fecha_fin_vigencia ?? 'No aplica' }</td>
                    <td>${ documento.fecha_inicio_vigencia ?? 'No aplica' }</td>
                    <td>${ (documento.fecha_inicio_vigencia) ? documento.fecha_fin_vigencia - documento.fecha_inicio_vigencia : 'No aplica' }</td>
                    <td width="250px">${ documento.entidad_expide }</td>
                    <td>${ documento.estado }</td>
                    <td width="180px" class="text-center">
                        <button type="button" onclick="editar_documento_legal(${ documento.id }, ${ documento.vehiculo_id }, '${ documento.tipo }')" class="btn btn-sm btn-info waves-effect waves-light"><i class="fa fa-edit"></i></button>
                        <button type="button" onclick="ver_documento_legal('${ documento.documento_file }', '${ documento.tipo }')" ${ (documento.documento_file) ? '' : 'disabled' } class="btn btn-sm btn-success waves-effect waves-light"><i class="fa fa-eye"></i></button>
                        <a href="/storage/${ documento.documento_file }" download class="${ (documento.documento_file) ? '' : 'disabled' } btn btn-sm btn-primary waves-effect waves-light"><i class="fa fa-download"></i></a>
                        <button type="button" onclick="eliminar_documento_legal(${ documento.id }, ${ documento.vehiculo_id }, '${ documento.tipo }')" class="btn btn-sm btn-danger waves-effect waves-light"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>
                `;
            });
            switch (tipo) {
                case 'Tarjeta de Propiedad':
                    $('#content_table_documentos_legales').html(content)
                    break;
        
                case 'Tarjeta Operación':
                    $('#content_table_tarjeta_operacion').html(content)
                    break;
        
                case 'SOAT':
                    $('#content_table_soat').html(content)
                    break;
                    
                case 'Técnico Mecánica':
                    $('#content_table_tecnico_mecanica').html(content)
                    break;
        
                case 'Seguro Todo Riesgo':
                    $('#content_table_seguro').html(content)
                    break;
        
                case 'Certificado GPS':
                    $('#content_table_gps').html(content)
                    break;
        
                case 'RUNT':
                    $('#content_table_runt').html(content)
                    break;
        
                case 'Póliza contractual':
                    $('#content_table_contractual').html(content)
                    break;
        
                case 'Póliza extracontractual':
                    $('#content_table_extracontractual').html(content)
                    break;
        
            }
        }
    })
}

function eliminar_documento_legal(id, vehiculo_id, tipo) {
    $.ajax({
        url: '/vehiculos/eliminar_documento_legal',
        type: 'POST',
        data: {id:id, vehiculo_id:vehiculo_id, tipo:tipo},
        success: function (data) {
            cargar_tarjeta_propiedad(data.tipo, data.vehiculo_id)
        }
    })
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
    
    }
}


                
                