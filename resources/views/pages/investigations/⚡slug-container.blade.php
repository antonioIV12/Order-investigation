<?php

use App\Models\Integration;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component {
    use WithPagination;

    // Computed property to keep the template clean
    public function integrations()
    {
        return Integration::latest()->paginate(10);
    }

    public function delete($id)
    {
        Integration::find($id)->delete();
    }
};
?>

<div class="min-h-screen transition-colors duration-300">
    <div class="max-w-6xl mx-auto py-12 px-6">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
            <div>
                <h1 class="text-4xl font-black text-zinc-900 dark:text-zinc-50 tracking-tight">API Vault</h1>
                <p class="text-zinc-500 dark:text-zinc-400 text-sm mt-1">Manage your saved profiles and API credentials.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <a href="/" wire:navigate
                    class="px-6 py-3 bg-zinc-100 dark:bg-zinc-900 text-zinc-600 dark:text-zinc-300 rounded-2xl font-bold text-sm hover:bg-zinc-200 dark:hover:bg-zinc-800 transition-all active:scale-95">
                    Back
                </a>
                <button wire:click="$dispatch('open-integration-modal')"
                    class="px-6 py-3 bg-blue-600 dark:bg-blue-500 text-white rounded-2xl font-bold text-sm shadow-lg shadow-blue-600/20 dark:shadow-blue-500/10 hover:bg-blue-500 dark:hover:bg-blue-400 transition-all active:scale-95">
                    + New Slug
                </button>
            </div>
        </div>

        <div
            class="bg-white dark:bg-zinc-900/50 border border-zinc-200 dark:border-zinc-800 rounded-[2.5rem] overflow-hidden shadow-xl shadow-zinc-200/50 dark:shadow-none">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-zinc-50 dark:bg-zinc-900/80 border-b border-zinc-100 dark:border-zinc-800">
                            <th
                                class="p-6 text-[10px] font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-[0.2em]">
                                Store Slug</th>
                            <th
                                class="p-6 text-[10px] font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-[0.2em] text-right">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800">
                        @forelse($this->integrations() as $int)
                            <tr class="group hover:bg-zinc-50/50 dark:hover:bg-zinc-800/40 transition-colors">
                                <td class="p-6">
                                    <span
                                        class="text-xs font-mono py-1.5 px-3 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 border border-zinc-200 dark:border-zinc-700">
                                        {{ $int->cp_slug }}
                                    </span>
                                </td>
                                <td class="p-6 text-right">
                                    <button wire:click="delete({{ $int->id }})"
                                        wire:confirm="Are you sure you want to delete this profile?"
                                        class="p-2.5 text-zinc-400 dark:text-zinc-600 hover:text-red-500 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-xl transition-all">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="p-24 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="p-4 bg-zinc-50 dark:bg-zinc-800 rounded-full mb-4">
                                            <svg class="w-8 h-8 text-zinc-300 dark:text-zinc-600" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                                </path>
                                            </svg>
                                        </div>
                                        <p class="text-zinc-500 dark:text-zinc-400 font-medium">No API profiles found.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($this->integrations()->hasPages())
                <div class="p-6 bg-zinc-50/50 dark:bg-zinc-900/30 border-t border-zinc-100 dark:border-zinc-800">
                    {{ $this->integrations()->links() }}
                </div>
            @endif
        </div>

        <livewire:create-slug-modal />
    </div>
</div>
