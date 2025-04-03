<?php

namespace App\Livewire;

use App\Models\Branch;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;

class MapLocation extends Component
{
    use WithFileUploads;
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

    public $nameBranch, $codeBranch, $phoneBranch, $addressBranch, $zipcodeBranch, $provinceBranch, $amphoeBranch, $districtBranch, $imageBranch;


    public $geoJsonBranch;

    #[On('generateCodeBranch')]
    public function generateCodeBranch()
    {
        $zipcode = substr($this->zipcodeBranch ?? '', 0, 2);
        $this->codeBranch = $zipcode . '-' . str_pad(Branch::withTrashed()->count() + 1, 4, '0', STR_PAD_LEFT);
    }

    public function loadBranchLocation()
    {
        $user = auth()->user();
        $locations = Branch::query()
            ->when($user && $user->us_role === 'Sales', function ($query) use ($user) {
                $query->where('br_us_id', $user->us_id);
            })
            ->when($user && $user->us_role === 'Sales Supervisor', function ($query) use ($user) {
                $query->whereIn('br_us_id', function ($sub) use ($user) {
                    $sub->select('us_id')
                        ->from('users')
                        ->where('us_head', $user->us_id)
                        ->orWhere('us_id', $user->us_id);
                });
            })
            ->with(['image', 'manager'])
            ->select(
                'br_id',
                'br_code',
                'br_name',
                'br_phone',
                'br_scope',
                DB::raw('ST_Y(br_longlat) as lat'),
                DB::raw('ST_X(br_longlat) as lng'),
                'br_address',
                'br_subdistrict',
                'br_district',
                'br_province',
                'br_postalcode',
                'br_us_id',
            )
            ->get();

        $arrayLocation = [];
        foreach ($locations as $location) {
            $address = $location->br_address . ' ' . $location->br_subdistrict . ' ' . $location->br_district . ' ' . $location->br_province . ' ' . $location->br_postalcode;
            $images = $location->image->pluck('i_pathUrl');
            $manager = $location->manager?->us_fname . ' ' . $location->manager?->us_lname;
            $arrayLocation[] = [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [(float)$location->lng, (float)$location->lat],
                ],
                'properties' => [
                    'id' => $location->br_id,
                    'code' => $location->br_code,
                    'name' => $location->br_name,
                    'address' => $address,
                    'scope' => (float)$location->br_scope,
                    'image' => $images->values()->toArray(),
                    'phone' => $location->br_phone,
                    'manager_id' => $location->manager?->us_id,
                    'manager_name' => $manager,
                    'manager_image' => $location->manager?->us_image,
                    'manager_role' => $location->manager?->us_role,
                    'manager_email' => $location->manager?->us_email,
                ],
            ];
        }

        $geoBranch = [
            'type' => 'FeatureCollection',
            'features' => $arrayLocation,
        ];

        $geoJson = collect($geoBranch)->toJson();
        $this->geoJsonBranch = $geoJson;
    }

    public function validateBranchForm()
    {
        $validate = $this->validate([
            'nameBranch' => 'required|unique:branch,br_name',
            'codeBranch' => 'required|unique:branch,br_code',
            'phoneBranch' => 'required|min:10',
            'addressBranch' => 'required',
            'zipcodeBranch' => 'required|numeric',
            'provinceBranch' => 'required',
            'amphoeBranch' => 'required',
            'districtBranch' => 'required',
            'imageBranch.*' => 'image|max:2048',
        ], [
            'nameBranch.required' => 'กรุณากรอกชื่อสาขา',
            'nameBranch.unique' => 'ชื่อสาขานี้มีอยู่ในระบบแล้ว',
            'codeBranch.required' => 'กรุณากรอกรหัสสาขา',
            'codeBranch.unique' => 'รหัสสาขานี้มีอยู่ในระบบแล้ว',
            'phoneBranch.required' => 'กรุณากรอกเบอร์โทรศัพท์',
            'phoneBranch.min' => 'กรุณากรอกเบอร์โทรศัพท์ให้ครบ',
            'addressBranch.required' => 'กรุณากรอกบ้านเลขที่',
            'zipcodeBranch.required' => 'กรุณากรอกรหัสไปรษณีย์',
            'provinceBranch.required' => 'กรุณากรอกจังหวัด',
            'amphoeBranch.required' => 'กรุณากรอกอำเภอ',
            'districtBranch.required' => 'กรุณากรอกตำบล',
            'imageBranch.image' => 'ไฟล์ที่อัปโหลดต้องเป็นรูปภาพ',
            'imageBranch.max' => 'ขนาดไฟล์รูปภาพต้องไม่เกิน 2MB',
            'imageBranch.*.image' => 'ไฟล์ที่อัปโหลดต้องเป็นรูปภาพ',
        ]);

        $this->dispatch('showConfirmCreateBranch');
    }

    #[On('create-branch')]
    public function createBranch()
    {
        try {
            DB::transaction(function () {
                do {
                    $this->generateCodeBranch();
                } while (
                    Branch::where('br_code', $this->codeBranch)->lockForUpdate()->exists()
                );

                $branch = Branch::create([
                    'br_code' => $this->codeBranch,
                    'br_name' => $this->nameBranch,
                    'br_phone' => $this->phoneBranch,
                    'br_scope' => $this->scope,
                    'br_longlat' => DB::raw("ST_GeomFromText('POINT($this->long $this->lat)', 4326)"),
                    'br_address' => $this->addressBranch,
                    'br_subdistrict' => $this->amphoeBranch,
                    'br_district' => $this->districtBranch,
                    'br_province' => $this->provinceBranch,
                    'br_postalcode' => $this->zipcodeBranch,
                    'br_us_id' => auth()->id(),
                ]);

                if ($this->imageBranch) {
                    foreach ($this->imageBranch as $image) {
                        $imageName = time() . '_' . $image->getClientOriginalName();
                        $image->storeAs('images_branch', $imageName, 'public');
                        $imageUrl = Storage::url('/app/public/images_branch/' . $imageName);
                        $branch->image()->create([
                            'i_pathUrl' => $imageUrl,
                        ]);
                    }
                }
            });

            $this->reset(['codeBranch', 'nameBranch', 'phoneBranch', 'addressBranch', 'zipcodeBranch', 'provinceBranch', 'amphoeBranch', 'districtBranch', 'imageBranch']);
            $this->dispatch('branch-added-alert');
            $this->loadBranchLocation();
            $this->dispatch('updateBranchLocation', $this->geoJsonBranch);
        } catch (\Exception $e) {
            // dd($e);
            $this->dispatch('error', 'เกิดข้อผิดพลาดในการสร้างสาขา กรุณาลองใหม่อีกครั้ง');
            return;
        }
    }

    #[On('delete-branch')]
    public function deleteBranch($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $branch = Branch::findOrFail($id);
                $branch->image()->delete();
                $branch->delete();
            });
            $this->loadBranchLocation();
            $this->dispatch('deleteBranchLocation', $this->geoJsonBranch);
            $this->dispatch('branch-deleted-alert');
        } catch (\Exception $e) {
            // dd($e);
            $this->dispatch('error', 'เกิดข้อผิดพลาดในการลบสาขา กรุณาลองใหม่อีกครั้ง');
            return;
        }
    }

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
        $this->loadBranchLocation();
        return view('livewire.map-location');
    }
}
