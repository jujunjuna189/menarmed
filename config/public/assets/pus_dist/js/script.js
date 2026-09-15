const _monthScript = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

const difference_in_days = (date1, date2) => {
    date1 = new Date(date1);
    date2 = new Date(date2);

    let diff = new Date(date2 - date1);
    let days = diff / 1000 / 60 / 60 / 24;

    return Math.floor(days);
};

const setDateFormat = (date) => {
    return date.getFullYear() + '-' + set_zero(date.getMonth() + 1) + '-' + set_zero(date.getDate());
}

// =========================================================================================================
// Picker date
const lite_picker = () => {
    let picker = new Litepicker({
        element: document.getElementById("datepicker-icon"),
        buttonText: {
            previousMonth: `<!-- Download SVG icon from http://tabler-icons.io/i/chevron-left -->
    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="15 6 9 12 15 18" /></svg>`,
            nextMonth: `<!-- Download SVG icon from http://tabler-icons.io/i/chevron-right -->
    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><polyline points="9 6 15 12 9 18" /></svg>`,
        },
    });

    return picker;
};
// =========================================================================================================
// =========================================================================================================
// Open Modal
const open_modal = (target, onFucus) => {
    $(target).modal("show");
    setTimeout(function () {
        if (onFucus) {
            $(onFucus).focus();
        }
    }, 500);
};
// =========================================================================================================
// Jusgage chart
const chartJusgage = (value = 0) => {
    let jusgage = new JustGage({
        id: "jusgage",
        value: value,
        min: 0,
        max: 100,
        symbol: "%",
        donut: true,
        pointer: true,
        gaugeWidthScale: 0.7,
        shadowOpacity: 0.6,
        shadowSize: 5,
        pointerOptions: {
            toplength: 10,
            bottomlength: 10,
            bottomwidth: 8,
            color: "#000",
        },
        customSectors: [
            {
                color: "#4299e1",
                lo: 50,
                hi: 100,
            },
            {
                color: "#4299e1",
                lo: 0,
                hi: 50,
            },
        ],
        counter: true,
    });

    return jusgage;
};
// For Toast and sweetalert
const swal_loader = (message) => {
    $(function () {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-right',
            showConfirmButton: false,
            onOpen: function () {
                swal.showLoading();
            },
        });

        Toast.fire({
            icon: false,
            background: '#fff',
            title: message,
        })
    });
}

const close_swal = (notif_status = true, message = 'Success', icon = 'success') => {
    setTimeout(function () {
        Swal.close()

        if (notif_status) {
            notif(message, icon);
        }
    }, 1000)
}

const reloadPage = () => {
    setTimeout(function () {
        location.reload();
    }, 2000);
}

const reload = () => {
    location.reload();
}

// Function for array
// ==================================
const arrayRemoveDuplicates = (array, key) => {
    var newArray = [];
    var lookupObject = {};

    for (var i in array) {
        lookupObject[array[i][key]] = array[i];
    }

    for (i in lookupObject) {
        newArray.push(lookupObject[i]);
    }
    return newArray;
}

const arrayGroupByKey = (array, key) => {
    return array
        .reduce((hash, obj) => {
            if (obj[key] === undefined) return hash;
            return Object.assign(hash, {
                [obj[key]]: (hash[obj[key]] || []).concat(obj)
            })
        }, {})
}
// ==================================

const notif = (message = '', icon = false) => {
    $(function () {
        'use strict';
        resetToastPosition();
        $.toast({
            heading: 'Notifikasi',
            text: message,
            showHideTransition: 'plain',
            position: 'top-right',
            icon: icon,
            stack: false,
            loader: false,
            loaderBg: '#57c7d4',
        })
    });
}

const resetToastPosition = () => {
    $('.jq-toast-wrap').removeClass('bottom-left bottom-right top-left top-right mid-center'); // to remove previous position class
    $(".jq-toast-wrap").css({
        "top": "",
        "left": "",
        "bottom": "",
        "right": ""
    }); //to remove previous position style
}

// Request to server
const requestServer = ({ url = '', type = 'post', data = [], onLoader = true, onSuccess }) => {
    $.ajax({
        url: url,
        type: type,
        dataType: 'json',
        data: data,
        headers: {
            'X-CSRF-TOKEN': token,
        },
        beforeSend: function () {
            if (onLoader) {
                swal_loader('Loading...');
            }
        },
        success: function (data) {
            onSuccess(data);
        },
        error: function (error) {
            close_swal(true, 'Terjadi kesalahan saat request data', 'error');
        }
    });
}

const logout_app = () => {
    let parent_modal = '#modal-logout';
    $(parent_modal).modal('show');

    $(parent_modal + ' #btn-logout-execute').attr('onclick', 'execute_logout()');
}

// Data parse
const set_zero = (value) => {
    return value < 10 ? '0' + value : value;
}

const execute_logout = () => {
    $.ajax({
        url: url + '/logout',
        type: 'post',
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': token,
        },
        beforeSend: function () {
            swal_loader('Logout app...');
        },
        success: function (data) {
            location.reload();
        },
        error: function (error) {
            close_swal(true, 'Failed logout app..', 'error');
        }
    });
}
