<div>
    <button wire:click="remove()" class="text-red-600 flex items-center cursor-pointer font-semibold gap-x-2">
        Delete
        <span wire:loading
            class="animate-spin inline-block size-4 border-3 border-current border-t-transparent text-red-600 rounded-full"
            role="status" aria-label="loading">
            <span class="sr-only">Loading...</span>
        </span>
    </button>
</div>
