
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">Menu</li>

                <li class="mb-3">
                    <a href="/" class="waves-effect">
                        <div class="d-inline-block icons-sm mr-1"><i class="uim uim-airplay"></i></div>
                        <span>Dashboard</span>
                    </a>
                </li>

                @if ( Request::is('/') )

                    <li class="menu-title">Modulos</li>

                    @canany(['correos', 'universal'])
                        <li>
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <div class="d-inline-block icons-sm mr-1"><i class="uim uim-comment-alt-message"></i></div>
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
                                <span>Cotizaciones</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="cotizaciones/nuevas">Nuevas</a></li>
                                <li><a href="cotizaciones/respondidas">Respondidas</a></li>
                            </ul>
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

                    @canany(['universal'])

                        <li class="menu-title">Administrador</li>

                        <li>
                            <a href="admin/users" class="waves-effect">
                                <div class="d-inline-block icons-sm mr-1"><i class="uim uim-object-group"></i></div>
                                <span>Usuarios</span>
                            </a>
                        </li>

                    @endcanany

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
                            <span>Nuevas</span>
                        </a>
                    </li>

                    <li>
                        <a href="/cotizaciones/respondidas" class="waves-effect">
                            <div class="d-inline-block icons-sm"></div>
                            <span>Respondidas</span>
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

            </ul>

        </div>
        <!-- Sidebar -->
    </div>
</div>