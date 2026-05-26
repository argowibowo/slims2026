<?php

namespace App;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Slim 2026 API",
 *     version="1.0.0",
 *     description="Dokumentasi API untuk project Slim 2026.",
 *     @OA\Contact(
 *         name="Argo Uchiha"
 *     )
 * )
 * 
 * @OA\Server(
 *     description="Local Server",
 *     url="http://localhost/slim2026/public"
 * )
 */
class OpenApiSpec
{
    /**
     * @OA\Get(
     *     path="/obat",
     *     summary="Mendapatkan daftar obat",
     *     tags={"Obat"},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Nomor halaman",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Jumlah data per halaman",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Pencarian berdasarkan SKU",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Berhasil mengambil data obat"
     *     )
     * )
     */
    public function getObat()
    {
    }

    /**
     * @OA\Get(
     *     path="/simulasi-nested-looping",
     *     summary="Simulasi data nested menggunakan looping",
     *     tags={"Simulasi"},
     *     @OA\Response(
     *         response="200",
     *         description="Berhasil mengambil data simulasi nested looping",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id_resep", type="integer", example=101),
     *             @OA\Property(property="pasien", type="string", example="Rizky Fitriani"),
     *             @OA\Property(property="status", type="string", example="Selesai"),
     *             @OA\Property(
     *                 property="items",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="nama_obat", type="string", example="Paracetamol"),
     *                     @OA\Property(property="jumlah", type="integer", example=10),
     *                     @OA\Property(property="aturan_pakai", type="string", example="3x1")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function getSimulasiNestedLooping()
    {
    }

    /**
     * @OA\Post(
     *     path="/obat",
     *     summary="Menambahkan data obat baru",
     *     tags={"Obat"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="sku", type="string", example="OBT-001"),
     *             @OA\Property(property="id_rm", type="integer", example=1),
     *             @OA\Property(property="label_catatan", type="string", example="3x sehari sesudah makan"),
     *             @OA\Property(property="jumlah", type="integer", example=10)
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Berhasil menambahkan data obat",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="sku", type="string", example="OBT-001"),
     *             @OA\Property(property="id_rm", type="integer", example=1),
     *             @OA\Property(property="label_catatan", type="string", example="3x sehari sesudah makan"),
     *             @OA\Property(property="jumlah", type="integer", example=10)
     *         )
     *     )
     * )
     */
    public function createObat()
    {
    }

    /**
     * @OA\Put(
     *     path="/obat/{id}",
     *     summary="Memperbarui data obat",
     *     tags={"Obat"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID Obat",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="sku", type="string", example="OBT-001-REV"),
     *             @OA\Property(property="id_rm", type="integer", example=1),
     *             @OA\Property(property="label_catatan", type="string", example="2x sehari sesudah makan"),
     *             @OA\Property(property="jumlah", type="integer", example=15)
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Berhasil memperbarui data obat",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="BERHASIL UPDATE COYY"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="sku", type="string", example="OBT-001-REV"),
     *                 @OA\Property(property="id_rm", type="integer", example=1),
     *                 @OA\Property(property="label_catatan", type="string", example="2x sehari sesudah makan"),
     *                 @OA\Property(property="jumlah", type="integer", example=15)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Data obat tidak ditemukan"
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Validasi gagal"
     *     )
     * )
     */
    public function updateObat()
    {
    }

    /**
     * @OA\Delete(
     *     path="/obat/{id}",
     *     summary="Menghapus data obat",
     *     tags={"Obat"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID Obat",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Berhasil menghapus data obat",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="deleted")
     *         )
     *     )
     * )
     */
    public function deleteObat()
    {
    }
}
