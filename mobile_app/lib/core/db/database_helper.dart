import 'package:sqflite/sqflite.dart';
import 'package:path/path.dart';

class DatabaseHelper {
  static final DatabaseHelper instance = DatabaseHelper._init();
  static Database? _database;

  DatabaseHelper._init();

  Future<Database> get database async {
    if (_database != null) return _database!;
    _database = await _initDB('geo_sinfra.db');
    return _database!;
  }

  Future<Database> _initDB(String filePath) async {
    final dbPath = await getDatabasesPath();
    final path = join(dbPath, filePath);

    return await openDatabase(
      path,
      version: 1,
      onCreate: _createDB,
    );
  }

  Future _createDB(Database db, int version) async {
    // Buat tabel untuk menyimpan survey secara offline
    await db.execute('''
      CREATE TABLE offline_surveys (
        uuid TEXT PRIMARY KEY,
        id_kelurahan INTEGER,
        nama_objek TEXT,
        nama_infrastruktur TEXT,
        latitude REAL,
        longitude REAL,
        kondisi TEXT,
        foto_path TEXT,
        is_synced INTEGER DEFAULT 0
      )
    ''');
  }

  Future<void> close() async {
    final db = await instance.database;
    db.close();
  }
}
