<?php
declare(strict_types=1);
namespace App\Livewire;


// use Spatie\Tags\Tag;
use App\Models\Tag;
use App\Models\Product;
use Livewire\Component;
use App\Data\ProductData;
use Livewire\WithPagination;
use App\Data\ProductCollectionData;

class ProductCatalog extends Component
{
    use WithPagination; // Trait Livewire untuk mendukung pagination

    // Properti untuk sinkronisasi dengan query string agar state filter tetap ada saat refresh
    public $queryString = [
        'selectCollections' => ['except' => []], // Simpan filter koleksi di URL
        'search' => ['except' => []],           // Simpan kata kunci pencarian di URL
        'sortBy' => ['except' => 'newest'],     // Simpan urutan sorting di URL (default newest)
    ];

    // Filter dan kontrol data
    public array $selectCollections = []; // Menyimpan koleksi terpilih
    public string $search = '';           // Kata kunci pencarian produk
    public string $sortBy = 'newest';     // Opsi sorting: newest, latest, price_asc, price_desc

    // Lifecycle hook Livewire: dijalankan saat komponen pertama kali di-mount
    public function mount()
    {
        $this->validate(); // Validasi awal ketika komponen dimuat
    }

    // Aturan validasi untuk properti komponen
    protected function rules()
    {
        return [
            'selectCollections' => 'array',                        // Harus array
            'selectCollections.*' => 'integer|exists:tags,id',    // Setiap item harus integer dan ada di tabel tags
            'search' => 'nullable|string|min:3|max:30',           // Kata kunci opsional, panjang 3-30 karakter
            'sortBy' => 'in:newest,latest,price_asc,price_desc',  // Hanya boleh salah satu dari opsi ini
        ];
    }

    // Dipanggil saat filter diterapkan agar kembali ke halaman pertama
    public function applyFilter()
    {
        $this->validate(); // Validasi input sebelum apply filter
        $this->resetPage(); // Reset ke halaman pertama agar data sesuai filter baru
    }

    // Reset semua filter ke kondisi default
    public function resetFilter()
    {
        $this->selectCollections = []; // Hapus filter koleksi
        $this->search = '';            // Kosongkan pencarian
        $this->sortBy = 'newest';      // Reset sorting ke default

        $this->resetErrorBag();        // Bersihkan pesan error validasi
        $this->resetPage();            // Kembali ke halaman pertama
    }

    public function render()
    {
        // Siapkan data default kosong untuk produk & koleksi
        $collection = ProductCollectionData::collect([]);
        $products = ProductData::collect([]);

        // Jika ada error validasi, tampilkan view dengan data kosong
        if ($this->getErrorBag()->isNotEmpty()) {
            return view('livewire.product-catalog', compact('products', 'collection'));
        }

        // Ambil semua tag bertipe 'collection' dan hitung jumlah produk di setiap koleksi
        $result_collection = Tag::query()
            ->withType('collection')
            ->withCount('products')
            ->get();

        // Query dasar untuk produk
        $query = Product::query();

        // Filter berdasarkan kata kunci pencarian
        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        // Filter berdasarkan koleksi yang dipilih
        if (!empty($this->selectCollections)) {
            $query->whereHas('tags', function ($query) {
                $query->whereIn('id', $this->selectCollections);
            });
        }

        // Terapkan sorting berdasarkan pilihan user
        switch ($this->sortBy) {
            case 'latest': // Urutkan produk dari yang paling lama
                $query->oldest();
                break;
            case 'price_asc': // Urutkan harga termurah dulu
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc': // Urutkan harga termahal dulu
                $query->orderBy('price', 'desc');
                break;
            default: // Default: produk terbaru dulu
                $query->latest();
                break;
        }

        // Konversi hasil query ke bentuk data menggunakan Laravel Data
        $products = ProductData::collect(
            $query->paginate(10) // Paginasi: 10 produk per halaman
        );

        // Ambil data koleksi yang sudah diproses
        $collection = ProductCollectionData::collect($result_collection);

        // Kirim data ke view Livewire
        return view('livewire.product-catalog', compact('products', 'collection'));
    }
}


