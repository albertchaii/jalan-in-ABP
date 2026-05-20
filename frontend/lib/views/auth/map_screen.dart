import 'package:flutter/material.dart';
import 'package:flutter_map/flutter_map.dart';
import 'package:latlong2/latlong.dart'; // Package khusus koordinat untuk flutter_map
import 'package:jalan_in/views/notifications/notifications_popup.dart';
import 'package:provider/provider.dart';
import 'package:jalan_in/providers/report_provider.dart';
import 'package:geolocator/geolocator.dart';

class MapScreen extends StatefulWidget {
  const MapScreen({super.key, this.onGoToReport});

  final VoidCallback? onGoToReport;

  @override
  State<MapScreen> createState() => _MapScreenState();
}

class _MapScreenState extends State<MapScreen> {
  final Color primaryRed = const Color(0xFF8A0B14);
  final Color bgPink = const Color(0xFFFEF9F9);
  
  // Koordinat default (Tengah Indonesia)
  LatLng _center = const LatLng(-0.789275, 113.921327);

  Map<String, dynamic>? _selectedReport;
  Set<String> _selectedStatuses = {'Dilaporkan', 'Disurvey', 'Tidak Valid', 'Diproses', 'Selesai'};
  final MapController _mapController = MapController();

  @override
  void initState() {
    super.initState();
    Future.microtask(() {
      Provider.of<ReportProvider>(context, listen: false).fetchReportsMap();
    });
    _determinePosition();
  }

  Future<void> _determinePosition() async {
    bool serviceEnabled;
    LocationPermission permission;

    serviceEnabled = await Geolocator.isLocationServiceEnabled();
    if (!serviceEnabled) return;

    permission = await Geolocator.checkPermission();
    if (permission == LocationPermission.denied) {
      permission = await Geolocator.requestPermission();
      if (permission == LocationPermission.denied) return;
    }
    if (permission == LocationPermission.deniedForever) return;

    Position position = await Geolocator.getCurrentPosition();
    if (mounted) {
      setState(() {
        _center = LatLng(position.latitude, position.longitude);
      });
      _mapController.move(_center, 15.0);
    }
  }

  Color _getStatusColor(String status) {
    switch (status) {
      case 'Dilaporkan': return Colors.red;
      case 'Disurvey': return Colors.amber;
      case 'Tidak Valid': return Colors.blueGrey.shade700;
      case 'Diproses': return Colors.blue.shade900;
      case 'Selesai': return Colors.green.shade600;
      default: return Colors.grey;
    }
  }

