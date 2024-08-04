<?php
ob_start();
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
?>
<script>
         document.addEventListener('DOMContentLoaded', function () {
                const showMoreBtn = document.getElementById('show-more-btn');
                const photoItems = document.querySelectorAll('.photo-item');
                const accordionTitles = document.querySelectorAll('.accordion-title');
                const cancellationButtons = document.querySelectorAll('.cancellation-button');
     const itineraryHeaders = document.querySelectorAll('.itinerary-header');
    const itinerariesCount = document.querySelectorAll('.itinerary-container').length;

    itineraryHeaders.forEach(header => {
        header.addEventListener('click', function () {
            const index = this.getAttribute('data-index');
            const content = this.nextElementSibling;
            const allContents = document.querySelectorAll('.itinerary-content');

            if (itinerariesCount > 1) {
                allContents.forEach((item, i) => {
                    if (i != index) {
                        item.classList.add('hidden');
                    }
                });
                content.classList.toggle('hidden');
            }
        });
    });

    // Automatically open the first itinerary if there's only one
    if (itinerariesCount === 1) {
        document.querySelector('.itinerary-content').classList.remove('hidden');
    }
                if (showMoreBtn) {
                    showMoreBtn.addEventListener('click', function () {
                        photoItems.forEach((item, index) => {
                            if (index >= 6) {
                                item.classList.toggle('hidden');
                            }
                        });

                        showMoreBtn.innerText = showMoreBtn.innerText === 'Show More' ? 'Show Less' : 'Show More';
                    });
                }

                accordionTitles.forEach(title => {
                    title.addEventListener('click', function () {
                        const content = this.nextElementSibling;
                        content.classList.toggle('hidden');
                        this.querySelector('.accordion-icon').innerText = content.classList.contains('hidden') ? '+' : '-';
                    });
                });

                cancellationButtons.forEach(button => {
                    button.addEventListener('click', function () {
                        const itineraryCancellation = this.getAttribute('data-cancellation');

                        const iContainer = jQuery(this).closest('.itinerary-container');
                        const cancellationModal = iContainer.find('.cancellationModal');

                        const cancellationContent = cancellationModal.find('.cancellationContent');
                        cancellationContent.innerHTML = itineraryCancellation;

                        cancellationModal.removeClass('hidden');
                    });
                });

                jQuery('.closeModalBtn').on('click', function () {
                    const iContainer = jQuery(this).closest('.itinerary-container');

                    const cancellationModal = iContainer.find('.cancellationModal');

                    cancellationModal.addClass('hidden');
                });
            });
