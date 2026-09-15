import { initializeApp } from "https://www.gstatic.com/firebasejs/9.8.4/firebase-app.js";
import { getDatabase, ref, set, push, child, update, remove, onValue, onChildAdded, onChildChanged, onChildRemoved } from "https://www.gstatic.com/firebasejs/9.8.4/firebase-database.js";

const firebaseConfig = {
    apiKey: "AIzaSyCFVz4GHd8sM893TyLKaQQVz-gcaD0KHCY",
    authDomain: "taspro-dc438.firebaseapp.com",
    databaseURL: "https://taspro-dc438-default-rtdb.firebaseio.com",
    projectId: "taspro-dc438",
    storageBucket: "taspro-dc438.appspot.com",
    messagingSenderId: "978558307890",
    appId: "1:978558307890:web:83ac3c445a4829ae5db62b",
    measurementId: "G-Z7BVJKNDRT"
};

// Initialize Firebase
const firebaseApp = initializeApp(firebaseConfig);
const firebaseDatabase = getDatabase(firebaseApp);

// Get realtime database
window.getFirebaseNotification = function (key_id, callback) {
    onValue(ref(firebaseDatabase, 'notifications/' + key_id.replace('.', '::')), (snapshot) => {
        callback(snapshot.val());
    });
}

// Push data notification
$('#firebase-push-data-notification').on('click', function () {
    let key_id = $(this).data('fire-key-id');
    let title = $(this).data('fire-title');
    let message = $(this).data('fire-message');
    let data = $(this).data('fire-data');

    push(ref(firebaseDatabase, 'notifications/' + key_id.replace('.', '::')), {
        title: title,
        message: message,
        data: data
    }).then(() => {
        notif('Berhasil', 'info');
    });
});

// Update
window.updateFirebaseNotification = function (key_id, key_collection, spesificUpdate, value, notif = true) {
    let updateArr = {};
    updateArr['notifications/' + key_id.replace('.', '::') + '/' + key_collection + '/' + spesificUpdate] = value;
    update(ref(firebaseDatabase), updateArr).then(() => {
        if (notif) {
            notif('Berhasil', 'info');
        }
    });
}

// Delete data notification
$('#firebase-delete-data-notification').on('click', function () {
    let key_id = $(this).data('fire-key-id');
    remove(ref(firebaseDatabase, 'notifications/' + key_id.replace('.', '::')));
});

//Funtion delete
window.deleteFirebaseNotification = function (key_id) {
    remove(ref(firebaseDatabase, 'notifications/' + key_id.replace('.', '::')));
}

window.onChildRemovedFirebaseNotivication = function (key_id, callback) {
    onChildRemoved(ref(firebaseDatabase, 'notifications/' + key_id.replace('.', '::')), (data) => {
        callback(data);
    });
}