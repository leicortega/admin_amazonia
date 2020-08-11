<script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/libs/metismenu/metisMenu.min.js') }}"></script>
<script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>

<script src="https://unicons.iconscout.com/release/v2.0.1/script/monochrome/bundle.js"></script>

<!-- datepicker -->
<script src="{{ asset('assets/libs/air-datepicker/js/datepicker.min.js') }}"></script>
<script src="{{ asset('assets/libs/air-datepicker/js/i18n/datepicker.en.js') }}"></script>

<!-- apexcharts -->
{{-- <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script> --}}

<script src="{{ asset('assets/libs/jquery-knob/jquery.knob.min.js') }}"></script> 

<!-- Jq vector map -->
<script src="{{ asset('assets/libs/jqvmap/jquery.vmap.min.js') }}"></script>
<script src="{{ asset('assets/libs/jqvmap/maps/jquery.vmap.usa.js') }}"></script>

@yield('Plugins')

{{-- <script src="{{ asset('assets/js/pages/dashboard.init.js') }}"></script> --}}

<script src="{{ asset('assets/js/app.js') }}"></script>
<script src="{{ asset('assets/js/peticiones.js') }}"></script>

@yield('jsMain')