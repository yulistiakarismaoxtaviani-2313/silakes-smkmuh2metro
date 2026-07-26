<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;

class SiswaImport implements ToCollection
{
    protected $id_tahun;

    public function __construct($id_tahun)
    {
        $this->id_tahun = $id_tahun;
    }

    public function collection(Collection $rows)
    {
        DB::beginTransaction();

        try {

            // Ambil nama kelas dari baris pertama
            $rawKelas = $rows[0][0] ?? '';

            $namaKelas = trim(
                str_ireplace('KELAS', '', $rawKelas)
            );

            $kelas = Kelas::where(
                'nama_kelas',
                'like',
                '%' . $namaKelas . '%'
            )->first();

            if (!$kelas) {
                throw new \Exception(
                    'Kelas tidak ditemukan: ' . $namaKelas
                );
            }

            foreach ($rows as $index => $row) {

                // Lewati baris kelas dan header
                if ($index < 2) {
                    continue;
                }

                $nis = trim((string) ($row[0] ?? ''));
                $nama = trim((string) ($row[1] ?? ''));
                $jk = strtoupper(trim((string) ($row[2] ?? 'L')));

                if (empty($nis) || empty($nama)) {
                    continue;
                }

                // Simpan user
                $user = User::updateOrCreate(
                    [
                        'username' => $nis
                    ],
                    [
                        'nama'     => strtoupper($nama),
                        'email'    => $nis . '@siswa.com',
                        'password' => Hash::make($nis),
                        'role'     => 'siswa'
                    ]
                );

                // Simpan siswa
                Siswa::updateOrCreate(
                    [
                        'nis' => $nis
                    ],
                    [
                        'id_user'         => $user->id_user,
                        'id_kelas'        => $kelas->id_kelas,
                        'id_tahun_ajaran' => $this->id_tahun,
                        'jenis_kelamin'   => $jk,
                        'status'          => 'aktif',
                        'foto'            => ''
                    ]
                );
            }

            DB::commit();

        } catch (\Exception $e) {

            DB::rollBack();

            throw new \Exception(
                'Gagal import siswa: ' . $e->getMessage()
            );
        }
    }
}