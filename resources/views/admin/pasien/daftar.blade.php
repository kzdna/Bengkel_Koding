<x-layouts.app title="Daftar Poli">
    <div class="flex items-center justify-center px-4">
        <div class="w-full max-w-3xl">
            <div class="card bg-base-100 shadow">
                <div class="card-body">
                    <h2 class="text-2xl font-bold text-center mb-6">🏥 Pendaftaran Poli</h2>

                    @if (session('message'))
                    <div class="alert alert-success mb-4">
                        <span>{{ session('message') }}</span>
                    </div>
                    @endif

                    <form action="{{ route('pasien.daftar.submit') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id_pasien" value="{{ $user->id }}">

                        <div class="mb-4">
                            <label class="font-semibold block mb-1">Nomor Rekam Medis</label>
                            <input type="text" value="{{ $user->no_rm }}" class="w-full border-2 rounded-lg p-2 bg-gray-100" disabled>
                        </div>

                        <div class="mb-4">
                            <label class="font-semibold block mb-1">Pilih Poli</label>
                            <select name="id_poli" id="poliSelect" class="w-full border-2 rounded-lg p-2">
                                <option value="">-- Pilih Poli --</option>
                                @foreach ($polis as $poli)
                                <option value="{{ $poli->id }}">{{ $poli->nama_poli }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="font-semibold block mb-1">Pilih Jadwal Periksa</label>
                            <select name="id_jadwal" id="jadwalSelect" class="w-full border-2 rounded-lg p-2">
                                <option value="">-- Pilih Jadwal --</option>
                            </select>
                        </div>

                        <div class="mb-6">
                            <label class="font-semibold block mb-1">Keluhan</label>
                            <textarea name="keluhan" rows="3" class="w-full border-2 rounded-lg p-2" placeholder="Tulis keluhan anda..."></textarea>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="px-8 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg">
                                Daftar Poli
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const poliSelect = document.getElementById("poliSelect");
            const jadwalSelect = document.getElementById("jadwalSelect");
            
            const dataJadwal = [
    @foreach ($jadwals as $j)
    {
        id: "{{ $j->id }}",
        poli_id: "{{ $j->dokter->id_poli ?? '' }}", 
        // Menggunakan offsetGet untuk bypass semua proteksi Laravel
        info: "{{ $j->hari }} | {{ substr($j->getAttributes()['jam_mulai'], 0, 5) }} - {{ substr($j->getAttributes()['jam_selesai'], 0, 5) }} (Dr. {{ $j->dokter->nama ?? 'Tanpa Nama' }})"
    },
    @endforeach
];

            console.log("Jadwal Berhasil Di-load:", dataJadwal);

            poliSelect.addEventListener("change", function() {
                const idTerpilih = this.value;
                console.log("Poli dipilih:", idTerpilih);

                jadwalSelect.innerHTML = '<option value="">-- Pilih Jadwal --</option>';

                const filtered = dataJadwal.filter(item => String(item.poli_id) === String(idTerpilih));

                if (filtered.length > 0) {
                    filtered.forEach(item => {
                        const opt = document.createElement("option");
                        opt.value = item.id;
                        opt.textContent = item.info;
                        jadwalSelect.appendChild(opt);
                    });
                } else {
                    const opt = document.createElement("option");
                    opt.value = "";
                    opt.textContent = "Tidak ada jadwal untuk poli ini";
                    jadwalSelect.appendChild(opt);
                }
            });
        });
    </script>
    @endpush
</x-layouts.app>