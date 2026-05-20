import 'package:flutter/material.dart';

void showNotificationsPopup(BuildContext context) {
  showModalBottomSheet(
    context: context,
    isScrollControlled: true,
    backgroundColor: Colors.transparent,
    builder: (context) => const _NotificationsSheet(),
  );
}

class _NotificationsSheet extends StatelessWidget {
  const _NotificationsSheet();

  static const _primaryRed = Color(0xFF8A0B14);
  static const _bgPink = Color(0xFFFEF9F9);

  @override
  Widget build(BuildContext context) {
    return Container(
      height: MediaQuery.of(context).size.height * 0.65,
      decoration: const BoxDecoration(
        color: _bgPink,
        borderRadius: BorderRadius.vertical(top: Radius.circular(28)),
      ),
      child: Column(
        children: [
          const SizedBox(height: 12),
          Container(
            width: 40,
            height: 4,
            decoration: BoxDecoration(
              color: Colors.grey.shade300,
              borderRadius: BorderRadius.circular(2),
            ),
          ),
          const SizedBox(height: 20),
          const Padding(
            padding: EdgeInsets.symmetric(horizontal: 24),
            child: Row(
              children: [
                Text(
                  'Notifikasi',
                  style: TextStyle(
                    fontSize: 22,
                    fontWeight: FontWeight.w900,
                    letterSpacing: -0.4,
                  ),
                ),
              ],
            ),
          ),
          const SizedBox(height: 16),
          Expanded(
            child: ListView(
              padding: const EdgeInsets.symmetric(horizontal: 16),
              children: const [
                _NotifTile(
                  icon: Icons.check_circle,
                  iconColor: Color(0xFF14A44D),
                  title: 'Laporan Anda Selesai',
                  subtitle: 'Laporan jalan rusak di Jakarta Pusat telah selesai diperbaiki.',
                  time: '2 jam lalu',
                ),
                _NotifTile(
                  icon: Icons.engineering,
                  iconColor: Color(0xFF2F6BFF),
                  title: 'Laporan Sedang Diproses',
                  subtitle: 'Tim sedang menangani laporan Anda di Jakarta Selatan.',
                  time: '1 hari lalu',
                ),
                _NotifTile(
                  icon: Icons.info_outline,
                  iconColor: _primaryRed,
                  title: 'Laporan Diterima',
                  subtitle: 'Laporan baru Anda telah berhasil dikirim ke sistem.',
                  time: '3 hari lalu',
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}

class _NotifTile extends StatelessWidget {
  const _NotifTile({
    required this.icon,
    required this.iconColor,
    required this.title,
    required this.subtitle,
    required this.time,
  });

  final IconData icon;
  final Color iconColor;
  final String title;
  final String subtitle;
  final String time;

  @override
  Widget build(BuildContext context) {
    return Container(
      margin: const EdgeInsets.only(bottom: 12),
      padding: const EdgeInsets.all(14),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(16),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.05),
            blurRadius: 8,
            offset: const Offset(0, 2),
          ),
        ],
      ),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Container(
            padding: const EdgeInsets.all(8),
            decoration: BoxDecoration(
              color: iconColor.withOpacity(0.1),
              shape: BoxShape.circle,
            ),
            child: Icon(icon, color: iconColor, size: 20),
          ),
          const SizedBox(width: 12),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  title,
                  style: const TextStyle(
                    fontSize: 14,
                    fontWeight: FontWeight.w800,
                  ),
                ),
                const SizedBox(height: 4),
                Text(
                  subtitle,
                  style: TextStyle(
                    fontSize: 12,
                    color: Colors.grey.shade600,
                  ),
                ),
                const SizedBox(height: 6),
                Text(
                  time,
                  style: TextStyle(
                    fontSize: 11,
                    color: Colors.grey.shade400,
                    fontWeight: FontWeight.w600,
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}
