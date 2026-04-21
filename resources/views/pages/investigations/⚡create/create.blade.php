<div class="max-w-4xl mx-auto my-10 font-sans text-zinc-800 dark:text-zinc-200">
    <div class="flex justify-between items-start mb-8">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-zinc-50">Order ID Investigation</h1>
            <p class="text-zinc-500 dark:text-zinc-400 text-sm mt-1">Search Orde details using CartPanda Order ID.</p>
        </div>
        <div
            class="bg-blue-50 dark:bg-blue-950/30 border border-blue-100 dark:border-blue-900/50 px-3 py-1.5 rounded-lg flex items-center gap-2">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
            </span>
            <span class="text-[10px] font-bold text-blue-700 dark:text-blue-400 uppercase tracking-widest">Active
                Engine</span>
        </div>
    </div>

    <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-sm border border-zinc-100 dark:border-zinc-800 p-8">
        <div>
            <livewire:create-slug-modal />

            <div class="flex items-end gap-3 mb-8">
                <div class="flex-1 w-full">
                    <label class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest ml-2 mb-2 block">Active
                        Profile</label>
                    <div class="flex flex-row justify-between">

                        <select wire:model.live="selectedIntegrationId"
                            class="w-1/3 bg-white dark:bg-zinc-950 border-none rounded-2xl p-4 text-sm font-semibold focus:ring-2 focus:ring-blue-500 transition shadow-sm">
                            <option value="">Select a saved configuration...</option>
                            @foreach ($this->integrations as $int)
                                <option value="{{ $int->id }}" wire:key="int-{{ $int->id }}">
                                    {{ $int->cp_slug }}
                                </option>
                            @endforeach
                        </select>
                        <button type="button" wire:click="$dispatch('open-integration-modal')"
                            class="bg-blue-600 text-white p-4 rounded-2xl hover:bg-blue-500 transition shadow-lg shadow-blue-600/20 active:scale-95">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </button>
                    </div>


                </div>

            </div>

            <div class="mb-10">
                <h3 class="text-[10px] font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-[0.15em] mb-4">
                    Upload
                    Investigation File</h3>
                <div
                    class="relative group border-2 border-dashed border-zinc-200 dark:border-zinc-800 rounded-xl p-10 flex flex-col items-center justify-center bg-white dark:bg-zinc-900 hover:bg-zinc-50 dark:hover:bg-zinc-950 hover:border-blue-400 dark:hover:border-blue-900 transition cursor-pointer overflow-hidden">
                    <input type="file" wire:model="uploadedFile"
                        class="absolute inset-0 opacity-0 cursor-pointer z-10">

                    @if ($uploadedFile)
                        <div
                            class="flex items-center gap-3 bg-white dark:bg-zinc-900 border border-zinc-100 dark:border-zinc-800 shadow-sm px-4 py-2 rounded-full text-sm font-medium text-zinc-700 dark:text-zinc-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-500" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            {{ $uploadedFile->getClientOriginalName() }}
                        </div>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-10 w-10 text-zinc-300 dark:text-zinc-700 mb-4 group-hover:text-blue-400 transition"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        <p class="text-sm text-zinc-400 dark:text-zinc-500">Click to select or drag and drop
                            investigation
                            CSV/XLSX</p>
                    @endif
                </div>
            </div>

            <div>
                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-xs">
                        <p class="font-bold">Please check the following:</p>
                        <ul class="list-disc ml-4">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div
                    class="bg-zinc-50 dark:bg-zinc-950 border border-zinc-100 dark:border-zinc-800 rounded-xl p-6 flex flex-col md:flex-row md:items-center justify-between gap-6 transition">
                    <div class="flex-1">
                        <div class="flex justify-between items-end mb-2">
                            <div>
                                <h4 class="text-sm font-bold text-zinc-900 dark:text-zinc-100">
                                    {{ $isProcessing ? 'Processing Investigation...' : 'Investigation Complete' }}
                                </h4>
                                <p class="text-[10px] text-zinc-500 dark:text-zinc-400 font-medium tracking-wide">
                                    {{ $isProcessing ? 'Syncing records between Cartpanda and Cartrover' : 'Your report is ready for download' }}
                                </p>
                            </div>
                            <div class="text-right">
                                <span
                                    class="text-xl font-black text-zinc-900 dark:text-zinc-50 leading-none">{{ $progressPercentage }}%</span>
                                <p class="text-[10px] text-zinc-400 dark:text-zinc-500 font-bold uppercase">
                                    {{ $processedCount }} / {{ $totalOrders }} COMPLETED</p>
                            </div>
                        </div>
                        <div class="w-full bg-zinc-200 dark:bg-zinc-800 rounded-full h-1.5 overflow-hidden">
                            <div class="bg-blue-600 dark:bg-blue-500 h-full transition-all duration-700 ease-out"
                                style="width: {{ $progressPercentage }}%"></div>
                        </div>
                    </div>

                    @if ($isProcessing)
                        <button wire:click="stopProcess"
                            class="bg-red-100 hover:bg-red-200 text-red-600 px-6 py-4 rounded-lg font-bold text-sm transition-all active:scale-95">
                            Stop
                        </button>
                    @endif
                    @if (!$isProcessing && $processedCount > 0)
                        <button wire:click="downloadResults"
                            class="bg-green-600 hover:bg-green-500 text-white px-10 py-4 rounded-lg font-bold text-sm shadow-lg flex items-center justify-center gap-3 transition-all transform active:scale-95">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4-4v12" />
                            </svg>
                            <span>Download Results</span>
                        </button>
                    @else
                        <button wire:click="startInvestigation" wire:loading.attr="disabled"
                            class="bg-[#050C21] dark:bg-blue-600 hover:bg-[#0A1635] dark:hover:bg-blue-500 text-white px-10 py-4 rounded-lg font-bold text-sm shadow-lg shadow-blue-900/10 flex items-center justify-center gap-3 transition-all transform active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path d="M13 10V3L4 14H11V21L20 10H13Z" />
                            </svg>
                            <span>{{ $isProcessing ? 'Processing...' : 'Run Investigation' }}</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('livewire:init', () => {
                Livewire.on('process-next-item', () => {
                    // Short delay to prevent browser locking and allow UI to update
                    setTimeout(() => {
                        @this.processNext();
                    }, 100);
                });

                Livewire.on('rate-limit-pause', () => {
                    console.warn('Rate limit hit. Pausing for 10 seconds...');
                    setTimeout(() => {
                        @this.processNext();
                    }, 10000);
                });
            });
        </script>
    </div>
