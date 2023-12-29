<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    $(document).ready(function () {
      $('#example').dataTable();

        @if(Session::has('success'))
            toastr.options = {
                "closeButton": true,
                "timeOut": 3000  
            };
            toastr.success("{{ session('success') }}");
        @endif

      
        @if(Session::has('error'))
            toastr.options = {
                "closeButton": true,
                "timeOut": 3000  
            };
            toastr.error("{{ session('error') }}");
        @endif
    });
</script>