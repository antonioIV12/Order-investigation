<?php

use Livewire\Component;
use App\Models\Integration;

new class extends Component
{
 // These must be public for Livewire to bind them
    public bool $show = false;
    public string $cp_slug = '';
    public string $cp_token = '';
    public string $cr_user = '';
    public string $cr_key = '';

    protected $listeners = ['open-integration-modal' => 'open'];

    public function open() {
        $this->show = true;
    }

    public function save() {
        // 1. Validate
        $this->validate([
            'cp_slug' => 'required',
            'cp_token' => 'required',
        ]);

        // 2. Persist
        Integration::create([
            'cp_slug' => $this->cp_slug,
            'cp_token' => $this->cp_token,
            'cr_user' => $this->cr_user,
            'cr_key' => $this->cr_key,
        ]);

        // 3. Reset and Close
        $this->show = false;
        $this->dispatch('integration-created');
        $this->reset([ 'cp_slug', 'cp_token', 'cr_user', 'cr_key']);
    }
};
?>

<div x-data="{ open: @entangle('show') }"
     x-show="open"
     x-on:keydown.escape.window="open = false"
     class="fixed inset-0 z-[60] flex items-center justify-center p-4"
     x-cloak>

    <div class="absolute inset-0 bg-zinc-950/60 backdrop-blur-sm" @click="open = false"></div>

    <div class="relative bg-white dark:bg-zinc-900 w-full max-w-lg rounded-[2rem] shadow-2xl border border-zinc-200 dark:border-zinc-800 p-8 overflow-hidden max-h-[90vh] overflow-y-auto">
        <div class="mb-6">
            <h3 class="text-2xl font-black text-zinc-900 dark:text-white">New Integration Profile</h3>
            <p class="text-zinc-500 text-sm">Bundle your API credentials into one reusable profile.</p>
        </div>

        <div class="space-y-6">

            <hr class="border-zinc-100 dark:border-zinc-800">

            <div class="space-y-4">
                <h4 class="text-[10px] font-black text-blue-500 uppercase tracking-[0.2em] ml-2">Cartpanda Config</h4>
                <div class="grid grid-cols-1 gap-4">
                    <input type="text" wire:model="cp_slug" placeholder="Store Slug" class="w-full bg-zinc-50 dark:bg-zinc-950 border-none rounded-2xl p-4 text-sm focus:ring-2 focus:ring-blue-500 transition">
                    <input type="password" wire:model="cp_token" placeholder="API Token" class="w-full bg-zinc-50 dark:bg-zinc-950 border-none rounded-2xl p-4 text-sm focus:ring-2 focus:ring-blue-500 transition">
                </div>
            </div>

            <div class="space-y-4">
                <h4 class="text-[10px] font-black text-emerald-500 uppercase tracking-[0.2em] ml-2">Cartrover Config (Optional)</h4>
                <div class="grid grid-cols-1 gap-4">
                    <input type="text" wire:model="cr_user" placeholder="Cartrover User ID" class="w-full bg-zinc-50 dark:bg-zinc-950 border-none rounded-2xl p-4 text-sm focus:ring-2 focus:ring-emerald-500 transition">
                    <input type="password" wire:model="cr_key" placeholder="Cartrover API Key" class="w-full bg-zinc-50 dark:bg-zinc-950 border-none rounded-2xl p-4 text-sm focus:ring-2 focus:ring-emerald-500 transition">
                </div>
            </div>

            <div class="pt-4 flex gap-3">
                <button @click="open = false" class="flex-1 px-6 py-4 rounded-2xl font-bold text-sm bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300">Cancel</button>
                <button wire:click="save" class="flex-1 px-6 py-4 rounded-2xl font-bold text-sm bg-blue-600 text-white shadow-lg shadow-blue-600/20 hover:bg-blue-500 transition">Save Profile</button>
            </div>
        </div>
    </div>
</div>