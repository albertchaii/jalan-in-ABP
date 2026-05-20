import 'package:flutter/material.dart';
import 'package:provider/provider.dart'; // --- DITAMBAHKAN: Import Provider ---
import '../../providers/auth_provider.dart'; // --- DITAMBAHKAN: Import AuthProvider ---
import 'register_screen.dart'; 

// --- DIUBAH: Menjadi StatefulWidget agar bisa pakai Controller & Loading ---
class LoginScreen extends StatefulWidget {
  const LoginScreen({super.key});

  @override
  State<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  // Mendefinisikan warna sesuai mockup agar konsisten
  final Color primaryRed = const Color(0xFF8A0B14); 
  final Color bgPink = const Color(0xFFFEF9F9); 
  final Color fieldBgColor = const Color(0xFFF7EBEA); 
  final Color textDark = const Color(0xFF333333);
  final Color textGrey = const Color(0xFF666666);

  // --- DITAMBAHKAN: Variabel State ---
  bool _isLoading = false;
  bool _obscurePassword = true; // Untuk toggle mata password

  // --- DITAMBAHKAN: Controller untuk menangkap input teks ---
  final TextEditingController _emailController = TextEditingController();
  final TextEditingController _passwordController = TextEditingController();

  @override
  void dispose() {
    _emailController.dispose();
    _passwordController.dispose();
    super.dispose();
  }

  // --- DITAMBAHKAN: Fungsi Logika Login API ---
  void _prosesLogin() async {
    if (_emailController.text.isEmpty || _passwordController.text.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Email dan kata sandi tidak boleh kosong!')),
      );
      return;
    }

    setState(() => _isLoading = true);

