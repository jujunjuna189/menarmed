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

let activeNotification = null;
let deletionConfirmationPending = false;

const confirmDelete = (message = 'Hapus data ini?') => {
    if (deletionConfirmationPending) return Promise.resolve(false);
    deletionConfirmationPending = true;
    const element = document.getElementById('delete-confirmation');
    const button = document.getElementById('delete-confirmation-submit');
    document.getElementById('delete-confirmation-message').textContent = message;
    const modal = bootstrap.Modal.getInstance(element) || new bootstrap.Modal(element);
    return new Promise(resolve => {
        let confirmed = false;
        const approve = () => {
            confirmed = true;
            button.disabled = true;
            modal.hide();
        };
        button.addEventListener('click', approve);
        element.addEventListener('hidden.bs.modal', () => {
            button.removeEventListener('click', approve);
            button.disabled = false;
            deletionConfirmationPending = false;
            resolve(confirmed);
        }, { once: true });
        modal.show();
    });
};
let notificationTimer = null;

const notif = (message = '', icon = 'info') => {
    const types = { success: 'success', info: 'info', warning: 'warning', danger: 'danger', error: 'danger' };
    const type = types[icon] || 'info';
    window.clearTimeout(notificationTimer);
    if (activeNotification) activeNotification.remove();
    const toast = document.createElement('div');
    toast.className = 'app-toast app-toast-' + type;
    toast.setAttribute('role', type === 'danger' || type === 'warning' ? 'alert' : 'status');
    const text = document.createElement('span');
    text.textContent = Array.isArray(message) ? message.join(' ') : String(message);
    const close = document.createElement('button');
    close.type = 'button';
    close.className = 'btn-close';
    close.setAttribute('aria-label', 'Tutup notifikasi');
    toast.append(text, close);
    (document.fullscreenElement || document.body).appendChild(toast);
    activeNotification = toast;
    requestAnimationFrame(() => {
        requestAnimationFrame(() => {
            if (toast.isConnected) toast.classList.add('is-visible');
        });
    });
    const dismiss = () => {
        if (activeNotification === toast) window.clearTimeout(notificationTimer);
        toast.classList.remove('is-visible');
        window.setTimeout(() => toast.remove(), 160);
    };
    close.addEventListener('click', dismiss);
    notificationTimer = window.setTimeout(dismiss, type === 'success' ? 2500 : 5000);
};

document.addEventListener('DOMContentLoaded', () => {
    const messages = [];
    document.querySelectorAll('.alert-success, .alert-info, .alert-warning, .alert-danger').forEach(element => {
        if (!element.classList.contains('alert') || element.querySelector('input, textarea, select')) return;
        const type = ['success', 'info', 'warning', 'danger'].find(value => element.classList.contains('alert-' + value));
        const content = element.cloneNode(true);
        content.querySelectorAll('button, .btn-close').forEach(button => button.remove());
        messages.push({ message: content.textContent.trim(), type });
        element.remove();
    });
    if (messages.length) {
        const priority = { info: 0, success: 1, warning: 2, danger: 3 };
        const type = messages.reduce((result, item) => priority[item.type] > priority[result] ? item.type : result, 'info');
        notif(messages.map(item => item.message).join(' '), type);
    }
});
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
