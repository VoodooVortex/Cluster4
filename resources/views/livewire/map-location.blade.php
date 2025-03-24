<div>
    <div id='map' wire:ignore style="width: 100vw; min-height: 100dvh"></div>

    {{-- add branch form --}}

    <div wire:ignore.self id="locationForm"
        class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center">
        <div class="w-full h-full bg-white p-6 px-4 rounded-lg shadow-lg overflow-y-auto">
            {{-- header --}}
            <div class="text-white border-[#4D55A0] text-2xl font-extrabold py-3 rounded-2xl flex items-center w-full"
                style="background-color: #4D55A0;">
                <button id="backBtnForm"><span class="mx-5"><i class="fa-solid fa-arrow-left"
                            style="color: #ffffff;"></i></span></button>
                <span>เพิ่มสถานที่</span>
            </div>
            {{-- <div>
                <button class="px-4 py-2 mt-4 rounded-md" onclick="_testAlert()">Test Alert</button>
            </div> --}}
            {{-- tab --}}
            <div class="border rounded-lg shadow my-4">
                <div class="grid grid-cols-2">
                    <button class="tab-link px-6 py-3 rounded-t-lg border-r-2 transition-all duration-300 bg-white"
                        onclick="_openTab(event, 'branchTab')">
                        สาขา
                    </button>
                    <button class="tab-link px-6 py-3 rounded-t-lg border-l-2 transition-all duration-300 bg-gray-200"
                        onclick="_openTab(event, 'otherTab')">
                        อื่นๆ
                    </button>
                </div>
                {{-- tab content --}}{{-- tab branch --}}
                <form id="branchTab" wire:submit.prevent="validateBranchForm"
                    class="tab-content pb-5 pt-5 px-7 scrollbar-ios max-h-[80vh] overflow-y-auto overscroll-contain scrollbar-hidden bg-white rounded-b-lg scroll-container">
                    <label class="block mb-1">ชื่อสาขา <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="nameBranch" class="border p-2 w-full rounded-md"
                        placeholder="กรุณาระบุชื่อสาขา">
                    @error('nameBranch')
                        <small class="text-red-600">{{ $message }}</small>
                    @enderror
                    <label class="block mt-4 mb-1">รหัสสาขา <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="codeBranch" class="border p-2 w-full rounded-md"
                        placeholder="กรุณาระบุรหัสสาขา">
                    @error('codeBranch')
                        <small class="text-red-600">{{ $message }}</small>
                    @enderror
                    <label class="block mt-4 mb-1">เบอร์โทรศัพท์</label>
                    <input type="text" wire:model="phoneBranch" class="border p-2 w-full rounded-md"
                        placeholder="กรุณาระบุเบอร์โทรศัพท์">
                    @error('phoneBranch')
                        <small class="text-red-600">{{ $message }}</small>
                    @enderror
                    <label class="block mt-4 mb-1">บ้านเลขที่ <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="addressBranch" class="border p-2 w-full rounded-md"
                        placeholder="กรุณาระบุบ้านเลขที่">
                    @error('addressBranch')
                        <small class="text-red-600">{{ $message }}</small>
                    @enderror
                    <label class="block mt-4 mb-1">รหัสไปรษณีย์</label>
                    <div wire:ignore>
                        <input type="text" wire:model="zipcodeBranch" id="zipcode"
                            class="border p-2 w-full rounded-md" placeholder="กรุณาระบุรหัสไปรษณีย์">
                    </div>
                    @error('zipcodeBranch')
                        <small class="text-red-600">{{ $message }}</small>
                    @enderror
                    <label class="block mt-4 mb-1">จังหวัด</label>
                    <div wire:ignore>
                        <input type="text" wire:model="provinceBranch" id="province"
                            class="border p-2 w-full rounded-md" placeholder="กรุณาระบุจังหวัด">
                    </div>
                    @error('provinceBranch')
                        <small class="text-red-600">{{ $message }}</small>
                    @enderror
                    <label class="block mt-4 mb-1">อำเภอ</label>
                    <div wire:ignore>
                        <input type="text" wire:model="amphoeBranch" id="amphoe"
                            class="border p-2 w-full rounded-md" placeholder="กรุณาระบุอำเภอ">
                    </div>
                    @error('amphoeBranch')
                        <small class="text-red-600">{{ $message }}</small>
                    @enderror
                    <label class="block mt-4 mb-1">ตำบล</label>
                    <div wire:ignore>
                        <input type="text" wire:model="districtBranch" id="district"
                            class="border p-2 w-full rounded-md" placeholder="กรุณาระบุตำบล">
                    </div>
                    @error('districtBranch')
                        <small class="text-red-600">{{ $message }}</small>
                    @enderror

                    <div class="w-full mt-4 mx-auto">
                        <label for="images"
                            class="block w-full border rounded-lg p-6 text-center cursor-pointe hover:bg-gray-200">
                            <div wire:loading wire:target="imageBranch">
                                <p class="text-sm mb-2 text-gray-500">กำลังโหลดรูปภาพ...</p>
                            </div>
                            @if ($imageBranch)
                                <div class="flex flex-wrap justify-center space-x-2 mb-3">
                                    @foreach ($imageBranch as $image)
                                        <img class="w-15 h-15 rounded-md" src="{{ $image->temporaryUrl() }}">
                                    @endforeach
                                </div>
                            @endif

                            <div class="flex flex-col items-center justify-center">
                                <span class=""><i class="fa-solid fa-image fa-2xl"
                                        style="color: #808080;"></i></span>
                                <p class="text-gray-500 mt-2">อัปโหลดรูปภาพ</p>
                            </div>
                            <input type="file" wire:model="imageBranch" multiple id="images" multiple
                                class="hidden">
                            @error('imageBranch.*')
                                <small class="text-red-600">{{ $message }}</small>
                            @enderror
                            @error('imageBranch')
                                <small class="text-red-600">{{ $message }}</small>
                            @enderror
                        </label>
                    </div>
                    <div class="grid grid-cols-3 gap-2 mt-4">
                        <span class="text-left">ลองจิจูด</span><span
                            class="text-left">{{ $long }}</span><span></span>
                        <span class="text-left">ละติจูด</span><span
                            class="text-left">{{ $lat }}</span><span></span>
                    </div>

                    <div class="grid grid-cols-2 gap-2 mt-4">
                        <span class="content-center">
                            ขอบเขตของสาขา
                        </span>
                        <div class=" items-center justify-center">
                            <div class="relative group">
                                <div id="dropdown-button"
                                    class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-blue-500">
                                    <span class="mr-2" wire:key="scope">{{ $scope }} กิโลเมตร</span>
                                    <span class="justify-center"><i class="fa-solid fa-chevron-down"></i></span>
                                </div>
                                <div id="dropdown-menu"
                                    class="hidden absolute bottom-full right-0 mt-2 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 p-1 space-y-1">
                                    <input id="search-input"
                                        class="block w-full px-4 py-2 text-gray-800 border rounded-md  border-gray-300 focus:outline-none"
                                        type="text" placeholder="เพิ่มขอบเขต" autocomplete="off">
                                    <a href="#" data-value="5"
                                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100 active:bg-blue-100 cursor-pointer rounded-md">5
                                        กิโลเมตร</a>
                                    <a href="#" data-value="10"
                                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100 active:bg-blue-100 cursor-pointer rounded-md">10
                                        กิโลเมตร</a>
                                    <a href="#" data-value="15"
                                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100 active:bg-blue-100 cursor-pointer rounded-md">15
                                        กิโลเมตร
                                    </a>
                                    <a href="#" data-value="20"
                                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100 active:bg-blue-100 cursor-pointer rounded-md">20
                                        กิโลเมตร
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <span><i class="fa-solid fa-house" style="color: #4D55A0;"></i></span><span
                            class="ml-2">สถานที่ใกล้เคียง</span>
                    </div>

                    <div class="flex justify-center">
                        <button type="submit" style="background-color: #4D55A0;"
                            class="text-white px-4 py-2 mt-4 rounded-md">บันทึก</button>
                    </div>
                </form>
                {{-- tab other --}}
            </div>
        </div>

    </div>

    <div id="bottomDrawer"
        class="fixed bottom-0 left-0 right-0 z-50 w-full max-h-[90vh] h-[50vh] translate-y-full overflow-y-auto bg-white transition-transform duration-300 rounded-t-2xl shadow-lg">
        <div class="w-12 h-1 bg-gray-400 mx-auto rounded my-2 cursor-pointer" id="drawerHandle"></div>
        <div class="px-4 pb-6">
            <h2 class="text-xl font-semibold" id="drawerTitle">ชื่อสาขา</h2>
            <p class="text-gray-600" id="drawerAddress">ที่อยู่</p>
            <div class="flex flex-wrap gap-2 mt-4" id="drawerImages">
                <!-- Images -->
            </div>
        </div>
    </div>

