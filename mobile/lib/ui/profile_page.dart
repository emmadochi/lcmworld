import 'package:flutter/material.dart';
import '../../theme/app_theme.dart';
import '../../services/auth_service.dart';
import 'auth/login_page.dart';

import '../../models/app_item.dart';
import '../../services/app_status_service.dart';

class ProfilePage extends StatefulWidget {
  final List<AppItem> apps;
  const ProfilePage({super.key, required this.apps});

  @override
  State<ProfilePage> createState() => _ProfilePageState();
}

class _ProfilePageState extends State<ProfilePage> {
  final AuthService _authService = AuthService();
  bool _isLoggedIn = false;
  Map<String, dynamic>? _user;
  
  int _appsInstalledCount = 0;
  int _pendingUpdatesCount = 0;

  @override
  void initState() {
    super.initState();
    _checkAuth();
    _calculateStats();
  }

  Future<void> _calculateStats() async {
    int installed = 0;
    int pending = 0;
    for (var app in widget.apps) {
      final status = await AppStatusService.checkAppStatus(app.packageName, app.version);
      if (status == AppInstallStatus.installedUpToDate) {
        installed++;
      } else if (status == AppInstallStatus.updateAvailable) {
        installed++;
        pending++;
      }
    }
    if (mounted) {
      setState(() {
        _appsInstalledCount = installed;
        _pendingUpdatesCount = pending;
      });
    }
  }

  Future<void> _checkAuth() async {
    final loggedIn = await _authService.isLoggedIn();
    final user = await _authService.getCurrentUser();
    if (mounted) {
      setState(() {
        _isLoggedIn = loggedIn;
        _user = user;
      });
    }
  }

  void _logout() async {
    await _authService.logout();
    _checkAuth();
  }

