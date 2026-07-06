import 'package:flutter/material.dart';
import 'package:lcmworld/models/app_item.dart';
import 'package:lcmworld/theme/app_theme.dart';

/// A placeholder icon widget that renders a gradient avatar with initials,
/// so dummy content always displays — even without network access.
class AppIconPlaceholder extends StatelessWidget {
  final String appName;
  final double size;

  const AppIconPlaceholder({super.key, required this.appName, this.size = 56});

  Color _colorFromName(String name) {
    final colors = [
      const Color(0xFF3B82F6), // blue
      const Color(0xFFEC4899), // pink
      const Color(0xFF10B981), // green
      const Color(0xFFF59E0B), // amber
      const Color(0xFF8B5CF6), // purple
      const Color(0xFFEF4444), // red
    ];
    final index = name.codeUnits.fold(0, (a, b) => a + b) % colors.length;
    return colors[index];
  }

  @override
  Widget build(BuildContext context) {
    final color = _colorFromName(appName);
    final initials = appName.trim().split(' ').take(2).map((w) => w[0].toUpperCase()).join();
    return Container(
      width: size,
      height: size,
      decoration: BoxDecoration(
        borderRadius: BorderRadius.circular(size * 0.28),
        gradient: LinearGradient(
          colors: [color, color.withAlpha(180)],
          begin: Alignment.topLeft,
          end: Alignment.bottomRight,
        ),
      ),
      alignment: Alignment.center,
      child: Text(
        initials,
        style: TextStyle(
          color: Colors.white,
          fontWeight: FontWeight.bold,
          fontSize: size * 0.35,
          letterSpacing: 0.5,
        ),
      ),
    );
  }
}

class FeaturedAppCard extends StatelessWidget {
  final AppItem appItem;

  const FeaturedAppCard({super.key, required this.appItem});

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);

    return Container(
      height: 220,
      margin: const EdgeInsets.symmetric(horizontal: 20),
      clipBehavior: Clip.antiAlias,
      decoration: BoxDecoration(
        borderRadius: BorderRadius.circular(24),
        gradient: AppTheme.featuredCardGradient,
        border: Border.all(
          color: Colors.white.withAlpha(30),
          width: 1,
        ),
        boxShadow: [
          BoxShadow(
            color: AppTheme.electricBlue.withAlpha(40),
            blurRadius: 20,
            offset: const Offset(0, 10),
          ),
        ],
      ),
      child: Stack(
        children: [
          // Decorative glow circle
          Positioned(
            right: -30,
            top: -30,
            child: Container(
              width: 180,
              height: 180,
              decoration: BoxDecoration(
                shape: BoxShape.circle,
                gradient: RadialGradient(
                  colors: [
                    AppTheme.electricBlue.withAlpha(60),
                    Colors.transparent,
                  ],
                ),
              ),
            ),
          ),
          Positioned(
            right: 20,
            bottom: -10,
            child: Container(
              width: 100,
              height: 100,
              decoration: BoxDecoration(
                shape: BoxShape.circle,
                gradient: RadialGradient(
                  colors: [
                    AppTheme.vividPurple.withAlpha(40),
                    Colors.transparent,
                  ],
                ),
              ),
            ),
          ),
          // Content
          Padding(
            padding: const EdgeInsets.all(20.0),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                // Icon
                appItem.iconUrl.isNotEmpty
                    ? ClipRRect(
                        borderRadius: BorderRadius.circular(56 * 0.28),
                        child: Image.network(
                          appItem.iconUrl,
                          width: 56,
                          height: 56,
                          fit: BoxFit.cover,
                          errorBuilder: (context, error, stackTrace) => AppIconPlaceholder(appName: appItem.appName, size: 56),
                        ),
                      )
                    : AppIconPlaceholder(appName: appItem.appName, size: 56),
                const SizedBox(height: 14),
                // Title
                Text(
                  appItem.appName,
                  style: theme.textTheme.titleLarge?.copyWith(
                    fontWeight: FontWeight.bold,
                    color: Colors.white,
                    fontSize: 20,
                  ),
                ),
                const SizedBox(height: 6),
                // Description
                Text(
                  appItem.description,
                  maxLines: 2,
                  overflow: TextOverflow.ellipsis,
                  style: theme.textTheme.bodySmall?.copyWith(
                    color: Colors.white70,
                    height: 1.4,
                  ),
                ),
                const Spacer(),
                // Bottom Row
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  crossAxisAlignment: CrossAxisAlignment.center,
                  children: [
                    // Rating & Reviews
                    Row(
                      children: [
                        const Icon(Icons.star, color: Colors.amber, size: 15),
                        const SizedBox(width: 4),
                        Text(
                          '${appItem.rating}',
                          style: theme.textTheme.bodyMedium?.copyWith(
                            fontWeight: FontWeight.bold,
                            color: Colors.white,
                          ),
                        ),
                        const SizedBox(width: 6),
                        Text(
                          '| ${appItem.reviewCount} Reviews',
                          style: theme.textTheme.bodySmall?.copyWith(
                            color: Colors.white54,
                          ),
                        ),
                      ],
                    ),
                    // View App Button
                    Container(
                      padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 7),
                      decoration: BoxDecoration(
                        color: AppTheme.electricBlue,
                        borderRadius: BorderRadius.circular(20),
                        boxShadow: [
                          BoxShadow(
                            color: AppTheme.electricBlue.withAlpha(100),
                            blurRadius: 10,
                            offset: const Offset(0, 4),
                          ),
                        ],
                      ),
                      child: Row(
                        mainAxisSize: MainAxisSize.min,
                        children: [
                          Text(
                            'View App',
                            style: theme.textTheme.bodySmall?.copyWith(
                              color: Colors.black,
                              fontWeight: FontWeight.w700,
                            ),
                          ),
                          const SizedBox(width: 4),
                          const Icon(Icons.arrow_forward_rounded, color: Colors.black, size: 14),
                        ],
                      ),
                    ),
                  ],
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}