</div>

{{-- AutoFill --}}
<script>
    $.Thailand({
        $district: $('#district'),
        $amphoe: $('#amphoe'),
        $province: $('#province'),
        $zipcode: $('#zipcode'),
    });
    document.addEventListener("livewire:init", function() {
        $(document).ready(function() {
            $('#zipcode').on('change', function() {
                let zipcode = $(this).val();
                Livewire.dispatch('updateZipBranch', {
                    zipcode: zipcode
                });
                Livewire.dispatch('generateCodeBranch');
            });

            $('#province').on('change', function() {
                let province = $(this).val();
                Livewire.dispatch('updateProBranch', {
                    province: province
                })
            })

            $('#amphoe').on('change', function() {
                let amphoe = $(this).val();
                Livewire.dispatch('updateAmpBranch', {
                    amphoe: amphoe
                })
            })

            $('#district').on('change', function() {
                let district = $(this).val();
                Livewire.dispatch('updateDisBranch', {
                    district: district
                })
            })
        });
    });
</script>

{{-- Dropdown --}}
<script>
    document.addEventListener("livewire:init", function() {
        $(document).ready(function() {
            const dropdownButton = $('#dropdown-button');
            const dropdownMenu = $('#dropdown-menu');
            const searchInput = $('#search-input');
            let isOpen = false;

            function _toggleDropdown(forceOpen = null) {
                if (forceOpen !== null) {
                    isOpen = forceOpen;
                } else {
                    isOpen = !isOpen;
                }
                dropdownMenu.toggleClass('hidden', !isOpen);
            }

            // Set initial state
            _toggleDropdown();

            dropdownButton.on('click', () => {
                $('#temp-item').remove();
                _toggleDropdown();
            });

            searchInput.on('input', function() {
                const searchTerm = $(this).val().toLowerCase();
                let found = false;

                dropdownMenu.find('a').each(function() {
                    const text = $(this).text().toLowerCase();
                    if (text.includes(searchTerm) || searchTerm === '') {
                        $(this).show();
                        found = true;
                    } else {
                        $(this).hide();
                    }
                    if (!found && searchTerm.match(/^\d+$/)) {
                        $('#temp-item').remove();
                        dropdownMenu.append(`
                            <a href="#" id="temp-item" data-value="${searchTerm}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100 active:bg-blue-100 cursor-pointer rounded-md">
                                ${searchTerm} กิโลเมตร
                            </a>
                        `);
                    }
                    if (searchTerm === '') {
                        $('#temp-item').remove();
                    }
                });
            });

            //เมื่อกด enter
            searchInput.on('keypress', function(event) {
                if (event.which === 13) { // Enter Key
                    event.preventDefault();
                    let newValue = $(this).val().trim();

                    if (newValue.match(/^\d+$/)) {
                        Livewire.dispatch('updateScope', {
                            scope: newValue
                        });
                        _toggleDropdown(false);
                    }
                }
            });

            $(document).on('click', '#dropdown-menu a', function() {
                let selectValue = $(this).data('value');
                console.log(selectValue);

                Livewire.dispatch('updateScope', {
                    scope: selectValue
                });

                _toggleDropdown(false);
            });
        })
    })
