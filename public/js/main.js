$(document).ready(function () {
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

    const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
    const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl))

    $(".select2").select2();
    const toggle = $(`#header-toggle-icon`),
    nav = $(`#nav-bar`),
    bodypd = $(`#body-pd`),
    headerpd = $(`#header`),
    toggler = $(`#header-toggle`);

    toggler.on("click", () => {
        changeNavBar(nav, toggler, toggle, bodypd, headerpd);
    });

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
