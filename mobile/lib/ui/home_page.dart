import 'package:flutter/material.dart';
import 'package:animate_do/animate_do.dart';
import '../models/app_item.dart';
import '../models/category_item.dart';
import '../theme/app_theme.dart';
import 'widgets/app_card.dart';
import 'widgets/featured_app_card.dart';
import 'widgets/search_bar_widget.dart';
import 'documents_page.dart';
import 'categories_page.dart';
import 'updates_page.dart';
import 'profile_page.dart';
import '../services/app_status_service.dart';

class HomePage extends StatefulWidget {
  final List<AppItem> dummyApps;
  final List<CategoryItem> categories;

  const HomePage({super.key, required this.dummyApps, required this.categories});

  @override
  State<HomePage> createState() => _HomePageState();
}

class _HomePageState extends State<HomePage> {
  int _selectedIndex = 0;
  String _searchQuery = '';
  int? _selectedCategoryId;
  int _updatesAvailableCount = 0;

  @override
  void initState() {
    super.initState();
    _checkUpdates();
  }

  @override
  void didUpdateWidget(HomePage oldWidget) {
    super.didUpdateWidget(oldWidget);
    if (oldWidget.dummyApps != widget.dummyApps) {
      _checkUpdates();
    }
  }

  Future<void> _checkUpdates() async {
    int count = 0;
    for (var app in widget.dummyApps) {
      final status = await AppStatusService.checkAppStatus(app.packageName, app.version);
      if (status == AppInstallStatus.updateAvailable) {
        count++;
      }
    }
    if (mounted) {
      setState(() {
        _updatesAvailableCount = count;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppTheme.darkBackground,
      appBar: AppBar(
        backgroundColor: AppTheme.darkBackground,
        elevation: 0,
        title: Row(
          children: [
            Image.asset(
              'assets/images/logo.png',
              height: 32,
              errorBuilder: (context, error, stackTrace) => const Icon(Icons.apps, color: Colors.white),
            ),
            const SizedBox(width: 12),
            const Text(
              'LCMWorld',
              style: TextStyle(
                fontWeight: FontWeight.bold,
                fontSize: 20,
              ),
            ),
          ],
        ),
      ),
      body: SafeArea(
        child: IndexedStack(
          index: _selectedIndex,
          children: [
            _buildHomeTab(),
            const DocumentsPage(),
            CategoriesPage(
              apps: widget.dummyApps,
              categories: widget.categories,
              onCategorySelected: (categoryId) {
                setState(() {
                  _selectedIndex = 0; // Go to Home tab
                  _selectedCategoryId = categoryId; // Select the category
                });
              },
            ),
            UpdatesPage(apps: widget.dummyApps),
            ProfilePage(apps: widget.dummyApps),
          ],
        ),
      ),
      bottomNavigationBar: _buildBottomNavigationBar(),
    );
  }

  // ── Home Tab ────────────────────────────────────────────────────────────────

  Widget _buildHomeTab() {
    final theme = Theme.of(context);
    final featuredApp = widget.dummyApps.isNotEmpty ? widget.dummyApps.first : null;
    
    // Filter apps based on category and search query
    List<AppItem> filteredApps = widget.dummyApps;
    if (_selectedCategoryId != null) {
      filteredApps = filteredApps.where((app) => app.categoryId == _selectedCategoryId).toList();
    }
    if (_searchQuery.isNotEmpty) {
      final query = _searchQuery.toLowerCase();
      filteredApps = filteredApps.where((app) => 
        app.appName.toLowerCase().contains(query) || 
        app.description.toLowerCase().contains(query)
      ).toList();
    }

    return SingleChildScrollView(
      physics: const BouncingScrollPhysics(),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const SizedBox(height: 16),
          // Featured App Section Title
          if (_searchQuery.isEmpty && _selectedCategoryId == null) ...[
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 20),
              child: Text(
                'Featured App',
                style: theme.textTheme.headlineSmall?.copyWith(
                  fontWeight: FontWeight.bold,
                  color: Colors.white,
                ),
              ),
            ),
            const SizedBox(height: 16),

            // Featured Card
            if (featuredApp != null)
              FadeInDown(
                duration: const Duration(milliseconds: 600),
                child: FeaturedAppCard(appItem: featuredApp),
              ),

            const SizedBox(height: 16),
            // Pagination Dots
            Row(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                _buildDot(active: true),
                _buildDot(active: false),
                _buildDot(active: false),
              ],
            ),
          ],

          const SizedBox(height: 24),
          // Search Bar
          FadeIn(
            duration: const Duration(milliseconds: 600),
            delay: const Duration(milliseconds: 200),
            child: SearchBarWidget(
              onChanged: (value) {
                setState(() {
                  _searchQuery = value;
                });
              },
            ),
          ),
          
          const SizedBox(height: 20),
          
          // Category Pills
          if (widget.categories.isNotEmpty)
            SizedBox(
              height: 40,
              child: ListView.builder(
                scrollDirection: Axis.horizontal,
                padding: const EdgeInsets.symmetric(horizontal: 20),
                itemCount: widget.categories.length + 1, // +1 for "All"
                itemBuilder: (context, index) {
                  final isAll = index == 0;
                  final category = isAll ? null : widget.categories[index - 1];
                  final isSelected = isAll ? _selectedCategoryId == null : _selectedCategoryId == category?.id;
                  
                  return GestureDetector(
                    onTap: () {
                      setState(() {
                        _selectedCategoryId = isAll ? null : category?.id;
                      });
                    },
                    child: Container(
                      margin: const EdgeInsets.only(right: 10),
                      padding: const EdgeInsets.symmetric(horizontal: 16),
                      decoration: BoxDecoration(
                        color: isSelected ? AppTheme.electricBlue : AppTheme.darkCardBorder.withOpacity(0.5),
                        borderRadius: BorderRadius.circular(20),
                        border: Border.all(
                          color: isSelected ? AppTheme.electricBlue : Colors.white.withOpacity(0.1),
                        ),
                      ),
                      alignment: Alignment.center,
                      child: Text(
                        isAll ? 'All' : category!.name,
                        style: TextStyle(
                          color: isSelected ? Colors.white : AppTheme.darkTextSecondary,
                          fontWeight: isSelected ? FontWeight.bold : FontWeight.normal,
                        ),
                      ),
                    ),
                  );
                },
              ),
            ),

          const SizedBox(height: 20),
          
          // App List
          if (filteredApps.isEmpty)
            const Padding(
              padding: EdgeInsets.all(40.0),
              child: Center(
                child: Text('No apps found.', style: TextStyle(color: Colors.white54, fontSize: 16)),
              ),
            )
          else
            ListView.builder(
              shrinkWrap: true,
              physics: const NeverScrollableScrollPhysics(),
              padding: const EdgeInsets.only(bottom: 24),
              itemCount: filteredApps.length,
              itemBuilder: (context, index) {
                return FadeInUp(
                  duration: const Duration(milliseconds: 300),
                  child: AppCard(
                    key: ValueKey(filteredApps[index].id),
                    appItem: filteredApps[index],
                    index: index,
                  ),
                );
              },
            ),
        ],
      ),
    );
  }

  // ── Shared Helpers ──────────────────────────────────────────────────────────

  Widget _buildDot({required bool active}) {
    return AnimatedContainer(
      duration: const Duration(milliseconds: 250),
      margin: const EdgeInsets.symmetric(horizontal: 4),
      width: active ? 16 : 7,
      height: 4,
      decoration: BoxDecoration(
        color: active
            ? AppTheme.electricBlue
            : AppTheme.darkTextSecondary.withOpacity(0.4),
        borderRadius: BorderRadius.circular(2),
      ),
    );
  }

  Widget _buildBottomNavigationBar() {
    return Container(
      decoration: const BoxDecoration(
        border: Border(
          top: BorderSide(color: AppTheme.darkCardBorder, width: 1),
        ),
      ),
      child: BottomNavigationBar(
        currentIndex: _selectedIndex,
        onTap: (index) => setState(() => _selectedIndex = index),
        backgroundColor: AppTheme.darkBackground,
        type: BottomNavigationBarType.fixed,
        selectedItemColor: AppTheme.electricBlue,
        unselectedItemColor: AppTheme.darkTextSecondary,
        showUnselectedLabels: true,
        selectedLabelStyle:
            const TextStyle(fontWeight: FontWeight.bold, fontSize: 10),
        unselectedLabelStyle:
            const TextStyle(fontWeight: FontWeight.normal, fontSize: 10),
        elevation: 0,
        items: [
          _navItem(Icons.home_filled, Icons.home_outlined, 'Home', 0),
          _navItem(Icons.folder_shared_rounded, Icons.folder_shared_outlined, 'Documents', 1),
          _navItem(Icons.grid_view_rounded, Icons.grid_view_outlined, 'Categories', 2),
          _updatesNavItem(),
          _navItem(Icons.person_rounded, Icons.person_outline, 'Profile', 4),
        ],
      ),
    );
  }

  BottomNavigationBarItem _updatesNavItem() {
    return BottomNavigationBarItem(
      icon: Stack(
        clipBehavior: Clip.none,
        children: [
          const Icon(Icons.notifications_none_outlined),
          if (_updatesAvailableCount > 0)
            Positioned(
              right: -4,
              top: -4,
              child: Container(
                padding: const EdgeInsets.all(4),
                decoration: const BoxDecoration(
                  color: Colors.red,
                  shape: BoxShape.circle,
                ),
                child: Text(
                  _updatesAvailableCount > 9 ? '9+' : '$_updatesAvailableCount',
                  style: const TextStyle(
                    color: Colors.white,
                    fontSize: 8,
                    fontWeight: FontWeight.bold,
                  ),
                ),
              ),
            ),
        ],
      ),
      activeIcon: Stack(
        clipBehavior: Clip.none,
        children: [
          const Icon(Icons.notifications_rounded),
          if (_updatesAvailableCount > 0)
            Positioned(
              right: -4,
              top: -4,
              child: Container(
                padding: const EdgeInsets.all(4),
                decoration: const BoxDecoration(
                  color: Colors.red,
                  shape: BoxShape.circle,
                ),
                child: Text(
                  _updatesAvailableCount > 9 ? '9+' : '$_updatesAvailableCount',
                  style: const TextStyle(
                    color: Colors.white,
                    fontSize: 8,
                    fontWeight: FontWeight.bold,
                  ),
                ),
              ),
            ),
        ],
      ),
      label: 'Updates',
    );
  }

  BottomNavigationBarItem _navItem(
    IconData activeIcon,
    IconData icon,
    String label,
    int index,
  ) {
    return BottomNavigationBarItem(
      icon: Icon(icon),
      activeIcon: Icon(activeIcon),
      label: label,
    );
  }
}
