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
                {{-- tab content --}}
                <form id="branchTab" {{-- tab branch --}}
                    class="tab-content pb-5 pt-5 px-7 scrollbar-ios max-h-[80vh] overflow-y-auto overscroll-contain scrollbar-hidden bg-white rounded-b-lg scroll-container">
                    <label class="block mb-1">ชื่อสาขา <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="nameBranch" class="border p-2 w-full rounded-md"
                        placeholder="กรุณาระบุชื่อสาขา">

                    <label class="block mt-4 mb-1">รหัสสาขา <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="codeBranch" class="border p-2 w-full rounded-md"
                        placeholder="กรุณาระบุรหัสสาขา">

                    <label class="block mt-4 mb-1">เบอร์โทรศัพท์</label>
                    <input type="text" wire:model="phoneBranch" class="border p-2 w-full rounded-md"
                        placeholder="กรุณาระบุเบอร์โทรศัพท์">

                    <label class="block mt-4 mb-1">บ้านเลขที่ <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="addressBranch" class="border p-2 w-full rounded-md"
                        placeholder="กรุณาระบุบ้านเลขที่">

                    <div wire:ignore>
                        <label class="block mt-4 mb-1">รหัสไปรษณีย์</label>
                        <input type="text" wire:model="zipcodeBranch" id="zipcode"
                            class="border p-2 w-full rounded-md" placeholder="กรุณาระบุรหัสไปรษณีย์">

                        <label class="block mt-4 mb-1">จังหวัด</label>
                        <input type="text" wire:model="provinceBranch" id="province"
                            class="border p-2 w-full rounded-md" placeholder="กรุณาระบุจังหวัด">

                        <label class="block mt-4 mb-1">อำเภอ</label>
                        <input type="text" wire:model="amphoeBranch" id="amphoe"
                            class="border p-2 w-full rounded-md" placeholder="กรุณาระบุอำเภอ">

                        <label class="block mt-4 mb-1">ตำบล</label>
                        <input type="text" wire:model="districtBranch" id="district"
                            class="border p-2 w-full rounded-md" placeholder="กรุณาระบุตำบล">
                    </div>
                    <div class="w-full mt-4 mx-auto">
                        <label for="images"
                            class="block w-full border rounded-lg p-6 text-center cursor-pointe hover:bg-gray-200">
                            <div id="previewContainer" class="flex flex-wrap justify-center space-x-2 mb-3"></div>

                            <div class="flex flex-col items-center justify-center">
                                <span class=""><i class="fa-solid fa-image fa-2xl"
                                        style="color: #808080;"></i></span>
                                <p class="text-gray-500 mt-2">อัปโหลดรูปภาพ</p>
                            </div>
                            <input type="file" wire:model="imageBranch" id="images" multiple class="hidden"
                                accept="image/*">
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
                                <button id="dropdown-button"
                                    class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-blue-500">
                                    <span class="mr-2" wire:key="scope">{{ $scope }} กิโลเมตร</span>
                                    <span class="justify-center"><i class="fa-solid fa-chevron-down"></i></span>
                                </button>
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
                        <span><i class="fa-solid fa-house" style="color: #4d55a0;"></i></span><span
                            class="ml-2">สถานที่ใกล้เคียง</span>
                    </div>

                    <div class="flex justify-center">
                        <button type="submit" style="background-color: #4D55A0;"
                            class="text-white px-4 py-2 mt-4 rounded-md">บันทึก</button>
                    </div>
                </form>

                {{-- tab interest location --}}
                <form id="otherTab" {{-- tab other --}}
                    class="tab-content pb-5 pt-5 px-7 scrollbar-ios max-h-[80vh] overflow-y-auto overscroll-contain scrollbar-hidden bg-white rounded-b-lg scroll-container">
                    <label class="block mb-1">ชื่อสาขา <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="nameBranch" class="border p-2 w-full rounded-md"
                        placeholder="กรุณาระบุชื่อสาขา">

                    <label class="block mt-4 mb-1">รหัสสาขา <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="codeBranch" class="border p-2 w-full rounded-md"
                        placeholder="กรุณาระบุรหัสสาขา">

                    <label class="block mt-4 mb-1">เบอร์โทรศัพท์</label>
                    <input type="text" wire:model="phoneBranch" class="border p-2 w-full rounded-md"
                        placeholder="กรุณาระบุเบอร์โทรศัพท์">

                    <label class="block mt-4 mb-1">บ้านเลขที่ <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="addressBranch" class="border p-2 w-full rounded-md"
                        placeholder="กรุณาระบุบ้านเลขที่">

                    <div wire:ignore>
                        <label class="block mt-4 mb-1">รหัสไปรษณีย์</label>
                        <input type="text" wire:model="zipcodeBranch" id="zipcode"
                            class="border p-2 w-full rounded-md" placeholder="กรุณาระบุรหัสไปรษณีย์">

                        <label class="block mt-4 mb-1">จังหวัด</label>
                        <input type="text" wire:model="provinceBranch" id="province"
                            class="border p-2 w-full rounded-md" placeholder="กรุณาระบุจังหวัด">

                        <label class="block mt-4 mb-1">อำเภอ</label>
                        <input type="text" wire:model="amphoeBranch" id="amphoe"
                            class="border p-2 w-full rounded-md" placeholder="กรุณาระบุอำเภอ">

                        <label class="block mt-4 mb-1">ตำบล</label>
                        <input type="text" wire:model="districtBranch" id="district"
                            class="border p-2 w-full rounded-md" placeholder="กรุณาระบุตำบล">
                    </div>
                    <div class="w-full mt-4 mx-auto">
                        <label for="images"
                            class="block w-full border rounded-lg p-6 text-center cursor-pointe hover:bg-gray-200">
                            <div id="previewContainer" class="flex flex-wrap justify-center space-x-2 mb-3"></div>

                            <div class="flex flex-col items-center justify-center">
                                <span class=""><i class="fa-solid fa-image fa-2xl"
                                        style="color: #808080;"></i></span>
                                <p class="text-gray-500 mt-2">อัปโหลดรูปภาพ</p>
                            </div>
                            <input type="file" wire:model="imageBranch" id="images" multiple class="hidden"
                                accept="image/*">
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
                                <button id="dropdown-button"
                                    class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-blue-500">
                                    <span class="mr-2" wire:key="scope">{{ $scope }} กิโลเมตร</span>
                                    <span class="justify-center"><i class="fa-solid fa-chevron-down"></i></span>
                                </button>
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
                        <span><i class="fa-solid fa-house" style="color: #4d55a0;"></i></span><span
                            class="ml-2">สถานที่ใกล้เคียง</span>
                    </div>

                    <div class="flex justify-center">
                        <button type="submit" style="background-color: #4D55A0;"
                            class="text-white px-4 py-2 mt-4 rounded-md">บันทึก</button>
                    </div>
                </form>
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

