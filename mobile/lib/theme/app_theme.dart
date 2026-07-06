import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';

class AppTheme {
  // Common Colors
  static const Color primaryBlue = Color(0xFF007AFF);
  static const Color electricBlue = Color(0xFF00F0FF);
  static const Color vividPurple = Color(0xFF8A2BE2);
  
  // Dark Mode Colors
  static const Color darkBackground = Color(0xFF0D0F14); // Deep dark background from image
  static const Color darkCard = Color(0xFF161A22); // Slightly lighter than background
  static const Color darkCardBorder = Color(0xFF282E3A); // Subtle border
  static const Color darkText = Colors.white;
  static const Color darkTextSecondary = Color(0xFF8A92A6);

  // Gradients
  static const LinearGradient buttonGradient = LinearGradient(
    colors: [Color(0xFF00E5FF), Color(0xFF8A2BE2)], // Cyan to purple
    begin: Alignment.centerLeft,
    end: Alignment.centerRight,
  );
  
  static const LinearGradient featuredCardGradient = LinearGradient(
    colors: [Color(0xFF1E2841), Color(0xFF2E1C40)], // Blueish to purplish dark gradient
    begin: Alignment.topLeft,
    end: Alignment.bottomRight,
  );

  // Light Mode Colors
  static const Color lightBackground = Color(0xFFF5F7FA);
  static const Color lightCard = Colors.white;
  static const Color lightText = Color(0xFF1E1E1E);
  static const Color lightTextSecondary = Color(0xFF6B7280);

  // Text Theme using Google Fonts (Inter)
  static TextTheme _buildTextTheme(TextTheme base, Color color) {
    return GoogleFonts.interTextTheme(base).copyWith(
      displayLarge: GoogleFonts.inter(
        fontSize: 32,
        fontWeight: FontWeight.bold,
        color: color,
        letterSpacing: -0.5,
      ),
      titleLarge: GoogleFonts.inter(
        fontSize: 20,
        fontWeight: FontWeight.w600,
        color: color,
        letterSpacing: -0.2,
      ),
      bodyLarge: GoogleFonts.inter(
        fontSize: 16,
        fontWeight: FontWeight.normal,
        color: color,
      ),
      bodyMedium: GoogleFonts.inter(
        fontSize: 14,
        fontWeight: FontWeight.normal,
        color: color,
      ),
    );
  }

  // Dark Theme
  static ThemeData get darkTheme {
    final base = ThemeData.dark();
    return base.copyWith(
      scaffoldBackgroundColor: darkBackground,
      primaryColor: electricBlue,
      colorScheme: const ColorScheme.dark(
        primary: electricBlue,
        secondary: vividPurple,
        surface: darkCard,
      ),
      appBarTheme: const AppBarTheme(
        backgroundColor: darkBackground,
        elevation: 0,
        centerTitle: true,
        iconTheme: IconThemeData(color: darkText),
      ),
      cardTheme: CardThemeData(
        color: darkCard,
        elevation: 8,
        shadowColor: Colors.black.withAlpha((0.4 * 255).toInt()),
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(24),
        ),
      ),
      textTheme: _buildTextTheme(base.textTheme, darkText).copyWith(
        bodyMedium: GoogleFonts.inter(color: darkTextSecondary, fontSize: 14),
      ),
      elevatedButtonTheme: ElevatedButtonThemeData(
        style: ElevatedButton.styleFrom(
          backgroundColor: electricBlue,
          foregroundColor: darkBackground,
          elevation: 4,
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(24),
          ),
          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 12),
          textStyle: GoogleFonts.inter(
            fontWeight: FontWeight.w600,
            fontSize: 14,
          ),
        ),
      ),
    );
  }

  // Light Theme
  static ThemeData get lightTheme {
    final base = ThemeData.light();
    return base.copyWith(
      scaffoldBackgroundColor: lightBackground,
      primaryColor: primaryBlue,
      colorScheme: const ColorScheme.light(
        primary: primaryBlue,
        secondary: vividPurple,
        surface: lightCard,
      ),
      appBarTheme: const AppBarTheme(
        backgroundColor: lightBackground,
        elevation: 0,
        centerTitle: true,
        iconTheme: IconThemeData(color: lightText),
      ),
      cardTheme: CardThemeData(
        color: lightCard,
        elevation: 12,
        shadowColor: Colors.black.withAlpha((0.05 * 255).toInt()),
        shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.circular(24),
        ),
      ),
      textTheme: _buildTextTheme(base.textTheme, lightText).copyWith(
        bodyMedium: GoogleFonts.inter(color: lightTextSecondary, fontSize: 14),
      ),
      elevatedButtonTheme: ElevatedButtonThemeData(
        style: ElevatedButton.styleFrom(
          backgroundColor: primaryBlue,
          foregroundColor: Colors.white,
          elevation: 2,
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(24),
          ),
          padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 12),
          textStyle: GoogleFonts.inter(
            fontWeight: FontWeight.w600,
            fontSize: 14,
          ),
        ),
      ),
    );
  }
}
