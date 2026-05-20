import 'package:flutter/material.dart';
import '../services/api_service.dart';

class ReportProvider with ChangeNotifier {
  final ApiService _apiService = ApiService();

  bool _isLoading = false;
  List<dynamic> _reports = [];
  List<dynamic> _mapReports = [];

  bool get isLoading => _isLoading;
  List<dynamic> get reports => _reports;
  List<dynamic> get mapReports => _mapReports;

  Future<void> fetchReports() async {
    _isLoading = true;
    notifyListeners();

    try {
      _reports = await _apiService.getReports();
    } catch (e) {
      debugPrint('Error fetching reports: $e');
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<void> fetchReportsMap() async {
    _isLoading = true;
    notifyListeners();

    try {
      _mapReports = await _apiService.getReportsMap();
    } catch (e) {
      debugPrint('Error fetching reports map: $e');
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<bool> createReport(String description, String photoPath, double lat, double lng) async {
    _isLoading = true;
    notifyListeners();

    try {
      final success = await _apiService.createReport(description, photoPath, lat, lng);
      if (success) {
        await fetchReports();
        await fetchReportsMap();
      }
      return success;
    } catch (e) {
      debugPrint('Error creating report: $e');
      rethrow;
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }
}
