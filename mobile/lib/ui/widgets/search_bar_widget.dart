import 'package:flutter/material.dart';
import 'package:lcmworld/theme/app_theme.dart';

class SearchBarWidget extends StatelessWidget {
  final ValueChanged<String>? onChanged;
  
  const SearchBarWidget({super.key, this.onChanged});

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    return Container(
      margin: const EdgeInsets.symmetric(horizontal: 20),
      padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 4),
      decoration: BoxDecoration(
        color: AppTheme.darkCardBorder.withAlpha(100),
        borderRadius: BorderRadius.circular(24),
        border: Border.all(
          color: Colors.white.withAlpha(20),
          width: 1,
        ),
      ),
      child: TextField(
        onChanged: onChanged,
        style: theme.textTheme.bodyMedium?.copyWith(color: Colors.white),
        decoration: InputDecoration(
          hintText: 'Search Apps, Tools & Teams...',
          hintStyle: theme.textTheme.bodyMedium?.copyWith(
            color: AppTheme.darkTextSecondary,
          ),
          border: InputBorder.none,
          suffixIcon: const Icon(
            Icons.search,
            color: AppTheme.darkTextSecondary,
          ),
          contentPadding: const EdgeInsets.symmetric(vertical: 14),
        ),
      ),
    );
  }
}
