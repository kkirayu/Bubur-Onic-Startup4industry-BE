## Testing  Flow  :  PEMBUATAN_JURNAL

Digunakan untuk testing flow PEMBUATAN_JURNAL mengunakan camunda engine
        transaksi akan di kirim dan mengunggu approval dari admin dan direksi sebelum masuk ke database uatama
        

## User yang akan di gunakan :  

```
{
    "name": "Berta Volkman",
    "email": "cecil59@example.com",
    "email_verified_at": "2023-10-06T03:57:33.000000Z",
    "updated_at": "2023-10-06T03:57:33.000000Z",
    "created_at": "2023-10-06T03:57:33.000000Z",
    "id": 158
}
```

## Menyiapkan Company yang akan di gunakan

Payload:

```
{
    "nama": "Prof. Davon Wehner",
    "alamat": "Jl. Test",
    "domain": "test.com",
    "cabang": {
        "nama": "Nicholaus Johnston",
        "alamat": "Jl. Cabang Test",
        "kode": "CT"
    },
    "owner": {
        "nama": "Ms. Edyth Dare",
        "email": "steve87@gmail.com",
        "password": "password"
    }
}
```

url : 

api/saas/perusahaan/register-perusahaan

Response :

```
{
    "baseResponse": {
        "headers": {},
        "original": {
            "id": 23,
            "created_at": "2023-10-06T03:57:33.000000Z",
            "updated_at": "2023-10-06T03:57:33.000000Z",
            "created_by": null,
            "updated_by": null,
            "deleted_by": null,
            "kode_perusahaan": "PERU20231006035733",
            "domain_perusahaan": "test.com",
            "status_perusahaan": "AKTIF",
            "nama": "Prof. Davon Wehner",
            "alamat": "Jl. Test"
        },
        "exception": null
    },
    "exceptions": []
}
```

## Data Cabang Yang akan di gunakan

cabang : 

```
{
    "id": 23,
    "kode_cabang": "CAB20231006035733",
    "perusahaan_id": 23,
    "nama": "Nicholaus Johnston",
    "alamat": "Jl. Cabang Test",
    "created_at": "2023-10-06T03:57:33.000000Z",
    "updated_at": "2023-10-06T03:57:33.000000Z",
    "created_by": null,
    "updated_by": null,
    "deleted_by": null
}
```

## Payload Pengajuan 

Payload yang di gunakan :

```
{
    "perusahaan_id": 23,
    "cabang_id": 23,
    "jenis_aksi": "PEMBUATAN_JURNAL",
    "payload": "{\"nama\":\"Tambah Kas \"}",
    "nama": "Tambah Kas "
}
```

response :

```
{
    "perusahaan_id": 23,
    "cabang_id": 23,
    "jenis_aksi": "PEMBUATAN_JURNAL",
    "payload": "{\"nama\":\"Tambah Kas \"}",
    "nama": "Tambah Kas ",
    "updated_at": "2023-10-06T03:57:34.000000Z",
    "created_at": "2023-10-06T03:57:34.000000Z",
    "id": 15
}
```

## Start Process Instance 

Start Process instance dengan id  15 dan payload: 

```
[]
```

response :

```
{
    "baseResponse": {
        "headers": {},
        "original": {
            "id": 15,
            "process_instance_id": "7b3ede6a-63fc-11ee-84de-0242ac110002",
            "perusahaan_id": 23,
            "cabang_id": 23,
            "payload": "{\"nama\":\"Tambah Kas \"}",
            "nama": "Tambah Kas ",
            "jenis_aksi": "PEMBUATAN_JURNAL",
            "created_at": "2023-10-06T03:57:34.000000Z",
            "updated_at": "2023-10-06T03:57:34.000000Z",
            "created_by": null,
            "updated_by": null,
            "deleted_by": null
        },
        "exception": null
    },
    "exceptions": []
}
```

## Jalankan service task  Notifikasi Direksi

Output : 

## Jalankan usertask review-direksi dengan taskid 7b635664-63fc-11ee-84de-0242ac110002

Payload :

```
{
    "review_direksi": "TERIMA",
    "keterangan_konfirmasi": "ga oke direvisi dulu"
}
```

url : 

/api/pengajuan-perubahan-journal-dan-kas/review-direksi/15/task/7b635664-63fc-11ee-84de-0242ac110002/submit

response :

```
{
    "baseResponse": {
        "headers": {},
        "original": {
            "review_direksi": "TERIMA",
            "business_key": "15",
            "updated_at": "2023-10-06T03:57:35.000000Z",
            "created_at": "2023-10-06T03:57:35.000000Z",
            "id": 13
        },
        "exception": null
    },
    "exceptions": []
}
```

## Jalankan service task  Pengerjaan Task

Output : 

## Jalankan service task  Notifikasi email

Output : 

## Pastikan tidak ada task yang tersisa 

Sista Task :  0

