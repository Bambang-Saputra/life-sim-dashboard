<?php

namespace App\Livewire;

use App\Models\Saving;
use App\Models\SavingDeposit;
use Livewire\Component;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Computed;

class SavingsTracker extends Component
{
    // Saving form
    #[Rule('required|string|max:100')]
    public string $name = '';

    #[Rule('nullable|numeric|min:0|max:999999999999')]
    public string $target_amount = '';

    #[Rule('nullable|date')]
    public string $target_date = '';

    #[Rule('required|in:grass,corn,sky,berry,soil')]
    public string $color = 'grass';

    #[Rule('nullable|string|max:8')]
    public string $icon = '💰';

    #[Rule('nullable|string|max:500')]
    public string $note = '';

    public bool $showSavingForm = false;
    public ?int $editingSavingId = null;

    // Deposit form
    #[Rule('required|exists:savings,id')]
    public ?int $depositSavingId = null;

    #[Rule('required|numeric|not_in:0|min:-999999999|max:999999999')]
    public string $depositAmount = '';

    #[Rule('required|date')]
    public string $depositDate = '';

    #[Rule('nullable|string|max:200')]
    public string $depositNote = '';

    public bool $showDepositForm = false;

    public function mount(): void
    {
        $this->depositDate = now()->format('Y-m-d');
    }

    #[Computed]
    public function savings()
    {
        return Saving::where('is_active', true)
            ->withSum('deposits as current_amount_raw', 'amount')
            ->orderByDesc('id')
            ->get();
    }

    #[Computed]
    public function totalSaved(): float
    {
        return (float) SavingDeposit::sum('amount');
    }

    #[Computed]
    public function monthlyDeposits(): array
    {
        // last 6 months
        $months = [];
        $totals = [];
        for ($i = 5; $i >= 0; $i--) {
            $d = now()->subMonths($i);
            $months[] = $d->format('M Y');
            $totals[] = (float) SavingDeposit::whereYear('deposited_at', $d->year)
                ->whereMonth('deposited_at', $d->month)
                ->sum('amount');
        }
        return compact('months', 'totals');
    }

    public function saveSaving(): void
    {
        $this->validateOnly('name');
        $this->validateOnly('color');

        $data = [
            'name'          => $this->name,
            'target_amount' => $this->target_amount ?: null,
            'target_date'   => $this->target_date ?: null,
            'icon'          => $this->icon ?: '💰',
            'color'         => $this->color,
            'note'          => $this->note ?: null,
        ];

        if ($this->editingSavingId) {
            Saving::findOrFail($this->editingSavingId)->update($data);
        } else {
            Saving::create($data + ['is_active' => true]);
        }

        $this->resetSavingForm();
    }

    public function startEditSaving(int $id): void
    {
        $s = Saving::findOrFail($id);
        $this->editingSavingId = $id;
        $this->name            = $s->name;
        $this->target_amount   = $s->target_amount ? (string) $s->target_amount : '';
        $this->target_date     = $s->target_date?->format('Y-m-d') ?? '';
        $this->color           = $s->color;
        $this->icon            = $s->icon;
        $this->note            = $s->note ?? '';
        $this->showSavingForm  = true;
    }

    public function deleteSaving(int $id): void
    {
        Saving::findOrFail($id)->delete();
    }

    public function startDeposit(int $savingId): void
    {
        $this->depositSavingId = $savingId;
        $this->depositAmount   = '';
        $this->depositNote     = '';
        $this->depositDate     = now()->format('Y-m-d');
        $this->showDepositForm = true;
    }

    public function saveDeposit(): void
    {
        $this->validate([
            'depositSavingId' => 'required|exists:savings,id',
            'depositAmount'   => 'required|numeric|not_in:0',
            'depositDate'     => 'required|date',
            'depositNote'     => 'nullable|string|max:200',
        ]);

        SavingDeposit::create([
            'saving_id'    => $this->depositSavingId,
            'amount'       => $this->depositAmount,
            'deposited_at' => $this->depositDate,
            'note'         => $this->depositNote ?: null,
        ]);

        $this->showDepositForm = false;
        $this->depositSavingId = null;
        $this->depositAmount   = '';
        $this->depositNote     = '';
    }

    public function cancelSavingForm(): void
    {
        $this->resetSavingForm();
    }

    public function cancelDepositForm(): void
    {
        $this->showDepositForm = false;
        $this->depositSavingId = null;
    }

    private function resetSavingForm(): void
    {
        $this->name            = '';
        $this->target_amount   = '';
        $this->target_date     = '';
        $this->color           = 'grass';
        $this->icon            = '💰';
        $this->note            = '';
        $this->editingSavingId = null;
        $this->showSavingForm  = false;
    }

    public function render()
    {
        return view('livewire.savings-tracker');
    }
}
