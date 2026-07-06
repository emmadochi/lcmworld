class AppItem {
  final int id;
  final String appName;
  final String description;
  final String iconUrl;
  final String apkDownloadUrl;
  final double rating;
  final String reviewCount;
  final int? categoryId;
  final String? categoryName;
  final String? packageName;
  final String version;

  AppItem({
    this.id = 0,
    required this.appName,
    required this.description,
    required this.iconUrl,
    required this.apkDownloadUrl,
    this.rating = 4.5,
    this.reviewCount = '10K',
    this.categoryId,
    this.categoryName,
    this.packageName,
    this.version = '1.0.0',
  });

  factory AppItem.fromJson(Map<String, dynamic> json) {
    String apkUrl = json['apk_download_url'] ?? '';
    apkUrl = apkUrl.replaceAll('localhost', '192.168.100.59');
    
    return AppItem(
      id: json['id'] != null ? int.tryParse(json['id'].toString()) ?? 0 : 0,
      appName: json['app_name'] ?? 'Unknown App',
      description: json['description'] ?? '',
      iconUrl: json['icon_url'] != null 
          ? (json['icon_url'].toString().startsWith('http') 
              ? json['icon_url'] 
              : 'http://192.168.100.59:8000/storage/' + json['icon_url']) 
          : '',
      apkDownloadUrl: apkUrl,
      rating: json['rating'] != null ? double.tryParse(json['rating'].toString()) ?? 4.5 : 4.5,
      reviewCount: json['review_count'] != null ? json['review_count'].toString() : '0',
      categoryId: json['category_id'] != null ? int.tryParse(json['category_id'].toString()) : null,
      categoryName: json['category'] != null ? json['category']['name'] : null,
      packageName: json['package_name'],
      version: json['version'] ?? '1.0.0',
    );
  }
}
