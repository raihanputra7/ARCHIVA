<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDisposisiRequest;
use App\Http\Requests\UpdateTindakLanjutRequest;
use App\Models\Disposisi;
use App\Models\SuratMasuk;
use App\Services\DisposisiService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class DisposisiController extends Controller
{
    /**
     * Membuat lembar instruksi disposisi (oleh Kepala Sekolah / Admin).
     */
    public function store(StoreDisposisiRequest $request, SuratMasuk $suratMasuk, DisposisiService $service): RedirectResponse
    {
        Gate::authorize('createDisposisi', $suratMasuk);

        $created = $service->createDisposisi(
            $suratMasuk,
            $request->validated(),
            $request->user()
        );

        return back()
            ->with('status', 'Instruksi disposisi berhasil diteruskan kepada penerima.')
            ->with('disposisi_created_ids', $created->pluck('id')->all());
    }

    /**
     * Memperbarui progres / status tindak lanjut oleh penerima disposisi.
     */
    public function update(UpdateTindakLanjutRequest $request, Disposisi $disposisi, DisposisiService $service): RedirectResponse
    {
        $service->updateTindakLanjut(
            $disposisi,
            $request->validated()
        );

        return back()->with('status', 'Status tindak lanjut disposisi berhasil diperbarui.');
    }
}
