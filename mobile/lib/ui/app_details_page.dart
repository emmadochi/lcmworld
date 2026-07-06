import 'package:flutter/material.dart';
import '../models/app_item.dart';
import '../services/apk_installer_service.dart';
import '../services/app_status_service.dart';
import '../services/auth_service.dart';
import '../theme/app_theme.dart';
import 'package:dio/dio.dart';
import 'widgets/featured_app_card.dart' show AppIconPlaceholder;

class AppDetailsPage extends StatefulWidget {
  final AppItem appItem;

  const AppDetailsPage({super.key, required this.appItem});

  @override
  State<AppDetailsPage> createState() => _AppDetailsPageState();
}

class _AppDetailsPageState extends State<AppDetailsPage> with WidgetsBindingObserver {
  final ApkInstallerService _installerService = ApkInstallerService();
  final AuthService _authService = AuthService();
  
  bool _isDownloading = false;
  double _progress = 0.0;
  bool _isLoadingReviews = true;
  List<dynamic> _reviews = [];
  bool _isLoggedIn = false;
  AppInstallStatus _installStatus = AppInstallStatus.unknown;

  @override
  void initState() {
    super.initState();
    WidgetsBinding.instance.addObserver(this);
    _checkAuth();
    _loadReviews();
    _checkStatus();
  }

  @override
  void dispose() {
    WidgetsBinding.instance.removeObserver(this);
    super.dispose();
  }

  @override
  void didChangeAppLifecycleState(AppLifecycleState state) {
    if (state == AppLifecycleState.resumed) {
      _checkStatus();
    }
  }

  Future<void> _checkStatus() async {
    final status = await AppStatusService.checkAppStatus(
      widget.appItem.packageName,
      widget.appItem.version,
    );
    if (mounted) {
      setState(() => _installStatus = status);
    }
  }

  Future<void> _checkAuth() async {
    final loggedIn = await _authService.isLoggedIn();
    if (mounted) setState(() => _isLoggedIn = loggedIn);
  }

