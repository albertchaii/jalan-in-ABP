import 'package:dio/dio.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';

class ApiService {
  // Menggunakan IP lokal Mac agar bisa diakses dari HP fisik di jaringan WiFi yang sama
  static String get baseUrl {
    return 'http://192.168.100.152:8000/api';
  } 
  final FlutterSecureStorage _storage = const FlutterSecureStorage();
  
  late final Dio _dio;

  ApiService() {
    _dio = Dio(
      BaseOptions(
        baseUrl: baseUrl,
        headers: {'Accept': 'application/json'}, 
      ),
    );

    _dio.interceptors.add(InterceptorsWrapper(
      onRequest: (options, handler) async {
        final token = await _storage.read(key: 'auth_token');
        if (token != null) {
          options.headers['Authorization'] = 'Bearer $token';
        }
        return handler.next(options);
      },
    ));
  }

  // --- FUNGSI REGISTER ---
  Future<bool> register(String name, String email, String password) async {
    try {
      final response = await _dio.post('/register', data: {
        'name': name,
        'email': email,
        'password': password,
        'password_confirmation': password, 
      });
      return response.statusCode == 201 || response.statusCode == 200;
    } on DioException catch (e) {
      throw Exception(e.response?.data['message'] ?? 'Gagal mendaftar');
    }
  }

  // --- FUNGSI LOGIN ---
  Future<String?> login(String email, String password) async {
    try {
      final response = await _dio.post('/login', data: {
        'email': email,
        'password': password,
        'device_name': 'Mac Simulator',
      });
      // Ambil token dari response JSON Laravel
      return response.data['data']['token']; 
    } on DioException catch (e) {
      throw Exception(e.response?.data['message'] ?? 'Email atau kata sandi salah');
    }
  }

  // --- FUNGSI LOGOUT ---
  Future<void> logout() async {
    try {
      await _dio.post('/logout');
    } catch (e) {
      // Abaikan jika token sudah tidak valid di server
    }
  }

  // --- FUNGSI GET USER ---
  Future<Map<String, dynamic>> getUser() async {
    try {
      final response = await _dio.get('/user');
      return response.data;
    } on DioException catch (e) {
      throw Exception(e.response?.data['message'] ?? 'Gagal mengambil data pengguna');
    }
  }

  // --- FUNGSI REPORTS ---
  Future<List<dynamic>> getReports() async {
    try {
      final response = await _dio.get('/reports');
      return response.data['data'] ?? [];
    } on DioException catch (e) {
      throw Exception(e.response?.data['message'] ?? 'Gagal mengambil data laporan');
    }
  }

  Future<List<dynamic>> getReportsMap() async {
    try {
      final response = await _dio.get('/reports/map');
      return response.data['data'] ?? [];
    } on DioException catch (e) {
      throw Exception(e.response?.data['message'] ?? 'Gagal mengambil data peta');
    }
  }

  Future<bool> createReport(String description, String photoPath, double lat, double lng) async {
    try {
      FormData formData = FormData.fromMap({
        'description': description,
        'latitude': lat,
        'longitude': lng,
        'photo': await MultipartFile.fromFile(photoPath, filename: 'report.jpg'),
      });
      final response = await _dio.post('/reports', data: formData);
      return response.statusCode == 201 || response.statusCode == 200;
    } on DioException catch (e) {
      throw Exception(e.response?.data['message'] ?? 'Gagal mengirim laporan');
    }
  }
}