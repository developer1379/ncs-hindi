<script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.15.4/dist/echo.iife.js"></script>

@stack('scripts')
<script>
    // Initialize Laravel Echo for WebSocket communication
    @if(config('broadcasting.default') !== 'null' && config('broadcasting.default') !== 'log')
        @if(config('broadcasting.default') === 'pusher')
            window.Echo = new Echo({
                broadcaster: 'pusher',
                key: "{{ config('broadcasting.connections.pusher.key') }}",
                cluster: "{{ config('broadcasting.connections.pusher.options.cluster') }}",
                forceTLS: true,
                encrypted: true
            });
        @elseif(config('broadcasting.default') === 'reverb')
            window.Echo = new Echo({
                broadcaster: 'reverb',
                key: "{{ config('broadcasting.connections.reverb.key') }}",
                wsHost: "{{ config('broadcasting.connections.reverb.options.host') }}",
                wsPort: "{{ config('broadcasting.connections.reverb.options.port') }}",
                wssPort: "{{ config('broadcasting.connections.reverb.options.port') }}",
                forceTLS: "{{ config('broadcasting.connections.reverb.options.useTLS') }}",
                encrypted: true,
                disableStats: true,
            });
        @endif
    @else
        // For log or null drivers, create a mock Echo object
        window.Echo = {
            channel: function(name) {
                return {
                    listen: function(event, callback) {
                        console.log('Echo.channel listening for', event, 'on', name);
                        return this;
                    },
                    on: function(event, callback) {
                        console.log('Echo.on listening for', event, 'on', name);
                        return this;
                    }
                };
            }
        };
        console.log('Broadcasting driver is set to "{{ config('broadcasting.default') }}". Real-time updates are disabled. For development, consider using Pusher or Reverb.');
    @endif

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        }
    });

    $(document).on('click', '[data-stem-like-btn]', function(e) {
        e.preventDefault();

        const $btn = $(this);
        const url = $btn.data('like-url');
        let $card = $btn.closest('[data-like-card]');

        if (!$card.length || $card.is($btn)) {
            $card = $btn.parents().filter(function() {
                return $(this).find('[data-like-count]').length > 0;
            }).first();
        }

        if (!$card.length) {
            $card = $btn.closest('article, section, div').first();
        }

        const $count = $card.find('[data-like-count]').first();
        const $icon = $btn.find('[data-stem-like-icon]').first();

        $btn.prop('disabled', true);

        $.post(url, {}, function(res) {
            const liked = !!res.liked;

            $btn.data('liked', liked ? 1 : 0);
            $btn.attr('aria-pressed', liked ? 'true' : 'false');

            if ($count.length && typeof res.count !== 'undefined') {
                $count.text(Number(res.count).toLocaleString());
            }

            if ($icon.length) {
                $icon.toggleClass('fa-solid', liked);
                $icon.toggleClass('fa-regular', !liked);
            }

            $btn.toggleClass('text-red-400', liked);
            $btn.toggleClass('text-zinc-300', !liked);

            if (window.toastr) {
                toastr.success(res.message || (liked ? 'Added to likes.' : 'Removed from likes.'));
            }
        }).fail(function(xhr) {
            if (xhr.status === 401) {
                window.location.href = "{{ route('login') }}";
                return;
            }

            if (window.toastr) {
                toastr.error('Could not update like right now.');
            } else {
                alert('Could not update like right now.');
            }
        }).always(function() {
            $btn.prop('disabled', false);
        });
    });

    $('#shareMusicModal').on('show.bs.modal', function(event) {
        const trigger = event.relatedTarget || document.querySelector('[data-stem-share-btn]');
        const $btn = $(trigger);
        const title = $btn.data('share-title') || document.title;
        const url = $btn.data('share-url') || window.location.href;
        const message = `${title} - ${url}`;
        const encodedTitle = encodeURIComponent(title);
        const encodedUrl = encodeURIComponent(url);
        const encodedMessage = encodeURIComponent(message);

        $('#shareMusicTitle').text(title);
        $('#shareMusicUrl').text(url);

        const shareLinks = {
            whatsapp: `https://wa.me/?text=${encodedMessage}`,
            x: `https://twitter.com/intent/tweet?text=${encodedMessage}`,
            facebook: `https://www.facebook.com/sharer/sharer.php?u=${encodedUrl}`,
            telegram: `https://t.me/share/url?url=${encodedUrl}&text=${encodedTitle}`,
            linkedin: `https://www.linkedin.com/sharing/share-offsite/?url=${encodedUrl}`,
            reddit: `https://www.reddit.com/submit?url=${encodedUrl}&title=${encodedTitle}`,
            email: `mailto:?subject=${encodedTitle}&body=${encodedMessage}`,
        };

        Object.entries(shareLinks).forEach(([channel, href]) => {
            $(`[data-share-channel="${channel}"]`).attr('href', href);
        });

        $('[data-share-copy]').data('share-url', url);
    });

    $(document).on('click', '[data-share-copy]', async function(e) {
        e.preventDefault();

        const url = $(this).data('share-url') || window.location.href;

        try {
            await navigator.clipboard.writeText(url);
            if (window.toastr) {
                toastr.success('Link copied to clipboard.');
            }
        } catch (err) {
            const $temp = $('<input>');
            $('body').append($temp);
            $temp.val(url).select();
            document.execCommand('copy');
            $temp.remove();

            if (window.toastr) {
                toastr.success('Link copied to clipboard.');
            }
        }
    });

    console.log('NCS Hindi WebApp Initialized');
</script>
