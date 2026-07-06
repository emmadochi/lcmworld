import 'dart:convert';
import 'package:dio/dio.dart';
import 'package:shared_preferences/shared_preferences.dart';

class AuthService {
  // Using 10.0.2.2 for Android Emulator to connect to localhost
  static const String baseUrl = 'https://lcmworld.lifechangerstouch.org/api';
  final Dio _dio = Dio();

  static const String _tokenKey = 'auth_token';
  static const String _userKey = 'auth_user';

  // Login
  Future<bool> login(String email, String password) async {
    try {
      final response = await _dio.post(
        '$baseUrl/login',
        data: {
          'email': email,
          'password': password,
        },
      );

      if (response.statusCode == 200) {
        final data = response.data;
        await _saveAuthData(data['token'], jsonEncode(data['user']));
        return true;
      }
    } catch (e) {
      print('Login error: $e');
    }
    return false;
  }

  // Register
  Future<bool> register(String name, String email, String password) async {
    try {
      final response = await _dio.post(
        '$baseUrl/register',
        data: {
          'name': name,
          'email': email,
          'password': password,
        },
      );

      if (response.statusCode == 200) {
        final data = response.data;
        await _saveAuthData(data['token'], jsonEncode(data['user']));
        return true;
      }
    } catch (e) {
      print('Register error: $e');
    }
    return false;
  }

  // Logout
  Future<void> logout() async {
    try {
      final token = await getToken();
      if (token != null) {
        await _dio.post(
          '$baseUrl/logout',
          options: Options(headers: {'Authorization': 'Bearer $token'}),
        );
      }
    } catch (e) {
      print('Logout API error: $e');
    } finally {
      // Clear local storage even if API fails
      final prefs = await SharedPreferences.getInstance();
      await prefs.remove(_tokenKey);
      await prefs.remove(_userKey);
    }
  }

  // Save Token & User
  Future<void> _saveAuthData(String token, String userJson) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString(_tokenKey, token);
    await prefs.setString(_userKey, userJson);
  }

  // Get Token
  Future<String?> getToken() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString(_tokenKey);
  }

  // Get Current User
  Future<Map<String, dynamic>?> getCurrentUser() async {
    final prefs = await SharedPreferences.getInstance();
    final userStr = prefs.getString(_userKey);
    if (userStr != null) {
      return jsonDecode(userStr);
    }
    return null;
  }

  // Check if logged in
  Future<bool> isLoggedIn() async {
    final token = await getToken();
    return token != null;
  }

  // Get Dio instance with Auth Header (for other services to use)
  Future<Dio> getAuthDio() async {
    final dio = Dio();
    final token = await getToken();
    if (token != null) {
      dio.options.headers['Authorization'] = 'Bearer $token';
    }
    return dio;
  }
}
