@if (session('success') || session('error'))
    @php
        $isSuccess = session()->has('success');
        $message = session('success') ?? session('error');
        $sound = $isSuccess ? 'success.mp3' : 'error.mp3';
    @endphp

    <div x-data="{ show: true }"
         x-init="
            setTimeout(() => show = false, 6000);
            $refs.notificationSound.play().catch(error => console.log('Audio bloqué :', error));
         "
         x-show="show"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="fixed top-6 left-1/2 -translate-x-1/2 z-50 max-w-md w-full bg-content border border-muted/20 shadow-2xl rounded-2xl p-4 flex items-center gap-3.5 backdrop-blur-md">

        <audio x-ref="notificationSound" src="{{ asset('sounds/' . $sound) }}" preload="auto"></audio>

        <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0
                    {{ $isSuccess ? 'bg-emerald-500/10 text-emerald-500' : 'bg-red-500/10 text-red-500' }}">
            <i class="fa-solid {{ $isSuccess ? 'fa-check' : 'fa-triangle-exclamation' }} text-sm"></i>
        </div>

        <div class="flex-1">
            <p class="text-[10px] font-mono uppercase tracking-wider text-muted text-sm font-semibold">
                {{ $isSuccess ? 'Succès' : 'Erreur' }}
            </p>
            <p class="text-xs font-medium text-text">{!! $message !!}</p>
        </div>

        <button @click="show = false" class="text-muted hover:text-text p-1 font-bold text-lg leading-none">
            &times;
        </button>
    </div>
@endif