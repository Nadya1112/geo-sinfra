import 'package:dio/dio.dart';
import 'package:get/get.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../../../core/network/api_client.dart';

class AuthController extends GetxController {
  final ApiClient _apiClient = ApiClient();
  
  var isLoading = false.obs;
  var errorMessage = ''.obs;

  Future<bool> login(String email, String password) async {
    isLoading.value = true;
    errorMessage.value = '';
    
    try {
      final response = await _apiClient.dio.post('/login', data: {
        'email': email,
        'password': password,
      });

      if (response.data['success'] == true) {
        final token = response.data['data']['access_token'];
        final role = response.data['data']['user']['role'];
        
        final prefs = await SharedPreferences.getInstance();
        await prefs.setString('access_token', token);
        await prefs.setString('user_role', role);
        
        return true;
      } else {
        errorMessage.value = response.data['message'] ?? 'Login failed';
        return false;
      }
    } on DioException catch (e) {
      if (e.response != null) {
        errorMessage.value = e.response?.data['message'] ?? 'Email atau password salah';
      } else {
        errorMessage.value = 'Koneksi internet bermasalah';
      }
      return false;
    } finally {
      isLoading.value = false;
    }
  }

  Future<void> logout() async {
    try {
      await _apiClient.dio.post('/logout');
    } catch (e) {
      // Abaikan error saat logout (mungkin token sudah expire di server)
    } finally {
      final prefs = await SharedPreferences.getInstance();
      await prefs.remove('access_token');
      await prefs.remove('user_role');
      // Redirect ke login
    }
  }
}
