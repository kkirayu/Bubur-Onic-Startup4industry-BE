## Testing Revisi journal terlebih dahulu sebelum melakukan flow normal

Digunakan untuk testing flow revisi journal terlebih dahulu sebelum melakukan flow normal bertujuan untuk memastikan revisi journal berjalan dengan baik
        

## Start Process Instance 

Start Process instance deongan id  121 dan variable: 

```
[]
```

## Jalankan usertask review-direksi dengan taskid 66cf1b32-6287-11ee-ba1e-0242ac110003

Payload yang di gunakan :

```
{"review_direksi":"REVISI","keterangan_konfirmasi":"ga oke direvisi dulu"}
```

## Jalankan usertask revisi-pengajuan dengan taskid 66d8df3a-6287-11ee-ba1e-0242ac110003

Payload yang di gunakan :

```
[]
```

## Jalankan usertask review-direksi dengan taskid 66e58972-6287-11ee-ba1e-0242ac110003

Payload yang di gunakan :

```
{"review_direksi":"TERIMA","keterangan_konfirmasi":"ga oke direvisi dulu"}
```

