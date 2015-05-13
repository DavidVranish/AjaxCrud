<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">
	
	<meta name="csrf-token" content="{{ csrf_token() }}" />
	
	<link rel="shortcut icon" href="{{ asset('/images/gfg-salsa.png') }}">

	<title>{{ Config::get('app.name') }}</title>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,400italic,700,800' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Raleway:300,200,100' rel='stylesheet' type='text/css'>

    <!-- Bootstrap -->
    <link href="{{ asset('/bower_components/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/bower_components/gritter/css/jquery.gritter.css') }}" rel="stylesheet">
    
    <!-- FA -->
    <link href="{{ asset('/fonts/font-awesome-4/css/font-awesome.min.css') }}" rel="stylesheet">
    
    <!-- Theme -->
    <link href="{{ asset('/css/dashboard.css') }}" rel="stylesheet">
    
    <!-- DataTables -->
    <link href="{{ asset('/js/jquery.datatables-bootstrap/bootstrap-adapter/css/datatables.css') }}" rel="stylesheet">
    
    <!-- Helper scripts -->
    <script src="{{ asset('/js/holder.js') }}"></script>
    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <link href="{{ asset('/bower_components/nanoscroller/bin/css/nanoscroller.css') }}" rel="stylesheet">
    <link href="{{ asset('/bower_components/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.css') }}" rel="stylesheet">
    <link href="{{ asset('/bower_components/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/bower_components/bootstrap-datepicker/css/datepicker3.css') }}" rel="stylesheet">
    <link href="{{ asset('/bower_components/select2/select2.css') }}" rel="stylesheet">
    <link href="{{ asset('/bower_components/bootstrap.multiselect/dist/css/bootstrap-multiselect.css') }}" rel="stylesheet">
    <link href="{{ asset('/bower_components/jquery.multi-select/css/multi-select.css') }}" rel="stylesheet">
    <link href="{{ asset('/bower_components/bootstrap-daterangepicker/daterangepicker-bs3.css') }}" rel="stylesheet">
    <link href="{{ asset('/bower_components/jquery-timepicker/jquery.timepicker.css') }}" rel="stylesheet">
    <link href="{{ asset('/bower_components/iCheck/skins/square/blue.css') }}" rel="stylesheet">
    <link href="{{ asset('/bower_components/jquery-niftymodals/css/component.css') }}" rel="stylesheet">
    <link href="{{ asset('/bower_components/x-editable/dist/bootstrap3-editable/css/bootstrap-editable.css') }}" rel="stylesheet">
    <link href="{{ asset('js/validation/formValidation.min.css') }}" rel="stylesheet">
    <link href="{{ asset('js/jquery.fullcalendar/fullcalendar/fullcalendar.css') }}" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="{{ asset('/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/custom.css') }}" rel="stylesheet">

    @yield('styles')

</head>

<body>

@include('partials.navbarTop')

@yield('leftNavbarCollapseOpen')

@include('partials.navbarLeft')

@yield ('submenu')

<div class="container-fluid" id="pcont">

<div class="page-head"> <!-- open page-head to include session messages-->
@include('partials.sessionMessage')
@yield('title')
</div> <!-- close page-head-->

@yield('content')

</div> <!-- close container -->

@yield('leftNavbarCollapseClose')

