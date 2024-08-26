<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\data_siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;

class RankingController extends Controller
{
    private $bobot = [
        "izin" => 0.10,
        "hadir" => 0.70,
        "alpa" => 0.15,
        "sakit" => 0.5,
    ];
    public function index(Request $request)
    {
        $kelas = Kelas::all();

        if ($request->ajax()) {
            $startDate = $request->start;
            $endDate = $request->end;
            $kelasId = $request->kelas_id;

            $datas = data_siswa::when($kelasId, function ($query) use ($kelasId) {
                return $query->where('kelas_id', $kelasId);
            })->withCount([
                'absen as hadir_count' => function ($query) use ($startDate, $endDate) {
                    $query->where('status', 'H')
                        ->when($startDate, function ($query) use ($startDate) {
                            $query->where('tanggal', '>=', $startDate);
                        })
                        ->when($endDate, function ($query) use ($endDate) {
                            $query->where('tanggal', '<=', $endDate);
                        });
                },
                'absen as izin_count' => function ($query) use ($startDate, $endDate) {
                    $query->where('status', 'I')
                        ->when($startDate, function ($query) use ($startDate) {
                            $query->where('tanggal', '>=', $startDate);
                        })
                        ->when($endDate, function ($query) use ($endDate) {
                            $query->where('tanggal', '<=', $endDate);
                        });
                },
                'absen as sakit_count' => function ($query) use ($startDate, $endDate) {
                    $query->where('status', 'S')
                        ->when($startDate, function ($query) use ($startDate) {
                            $query->where('tanggal', '>=', $startDate);
                        })
                        ->when($endDate, function ($query) use ($endDate) {
                            $query->where('tanggal', '<=', $endDate);
                        });
                },
                'absen as alpa_count' => function ($query) use ($startDate, $endDate) {
                    $query->where('status', 'A')
                        ->when($startDate, function ($query) use ($startDate) {
                            $query->where('tanggal', '>=', $startDate);
                        })
                        ->when($endDate, function ($query) use ($endDate) {
                            $query->where('tanggal', '<=', $endDate);
                        });
                }
            ])->get();

            $normalisasi = $this->normalisasi($datas);
            $hasil_akhir2 = $normalisasi->sortByDesc('nilai_akhir')->values()->all();
            return response()->json($hasil_akhir2, 200);
        }

        return view('ranking.index', compact('kelas'));
    }

    private function normalisasi($datas)
    {
        $max = $this->max_data($datas);
        $hasil = collect();
        // dd($max);
        foreach ($datas as $data) {
            $temp = [
                "NISN" => $data->NISN,
                "No_wa" => $data->No_wa,
                "alamat" => $data->alamat,
                "alpa_count" => $data->alpa_count,
                "hadir_count" => $data->hadir_count,
                "izin_count" => $data->izin_count,
                "sakit_count" => $data->sakit_count,
                "nama" => $data->nama,
                "jenis_kelamin" => $data->jenis_kelamin,
                "nilai_sakit" => $this->count_sakit($data->sakit_count) / $max["sakit"],
                "nilai_izin" => $this->count_izin($data->izin_count) / $max["izin"],
                "nilai_alpa" => $this->count_alpa($data->alpa_count) / $max["alpa"],
                "nilai_hadir" => $this->count_hadir($data->hadir_count) / $max["hadir"]
            ];
            $temp += [
                "nilai_akhir" => ($temp["nilai_sakit"] * $this->bobot["sakit"]) +
                    ($temp["nilai_alpa"] * $this->bobot["alpa"]) +
                    ($temp["nilai_izin"] * $this->bobot["izin"]) +
                    ($temp["nilai_hadir"] * $this->bobot["hadir"])
            ];
            $hasil->push($temp);
        }

        return $hasil;
    }

    private function count_izin($data)
    {
        if ($data > 3) {
            return 3;
        } else if ($data >= 1) {
            return 2;
        } else {
            return 1;
        }
    }

    private function count_sakit($data)
    {
        if ($data > 3) {
            return 3;
        } else if ($data >= 1) {
            return 2;
        } else {
            return 1;
        }
    }

    private function count_alpa($data)
    {
        if ($data > 3) {
            return 3;
        } else if ($data >= 1) {
            return 2;
        } else {
            return 1;
        }
    }

    private function count_hadir($data)
    {
        if ($data < 10) {
            return 1;
        } else if ($data <= 24) {
            return 2;
        } else {
            return 3;
        }
    }

    private function max_data($data)
    {
        $max = [
            "izin" => $this->count_izin($data->min('izin_count')),
            "alpa" => $this->count_alpa($data->min('alpa_count')),
            "sakit" => $this->count_sakit($data->min('sakit_count')),
            "hadir" => $this->count_hadir($data->max('hadir_count')),
        ];

        return $max;
    }
}
