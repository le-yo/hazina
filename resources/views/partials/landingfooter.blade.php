    <!-- JavaScript -->
    <script src="flatfy/js/jquery-1.10.2.js"></script>
    <script src="flatfy/js/bootstrap.js"></script>
    <script src="flatfy/js/owl.carousel.js"></script>
    <script src="flatfy/js/script.js"></script>
    <!-- StikyMenu -->
    <script src="flatfy/js/stickUp.min.js"></script>
    <script type="text/javascript">
        jQuery(function($) {
            $(document).ready( function() {
                $('.navbar-default').stickUp();

            });
        });

    </script>
    <!-- Smoothscroll -->
    <script type="text/javascript" src="flatfy/js/jquery.corner.js"></script>
    <script src="flatfy/js/wow.min.js"></script>
    <script>
        new WOW().init();
    </script>
    <script src="flatfy/js/classie.js"></script>
    <script src="flatfy/js/uiMorphingButton_inflow.js"></script>
    <!-- Magnific Popup core JS file -->
    <script src="flatfy/js/jquery.magnific-popup.js"></script>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="{{ asset('plugins/jquery/jquery-2.1.3.min.js') }}"></script>
    <!-- Datatables -->
    <script src="{{ asset('plugins/jquery.dataTables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery.dataTables/dataTables.bootstrap.min.js') }}"></script>
    <!-- Latest compiled and minified Bootstrap JavaScript -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.min.js') }}"></script>
    <!-- Restfulizer.js - A tool for simulating put,patch and delete requests -->
    <script src="{{ asset('js/restfulizer.js') }}"></script>
    <!-- Custom Javascript -->
    <script src="{{ asset('js/custom-scripts.js') }}"></script>
    @stack('scripts')