<?php

use App\Models\Integration;
use App\Services\OrderInvestigationService;
use Illuminate\Support\Facades\Cache as FacadesCache;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;
use Livewire\Attributes\Computed;
use Maatwebsite\Excel\Facades\Excel;

new class extends Component {
    use WithFileUploads;

    // Integration Selection
    public $selectedIntegrationId = '';

    // Credentials (Hydrated via Dropdown)
    public string $cartpandaToken = '';
    public string $cartpandaSlug = '';
    public string $cartroverUser = '';
    public string $cartroverKey = '';

    // Processing State
    public $uploadedFile;
    public array $orderQueue = [];
    public bool $isProcessing = false;
    public int $totalOrders = 0;
    public int $progressPercentage = 0;
    public string $processId = '';
    public int $processedCount = 0;

    //stop the procccess
    public bool $stopSignal = false;

    public function stopProcess()
    {
        $this->stopSignal = true;
        $this->isProcessing = false;
        $this->orderQueue = [];

        return $this->redirect(request()->header('Referer'), navigate: false);
    }


    #[On('integration-created')]
    public function refreshIntegrations() {}

    public function updatedSelectedIntegrationId($value)
    {
        if (empty($value)) {
            $this->reset(['cartpandaSlug', 'cartpandaToken', 'cartroverUser', 'cartroverKey']);
            return;
        }

        $integration = Integration::find($value);

        if ($integration) {
            // Use fill() to ensure all properties update at once
            $this->fill([
                'cartpandaSlug'  => $integration->cp_slug,
                'cartpandaToken' => $integration->cp_token,
                'cartroverUser'  => $integration->cr_user,
                'cartroverKey'   => $integration->cr_key,
            ]);
        }
    }

    /**
     * Computed Property for the Dropdown
     * This ensures the list is always fresh without manual variable passing
     */
    #[Computed]
    public function integrations()
    {
        return Integration::orderBy('name')->get();
    }

    public function startInvestigation()
    {
        // Debug: Check if values actually exist when the button is clicked
        if (empty($this->cartpandaSlug) || empty($this->cartpandaToken)) {
            $this->addError('selectedIntegrationId', 'Slug or Token is missing from the selected profile.');
            return;
        }

        $this->validate([
            'cartpandaToken' => 'required',
            'cartpandaSlug'  => 'required',
            'uploadedFile'   => 'required|mimes:xlsx,csv',
        ]);

        $data = Excel::toArray([], $this->uploadedFile->getRealPath())[0];
        $this->orderQueue = collect($data)->flatten()->filter()->values()->toArray();

        if (isset($this->orderQueue[0]) && !is_numeric($this->orderQueue[0])) {
            array_shift($this->orderQueue);
        }

        $this->totalOrders = count($this->orderQueue);
        $this->processedCount = 0;
        $this->isProcessing = true;
        $this->processId = 'inv_' . auth()->id() . '_' . time();

        $this->processNext();
    }

    public function processNext()
    {
        // Check if the user clicked "Stop"
        if ($this->stopSignal || empty($this->orderQueue)) {
            return $this->finishProcessing();
        }

        $service = new OrderInvestigationService();

        // 1. Shift the order out
        try {
            $orderNumber = array_shift($this->orderQueue);
            $this->orderQueue = $this->orderQueue;

            $cpData = $service->fetchCartpandaData($this->cartpandaSlug, $this->cartpandaToken, $orderNumber);

            // Handle Rate Limiting
            if (isset($cpData['error'])) {
                array_unshift($this->orderQueue, $orderNumber);
                $this->orderQueue = $this->orderQueue; // Persist the put-back
                $this->dispatch('rate-limit-pause');
                return;
            }

            $finalRow = array_merge($cpData);

            $currentResults = FacadesCache::get($this->processId, []);
            $currentResults[] = $finalRow;
            FacadesCache::put($this->processId, $currentResults, now()->addHours(2));

            $this->processedCount = count($currentResults);

            if ($this->totalOrders > 0) {
                $this->progressPercentage = round(($this->processedCount / $this->totalOrders) * 100);
            }

            // Trigger the next iteration via the JS listener
            $this->dispatch('process-next-item');
        } catch (\Exception $e) {
            // Log the error but keep the process moving so it doesn't hang
            Log::error("Row failed: " . $e->getMessage());
            $this->dispatch('process-next-item');
        }
    }

    protected function finishProcessing()
    {
        $this->isProcessing = false;
        $this->progressPercentage = 100;
        $this->dispatch('process-finished');
    }

    public function downloadResults()
    {
        $results = FacadesCache::get($this->processId, []);
        return Excel::download(new class($results) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings {
            public function __construct(protected $results) {}
            public function headings(): array
            {
                return ['Order Number', 'Cartpanda ID', 'Payment Status', 'Fulfillment Status', 'Status ID', 'CP SKU', 'Test Order', 'Cartpanda Result', 'address2', 'address1', 'city', 'country', 'first_name', 'last_name', 'phone', 'province', 'zip', 'name', 'province_code', 'country_code', 'Cartrover Result'];
            }
            public function collection()
            {
                return collect($this->results);
            }
        }, 'investigation_report.xlsx');
    }
};
