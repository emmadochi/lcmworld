class DocumentItem {
  final int id;
  final String title;
  final String fileName;
  final String fileUrl;
  final String fileType;
  final String createdAt;

  DocumentItem({
    required this.id,
    required this.title,
    required this.fileName,
    required this.fileUrl,
    required this.fileType,
    required this.createdAt,
  });

  factory DocumentItem.fromJson(Map<String, dynamic> json) {
    // Determine appropriate IP base url if needed, similar to app_item.dart
    String url = json['file_url'] ?? '';
    if (url.contains('localhost')) {
      url = url.replaceAll('localhost', '192.168.100.59');
    }

    return DocumentItem(
      id: json['id'] ?? 0,
      title: json['title'] ?? 'Unknown Document',
      fileName: json['file_name'] ?? '',
      fileUrl: url,
      fileType: json['file_type'] ?? '',
      createdAt: json['created_at'] ?? '',
    );
  }
}
