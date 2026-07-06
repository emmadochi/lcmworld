class CategoryItem {
  final int id;
  final String name;
  final String? iconUrl;

  CategoryItem({
    required this.id,
    required this.name,
    this.iconUrl,
  });

  factory CategoryItem.fromJson(Map<String, dynamic> json) {
    return CategoryItem(
      id: json['id'] != null ? int.tryParse(json['id'].toString()) ?? 0 : 0,
      name: json['name'],
      iconUrl: json['icon_url'],
    );
  }
}
