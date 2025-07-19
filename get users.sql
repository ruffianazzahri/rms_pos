SELECT    dispo.no                                  AS no,
          dk.nama_lengkap                           AS nama_pengaju,
          j.nama_jabatan                            AS jabatan,
          Date_format(dispo.created_at, '%d/%m/%Y') AS tanggal_pengajuan,
          dispo.perihal                             AS jenis_pengajuan,
          usub.nama                                 AS sub_direktorat_pengaju,
          'Kepala Sub Direktorat Verifikasi Teknis' AS persetujuan_selanjutnya,
          CASE
                    WHEN dispo.perihal = 'Belanja ATK' THEN Sha2('tbl_persyaratan_atk', 256)
                    WHEN dispo.perihal = 'Belanja Kelontong' THEN Sha2('tbl_persyaratan_kelontong', 256)
                    WHEN dispo.perihal = 'Konsumsi Snack & Makan Rapat' THEN Sha2('tbl_persyaratan_konsumsi_snack_dan_makan_rapat', 256)
                    WHEN dispo.perihal = 'Konsumsi Snack Rapat' THEN Sha2('tbl_persyaratan_snack_rapat', 256)
                    WHEN dispo.perihal = 'Konsumsi Makan Rapat' THEN Sha2('tbl_persyaratan_makan_rapat', 256)
                    WHEN dispo.perihal = 'Belanja Seminar Kit' THEN Sha2('tbl_persyaratan_seminar_kit', 256)
                    WHEN dispo.perihal = 'Cetak Brosur' THEN Sha2('tbl_persyaratan_cetak_brosur', 256)
                    WHEN dispo.perihal = 'Cetak Spanduk' THEN Sha2('tbl_persyaratan_cetak_spanduk', 256)
                    WHEN dispo.perihal = 'Belanja Barang Keperluan Lainnya' THEN Sha2('tbl_persyaratan_belanja_barang_keperluan_lainnya', 256)
                    WHEN dispo.perihal = 'Paket Sewa Ruangan Halfday' THEN Sha2('tbl_persyaratan_paket_sewa_ruangan_halfday', 256)
                    WHEN dispo.perihal = 'Paket Sewa Ruangan Fullday' THEN Sha2('tbl_persyaratan_paket_sewa_ruangan_fullday', 256)
                    WHEN dispo.perihal = 'Paket Sewa Ruangan Fullboard' THEN Sha2('tbl_persyaratan_sewa_ruangan_fullboard', 256)
                    WHEN dispo.perihal = 'Honor Narasumber' THEN Sha2('tbl_persyaratan_honor_narasumber', 256)
                    WHEN dispo.perihal = 'Biaya Perjalanan Dinas' THEN Sha2('tbl_persyaratan_biaya_perjalanan_dinas', 256)
                    WHEN dispo.perihal = 'Biaya Perjalanan Dinas Narasumber' THEN Sha2('tbl_persyaratan_biaya_perjalanan_dinas_narasumber', 256)
                    WHEN dispo.perihal = 'Belanja Perjalanan Dinas (SPPD)' THEN Sha2('tbl_sppd_pembayaran_sppd', 256)
                    ELSE 'Tabel tidak ditemukan'
          END AS lembar_persetujuan_pembayaran_table,
          CASE
                    WHEN (
                                        CASE
                                                  WHEN dispo.perihal = 'Belanja ATK' THEN
                                                            (
                                                                   SELECT FILE
                                                                   FROM   tbl_persyaratan_atk
                                                                   WHERE  no_disposisi = dispo.no limit 1)
                                                  WHEN dispo.perihal = 'Belanja Kelontong' THEN
                                                            (
                                                                   SELECT FILE
                                                                   FROM   tbl_persyaratan_kelontong
                                                                   WHERE  no_disposisi = dispo.no limit 1)
                                                  WHEN dispo.perihal = 'Konsumsi Snack & Makan Rapat' THEN
                                                            (
                                                                   SELECT FILE
                                                                   FROM   tbl_persyaratan_konsumsi_snack_dan_makan_rapat
                                                                   WHERE  no_disposisi = dispo.no limit 1)
                                                  WHEN dispo.perihal = 'Konsumsi Snack Rapat' THEN
                                                            (
                                                                   SELECT FILE
                                                                   FROM   tbl_persyaratan_snack_rapat
                                                                   WHERE  no_disposisi = dispo.no limit 1)
                                                  WHEN dispo.perihal = 'Konsumsi Makan Rapat' THEN
                                                            (
                                                                   SELECT FILE
                                                                   FROM   tbl_persyaratan_makan_rapat
                                                                   WHERE  no_disposisi = dispo.no limit 1)
                                                  WHEN dispo.perihal = 'Belanja Seminar Kit' THEN
                                                            (
                                                                   SELECT FILE
                                                                   FROM   tbl_persyaratan_seminar_kit
                                                                   WHERE  no_disposisi = dispo.no limit 1)
                                                  WHEN dispo.perihal = 'Cetak Brosur' THEN
                                                            (
                                                                   SELECT FILE
                                                                   FROM   tbl_persyaratan_cetak_brosur
                                                                   WHERE  no_disposisi = dispo.no limit 1)
                                                  WHEN dispo.perihal = 'Cetak Spanduk' THEN
                                                            (
                                                                   SELECT FILE
                                                                   FROM   tbl_persyaratan_cetak_spanduk
                                                                   WHERE  no_disposisi = dispo.no limit 1)
                                                  WHEN dispo.perihal = 'Belanja Barang Keperluan Lainnya' THEN
                                                            (
                                                                   SELECT FILE
                                                                   FROM   tbl_persyaratan_belanja_barang_keperluan_lainnya
                                                                   WHERE  no_disposisi = dispo.no limit 1)
                                                  WHEN dispo.perihal = 'Paket Sewa Ruangan Halfday' THEN
                                                            (
                                                                   SELECT FILE
                                                                   FROM   tbl_persyaratan_paket_sewa_ruangan_halfday
                                                                   WHERE  no_disposisi = dispo.no limit 1)
                                                  WHEN dispo.perihal = 'Paket Sewa Ruangan Fullday' THEN
                                                            (
                                                                   SELECT FILE
                                                                   FROM   tbl_persyaratan_paket_sewa_ruangan_fullday
                                                                   WHERE  no_disposisi = dispo.no limit 1)
                                                  WHEN dispo.perihal = 'Paket Sewa Ruangan Fullboard' THEN
                                                            (
                                                                   SELECT FILE
                                                                   FROM   tbl_persyaratan_sewa_ruangan_fullboard
                                                                   WHERE  no_disposisi = dispo.no limit 1)
                                                  WHEN dispo.perihal = 'Honor Narasumber' THEN
                                                            (
                                                                   SELECT FILE
                                                                   FROM   tbl_persyaratan_honor_narasumber
                                                                   WHERE  no_disposisi = dispo.no limit 1)
                                                  WHEN dispo.perihal = 'Biaya Perjalanan Dinas' THEN
                                                            (
                                                                   SELECT FILE
                                                                   FROM   tbl_persyaratan_biaya_perjalanan_dinas
                                                                   WHERE  no_disposisi = dispo.no limit 1)
                                                  WHEN dispo.perihal = 'Biaya Perjalanan Dinas Narasumber' THEN
                                                            (
                                                                   SELECT FILE
                                                                   FROM   tbl_persyaratan_biaya_perjalanan_dinas_narasumber
                                                                   WHERE  no_disposisi = dispo.no limit 1)
                                        END) IS NOT NULL THEN sha2( (
                              CASE
                                        WHEN dispo.perihal = 'Belanja ATK' THEN
                                                  (
                                                         SELECT FILE
                                                         FROM   tbl_persyaratan_atk
                                                         WHERE  no_disposisi = dispo.no limit 1)
                                        WHEN dispo.perihal = 'Belanja Kelontong' THEN
                                                  (
                                                         SELECT FILE
                                                         FROM   tbl_persyaratan_kelontong
                                                         WHERE  no_disposisi = dispo.no limit 1)
                                        WHEN dispo.perihal = 'Konsumsi Snack & Makan Rapat' THEN
                                                  (
                                                         SELECT FILE
                                                         FROM   tbl_persyaratan_konsumsi_snack_dan_makan_rapat
                                                         WHERE  no_disposisi = dispo.no limit 1)
                                        WHEN dispo.perihal = 'Konsumsi Snack Rapat' THEN
                                                  (
                                                         SELECT FILE
                                                         FROM   tbl_persyaratan_snack_rapat
                                                         WHERE  no_disposisi = dispo.no limit 1)
                                        WHEN dispo.perihal = 'Konsumsi Makan Rapat' THEN
                                                  (
                                                         SELECT FILE
                                                         FROM   tbl_persyaratan_makan_rapat
                                                         WHERE  no_disposisi = dispo.no limit 1)
                                        WHEN dispo.perihal = 'Belanja Seminar Kit' THEN
                                                  (
                                                         SELECT FILE
                                                         FROM   tbl_persyaratan_seminar_kit
                                                         WHERE  no_disposisi = dispo.no limit 1)
                                        WHEN dispo.perihal = 'Cetak Brosur' THEN
                                                  (
                                                         SELECT FILE
                                                         FROM   tbl_persyaratan_cetak_brosur
                                                         WHERE  no_disposisi = dispo.no limit 1)
                                        WHEN dispo.perihal = 'Cetak Spanduk' THEN
                                                  (
                                                         SELECT FILE
                                                         FROM   tbl_persyaratan_cetak_spanduk
                                                         WHERE  no_disposisi = dispo.no limit 1)
                                        WHEN dispo.perihal = 'Belanja Barang Keperluan Lainnya' THEN
                                                  (
                                                         SELECT FILE
                                                         FROM   tbl_persyaratan_belanja_barang_keperluan_lainnya
                                                         WHERE  no_disposisi = dispo.no limit 1)
                                        WHEN dispo.perihal = 'Paket Sewa Ruangan Halfday' THEN
                                                  (
                                                         SELECT FILE
                                                         FROM   tbl_persyaratan_paket_sewa_ruangan_halfday
                                                         WHERE  no_disposisi = dispo.no limit 1)
                                        WHEN dispo.perihal = 'Paket Sewa Ruangan Fullday' THEN
                                                  (
                                                         SELECT FILE
                                                         FROM   tbl_persyaratan_paket_sewa_ruangan_fullday
                                                         WHERE  no_disposisi = dispo.no limit 1)
                                        WHEN dispo.perihal = 'Paket Sewa Ruangan Fullboard' THEN
                                                  (
                                                         SELECT FILE
                                                         FROM   tbl_persyaratan_sewa_ruangan_fullboard
                                                         WHERE  no_disposisi = dispo.no limit 1)
                                        WHEN dispo.perihal = 'Honor Narasumber' THEN
                                                  (
                                                         SELECT FILE
                                                         FROM   tbl_persyaratan_honor_narasumber
                                                         WHERE  no_disposisi = dispo.no limit 1)
                                        WHEN dispo.perihal = 'Biaya Perjalanan Dinas' THEN
                                                  (
                                                         SELECT FILE
                                                         FROM   tbl_persyaratan_biaya_perjalanan_dinas
                                                         WHERE  no_disposisi = dispo.no limit 1)
                                        WHEN dispo.perihal = 'Biaya Perjalanan Dinas Narasumber' THEN
                                                  (
                                                         SELECT FILE
                                                         FROM   tbl_persyaratan_biaya_perjalanan_dinas_narasumber
                                                         WHERE  no_disposisi = dispo.no limit 1)
                              END), 256)
                    ELSE 'Belum ada File'
          END AS file_persyaratan,
          CASE
                    WHEN THEN 'Telah Disetujui'
                    ELSE 'Belum diproses'
          END           AS status
