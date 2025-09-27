/*
Template Name: Yum - Multipurpose Food Tailwind CSS Template
Version: 1.0
Author: coderthemes
Email: support@coderthemes.com
*/

import Quill from 'quill';
import flatpickr from 'flatpickr';

const quill = new Quill('#editor', {
    theme: 'snow'
});

flatpickr('#datepicker-basic', { defaultDate: new Date() });
flatpickr('#timepicker', {
    enableTime: true,
    noCalendar: true,
    dateFormat: 'H:i',
    defaultDate: '13:45'
});

document.addEventListener('DOMContentLoaded', () => {
    const orderTypeInput = document.getElementById('orderTypeInput');
    const sizeInput = document.getElementById('sizeInput');
    const sizePricesInput = document.getElementById('sizePricesInput');
    let selectedOrderTypes = [];
    let selectedSizes = [];
    let sizePrices = {};
    let mainImageFile = null;
    let additionalImage1File = null;
    let additionalImage2File = null;

    // Handle Order Type Toggle Buttons
    document.querySelectorAll('.order-type-toggle').forEach(button => {
        button.addEventListener('click', () => {
            const value = button.getAttribute('data-value');

            if (selectedOrderTypes.includes(value)) {
                // Remove the value if already selected
                selectedOrderTypes = selectedOrderTypes.filter(type => type !== value);
                button.classList.remove('bg-primary', 'text-white');
                button.classList.add('bg-gray-200', 'text-gray-800');
            } else {
                // Add the value if not selected
                selectedOrderTypes.push(value);
                button.classList.remove('bg-gray-200', 'text-gray-800');
                button.classList.add('bg-primary', 'text-white');
            }

            // Update the hidden input field
            orderTypeInput.value = JSON.stringify(selectedOrderTypes);
        });
    });

    // Handle Size Toggle Buttons
    document.querySelectorAll('.size-toggle').forEach(button => {
        button.addEventListener('click', () => {
            const value = button.getAttribute('data-value');

            if (selectedSizes.includes(value)) {
                // Remove the value if already selected
                selectedSizes = selectedSizes.filter(size => size !== value);
                button.classList.remove('bg-primary', 'text-white');
                button.classList.add('bg-gray-200', 'text-gray-800');
                delete sizePrices[value];
            } else {
                // Add the value if not selected
                selectedSizes.push(value);
                button.classList.remove('bg-gray-200', 'text-gray-800');
                button.classList.add('bg-primary', 'text-white');
                // Set default price to main product price
                const mainPrice = document.getElementById('productPrice').value || 0;
                sizePrices[value] = mainPrice;
            }

            // Update the hidden input fields
            sizeInput.value = JSON.stringify(selectedSizes);
            sizePricesInput.value = JSON.stringify(selectedSizes.map(size => sizePrices[size] || 0));
            
            // Update price inputs UI
            updateSizePriceInputs();
        });
    });

    // Function to update size price inputs UI
    function updateSizePriceInputs() {
        const container = document.getElementById('sizePriceInputs');
        if (selectedSizes.length === 0) {
            container.classList.add('hidden');
            return;
        }

        container.classList.remove('hidden');
        container.innerHTML = '<h5 class="text-sm font-medium text-default-700 mb-2">Set Prices for Selected Sizes:</h5>';

        selectedSizes.forEach(size => {
            const priceInputDiv = document.createElement('div');
            priceInputDiv.className = 'flex items-center gap-3';
            priceInputDiv.innerHTML = `
                <label class="text-sm text-default-600 w-20">${size.charAt(0).toUpperCase() + size.slice(1)}:</label>
                <input type="number" 
                       class="flex-1 bg-transparent rounded-lg py-2 px-3 border border-default-200 focus:ring-transparent focus:border-default-200 dark:bg-default-50" 
                       placeholder="Price" 
                       value="${sizePrices[size] || ''}"
                       data-size="${size}"
                       step="0.01"
                       min="0">
            `;
            container.appendChild(priceInputDiv);
        });

        // Add event listeners to price inputs
        container.querySelectorAll('input[data-size]').forEach(input => {
            input.addEventListener('input', (e) => {
                const size = e.target.getAttribute('data-size');
                sizePrices[size] = parseFloat(e.target.value) || 0;
                sizePricesInput.value = JSON.stringify(selectedSizes.map(size => sizePrices[size] || 0));
            });
        });
    }

    // Helper to create a removable preview (X) like edit page
    function createMainPreview(previewUrl) {
        const previewContainer = $('<div>', {
            id: 'mainImagePreviewContainer',
            class: 'relative inline-block w-full'
        });
        const imgEl = $('<img>', {
            id: 'mainImagePreview',
            src: previewUrl,
            class: 'max-w-full max-h-96 rounded-lg'
        });
        const removeBtn = $('<button>', {
            type: 'button',
            id: 'btnremoveimage',
            class: 'absolute -top-2 -right-2 bg-red-600 text-white rounded-full p-1 hover:bg-red-800',
            text: '✕'
        });
        removeBtn.on('click', function () {
            // Clear file and restore upload UI
            mainImageFile = null;
            $('#mainImagePreviewContainer').remove();
            const uploadUI = $(
                '<div class="h-96 p-6 flex flex-col items-center justify-center rounded-lg border border-default-200 mb-4 mainImageUpload cursor-pointer text-center">\
                    <div class="mb-4">\
                        <i data-lucide="image" class="w-10 h-10 stroke-primary fill-primary/10"></i>\
                    </div>\
                    <h5 class="text-base text-primary font-medium mb-2">\
                        <i data-lucide="upload-cloud" class="inline-flex ms-2"></i>\
                        Upload Image\
                    </h5>\
                    <p class="text-sm text-default-600 mb-2">Upload a cover image for your product.</p>\
                    <p class="text-sm text-default-600">File Format <span class="text-default-800">jpeg, png</span> Recommened Size <span class="text-default-800">600x600 (1:1)</span></p>\
                </div>'
            );
            $('#mainImageInput').after(uploadUI);
            uploadUI.on('click', function () { $('#mainImageInput').click(); });
        });
        previewContainer.append(imgEl).append(removeBtn);
        $('.mainImageUpload').replaceWith(previewContainer);
    }

    function createAdditionalPreview(which, previewUrl) {
        const containerId = `additionalImage${which}PreviewContainer`;
        const imgId = `additionalImage${which}Preview`;
        const removeBtnId = `btnremove_additional${which}`;
        const previewContainer = $(`
            <div id="${containerId}" class="relative inline-block">\
                <img id="${imgId}" src="${previewUrl}" class="max-w-full max-h-24 rounded-lg" />\
                <button type="button" id="${removeBtnId}" class="absolute -top-2 -right-2 bg-red-600 text-white rounded-full p-1 hover:bg-red-800">✕</button>\
            </div>
        `);
        previewContainer.on('click', `#${removeBtnId}`, function () {
            if (which === 1) { additionalImage1File = null; }
            if (which === 2) { additionalImage2File = null; }
            $(`#${containerId}`).remove();
            const uploadSelector = `.additionalImage${which}Upload`;
            const uploadUI = $(`
                <div class="${uploadSelector.replace('.', '')} text-center cursor-pointer">\
                    <div class="mb-2">\
                        <i data-lucide=\"image\" class=\"w-6 h-6 stroke-primary fill-primary/10\"></i>\
                    </div>\
                    <h6 class="text-sm text-primary font-medium">\
                        <i data-lucide=\"upload-cloud\" class=\"inline-flex ms-1\"></i>\
                        Upload\
                    </h6>\
                </div>
            `);
            $(`#additionalImage${which}Input`).closest('.border').find(uploadSelector).remove();
            $(`#additionalImage${which}Input`).closest('.border').prepend(uploadUI);
            uploadUI.on('click', function () { $(`#additionalImage${which}Input`).click(); });
        });
        $(`.additionalImage${which}Upload`).replaceWith(previewContainer);
    }

    // Main Image Upload
    $('.mainImageUpload').on('click', function () {
        $('#mainImageInput').click();
    });

    $('#mainImageInput').on('change', function () {
        const file = this.files && this.files[0];
        if (file && file.type.startsWith('image/')) {
            mainImageFile = file; // Persist the file for saving
            const reader = new FileReader();
            reader.onload = function (e) {
                createMainPreview(e.target.result);
            };
            reader.readAsDataURL(file);
        }
        $(this).val(''); // Allow re-selecting the same file
    });

    // Additional Image 1 Upload
    $('.additionalImage1Upload').on('click', function () {
        $('#additionalImage1Input').click();
    });

    $('#additionalImage1Input').on('change', function () {
        const file = this.files && this.files[0];
        if (file && file.type.startsWith('image/')) {
            additionalImage1File = file;
            const reader = new FileReader();
            reader.onload = function (e) {
                createAdditionalPreview(1, e.target.result);
            };
            reader.readAsDataURL(file);
        }
        $(this).val('');
    });

    // Additional Image 2 Upload
    $('.additionalImage2Upload').on('click', function () {
        $('#additionalImage2Input').click();
    });

    $('#additionalImage2Input').on('change', function () {
        const file = this.files && this.files[0];
        if (file && file.type.startsWith('image/')) {
            additionalImage2File = file;
            const reader = new FileReader();
            reader.onload = function (e) {
                createAdditionalPreview(2, e.target.result);
            };
            reader.readAsDataURL(file);
        }
        $(this).val('');
    });


    // Save Product Button
    $('#saveProductBtn').on('click', function (e) {
        e.preventDefault();
        const formData = new FormData();
        formData.append('_token', $('#csrfToken').val());
        formData.append('name', $('#productName').val());
        formData.append('menu_section_id', $('#productCategory').val());

        const price = parseInt($('#productPrice').val(), 10);
        const costPrice = parseInt($('#productCostPrice').val(), 10);

        if (isNaN(price) || isNaN(costPrice)) {
            alert('Cost Price and Selling Price must be valid integers.');
            return;
        }
        const menuSectionId = $('#productCategory').val();

if (!menuSectionId || menuSectionId === "Select Product Category") {
    alert('Please select a valid product category.');
    return;
}

formData.append('menu_section_id', menuSectionId);

        formData.append('price', price);
        if (isNaN(costPrice)) {
            alert('Cost Price must be a valid number.');
            return;
        }

        formData.append('cost_price', costPrice);

        const orderTypeInputValue = $('#orderTypeInput').val();
        if (!orderTypeInputValue || JSON.parse(orderTypeInputValue).length === 0) {
            alert('Please select at least one order type.');
            return;
        }
        formData.append('order_type', orderTypeInputValue);
//         const orderTypeInputValue = $('#orderTypeInput').val();
// let orderTypeArray = [];

// try {
//     orderTypeArray = JSON.parse(orderTypeInputValue);
// } catch (error) {
//     console.error('Failed to parse order type input:', error);
//     alert('Invalid order type selection. Please try again.');
//     return;
// }

// if (!orderTypeArray || orderTypeArray.length === 0) {
//     alert('Please select at least one order type.');
//     return;
// }

// orderTypeArray.forEach(type => {
//     formData.append('order_type', type); // Append each value as part of an array
// });

        // Handle size data
        const sizeData = $('#sizeInput').val();
        const sizePricesData = $('#sizePricesInput').val();
        if (sizeData && sizeData !== '[]') {
            formData.append('size', sizeData);
            formData.append('size_prices', sizePricesData);
        }
        formData.append('description', quill.getText().trim());

        // Append image files to FormData
        if (mainImageFile) {
            formData.append('image', mainImageFile);
        }
        if (additionalImage1File) {
            formData.append('additional_image_1', additionalImage1File);
        }
        if (additionalImage2File) {
            formData.append('additional_image_2', additionalImage2File);
        }
        for (let [key, value] of formData.entries()) {
            console.log(`${key}: ${value}`);
        }
        $.ajax({
            url: '/products-save',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function () {
                window.location.href = '/products/list';
            },
            // error: function (xhr) {
            //     console.error(xhr.responseText);
            //     alert('Failed to save product. Please check your inputs.');
            // }
            error: function (xhr) {
                console.error('Error Response:', xhr.responseText);
                const response = JSON.parse(xhr.responseText);
                if (response.errors) {
                    for (const [field, messages] of Object.entries(response.errors)) {
                        alert(`${field}: ${messages.join(', ')}`);
                    }
                } else {
                    alert('Failed to save product. Please check your inputs.');
                }
            }
        });
    });
});