  void _showFilterDialog(BuildContext context) {
    showDialog(
      context: context,
      builder: (BuildContext context) {
        return StatefulBuilder(
          builder: (context, setStateDialog) {
            return Dialog(
              backgroundColor: Colors.white,
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(24)),
              child: Padding(
                padding: const EdgeInsets.all(24.0),
                child: Column(
                  mainAxisSize: MainAxisSize.min,
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        const Text(
                          'Filter',
                          style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
                        ),
                        TextButton(
                          onPressed: () {
                            setStateDialog(() {
                              _selectedStatuses.clear();
                            });
                            setState(() {}); // trigger rebuild map
                          },
                          child: const Text(
                            'Bersihkan Semua',
                            style: TextStyle(color: Color(0xFF8A0B14), fontWeight: FontWeight.bold, fontSize: 16),
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 24),
                    Text(
                      'STATUS KERUSAKAN',
                      style: TextStyle(fontSize: 14, fontWeight: FontWeight.bold, color: Colors.grey.shade500, letterSpacing: 1.2),
                    ),
                    const SizedBox(height: 16),
                    _buildFilterCheckbox('Dilaporkan', Colors.red, setStateDialog),
                    const SizedBox(height: 8),
                    _buildFilterCheckbox('Disurvey', Colors.amber, setStateDialog),
                    const SizedBox(height: 8),
                    _buildFilterCheckbox('Tidak Valid', Colors.blueGrey.shade700, setStateDialog),
                    const SizedBox(height: 8),
                    _buildFilterCheckbox('Diproses', Colors.blue, setStateDialog),
                    const SizedBox(height: 8),
                    _buildFilterCheckbox('Selesai', Colors.green, setStateDialog),
                  ],
                ),
              ),
            );
          },
        );
      },
    );
  }

  Widget _buildFilterCheckbox(String status, Color color, StateSetter setStateDialog) {
    bool isSelected = _selectedStatuses.contains(status);
    return InkWell(
      onTap: () {
        setStateDialog(() {
          if (isSelected) {
            _selectedStatuses.remove(status);
          } else {
            _selectedStatuses.add(status);
          }
        });
        setState(() {}); // trigger rebuild map
      },
      child: Padding(
        padding: const EdgeInsets.symmetric(vertical: 8.0),
        child: Row(
          children: [
            Container(
              width: 28,
              height: 28,
              decoration: BoxDecoration(
                color: isSelected ? Colors.blue.shade600 : Colors.transparent,
                border: Border.all(color: isSelected ? Colors.blue.shade600 : Colors.grey.shade500, width: 2),
                borderRadius: BorderRadius.circular(4),
              ),
              child: isSelected ? const Icon(Icons.check, size: 20, color: Colors.white) : null,
            ),
            const SizedBox(width: 16),
            Container(
              width: 16,
              height: 16,
              decoration: BoxDecoration(
                color: color,
                shape: BoxShape.circle,
              ),
            ),
            const SizedBox(width: 12),
            Text(
              status,
              style: TextStyle(fontSize: 18, color: Colors.grey.shade800),
            ),
          ],
        ),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: bgPink,
      appBar: AppBar(
        backgroundColor: bgPink,
        elevation: 0,
        automaticallyImplyLeading: false,
        title: const Text(
          'jalan.in',
          style: TextStyle(
            color: Color(0xFF8A0B14),
            fontWeight: FontWeight.w900,
            fontSize: 24,
            letterSpacing: -0.5,
          ),
        ),
        centerTitle: true,
        actions: [
          IconButton(
            icon: const Icon(Icons.filter_list, color: Color(0xFF8A0B14)),
            onPressed: () => _showFilterDialog(context),
          ),
          IconButton(
            icon: const Icon(Icons.notifications_none, color: Color(0xFF8A0B14)),
            onPressed: () => showNotificationsPopup(context),
          ),
        ],
      ),
      
      body: Stack(
        children: [
          // Lapis 1: flutter_map (Leaflet)
          FlutterMap(
            mapController: _mapController,
            options: MapOptions(
              initialCenter: _center,
              initialZoom: 5.0,
              // Batasi pergerakan peta agar tidak terlalu jauh (opsional)
              interactionOptions: const InteractionOptions(
                flags: InteractiveFlag.all & ~InteractiveFlag.rotate, // Matikan rotasi agar peta tegak
              ),
            ),
            children: [
              // Mengambil gambar peta dari OpenStreetMap
              TileLayer(
                urlTemplate: 'https://tile.openstreetmap.org/{z}/{x}/{y}.png',
                userAgentPackageName: 'com.jalan_in.app',
                // Nanti Anda bisa mengganti URL template ini dengan style peta Dark Mode 
                // dari penyedia pihak ketiga seperti Mapbox atau Stadia Maps secara gratis.
              ),
              // Layer untuk menaruh pin / marker
              Consumer<ReportProvider>(
                builder: (context, provider, child) {
                  return MarkerLayer(
                    markers: provider.mapReports.where((report) {
                      final String status = report['status'] ?? 'Dilaporkan';
                      return _selectedStatuses.contains(status);
                    }).map((report) {
                      final double lat = double.tryParse(report['latitude'].toString()) ?? 0.0;
                      final double lng = double.tryParse(report['longitude'].toString()) ?? 0.0;
                      final String status = report['status'] ?? 'Dilaporkan';
                      return Marker(
                        point: LatLng(lat, lng),
                        width: 40,
                        height: 40,
                        child: GestureDetector(
                          onTap: () {
                            setState(() {
                              _selectedReport = report;
                            });
                            // Zoom to marker for street detail
                            _mapController.move(LatLng(lat, lng), 17.0);
                          },
                          child: Icon(Icons.location_on, color: _getStatusColor(status), size: 40)
                        ),
                      );
                    }).toList(),
                  );
                },
              ),
            ],
          ),

          // Lapis 2: Legend Keterangan Warna
          Positioned(
            top: 16,
            left: 16,
            child: Container(
              padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
              decoration: BoxDecoration(
                color: Colors.white.withOpacity(0.9),
                borderRadius: BorderRadius.circular(12),
                boxShadow: [
                  BoxShadow(color: Colors.black.withOpacity(0.1), blurRadius: 8)
                ],
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  _buildLegendItem(Colors.red.shade700, 'DILAPORKAN'),
                  const SizedBox(height: 8),
                  _buildLegendItem(Colors.yellow.shade600, 'DISURVEI'),
                  const SizedBox(height: 8),
                  _buildLegendItem(Colors.blue.shade900, 'DIPROSES'),
                  const SizedBox(height: 8),
                  _buildLegendItem(Colors.green.shade600, 'SELESAI'),
                ],
              ),
            ),
          ),

          // Lapis 3: Tombol Aksi Kanan Atas
          Positioned(
            top: 16,
            right: 16,
            child: Column(
              children: [
                // Tombol Plus Besar
                Container(
                  height: 56,
                  width: 56,
                  decoration: BoxDecoration(
                    color: primaryRed,
                    borderRadius: BorderRadius.circular(16),
                    boxShadow: [
                      BoxShadow(color: primaryRed.withOpacity(0.4), blurRadius: 8, offset: const Offset(0, 4))
                    ],
                  ),
                  child: IconButton(
                    icon: const Icon(Icons.add, color: Colors.white, size: 32),
                    onPressed: () {
                      widget.onGoToReport?.call();
                    },
                  ),
                ),
                const SizedBox(height: 16),
                // Tombol Zoom (In/Out)
                Container(
                  decoration: BoxDecoration(
                    color: Colors.white.withOpacity(0.9),
                    borderRadius: BorderRadius.circular(12),
                    boxShadow: [
                      BoxShadow(color: Colors.black.withOpacity(0.1), blurRadius: 8)
                    ],
                  ),
                  child: Column(
                    children: [
                      IconButton(
                        icon: const Icon(Icons.add, color: Colors.black87),
                        onPressed: () {
                          final currentZoom = _mapController.camera.zoom;
                          _mapController.move(_mapController.camera.center, currentZoom + 1);
                        },
                      ),
                      Container(height: 1, width: 30, color: Colors.grey.shade300),
                      IconButton(
                        icon: const Icon(Icons.remove, color: Colors.black87),
                        onPressed: () {
                          final currentZoom = _mapController.camera.zoom;
                          _mapController.move(_mapController.camera.center, currentZoom - 1);
                        },
                      ),
                    ],
                  ),
                ),
              ],
            ),
          ),

          // Lapis 4: Kartu Detail Bottom Sheet
          if (_selectedReport != null)
            Positioned(
              bottom: 16,
              left: 16,
              right: 16,
              child: Container(
                padding: const EdgeInsets.all(16),
                decoration: BoxDecoration(
                  color: Colors.white,
                  borderRadius: BorderRadius.circular(24),
                  boxShadow: [
                    BoxShadow(color: Colors.black.withOpacity(0.15), blurRadius: 20, offset: const Offset(0, 5))
                  ],
                ),
                child: Column(
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    Stack(
                      alignment: Alignment.center,
                      children: [
                        Container(
                          width: double.infinity,
                          alignment: Alignment.center,
                          child: Container(
                            width: 40,
                            height: 4,
                            margin: const EdgeInsets.only(bottom: 16),
                            decoration: BoxDecoration(
                              color: Colors.grey.shade300,
                              borderRadius: BorderRadius.circular(2),
                            ),
                          ),
                        ),
                        Positioned(
                          right: 0,
                          top: 0,
                          child: GestureDetector(
                            onTap: () {
                              setState(() {
                                _selectedReport = null;
                              });
                            },
                            child: const Icon(Icons.close, color: Colors.grey),
                          ),
                        ),
                      ],
                    ),
                    Row(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        ClipRRect(
                          borderRadius: BorderRadius.circular(12),
                          child: _selectedReport!['photo_url'] != null
                              ? Image.network(
                                  _selectedReport!['photo_url'],
                                  width: 80,
                                  height: 80,
                                  fit: BoxFit.cover,
                                  errorBuilder: (context, error, stackTrace) => Container(
                                    width: 80,
                                    height: 80,
                                    color: Colors.grey.shade300,
                                    child: const Icon(Icons.image, color: Colors.grey),
                                  ),
                                )
                              : Container(
                                  width: 80,
                                  height: 80,
                                  color: Colors.grey.shade300,
                                  child: const Icon(Icons.image, color: Colors.grey),
                                ),
                        ),
                        const SizedBox(width: 16),
                        Expanded(
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Container(
                                padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                                decoration: BoxDecoration(
                                  color: _getStatusColor(_selectedReport!['status'] ?? '').withOpacity(0.1),
                                  borderRadius: BorderRadius.circular(8),
                                ),
                                child: Text(
                                  (_selectedReport!['status'] ?? 'DILAPORKAN').toString().toUpperCase(),
                                  style: TextStyle(
                                    fontSize: 10, 
                                    fontWeight: FontWeight.bold, 
                                    color: _getStatusColor(_selectedReport!['status'] ?? '')
                                  ),
                                ),
                              ),
                              const SizedBox(height: 8),
                              Text(
                                _selectedReport!['description'] ?? 'Tanpa deskripsi',
                                style: const TextStyle(fontSize: 16, fontWeight: FontWeight.bold, height: 1.2),
                                maxLines: 2,
                                overflow: TextOverflow.ellipsis,
                              ),
                              const SizedBox(height: 8),
                              Row(
                                children: [
                                  Icon(Icons.access_time, size: 14, color: Colors.grey.shade600),
                                  const SizedBox(width: 4),
                                  Expanded(
                                    child: Text(
                                      _selectedReport!['created_at'] != null 
                                          ? 'Dilaporkan pada ${_selectedReport!['created_at'].toString().split('T')[0]}' 
                                          : 'Waktu tidak diketahui', 
                                      style: TextStyle(fontSize: 12, color: Colors.grey.shade600),
                                      maxLines: 1,
                                      overflow: TextOverflow.ellipsis,
                                    ),
                                  ),
                                ],
                              )
                            ],
                          ),
                        )
                      ],
                    ),
                    const SizedBox(height: 16),
                    Row(
                      children: [
                        Expanded(
                          child: ElevatedButton.icon(
                            onPressed: () {},
                            icon: const Icon(Icons.visibility),
                            label: const Text('Lihat Detail', style: TextStyle(fontWeight: FontWeight.bold)),
                            style: ElevatedButton.styleFrom(
                              backgroundColor: primaryRed,
                              foregroundColor: Colors.white,
                              padding: const EdgeInsets.symmetric(vertical: 12),
                              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                            ),
                          ),
                        ),
                        const SizedBox(width: 12),
                        Container(
                          decoration: BoxDecoration(
                            border: Border.all(color: Colors.grey.shade300),
                            borderRadius: BorderRadius.circular(12),
                          ),
                          child: IconButton(
                            icon: Icon(Icons.directions, color: primaryRed),
                            onPressed: () {},
                          ),
                        )
                      ],
                    )
                  ],
                ),
              ),
            ),
        ],
      ),
    );
  }

  Widget _buildLegendItem(Color color, String text) {
    return Row(
      mainAxisSize: MainAxisSize.min,
      children: [
        Container(
          width: 10,
          height: 10,
          decoration: BoxDecoration(color: color, shape: BoxShape.circle),
        ),
        const SizedBox(width: 8),
        Text(
          text,
          style: TextStyle(fontSize: 10, fontWeight: FontWeight.bold, color: Colors.grey.shade800, letterSpacing: 0.5),
        ),
      ],
    );
  }
}