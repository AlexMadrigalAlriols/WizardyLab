const toggle = $(`#header-toggle-icon`),
nav = $(`#nav-bar`),
bodypd = $(`#body-pd`),
headerpd = $(`#header`),
toggler = $(`#header-toggle`);

$(document).ready(function () {
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

    const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
    const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl))

    $(".select2").select2();

    toggler.on("click", () => {
        changeNavBar(nav, toggler, toggle, bodypd, headerpd);
    });

    $('.textricheditor').summernote({
        height: 100,   //set editable area's height
        theme: 'flatly'
    });

    $('.colorpicker').spectrum({
        preferredFormat: "hex",
        showAlpha: true
    });

    initializeFlatPick();
});

document.querySelectorAll('.has_submenu ').forEach(toggle => {
    toggle.addEventListener('click', function() {
        const parent = this.closest('.nav_item');
        parent.classList.toggle('active');

        // Toggle the max-height for smooth transition
        const subMenu = parent.querySelector('.treeview');
        subMenu.classList.toggle('active');

        const togglerIcon = parent.querySelector('.toggler');
        togglerIcon.classList.toggle('bx-chevron-up');
        togglerIcon.classList.toggle('bx-chevron-down');
    });
});

function initializeFlatPick() {
    $('.flatpicker').flatpickr({
        dateFormat: "Y-m-d",
        allowInput: true,
        onClose: function(selectedDates, dateStr, instance) {
            if (!dateStr) {
                instance.clear(); // Limpiar la fecha si el campo está vacío
            }
        }
    });

    $('.flatpicker.hasTime').flatpickr({
        enableTime: true,
        dateFormat: "Y-m-d H:i",
        time_24hr: true,
        minuteIncrement: 1,
        minDate: "today",
        defaultDate: "{{old('due_date') ?? $task->due_date}}"
    });

    $('.flatpicker.multiple').flatpickr({
        mode: 'multiple',
        dateFormat: "Y-m-d",
        minDate: "today"
    });
}

function countChars(limitedCharFields) {
    limitedCharFields.forEach(function(field) {
        $('#' + field + 'CountChar').html($('#' + field).val().length);

        $('#' + field).on('keyup', function() {
            $('#' + field + 'CountChar').html(this.value.length);
        });
    });
}

function changeNavBar(nav, toggler, toggle, bodypd, headerpd) {
    // show navbar
    nav.toggleClass("show");

    // change icon
    toggler.toggleClass("show");
    toggle.toggleClass("bx-x");
    // add padding to body
    bodypd.toggleClass("body-pd");
    // add padding to header
    headerpd.toggleClass("header-pd");
    headerpd.toggleClass("body-pd");
}

function parseSelect2Results(results) {
    return Array.isArray(results) ? results : Object.values(results);
}

$(function() {
    let timer = $('#timer');

    function updateTimer() {
        var myTime = timer.html();
        var ss = myTime.split(":");

        var hours = ss[0];
        var mins = ss[1];
        var secs = ss[2];
        secs = parseInt(secs) + 1;

        if(secs > 59) {
            secs = '00';
            mins = parseInt(mins) + 1;
        }

        if(mins > 59) {
            mins = '00';
            secs = '00';
            hours = parseInt(hours) + 1;
        }

        if (hours.toString().length < 2) {
            hours = '0' + hours;
        }

        if (mins.toString().length < 2) {
            mins = '0' + mins;
        }

        if (secs.toString().length < 2) {
            secs = '0' + secs;
        }

        var ts = hours + ":" + mins + ":" + secs;
        timer.html(ts);
    }

    if($('#timer.btn-primary').length) {
        // Invoke updateTimer all the time every 1 second
        setInterval(updateTimer, 1000);
    }
})

function checkObligatoryFields(obligatoryFields) {
    var empty = obligatoryFields.some(function(field) {
        return !$('#' + field).val();
    });

    if (empty) {
        $('#submitBtn').attr('disabled', 'disabled');
    } else {
        $('#submitBtn').removeAttr('disabled');
    }
}

function generateDropZone(id, url, token, multiple = false, only_image = false) {
    var acceptedFiles = ".jpeg,.jpg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx,.txt";

    if(only_image) {
        acceptedFiles = ".jpeg,.jpg,.png,.gif";
    }

    var myDropzone = new Dropzone(id, {
        url: url, // Ruta donde manejarás la carga de archivos
        paramName: "dropzone_image", // Nombre del campo de formulario para el archivo
        maxFilesize: 2, // Tamaño máximo en MB
        acceptedFiles: acceptedFiles,
        addRemoveLinks: true,
        uploadMultiple: multiple,
        headers: {
            'X-CSRF-TOKEN': token
        }
    });

    $(document).on('paste', function(event) {
        var items = (event.originalEvent.clipboardData || event.clipboardData).items;

        for (var index in items) {
            var item = items[index];
            if (item.kind === 'file' && item.type.indexOf('image/') !== -1) {
                var file = item.getAsFile();
                myDropzone.addFile(file);
            }
        }
    });
}

function isPhoneDevice() {
    return /Mobi|Android|iPhone|iPad|iPod|Opera Mini|IEMobile|WPDesktop/.test(navigator.userAgent);
}
