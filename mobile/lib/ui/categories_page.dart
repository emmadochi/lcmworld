import 'package:flutter/material.dart';
import '../models/app_item.dart';
import '../models/category_item.dart';
import '../theme/app_theme.dart';

class CategoriesPage extends StatelessWidget {
  final List<CategoryItem> categories;
  final List<AppItem> apps;
  final Function(int) onCategorySelected;

  const CategoriesPage({
    super.key, 
    required this.categories, 
    required this.apps, 
    required this.onCategorySelected,
  });

  IconData _iconFromName(String name) {
    // Basic mapping based on name keywords for nice visuals
    final n = name.toLowerCase();
    if (n.contains('analytic') || n.contains('bi')) return Icons.bar_chart_rounded;
    if (n.contains('secur')) return Icons.lock_outline_rounded;
    if (n.contains('hr') || n.contains('people')) return Icons.people_alt_outlined;
    if (n.contains('invent')) return Icons.inventory_2_outlined;
    if (n.contains('chat') || n.contains('commun')) return Icons.chat_bubble_outline_rounded;
    if (n.contains('product') || n.contains('task')) return Icons.task_alt_rounded;
    if (n.contains('financ') || n.contains('money')) return Icons.monetization_on_outlined;
    if (n.contains('support') || n.contains('crm')) return Icons.support_agent_outlined;
    return Icons.category_outlined;
  }

  Color _colorFromName(String name) {
    final colors = [
      const Color(0xFF3B82F6),
      const Color(0xFFEC4899),
      const Color(0xFF10B981),
      const Color(0xFFF59E0B),
      const Color(0xFF8B5CF6),
      const Color(0xFFEF4444),
      const Color(0xFF06B6D4),
    ];
    final index = name.codeUnits.fold(0, (a, b) => a + b) % colors.length;
    return colors[index];
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
            'Categories',
            style: theme.textTheme.headlineSmall?.copyWith(
              fontWeight: FontWeight.bold,
              color: Colors.white,
            ),
          ),
          const SizedBox(height: 6),
          Text(
            'Browse all available app categories',
            style: theme.textTheme.bodyMedium?.copyWith(
              color: AppTheme.darkTextSecondary,
            ),
          ),
          const SizedBox(height: 24),
          
          if (categories.isEmpty)
            const Padding(
              padding: EdgeInsets.all(40.0),
              child: Center(
                child: Text('No categories found.', style: TextStyle(color: Colors.white54, fontSize: 16)),
              ),
            )
          else
            ListView.separated(
              shrinkWrap: true,
              physics: const NeverScrollableScrollPhysics(),
              itemCount: categories.length,
              separatorBuilder: (_, __) => const SizedBox(height: 12),
              itemBuilder: (context, index) {
                final cat = categories[index];
                final icon = _iconFromName(cat.name);
                final color = _colorFromName(cat.name);
                
                // Calculate app count for this category
                final appCount = apps.where((app) => app.categoryId == cat.id).length;
                
                return Container(
                  decoration: BoxDecoration(
                    color: AppTheme.darkCard,
                    borderRadius: BorderRadius.circular(16),
                    border: Border.all(color: AppTheme.darkCardBorder, width: 1.2),
                  ),
                  child: Material(
                    color: Colors.transparent,
                    borderRadius: BorderRadius.circular(16),
                    clipBehavior: Clip.antiAlias,
                    child: InkWell(
                      onTap: () {
                        onCategorySelected(cat.id);
                      },
                      child: Padding(
                        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
                        child: Row(
                          children: [
                            Container(
                              width: 48,
                              height: 48,
                              decoration: BoxDecoration(
                                color: color.withAlpha(40),
                                borderRadius: BorderRadius.circular(14),
                              ),
                              child: Icon(
                                icon,
                                color: color,
                                size: 24,
                              ),
                            ),
                            const SizedBox(width: 16),
                            Expanded(
                              child: Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  Text(
                                    cat.name,
                                    style: theme.textTheme.bodyLarge?.copyWith(
                                      fontWeight: FontWeight.bold,
                                      color: Colors.white,
                                    ),
                                  ),
                                  const SizedBox(height: 3),
                                  Text(
                                    '$appCount App${appCount != 1 ? 's' : ''}',
                                    style: theme.textTheme.bodySmall?.copyWith(
                                      color: AppTheme.darkTextSecondary,
                                    ),
                                  ),
                                ],
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
              },
            ),
        ],
      ),
    );
  }
}