</script>

{{-- Tab Script --}}
<script>
    function _openTab(event, tabId) {
        $('.tab-content').each(() => {
            $('.tab-content').addClass('hidden');
        });

        $('#' + tabId).removeClass('hidden');

        $('.tab-link').each(() => {
            $('.tab-link').removeClass('bg-white').addClass('bg-gray-200');
        });

        $(event.target).addClass('bg-white').removeClass('bg-gray-200');
    }
</script>

{{-- SweetAlert2 --}}
<script>
    const branchAlert = '/public/alert-icon/BranchAlert.png';
    const deleteAlert = '/public/alert-icon/DeleteAlert.png';
    const editAlert = '/public/alert-icon/EditAlert.png';
    const errorAlert = '/public/alert-icon/ErrorAlert.png';
    const orderAlert = '/public/alert-icon/OrderAlert.png';
    const successAlert = '/public/alert-icon/SuccessAlert.png';
    const userAlert = '/public/alert-icon/UserAlert.png';

    // function _testAlert() {
    //     Livewire.dispatch('branch-added-alert');
    // }

    document.addEventListener('livewire:init', () => {
        Livewire.on('showConfirmCreateBranch', () => {
            Swal.fire({
                title: 'ยืนยันการเพิ่มสาขา',
                showCancelButton: true,
                confirmButtonText: 'ยืนยัน',
                cancelButtonText: 'ยกเลิก',
                reverseButtons: true,
                imageUrl: branchAlert,
                customClass: {
                    confirmButton: 'swal2-confirm-custom',
                    cancelButton: 'swal2-cancel-custom',
                    title: 'no-padding-title',
                    actions: 'swal2-actions-gap',
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('create-branch');
                }
            });
        });

        Livewire.on('error', (message) => {
            console.log(message);
            Swal.fire({
                title: message,
                confirmButtonText: 'ตกลง',
                imageUrl: errorAlert,
                customClass: {
                    confirmButton: 'swal2-success-custom',
                    title: 'no-padding-title',
                },
                buttonsStyling: false
            });
        });

        Livewire.on('branch-added-alert', () => {
            Swal.fire({
                title: 'ดำเนินการเสร็จสิ้น',
                confirmButtonText: 'ตกลง',
                imageUrl: successAlert,
                customClass: {
                    confirmButton: 'swal2-success-custom',
                    title: 'no-padding-title',
                },
                buttonsStyling: false
            }).then(() => {
                $('#locationForm').addClass('hidden');
            });
        })
    });
