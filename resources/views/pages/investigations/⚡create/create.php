

<?php

use App\Services\OrderInvestigationService;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

new class extends Component {
    use WithFileUploads;

    // Credentials
    public string $cartpandaToken = '';
    public string $cartpandaSlug = '';
    public string $cartroverUser = '';
    public string $cartroverKey = '';

    // State
    public $uploadedFile;
    public array $orderQueue = [];
    public array $processedResults = [];
    public bool $isProcessing = false;
    public int $totalOrders = 0;
    public int $progressPercentage = 0;

    public function startInvestigation()
    {
        $this->validate([
            'cartpandaToken' => 'required',
            'cartpandaSlug'  => 'required',
            'uploadedFile'   => 'required|mimes:xlsx,csv',
        ]);

        $data = Excel::toArray([], $this->uploadedFile->getRealPath())[0];
        $this->orderQueue = collect($data)->flatten()->filter()->values()->toArray();

        // Skip header if first item is non-numeric
        if (isset($this->orderQueue[0]) && !is_numeric($this->orderQueue[0])) {
            array_shift($this->orderQueue);
        }

        $this->totalOrders = count($this->orderQueue);
        $this->processedResults = [];
        $this->isProcessing = true;

        $this->processBatch();
    }

    public function processBatch()
    {
        $service = new OrderInvestigationService();
        $batchSize = 5;

        for ($i = 0; $i < $batchSize; $i++) {
            if (empty($this->orderQueue)) break;

            $orderNumber = array_shift($this->orderQueue);

            // 1. Fetch Cartpanda
            $cpData = $service->fetchCartpandaData($this->cartpandaSlug, $this->cartpandaToken, $orderNumber);

            // 2. Fetch Cartrover if Cartpanda ID exists
            $cpId = $cpData['Cartpanda ID'] ?? null;
            $crData = ($cpId && $cpId !== 'N/A')
                ? $service->fetchCartroverData($this->cartroverUser, $this->cartroverKey, $cpId)
                : ['Cartrover Result' => 'Skipped'];

            $this->processedResults[] = array_merge(['Order Number' => $orderNumber], $cpData, $crData);
        }

        $this->updateProgress();

        if (!empty($this->orderQueue)) {
            $this->dispatch('batch-finished');
        } else {
            $this->isProcessing = false;
            return $this->downloadResults();
        }
    }

    private function updateProgress()
    {
        if ($this->totalOrders > 0) {
            $this->progressPercentage = round((count($this->processedResults) / $this->totalOrders) * 100);
        }
    }

    public function downloadResults()
    {
        return Excel::download(
            new class ($this->processedResults) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings {
                public function __construct(protected $results) {}
                public function headings(): array {
                    return ['Order Number', 'Cartpanda ID', 'Payment Status', 'Fulfillment Status', 'Status ID', 'CP SKU', 'Test Order', 'Cartpanda Result', 'address2', 'address1', 'city', 'country', 'first_name', 'last_name', 'phone', 'province', 'zip', 'name', 'province_code', 'country_code', 'Cartrover Result'];
                }
                public function collection() { return collect($this->results); }
            },
            'investigation_report.xlsx'
        );
    }
};
