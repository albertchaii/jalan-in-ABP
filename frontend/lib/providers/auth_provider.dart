import 'package:flutter/material.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import '../services/api_service.dart';

class AuthProvider with ChangeNotifier {
  final _storage = const FlutterSecureStorage();
  final ApiService _apiService = ApiService();
  
  bool _isAuthenticated = false;
  bool _isLoading = false; 
  String? _token;
  String? _name;
  String? _email;

  bool get isAuthenticated => _isAuthenticated;
  bool get isLoading => _isLoading; 
  String? get name => _name;
  String? get email => _email;

  // Cek token saat aplikasi baru dibuka
  Future<void> checkAuthStatus() async {
    _isLoading = true;
    notifyListeners();

    _token = await _storage.read(key: 'auth_token');
    _isAuthenticated = _token != null;
    
    if (_isAuthenticated) {
      try {
        final userData = await _apiService.getUser();
        _name = userData['name'];
        _email = userData['email'];
      } catch (e) {
        _isAuthenticated = false;
        await _storage.delete(key: 'auth_token');
      }
    }

    _isLoading = false;
    notifyListeners();
  }

  // Fungsi Login
  Future<bool> login(String email, String password) async {
    try {
      String? token = await _apiService.login(email, password);
      if (token != null) {
        _token = token;
        await _storage.write(key: 'auth_token', value: _token);
        _isAuthenticated = true;
        
        final userData = await _apiService.getUser();
        _name = userData['name'];
        _email = userData['email'];

        notifyListeners();
        return true;
      }
      return false;
    } catch (e) {
      rethrow;
    }
  }

  // Fungsi Register
  Future<bool> register(String name, String email, String password) async {
    try {
      return await _apiService.register(name, email, password);
    } catch (e) {
      rethrow;
    }
  }

  // Fungsi Logout
  Future<void> logout() async {
    _isLoading = true;
    notifyListeners();
    
    try {
      await _apiService.logout();
    } finally {
      await _storage.delete(key: 'auth_token');
      _token = null;
      _name = null;
      _email = null;
      _isAuthenticated = false;
      _isLoading = false;
      notifyListeners();
    }
  }
}