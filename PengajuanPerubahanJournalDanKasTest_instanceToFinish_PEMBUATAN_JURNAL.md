## Testing  Flow  :  PEMBUATAN_JURNAL

Digunakan untuk testing flow PEMBUATAN_JURNAL mengunakan camunda engine
        transaksi akan di kirim dan mengunggu approval dari admin dan direksi sebelum masuk ke database uatama
        

## User yang akan di gunakan :  Eloise O'Reilly

## Menyiapkan Company yang akan di gunakan

Payload yang di gunakan :

```
{"nama":"Ms. June Bednar DDS","alamat":"Jl. Test","domain":"test.com","cabang":{"nama":"Dr. Maria Bashirian IV","alamat":"Jl. Cabang Test","kode":"CT"},"owner":{"nama":"Gabrielle Zemlak V","email":"eryn91@schuster.com","password":"password"}}
```

## Data Cabang Yang akan di gunakan

cabang : Dr. Maria Bashirian IV

## Start Process Instance 

Start Process instance deongan id  116 dan variable: 

```
[]
```

## Jalankan service task  Notifikasi Direksi

Output : 

## Jalankan usertask review-direksi dengan taskid 656a5b81-6287-11ee-ba1e-0242ac110003

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

