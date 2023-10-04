## Testing  Flow  :  PENGEDITAN_JURNAL

Digunakan untuk testing flow PENGEDITAN_JURNAL mengunakan camunda engine
        transaksi akan di kirim dan mengunggu approval dari admin dan direksi sebelum masuk ke database uatama
        

## User yang akan di gunakan :  Dr. Larue Mraz V

## Menyiapkan Company yang akan di gunakan

Payload yang di gunakan :

```
{"nama":"Dr. Kyler Zulauf PhD","alamat":"Jl. Test","domain":"test.com","cabang":{"nama":"Prof. Ilene Walsh Sr.","alamat":"Jl. Cabang Test","kode":"CT"},"owner":{"nama":"Rhiannon Kertzmann","email":"gortiz@jast.com","password":"password"}}
```

## Data Cabang Yang akan di gunakan

cabang : Prof. Ilene Walsh Sr.

## Start Process Instance 

Start Process instance deongan id  118 dan variable: 

```
[]
```

## Jalankan service task  Notifikasi Direksi

Output : 

## Jalankan usertask review-direksi dengan taskid 65f9f18b-6287-11ee-ba1e-0242ac110003

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

