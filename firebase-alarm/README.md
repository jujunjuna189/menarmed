# Alarm Stelling Push (Android)

The existing Realtime Database write at /alarm/demo triggers an FCM notification
to the stelling_alarm topic. Android displays it in background/terminated state;
Flutter shows a local notification in foreground.

## Deployment

1. Enable FCM HTTP v1 API in the same Firebase project used by the mobile app.
2. Enable billing for Cloud Functions. Deployment can incur costs.
3. Use Node.js 22 (the local host currently has Node 20), install Firebase CLI,
   sign in, then install dependencies in functions:
   npm install
4. Set ALARM_FUNCTION_REGION to the region of the Realtime Database instance.
5. From this directory, deploy:
   firebase deploy --only functions:stellingAlarmPush --project YOUR_FIREBASE_PROJECT_ID
6. Rebuild/install the Android app, grant notifications, open once, then test
   an alarm with the app in background and swiped out of recents.

Deployment and live alarm tests are not performed automatically.
No service account keys belong in the mobile app.

The topic currently includes all app installations that granted permissions.
It is not a private authorization boundary. Restrict database alarm writes
to authorized operators in Firebase rules before production deployment.
Do not make database rules public to enable this feature.

Force Stop blocks delivery until reopening. Silent mode, notification settings,
connectivity and vendor battery restrictions can prevent delivery or sound.
The siren is a notification sound, not an indefinitely looping emergency alarm.
Stopping an alarm does not retract notifications already delivered.
iOS/APNs and custom iOS sound are not configured by this Android implementation.