    try {
      final authProvider = Provider.of<AuthProvider>(context, listen: false);
      await authProvider.login(
        _emailController.text, 
        _passwordController.text
      );
      // Jika sukses, main.dart akan otomatis memindahkan layar ke MainScreen
    } catch (e) {
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text(e.toString().replaceAll('Exception: ', ''))),
        );
      }
    } finally {
      if (mounted) {
        setState(() => _isLoading = false);
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: bgPink,
      body: SafeArea(
        child: Center(
          child: SingleChildScrollView(
            padding: const EdgeInsets.symmetric(horizontal: 24.0, vertical: 32.0),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                // 1. Logo jalan.in
                Center(
                  child: Text(
                    'jalan.in',
                    style: TextStyle(
                      fontSize: 32,
                      fontWeight: FontWeight.w900,
                      color: primaryRed,
                      letterSpacing: -0.5,
                    ),
                  ),
                ),
                const SizedBox(height: 50),

                // 2. Judul & Subjudul
                Text(
                  'Selamat datang kembali',
                  style: TextStyle(
                    fontSize: 26,
                    fontWeight: FontWeight.bold,
                    color: textDark,
                    letterSpacing: -0.5,
                  ),
                ),
                const SizedBox(height: 12),
                Text(
                  'Masuk ke akun Anda untuk lanjut melaporkan\ndan memperbaiki infrastruktur jalan kita.',
                  style: TextStyle(
                    fontSize: 15,
                    color: textGrey,
                    height: 1.4, 
                  ),
                ),
                const SizedBox(height: 40),

                // 3. Form Input Email
                Text(
                  'EMAIL',
                  style: TextStyle(
                    fontSize: 12,
                    fontWeight: FontWeight.bold,
                    color: textDark.withOpacity(0.8),
                    letterSpacing: 1.0,
                  ),
                ),
                const SizedBox(height: 8),
                TextField(
                  controller: _emailController, // --- DITAMBAHKAN ---
                  keyboardType: TextInputType.emailAddress,
                  decoration: InputDecoration(
                    hintText: 'nama@integrity.org',
                    hintStyle: TextStyle(color: Colors.grey.shade500),
                    prefixIcon: Icon(Icons.alternate_email, color: textDark.withOpacity(0.7)),
                    filled: true,
                    fillColor: fieldBgColor,
                    contentPadding: const EdgeInsets.symmetric(vertical: 18),
                    border: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(12),
                      borderSide: BorderSide.none,
                    ),
                  ),
                ),
                const SizedBox(height: 24),

                // 4. Form Input Kata Sandi & Lupa Kata Sandi
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Text(
                      'KATA SANDI',
                      style: TextStyle(
                        fontSize: 12,
                        fontWeight: FontWeight.bold,
                        color: textDark.withOpacity(0.8),
                        letterSpacing: 1.0,
                      ),
                    ),
                    GestureDetector(
                      onTap: () {
                        // TODO: Tambahkan navigasi ke Lupa Kata Sandi nantinya
                      },
                      child: Text(
                        'Lupa Kata Sandi?',
                        style: TextStyle(
                          fontSize: 12,
                          fontWeight: FontWeight.bold,
                          color: primaryRed,
                        ),
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 8),
                TextField(
                  controller: _passwordController, // --- DITAMBAHKAN ---
                  obscureText: _obscurePassword, // --- DIUBAH ---
                  decoration: InputDecoration(
                    hintText: '••••••••',
                    hintStyle: TextStyle(color: Colors.grey.shade500, letterSpacing: 4.0),
                    prefixIcon: Icon(Icons.lock_outline, color: textDark.withOpacity(0.7)),
                    // --- DITAMBAHKAN: Ikon mata untuk melihat password ---
                    suffixIcon: IconButton(
                      icon: Icon(
                        _obscurePassword ? Icons.visibility_off_outlined : Icons.visibility_outlined,
                        color: textDark.withOpacity(0.7),
                      ),
                      onPressed: () {
                        setState(() {
                          _obscurePassword = !_obscurePassword;
                        });
                      },
                    ),
                    filled: true,
                    fillColor: fieldBgColor,
                    contentPadding: const EdgeInsets.symmetric(vertical: 18),
                    border: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(12),
                      borderSide: BorderSide.none,
                    ),
                  ),
                ),
                const SizedBox(height: 40),

                // 5. Tombol Masuk
                SizedBox(
                  width: double.infinity,
                  height: 56,
                  child: ElevatedButton(
                    // --- DIUBAH: Panggil fungsi login ---
                    onPressed: _isLoading ? null : _prosesLogin, 
                    style: ElevatedButton.styleFrom(
                      backgroundColor: primaryRed,
                      foregroundColor: Colors.white,
                      elevation: 0,
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(12),
                      ),
                    ),
                    // --- DIUBAH: Efek loading pada tombol ---
                    child: _isLoading
                        ? const SizedBox(
                            height: 24,
                            width: 24,
                            child: CircularProgressIndicator(
                              color: Colors.white,
                              strokeWidth: 3,
                            ),
                          )
                        : Row(
                            mainAxisAlignment: MainAxisAlignment.center,
                            children: const [
                              Text(
                                'Masuk',
                                style: TextStyle(
                                  fontSize: 16,
                                  fontWeight: FontWeight.bold,
                                ),
                              ),
                              SizedBox(width: 8),
                              Icon(Icons.arrow_forward, size: 20),
                            ],
                          ),
                  ),
                ),
                const SizedBox(height: 40),

                // 6. Teks Belum Punya Akun -> Daftar
                Center(
                  child: GestureDetector(
                    onTap: () {
                      Navigator.push(
                        context,
                        MaterialPageRoute(builder: (context) => const RegisterScreen()),
                      );
                    },
                    child: RichText(
                      text: TextSpan(
                        text: 'Belum punya akun? ',
                        style: TextStyle(color: textGrey, fontSize: 14),
                        children: [
                          TextSpan(
                            text: 'Daftar',
                            style: TextStyle(
                              color: primaryRed,
                              fontWeight: FontWeight.bold,
                            ),
                          ),
                        ],
                      ),
                    ),
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}