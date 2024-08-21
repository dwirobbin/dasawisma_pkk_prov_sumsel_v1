<?php

namespace App\Livewire\App\Backend\DataRecap;

use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use App\Models\FamilyActivity;
use Illuminate\Database\Eloquent\Builder;
use RalphJSmit\Livewire\Urls\Facades\Url as LivewireUrl;

class FamilyActivityIndex extends Component
{
    use WithPagination;

    public $param = '';

    public int $perPage = 5;

    #[Url]
    public string $search = '';

    public ?string $currentUrl = NULL;

    public function mount()
    {
        $this->currentUrl = LivewireUrl::current();
    }

    public function placeholder()
    {
        return view('placeholder');
    }

    public function render()
    {
        $param = match (true) {
            str_contains($this->currentUrl, '/index') => 'index',
            str_contains($this->currentUrl, '/area-code') && strlen($this->param) == 4 => $this->param,
            str_contains($this->currentUrl, '/area-code') && strlen($this->param) == 7 => $this->param,
            str_contains($this->currentUrl, '/area-code') && strlen($this->param) == 10 => $this->param,
            default => 'dasawisma'
        };

        $user = auth()->user();

        $familyActivities = FamilyActivity::query()
            ->selectRaw("
                SUM(CASE
                    WHEN ISNULL(NULLIF(family_activities.up2k_activity, ''))
                    THEN 0
                    WHEN family_activities.up2k_activity NOT LIKE '(%%)'
                    THEN 1
                    ELSE LENGTH(family_activities.up2k_activity) - LENGTH(REPLACE(family_activities.up2k_activity, ')', ''))
                END) AS up2k_activities_sum,
                SUM(CASE
                    WHEN ISNULL(NULLIF(family_activities.env_health_activity, ''))
                    THEN 0
                    WHEN family_activities.env_health_activity NOT LIKE '(%%)'
                    THEN 1
                    ELSE LENGTH(family_activities.env_health_activity) - LENGTH(REPLACE(family_activities.env_health_activity, ')', ''))
                END) AS env_health_activities_sum
            ")
            ->join('family_heads', 'family_activities.family_head_id', '=', 'family_heads.id')
            ->join('dasawismas', 'family_heads.dasawisma_id', '=', 'dasawismas.id')
            ->when($param == 'index', function (Builder $query) {
                $query->addSelect('regencies.id', 'regencies.name')
                    ->join('regencies', 'dasawismas.regency_id', '=', 'regencies.id')
                    ->where('dasawismas.province_id', '=', 16)
                    ->when($this->search != '', function (Builder $query) {
                        $query->where('regencies.name', 'LIKE', '%' . trim($this->search) . '%');
                    })
                    ->groupBy('regencies.id')
                    ->orderBy('dasawismas.regency_id', 'ASC');
            })
            ->when(strlen($param) == 4, function (Builder $query) {
                $query->addSelect('districts.id', 'districts.name')
                    ->join('districts', 'dasawismas.district_id', '=', 'districts.id')
                    ->where('dasawismas.regency_id', '=', $this->param)
                    ->when($this->search != '', function (Builder $query) {
                        $query->where('districts.name', 'LIKE', '%' . trim($this->search) . '%');
                    })
                    ->groupBy('districts.id')
                    ->orderBy('dasawismas.regency_id', 'ASC');
            })
            ->when(strlen($param) == 7, function (Builder $query) {
                $query->addSelect('villages.id', 'villages.name')
                    ->join('villages', 'dasawismas.village_id', '=', 'villages.id')
                    ->where('dasawismas.district_id', '=', $this->param)
                    ->when($this->search != '', function (Builder $query) {
                        $query->where('villages.name', 'LIKE', '%' . trim($this->search) . '%');
                    })
                    ->groupBy('villages.id')
                    ->orderBy('dasawismas.district_id', 'ASC');
            })
            ->when(strlen($param) == 10, function (Builder $query) {
                $query->addSelect('dasawismas.id', 'dasawismas.name', 'dasawismas.slug')
                    ->where('dasawismas.village_id', '=', $this->param)
                    ->when($this->search != '', function (Builder $query) {
                        $query->where('dasawismas.name', 'LIKE', '%' . trim($this->search) . '%');
                    })
                    ->groupBy('dasawismas.id')
                    ->orderBy('dasawismas.village_id', 'ASC');
            })
            ->when($param == 'dasawisma', function (Builder $query) {
                $query->addSelect([
                    'family_heads.id',
                    'family_heads.family_head AS name',
                    'family_activities.up2k_activity',
                    'family_activities.env_health_activity'
                ])
                    ->where('dasawismas.slug', '=', $this->param)
                    ->when($this->search != '', function (Builder $query) {
                        $query->where('family_heads.family_head', 'LIKE', '%' . trim($this->search) . '%');
                    })
                    ->groupBy('family_heads.id')
                    ->orderBy('family_activities.family_head_id', 'ASC');
            })
            ->when($user->role_id == 2 && $user->admin->village_id != NULL, function (Builder $query) use ($user) {
                $query->where('dasawismas.village_id', '=', $user->admin->village_id);
            })
            ->when($user->role_id == 2 && $user->admin->district_id != NULL, function (Builder $query) use ($user) {
                $query->where('dasawismas.district_id', '=', $user->admin->district_id);
            })
            ->when($user->role_id == 2 && $user->admin->regency_id != NULL, function (Builder $query) use ($user) {
                $query->where('dasawismas.regency_id', '=', $user->admin->regency_id);
            })
            ->when($user->role_id == 2 && $user->admin->province_id != NULL, function (Builder $query) use ($user) {
                $query->where('dasawismas.province_id', '=', $user->admin->province_id);
            })
            ->simplePaginate($this->perPage);

        return view('livewire.app.backend.data-recap.family-activity-index', [
            'data' => $familyActivities,
        ]);
    }
}
