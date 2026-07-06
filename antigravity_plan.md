Project Context:
You are an expert Flutter and Android native developer. We are building a private Enterprise App Catalog. This Flutter app will act as a hub where employees can view a list of internal organizational apps, download the .apk files directly from a private server, and install them natively on their Android devices.

Execution Rules:

Execute this plan sequentially from Phase 1 to Phase 4.

Do not skip the native XML configurations in Phase 2, as they are mandatory for avoiding Android FileUriExposedException and SecurityException errors.

Use standard, modern Dart practices with Null Safety.

Phase 1: Environment & Dependencies
Initialize a new Flutter project if one does not already exist.

Run flutter pub add to install the following specific packages:

dio (for downloading the APK securely and tracking progress)

path_provider (for locating the external cache directory)

open_filex (to trigger the native Android package installer intent)

permission_handler (to request runtime permissions)

Phase 2: Native Android Configuration (Crucial)
You must modify the Android-specific files to allow external package installation and file sharing via content:// URIs.

Update AndroidManifest.xml:
Navigate to android/app/src/main/AndroidManifest.xml and add the following permissions above the <application> tag:

XML
<uses-permission android:name="android.permission.INTERNET" />
<uses-permission android:name="android.permission.REQUEST_INSTALL_PACKAGES" />
<uses-permission android:name="android.permission.READ_EXTERNAL_STORAGE" />
Setup FileProvider:
Inside the <application> tag of the same AndroidManifest.xml, add the following provider block exactly:

XML
<provider
    android:name="androidx.core.content.FileProvider"
    android:authorities="${applicationId}.fileprovider"
    android:exported="false"
    android:grantUriPermissions="true">
    <meta-data
        android:name="android.support.FILE_PROVIDER_PATHS"
        android:resource="@xml/file_paths" />
</provider>
Create file_paths.xml:
Create a new directory and file at android/app/src/main/res/xml/file_paths.xml. Insert this XML code to allow sharing from the cache directory:

XML
<?xml version="1.0" encoding="utf-8"?>
<paths xmlns:android="http://schemas.android.com/apk/res/android">
    <external-cache-path name="external_cache" path="." />
</paths>
Phase 3: Core Dart Logic (The Installer Service)
Create a new file in lib/services/apk_installer_service.dart.
Implement a class named ApkInstallerService with a method downloadAndInstall. The method must:

Use permission_handler to request Permission.requestInstallPackages. Throw an exception if denied.

Use path_provider to get getExternalCacheDirectories().

Use dio to download an APK from a provided URL to the cache directory. Include an onReceiveProgress callback to track download percentage.

Use open_filex to open the saved file path using the MIME type "application/vnd.android.package-archive".

Phase 4: UI/UX Implementation
Create a lib/models/app_item.dart file with a simple class containing: appName, description, iconUrl, and apkDownloadUrl.

Update lib/main.dart to display a modern, clean ListView of dummy organizational apps.

Each list item should have an "Install" button.

When the user taps "Install", it should invoke the ApkInstallerService.

Show a LinearProgressIndicator or CircularProgressIndicator on the UI that updates in real-time as dio downloads the file.

Once the download is 100%, the progress indicator should disappear, and the native installation prompt should take over. Handle and catch any errors, displaying them in a SnackBar.