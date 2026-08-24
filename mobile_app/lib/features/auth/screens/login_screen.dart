import 'dart:ui';
import 'package:flutter/material.dart';
import 'package:get/get.dart';
import '../controllers/auth_controller.dart';
import '../../../core/theme.dart';

class LoginScreen extends StatelessWidget {
  final AuthController authController = Get.put(AuthController());
  final TextEditingController emailController = TextEditingController();
  final TextEditingController passwordController = TextEditingController();
  final RxBool _obscureText = true.obs;

  LoginScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFF070617), // Gelap khas Geo-Sinfra (navy-950)
      body: Stack(
        children: [
          // Background Gradient Base
          Container(
            decoration: const BoxDecoration(
              gradient: RadialGradient(
                center: Alignment.center,
                radius: 1.5,
                colors: [
                  Color.fromRGBO(14, 14, 40, 0.6),
                  Color(0xFF070617),
                ],
              ),
            ),
          ),

          // Orb 1 (Emas - Kanan Atas)
          Positioned(
            top: -100,
            right: -100,
            child: _buildOrb(const Color(0xFFC5A059), 300),
          ),

          // Orb 2 (Ungu/Indigo - Kiri Bawah)
          Positioned(
            bottom: -100,
            left: -80,
            child: _buildOrb(const Color(0xFF6366F1), 250),
          ),

          // Konten Utama
          SafeArea(
            child: Center(
              child: SingleChildScrollView(
                padding: const EdgeInsets.symmetric(horizontal: 28.0, vertical: 24.0),
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.center,
                  crossAxisAlignment: CrossAxisAlignment.stretch,
                  children: [
                    // Logo dengan Glow Ring
                    Center(
                      child: Container(
                        width: 100,
                        height: 100,
                        decoration: BoxDecoration(
                          shape: BoxShape.circle,
                          gradient: const LinearGradient(
                            colors: [AppTheme.gold500, AppTheme.navy500],
                            begin: Alignment.topLeft,
                            end: Alignment.bottomRight,
                          ),
                          boxShadow: [
                            BoxShadow(
                              color: AppTheme.gold500.withOpacity(0.3),
                              blurRadius: 30,
                              spreadRadius: 5,
                            ),
                          ],
                        ),
                        padding: const EdgeInsets.all(3),
                        child: Container(
                          decoration: const BoxDecoration(
                            shape: BoxShape.circle,
                            color: Color(0xFF0F0E2C), // navy-900
                          ),
                          padding: const EdgeInsets.all(16),
                          child: Image.asset(
                            'assets/images/logo.png',
                            fit: BoxFit.contain,
                            errorBuilder: (context, error, stackTrace) => const Icon(
                              Icons.map_rounded,
                              size: 40,
                              color: Colors.white,
                            ),
                          ),
                        ),
                      ),
                    ),
                    const SizedBox(height: 24),
                    
                    // Tulisan GEO-SINFRA
                    const Text(
                      'SISTEM PEMETAAN',
                      textAlign: TextAlign.center,
                      style: TextStyle(
                        fontSize: 11,
                        fontWeight: FontWeight.w900,
                        color: AppTheme.gold500,
                        letterSpacing: 4.0,
                      ),
                    ),
                    const SizedBox(height: 8),
                    RichText(
                      textAlign: TextAlign.center,
                      text: TextTheme.of(context).headlineLarge?.copyWith(
                        fontSize: 36,
                        fontWeight: FontWeight.w900,
                        color: Colors.white,
                        letterSpacing: -1.0,
                      ) ?? const TextStyle(),
                      textScaler: TextScaler.noScaling,
                    ).apply(
                      text: const TextSpan(
                        children: [
                          TextSpan(text: 'GEO', style: TextStyle(color: Colors.white)),
                          TextSpan(text: '-', style: TextStyle(color: AppTheme.gold500)),
                          TextSpan(text: 'SINFRA', style: TextStyle(color: Colors.white)),
                        ],
                      ),
                    ),
                    const SizedBox(height: 12),
                    Text(
                      'Infrastruktur Permukiman Kota Banjarmasin berbasis Web GIS & AI',
                      textAlign: TextAlign.center,
                      style: TextStyle(
                        fontSize: 13,
                        fontWeight: FontWeight.w500,
                        color: Colors.white.withOpacity(0.7),
                        height: 1.5,
                      ),
                    ),
                    
                    const SizedBox(height: 48),

                    // Glassmorphism Form Container
                    ClipRRect(
                      borderRadius: BorderRadius.circular(24),
                      child: BackdropFilter(
                        filter: ImageFilter.blur(sigmaX: 16, sigmaY: 16),
                        child: Container(
                          padding: const EdgeInsets.all(24),
                          decoration: BoxDecoration(
                            color: Colors.white.withOpacity(0.05),
                            borderRadius: BorderRadius.circular(24),
                            border: Border.all(
                              color: Colors.white.withOpacity(0.1),
                              width: 1.5,
                            ),
                          ),
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.stretch,
                            children: [
                              const Text(
                                'PORTAL AKSES',
                                style: TextStyle(
                                  fontSize: 10,
                                  fontWeight: FontWeight.w900,
                                  color: AppTheme.gold500,
                                  letterSpacing: 2.0,
                                ),
                              ),
                              const SizedBox(height: 24),

                              // Error Alert
                              Obx(() {
                                if (authController.errorMessage.value.isNotEmpty) {
                                  return Container(
                                    padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
                                    margin: const EdgeInsets.only(bottom: 24),
                                    decoration: BoxDecoration(
                                      color: Colors.red.withOpacity(0.1),
                                      borderRadius: BorderRadius.circular(12),
                                      border: Border.all(color: Colors.red.withOpacity(0.3)),
                                    ),
                                    child: Row(
                                      children: [
                                        Icon(Icons.error_outline, color: Colors.red.shade400, size: 20),
                                        const SizedBox(width: 12),
                                        Expanded(
                                          child: Text(
                                            authController.errorMessage.value,
                                            style: TextStyle(
                                              color: Colors.red.shade200,
                                              fontSize: 13,
                                              fontWeight: FontWeight.w600,
                                            ),
                                          ),
                                        ),
                                      ],
                                    ),
                                  );
                                }
                                return const SizedBox.shrink();
                              }),

                              // Email Field
                              _buildLabel('Email / Nomor WhatsApp'),
                              TextField(
                                controller: emailController,
                                style: const TextStyle(color: Colors.white, fontWeight: FontWeight.w600),
                                decoration: _buildInputDecoration(
                                  hint: 'Email atau 0812xxxx',
                                  icon: Icons.person_outline,
                                ),
                                keyboardType: TextInputType.emailAddress,
                              ),
                              
                              const SizedBox(height: 20),

                              // Password Field
                              _buildLabel('Kata Sandi'),
                              Obx(() => TextField(
                                controller: passwordController,
                                style: const TextStyle(color: Colors.white, fontWeight: FontWeight.w600),
                                obscureText: _obscureText.value,
                                decoration: _buildInputDecoration(
                                  hint: '••••••••',
                                  icon: Icons.lock_outline,
                                ).copyWith(
                                  suffixIcon: IconButton(
                                    icon: Icon(
                                      _obscureText.value ? Icons.visibility_off : Icons.visibility,
                                      color: Colors.white.withOpacity(0.5),
                                      size: 20,
                                    ),
                                    onPressed: () => _obscureText.value = !_obscureText.value,
                                  ),
                                ),
                              )),
                              
                              const SizedBox(height: 32),

                              // Submit Button (Btn Gold)
                              Obx(() => Container(
                                height: 56,
                                decoration: BoxDecoration(
                                  borderRadius: BorderRadius.circular(14),
                                  gradient: const LinearGradient(
                                    colors: [AppTheme.gold500, Color(0xFFB38F4A)],
                                    begin: Alignment.topLeft,
                                    end: Alignment.bottomRight,
                                  ),
                                  boxShadow: [
                                    BoxShadow(
                                      color: AppTheme.gold500.withOpacity(0.3),
                                      blurRadius: 20,
                                      offset: const Offset(0, 8),
                                    ),
                                  ],
                                ),
                                child: ElevatedButton(
                                  onPressed: authController.isLoading.value
                                      ? null
                                      : () async {
                                          final success = await authController.login(
                                            emailController.text,
                                            passwordController.text,
                                          );
                                          if (success) {
                                            Get.snackbar(
                                              'Berhasil', 
                                              'Selamat datang di Geo-Sinfra',
                                              backgroundColor: Colors.green.shade800,
                                              colorText: Colors.white,
                                            );
                                          }
                                        },
                                  style: ElevatedButton.styleFrom(
                                    backgroundColor: Colors.transparent,
                                    shadowColor: Colors.transparent,
                                    shape: RoundedRectangleBorder(
                                      borderRadius: BorderRadius.circular(14),
                                    ),
                                  ),
                                  child: authController.isLoading.value
                                      ? const SizedBox(
                                          height: 20,
                                          width: 20,
                                          child: CircularProgressIndicator(
                                            strokeWidth: 2, 
                                            color: Colors.white
                                          ),
                                        )
                                      : const Row(
                                          mainAxisAlignment: MainAxisAlignment.center,
                                          children: [
                                            Icon(Icons.login, size: 18, color: Colors.white),
                                            SizedBox(width: 8),
                                            Text(
                                              'MASUK KE SISTEM', 
                                              style: TextStyle(
                                                fontSize: 12, 
                                                fontWeight: FontWeight.w900,
                                                letterSpacing: 2.0,
                                                color: Colors.white,
                                              ),
                                            ),
                                          ],
                                        ),
                                ),
                              )),
                            ],
                          ),
                        ),
                      ),
                    ),
                    
                    const SizedBox(height: 32),

                    // Register Link
                    Row(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        Text(
                          'Belum punya akun? ',
                          style: TextStyle(
                            color: Colors.white.withOpacity(0.7),
                            fontSize: 14,
                            fontWeight: FontWeight.w500,
                          ),
                        ),
                        const Text(
                          'Daftar Sekarang',
                          style: TextStyle(
                            color: AppTheme.gold500,
                            fontSize: 14,
                            fontWeight: FontWeight.w800,
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 40),
                  ],
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildOrb(Color color, double size) {
    return ImageFiltered(
      imageFilter: ImageFilter.blur(sigmaX: 80, sigmaY: 80),
      child: Container(
        width: size,
        height: size,
        decoration: BoxDecoration(
          shape: BoxShape.circle,
          color: color.withOpacity(0.35),
        ),
      ),
    );
  }

  Widget _buildLabel(String text) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 8.0),
      child: Row(
        children: [
          Text(
            text.toUpperCase(),
            style: TextStyle(
              fontSize: 10,
              fontWeight: FontWeight.w800,
              color: Colors.white.withOpacity(0.7),
              letterSpacing: 1.5,
            ),
          ),
          const SizedBox(width: 4),
          const Text('*', style: TextStyle(color: AppTheme.gold500, fontWeight: FontWeight.bold)),
        ],
      ),
    );
  }

  InputDecoration _buildInputDecoration({required String hint, required IconData icon}) {
    return InputDecoration(
      hintText: hint,
      hintStyle: TextStyle(
        color: Colors.white.withOpacity(0.3),
        fontWeight: FontWeight.w500,
        fontSize: 14,
      ),
      prefixIcon: Icon(icon, color: Colors.white.withOpacity(0.5), size: 20),
      filled: true,
      fillColor: Colors.white.withOpacity(0.05),
      contentPadding: const EdgeInsets.symmetric(horizontal: 20, vertical: 16),
      border: OutlineInputBorder(
        borderRadius: BorderRadius.circular(14),
        borderSide: BorderSide(color: Colors.white.withOpacity(0.1), width: 1.5),
      ),
      enabledBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(14),
        borderSide: BorderSide(color: Colors.white.withOpacity(0.1), width: 1.5),
      ),
      focusedBorder: OutlineInputBorder(
        borderRadius: BorderRadius.circular(14),
        borderSide: const BorderSide(color: AppTheme.gold500, width: 1.5),
      ),
    );
  }
}