FROM      tbl_disposisi AS dispo
JOIN      users         AS u
ON        u.id_user = dispo.id_user
JOIN      data_karyawan AS dk
ON        dk.id_user = u.id_user
LEFT JOIN unit_sub_direktorat AS usub
ON        usub.id_unit = dk.id_unit
LEFT JOIN tbl_status_perubahan_disposisi AS status
ON        status.no = dispo.no
LEFT JOIN jabatan AS j
ON        j.id_jabatan = dk.id_jabatan
UNION ALL
SELECT    tbl_sppd.id_sppd                                 AS no,
          dk.nama_lengkap                                  AS nama_pengaju,
          j.nama_jabatan                                   AS jabatan,
          date_format(tbl_sppd.tgl_permohonan, '%d/%m/%Y') AS tanggal_pengajuan,
          'Pelaksanaan SPPD'                               AS jenis_pengajuan,
          usub.nama                                        AS sub_direktorat_pengaju,
          'Kepala Sub Direktorat Verifikasi Teknis'        AS persetujuan_selanjutnya,
          NULL                                             AS lembar_persetujuan_pembayaran_table,
          NULL                                             AS file_persyaratan,
't' as status
FROM      tbl_sppd
JOIN      users AS u
ON        u.id_user = tbl_sppd.id_user
JOIN      data_karyawan AS dk
ON        dk.id_user = u.id_user
LEFT JOIN tbl_status_perubahan_sppd AS status
ON        status.id_sppd = tbl_sppd.id_sppd
LEFT JOIN unit_sub_direktorat AS usub
ON        usub.id_unit = dk.id_unit
LEFT JOIN jabatan AS j
ON        j.id_jabatan = dk.id_jabatan
LEFT JOIN tbl_disposisi_sppd dispo
ON        dispo.id_sppd=tbl_sppd.id_sppd
UNION ALL
SELECT    tbl_sppd_pembayaran_sppd.no_pembayaran_sppd                  AS no,
          dk.nama_lengkap                                              AS nama_pengaju,
          j.nama_jabatan                                               AS jabatan,
          date_format(tbl_sppd_pembayaran_sppd.created_at, '%d/%m/%Y') AS tanggal_pengajuan,
          'Pembayaran SPPD'                                            AS jenis_pengajuan,
          usub.nama                                                    AS sub_direktorat_pengaju,
          'Kepala Sub Direktorat Verifikasi Teknis'                    AS persetujuan_selanjutnya,
          NULL                                                         AS lembar_persetujuan_pembayaran_table,
          NULL                                                         AS file_persyaratan,
