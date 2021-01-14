
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">Menu</li>

                <li class="mb-3">
                    <a href="/" class="waves-effect">
                        <div class="d-inline-block icons-sm mr-1 uim-icon-primary"><i class="uim uim-airplay"></i></div>
                        <span class="text-dark">Escritorio</span>
                    </a>
                </li>

                @if ( Request::is('/') || Request::is('informacion/covid') )

                    <li class="menu-title">Modulos</li>

                    @canany(['terceros', 'universal'])
                        <li>
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <div class="d-inline-block icons-sm mr-1"><i class="uim fas fa-users mx-1"></i></div>
                                <span>Terceros</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="/terceros">Datos Terceros</a></li>
                                <li><a href="/terceros/correspondencia">Correspondencia</a></li>
                            </ul>
                        </li>
                    @endcanany

                    @canany(['vehiculos', 'universal'])
                        <li>
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <div class="d-inline-block icons-sm mr-1"><i class="uim uim-window-grid"></i></div>
                                <span>Vehiculos</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="/vehiculos/">Vehiculos</a></li>
                                <li><a href="/vehiculos/tanqueos">Tanqueos</a></li>
                                <li><a href="/vehiculos/inspecciones">Inspecciones</a></li>
                                <li><a href="/vehiculos/graficas">Graficas Tanqueos</a></li>
                                <li><a href="/vehiculos/mantenimientos">Mantenimientos</a></li>
                            </ul>
                        </li>
                    @endcanany

                    @canany(['contabilidad', 'universal'])
                        <li>
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <div class="d-inline-block icons-sm mr-1"><i class="uim uim-graph-bar"></i></div>
                                <span>Contabilidad</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="/contabilidad">Contabilidad</a></li>
                                <li><a href="/solicitud-dinero">Solicitud de dinero</a></li>
                            </ul>
                        </li>
                    @endcanany

                    @canany(['personal', 'universal'])
                        <li>
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <div class="d-inline-block icons-sm mr-1"><i class="uim fas fa-user-friends mx-1"></i> </i></div>
                                <span> Personal</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="personal/datos-personal">Personal</a></li>
                                <li><a href="personal/alumnos">Alumnos</a></li>
                            </ul>
                        </li>
                    @endcanany

                    @canany(['correos', 'universal'])
                        <li>
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <div class="d-inline-block icons-sm mr-1"><i class="uim uim-comment-alt-message"></i></div>
                                <span class="badge badge-pill badge-primary float-right">{{ $correos ?? '' }}</span>
                                <span>Correos</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="correos/nuevos">Nuevos</a></li>
                                <li><a href="correos/respondidos">Respondidos</a></li>
                            </ul>
                        </li>
                    @endcanany

                    @canany(['cotizaciones', 'universal'])
                        <li>
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <div class="d-inline-block icons-sm mr-1"><i class="uim uim-window-grid"></i></div>
                                <span class="badge badge-pill badge-primary float-right">{{ $cotizaciones ?? '' }}</span>
                                <span>Cotizaciones</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="cotizaciones/nuevas">Nuevas</a></li>
                                <li><a href="cotizaciones/aceptadas">Aceptadas</a></li>
                                <li><a href="cotizaciones/respondidas">Respondidas</a></li>
                            </ul>
                        </li>
                    @endcanany

                    @canany(['hseq', 'universal'])
                        <li>
                            <a href="hseq/list" class="waves-effect">
                                <div class="d-inline-block icons-sm mr-1"><i class="uim fa fa-shield-alt mx-1"></i></div>
                                <span>HSEQ</span>
                            </a>
                        </li>
                    @endcanany

                    @canany(['control ingreso', 'universal'])
                        <li>
                            <a href="control_ingreso/funcionarios" class="waves-effect">
                                <div class="d-inline-block icons-sm mr-1"><i class="uim uim-exit"></i></div>
                                <span>Control de Ingreso</span>
                            </a>
                        </li>
                    @endcanany

                    @canany(['tareas', 'universal'])
                        <li>
                            <a href="tareas" class="waves-effect">
                                <div class="d-inline-block icons-sm mr-1"><i class="uim fas fa-business-time mx-1"></i></div>
                                <span>Tareas</span>
                            </a>
                        </li>
                    @endcanany

                    @canany(['blog', 'universal'])
                        <li>
                            <a href="blog" class="waves-effect">
                                <div class="d-inline-block icons-sm mr-1"><i class="uim uim-document-layout-left"></i></div>
                                <span>Blog</span>
                            </a>
                        </li>
                    @endcanany

                    @canany(['universal'])

                        <li class="menu-title">Administrador</li>

                        <li>
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <div class="d-inline-block icons-sm mr-1"><i class="uim uim-window-grid"></i></div>
                                <span>Administrar Sistema</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="admin/sistema/vehiculos">Datos Vehiculos</a></li>
                            </ul>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="admin/sistema/cargos">Administrar Cargos</a></li>
                            </ul>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="admin/sistema/inspecciones">Administrar Inspecciones</a></li>
                            </ul>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="{{route('proveedores')}}">Administrar Proveedores</a></li>
                            </ul>
                        </li>

                        <li>
                            <a href="admin/users" class="waves-effect">
                                <div class="d-inline-block icons-sm mr-1"><i class="uim uim-object-group"></i></div>
                                <span>Usuarios</span>
                            </a>
                        </li>

                    @endcanany

                    <li class="menu-title">Información</li>

                    <li>
                        <a href="/informacion/documentacion/" class="waves-effect">
                            <div class="d-inline-block icons-sm mr-1"><i class="uim uim-bag"></i></div>
                            <span>Documentación</span>
                        </a>
                    </li>

                    <li>
                        <a href="/informacion/covid/" class="waves-effect">
                            <div class="d-inline-block icons-sm mr-1"><i class="uim uim-exclamation-triangle"></i></div>
                            <span>COVID - 19</span>
                        </a>
                    </li>

                @endif

                @if ( Request::is('admin/users') || Request::is('admin/users/*') )

                    <li class="menu-title">Usuarios</li>

                    <li>
                        <a href="/admin/users" class="waves-effect">
                            <div class="d-inline-block icons-sm"></div>
                            <span>Lista de Usuarios</span>
                        </a>
                    </li>

                    <li>
                        <a href="#" data-toggle="modal" data-target="#modal-create-user" class="waves-effect">
                            <div class="d-inline-block icons-sm"></div>
                            <span>Crear</span>
                        </a>
                    </li>

                @endif

                @if ( Request::is('admin/sistema') || Request::is('admin/sistema/*') )

                    <li class="menu-title">Administrar Sistema</li>

                    <li>
                        <a href="/admin/sistema/vehiculos" class="waves-effect">
                            <div class="d-inline-block icons-sm"></div>
                            <span>Datos Vehiculos</span>
                        </a>
                    </li>

                    <li>
                        <a href="/admin/sistema/cargos" class="waves-effect">
                            <div class="d-inline-block icons-sm"></div>
                            <span>Administrar Cargos</span>
                        </a>
                    </li>

                    <li>
                        <a href="/admin/sistema/inspecciones" class="waves-effect">
                            <div class="d-inline-block icons-sm"></div>
                            <span>Administrar Inspecciones</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{route('proveedores')}}" class="waves-effect">
                            <div class="d-inline-block icons-sm"></div>
                            <span>Administrar Proveedores</span>
                        </a>
                    </li>

                @endif

                @if ( Request::is('correos/*') )

                    <li class="menu-title">Correos</li>

                    <li>
                        <a href="/correos/nuevos" class="waves-effect">
                            <div class="d-inline-block icons-sm"></div>
                            <span>Nuevos</span>
                        </a>
                    </li>

                    <li>
                        <a href="/correos/respondidos" class="waves-effect">
                            <div class="d-inline-block icons-sm"></div>
                            <span>Respondidos</span>
                        </a>
                    </li>

                @endif

                @if ( Request::is('cotizaciones/*') )

                    <li class="menu-title">Cotizaciones</li>

                    <li>
                        <a href="/cotizaciones/nuevas" class="waves-effect">
                            <div class="d-inline-block icons-sm"></div>
                            <span class="badge badge-pill badge-primary float-right"></span>
                            <span>Nuevas</span>
                        </a>
                    </li>

                    <li>
                        <a href="/cotizaciones/aceptadas" class="waves-effect">
                            <div class="d-inline-block icons-sm"></div>
                            <span class="badge badge-pill badge-primary float-right"></span>
                            <span>Aceptadas</span>
                        </a>
                    </li>

                    <li>
                        <a href="/cotizaciones/respondidas" class="waves-effect">
                            <div class="d-inline-block icons-sm"></div>
                            <span class="badge badge-pill badge-primary float-right"></span>
                            <span>Respondidas</span>
                        </a>
                    </li>

                    <li class="menu-title mt-3">Contratos Realizados</li>

                    <li>
                        <a href="/cotizaciones/contratos" class="waves-effect">
                            <div class="d-inline-block icons-sm"></div>
                            <span class="badge badge-pill badge-primary float-right"></span>
                            <span>Contratos</span>
                        </a>
                    </li>

                @endif

                @if ( Request::is('control_ingreso/*') )

                    <li class="menu-title">Control de Ingreso</li>

                    <li>
                        <a href="/control_ingreso/funcionarios" class="waves-effect">
                            <div class="d-inline-block icons-sm"></div>
                            <span>Funcionarios</span>
                        </a>
                    </li>

                    <li>
                        <a href="/control_ingreso/clientes" class="waves-effect">
                            <div class="d-inline-block icons-sm"></div>
                            <span>Clientes</span>
                        </a>
                    </li>

                @endif

                @if ( Request::is('vehiculos') || Request::is('vehiculos/*') )

                    <li class="menu-title">Vehiculos</li>

                    <li><a href="/vehiculos/"  class="waves-effect">Vehiculos</a></li>
                    @role('admin') <li><a href="/vehiculos/tanqueos"  class="waves-effect">Tanqueos</a></li> @endrole
                    @role('admin') <li><a href="/vehiculos/inspecciones"  class="waves-effect">Inspecciones</a></li> @endrole
                    @role('admin') <li><a href="/vehiculos/graficas"  class="waves-effect">Graficas Tanqueos</a></li> @endrole
                    @role('admin') <li><a href="/vehiculos/mantenimientos"  class="waves-effect">Mantenimientos</a></li> @endrole

                @endif

                @if ( Request::is('personal') || Request::is('personal/*') )

                    <li class="menu-title">Administrar Sistema</li>

                    <li>
                        <a href="/personal/datos-personal" class="waves-effect">
                            <div class="d-inline-block icons-sm"></div>
                            <span>Personal</span>
                        </a>
                    </li>

                    <li>
                        <a href="/personal/alumnos" class="waves-effect">
                            <div class="d-inline-block icons-sm"></div>
                            <span>Alumnos</span>
                        </a>
                    </li>

                @endif

                @if ( Request::is('terceros') || Request::is('terceros/*') )

                    <li class="menu-title">Terceros</li>

                    <li><a href="/terceros"  class="waves-effect">Datos Terceros</a></li>
                    <li><a href="/terceros/correspondencia"  class="waves-effect">Correspondencia</a></li>

                @endif

                @if ( Request::is('tareas') || Request::is('tareas/*') )

                    <li class="menu-title">Tareas</li>

                    <li><a href="/tareas"  class="waves-effect">Pendientes</a></li>
                    <li><a href="/tareas/asignadas"  class="waves-effect">Asignadas</a></li>
                    <li><a href="/tareas/completadas"  class="waves-effect">Completadas</a></li>

                @endif

                @if ( Request::is('hseq') || Request::is('hseq/*') )

                    <li class="menu-title">HSEQ</li>

                    <li><a href="/"  class="waves-effect">Volver</a></li>

                @endif

                @if ( Request::is('informacion/documentacion') || Request::is('informacion/documentacion/*') )

                    <li class="menu-title">Documentación</li>

                    <li><a href="/" class="waves-effect">Atras</a></li>
                    <li><a href="#" data-toggle="modal" data-target="#modal_crear_modulo" class="waves-effect">Agregar Modulo</a></li>
                    <li><a href="javascript:exportar_documentos()" class="waves-effect">Exportar Documentación</a></li>

                @endif

            </ul>

        </div>
        <!-- Sidebar -->
    </div>
</div>
