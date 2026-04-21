<?php

use App\Services\OrderSearchService;
use App\Models\Integration;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;
use Livewire\Attributes\Computed;
use Maatwebsite\Excel\Facades\Excel;

new class extends Component
{
    use WithFileUploads;
    // --- ADD THIS LINE ---
    public $selectedIntegrationId = '';

    public string $cartpandaToken = '';
    public string $cartpandaSlug = '';
    public string $cartroverUser = '';
    public string $cartroverKey = '';
    public $uploadedFile;

    public array $queue = [];
    public bool $isProcessing = false;
    public int $total = 0;
    public int $progressPercentage = 0;
    public int $processedCount = 0;
    public string $processId = '';

    //stop the procccess
    public bool $stopSignal = false;

    public function stopProcess()
    {
        $this->stopSignal = true;
        $this->isProcessing = false;

        return $this->redirect(request()->header('Referer'), navigate: false);
    }

    #[On('integration-created')]
    public function refreshIntegrations() {}

    /**
     * Dropdown Observer
     * Automatically fills the API keys when you pick a profile
     */
    public function updatedSelectedIntegrationId($id)
    {
        if ($id) {
            $integration = Integration::find($id);
            if ($integration) {
                $this->cartpandaSlug = $integration->cp_slug;
                $this->cartpandaToken = $integration->cp_token;
                $this->cartroverUser = $integration->cr_user;
                $this->cartroverKey = $integration->cr_key;
            }
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

    public function start()
    {
        $this->validate([
            'uploadedFile' => 'required|mimes:xlsx,csv',
            'cartpandaSlug' => 'required',
            'cartpandaToken' => 'required',
        ]);

        $rows = Excel::toArray([], $this->uploadedFile->getRealPath())[0];
        array_shift($rows); // Remove header

        $this->queue = $rows;
        $this->total = count($this->queue);
        $this->processedCount = 0;
        $this->isProcessing = true;
        $this->processId = 'search_' . auth()->id() . '_' . time();

        $this->processNext();
    }

    public function processNext()
    {
        // 1. Safety check: if queue is gone, we are done
        if ($this->stopSignal ||    empty($this->queue)) {
            $this->isProcessing = false;
            return;
        }

        try {
            $service = new OrderSearchService();
            $row = array_shift($this->queue);

            $name = $row[0] ?? '';
            $email = $row[1] ?? '';

            $orderId = $service->findVerifiedOrderId($this->cartpandaSlug, $this->cartpandaToken, $email, $name);

            if ($orderId) {
                $cpData = $service->getFormattedOrderData($this->cartpandaSlug, $this->cartpandaToken, $orderId);
                $finalData = $cpData; // Merge logic if you have other sources
            } else {
                $finalData = [
                    'Order Number' => 'N/A',
                    'Cartpanda ID' => 'N/A',
                    'Cartpanda Result' => 'Not Found',
                    'Cartrover Result' => 'Skipped'
                ];
            }

            $results = Cache::get($this->processId, []);
            $results[] = $finalData;
            Cache::put($this->processId, $results, now()->addHours(2));

            $this->processedCount = count($results);

            if ($this->total > 0) {
                $this->progressPercentage = round(($this->processedCount / $this->total) * 100);
            }
            // 2. Dispatch next step
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


    public function download()
    {
        $results = Cache::get($this->processId, []);

        return Excel::download(
            new class($results) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings {
                public function __construct(protected $results) {}
                public function headings(): array
                {
                    return ['Order Number', 'Cartpanda ID', 'Payment Status', 'Fulfillment Status', 'Status ID', 'CP SKU', 'Test Order', 'Cartpanda Result', 'address2', 'address1', 'city', 'country', 'first_name', 'last_name', 'phone', 'province', 'zip', 'name', 'province_code', 'country_code', 'Cartrover Result'];
                }
                public function collection()
                {
                    return collect($this->results);
                }
            },
            'investigation_report.xlsx'
        );
    }
};
