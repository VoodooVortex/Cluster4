<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class MapLocation extends Component
{
    public $long, $lat;
    public $center = [100.5018, 13.7563]; // Bangkok coordinates
    public $mapBoxToken;
    public $scope = 5;

    protected function getListeners(): array
    {
        return [
            'setLongLat' => 'setLongLat',
            'updateLocation' => 'updateLocation',
            'updateScope' => 'updateScope',
        ];
    }

    public function mount()
    {
        $this->mapBoxToken = env('MAPBOX_ACCESS_TOKEN');
    }

    public $nameBranch;

    public $codeBranch;

    public $phoneBranch;

    public $addressBranch;

    public $zipcodeBranch;

    public $provinceBranch;

    public $amphoeBranch;

    public $districtBranch;

    public $imageBranch;

    public function createBranch() {}


    #[On('updateZipBranch')]
    public function setZipcodeBranch($zipcode = null)
    {
        $this->zipcodeBranch = $zipcode;
    }

    #[On('updateProBranch')]
    public function setProvinceBranch($province = null)
    {
        $this->provinceBranch = $province;
    }

    #[On('updateAmpBranch')]
    public function setAmphoeBranch($amphoe = null)
    {
        $this->amphoeBranch = $amphoe;
    }

    #[On('updateDisBranch')]
    public function setDistrictBranch($district = null)
    {
        $this->districtBranch = $district;
    }

    public function updateLocation($long = null, $lat = null)
    {
        $this->center = [$long, $lat];
        $this->dispatch('locationUpdate', $this->center);
    }

    public function updateScope($scope)
    {
        $this->scope = $scope;
    }

    public function setLongLat($long, $lat)
    {
        $this->long = $long;
        $this->lat = $lat;
    }

    public function render()
    {
        return view('livewire.map-location');
    }
}
