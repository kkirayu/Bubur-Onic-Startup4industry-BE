## Testing  Flow  :  PENGHAPUSAN_JURNAL

Digunakan untuk testing flow PENGHAPUSAN_JURNAL mengunakan camunda engine
        transaksi akan di kirim dan mengunggu approval dari admin dan direksi sebelum masuk ke database uatama
        

## User yang akan di gunakan :  Julian Beier

## Menyiapkan Company yang akan di gunakan

Payload yang di gunakan :

```
{"nama":"Mr. Tristian Anderson Jr.","alamat":"Jl. Test","domain":"test.com","cabang":{"nama":"Callie Price","alamat":"Jl. Cabang Test","kode":"CT"},"owner":{"nama":"Mr. Hilbert Yundt DVM","email":"angeline.ruecker@bosco.com","password":"password"}}
```

## Data Cabang Yang akan di gunakan

cabang : Callie Price

## Start Process Instance 

Start Process instance deongan id  117 dan variable: 

```
[]
```

## Jalankan service task  Notifikasi Direksi

Output : 

## Jalankan usertask review-direksi dengan taskid 65b917de-6287-11ee-ba1e-0242ac110003

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