{{-- Map --}}
<script>
    document.addEventListener("livewire:init", function() {
        mapboxgl.accessToken = @js($mapBoxToken);
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
    });



    // map.on('click', function(e) {
    //     const longitute = e.lngLat.lng;
    //     const latitude = e.lngLat.lat;

    //     @this.long = longitute;
    //     @this.lat = latitude;
    //     console.log(@this.long, @this.lat);
    // });
    // let geoJson = {
    //     "type": "FeatureCollection",
    //     "features": [{
    //             "type": "Feature",
    //             "geometry": {
    //                 "coordinates": [
    //                     106.73830754205,
    //                     -6.2922403995615
    //                 ],
    //                 "type": "Point"
    //             },
    //             "properties": {
    //                 "message": "Mantap",
    //                 "iconSize": [
    //                     50,
    //                     50
    //                 ],
    //                 "locationId": 30,
    //                 "title": "Hello new",
    //                 "image": "1a1eb1e4106fff0cc3467873f0f39cab.jpeg",
    //                 "description": "Mantap"
    //             }
    //         },
    //         {
    //             "type": "Feature",
    //             "geometry": {
    //                 "coordinates": [
    //                     106.68681595869,
    //                     -6.3292244652013
    //                 ],
    //                 "type": "Point"
    //             },
    //             "properties": {
    //                 "message": "oke mantap Edit",
    //                 "iconSize": [
    //                     50,
    //                     50
    //                 ],
    //                 "locationId": 29,
    //                 "title": "Rumah saya Edit",
    //                 "image": "0ea59991df2cb96b4df6e32307ea20ff.png",
    //                 "description": "oke mantap Edit"
    //             }
    //         },
    //         {
    //             "type": "Feature",
    //             "geometry": {
    //                 "coordinates": [
    //                     106.62490035406,
    //                     -6.3266855038639
    //                 ],
    //                 "type": "Point"
    //             },
    //             "properties": {
    //                 "message": "Update Baru",
    //                 "iconSize": [
    //                     50,
    //                     50
    //                 ],
    //                 "locationId": 22,
    //                 "title": "Update Baru Gambar",
    //                 "image": "d09444b68d8b72daa324f97c999c2301.jpeg",
    //                 "description": "Update Baru"
    //             }
    //         },
    //         {
    //             "type": "Feature",
    //             "geometry": {
    //                 "coordinates": [
    //                     106.72391468711,
    //                     -6.3934163494543
    //                 ],
    //                 "type": "Point"
    //             },
    //             "properties": {
    //                 "message": "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.",
    //                 "iconSize": [
    //                     50,
    //                     50
    //                 ],
    //                 "locationId": 19,
    //                 "title": "awdwad",
    //                 "image": "f0b88ffd980a764b9fca60d853b300ff.png",
    //                 "description": "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum."
    //             }
    //         },
    //         {
    //             "type": "Feature",
    //             "geometry": {
    //                 "coordinates": [
    //                     106.67224158205,
    //                     -6.3884963990263
    //                 ],
    //                 "type": "Point"
    //             },
    //             "properties": {
    //                 "message": "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.",
    //                 "iconSize": [
    //                     50,
    //                     50
    //                 ],
    //                 "locationId": 18,
    //                 "title": "adwawd",
    //                 "image": "4c35cb1b76af09e6205f94024e093fe6.jpeg",
    //                 "description": "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum."
    //             }
    //         },
    //         {
    //             "type": "Feature",
    //             "geometry": {
    //                 "coordinates": [
    //                     106.74495523289,
    //                     -6.3642034511073
    //                 ],
    //                 "type": "Point"
    //             },
    //             "properties": {
    //                 "message": "awdwad",
    //                 "iconSize": [
    //                     50,
    //                     50
    //                 ],
    //                 "locationId": 12,
    //                 "title": "adawd",
    //                 "image": "7c8c949fd0499eb50cb33787d680778c.jpeg",
    //                 "description": "awdwad"
    //             }
    //         }
    //     ]
    // }

    // const loadLocation = () => {
    //     geoJson.features.forEach((location) => {
    //         const {
    //             geometry,
    //             properties
    //         } = location;
    //         const {
    //             iconSize,
    //             locationId,
    //             title,
    //             image,
    //             description
    //         } = properties;

    //         let markerElement = document.createElement('div');
    //         markerElement.className = 'marker' + locationId;
    //         markerElement.id = locationId;
    //         // markerElement.style.backgroundImage = 'url(/asset/Location-Icon.svg)';
    //         markerElement.style.backgroundSize = 'cover';
    //         markerElement.style.width = '50px';
    //         markerElement.style.height = '50px';

    //         const popUp = new mapboxgl.Popup({
    //             offset: 25
    //         }).setHTML(description).setMaxWidth("400px")

    //         new mapboxgl.Marker(markerElement)
    //             .setLngLat(geometry.coordinates)
    //             .setPopup(new mapboxgl.Popup().setHTML(description))
    //             .addTo(map);
    //     })
    // }
    // loadLocation();
</script>
