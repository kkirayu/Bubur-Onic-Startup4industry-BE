## Testing  Flow  :  PERUBAHAN_KAS

Digunakan untuk testing flow PERUBAHAN_KAS mengunakan camunda engine
        transaksi akan di kirim dan mengunggu approval dari admin dan direksi sebelum masuk ke database uatama
        

## User yang akan di gunakan :  Mrs. Raegan Kunde PhD

## Menyiapkan Company yang akan di gunakan

Payload yang di gunakan :

```
{"nama":"Geo Rodriguez","alamat":"Jl. Test","domain":"test.com","cabang":{"nama":"Lawrence Denesik","alamat":"Jl. Cabang Test","kode":"CT"},"owner":{"nama":"Hermann Willms","email":"cordie26@paucek.info","password":"password"}}
```

## Data Cabang Yang akan di gunakan

cabang : Lawrence Denesik

## Start Process Instance 

Start Process instance deongan id  120 dan variable: 

```
[]
```

## Jalankan service task  Notifikasi Direksi

Output : 

## Jalankan usertask review-direksi dengan taskid 6682d0d5-6287-11ee-ba1e-0242ac110003

Payload yang di gunakan :

```
{"review_direksi":"TERIMA","keterangan_konfirmasi":"ga oke direvisi dulu"}
```

## Jalankan service task  Pengerjaan Task

Output : 

## Jalankan service task  Notifikasi email

Output : 

## Pastikan tidak ada task yang tersisa 

Sista Task :  0

