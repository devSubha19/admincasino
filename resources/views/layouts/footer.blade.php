@if(session('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
@endif
<script src="sjs/index.js"></script>
</body>
</html>