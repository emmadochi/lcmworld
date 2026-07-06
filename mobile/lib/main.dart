import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:dio/dio.dart';
import 'theme/app_theme.dart';
import 'ui/home_page.dart';
import 'models/app_item.dart';
import 'models/category_item.dart';

import 'package:firebase_core/firebase_core.dart';
import 'package:firebase_messaging/firebase_messaging.dart';

Future<void> _firebaseMessagingBackgroundHandler(RemoteMessage message) async {
  // If you're going to use other Firebase services in the background, such as Firestore,
  // make sure you call `initializeApp` before using other Firebase services.
  print("Handling a background message: ${message.messageId}");
}

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  
  try {
    await Firebase.initializeApp();
    FirebaseMessaging.onBackgroundMessage(_firebaseMessagingBackgroundHandler);
    
    // Request permission for iOS/Web
    FirebaseMessaging messaging = FirebaseMessaging.instance;
    await messaging.requestPermission(
      alert: true,
      badge: true,
      sound: true,
    );

    // Subscribe to all_users topic
    await messaging.subscribeToTopic('all_users');
    print('Successfully subscribed to all_users topic');
  } catch (e) {
    print('Firebase initialization failed (missing google-services.json?): $e');
  }
  
  // Make the status bar transparent for the Hero header
  SystemChrome.setSystemUIOverlayStyle(
    const SystemUiOverlayStyle(
      statusBarColor: Colors.transparent,
    ),
  );
  
  runApp(const AppCatalog());
}

class AppCatalog extends StatelessWidget {
  const AppCatalog({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Enterprise App Catalog',
      debugShowCheckedModeBanner: false,
      themeMode: ThemeMode.system, // Respects device setting as requested
      theme: AppTheme.lightTheme,
      darkTheme: AppTheme.darkTheme,
      home: CatalogAppLoader(),
    );
  }
}

class CatalogAppLoader extends StatefulWidget {
  const CatalogAppLoader({super.key});

  @override
  State<CatalogAppLoader> createState() => _CatalogAppLoaderState();
}

class _CatalogAppLoaderState extends State<CatalogAppLoader> {
  List<AppItem> _apps = [];
  List<CategoryItem> _categories = [];
  bool _isLoading = true;
  String? _error;

  @override
  void initState() {
    super.initState();
    _fetchData();
    _setupForegroundMessaging();
  }

  void _setupForegroundMessaging() {
    FirebaseMessaging.onMessage.listen((RemoteMessage message) {
      if (message.notification != null) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(
            content: Text('${message.notification?.title}: ${message.notification?.body}'),
            duration: const Duration(seconds: 5),
            action: SnackBarAction(
              label: 'View',
              onPressed: () {
                // Refresh list on new app
                _fetchData();
              },
            ),
          ),
        );
      }
    });
  }

  Future<void> _fetchData() async {
    try {
      final dio = Dio();
      // Using 10.0.2.2 for Android Emulator. Use localhost for Windows desktop build.
      
      final appsFuture = dio.get('http://192.168.100.59:8000/api/admin/apps');
      final categoriesFuture = dio.get('http://192.168.100.59:8000/api/admin/categories');
      
      final results = await Future.wait([appsFuture, categoriesFuture]);
      
      final appsResponse = results[0];
      final categoriesResponse = results[1];
      
      if (appsResponse.statusCode == 200 && categoriesResponse.statusCode == 200) {
        final List<dynamic> appsData = appsResponse.data;
        final List<dynamic> categoriesData = categoriesResponse.data;
        
        setState(() {
          _apps = appsData.map((json) => AppItem.fromJson(json)).toList();
          _categories = categoriesData.map((json) => CategoryItem.fromJson(json)).toList();
          _isLoading = false;
        });
      }
    } catch (e) {
      setState(() {
        _error = 'Failed to load data: $e';
        _isLoading = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    if (_isLoading) {
      return const Scaffold(
        body: Center(child: CircularProgressIndicator()),
      );
    }

    if (_error != null) {
      return Scaffold(
        body: Center(
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              const Icon(Icons.error_outline, size: 48, color: Colors.red),
              const SizedBox(height: 16),
              Text(_error!, style: const TextStyle(color: Colors.red)),
              const SizedBox(height: 16),
              ElevatedButton(
                onPressed: () {
                  setState(() {
                    _isLoading = true;
                    _error = null;
                  });
                  _fetchData();
                },
                child: const Text('Retry'),
              ),
            ],
          ),
        ),
      );
    }

    return HomePage(dummyApps: _apps, categories: _categories);
  }
}
