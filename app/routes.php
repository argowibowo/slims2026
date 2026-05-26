<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Illuminate\Database\Capsule\Manager as DB;

use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

use Dompdf\Dompdf;
use Dompdf\Options;


return function (\Slim\App $app) {
    // Route untuk meng-generate Swagger JSON
    $app->get('/swagger.json', function (Request $request, Response $response) {
        // Melakukan scan pada folder app dan src untuk mencari anotasi Swagger
        $generator = new \OpenApi\Generator();
        $openapi = $generator->generate([__DIR__ . '/../app', __DIR__ . '/../src']);

        if ($openapi) {
            $response->getBody()->write($openapi->toJson());
        }
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization');
    });

    //contoh nested
    $app->get('/simulasi-nested', function ($request, $response) {
        // --- SIMULASI DATA DARI DATABASE ---
        // Level 1: Data Resep
        $dataResep = [
            'id_resep' => 101,
            'nama_pasien' => 'Budi Santoso',
            'dokter' => 'dr. Andi',
            'tanggal' => '2026-03-04',
            'status' => 'diproses'
        ];

        // Level 2: Data Obat (Array didalam Array)
        $daftarObat = [
            [
                'id_obat' => 12,
                'nama_obat' => 'Amoxicillin',
                'jumlah' => 10,
                'dosis' => '3x1 sesudah makan'
            ],
            [
                'id_obat' => 45,
                'nama_obat' => 'Paracetamol',
                'jumlah' => 5,
                'dosis' => '1x1 jika demam'
            ]
        ];

        // --- PROSES NESTING (PENGGABUNGAN) ---
        // Kita masukkan array daftarObat ke dalam key baru bernama 'detail_obat'
        $dataResep['detail_obat'] = $daftarObat;

        // Kirim sebagai JSON
        $response->getBody()->write(json_encode($dataResep));
        return $response->withHeader('Content-Type', 'application/json');
    });

    // contoh looping nested
    $app->get('/simulasi-nested-looping', function ($request, $response) {
        // 1. Simulasi Data Parent (Hasil dari Query: SELECT * FROM resep WHERE id=101)
        $resep = [
            'id_resep' => 101,
            'pasien' => 'Rizky Fitriani',
            'status' => 'Selesai'
        ];

        // 2. Simulasi Data Child (Hasil dari Query JOIN detil_resep & obat)
        // Anggap ini hasil dari $details = DB::table(...)->get();
        $rawDetailsFromDb = [
            ['nama' => 'Paracetamol', 'qty' => 10, 'aturan' => '3x1'],
            ['nama' => 'Amoxicillin', 'qty' => 5, 'aturan' => '2x1'],
            ['nama' => 'Vitamin C', 'qty' => 30, 'aturan' => '1x1']
        ];

        // 3. Inisialisasi array kosong untuk menampung hasil looping
        $listObat = [];

        // 4. Proses Looping
        foreach ($rawDetailsFromDb as $row) {
            // Kita susun ulang datanya jika perlu, atau langsung masukkan
            $listObat[] = [
                'nama_obat' => $row['nama'],
                'jumlah' => $row['qty'],
                'aturan_pakai' => $row['aturan']
            ];
        }

        // 5. Masukkan hasil looping ke dalam object Parent
        $resep['items'] = $listObat;

        // 6. Return sebagai JSON
        $response->getBody()->write(json_encode($resep));
        return $response->withHeader('Content-Type', 'application/json');
    });

    // --- GET: Tampilkan Semua Resep (Header saja) ---
    $app->get('/resep', function ($request, $response) {
        $resep = DB::table('resep')->get();
        $response->getBody()->write(json_encode($resep));
        return $response->withHeader('Content-Type', 'application/json');
    });

    // --- POST: Buat Resep Baru ---
    $app->post('/resep', function ($request, $response) {
        $data = $request->getParsedBody();
        $id = DB::table('resep')->insertGetId([
            'id_rm' => $data['id_rm'],
            'id_dokter' => $data['id_dokter'],
            'tgl_resep' => date('Y-m-d'),
            'status' => 'menunggu'
        ]);
        $response->getBody()->write(json_encode(['id_resep' => $id]));
        return $response->withHeader('Content-Type', 'application/json');
    });

    //detil resep
    // --- GET: Lihat Isi Detail dari Satu Nomor Resep (JOIN) ---
    $app->get('/detilresep/{id}/', function ($request, $response, $args) {
        $detail = DB::table('detil_resep')
            ->join('obat', 'detil_resep.id_obat', '=', 'obat.id')
            ->select('detil_resep.*', 'obat.sku', 'obat.label_catatan')
            ->where('detil_resep.id_resep', $args['id'])
            ->get();

        $response->getBody()->write(json_encode($detail));
        return $response->withHeader('Content-Type', 'application/json');
    });

    $app->get('/detilresepnested/{id}/', function ($request, $response, $args) {
        $id_resep = $args['id'];

        // 1. Ambil data Header Resep
        $resep = DB::table('resep')
            ->where('id_resep', $id_resep)
            ->first();

        if (!$resep) {
            $response->getBody()->write(json_encode(['message' => 'Resep tidak ditemukan']));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }

        // 2. Ambil data Detail Resep beserta data Obatnya (JOIN)
        $details = DB::table('detil_resep')
            ->join('obat', 'detil_resep.id_obat', '=', 'obat.id')
            ->select(
                'detil_resep.id_detil',
                'detil_resep.jumlah',
                'detil_resep.dosis',
                'detil_resep.keterangan',
                'obat.id',
                'obat.sku',
                'obat.label_catatan'
            )
            ->where('detil_resep.id_resep', $id_resep)
            ->get();

        // 3. Susun struktur Nested
        // Kita ubah stdClass menjadi array agar lebih mudah dimanipulasi
        $resepArray = (array) $resep;
        $resepArray['items'] = [];

        foreach ($details as $item) {
            $resepArray['items'][] = [
                'id_detail' => $item->id_detil,
                'jumlah' => $item->jumlah,
                'dosis' => $item->dosis,
                'keterangan' => $item->keterangan,
                'obat' => [
                    'id_obat' => $item->id,
                    'nama_obat' => $item->sku,
                    'label' => $item->label_catatan
                ]
            ];
        }

        $response->getBody()->write(json_encode($resepArray));
        return $response->withHeader('Content-Type', 'application/json');
    });

    // --- POST: Tambah Item Obat ke Dalam Resep ---
    $app->post('/detilresep', function ($request, $response) {
        $data = $request->getParsedBody();

        // Insert ke detail
        DB::table('detil_resep')->insert([
            'id_resep' => $data['id_resep'],
            'id_obat' => $data['id_obat'],
            'jumlah' => $data['jumlah'],
            'dosis' => $data['dosis'],
            'keterangan' => $data['keterangan']
        ]);

        // Kurangi stok obat
        DB::table('obat')->where('id', $data['id_obat'])->decrement('jumlah', $data['jumlah']);

        $response->getBody()->write(json_encode(['message' => 'Item obat berhasil ditambahkan ke resep']));
        return $response->withHeader('Content-Type', 'application/json');
    });

    // --- DELETE: Hapus Item dari Resep (Stok dikembalikan) ---
    $app->delete('/detilresep/{id_detail}', function ($request, $response, $args) {
        $item = DB::table('detil_resep')->where('id_detil', $args['id_detil'])->first();

        if ($item) {
            // Kembalikan stok obat sebelum dihapus
            DB::table('obat')->where('id_obat', $item->id_obat)->increment('stok', $item->jumlah);
            // Hapus detail
            DB::table('detil_resep')->where('id_detil', $args['id_detil'])->delete();
        }

        $response->getBody()->write(json_encode(['message' => 'Item dihapus dan stok dikembalikan']));
        return $response->withHeader('Content-Type', 'application/json');
    });

    //rekam medis
    $app->get('/rekammedis', function (Request $request, Response $response) {
        $db = $this->get('db');
        $result = $db->table('rekam_medis')->get();
        // $result = DB::table('rekam_medis')->get();
        $response->getBody()->write(json_encode($result));
        return $response->withHeader('Content-Type', 'application/json');
    });

    $app->post('/rekammedis', function (Request $request, Response $response) {
        $db = $this->get('db');
        $data = $request->getParsedBody();
        $id = $db->table('rekam_medis')->insertGetId([
            'no_rm' => $data['no_rm'],
            'keluhan' => $data['keluhan'],
            'tinggi' => $data['tinggi'],
            'berat' => $data['berat'],
            'tensi' => $data['tensi'],
            'dokter' => $data['dokter'],
            'status_obat' => $data['status_obat'],
            'tanggal' => $data['tanggal'],
        ]);
        $result = $db->table('rekam_medis')->where('id_rm', $id)->first();

        $response->getBody()->write(json_encode($result));
        return $response->withHeader('Content-Type', 'application/json');
    });

    $app->put('/rekammedis', function (Request $request, Response $response) {
        $db = $this->get('db');
        $data = $request->getParsedBody();
        $db->table('rekam_medis')
            ->where('id_rm', $data['id_rm'])
            ->update([
                'no_rm' => $data['no_rm'],
                'keluhan' => $data['keluhan'],
                'tinggi' => $data['tinggi'],
                'berat' => $data['berat'],
                'tensi' => $data['tensi'],
                'dokter' => $data['dokter'],
                'status_obat' => $data['status_obat'],
                'tanggal' => $data['tanggal']
            ]);

        $result = $db->table('rekam_medis')->where('id_rm', $data['id_rm'])->first();

        $response->getBody()->write(json_encode($result));
        return $response->withHeader('Content-Type', 'application/json');
    });

    $app->delete('/rekammedis', function (Request $request, Response $response) {
        $db = $this->get('db');
        $data = $request->getParsedBody();
        $db->table('rekam_medis')
            ->where('id_rm', $data['id_rm'])
            ->delete();
        $response->getBody()->write(json_encode(['status' => 'deleted']));
        return $response->withHeader('Content-Type', 'application/json');
    });

    // obat
    $app->post('/obat', function (Request $request, Response $response) {
        $data = $request->getParsedBody();
        $id = DB::table('obat')->insertGetId([
            'sku' => $data['sku'],
            'id_rm' => $data['id_rm'],
            'label_catatan' => $data['label_catatan'],
            'jumlah' => $data['jumlah']
        ]);
        $result = DB::table('obat')->where('id', $id)->first();
        $response->getBody()->write(json_encode($result));
        return $response->withHeader('Content-Type', 'application/json');
    });

    // $app->get('/obat', function (Request $request, Response $response) {
    //     $result = DB::table('obat')->get();
    //     $response->getBody()->write(json_encode($result));
    //     return $response->withHeader('Content-Type', 'application/json');
    // });

    // contoh filtering
    // $app->get('/obat', function (Request $request, Response $response) {
    //     // 1. Ambil semua parameter dari URL
    //     $params = $request->getQueryParams();

    //     // 2. Inisialisasi Query Builder
    //     $query = DB::table('obat');

    //     // 3. Logika SEARCH (Berdasarkan Nama)
    //     if (!empty($params['search'])) {
    //         $searchTerm = $params['search'];
    //         $query->where('sku', 'LIKE', "%$searchTerm%");
    //     }

    //     // 4. Logika FILTER (Berdasarkan Kolom Spesifik)
    //     if (!empty($params['jumlah'])) {
    //         $query->where('jumlah', $params['jumlah']);
    //     }

    //     // 5. Logika FILTER Range (label)
    //     if (!empty($params['label'])) {
    //         $searchlabel = $params['label'];
    //         $query->where('label_catatan', 'LIKE', "%$searchlabel%");
    //     }

    //     // 6. Eksekusi Query
    //     $result = $query->get();

    //     $response->getBody()->write(json_encode($result));
    //     return $response->withHeader('Content-Type', 'application/json');
    // });

    $app->get('/obat', function ($request, $response) {
        $params = $request->getQueryParams();

        // 1. Tentukan Parameter Pagination
        $page = isset($params['page']) ? (int) $params['page'] : 1;
        $perPage = isset($params['per_page']) ? (int) $params['per_page'] : 10;
        $offset = ($page - 1) * $perPage;

        // 2. Inisialisasi Query
        $query = DB::table('obat');

        // 3. (Opsional) Tambahkan Filtering/Search
        if (!empty($params['search'])) {
            $query->where('sku', 'LIKE', '%' . $params['search'] . '%');
        }

        // 4. Hitung Total Data (Penting untuk Frontend membuat tombol navigasi)
        $totalData = $query->count();
        $totalPage = ceil($totalData / $perPage);

        // 5. Ambil Data dengan Limit dan Offset
        $data = $query->limit($perPage)
            ->offset($offset)
            ->orderBy('id', 'desc')
            ->get();

        // 6. Susun Response JSON yang Informatif (Meta Data)
        $result = [
            'status' => 'success',
            'meta' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total_data' => $totalData,
                'total_pages' => $totalPage
            ],
            'data' => $data
        ];

        $response->getBody()->write(json_encode($result));
        return $response->withHeader('Content-Type', 'application/json');
    });

    $app->get('/obat/{id}', function (Request $request, Response $response, $args) {
        $result = DB::table('obat')->where('id', $args['id'])->first();
        $response->getBody()->write(json_encode($result));
        return $response->withHeader('Content-Type', 'application/json');
    });

    $app->put('/obat/{id}', function (Request $request, Response $response, $args) {
        // Pastikan data diparsing dengan benar

        //validasi manual
        $data = $request->getParsedBody() ?? [];
        // if (empty($data['sku'])) {
        //     $response->getBody()->write(json_encode([
        //         'debug_info' => 'SKU terdeteksi kosong di level PHP',
        //         'data_received' => $data
        //     ]));
        //     return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        // }

        $id = $args['id'];

        $data['id'] = $args['id'];
        $data['id'] = $id;

        // menggunakan validator
        // set rule
        $validator = v::key('sku', v::stringType()->notEmpty())
            ->key('id_rm', v::intVal()->notEmpty())
            ->key('label_catatan', v::optional(v::stringType()))
            ->key('jumlah', v::intVal()->min(1));

        try {
            // JALANKAN VALIDASI
            // Paksa lempar error jika sku kosong
            // if (!isset($data['sku']) || $data['sku'] === "") {
            //     throw new \Exception("SKU tidak boleh kosong!");
            // }

            $validator->assert($data);

            $check = DB::table('obat')->where('id', $id)->first();
            if (!$check) {
                $response->getBody()->write(json_encode(['message' => 'Data obat tidak ditemukan']));
                return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
            }

            DB::table('obat')->where('id', $id)->update([
                'sku' => $data['sku'],
                'id_rm' => $data['id_rm'],
                'label_catatan' => $data['label_catatan'] ?? null,
                'jumlah' => $data['jumlah']
            ]);

            $result = DB::table('obat')->where('id', $id)->first();

            $response->getBody()->write(json_encode([
                'status' => 'BERHASIL UPDATE COYY',
                'data' => $result
            ]));
            // Berikan status 200 secara eksplisit
            return $response->withStatus(200)->withHeader('Content-Type', 'application/json');

        } catch (NestedValidationException $e) {
            // TANGKAP ERROR VALIDASI
            // $errors = $e->getFullMessage(); // untuk debuging
            $errors = $e->getMessages([
                "sku" => "SKU tidak boleh kosong!",
                "id_rm" => "ID RM tidak boleh kosong!",
                "label_catatan" => "Label catatan tidak boleh kosong!",
                "jumlah" => "Jumlah tidak boleh kosong!"
            ]);

            $payload = json_encode([
                'status' => 'fail',
                'message' => 'Validasi gagal',
                // 'errors' => $e->getMessages()
                'errors' => array_filter($errors)
            ]);

            $response->getBody()->write($payload);

            // PENTING: Harus return dengan status 422
            return $response
                ->withStatus(422)
                ->withHeader('Content-Type', 'application/json');

        } catch (\Exception $e) {
            // Tangkap error umum jika ada masalah database/syntax
            $response->getBody()->write(json_encode(['error' => $e->getMessage()]));
            return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
        }
    });

    // $app->put('/obat/{id}', function (Request $request, Response $response, $args) {
    //     $data = $request->getParsedBody();

    //     // DB::table('obat')
    //     //     ->where('id', $args['id'])
    //     //     ->update([
    //     //         'sku'   => $data['sku'],
    //     //         'id_rm' => $data['id_rm'],
    //     //         'label_catatan' => $data['label_catatan'],
    //     //         'jumlah' => $data['jumlah']
    //     //     ]);
    //     // $result = DB::table('obat')->where('id', $args['id'])->first();
    //     // $response->getBody()->write(json_encode($result));
    //     // return $response->withHeader('Content-Type', 'application/json');
    //     $id = $args['id'];

    //     // 1. Definisikan Aturan Validasi
    //     $validator = v::key('sku', v::stringType()->notEmpty())
    //                 ->key('id_rm', v::intVal()->notEmpty())
    //                 ->key('label_catatan', v::stringType()->optional())
    //                 ->key('jumlah', v::intVal()->min(1));

    //     try {
    //         // 2. Jalankan Validasi terhadap data input
    //         $validator->assert($data);

    //         // 3. Cek apakah data obat memang ada di database
    //         $check = DB::table('obat')->where('id', $id)->first();
    //         if (!$check) {
    //             $response->getBody()->write(json_encode(['message' => 'Data obat tidak ditemukan']));
    //             return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
    //         }

    //         // 4. Proses Update jika Valid
    //         DB::table('obat')
    //             ->where('id', $id)
    //             ->update([
    //                 'sku'           => $data['sku'],
    //                 'id_rm'         => $data['id_rm'],
    //                 'label_catatan' => $data['label_catatan'] ?? null,
    //                 'jumlah'        => $data['jumlah']
    //             ]);

    //         // Ambil data terbaru
    //         $result = DB::table('obat')->where('id', $id)->first();

    //         $response->getBody()->write(json_encode([
    //             'status' => 'success',
    //             'data' => $result
    //         ]));
    //         return $response->withHeader('Content-Type', 'application/json');

    //     } catch (NestedValidationException $e) {
    //         // 5. Tangkap Error Validasi dan kembalikan status 422
    //         $errors = $e->getMessages();

    //         $response->getBody()->write(json_encode([
    //             'status' => 'fail',
    //             'message' => 'Validasi gagal',
    //             'errors' => $errors
    //         ]));

    //         return $response
    //             ->withStatus(422)
    //             ->withHeader('Content-Type', 'application/json');
    //     }
    // });

    $app->delete('/obat/{id}', function (Request $request, Response $response, $args) {
        DB::table('obat')->where('id', $args['id'])->delete();
        $response->getBody()->write(json_encode(['status' => 'deleted']));
        return $response->withHeader('Content-Type', 'application/json');
    });

    $app->get('/obat-secure', function (Request $request, Response $response) {
        // Ambil kredensial (mendukung Basic Auth atau Custom Header)
        $serverParams = $request->getServerParams();
        $username = $serverParams['PHP_AUTH_USER'] ?? $request->getHeaderLine('username');
        $password = $serverParams['PHP_AUTH_PW'] ?? $request->getHeaderLine('password');

        // Pengecekan jika kredensial kosong
        if (empty($username) || empty($password)) {
            $response->getBody()->write(json_encode([
                'status' => 'error',
                'message' => 'Autentikasi diperlukan. Harap sertakan username dan password.'
            ]));
            return $response->withStatus(401)
                ->withHeader('WWW-Authenticate', 'Basic realm="Protected Area"')
                ->withHeader('Content-Type', 'application/json');
        }

        // Cek username di tabel user_role
        $user = DB::table('user_role')->where('username', $username)->first();

        // Verifikasi password (password pada database sudah di hash, misal dengan password_hash())
        if (!$user || !password_verify($password, $user->password)) {
            $response->getBody()->write(json_encode([
                'status' => 'error',
                'message' => 'Username atau password salah!'
            ]));
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
        }

        // Jika lolos autentikasi, ambil data obat
        $dataObat = DB::table('obat')->get();

        $response->getBody()->write(json_encode([
            'status' => 'success',
            'message' => 'Berhasil mengambil data obat',
            'data' => $dataObat
        ]));
        return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
    });

    // --- JWT Authentication ---
    // 1. Endpoint untuk Login dan mendapatkan Token JWT
    $app->post('/login-jwt', function (Request $request, Response $response) {
        $data = $request->getParsedBody();
        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';

        if (empty($username) || empty($password)) {
            $response->getBody()->write(json_encode([
                'status' => 'error',
                'message' => 'Username dan password diperlukan'
            ]));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        $user = DB::table('user_role')->where('username', $username)->first();

        // Verifikasi password hash
        if (!$user || !password_verify($password, $user->password)) {
            $response->getBody()->write(json_encode([
                'status' => 'error',
                'message' => 'Username atau password salah!'
            ]));
            return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
        }

        // Kunci Rahasia untuk JWT (Sebaiknya simpan di file .env)
        $secretKey = "supersecretkeyyoushouldnotcommittogithub";

        $payload = [
            "iat" => time(),
            "exp" => time() + (60 * 60), // Token berlaku selama 1 jam
            "sub" => $user->username,
            "uid" => $user->id ?? 1, // ID user untuk pengecekan resource (asumsi tabel user_role punya id)
            "role" => $user->role ?? 'apoteker' // Menambahkan role untuk authorization (asumsi fallback apoteker)
        ];

        // Generate Token
        $token = \Firebase\JWT\JWT::encode($payload, $secretKey, 'HS256');

        $response->getBody()->write(json_encode([
            'status' => 'success',
            'message' => 'Login berhasil',
            'token' => $token
        ]));
        return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
    });

    // 2. Endpoint Get Obat yang dilindungi oleh JWT Middleware
    $app->get('/obat-jwt', function (Request $request, Response $response) {
        $dataObat = DB::table('obat')->get();

        $response->getBody()->write(json_encode([
            'status' => 'success',
            'message' => 'Berhasil mengambil data obat (JWT Verified)',
            'data' => $dataObat
        ]));
        return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
    })->add(new \Tuupola\Middleware\JwtAuthentication([
                    "secret" => "supersecretkeyyoushouldnotcommittogithub",
                    "error" => function ($response, $arguments) {
                        $data["status"] = "error";
                        $data["message"] = $arguments["message"];
                        $response->getBody()->write(
                            json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)
                        );
                        return $response->withHeader("Content-Type", "application/json");
                    }
                ]));

    // 3  Hanya bisa diakses oleh user dengan role 'apoteker' (Role-Based)
    // dan hanya bisa menghapus obat miliknya sendiri (Resource-Based)
    $app->delete('/obat-otorisasi/{id}', function (Request $request, Response $response, $args) {
        $obatId = $args['id'];

        // Data token otomatis ada dari JwtAuthentication middleware Tuupola
        $token = $request->getAttribute("token");
        $currentUserId = is_array($token) ? ($token['uid'] ?? null) : ($token->uid ?? null);

        // Ambil data obat dari database
        $obat = DB::table('obat')->where('id', $obatId)->first();

        if (!$obat) {
            $response->getBody()->write(json_encode(["error" => "Obat tidak ditemukan"]));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }

        // --- Logika Otorisasi Resource ---
        // Contoh: Kita asumsikan kolom `id_rm` pada tabel `obat` merepresentasikan ID user yang membuatnya.
        // Jika ID user dari token (currentUserId) tidak sama dengan id_rm dari obat, tolak aksesnya.
        if ($obat->id_rm != $currentUserId) {
            $response->getBody()->write(json_encode([
                "error" => "Unauthorized: Anda bukan pemilik data obat ini!"
            ]));
            return $response->withStatus(403)->withHeader('Content-Type', 'application/json');
        }

        // Proses hapus obat jika lolos pengecekan
        DB::table('obat')->where('id', $obatId)->delete();

        $response->getBody()->write(json_encode([
            "status" => "success",
            "message" => "Data obat Anda berhasil dihapus."
        ]));

        return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
    })
        // Penambahan Middleware dieksekusi berurutan (LIFO / Last In First Out) di Slim 4
        // 1. RoleMiddleware mengecek role (Inner layer)
        ->add(new \App\Application\Middleware\RoleMiddleware('apoteker'))
        // 2. JwtAuthentication memvalidasi JWT dan mengisi atribut 'token' (Outer layer)
        ->add(new \Tuupola\Middleware\JwtAuthentication([
            "secret" => "supersecretkeyyoushouldnotcommittogithub",
            "error" => function ($response, $arguments) {
                $data["status"] = "error";
                $data["message"] = $arguments["message"];
                $response->getBody()->write(
                    json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)
                );
                return $response->withHeader("Content-Type", "application/json");
            }
        ]));

};