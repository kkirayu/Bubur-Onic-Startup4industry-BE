## Testing  Flow  :  PEMBUATAN_JURNAL

Digunakan untuk testing flow PEMBUATAN_JURNAL mengunakan camunda engine
        transaksi akan di kirim dan mengunggu approval dari admin dan direksi sebelum masuk ke database uatama
        

## User yang akan di gunakan :  

```
{"name":"Larissa Tromp","email":"kelsie.cole@example.org","email_verified_at":"2023-10-06T03:21:44.000000Z","updated_at":"2023-10-06T03:21:44.000000Z","created_at":"2023-10-06T03:21:44.000000Z","id":154}
```

## Menyiapkan Company yang akan di gunakan

Payload:

```
{"nama":"Gregg Padberg","alamat":"Jl. Test","domain":"test.com","cabang":{"nama":"Misty McDermott","alamat":"Jl. Cabang Test","kode":"CT"},"owner":{"nama":"Darion Emmerich","email":"janelle.steuber@yahoo.com","password":"password"}}
```

Response :

```
{"baseResponse":{"headers":{},"original":{"id":21,"created_at":"2023-10-06T03:21:44.000000Z","updated_at":"2023-10-06T03:21:44.000000Z","created_by":null,"updated_by":null,"deleted_by":null,"kode_perusahaan":"PERU20231006032144","domain_perusahaan":"test.com","status_perusahaan":"AKTIF","nama":"Gregg Padberg","alamat":"Jl. Test"},"exception":null},"exceptions":[]}
```

## Data Cabang Yang akan di gunakan

cabang : 

```
{"id":21,"kode_cabang":"CAB20231006032145","perusahaan_id":21,"nama":"Misty McDermott","alamat":"Jl. Cabang Test","created_at":"2023-10-06T03:21:45.000000Z","updated_at":"2023-10-06T03:21:45.000000Z","created_by":null,"updated_by":null,"deleted_by":null}
```

## Payload Pengajuan 

Payload yang di gunakan :

```
{"perusahaan_id":21,"cabang_id":21,"jenis_aksi":"PEMBUATAN_JURNAL","payload":"{\"nama\":\"Tambah Kas \"}","nama":"Tambah Kas "}
```

response :

```
{"perusahaan_id":21,"cabang_id":21,"jenis_aksi":"PEMBUATAN_JURNAL","payload":"{\"nama\":\"Tambah Kas \"}","nama":"Tambah Kas ","updated_at":"2023-10-06T03:21:45.000000Z","created_at":"2023-10-06T03:21:45.000000Z","id":13}
```

## Start Process Instance 

Start Process instance dengan id  13 dan payload: 

```
[]
```

response :

```
{"baseResponse":{"headers":{},"original":{"id":13,"process_instance_id":"7a572f64-63f7-11ee-84de-0242ac110002","perusahaan_id":21,"cabang_id":21,"payload":"{\"nama\":\"Tambah Kas \"}","nama":"Tambah Kas ","jenis_aksi":"PEMBUATAN_JURNAL","created_at":"2023-10-06T03:21:45.000000Z","updated_at":"2023-10-06T03:21:45.000000Z","created_by":null,"updated_by":null,"deleted_by":null},"exception":null},"exceptions":[]}
```

## Jalankan service task  Notifikasi Direksi

Output : 

## Jalankan usertask review-direksi dengan taskid 7a6d9d9e-63f7-11ee-84de-0242ac110002

Payload :

```
{"review_direksi":"TERIMA","keterangan_konfirmasi":"ga oke direvisi dulu"}
```

response :

```
{"baseResponse":{"headers":{},"original":{"review_direksi":"TERIMA","business_key":"13","updated_at":"2023-10-06T03:21:46.000000Z","created_at":"2023-10-06T03:21:46.000000Z","id":11},"exception":null},"exceptions":[]}
```

## Jalankan service task  Pengerjaan Task

Output : 

## Jalankan service task  Notifikasi email

Output : 

## Pastikan tidak ada task yang tersisa 

Sista Task :  0

