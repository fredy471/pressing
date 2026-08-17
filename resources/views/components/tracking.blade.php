   <form action="{{ route('tracking') }}" method="POST" class="flex flex-col sm:flex-row gap-2 w-full max-w-md">
       @csrf
       <input
           type="text"
           name="tracking_code"
           placeholder="Code de suivi (ex: PR-9482)"
           required
           class="flex-1 rounded-sm border border-muted bg-bg px-4 py-2.5 text-[0.86rem] text-text placeholder:text-muted focus:border-text focus:outline-none transition duration-200">
       <x-button type="submit" variant="primary">
           Suivre mon colis
       </x-button>
   </form>