  void _goToLogin() async {
    final result = await Navigator.push(
      context,
      MaterialPageRoute(builder: (context) => const LoginPage()),
    );
    if (result == true) {
      _checkAuth();
    }
  }

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    return SingleChildScrollView(
      physics: const BouncingScrollPhysics(),
      padding: const EdgeInsets.fromLTRB(20, 20, 20, 24),
      child: Column(
        children: [
          const SizedBox(height: 16),
          
          if (!_isLoggedIn) ...[
            Container(
              width: 90,
              height: 90,
              decoration: BoxDecoration(
                shape: BoxShape.circle,
                color: AppTheme.darkCard,
                border: Border.all(color: AppTheme.darkCardBorder),
              ),
              child: const Icon(Icons.person_outline, size: 40, color: Colors.white54),
            ),
            const SizedBox(height: 16),
            Text('Guest User', style: theme.textTheme.titleLarge?.copyWith(fontWeight: FontWeight.bold, color: Colors.white)),
            const SizedBox(height: 16),
            ElevatedButton(
              onPressed: _goToLogin,
              style: ElevatedButton.styleFrom(
                backgroundColor: AppTheme.electricBlue,
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
              ),
              child: const Text('Login / Register', style: TextStyle(color: Colors.white)),
            ),
          ] else ...[
            // Avatar
            Container(
              width: 90,
              height: 90,
              decoration: BoxDecoration(
                shape: BoxShape.circle,
                gradient: const LinearGradient(
                  colors: [Color(0xFF00E5FF), Color(0xFF8A2BE2)],
                  begin: Alignment.topLeft,
                  end: Alignment.bottomRight,
                ),
                boxShadow: [
                  BoxShadow(
                    color: AppTheme.electricBlue.withAlpha(80),
                    blurRadius: 20,
                    offset: const Offset(0, 6),
                  ),
                ],
              ),
              alignment: Alignment.center,
              child: Text(
                _user?['name']?.substring(0, 2).toUpperCase() ?? 'U',
                style: const TextStyle(
                  color: Colors.white,
                  fontWeight: FontWeight.bold,
                  fontSize: 30,
                ),
              ),
            ),
            const SizedBox(height: 16),
            Text(
              _user?['name'] ?? 'User',
              style: theme.textTheme.titleLarge?.copyWith(
                fontWeight: FontWeight.bold,
                color: Colors.white,
              ),
            ),
            const SizedBox(height: 4),
            Text(
              _user?['email'] ?? '',
              style: theme.textTheme.bodyMedium?.copyWith(
                color: AppTheme.darkTextSecondary,
              ),
            ),
            const SizedBox(height: 8),
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 5),
              decoration: BoxDecoration(
                color: AppTheme.electricBlue.withAlpha(30),
                borderRadius: BorderRadius.circular(20),
              ),
              child: Text(
                'Member',
                style: TextStyle(
                  color: AppTheme.electricBlue,
                  fontWeight: FontWeight.bold,
                  fontSize: 12,
                ),
              ),
            ),
          ],
          
          const SizedBox(height: 32),

          // Stats Row
          Row(
            children: [
              _buildStat(theme, '$_appsInstalledCount', 'Apps Installed'),
              _buildDivider(),
              _buildStat(theme, '$_pendingUpdatesCount', 'Pending Updates'),
              _buildDivider(),
              _buildStat(theme, '${widget.apps.length}', 'Available Apps'),
            ],
          ),
          const SizedBox(height: 32),

          // Settings list
          ...[
            {'icon': Icons.person_outline, 'label': 'Account Settings', 'color': const Color(0xFF3B82F6)},
            {'icon': Icons.notifications_none_outlined, 'label': 'Notifications', 'color': const Color(0xFF8B5CF6)},
            {'icon': Icons.security_outlined, 'label': 'Privacy & Security', 'color': const Color(0xFF10B981)},
            {'icon': Icons.help_outline_rounded, 'label': 'Help & Support', 'color': const Color(0xFFF59E0B)},
            {'icon': Icons.info_outline_rounded, 'label': 'About', 'color': const Color(0xFFEC4899)},
          ].map((item) => _buildSettingsRow(theme, item)),

          if (_isLoggedIn)
            _buildSettingsRow(
              theme, 
              {'icon': Icons.logout, 'label': 'Logout', 'color': Colors.red},
              onTap: _logout,
            ),
        ],
      ),
    );
  }

  Widget _buildStat(ThemeData theme, String value, String label) {
    return Expanded(
      child: Column(
        children: [
          Text(
            value,
            style: theme.textTheme.titleLarge?.copyWith(
              fontWeight: FontWeight.bold,
              color: Colors.white,
            ),
          ),
          const SizedBox(height: 4),
          Text(
            label,
            textAlign: TextAlign.center,
            style: theme.textTheme.bodySmall?.copyWith(
              color: AppTheme.darkTextSecondary,
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildDivider() {
    return Container(
      width: 1,
      height: 40,
      color: AppTheme.darkCardBorder,
    );
  }

  Widget _buildSettingsRow(ThemeData theme, Map<String, dynamic> item, {VoidCallback? onTap}) {
    return Container(
      margin: const EdgeInsets.only(bottom: 10),
      decoration: BoxDecoration(
        color: AppTheme.darkCard,
        borderRadius: BorderRadius.circular(14),
        border: Border.all(color: AppTheme.darkCardBorder, width: 1.2),
      ),
      child: Material(
        color: Colors.transparent,
        borderRadius: BorderRadius.circular(14),
        clipBehavior: Clip.antiAlias,
        child: InkWell(
          onTap: onTap ?? () {},
          child: Padding(
            padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
            child: Row(
              children: [
                Container(
                  width: 38,
                  height: 38,
                  decoration: BoxDecoration(
                    color: (item['color'] as Color).withOpacity(0.1),
                    borderRadius: BorderRadius.circular(10),
                  ),
                  child: Icon(
                    item['icon'] as IconData,
                    color: item['color'] as Color,
                    size: 20,
                  ),
                ),
                const SizedBox(width: 14),
                Expanded(
                  child: Text(
                    item['label'] as String,
                    style: theme.textTheme.bodyLarge?.copyWith(
                      color: item['color'] == Colors.red ? Colors.red : Colors.white,
                      fontWeight: FontWeight.w500,
                    ),
                  ),
                ),
                Icon(
                  Icons.chevron_right_rounded,
                  color: AppTheme.darkTextSecondary,
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
