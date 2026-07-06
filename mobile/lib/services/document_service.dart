import 'dart:io';
import 'package:dio/dio.dart';
import 'package:path_provider/path_provider.dart';
import 'package:open_filex/open_filex.dart';
import '../models/document_item.dart';
import 'auth_service.dart';

class DocumentService {
  final Dio _dio = Dio();

  Future<List<DocumentItem>> fetchDocuments() async {
    try {
      final response = await _dio.get('${AuthService.baseUrl}/admin/documents');
      
      if (response.statusCode == 200) {
        List<dynamic> data = response.data;
        return data.map((json) => DocumentItem.fromJson(json)).toList();
      }
      return [];
    } catch (e) {
      print('Failed to fetch documents: $e');
      return [];
    }
  }

  Future<String?> downloadDocument(DocumentItem doc, {Function(int, int)? onProgress}) async {
    try {
      final directory = await getExternalStorageDirectory();
      if (directory == null) return null;
      
      final filePath = '${directory.path}/${doc.fileName}';
      
      await _dio.download(
        doc.fileUrl,
        filePath,
        onReceiveProgress: onProgress,
      );
      
      return filePath;
    } catch (e) {
      print('Failed to download document: $e');
      return null;
    }
  }

  Future<void> openDocument(String filePath) async {
    try {
      final result = await OpenFilex.open(filePath);
      print('OpenFilex result: ${result.type} - ${result.message}');
    } catch (e) {
      print('Failed to open document: $e');
    }
  }
}
