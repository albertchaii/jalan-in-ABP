import 'package:flutter/material.dart';
import 'package:jalan_in/views/auth/map_screen.dart';
import 'package:jalan_in/views/report/camera_screen.dart';
import 'package:jalan_in/views/user/profile_screen.dart';

class HomeShell extends StatefulWidget {
  const HomeShell({super.key, this.initialIndex = 0});

  final int initialIndex;

  @override
  State<HomeShell> createState() => _HomeShellState();
}

class _HomeShellState extends State<HomeShell> {
  static const _primaryRed = Color(0xFF8A0B14);
  static const _bgPink = Color(0xFFFEF9F9);

  late int _index;
  late final Set<int> _builtTabs;

  void _setIndex(int next) {
    if (next == _index) return;
    setState(() => _index = next);
    _builtTabs.add(next);
  }

  @override
  void initState() {
    super.initState();
    _index = widget.initialIndex.clamp(0, 2);
    _builtTabs = <int>{_index};
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: IndexedStack(
        index: _index,
        children: [
          _builtTabs.contains(0)
              ? MapScreen(onGoToReport: () => _setIndex(1))
              : const SizedBox.shrink(),
          _builtTabs.contains(1) ? const CameraScreen() : const SizedBox.shrink(),
          _builtTabs.contains(2) ? const ProfileScreen() : const SizedBox.shrink(),
        ],
      ),
      bottomNavigationBar: SafeArea(
        top: false,
        child: Container(
          padding: const EdgeInsets.fromLTRB(16, 10, 16, 12),
          color: _bgPink,
          child: ClipRRect(
            borderRadius: BorderRadius.circular(22),
            child: DecoratedBox(
              decoration: BoxDecoration(
                color: _primaryRed,
                borderRadius: BorderRadius.circular(22),
                boxShadow: [
                  BoxShadow(
                    color: Colors.black.withOpacity(0.12),
                    blurRadius: 22,
                    offset: const Offset(0, 12),
                  ),
                ],
              ),
              child: Theme(
                data: Theme.of(context).copyWith(
                  navigationBarTheme: NavigationBarThemeData(
                    height: 70,
                    backgroundColor: _primaryRed,
                    elevation: 0,
                    indicatorColor: Colors.white.withOpacity(0.18),
                    labelTextStyle: WidgetStateProperty.resolveWith(
                      (states) => TextStyle(
                        fontSize: 10,
                        fontWeight: FontWeight.w800,
                        letterSpacing: 0.35,
                        color: states.contains(WidgetState.selected)
                            ? Colors.white
                            : Colors.white.withOpacity(0.75),
                      ),
                    ),
                    iconTheme: WidgetStateProperty.resolveWith(
                      (states) => IconThemeData(
                        size: 22,
                        color: states.contains(WidgetState.selected)
                            ? Colors.white
                            : Colors.white.withOpacity(0.75),
                      ),
                    ),
                  ),
                ),
                child: NavigationBar(
                  selectedIndex: _index,
                  onDestinationSelected: _setIndex,
                  destinations: const [
                    NavigationDestination(
                      icon: Icon(Icons.map_outlined),
                      selectedIcon: Icon(Icons.map),
                      label: 'PETA',
                    ),
                    NavigationDestination(
                      icon: Icon(Icons.camera_alt_outlined),
                      selectedIcon: Icon(Icons.camera_alt),
                      label: 'LAPOR',
                    ),
                    NavigationDestination(
                      icon: Icon(Icons.person_outline),
                      selectedIcon: Icon(Icons.person),
                      label: 'PROFIL',
                    ),
                  ],
                ),
              ),
            ),
          ),
        ),
      ),
    );
  }
}