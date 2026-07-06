import 'dart:io';
import 'package:flutter/material.dart';
import '../../theme/app_theme.dart';
import '../../models/document_item.dart';
import '../../services/document_service.dart';

class DocumentsPage extends StatefulWidget {
  const DocumentsPage({super.key});

  @override
  State<DocumentsPage> createState() => _DocumentsPageState();
}

class _DocumentsPageState extends State<DocumentsPage> {
  final DocumentService _documentService = DocumentService();
  
  List<DocumentItem> _documents = [];
  List<DocumentItem> _filteredDocuments = [];
  bool _isLoading = true;
  String _searchQuery = "";
  
  // Track download progress for each document by its ID
  final Map<int, double> _downloadProgress = {};
  final Map<int, String> _downloadedPaths = {};

  @override
  void initState() {
    super.initState();
    _fetchDocuments();
  }

  Future<void> _fetchDocuments() async {
    setState(() => _isLoading = true);
    final docs = await _documentService.fetchDocuments();
    if (mounted) {
      setState(() {
        _documents = docs;
        _filteredDocuments = docs;
        _isLoading = false;
      });
    }
  }

  void _filterDocuments(String query) {
    setState(() {
      _searchQuery = query;
      if (query.isEmpty) {
        _filteredDocuments = _documents;
      } else {
        _filteredDocuments = _documents.where((doc) {
          return doc.title.toLowerCase().contains(query.toLowerCase());
        }).toList();
      }
    });
  }

  void _handleDocumentAction(DocumentItem doc) async {
    if (_downloadedPaths.containsKey(doc.id)) {
      // Already downloaded, just open it
      await _documentService.openDocument(_downloadedPaths[doc.id]!);
    } else {
      // Download it
      setState(() => _downloadProgress[doc.id] = 0.0);
      
      final path = await _documentService.downloadDocument(
        doc,
        onProgress: (received, total) {
          if (total != -1 && mounted) {
            setState(() {
              _downloadProgress[doc.id] = received / total;
            });
          }
        },
      );
      
      if (mounted) {
        setState(() {
          _downloadProgress.remove(doc.id);
          if (path != null) {
            _downloadedPaths[doc.id] = path;
            // Optionally open immediately after downloading
            _documentService.openDocument(path);
          }
        });
      }
    }
  }

  IconData _getIconForFileType(String type) {
    if (type.contains('pdf')) return Icons.picture_as_pdf_rounded;
    if (type.contains('image')) return Icons.image_rounded;
    if (type.contains('video')) return Icons.video_file_rounded;
    if (type.contains('audio')) return Icons.audio_file_rounded;
    if (type.contains('word') || type.contains('document')) return Icons.description_rounded;
    return Icons.insert_drive_file_rounded;
  }

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    
    return RefreshIndicator(
      onRefresh: _fetchDocuments,
      color: AppTheme.electricBlue,
      backgroundColor: AppTheme.darkCard,
      child: CustomScrollView(
        physics: const AlwaysScrollableScrollPhysics(parent: BouncingScrollPhysics()),
        slivers: [
          SliverPadding(
            padding: const EdgeInsets.fromLTRB(20, 20, 20, 10),
            sliver: SliverToBoxAdapter(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    'Documents Hub',
                    style: theme.textTheme.headlineSmall?.copyWith(
                      fontWeight: FontWeight.bold,
                      color: Colors.white,
                    ),
                  ),
                  const SizedBox(height: 6),
                  Text(
                    'Company guides, manuals, and resources',
                    style: theme.textTheme.bodyMedium?.copyWith(
                      color: AppTheme.darkTextSecondary,
                    ),
                  ),
                  const SizedBox(height: 20),
                  TextField(
                    onChanged: _filterDocuments,
                    style: const TextStyle(color: Colors.white),
                    decoration: InputDecoration(
                      hintText: 'Search documents...',
                      hintStyle: TextStyle(color: Colors.white.withOpacity(0.3)),
                      prefixIcon: const Icon(Icons.search, color: Colors.white54),
                      filled: true,
                      fillColor: AppTheme.darkCard,
                      border: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(15),
                        borderSide: BorderSide.none,
                      ),
                    ),
                  ),
                  const SizedBox(height: 20),
                ],
              ),
            ),
          ),
          
          if (_isLoading)
            const SliverFillRemaining(
              child: Center(child: CircularProgressIndicator(color: AppTheme.electricBlue)),
            )
          else if (_filteredDocuments.isEmpty)
            SliverFillRemaining(
              child: Center(
                child: Column(
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    Icon(Icons.folder_off_outlined, size: 60, color: Colors.white.withOpacity(0.2)),
                    const SizedBox(height: 16),
                    Text(
                      _searchQuery.isEmpty ? 'No documents available.' : 'No matching documents found.',
                      style: TextStyle(color: Colors.white.withOpacity(0.5), fontSize: 16),
                    ),
                  ],
                ),
              ),
            )
          else
            SliverPadding(
              padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 10),
              sliver: SliverList(
                delegate: SliverChildBuilderDelegate(
                  (context, index) {
                    final doc = _filteredDocuments[index];
                    final progress = _downloadProgress[doc.id];
                    final isDownloaded = _downloadedPaths.containsKey(doc.id);
                    final isDownloading = progress != null;

                    return Container(
                      margin: const EdgeInsets.only(bottom: 12),
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
                          onTap: () => _handleDocumentAction(doc),
                          child: Padding(
                            padding: const EdgeInsets.all(16),
                            child: Row(
                              children: [
                                Container(
                                  padding: const EdgeInsets.all(12),
                                  decoration: BoxDecoration(
                                    color: AppTheme.electricBlue.withOpacity(0.1),
                                    borderRadius: BorderRadius.circular(12),
                                  ),
                                  child: Icon(
                                    _getIconForFileType(doc.fileType),
                                    color: AppTheme.electricBlue,
                                    size: 28,
                                  ),
                                ),
                                const SizedBox(width: 16),
                                Expanded(
                                  child: Column(
                                    crossAxisAlignment: CrossAxisAlignment.start,
                                    children: [
                                      Text(
                                        doc.title,
                                        style: theme.textTheme.titleMedium?.copyWith(
                                          fontWeight: FontWeight.bold,
                                          color: Colors.white,
                                        ),
                                        maxLines: 1,
                                        overflow: TextOverflow.ellipsis,
                                      ),
                                      const SizedBox(height: 4),
                                      Text(
                                        doc.fileType.split('/').last.toUpperCase(),
                                        style: theme.textTheme.bodySmall?.copyWith(
                                          color: AppTheme.darkTextSecondary,
                                        ),
                                      ),
                                    ],
                                  ),
                                ),
                                const SizedBox(width: 16),
                                if (isDownloading)
                                  SizedBox(
                                    width: 24,
                                    height: 24,
                                    child: CircularProgressIndicator(
                                      value: progress,
                                      strokeWidth: 3,
                                      color: AppTheme.electricBlue,
                                      backgroundColor: Colors.white12,
                                    ),
                                  )
                                else if (isDownloaded)
                                  const Icon(Icons.check_circle_rounded, color: Colors.green)
                                else
                                  const Icon(Icons.download_rounded, color: Colors.white54),
                              ],
                            ),
                          ),
                        ),
                      ),
                    );
                  },
                  childCount: _filteredDocuments.length,
                ),
              ),
            ),
            
          const SliverPadding(padding: EdgeInsets.only(bottom: 24)),
        ],
      ),
    );
  }
}