@include('layouts.delete_modal')

	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="{{ asset('/bower_components/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('/bower_components/moment/moment.js') }}"></script>
    <!-- Javascript included with theme -->
    <script src="{{ asset('/bower_components/parsleyjs/dist/parsley.min.js') }}"></script>
    {{--<script src="{{ asset('/bower_components/nanoscroller/bin/javascripts/jquery.nanoscroller.js') }}"></script>--}}
    <script src="{{ asset('/bower_components/jquery.easy-pie-chart/dist/jquery.easypiechart.js') }}"></script>
    <script src="{{ asset('/js/behaviour/general.js') }}"></script>
    <script src="{{ asset('/bower_components/jquery-ui/jquery-ui.js') }}"></script>
    <script src="{{ asset('/bower_components/nestable/jquery.nestable.js') }}"></script>
    <script src="{{ asset('/bower_components/bootstrap-switch/dist/js/bootstrap-switch.min.js') }}"></script>
    <script src="{{ asset('/bower_components/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('/bower_components/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('/bower_components/select2/select2.min.js') }}"></script>
    <script src="{{ asset('/bower_components/bootstrap-slider/bootstrap-slider.js') }}"></script>
    <script src="{{ asset('/bower_components/gritter/js/jquery.gritter.min.js') }}"></script>
    
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="{{ asset('/bower_components/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/js/jquery.datatables-bootstrap/bootstrap-adapter/js/datatables.js') }}"></script>
    <script src="{{ asset('/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('/bower_components/bootstrap.multiselect/dist/js/bootstrap-multiselect.js') }}"></script>
	<script src="{{ asset('/bower_components/jquery.multi-select/js/jquery.multi-select.js') }}"></script>
	<script src="{{ asset('/bower_components/jquery-timepicker/jquery.timepicker.min.js') }}"></script>
	<script src="{{ asset('/bower_components/jquery.maskedinput/dist/jquery.maskedinput.min.js') }}"></script>
	<script src="{{ asset('/bower_components/iCheck/icheck.min.js') }}"></script>
	<script src="{{ asset('js/jquery.fullcalendar/fullcalendar/fullcalendar.js') }}"></script>
    <script src="{{ asset('/bower_components/blockui/jquery.blockUI.js') }}"></script>


    
   
    <script type="text/javascript">
        var urlDataTableUpdateLengthPost = "{{ route('data_table.update_length.post') }}";

        $(document).ready(function() {
            //initialize the javascript
            App.init();
            
        });
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

      	//reset modals for multiple data uploads
    	$(document).on('hidden.bs.modal', function (e) {
    		$(e.target).removeData('bs.modal');
    	});

    	// Fades alert out
    	window.setTimeout(function() {
    			$(".removeable").fadeTo(1500, 0).slideUp(500, function() {
    					$(this).remove();
    			});
    	}, 2500);
    </script>
    
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="{{ asset('/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('/bower_components/x-editable/dist/bootstrap3-editable/js/bootstrap-editable.min.js') }}"></script>
    <script src="{{ asset('/bower_components/flot/jquery.flot.js') }}"></script>
    <script src="{{ asset('/bower_components/flot/jquery.flot.pie.js') }}"></script>
    <script src="{{ asset('/bower_components/flot/jquery.flot.resize.js') }}"></script>
    <script src="{{ asset('/bower_components/flot/jquery.flot.time.js') }}"></script>
    <script src="{{ asset('/js/validation/formValidation.min.js') }}"></script>
    <script src="{{ asset('/js/validation/bootstrap.min.js') }}"></script>
    <script src="{{ asset('/bower_components/jquery-niftymodals/js/jquery.modalEffects.js') }}"></script>
    
    @yield('scripts')
    
    <!-- Include custom JS, if the file exists -->
    @if (file_exists('js/views/'.(Request::segment(1)?:'index').'/'.(Request::segment(2)?:'index').'.js'))
    <!-- Attempting to find: {{ 'views/'.(Request::segment(1)?:'index').'/'.(Request::segment(2)?:'index').'.js' }} -->
    <script src="{{ asset('/js/views/'.(Request::segment(1)?:'index').'/'.(Request::segment(2)?:'index').'.js') }}"></script>
    @elseif (file_exists('js/views/'.Request::segment(1).'.js'))
    <!-- Attempting to find: {{ 'views/'.Request::segment(1).'.js' }} -->
    <script src="{{ asset('/js/views/'.Request::segment(1).'.js') }}"></script>
    @endif
    
     <!-- Include custom JS files -->
    <script src="{{ asset('/js/views/global.js') }}"></script>
</body>
</html>
