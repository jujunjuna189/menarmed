const { test } = require("node:test");
const assert = require("node:assert/strict");
const { alarmMessage } = require("./alarm-message");
test("active alarm sends a high priority notification with native sound", () => {
  const message = alarmMessage({ status: false }, { status: true, code: 0 });
  assert.equal(message.notification.title, "Awan Jingga");
  assert.equal(message.android.notification.sound, "alarm");
  assert.equal(message.android.notification.channelId, "stelling_siren_v1");
});
test("stop, duplicate and invalid alarms do not send notifications", () => {
  assert.equal(alarmMessage(null, { status: false }), null);
  assert.equal(alarmMessage({ status: true, code: 0 }, { status: true, code: 0 }), null);
  assert.equal(alarmMessage(null, { status: true, code: 99 }), null);
});
