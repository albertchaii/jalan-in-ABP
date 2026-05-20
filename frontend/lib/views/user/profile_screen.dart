import 'package:flutter/material.dart';
import 'package:jalan_in/views/notifications/notifications_popup.dart';
import 'package:provider/provider.dart';
import 'package:jalan_in/providers/auth_provider.dart';

class ProfileScreen extends StatelessWidget {
  const ProfileScreen({super.key});

  static const _primaryRed = Color(0xFF8A0B14);
  static const _bgPink = Color(0xFFFEF9F9);
  static const _cardPink = Color(0xFFFFF2F1);

  @override
  Widget build(BuildContext context) {
    final authProvider = Provider.of<AuthProvider>(context);
    final userName = authProvider.name ?? 'Memuat...';
    final userEmail = authProvider.email ?? 'Memuat...';

    return Scaffold(
      backgroundColor: _bgPink,
      appBar: AppBar(
        backgroundColor: _bgPink,
        elevation: 0,
        automaticallyImplyLeading: false,
        title: const Text(
          'jalan.in',
          style: TextStyle(
            color: _primaryRed,
            fontWeight: FontWeight.w900,
            fontSize: 24,
            letterSpacing: -0.5,
          ),
        ),
        centerTitle: true,
        actions: [
          IconButton(
            icon: const Icon(Icons.notifications_none, color: _primaryRed),
            onPressed: () => showNotificationsPopup(context),
          ),
        ],
      ),
      body: ListView(
        padding: const EdgeInsets.fromLTRB(20, 18, 20, 24),
        children: [
          _MemberCard(
            name: userName,
            email: userEmail,
            onEditProfile: () {},
            onLogout: () async {
              await Provider.of<AuthProvider>(context, listen: false).logout();
            },
          ),
          const SizedBox(height: 24),
          const Text(
            'Laporan Anda',
            style: TextStyle(
              fontSize: 22,
              fontWeight: FontWeight.w900,
              color: Colors.black,
              letterSpacing: -0.2,
            ),
          ),
          const SizedBox(height: 6),
          Text(
            'Lacak status laporan jalan yang Anda kirimkan',
            style: TextStyle(
              fontSize: 13,
              color: Colors.black.withOpacity(0.55),
              fontWeight: FontWeight.w600,
            ),
          ),
          const SizedBox(height: 16),
          // Placeholder: in real app these come from API
          _HistoryTile(
            status: ReportStatus.reported,
            title: 'Lubang besar di jalan...',
            city: 'Jakarta Pusat',
            dateLabel: '24 OKT 2023',
            cardColor: _cardPink,
            primaryRed: _primaryRed,
            onTap: () {},
          ),
          const SizedBox(height: 12),
          _HistoryTile(
            status: ReportStatus.processing,
            title: 'Trotoar retak di...',
            city: 'Jakarta Selatan',
            dateLabel: '18 OKT 2023',
            cardColor: _cardPink,
            primaryRed: _primaryRed,
            onTap: () {},
          ),
          const SizedBox(height: 12),
          _HistoryTile(
            status: ReportStatus.done,
            title: 'Zebra cross pudar di...',
            city: 'Jakarta Timur',
            dateLabel: '12 OKT 2023',
            cardColor: _cardPink,
            primaryRed: _primaryRed,
            onTap: () {},
          ),
        ],
      ),
    );
  }
}

enum ReportStatus { reported, processing, done }

class _MemberCard extends StatelessWidget {
  const _MemberCard({
    required this.name,
    required this.email,
    required this.onEditProfile,
    required this.onLogout,
  });

  final String name;
  final String email;
  final VoidCallback onEditProfile;
  final VoidCallback onLogout;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.fromLTRB(18, 18, 18, 16),
      decoration: BoxDecoration(
        color: ProfileScreen._cardPink,
        borderRadius: BorderRadius.circular(18),
      ),
      child: Column(
        children: [
          Text(
            'ANGGOTA KOMUNITAS',
            style: TextStyle(
              fontSize: 11,
              fontWeight: FontWeight.w900,
              letterSpacing: 1.0,
              color: Colors.black.withOpacity(0.35),
            ),
          ),
          const SizedBox(height: 10),
          Text(
            name,
            style: const TextStyle(
              fontSize: 26,
              fontWeight: FontWeight.w900,
              letterSpacing: -0.4,
            ),
            textAlign: TextAlign.center,
          ),
          const SizedBox(height: 6),
          Text(
            email,
            style: TextStyle(
              fontSize: 13,
              fontWeight: FontWeight.w700,
              color: Colors.black.withOpacity(0.5),
            ),
            textAlign: TextAlign.center,
          ),
          const SizedBox(height: 14),
          SizedBox(
            height: 42,
            child: ElevatedButton.icon(
              onPressed: onEditProfile,
              icon: const Icon(Icons.edit, size: 18),
              label: const Text('Ubah Profil', style: TextStyle(fontWeight: FontWeight.w900)),
              style: ElevatedButton.styleFrom(
                backgroundColor: ProfileScreen._primaryRed,
                foregroundColor: Colors.white,
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                padding: const EdgeInsets.symmetric(horizontal: 18),
                elevation: 0,
              ),
            ),
          ),
          const SizedBox(height: 12),
          TextButton.icon(
            onPressed: onLogout,
            icon: const Icon(Icons.logout, size: 18),
            label: const Text('Keluar', style: TextStyle(fontWeight: FontWeight.w900)),
            style: TextButton.styleFrom(foregroundColor: ProfileScreen._primaryRed),
          ),
        ],
      ),
    );
  }
}

