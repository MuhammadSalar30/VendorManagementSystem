@extends('layouts.admin', ['subtitle'=> "Products", 'title' => "Edit Products"])

@section('content')

<div class="grid lg:grid-cols-3 gap-6">
    <div class="p-6 rounded-lg border border-default-200">

        <!-- Main Image Section -->
        <div class="h-96 p-6 flex flex-col items-center justify-center rounded-lg border border-default-200 mb-4 mainImageWrapper">
            @if (!empty($data->image))
            <button type="button" id="btnremoveimage" class="ml-auto bg-red-600 text-white rounded-full p-1 hover:bg-red-800">✕</button>
                <img src="{{ asset($data->image) }}" alt="" id="mainImagePreview" class="max-w-full max-h-96 rounded-lg">
            @else
                <div class="mainImageUpload btnuploadimage text-center cursor-pointer">
                    <div class="mb-4">
                        <i data-lucide="image" class="w-10 h-10 stroke-primary fill-primary/10"></i>
                    </div>
                    <h5 class="text-base text-primary font-medium mb-2">
                        <i data-lucide="upload-cloud" class="inline-flex ms-2"></i>
                        Upload Main Image
                    </h5>
                    <p class="text-sm text-default-600 mb-2">Upload a cover image for your product.</p>
                    {{-- <p class="text-sm text-default-600">
                        File Format
                        <span class="text-default-800">jpeg, png</span>
                        Recommended Size
                        <span class="text-default-800">600x600 (1:1)</span>
                    </p> --}}
                </div>
            @endif
        </div>
        <input type="file" id="mainImageInput" class="hidden" accept="image/*" />
        <input type="hidden" id="productId" value="{{ $data->id }}" />

        <!-- Additional Images Section -->
        <h4 class="text-base font-medium text-default-800 mb-4">Additional Images</h4>
        <div class="space-y-4">

            <!-- Additional Image 1 -->
            <div class="additional-image-container">
                <label class="block text-sm font-medium text-default-700 mb-2">Additional Image 1</label>

                <div class="h-32 p-4 flex flex-col items-center justify-center rounded-lg border border-default-200 additionalImage1Wrapper">
                    @if (!empty($data->additional_image_1))
                        <button type="button" id="btnremove_additional1" class="ml-auto bg-red-600 text-white rounded-full p-1 hover:bg-red-800">✕</button>
                        <div id="additionalImage1PreviewContainer" class="relative inline-block">
                            <img src="{{ asset($data->additional_image_1) }}" alt="Additional Image 1" class="max-w-full max-h-24 rounded-lg" id="additionalImage1Preview">
                        </div>
                    @else
                        <div class="additionalImage1Upload text-center cursor-pointer">
                            <div class="mb-2">
                                <i data-lucide="image" class="w-6 h-6 stroke-primary fill-primary/10"></i>
                            </div>
                            <h6 class="text-sm text-primary font-medium">
                                <i data-lucide="upload-cloud" class="inline-flex ms-1"></i>
                                Upload
                            </h6>
                        </div>
                    @endif

                    <input type="file" id="additionalImage1Input" class="hidden" accept="image/*" />
                    <input type="hidden" id="additionalImage1Path" value="{{ $data->additional_image_1 ?? '' }}" />
                </div>
            </div>


            <!-- Additional Image 2 -->
            <div class="additional-image-container">
                <label class="block text-sm font-medium text-default-700 mb-2">Additional Image 2</label>

                <div class="h-32 p-4 flex flex-col items-center justify-center rounded-lg border border-default-200 additionalImage2Wrapper">
                    @if (!empty($data->additional_image_2))
                        <button type="button" id="btnremove_additional2" class="ml-auto bg-red-600 text-white rounded-full p-1 hover:bg-red-800">✕</button>
                        <div id="additionalImage2PreviewContainer" class="relative inline-block">
                            <img src="{{ asset($data->additional_image_2) }}" alt="Additional Image 2" class="max-w-full max-h-24 rounded-lg" id="additionalImage2Preview">
                        </div>
                    @else
                        <div class="additionalImage2Upload text-center cursor-pointer">
                            <div class="mb-2">
                                <i data-lucide="image" class="w-6 h-6 stroke-primary fill-primary/10"></i>
                            </div>
                            <h6 class="text-sm text-primary font-medium">
                                <i data-lucide="upload-cloud" class="inline-flex ms-1"></i>
                                Upload
                            </h6>
                        </div>
                    @endif

                    <input type="file" id="additionalImage2Input" class="hidden" accept="image/*" />
                    <input type="hidden" id="additionalImage2Path" value="{{ $data->additional_image_2 ?? '' }}" />
                </div>
            </div>

        </div>

    </div>


    <div class="lg:col-span-2">
        <div class="p-6 rounded-lg border border-default-200">
            <div class="grid lg:grid-cols-2 gap-6 mb-6">
                <div class="space-y-6">
                    <div>
                        <input class="block w-full bg-transparent rounded-lg py-2.5 px-4 border border-default-200 focus:ring-transparent focus:border-default-200 dark:bg-default-50" type="text" placeholder="Product Name" value="{{ $data->name }}" id="productName">
                    </div>
                    <div>
                        <select class="block w-full bg-transparent rounded-lg py-2.5 px-4 border border-default-200 focus:ring-transparent focus:border-default-200 dark:bg-default-50" type="text" placeholder="Select Product Category" id="productCategory">
                            <option>Select Product Category</option>
                            @foreach (AllCategory() as $Cate)
                            <option value="{{ $Cate->id }}" {{ $data->menu_section_id == $Cate->id ? 'selected' : '' }} >{{ $Cate->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid lg:grid-cols-2 gap-6">
                        <div>
                            <input class="block w-full bg-transparent rounded-lg py-2.5 px-4 border border-default-200 focus:ring-transparent focus:border-default-200 dark:bg-default-50" type="text" placeholder="Selling Price" value="{{ $data->price }}" id="productPrice">
                        </div>

                        <div>
                            <input class="block w-full bg-transparent rounded-lg py-2.5 px-4 border border-default-200 focus:ring-transparent focus:border-default-200 dark:bg-default-50" type="text" placeholder="Cost Price" value="{{ $data->cost_price }}" id="productCostPrice">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="order_type" class="form-label">Order Type</label>
                        <div class="flex gap-2">
                            @php
                                $selectedOrderTypes = json_decode($data->order_type, true) ?? []; // Decode JSON from the database
                            @endphp
                            <button type="button" class="order-type-toggle py-2 px-4 {{ in_array('delivery', $selectedOrderTypes) ? 'bg-primary text-white' : 'bg-gray-200 text-gray-800' }} rounded-full hover:bg-primary hover:text-white transition-all" data-value="delivery">Delivery</button>
                            <button type="button" class="order-type-toggle py-2 px-4 {{ in_array('pickup', $selectedOrderTypes) ? 'bg-primary text-white' : 'bg-gray-200 text-gray-800' }} rounded-full hover:bg-primary hover:text-white transition-all" data-value="pickup">Pickup</button>
                            <button type="button" class="order-type-toggle py-2 px-4 {{ in_array('dine_in', $selectedOrderTypes) ? 'bg-primary text-white' : 'bg-gray-200 text-gray-800' }} rounded-full hover:bg-primary hover:text-white transition-all" data-value="dine_in">Dine-in</button>
                        </div>
                        <input type="hidden" id="orderTypeInput" name="order_type" value="{{ json_encode($selectedOrderTypes) }}"> <!-- Hidden input to store selected values -->
                    </div>

                    <div>
                        <select class="block w-full bg-transparent rounded-lg py-2.5 px-4 border border-default-200 focus:ring-transparent focus:border-default-200 dark:bg-default-50" id="productSize">
                            <option value="">Size (optional)</option>
                            <option value="full" {{ $data->size == 'full' ? 'selected' : '' }}>Full</option>
                            <option value="half" {{ $data->size == 'half' ? 'selected' : '' }}>Half</option>
                            <option value="quarter" {{ $data->size == 'quarter' ? 'selected' : '' }}>Quarter</option>
                        </select>
                    </div>

                    <div class="flex justify-between">
                        <h4 class="text-sm font-medium text-default-600">Discount</h4>
                        <div class="flex items-center gap-4">
                            <label class="block text-sm text-default-600" for="addDiscount">Add Discount</label>
                            <input type="checkbox" id="addDiscount" class="relative w-[3.25rem] h-7 bg-default-200 focus:ring-0 checked:bg-none checked:!bg-primary border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 appearance-none focus:ring-transparent before:inline-block before:w-6 before:h-6 before:bg-white before:translate-x-0 checked:before:translate-x-full before:shadow before:rounded-full before:transform before:transition before:ease-in-out before:duration-200" checked>
                        </div>
                    </div>

                    <div class="flex justify-between">
                        <h4 class="text-sm font-medium text-default-600">Expiry Date</h4>
                        <div class="flex items-center gap-4">
                            <label class="block text-sm text-default-600" for="addExpiryDate">Add Expiry Date</label>
                            <input type="checkbox" id="addExpiryDate" class="relative w-[3.25rem] h-7 bg-default-200 focus:ring-0 checked:bg-none checked:!bg-primary border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 appearance-none focus:ring-transparent before:inline-block before:w-6 before:h-6 before:bg-white before:translate-x-0 checked:before:translate-x-full before:shadow before:rounded-full before:transform before:transition before:ease-in-out before:duration-200">
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    {{-- <div>
                        <textarea class="block w-full bg-transparent rounded-lg py-2.5 px-4 border border-default-200 focus:ring-transparent focus:border-default-200 dark:bg-default-50" rows="5" placeholder="Short Description">Mexican burritos are usually made with a wheat tortilla and contain grilled meat, cheese toppings, and fresh vegetables which are sources of vitamins, proteins, fibers, minerals, and antioxidants.</textarea>
                    </div> --}}

                    <div>
                        <label class="block text-sm font-medium text-default-900 mb-2" for="desceditor">Product Description</label>
                        <div id="desceditor" class="h-36 mb-2">
                            {{ $data->description }}
                        </div>

                        <p class="text-sm text-default-600">Add a description for your product</p>
                    </div>

                    <div class="flex justify-between">
                        <h4 class="text-sm font-medium text-default-600">Return Policy</h4>
                        <div class="flex items-center gap-4">
                            <label class="block text-sm text-default-600" for="returnPolicy">Return Policy</label>
                            <input type="checkbox" id="returnPolicy" class="relative w-[3.25rem] h-7 bg-default-200 focus:ring-0 checked:bg-none checked:!bg-primary border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 appearance-none focus:ring-transparent before:inline-block before:w-6 before:h-6 before:bg-white before:translate-x-0 checked:before:translate-x-full before:shadow before:rounded-full before:transform before:transition before:ease-in-out before:duration-200">
                        </div>
                    </div>

                    <div>
                        <p class="text-xs text-default-500 mb-3">Date Added</p>
                        <div class="grid lg:grid-cols-2 gap-6">
                            <div>
                                <div class="relative">
                                    <span class="absolute top-1/2 start-2.5 -translate-y-1/2"><i data-lucide="calendar-days" class="h-4 w-4 text-default-700"></i></span>
                                    <span class="absolute top-1/2 end-2.5 -translate-y-1/2"><i data-lucide="chevron-down" class="h-4 w-4 text-default-700"></i></span>
                                    <input type="text" class="py-2.5 w-full px-9 block bg-default-100 rounded-md border-0 text-sm text-default-700 font-medium focus:border-default-200 focus:ring-0" id="datepicker-basic">
                                </div><!-- end relative -->
                            </div>

                            <div>
                                <div class="relative">
                                    <span class="absolute top-1/2 start-2.5 -translate-y-1/2"><i data-lucide="calendar-days" class="h-4 w-4 text-default-700"></i></span>
                                    <span class="absolute top-1/2 end-2.5 -translate-y-1/2"><i data-lucide="chevron-down" class="h-4 w-4 text-default-700"></i></span>
                                    <input type="text" class="py-2.5 w-full px-9 block bg-default-100 rounded-md border-0 text-sm text-default-700 font-medium focus:border-default-200 focus:ring-0" id="timepicker">
                                </div><!-- end relative -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="lg:col-span-3">
        <div class="flex flex-wrap justify-end items-center gap-4">
            <div class="flex flex-wrap items-center gap-4">
                <div class="hs-dropdown relative inline-flex [--placement:bottom-right]">
                    <button type="button" class="hs-dropdown-toggle flex items-center gap-2 font-medium text-default-700 text-sm py-2.5 px-4 rounded-md bg-default-100 transition-all">
                        Save as Draft <i data-lucide="chevron-down" class="h-4 w-4"></i>
                    </button><!-- end dropdown button -->

                    <div class="hs-dropdown-menu hs-dropdown-open:opacity-100 min-w-[200px] transition-[opacity,margin] mt-4 opacity-0 hidden z-20 bg-white shadow-[rgba(17,_17,_26,_0.1)_0px_0px_16px] rounded-lg border border-default-100 p-1.5 dark:bg-default-50">
                        <ul class="flex flex-col gap-1">
                            <li><a class="flex items-center gap-3 font-normal text-default-600 py-2 px-3 transition-all hover:text-default-700 hover:bg-default-100 rounded" href="javascript:void(0)">Publish</a></li>
                            <li><a class="flex items-center gap-3 font-normal text-default-600 py-2 px-3 transition-all hover:text-default-700 hover:bg-default-100 rounded" href="javascript:void(0)">Save as Darft</a></li>
                            <li><a class="flex items-center gap-3 font-normal text-default-600 py-2 px-3 transition-all hover:text-default-700 hover:bg-default-100 rounded" href="javascript:void(0)">Discard</a></li>
                        </ul><!-- end dropdown items -->
                    </div><!-- end dropdown menu -->
                </div>
                <button class="py-2.5 px-4 inline-flex rounded-lg text-sm font-medium bg-primary text-white transition-all hover:bg-primary-500" id="saveProductBtn">Save</button>
                {{-- <button class="py-2.5 px-4 inline-flex rounded-lg text-sm font-medium bg-primary text-white transition-all hover:bg-primary-500">Save</button> --}}
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
{{-- @vite(['resources/js/admin-product-add.js']) --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

<script type="module">
    import Quill from 'https://cdn.skypack.dev/quill';
    import flatpickr from 'https://cdn.skypack.dev/flatpickr';

    const quill = new Quill('#desceditor', {
        theme: 'snow'
    });

        // Replace HTML with plain text content in the editor on load
        quill.setText($('#desceditor').text());

    flatpickr("#datepicker-basic", { defaultDate: new Date() });

    flatpickr("#timepicker", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        defaultDate: "13:45"
    });
    var MainImagae;
    var AdditionalImage1;
    var AdditionalImage2;
    var AdditionalImage= [];
    $(document).on('click','.mainImageUpload', function () {
        $('#mainImageInput').click();
    });


        function removebtnfunc(){
            $('#mainImagePreviewContainer').remove();

                                MainImagae = null;

                                // Restore original upload UI
                                $('.mainImageWrapper').html(`
                                    <div class="mainImageUpload btnuploadimage text-center cursor-pointer">
                                        <div class="mb-4">
                                            <i data-lucide="image" class="w-10 h-10 stroke-primary fill-primary/10"></i>
                                        </div>
                                        <h5 class="text-base text-primary font-medium mb-2">
                                            <i data-lucide="upload-cloud" class="inline-flex ms-2"></i>
                                            Upload Main Image
                                        </h5>
                                        <p class="text-sm text-default-600 mb-2">Upload a cover image for your product.</p>
                                        <p class="text-sm text-default-600">
                                            File Format
                                            <span class="text-default-800">jpeg, png</span>
                                            Recommended Size
                                            <span class="text-default-800">600x600 (1:1)</span>
                                        </p>
                                    </div>
                                    <input type="file" id="mainImageInput" accept="image/*" class="hidden">
                                `);

                                // Re-bind change event to the new input
                                bindMainImageUpload();
                                // lucide.createIcons(); // Re-init icons
        }
        $(document).on('click','#btnremoveimage', function () {
        removebtnfunc();
    });
        function changeeventimage(file){
            MainImagae = file;
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const previewUrl = e.target.result;

                    // If preview already exists, just update src
                    if ($('#mainImagePreviewContainer').length > 0) {
                        $('#mainImagePreview').attr('src', previewUrl);
                    } else {
                        // Create preview container with remove button
                        const previewContainer = $('<div>', {
                            id: 'mainImagePreviewContainer',
                            class: 'relative inline-block'
                        });

                        const img = $('<img>', {
                            id: 'mainImagePreview',
                            src: previewUrl,
                            class: 'max-w-full max-h-96 rounded-lg'
                        });

                        const removeBtn = $('<button>', {
                            type: 'button',
                            text: '✕',
                            class: 'absolute top-0 right-0 bg-red-600 text-white rounded-full p-1 hover:bg-red-800',
                            click: function () {
                                // Remove preview
                               removebtnfunc();
                            }
                        });

                        previewContainer.append(img).append(removeBtn);

                        // Replace upload UI with preview container
                        $('.mainImageUpload').replaceWith(previewContainer);
                    }
                };

                reader.readAsDataURL(file);
            }

            // Reset input so same file can be reselected
            $(this).val('');

        }
         // Main image preview update
         $(document).on('change','#mainImageInput', function () {
            const file = this.files[0];
            changeeventimage(file);

        });
        function bindMainImageUpload() {
            $('#mainImageInput').on('change', function () {
                const file = this.files[0];
                changeeventimage(file);

            });
        }
    $(document).ready(function () {
        const orderTypeInput = $('#orderTypeInput');
        let selectedOrderTypes = JSON.parse(orderTypeInput.val()) || [];

        $('.order-type-toggle').on('click', function () {
            const value = $(this).data('value');

            if (selectedOrderTypes.includes(value)) {
                // Remove the value if already selected
                selectedOrderTypes = selectedOrderTypes.filter(type => type !== value);
                $(this).removeClass('bg-primary text-white').addClass('bg-gray-200 text-gray-800');
            } else {
                // Add the value if not selected
                selectedOrderTypes.push(value);
                $(this).removeClass('bg-gray-200 text-gray-800').addClass('bg-primary text-white');
            }

            // Update the hidden input field
            orderTypeInput.val(JSON.stringify(selectedOrderTypes));
        });




        // Additional Image 1 Preview Update
        // ========= Reusable builders for Additional Images =========
function restoreAdditionalUploadUI(which) {
    const wrapperClass = `.additionalImage${which}Wrapper`;
    const uploadHtml = `
        <div class="additionalImage${which}Upload text-center cursor-pointer">
            <div class="mb-2">
                <i data-lucide="image" class="w-6 h-6 stroke-primary fill-primary/10"></i>
            </div>
            <h6 class="text-sm text-primary font-medium">
                <i data-lucide="upload-cloud" class="inline-flex ms-1"></i>
                Upload
            </h6>
        </div>
        <input type="file" id="additionalImage${which}Input" class="hidden" accept="image/*" />
        <input type="hidden" id="additionalImage${which}Path" value="" />
    `;

    $(wrapperClass).html(uploadHtml);
    if (window.lucide && typeof lucide.createIcons === 'function') {
        lucide.createIcons();
    }
}

function createAdditionalPreview(which, previewUrl) {
    const containerId = `additionalImage${which}PreviewContainer`;
    const imgId = `additionalImage${which}Preview`;
    const removeBtnId = `btnremove_additional${which}`;

    const previewContainer = $(`
        <div id="${containerId}" class="relative inline-block">
            <img id="${imgId}" src="${previewUrl}" class="max-w-full max-h-24 rounded-lg" />
            <button type="button" id="${removeBtnId}" class="absolute -top-2 -right-2 bg-red-600 text-white rounded-full p-1 hover:bg-red-800">✕</button>
        </div>
    `);

    // Replace the upload UI with preview container
    $(`.additionalImage${which}Upload`).replaceWith(previewContainer);
}

function handleAdditionalChange(which, file) {
    if (!file) return;

    // keep your separate variables in sync for formData
    if (which === 1) {
        AdditionalImage1 = file;
        // Clearing the old path because a new file is chosen
        $('#additionalImage1Path').val('');
    } else if (which === 2) {
        AdditionalImage2 = file;
        $('#additionalImage2Path').val('');
    }

    if (file.type && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function (e) {
            const previewUrl = e.target.result;

            if ($(`#additionalImage${which}PreviewContainer`).length > 0) {
                $(`#additionalImage${which}Preview`).attr('src', previewUrl);
            } else {
                createAdditionalPreview(which, previewUrl);
            }
        };
        reader.readAsDataURL(file);
    }
}

// ========= Event delegation for dynamic elements =========

// Open file pickers
$(document).on('click', '.additionalImage1Upload', function () {
    $('#additionalImage1Input').click();
});
$(document).on('click', '.additionalImage2Upload', function () {
    $('#additionalImage2Input').click();
});

// On change, render preview
$(document).on('change', '#additionalImage1Input', function () {
    const file = this.files[0];
    handleAdditionalChange(1, file);
    $(this).val('');
});
$(document).on('change', '#additionalImage2Input', function () {
    const file = this.files[0];
    handleAdditionalChange(2, file);
    $(this).val('');
});

// Remove buttons (existing image or newly selected)
$(document).on('click', '#btnremove_additional1', function () {
    // clear file var & clear old path so server knows it's removed
    AdditionalImage1 = null;
    $('#additionalImage1Path').val('');
    // restore original UI
    restoreAdditionalUploadUI(1);
});
$(document).on('click', '#btnremove_additional2', function () {
    AdditionalImage2 = null;
    $('#additionalImage2Path').val('');
    restoreAdditionalUploadUI(2);
});
        // $('.additionalImage1Upload').click(function () {
        //     $('#additionalImage1Input').click();
        // });
        // $('#additionalImage1Input').on('change', function () {
        //     const file = this.files[0];
        //     AdditionalImage1 = file;
        //     if (file && file.type.startsWith('image/')) {
        //         const reader = new FileReader();
        //         reader.onload = function (e) {
        //             $('#additionalImage1Preview').attr('src', e.target.result);
        //         };
        //         reader.readAsDataURL(file);
        //     }
        //     $(this).val('');
        // });
        $('#saveProductBtn').on('click', function (e) {
    e.preventDefault();
    const id = $('#productId').val();
    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('id', id);
    formData.append('name', $('#productName').val());
    formData.append('menu_section_id', $('#productCategory').val());





    // Ensure Cost Price and Selling Price are integers
    const price = parseInt($('#productPrice').val(), 10);
    const costPrice = parseInt($('#productCostPrice').val(), 10);

    if (isNaN(price) || isNaN(costPrice)) {
        alert('Cost Price and Selling Price must be valid integers.');
        return;
    }

    formData.append('price', price);
    formData.append('cost_price', costPrice);

    formData.append('order_type', $('#orderTypeInput').val()); // Send selected order types
    const size = $('#productSize').val();
    if (size) formData.append('size', size);
    formData.append('description', quill.getText().trim());
    formData.append('additional_image_1_path', $('#additionalImage1Path').val());
    formData.append('additional_image_2_path', $('#additionalImage2Path').val());

    if (MainImagae) {
        formData.append('image', MainImagae);
    }
    if (AdditionalImage1) {
        formData.append('additional_image_1', AdditionalImage1);
    }
    if (AdditionalImage2) {
        formData.append('additional_image_2', AdditionalImage2);
    }

    $.ajax({
        url: "{{ route('saveProduct') }}",
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function () {
            window.location.href = '{{ route('second', ['products', 'list']) }}';
        },
        error: function (xhr) {
            console.error(xhr.responseText);
            alert('Failed to save product. Please check your inputs.');
        }
    });
});

    });
</script>


@endsection