document.addEventListener('DOMContentLoaded', function () {

    const steps = ['step1', 'step2', 'step3', 'step4', 'step5'];
    let currentStep = 0;
    let personCount = 0;

    const showStep = (step, itineraryId) => {
        const itineraryContainer = jQuery('.itinerary-container[data-id="' + itineraryId + '"]');
        steps.forEach((s, i) => {
            if (i === step) {
                itineraryContainer.find('.' + s).removeClass('hidden');
            } else {
                itineraryContainer.find('.' + s).addClass('hidden');
            }
        });
    };

    const updatePricing = (itineraryId) => {
    const itineraryContainer = jQuery('.itinerary-container[data-id="' + itineraryId + '"]');
    const selectedFacilities = itineraryContainer.find('.facilities-select').val();
    const selectedDate = itineraryContainer.find('.date-picker-container').find('.flatpickr-input').val();
    const selectedTimeSlot = itineraryContainer.find('.time-slot-select').val();
    
    // Explicitly set personCount to 1 if it is not valid
    let personCount = parseInt(itineraryContainer.find('.person-count').text());
    if (isNaN(personCount) || personCount <= 0) {
        personCount = 1;
    }

    const step4button = itineraryContainer.find('.nextToStep4');

    jQuery.ajax({
        url: '<?php echo admin_url('admin-ajax.php'); ?>',
        type: 'POST',
        data: {
            action: 'get_latest_pricing',
            itinerary_id: itineraryId,
            person_count: personCount,
            facilities: selectedFacilities,
            date: selectedDate,
            time_slot: selectedTimeSlot,
        },
        success: function (response) {
            if (response.success) {
                const error = response.data.error ?? null;
                const price = response.data.totalPrice ? response.data.totalPrice + ' €' : null;

                if (error || !price) {
                    step4button.attr('disabled', true);
                } else {
                    step4button.attr('disabled', false);
                }

                itineraryContainer.find('.booking-price').text(price ?? error ?? 'Error');
            } else {
                console.log('Error: ' + response.data);
            }
        },
        error: function (error) {
            console.log('AJAX Error: ', error);
        }
    });
};


    const checkout = (itineraryId, customerDetails) => {
        const itineraryContainer = jQuery('.itinerary-container[data-id="' + itineraryId + '"]');
        const selectedFacilities = itineraryContainer.find('.facilities-select').val();
        const selectedDate = itineraryContainer.find('.date-picker-container').find('.flatpickr-input').val();
        const selectedTimeSlot = itineraryContainer.find('.time-slot-select').val();
        const personCount = parseInt(itineraryContainer.find('.person-count').text());

        jQuery.ajax({
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            type: 'POST',
            data: {
                action: 'checkout',
                itinerary_id: itineraryId,
                person_count: personCount,
                facilities: selectedFacilities,
                date: selectedDate,
                time_slot: selectedTimeSlot,
                customer_details: customerDetails
            },
            success: function (response) {
                if (response.success) {
                   // console.log('Success: ', response);
                    window.location.href = response.data.paymentUrl;
                } else {
                    console.log('Error: ' + response.data);
                }
            },
            error: function (error) {
                console.log('AJAX Error: ', error);
            }
        });
    };

const fetchAvailability = (itineraryId, startDate, endDate, calendarInstance) => {
    // Ensure start date is today or later
    const today = new Date();
    if (startDate < today) {
        startDate = today;
    }

    const formattedStartDate = startDate.toISOString().split('T')[0];
    const formattedEndDate = endDate.toISOString().split('T')[0];

    jQuery.ajax({
        url: '<?php echo admin_url('admin-ajax.php'); ?>',
        type: 'POST',
        data: {
            action: 'get_availability',
            itinerary_id: itineraryId,
            start_date: formattedStartDate,
            end_date: formattedEndDate,
        },
        success: function (response) {
            if (response.success && response.data && response.data.dates) {
                let availableDates = [];
                let timeSlots = {};

                Object.entries(response.data.dates).forEach(function ([date, dateInfo]) {
                    if (dateInfo && typeof dateInfo === "object" && dateInfo.available) {
                        availableDates.push(date);
                        timeSlots[date] = dateInfo.availabilityTimes;
                    }
                });

                calendarInstance.set('enable', availableDates);

                calendarInstance.config.onChange.push(function(selectedDates, dateStr, instance) {
                    const timeSlotSelect = jQuery(instance.element).closest('.itinerary-container').find('.time-slot-select');
                    if (timeSlots[dateStr]) {
                        timeSlotSelect.html('<option value="">Επιλέξτε ώρα</option>');
                        timeSlots[dateStr].forEach(function (slot) {
                            timeSlotSelect.append('<option value="' + slot.timeId + '">' + slot.startTime + '</option>');
                        });
                        timeSlotSelect.closest('.time-slot-container').removeClass('hidden');
                    } else {
                        timeSlotSelect.closest('.time-slot-container').addClass('hidden');
                    }
                });
            } else {
                console.log('Error: ', response.data);
            }
        },
        error: function (error) {
            console.log('AJAX Error: ', error);
        }
    });
};





jQuery('.bookNowBtn').on('click', (e) => {
    let activityId = jQuery(e.target).closest('article').data('id');
    let itineraryId = jQuery(e.target).data('id');

    const datePickerContainer = jQuery(e.target).parent().find('.date-picker-container');
    const timeSlotContainer = jQuery(e.target).parent().find('.time-slot-container');
    const facilitiesContainer = jQuery(e.target).parent().find('.facilities-container');

    const bookingModal = jQuery(e.target).parent().find('.bookingModal');

    datePickerContainer.addClass('hidden');
    timeSlotContainer.addClass('hidden');
    facilitiesContainer.addClass('hidden');

    jQuery.ajax({
        url: '<?php echo admin_url('admin-ajax.php'); ?>',
        type: 'POST',
        data: {
            action: 'get_availability',
            itinerary_id: itineraryId,
            activity_id: activityId,
        },
        success: function (response) {
            if (response.success && response.data && response.data.dates) {
                let availableDates = [];
                let timeSlots = {};
                let facilities = response.data.facilities;

                Object.entries(response.data.dates).forEach(function ([date, dateInfo]) {
                    if (dateInfo && typeof dateInfo === "object" && dateInfo.available) {
                        availableDates.push(date);
                        timeSlots[date] = dateInfo.availabilityTimes;
                    }
                });

                const calendarInstance = jQuery('#datetime-' + itineraryId).flatpickr({
    enableTime: false,
    dateFormat: "Y-m-d",
    defaultDate: new Date(),  // Set default date to today
    enable: availableDates,
    onChange: function(selectedDates, dateStr, instance) {
        const timeSlotSelect = jQuery(instance.element).closest('.itinerary-container').find('.time-slot-select');
        if (timeSlots[dateStr]) {
            timeSlotSelect.html('<option value="">Επιλέξτε ώρα</option>');
            timeSlots[dateStr].forEach(function (slot) {
                timeSlotSelect.append('<option value="' + slot.timeId + '">' + slot.startTime + '</option>');
            });
            timeSlotSelect.closest('.time-slot-container').removeClass('hidden');
        } else {
            timeSlotSelect.closest('.time-slot-container').addClass('hidden');
        }
    },
    onMonthChange: function(selectedDates, dateStr, instance) {
        const itineraryId = instance.element.closest('.itinerary-container').dataset.id;
        let startDate = new Date(instance.currentYear, instance.currentMonth, 1);  // Always set start date to the 1st of the month
        const endDate = new Date(instance.currentYear, instance.currentMonth + 1, 0);

        // Ensure start date is today or later
        const today = new Date();
        if (startDate < today) {
            startDate = today;
        }

        fetchAvailability(itineraryId, startDate, endDate, instance);
    }
});



                datePickerContainer.removeClass('hidden');

                facilities.forEach(function (facility) {
                    facilitiesContainer.find('.facilities-select').append('<option value="' + facility.id + '">' + facility.title + '</option>');
                });

                facilitiesContainer.removeClass('hidden');

                facilitiesContainer.find('.facilities-select').on('change', function () {
                    updatePricing(itineraryId);
                });
            } else {
                console.log('Error: ', response.data);
            }
        },
        error: function (error) {
            console.log('AJAX Error: ', error);
        }
    });

    bookingModal.removeClass('hidden');
    showStep(0, itineraryId);
});


    jQuery('.closeBookingModalBtn').on('click', (e) => {
        jQuery(e.target).closest('.bookingModal').addClass('hidden');
    });

    jQuery('.nextToStep2').on('click', (e) => {
        const itineraryContainer = jQuery(e.target).closest('.itinerary-container');
        const itineraryId = itineraryContainer.data('id');

        const selectedDate = itineraryContainer.find('.flatpickr-input').val();

        if (!selectedDate) {
            alert('Παρακαλώ επιλέξτε μια ημερομηνία ή επιστρέψτε πίσω.');
            return;
        }

        currentStep = 1;
        showStep(currentStep, itineraryId);
    });

    jQuery('.nextToStep3').on('click', (e) => {
        const itineraryContainer = jQuery(e.target).closest('.itinerary-container');
        const itineraryId = itineraryContainer.data('id');

        const selectedTimeSlot = itineraryContainer.find('.time-slot-select').val();

        if (!selectedTimeSlot) {
            alert('Παρακαλώ επιλέξτε ώρα ή επιστρέψτε πίσω.');
            return;
        }

        currentStep = 2;
        showStep(currentStep, itineraryId);
    });

    jQuery('.nextToStep4').on('click', (e) => {
        const itineraryContainer = jQuery(e.target).closest('.itinerary-container');
        const itineraryId = itineraryContainer.data('id');

        currentStep = 3;
        showStep(currentStep, itineraryId);
    });

    jQuery('.nextToStep5').on('click', (e) => {
       const itineraryContainer = jQuery(e.target).closest('.itinerary-container');
        const itineraryId = itineraryContainer.data('id');

        // Fetch and display summary details
        const selectedDate = itineraryContainer.find('.flatpickr-input').val();
        const selectedTimeSlot = itineraryContainer.find('.time-slot-select option:selected').text();
        const personCount = itineraryContainer.find('.person-count').text();
        const selectedFacilities = itineraryContainer.find('.facilities-select option:selected').map(function() {
            return jQuery(this).text();
        }).get().join(', ');

        const customerName = itineraryContainer.find('input[name="customer_name"]').val();
        const customerSurname = itineraryContainer.find('input[name="customer_surname"]').val();
        const customerEmail = itineraryContainer.find('input[name="customer_email"]').val();
        const customerPhone = itineraryContainer.find('input[name="customer_phone"]').val();

        jQuery('#summary-date').text(selectedDate);
        jQuery('#summary-time').text(selectedTimeSlot);
        jQuery('#summary-persons').text(personCount);
        jQuery('#summary-facilities').text(selectedFacilities || 'Καμία');
        jQuery('#summary-name').text(customerName);
        jQuery('#summary-surname').text(customerSurname);
        jQuery('#summary-email').text(customerEmail);
        jQuery('#summary-phone').text(customerPhone);

        if (!customerName || !customerSurname || !customerEmail || !customerPhone) {
            alert('Παρακαλώ συμπληρώστε όλα τα στοιχεία σας');
            return;
        }

        currentStep = 4;
        showStep(currentStep, itineraryId);
    });

    jQuery('.backToStep1').on('click', (e) => {
        const itineraryId = jQuery(e.target).closest('.itinerary-container').data('id');

        currentStep = 0;
        showStep(currentStep, itineraryId);
    });

    jQuery('.backToStep2').on('click', (e) => {
        const itineraryId = jQuery(e.target).closest('.itinerary-container').data('id');

        currentStep = 1;
        showStep(currentStep, itineraryId);
    });

    jQuery('.backToStep3').on('click', (e) => {
        const itineraryId = jQuery(e.target).closest('.itinerary-container').data('id');

        currentStep = 2;
        showStep(currentStep, itineraryId);
    });

    jQuery('.backToStep4').on('click', (e) => {
        const itineraryId = jQuery(e.target).closest('.itinerary-container').data('id');

        currentStep = 3;
        showStep(currentStep, itineraryId);
    });

    jQuery('.confirmBooking').on('click', (e) => {
        const itineraryContainer = jQuery(e.target).closest('.itinerary-container');
        const itineraryId = itineraryContainer.data('id');

        const customerDetails = {
            name: itineraryContainer.find('input[name="customer_name"]').val(),
            surname: itineraryContainer.find('input[name="customer_surname"]').val(),
            email: itineraryContainer.find('input[name="customer_email"]').val(),
            phone: itineraryContainer.find('input[name="customer_phone"]').val()
        };

        itineraryContainer.find('.bookingModal').addClass('hidden');

        checkout(itineraryId, customerDetails);
    });

    jQuery('.increase-btn').on('click', (e) => {
        const itineraryContainer = jQuery(e.target).closest('.itinerary-container');
        const itineraryId = itineraryContainer.data('id');

        personCount++;
        itineraryContainer.find('.person-count').text(personCount);

        updatePricing(itineraryId);
    });

    jQuery('.decrease-btn').on('click', (e) => {
        const itineraryContainer = jQuery(e.target).closest('.itinerary-container');
        const itineraryId = itineraryContainer.data('id');

        if (personCount > 1) {
            personCount--;
            itineraryContainer.find('.person-count').text(personCount);

            updatePricing(itineraryId);
        }
    });
});

        </script>
        <?php
ob_end_flush(); ?>