</script>

<script>
    const drawer = $('#bottomDrawer');
    const drawerHandle = $('#drawerHandle');
    let startY = 0;
    let currentY = 0;
    let isDragging = false;

    function _openDrawer(properties) {
        drawer.removeClass('translate-y-full').addClass('translate-y-1/2');

        $('#drawerTitle').text(properties.name);
        $('#drawerAddress').text(properties.address);

        const images = Array.isArray(properties.image) ? properties.image : JSON.parse(properties.image);
        const imageHtml = images.map(img => `
            <img src="${img}" class="w-24 h-24 object-cover rounded" />`).join('');
        $('#drawerImages').html(imageHtml);
    }
    $(document).ready(function() {
        // Gesture: ลาก modal
        drawerHandle.on('touchstart', (e) => {
            isDragging = true;
            startY = e.originalEvent.touches[0].clientY;
        });

        drawerHandle.on('touchmove', (e) => {
            if (!isDragging) return;
            currentY = e.originalEvent.touches[0].clientY;
            const diff = currentY - startY;

            if (diff > 50) {
                // ลากลง → ปิด
                drawer.classList.add('translate-y-full');
                drawer.classList.remove('translate-y-1/2');
                drawer.classList.remove('translate-y-0');
            } else if (diff < -50) {
                // ลากขึ้น → เต็มจอ
                drawer.classList.add('translate-y-0');
                drawer.classList.remove('translate-y-1/2');
            }
        });

        drawerHandle.on('touchend', () => {
            isDragging = false;
        });
    });
</script>

