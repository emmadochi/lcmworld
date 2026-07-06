import 'package:device_apps/device_apps.dart';
import 'package:android_intent_plus/android_intent.dart';

enum AppInstallStatus {
  notInstalled,
  installedUpToDate,
  updateAvailable,
  unknown
}

class AppStatusService {
  
  /// Checks the installation status of an app by package name.
  /// Compares the installed version against the [serverVersion].
  static Future<AppInstallStatus> checkAppStatus(String? packageName, String serverVersion) async {
    if (packageName == null || packageName.isEmpty) {
      return AppInstallStatus.unknown;
    }

    try {
      bool isInstalled = await DeviceApps.isAppInstalled(packageName);
      
      if (!isInstalled) {
        return AppInstallStatus.notInstalled;
      }

      Application? app = await DeviceApps.getApp(packageName, true);
      
      if (app != null) {
        String installedVersion = app.versionName ?? '';
        
        if (installedVersion == serverVersion) {
          return AppInstallStatus.installedUpToDate;
        } else {
          // If the versions don't match, we assume an update is available on the server.
          // Note: In a real app, you might want to do semantic version comparison 
          // (e.g. 1.0.1 > 1.0.0) instead of just checking for inequality.
          return AppInstallStatus.updateAvailable;
        }
      }
      
      return AppInstallStatus.notInstalled;
    } catch (e) {
      print('Failed to check app status for $packageName: $e');
      return AppInstallStatus.unknown;
    }
  }

  /// Opens the app if it is installed
  static Future<bool> openApp(String? packageName) async {
    if (packageName == null || packageName.isEmpty) {
      return false;
    }
    
    try {
      bool isInstalled = await DeviceApps.isAppInstalled(packageName);
      if (isInstalled) {
        return await DeviceApps.openApp(packageName);
      }
    } catch (e) {
      print('Failed to open app $packageName: $e');
    }
    return false;
  }

  /// Triggers the Android uninstaller for the given package
  static Future<void> uninstallApp(String? packageName) async {
    if (packageName == null || packageName.isEmpty) return;

    try {
      final AndroidIntent intent = AndroidIntent(
        action: 'android.intent.action.DELETE',
        data: 'package:$packageName',
      );
      await intent.launch();
    } catch (e) {
      print('Failed to launch uninstall intent for $packageName: $e');
    }
  }
}
