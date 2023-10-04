## Testing  Flow  :  PEMBUATAN_KAS

Digunakan untuk testing flow PEMBUATAN_KAS mengunakan camunda engine
        transaksi akan di kirim dan mengunggu approval dari admin dan direksi sebelum masuk ke database uatama
        

## User yang akan di gunakan :  Prof. Jaycee Fahey Jr.

## Menyiapkan Company yang akan di gunakan

Payload yang di gunakan :

```
{"nama":"Mr. Nels Roberts","alamat":"Jl. Test","domain":"test.com","cabang":{"nama":"Mohamed Ferry","alamat":"Jl. Cabang Test","kode":"CT"},"owner":{"nama":"Milford Hyatt","email":"corwin.maximillia@gmail.com","password":"password"}}
```

## Data Cabang Yang akan di gunakan

cabang : Mohamed Ferry

## Start Process Instance 

Start Process instance deongan id  119 dan variable: 

```
[]
```

## Jalankan service task  Notifikasi Direksi

Output : 

## Jalankan usertask review-direksi dengan taskid 663dff88-6287-11ee-ba1e-0242ac110003

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

