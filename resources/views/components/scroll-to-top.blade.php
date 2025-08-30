    <div x-data="{ isVisible: false }" x-init="window.addEventListener('scroll', () => { isVisible = window.scrollY > 100; })" class="fixed z-50 bottom-6 right-6" x-show="isVisible"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform translate-y-2">
        <button title="{{ __('scroll_to_top') }}" aria-label="{{ __('scroll_to_top') }}"
            class="bg-gray-800 hover:bg-primary-600 text-white p-2 rounded-md cursor-pointer"
            @click="window.scrollTo({ top: 0, behavior: 'smooth' })">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75 12 3m0 0 3.75 3.75M12 3v18" />
            </svg>

        </button>
    </div>
