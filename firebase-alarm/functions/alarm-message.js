function alarmMessage(before, after) {
  if (!after || after.status !== true) return null;
  if (before && before.status === true && before.code === after.code) return null;
  const names = ["Awan Jingga", "Awan Kuning", "Awan Biru", "Angin Gunung", "Angin Puyuh"];
  const levels = ["Siaga Tingkat I", "Siaga Tingkat II", "Siaga Tingkat III", "Pencabutan Siaga", "Siap Digerakan Sewaktu Waktu"];
  const code = Number(after.code);
  if (!Number.isInteger(code) || code < 0 || code >= names.length) return null;
  return {
    topic: "stelling_alarm",
    notification: { title: names[code], body: levels[code] },
    data: { type: "stelling_alarm", code: String(code) },
    android: {
      priority: "high",
      ttl: 60000,
      notification: { channelId: "stelling_siren_v1", sound: "alarm", tag: "stelling_alarm" },
    },
  };
}
module.exports = { alarmMessage };
