<!-- jQuery -->
<script src="{{ asset('backend/js/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('backend/js/bootstrap.bundle.min.js') }}"></script>

<!-- AdminLTE App -->
<script src="{{ asset('backend/js/adminlte.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

<script>
    $('#main-side .has-treeview').click(function(){
        $(this).siblings('.menu-open').removeClass('menu-open').children('.nav-treeview').slideToggle();
    })
    document.addEventListener('DOMContentLoaded', function() {
        const darkModeSwitch = document.getElementById('modeSwitch');

        // Check the initial value of the switch
        setDarkModeFromLocalStorage();

        // Add event listener to detect switch changes
        darkModeSwitch.addEventListener('change', function() {
            toggleDarkMode();
        });

        function toggleDarkMode() {
            const body = document.body;

            if (darkModeSwitch.checked) {
                body.classList.add('dark-mode');
                localStorage.setItem('darkMode', 'enabled');
            } else {
                body.classList.remove('dark-mode');
                localStorage.setItem('darkMode', 'disabled');
            }
        }

        function setDarkModeFromLocalStorage() {
            const darkModeState = localStorage.getItem('darkMode');

            if (darkModeState === 'enabled') {
                darkModeSwitch.checked = true;
                toggleDarkMode();
            } else {
                darkModeSwitch.checked = false;
                toggleDarkMode();
            }
        }
    });
</script>
<script>
    toastr.options = {
    "positionClass": "toast-bottom-right",
    "closeButton": true,
    };
    @if (Session::has('message'))
        var type = "{{ Session::get('alert-type', 'info') }}"
        switch (type) {
            case 'info':
                toastr.options.timeOut = 10000;
                toastr.options.progressBar = true;
                toastr.info("{{ Session::get('message') }}");
                var audio = new Audio('{{ asset('backend/sounds/alerts/success.mp3') }}');
                audio.play();
                break;
            case 'success':
                toastr.options.timeOut = 10000;
                toastr.options.progressBar = true;
                toastr.success("{{ Session::get('message') }}");
                var audio = new Audio('audio.mp3');
                audio.play();

                break;
            case 'warning':
                toastr.options.timeOut = 10000;
                toastr.options.progressBar = true;
                toastr.warning("{{ Session::get('message') }}");
                var audio = new Audio('audio.mp3');
                audio.play();

                break;
            case 'error':
                toastr.options.timeOut = 10000;
                toastr.options.progressBar = true;
                toastr.error("{{ Session::get('message') }}");
                var audio = new Audio('audio.mp3');
                audio.play();
                break;
        }
    @endif
</script>
@yield('per_page_js')