{{-- Map --}}
<script>
    document.addEventListener("livewire:init", function() {
        const mapBoxToken = @js($mapBoxToken);
        mapboxgl.accessToken = mapBoxToken;
        var map = new mapboxgl.Map({
            container: 'map',
            center: {!! json_encode($center) !!},
            zoom: 11.15,
            style: 'mapbox://styles/mapbox/streets-v12'
        });
        // mapbox: //styles/mapbox/streets-v11
        //outdoors-v12, satellite-v9, dark-v11, streets-v11
        //mapbox://styles/mapbox/standard
        //light-v11, dark-v11, outdoors-v12, satellite-v9
        //streets-v11, satellite-streets-v11, light-v10
        //dark-v10, outdoors-v11, satellite-v9, satellite-hybrid-v9

        const nav = new mapboxgl.NavigationControl();
        map.addControl(nav);

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(_successLocation, _errorLocation, {
                enableHighAccuracy: true
            });
        } else {
            alert("Geolocation is not supported by this browser.");
        }

        function _successLocation(position) {
            const userLocation = [position.coords.longitude, position.coords.latitude];
            console.log("My Location: " + userLocation);

            Livewire.dispatch('updateLocation', {
                long: position.coords.longitude,
                lat: position.coords.latitude
            });
        }

        function _errorLocation(error) {
            console.error("Error getting location:", error.message);
            alert("ไม่สามารถเข้าถึงตำแหน่งของคุณได้ กรุณาเปิด GPS");
        }

        Livewire.on('locationUpdate', (center) => {
            let userLocation = [center[0][0], center[0][1]]
            map.flyTo({
                center: userLocation,
                zoom: 15,
                essential: true
            });

            new mapboxgl.Marker({
                    color: "blue"
                })
                .setLngLat(userLocation)
                .addTo(map);
        });

        let marker = null;
        let popup = null;
        map.on('click', function(e) {
            let lngLat = e.lngLat;
            const features = map.queryRenderedFeatures(e.point);
            const clickedLayerIds = features.map(f => f.layer.id);

            if (clickedLayerIds.includes('branch-marker-layer')) {
                const feature = features.find(f => f.layer.id === 'branch-marker-layer');
                if (marker) {
                    marker.remove();
                }
                _openDrawer(feature.properties);
                return;
            }

            if (marker) {
                marker.remove();
            }
            if (popup) {
                popup.remove();
            }
            popup = new mapboxgl.Popup({
                closeButton: false
            }).setHTML(`
                    <div class="flex flex-col items-center">
                        <div class="self-end">
                            <button id="closePopupBtn"><i class="fa-solid fa-xmark" style="color: #adadad;"></i></button>
                        </div>
                        <div class="grid grid-cols-2 gap-2">
                            <span class="text-right">ละติจูด :</span>
                            <span class="text-left" id="lat">${lngLat.lat.toFixed(5)}</span>
                            <span class="text-right">ลองจิจูด :</span>
                            <span class="text-left" id="long">${lngLat.lng.toFixed(5)}</span>
                        </div>
                    </div>
                    <hr class="my-2 ">
                    <div id="addLocationBtn" class="flex flex-row justify-center">
                        <span class="mr-2"><i class=" fa-solid fa-circle-plus" style="color: #3b82f6;"></i></span>
                        <span class="text-blue-500">เพิ่มสถานที่</span>
                    </div>`);
            marker = new mapboxgl.Marker({
                    color: "black"
                })
                .setLngLat(lngLat)
                .setPopup(popup)
                .addTo(map);

            popup.addTo(map);


            Livewire.dispatch('setLongLat', {
                long: parseFloat(lngLat.lng.toFixed(6)),
                lat: parseFloat(lngLat.lat.toFixed(6))
            });
        })

        $(document).on('click', '#closePopupBtn', () => {
            if (marker) {
                marker.remove();
            }
        })

        $(document).on('click', '#addLocationBtn', () => {
            $('#locationForm').removeClass('hidden');
            map.removeControl(nav);
        })

        $(document).on('click', '#backBtnForm', () => {
            $('#locationForm').addClass('hidden');
            Livewire.dispatch('updateScope', {
                scope: 5
            });
            map.addControl(nav);
        })


        // Load the branch markers
        const _loadBranchs = (geoJson) => {
            const circleFeatures = [];
            geoJson.features.forEach((location) => {
                const {
                    geometry,
                    properties
                } = location;
                const {
                    id,
                    code,
                    name,
                    address,
                    scope,
                    image
                } = properties;
                const center = geometry.coordinates;

                console.log(image);
                const displayScope = scope * 0.9;
                const circle = turf.circle(turf.point(center), displayScope, {
                    steps: 64,
                    units: 'kilometers'
                });

                circle.properties = {
                    id,
                    code,
                    name,
                    address,
                    scope,
                    image
                };

                circleFeatures.push(circle);
            });

            if (map.getSource('branch-markers')) {
                map.getSource('branch-markers').setData(geoJson);
            } else {
                map.addSource('branch-markers', {
                    type: 'geojson',
                    data: geoJson
                });
            }

            const imageUrl =
                `https://api.mapbox.com/v4/marker/pin-s-marker+4D55A0.png?access_token=${mapboxgl.accessToken}`;
            const iconName = `branch-icon-marker`;

            if (!map.hasImage(iconName)) {
                map.loadImage(imageUrl, (error, image) => {
                    if (error) throw error;
                    map.addImage(iconName, image);

                    if (!map.getLayer('branch-marker-layer')) {
                        map.addLayer({
                            id: 'branch-marker-layer',
                            type: 'symbol',
                            source: 'branch-markers',
                            layout: {
                                'icon-image': 'branch-icon-marker',
                            },
                        });
                    }
                });
            } else {
                if (!map.getLayer('branch-marker-layer')) {
                    map.addLayer({
                        id: 'branch-marker-layer',
                        type: 'symbol',
                        source: 'branch-markers',
                        layout: {
                            'icon-image': 'branch-icon-marker',
                        },
                    });
                }
            }

            const geojsonCircleCollection = {
                type: 'FeatureCollection',
                features: circleFeatures
            };

            if (map.getSource('branch-circles')) {
                map.getSource('branch-circles').setData(geojsonCircleCollection);
            } else {
                map.addSource('branch-circles', {
                    type: 'geojson',
                    data: geojsonCircleCollection
                });

                map.addLayer({
                    id: 'branch-circle-layer',
                    type: 'fill',
                    source: 'branch-circles',
                    paint: {
                        'fill-color': '#007BFF',
                        'fill-opacity': 0.3,
                        'fill-outline-color': '#0044cc'
                    }
                });
            }
        }

        map.on('load', () => {
            _loadBranchs({!! $geoJsonBranch !!})
        });

        Livewire.on('updateBranchLocation', (geoJson) => {
            marker.remove();
            _loadBranchs(JSON.parse(geoJson));
        })
    });
</script>
