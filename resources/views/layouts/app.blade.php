<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-PNNHB8LH4K"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-PNNHB8LH4K');
</script>

@inject('settings', 'App\Services\SettingService')
@props(['title' => 'Fitx Admin'])
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>{{ $title }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Fitx Admin Panel" name="description" />
    <meta name="google-site-verification" content="w2VijygYi0N-dJzLst6_6XwTUwn3gx3Am9OWf2dfAdg" />
    <link rel="icon" type="image/x-icon" href="{{ $settings->getImageUrl('favicon') }}">

    @include('layouts.common.styles-lib')
    @stack('styles-lib')
    @stack('styles')
</head>

<body data-menu-color="light" data-sidebar="default">

    <div id="app-layout">

        @include('layouts.partials.header')

        @include('layouts.partials.sidebar')

        <div class="content-page">
            <div class="content">

                {{ $slot }}

            </div>

            @include('layouts.partials.footer')

        </div>
    </div>

    @include('layouts.common.scripts-lib')
    @stack('scripts-lib')

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const forms = document.querySelectorAll('.needs-validation');

            Array.prototype.slice.call(forms).forEach(function(form) {
                form.addEventListener('submit', function(event) {

                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }

                    form.classList.add('was-validated');

                }, false);
            });

        });
    </script>

    <script>
        @if (session('success') || session('error'))
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": 5000
            };
            @if (session('success'))
                toastr.success("{{ session('success') }}");
            @endif
            @if (session('error'))
                toastr.error("{{ session('error') }}");
            @endif
        @endif

        document.addEventListener('DOMContentLoaded', function() {
            document.body.addEventListener('submit', function(e) {
                if (e.target.classList.contains('delete-form')) {
                    e.preventDefault();

                    const form = e.target;

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                }
            });
        });
    </script>

    @stack('scripts')

</body>

</html>
