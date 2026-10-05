const { initializeApp } = require("firebase-admin/app");
const { getMessaging } = require("firebase-admin/messaging");
const { onValueWritten } = require("firebase-functions/v2/database");
const { alarmMessage } = require("./alarm-message");
initializeApp();

exports.stellingAlarmPush = onValueWritten(
  { ref: "/alarm/demo", region: process.env.ALARM_FUNCTION_REGION || "us-central1" },
  async (event) => {
    const message = alarmMessage(event.data.before.val(), event.data.after.val());
    if (message) await getMessaging().send(message);
  },
);