  Future<void> _loadReviews() async {
    try {
      final dio = Dio();
      final response = await dio.get('${AuthService.baseUrl}/admin/apps/${widget.appItem.id}/reviews');
      if (mounted) {
        setState(() {
          _reviews = response.data;
          _isLoadingReviews = false;
        });
      }
    } catch (e) {
      print('Error loading reviews: $e');
      if (mounted) setState(() => _isLoadingReviews = false);
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
          if (total != -1 && mounted) {
            setState(() => _progress = received / total);
          }
        },
      );
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Starting install for ${widget.appItem.appName}')),
        );
        _checkStatus();
      }
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Download failed. Please try again.'), backgroundColor: Colors.red),
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

  void _showWriteReviewModal() {
    int rating = 5;
    final commentController = TextEditingController();

    showModalBottomSheet(
      context: context,
      backgroundColor: AppTheme.darkCard,
      isScrollControlled: true,
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
      ),
      builder: (context) {
        return StatefulBuilder(
          builder: (context, setModalState) {
            return Padding(
              padding: EdgeInsets.only(
                bottom: MediaQuery.of(context).viewInsets.bottom,
                left: 20,
                right: 20,
                top: 20,
              ),
              child: Column(
                mainAxisSize: MainAxisSize.min,
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text('Write a Review', style: TextStyle(color: Colors.white, fontSize: 20, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 20),
                  const Text('Rating', style: TextStyle(color: Colors.white70)),
                  Row(
                    children: List.generate(5, (index) {
                      return IconButton(
                        icon: Icon(
                          index < rating ? Icons.star : Icons.star_border,
                          color: Colors.amber,
                          size: 32,
                        ),
                        onPressed: () {
                          setModalState(() => rating = index + 1);
                        },
                      );
                    }),
                  ),
                  const SizedBox(height: 10),
                  TextField(
                    controller: commentController,
                    style: const TextStyle(color: Colors.white),
                    maxLines: 3,
                    decoration: InputDecoration(
                      hintText: 'Describe your experience (optional)',
                      hintStyle: const TextStyle(color: Colors.white30),
                      filled: true,
                      fillColor: Colors.white.withOpacity(0.05),
                      border: OutlineInputBorder(borderRadius: BorderRadius.circular(10), borderSide: BorderSide.none),
                    ),
                  ),
                  const SizedBox(height: 20),
                  SizedBox(
                    width: double.infinity,
                    height: 50,
                    child: ElevatedButton(
                      onPressed: () async {
                        Navigator.pop(context); // close modal
                        await _submitReview(rating, commentController.text);
                      },
                      style: ElevatedButton.styleFrom(
                        backgroundColor: AppTheme.electricBlue,
                        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(10)),
                      ),
                      child: const Text('Submit Review', style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
                    ),
                  ),
                  const SizedBox(height: 20),
                ],
              ),
            );
          },
        );
      },
    );
  }

  Future<void> _submitReview(int rating, String comment) async {
    try {
      final dio = await _authService.getAuthDio();
      await dio.post(
        '${AuthService.baseUrl}/admin/apps/${widget.appItem.id}/reviews',
        data: {'rating': rating, 'comment': comment},
      );
      _loadReviews();
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Failed to submit review')));
    }
  }

  @override
  Widget build(BuildContext context) {
    String actionLabel = 'Install App';
    VoidCallback action = _startInstall;
    Gradient? actionGradient = AppTheme.buttonGradient;
    Color? actionColor;
    
    if (_installStatus == AppInstallStatus.installedUpToDate) {
      actionLabel = 'Open App';
      action = _openApp;
      actionGradient = null;
      actionColor = AppTheme.darkCardBorder;
    } else if (_installStatus == AppInstallStatus.updateAvailable) {
      actionLabel = 'Update App';
      action = _startInstall;
      actionGradient = const LinearGradient(
        colors: [Colors.orange, Colors.deepOrange],
        begin: Alignment.topLeft,
        end: Alignment.bottomRight,
      );
    }

    return Scaffold(
      backgroundColor: const Color(0xFF0D0F14),
      appBar: AppBar(
        backgroundColor: Colors.transparent,
        elevation: 0,
        iconTheme: const IconThemeData(color: Colors.white),
      ),
      body: SingleChildScrollView(
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Header Section
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 20),
              child: Row(
                children: [
                  widget.appItem.iconUrl.isNotEmpty
                      ? ClipRRect(
                          borderRadius: BorderRadius.circular(20),
                          child: Image.network(widget.appItem.iconUrl, width: 90, height: 90, fit: BoxFit.cover, errorBuilder: (c, e, s) => AppIconPlaceholder(appName: widget.appItem.appName, size: 90)),
                        )
                      : AppIconPlaceholder(appName: widget.appItem.appName, size: 90),
                  const SizedBox(width: 20),
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(widget.appItem.appName, style: const TextStyle(color: Colors.white, fontSize: 24, fontWeight: FontWeight.bold)),
                        const SizedBox(height: 5),
                        const Text('LCMWorld Inc.', style: TextStyle(color: AppTheme.electricBlue, fontSize: 16)),
                        const SizedBox(height: 10),
                        Row(
                          children: [
                            const Icon(Icons.star, color: Colors.amber, size: 16),
                            const SizedBox(width: 4),
                            Text(widget.appItem.rating.toStringAsFixed(1), style: const TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
                            const SizedBox(width: 10),
                            Text('${widget.appItem.reviewCount} reviews', style: const TextStyle(color: Colors.white54)),
                          ],
                        )
                      ],
                    ),
                  )
                ],
              ),
            ),
            const SizedBox(height: 30),

            // Install Button
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 20),
              child: SizedBox(
                width: double.infinity,
                height: 50,
                child: _isDownloading
                    ? Container(
                        decoration: BoxDecoration(color: AppTheme.darkCardBorder, borderRadius: BorderRadius.circular(25)),
                        alignment: Alignment.center,
                        child: Text('Downloading ${(_progress * 100).toStringAsFixed(0)}%', style: const TextStyle(color: AppTheme.electricBlue, fontWeight: FontWeight.bold)),
                      )
                    : ElevatedButton(
                        onPressed: action,
                        style: ElevatedButton.styleFrom(
                          padding: EdgeInsets.zero,
                          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(25)),
                        ),
                        child: Ink(
                          decoration: BoxDecoration(
                            gradient: actionGradient, 
                            color: actionColor,
                            borderRadius: BorderRadius.circular(25)
                          ),
                          child: Center(
                            child: Text(
                              actionLabel, 
                              style: TextStyle(
                                color: actionGradient == null ? AppTheme.electricBlue : Colors.white, 
                                fontWeight: FontWeight.bold, 
                                fontSize: 16
                              )
                            )
                          ),
                        ),
                      ),
              ),
            ),
            
            if (_installStatus == AppInstallStatus.installedUpToDate || _installStatus == AppInstallStatus.updateAvailable) ...[
              const SizedBox(height: 12),
              Padding(
                padding: const EdgeInsets.symmetric(horizontal: 20),
                child: SizedBox(
                  width: double.infinity,
                  height: 50,
                  child: OutlinedButton(
                    onPressed: () {
                      AppStatusService.uninstallApp(widget.appItem.packageName);
                    },
                    style: OutlinedButton.styleFrom(
                      side: const BorderSide(color: Colors.redAccent, width: 1.5),
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(25)),
                    ),
                    child: const Text(
                      'Uninstall App',
                      style: TextStyle(color: Colors.redAccent, fontWeight: FontWeight.bold, fontSize: 16),
                    ),
                  ),
                ),
              ),
            ],
            
            const SizedBox(height: 30),
            
            // Description
            const Padding(
              padding: EdgeInsets.symmetric(horizontal: 20),
              child: Text('About this app', style: TextStyle(color: Colors.white, fontSize: 18, fontWeight: FontWeight.bold)),
            ),
            const SizedBox(height: 10),
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 20),
              child: Text(
                widget.appItem.description,
                style: const TextStyle(color: Colors.white70, height: 1.5),
              ),
            ),
            
            const SizedBox(height: 40),

            // Reviews Section
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 20),
              child: Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  const Text('Ratings and reviews', style: TextStyle(color: Colors.white, fontSize: 18, fontWeight: FontWeight.bold)),
                  if (_isLoggedIn)
                    TextButton(
                      onPressed: _showWriteReviewModal,
                      child: const Text('Write a Review', style: TextStyle(color: AppTheme.electricBlue)),
                    )
                ],
              ),
            ),
            const SizedBox(height: 10),

            if (_isLoadingReviews)
              const Center(child: CircularProgressIndicator())
            else if (_reviews.isEmpty)
              const Padding(padding: EdgeInsets.symmetric(horizontal: 20), child: Text('No reviews yet. Be the first to review!', style: TextStyle(color: Colors.white54)))
            else
              ListView.builder(
                shrinkWrap: true,
                physics: const NeverScrollableScrollPhysics(),
                itemCount: _reviews.length,
                itemBuilder: (context, index) {
                  final review = _reviews[index];
                  return Container(
                    margin: const EdgeInsets.symmetric(horizontal: 20, vertical: 8),
                    padding: const EdgeInsets.all(16),
                    decoration: BoxDecoration(
                      color: AppTheme.darkCard,
                      borderRadius: BorderRadius.circular(16),
                    ),
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Row(
                          children: [
                            CircleAvatar(
                              radius: 16,
                              backgroundColor: AppTheme.electricBlue.withOpacity(0.2),
                              child: Text(review['user']['name'].substring(0,1).toUpperCase(), style: const TextStyle(color: AppTheme.electricBlue, fontSize: 12)),
                            ),
                            const SizedBox(width: 10),
                            Text(review['user']['name'], style: const TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
                            const Spacer(),
                            Row(
                              children: List.generate(5, (i) => Icon(
                                i < review['rating'] ? Icons.star : Icons.star_border,
                                color: Colors.amber,
                                size: 14,
                              )),
                            ),
                          ],
                        ),
                        if (review['comment'] != null && review['comment'].isNotEmpty) ...[
                          const SizedBox(height: 10),
                          Text(review['comment'], style: const TextStyle(color: Colors.white70)),
                        ]
                      ],
                    ),
                  );
                },
              ),
              
            const SizedBox(height: 40),
          ],
        ),
      ),
    );
  }
}