't' as status
FROM      tbl_sppd_pembayaran_sppd
JOIN      users AS u
ON        u.id_user = tbl_sppd_pembayaran_sppd.id_user
JOIN      data_karyawan AS dk
ON        dk.id_user = u.id_user
LEFT JOIN tbl_status_perubahan_sppd_pembayaran AS status
ON        status.no_pembayaran_sppd = tbl_sppd_pembayaran_sppd.no_pembayaran_sppd
LEFT JOIN unit_sub_direktorat AS usub
ON        usub.id_unit = dk.id_unit
LEFT JOIN jabatan AS j
ON        j.id_jabatan = dk.id_jabatan
JOIN      tbl_disposisi_sppd_pembayaran dispo
ON        dispo.no_pembayaran_sppd=tbl_sppd_pembayaran_sppd.no_pembayaran_sppd
UNION ALL
SELECT    tbl_sppd_pembayaran_sppd.no_pembayaran_sppd                  AS no,
          dk.nama_lengkap                                              AS nama_pengaju,
          j.nama_jabatan                                               AS jabatan,
          date_format(tbl_sppd_pembayaran_sppd.created_at, '%d/%m/%Y') AS tanggal_pengajuan,
          'Pembayaran SPPD'                                            AS jenis_pengajuan,
          usub.nama                                                    AS sub_direktorat_pengaju,
          'Kepala Sub Direktorat Verifikasi Teknis'                    AS persetujuan_selanjutnya,
          NULL                                                         AS lembar_persetujuan_pembayaran_table,
          NULL                                                         AS file_persyaratan,
't' as status
FROM      tbl_sppd_pembayaran_sppd
JOIN      users AS u
ON        u.id_user = tbl_sppd_pembayaran_sppd.id_user
JOIN      data_karyawan AS dk
ON        dk.id_user = u.id_user
LEFT JOIN tbl_status_perubahan_sppd_pembayaran AS status
ON        status.no_pembayaran_sppd = tbl_sppd_pembayaran_sppd.no_pembayaran_sppd
LEFT JOIN unit_sub_direktorat AS usub
ON        usub.id_unit = dk.id_unit
LEFT JOIN jabatan AS j
ON        j.id_jabatan = dk.id_jabatan
LEFT JOIN tbl_disposisi_sppd_pembayaran dispo
ON        dispo.no_pembayaran_sppd=tbl_sppd_pembayaran_sppd.no_pembayaran_sppd
ORDER BY  nama_pengaju ASC,
          sub_direktorat_pengaju ASC,
          tanggal_pengajuan DESC