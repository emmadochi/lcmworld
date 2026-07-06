import 'package:flutter/material.dart';
import '../../models/app_item.dart';
import '../../services/apk_installer_service.dart';
import '../../services/app_status_service.dart';
import '../../theme/app_theme.dart';
import 'featured_app_card.dart' show AppIconPlaceholder;
import '../app_details_page.dart';

class AppCard extends StatefulWidget {
  final AppItem appItem;
  final int index;

  const AppCard({
    super.key,
    required this.appItem,
    required this.index,
  });

  @override
  State<AppCard> createState() => _AppCardState();
}

class _AppCardState extends State<AppCard> {
  final ApkInstallerService _installerService = ApkInstallerService();
  bool _isDownloading = false;
  double _progress = 0.0;
  AppInstallStatus _installStatus = AppInstallStatus.unknown;

  @override
  void initState() {
    super.initState();
    _checkStatus();
  }

  Future<void> _checkStatus() async {
    final status = await AppStatusService.checkAppStatus(
      widget.appItem.packageName, 
      widget.appItem.version,
    );
    if (mounted) {
      setState(() {
        _installStatus = status;
      });
    }
  }

  void _startInstall() async {
    setState(() {
      _isDownloading = true;
      _progress = 0.0;
    });

    try {
      await _installerService.downloadAndInstall(
        appId: widget.appItem.id,
        url: widget.appItem.apkDownloadUrl,
        appName: widget.appItem.appName,
        onReceiveProgress: (received, total) {
          if (total != -1) {
            setState(() {
              _progress = received / total;
            });
          }
        },
      );
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Starting install for ${widget.appItem.appName}')),
        );
        // Check status again after install
        _checkStatus();
      }
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: const Text(
              'Download failed. Please try again.',
              style: TextStyle(color: Colors.white),
            ),
            backgroundColor: Colors.red.shade800,
            behavior: SnackBarBehavior.floating,
            shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
            margin: const EdgeInsets.all(16),
            duration: const Duration(seconds: 3),
          ),
        );
      }
    } finally {
      if (mounted) {
        setState(() {
          _isDownloading = false;
          _progress = 0.0;
        });
      }
    }
  }

  void _openApp() async {
    bool opened = await AppStatusService.openApp(widget.appItem.packageName);
    if (!opened && mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Could not open app')),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);

    return Container(
      margin: const EdgeInsets.symmetric(horizontal: 20, vertical: 7),
      decoration: BoxDecoration(
        color: AppTheme.darkCard,
        borderRadius: BorderRadius.circular(18),
        border: Border.all(color: AppTheme.darkCardBorder, width: 1.2),
      ),
      child: Material(
        color: Colors.transparent,
        borderRadius: BorderRadius.circular(18),
        clipBehavior: Clip.antiAlias,
        child: InkWell(
          onTap: () {
            Navigator.push(
              context,
              MaterialPageRoute(builder: (context) => AppDetailsPage(appItem: widget.appItem)),
            ).then((_) => _checkStatus()); // Recheck status on return
          },
          splashColor: AppTheme.electricBlue.withAlpha(20),
          child: Padding(
            padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 16),
            child: Row(
              crossAxisAlignment: CrossAxisAlignment.center,
              children: [
                // App Icon
                widget.appItem.iconUrl.isNotEmpty
                    ? ClipRRect(
                        borderRadius: BorderRadius.circular(58 * 0.28),
                        child: Image.network(
                          widget.appItem.iconUrl,
                          width: 58,
                          height: 58,
                          fit: BoxFit.cover,
                          errorBuilder: (context, error, stackTrace) => AppIconPlaceholder(appName: widget.appItem.appName, size: 58),
                        ),
                      )
                    : AppIconPlaceholder(appName: widget.appItem.appName, size: 58),
                const SizedBox(width: 14),

                // Title and Subtitle
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        widget.appItem.appName,
                        style: theme.textTheme.bodyLarge?.copyWith(
                          fontWeight: FontWeight.bold,
                          color: Colors.white,
                        ),
                        maxLines: 1,
                        overflow: TextOverflow.ellipsis,
                      ),
                      const SizedBox(height: 5),
                      Text(
                        widget.appItem.description,
                        style: theme.textTheme.bodySmall?.copyWith(
                          color: AppTheme.darkTextSecondary,
                          height: 1.35,
                        ),
                        maxLines: 2,
                        overflow: TextOverflow.ellipsis,
                      ),
                    ],
                  ),
                ),
                const SizedBox(width: 10),

                // Rating & Install column
                Column(
                  crossAxisAlignment: CrossAxisAlignment.end,
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    // Rating
                    Row(
                      mainAxisSize: MainAxisSize.min,
                      children: [
                        const Icon(Icons.star, color: Colors.amber, size: 13),
                        const SizedBox(width: 3),
                        Text(
                          widget.appItem.rating.toStringAsFixed(1),
                          style: theme.textTheme.bodySmall?.copyWith(
                            fontWeight: FontWeight.bold,
                            color: Colors.white,
                          ),
                        ),
                        const SizedBox(width: 3),
                        Text(
                          '| ${widget.appItem.reviewCount}',
                          style: theme.textTheme.bodySmall?.copyWith(
                            color: AppTheme.darkTextSecondary,
                            fontSize: 10,
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 10),
                    _isDownloading
                        ? _buildProgressText(theme)
                        : _buildActionButton(theme),
                  ],
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildActionButton(ThemeData theme) {
    String label = 'Install';
    VoidCallback action = _startInstall;
    Gradient? gradient = AppTheme.buttonGradient;
    Color? borderColor;

    if (_installStatus == AppInstallStatus.installedUpToDate) {
      label = 'Open';
      action = _openApp;
      gradient = null;
      borderColor = AppTheme.darkCardBorder;
    } else if (_installStatus == AppInstallStatus.updateAvailable) {
      label = 'Update';
      action = _startInstall;
      gradient = const LinearGradient(
        colors: [Colors.orange, Colors.deepOrange],
        begin: Alignment.topLeft,
        end: Alignment.bottomRight,
      );
    } else if (_installStatus == AppInstallStatus.unknown) {
      // Still checking, show generic install
      label = 'Install';
    }

    return GestureDetector(
      onTap: action,
      child: Container(
        padding: const EdgeInsets.symmetric(horizontal: 18, vertical: 7),
        decoration: BoxDecoration(
          gradient: gradient,
          color: gradient == null ? Colors.transparent : null,
          borderRadius: BorderRadius.circular(20),
          border: borderColor != null ? Border.all(color: borderColor) : null,
          boxShadow: gradient != null
              ? [
                  BoxShadow(
                    color: gradient.colors.first.withAlpha(60),
                    blurRadius: 8,
                    offset: const Offset(0, 3),
                  ),
                ]
              : null,
        ),
        child: Text(
          label,
          style: theme.textTheme.bodySmall?.copyWith(
            fontWeight: FontWeight.bold,
            color: gradient == null ? AppTheme.electricBlue : Colors.white,
          ),
        ),
      ),
    );
  }

  Widget _buildProgressText(ThemeData theme) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 7),
      decoration: BoxDecoration(
        color: AppTheme.darkCardBorder,
        borderRadius: BorderRadius.circular(20),
      ),
      child: Text(
        '${(_progress * 100).toStringAsFixed(0)}%',
        style: theme.textTheme.bodySmall?.copyWith(
          fontWeight: FontWeight.bold,
          color: AppTheme.electricBlue,
        ),
      ),
    );
  }
}
