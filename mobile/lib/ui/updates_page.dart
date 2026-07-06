import 'package:flutter/material.dart';
import '../models/app_item.dart';
import '../services/app_status_service.dart';
import '../theme/app_theme.dart';
import 'widgets/app_card.dart';

class UpdatesPage extends StatefulWidget {
  final List<AppItem> apps;

  const UpdatesPage({super.key, required this.apps});

  @override
  State<UpdatesPage> createState() => _UpdatesPageState();
}

class _UpdatesPageState extends State<UpdatesPage> {
  List<AppItem> _updateableApps = [];
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _fetchUpdateableApps();
  }

  @override
  void didUpdateWidget(UpdatesPage oldWidget) {
    super.didUpdateWidget(oldWidget);
    if (oldWidget.apps != widget.apps) {
      _fetchUpdateableApps();
    }
  }

  Future<void> _fetchUpdateableApps() async {
    List<AppItem> updates = [];
    for (var app in widget.apps) {
      final status = await AppStatusService.checkAppStatus(app.packageName, app.version);
      if (status == AppInstallStatus.updateAvailable) {
        updates.add(app);
      }
    }
    
    if (mounted) {
      setState(() {
        _updateableApps = updates;
        _isLoading = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    
    return SingleChildScrollView(
      physics: const BouncingScrollPhysics(),
      padding: const EdgeInsets.fromLTRB(20, 20, 20, 24),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            'Updates',
            style: theme.textTheme.headlineSmall?.copyWith(
              fontWeight: FontWeight.bold,
              color: Colors.white,
            ),
          ),
          const SizedBox(height: 6),
          Text(
            'Apps with available updates',
            style: theme.textTheme.bodyMedium?.copyWith(
              color: AppTheme.darkTextSecondary,
            ),
          ),
          const SizedBox(height: 24),
          
          if (_isLoading)
            const Padding(
              padding: EdgeInsets.all(40.0),
              child: Center(
                child: CircularProgressIndicator(),
              ),
            )
          else if (_updateableApps.isEmpty)
            Container(
              width: double.infinity,
              padding: const EdgeInsets.all(40.0),
              decoration: BoxDecoration(
                color: AppTheme.darkCard,
                borderRadius: BorderRadius.circular(16),
                border: Border.all(color: AppTheme.darkCardBorder),
              ),
              child: Column(
                children: [
                  Icon(Icons.check_circle_outline_rounded, size: 64, color: AppTheme.electricBlue.withOpacity(0.5)),
                  const SizedBox(height: 16),
                  const Text(
                    'All Caught Up!',
                    style: TextStyle(color: Colors.white, fontSize: 18, fontWeight: FontWeight.bold),
                  ),
                  const SizedBox(height: 8),
                  const Text(
                    'You have the latest versions of all installed apps.',
                    textAlign: TextAlign.center,
                    style: TextStyle(color: AppTheme.darkTextSecondary),
                  ),
                ],
              ),
            )
          else
            ListView.builder(
              shrinkWrap: true,
              physics: const NeverScrollableScrollPhysics(),
              itemCount: _updateableApps.length,
              itemBuilder: (context, index) {
                // We use the same AppCard but it will automatically render in "Update" mode 
                // because AppStatusService will evaluate it as updateAvailable.
                return AppCard(
                  key: ValueKey(_updateableApps[index].id),
                  appItem: _updateableApps[index],
                  index: index,
                );
              },
            ),
        ],
      ),
    );
  }
}
