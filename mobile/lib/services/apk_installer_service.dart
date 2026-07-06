import 'dart:io';
import 'package:dio/dio.dart';
import 'package:path_provider/path_provider.dart';
import 'package:open_filex/open_filex.dart';
import 'package:permission_handler/permission_handler.dart';

class ApkInstallerService {
  final Dio _dio = Dio();

  Future<void> downloadAndInstall({
    required int appId,
    required String url,
    required String appName,
    required Function(int, int) onReceiveProgress,
  }) async {
    // 1. Log the download in Analytics
    try {
      await _dio.post('https://lcmworld.lifechangerstouch.org/api/admin/apps/$appId/track', data: {'type': 'download'});
    } catch (e) {
      print('Failed to track download: $e');
    }
    // 1. Request Permission
    var status = await Permission.requestInstallPackages.request();
    if (!status.isGranted) {
      throw Exception('Permission to install packages was denied.');
    }

    // 2. Get the external cache directory
    final directories = await getExternalCacheDirectories();
    if (directories == null || directories.isEmpty) {
      throw Exception('Could not find external cache directory.');
    }
    
    final Directory cacheDir = directories.first;
    // Ensure the filename is safe, here we just use appName and append .apk
    // In a real scenario you might want to sanitize appName
    final String safeAppName = appName.replaceAll(RegExp(r'[^a-zA-Z0-9]'), '_');
    final String savePath = '${cacheDir.path}/$safeAppName.apk';

    // 3. Download the APK
    try {
      await _dio.download(
        url,
        savePath,
        onReceiveProgress: onReceiveProgress,
      );
    } catch (e) {
      throw Exception('Failed to download APK: $e');
    }

    // 4. Open the saved file path
    final result = await OpenFilex.open(
      savePath,
      type: 'application/vnd.android.package-archive',
    );

    if (result.type != ResultType.done) {
      throw Exception('Failed to open APK: ${result.message}');
    }
  }
}
