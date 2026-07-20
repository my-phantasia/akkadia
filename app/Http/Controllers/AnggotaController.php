// app/Http/Controllers/AnggotaController.php
namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Services\RekomendasiService;
use Illuminate\Http\Request;

class AnggotaController extends Controller
{
    public function __construct(private readonly RekomendasiService $rekomendasiService) {}

    public function index()
    {
        $anggotas = Anggota::latest()->paginate(10);
        return view('anggota.index', compact('anggotas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:anggotas,email'],
            'telepon' => ['nullable', 'string', 'max:20'],
        ]);

        Anggota::create($validated);
        return redirect()->route('anggota.index')->with('success', 'Anggota berhasil terdaftar.');
    }

    // Req #6: Ditampilkan bersama detail profil anggota
    public function show(Anggota $anggota)
    {
        $anggota->load('peminjamans.detailPeminjamans.buku');
        $rekomendasiBuku = $this->rekomendasiService->getRekomendasiUntukAnggota($anggota->id);

        return view('anggota.show', compact('anggota', 'rekomendasiBuku'));
    }

    public function update(Request $request, Anggota $anggota)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:anggotas,email,' . $anggota->id],
            'telepon' => ['nullable', 'string', 'max:20'],
        ]);

        $anggota->update($validated);
        return redirect()->route('anggota.index')->with('success', 'Data anggota berhasil diperbarui.');
    }

    public function destroy(Anggota $anggota)
    {
        $anggota->delete();
        return redirect()->route('anggota.index')->with('success', 'Anggota berhasil dihapus.');
    }
}
