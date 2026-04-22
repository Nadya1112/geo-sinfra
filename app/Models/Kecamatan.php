namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    // Nama tabel di database
    protected $table = 'kecamatan';
    protected $primaryKey = 'id_kecamatan';

    // Kolom yang boleh diisi
    protected $fillable = ['nama_kecamatan', 'warna'];

    // Hubungan ke Kelurahan
    public function kelurahans()
    {
        return $this->hasMany(Kelurahan::class, 'id_kecamatan');
    }
}