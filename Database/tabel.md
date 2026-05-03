Kode Kerusakan	Kerusakan
K01	Sekering
K02	Aki
K03	Busi
K04	Fuel Pump
K05	Injektor
K06	Koil/spul/ECM
K07	Piston
K08	Bahan Bakar
K09	Bocor Kompresi
K10	Pengapian

Tabel 3.2 Jenis gejala
Kode gejala	Gejala
G01	Mesin tidak bisa hidup & lampu mati total
G02	Klakson tidak bunyi
G03	Speedometer mati
G04	Starter lemah,lampu redup,klakson pelan
G05	Motor sulit distarter terutama saat pagi hari
G06	Aki cepat habis meski baru diisi
G07	Mesin brebet atau tersendat
G08	Mesin sulit hidup
G09	Mesin hidup sebenter lalu mati
G10	Knalpot meletup kecil
G11	Mesin tidak mendapat suplai bahan bakar
G12	Mesin mendadak mati saat jalan
G13	Suara pompa (fuel pump)tidak terdengar
G14	Mesin tersendat saat akselerasi
G15	Konsumsi bahan bakar boros
G16	Knalpot berasap hitam
G17	Mesin sering mati di idle
G18	Tidak ada percikan api di busi
G19	Mesin mati total meski aki normal
G20	Mesin hidup-mati tidak stabil
G21	Suara mesin kasar (ngelitik)
G22	Tenaga berkurang drastis
G23	Oli cepat habis
G24	Knalpot berasap putih/abu
G25	Tarikan terasa berat
G26	Mesin mati padahal indikator masih ada bensin
G27	Tenaga hilang
G28	Mesin cepat panas
G29	Mesin tidak stabil di RPM tinggi



Tabel 3.3 Jenis Solusi
Kode solusi	Solusi
S1	Ganti sekering yang putus dengan ukuran yang sama
S2	Isi ulang aki, cek sistem pengisian, ganti aki jika soak
S3	Bersihkan busi, setel celah busi, ganti busi jika sudah aus
S4	Periksa kabel pompa, bersihkan filter, ganti pompa jika rusak
S5	Bersihkan injektor dengan cairan khusus atau servis injektor
S6	Periksa kabel koil, ganti koil/spul/ECM bila rusak
S7	Bongkar mesin, ganti piston dan ring piston
S8	Bersihkan tangki, ganti filter bahan bakar, gunakan bahan bakar berkualitas
S9	Setel ulang klep, ganti gasket kepala silinder, lakukan overhoule jika perlu
S10	Periksa CDI, kabel pengapian, ganti komponen pengapian yang rusak

Tabel 3.4 Tabel Rule
Rule	Kode Gejala	Kode Kerusakan	Kode Solusi
R1	G01,G02,G03	K01	S1
R2	G04,G05,G06	K02	S2
R3	G07,G08,G09,G10	K03	S3
R4	G11,G12,G13	K04	S4
R5	G14,G15,G16,G17	K05	S5
R6	G18,G19,G20	K06	S6
R7	G21,G22,G23,G24	K07	S7
R8	G07,G25,G26	K08	S8
R9	G08,G15,G27,G28	K09	S9
R10	G12,G14,G29	K10	S10

3.3.2	Konversi Tabel Keputusan Menjadi Kaidah Produksi
1.  IF Rule Untuk Kerusakan Sekering	(K01)
And Mesin tidak bisa hidup & lampu mati total	(G01)
And Klakson tidak bunyi	(G02)
And Speedometer mati	(G03)
Then Sekering

2. IF Rule Untuk Kerusakan Aki	(K02)
And Starter lemah, lampu redup, klakson pelan	(G04)
And Motor sulit distarter terutama pagi hari	(G05)
And Aki cepat habis meski baru diisi	(G06)
Then Aki


3. IF Rule Untuk Kerusakan Busi	(K03)
And Mesin berebet atau tersendat	(G07)
And Mesin Sulit hidup	(G08)
And Mesin hidup sebentar lalu mati	(G09)
And Knalpot meletup kecil	(G10)
Then Busi


4. IF Rule Untuk Kerusakan Fuel Pump	
(K04)
And Mesin tidak mendapatkan suplai bahan bakar	(G11)
And Mesin mati mendadak saat jalan	(G12)
And Suara pompa (fuel pump) tidak terdengar
Then Fuel Pump	(G13)

5.  IF Rule Untuk Kerusakan Injektor	
(K05)
And Mesin tersendat saat akselerasi	(G14)
And Konsumsi bahan bakar boros	(G15)
And Knalpot berasap hitam	(G16)
And Mesin sering mati di idle
Then Injektor	(G17)


6.  IF Rule Untuk Kerusakan Koil/Spul/ECM	
(K06)
And Tidak ada percikan api di busi	(G18)
And Mesin mati total meski aki normal	(G19)
And Mesin hidup-mati tidak setabil
Then Koil/Spul/ECM	(G20)


7.  IF Rule Untuk Kerusakan Pison	
(K07)
And Suara mesin kasar (ngelitik)	(G21)
And Tenaga berkurang drastis	(G22)
And Oli cepat habis	(G23)
And Knalpot berasap putih/abu
Then Piston	(G24)
    

8.  IF Rule Untuk Kerusakan Bahan bakar	
(K08)
And Mesin berebet atau tersendat	(G07)
And Tarikan terasa berat	(G25)
And Mesin mati padahal indikator bensin masih ada	(G26)
Then Bahan bakar	
    

9.  IF Rule Untuk Kerusakan Bocor Kompresi	
(K09)
And Mesin Sulit hidup	(G08)
And Konsumsi bahan bakar boros	(G15)
And Tenaga hilang	(G27)
And Mesin cepat panas
Then Bocor kompresi	(G28)
10.  IF Rule Untuk Kerusakan Pengapian	(K10)
And Mesin mati mendadak saat jalan	(G12)
And Mesin tersendat saat akselerasi	(G14)
And Mesin tidak stabil di RPM tinggi	(G29)
Then Pengapian
