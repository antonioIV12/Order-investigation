<div class="max-w-4xl mx-auto my-10 font-sans text-zinc-800 dark:text-zinc-200">
    <div class="flex justify-between items-start mb-8">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-zinc-50">Processor Configuration</h1>
            <p class="text-zinc-500 dark:text-zinc-400 text-sm mt-1">Configure your platform integration and processing parameters.</p>
        </div>
        <div class="bg-blue-50 dark:bg-blue-950/30 border border-blue-100 dark:border-blue-900/50 px-3 py-1.5 rounded-lg flex items-center gap-2">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
            </span>
            <span class="text-[10px] font-bold text-blue-700 dark:text-blue-400 uppercase tracking-widest">Active Engine</span>
        </div>
    </div>

    <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-sm border border-zinc-100 dark:border-zinc-800 p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8 mb-10">
            <div class="space-y-6">
                <h3 class="text-[10px] font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-[0.15em]">Cartpanda Integration</h3>
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-2">Cartpanda Slug</label>
                    <input type="text" wire:model="cartpandaSlug" placeholder="Slug"
                        class="w-full bg-zinc-50 dark:bg-zinc-950 border-none rounded-lg p-3 text-sm focus:ring-2 focus:ring-blue-500/20 transition dark:placeholder-zinc-600">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-2">Cartpanda API Token</label>
                    <div class="relative">
                        <input type="password" wire:model="cartpandaToken" placeholder="API Token"
                            class="w-full bg-zinc-50 dark:bg-zinc-950 border-none rounded-lg p-3 text-sm pr-10 focus:ring-2 focus:ring-blue-500/20 transition">
                        <span class="absolute right-3 top-3 text-zinc-400 dark:text-zinc-500 cursor-pointer hover:text-blue-500 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </span>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <h3 class="text-[10px] font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-[0.15em]">Cartrover Integration</h3>
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-2">Cartrover User</label>
                    <input type="text" wire:model="cartroverUser" placeholder="Merchant User"
                        class="w-full bg-zinc-50 dark:bg-zinc-950 border-none rounded-lg p-3 text-sm focus:ring-2 focus:ring-blue-500/20 transition">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-2">Cartrover Key</label>
                    <div class="relative">
                        <input type="password" wire:model="cartroverKey" placeholder="Merchant Key"
                            class="w-full bg-zinc-50 dark:bg-zinc-950 border-none rounded-lg p-3 text-sm pr-10 focus:ring-2 focus:ring-blue-500/20 transition">
                        <span class="absolute right-3 top-3 text-zinc-400 dark:text-zinc-500 cursor-pointer hover:text-blue-500 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-10">
            <h3 class="text-[10px] font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-[0.15em] mb-4">Upload Investigation File</h3>
            <div class="relative group border-2 border-dashed border-zinc-200 dark:border-zinc-800 rounded-xl p-10 flex flex-col items-center justify-center bg-white dark:bg-zinc-900 hover:bg-zinc-50 dark:hover:bg-zinc-950 hover:border-blue-400 dark:hover:border-blue-900 transition cursor-pointer overflow-hidden">
                <input type="file" wire:model="uploadedFile" class="absolute inset-0 opacity-0 cursor-pointer z-10">

                @if($uploadedFile)
                    <div class="flex items-center gap-3 bg-white dark:bg-zinc-900 border border-zinc-100 dark:border-zinc-800 shadow-sm px-4 py-2 rounded-full text-sm font-medium text-zinc-700 dark:text-zinc-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        {{ $uploadedFile->getClientOriginalName() }}
                    </div>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-zinc-300 dark:text-zinc-700 mb-4 group-hover:text-blue-400 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    <p class="text-sm text-zinc-400 dark:text-zinc-500">Click to select or drag and drop investigation CSV/XLSX</p>
                @endif
            </div>
        </div>

        <div class="bg-zinc-50 dark:bg-zinc-950 border border-zinc-100 dark:border-zinc-800 rounded-xl p-6 flex flex-col md:flex-row md:items-center justify-between gap-6 transition">
            <div class="flex-1">
                <div class="flex justify-between items-end mb-2">
                    <div>
                        <h4 class="text-sm font-bold text-zinc-900 dark:text-zinc-100">Processing Investigation...</h4>
                        <p class="text-[10px] text-zinc-500 dark:text-zinc-400 font-medium tracking-wide">Syncing records between Cartpanda and Cartrover</p>
                    </div>
                    <div class="text-right">
                        <span class="text-xl font-black text-zinc-900 dark:text-zinc-50 leading-none">{{ $progressPercentage }}%</span>
                        <p class="text-[10px] text-zinc-400 dark:text-zinc-500 font-bold uppercase">{{ count($processedResults) }} / {{ $totalOrders }} COMPLETED</p>
                    </div>
                </div>
                <div class="w-full bg-zinc-200 dark:bg-zinc-800 rounded-full h-1.5 overflow-hidden">
                    <div class="bg-blue-600 dark:bg-blue-500 h-full transition-all duration-700 ease-out" style="width: {{ $progressPercentage }}%"></div>
                </div>
            </div>

            <button wire:click="startInvestigation" wire:loading.attr="disabled"
                class="bg-[#050C21] dark:bg-blue-600 hover:bg-[#0A1635] dark:hover:bg-blue-500 text-white px-10 py-4 rounded-lg font-bold text-sm shadow-lg shadow-blue-900/10 flex items-center justify-center gap-3 transition-all transform active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M13 10V3L4 14H11V21L20 10H13Z" />
                </svg>
                <span>{{ $isProcessing ? 'Processing...' : 'Run Investigation' }}</span>
            </button>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('batch-finished', () => {
                setTimeout(() => { @this.processBatch(); }, 50);
            });
        });
    </script>
</div>