class _HistoryTile extends StatelessWidget {
  const _HistoryTile({
    required this.status,
    required this.title,
    required this.city,
    required this.dateLabel,
    required this.cardColor,
    required this.primaryRed,
    required this.onTap,
  });

  final ReportStatus status;
  final String title;
  final String city;
  final String dateLabel;
  final Color cardColor;
  final Color primaryRed;
  final VoidCallback onTap;

  @override
  Widget build(BuildContext context) {
    final badge = _badgeFor(status, primaryRed);

    return Material(
      color: cardColor,
      borderRadius: BorderRadius.circular(16),
      child: InkWell(
        onTap: onTap,
        borderRadius: BorderRadius.circular(16),
        child: Padding(
          padding: const EdgeInsets.all(12),
          child: Row(
            children: [
              ClipRRect(
                borderRadius: BorderRadius.circular(12),
                child: Container(
                  width: 54,
                  height: 54,
                  color: Colors.black.withOpacity(0.06),
                  child: Icon(Icons.image_outlined, color: Colors.black.withOpacity(0.35)),
                ),
              ),
              const SizedBox(width: 12),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      children: [
                        _Badge(text: badge.label, bg: badge.bg, fg: badge.fg),
                        const Spacer(),
                        Text(
                          dateLabel,
                          style: TextStyle(
                            fontSize: 11,
                            fontWeight: FontWeight.w800,
                            color: Colors.black.withOpacity(0.45),
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 8),
                    Text(
                      title,
                      maxLines: 1,
                      overflow: TextOverflow.ellipsis,
                      style: const TextStyle(
                        fontSize: 15,
                        fontWeight: FontWeight.w900,
                        letterSpacing: -0.2,
                      ),
                    ),
                    const SizedBox(height: 4),
                    Row(
                      children: [
                        Icon(Icons.location_on_outlined, size: 14, color: Colors.black.withOpacity(0.5)),
                        const SizedBox(width: 4),
                        Text(
                          city,
                          style: TextStyle(
                            fontSize: 12,
                            fontWeight: FontWeight.w800,
                            color: Colors.black.withOpacity(0.5),
                          ),
                        ),
                      ],
                    ),
                  ],
                ),
              ),
              const SizedBox(width: 10),
              Icon(Icons.chevron_right, color: Colors.black.withOpacity(0.35)),
            ],
          ),
        ),
      ),
    );
  }

  _StatusBadge _badgeFor(ReportStatus s, Color red) {
    switch (s) {
      case ReportStatus.reported:
        return _StatusBadge(label: 'DILAPORKAN', bg: red.withOpacity(0.10), fg: red);
      case ReportStatus.processing:
        return _StatusBadge(
          label: 'DIPROSES',
          bg: const Color(0xFF2F6BFF).withOpacity(0.12),
          fg: const Color(0xFF2F6BFF),
        );
      case ReportStatus.done:
        return _StatusBadge(
          label: 'SELESAI',
          bg: const Color(0xFF14A44D).withOpacity(0.14),
          fg: const Color(0xFF14A44D),
        );
    }
  }
}

class _StatusBadge {
  const _StatusBadge({required this.label, required this.bg, required this.fg});
  final String label;
  final Color bg;
  final Color fg;
}

class _Badge extends StatelessWidget {
  const _Badge({required this.text, required this.bg, required this.fg});
  final String text;
  final Color bg;
  final Color fg;

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 6),
      decoration: BoxDecoration(color: bg, borderRadius: BorderRadius.circular(10)),
      child: Text(
        text,
        style: TextStyle(color: fg, fontSize: 10, fontWeight: FontWeight.w900, letterSpacing: 0.4),
      ),
    );
  }
}
