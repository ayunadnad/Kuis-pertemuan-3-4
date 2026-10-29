<?php

class Buku {
    private $kode;
    private $judul;
    private $penulis;
    private $stok;

    public function __construct($kode, $judul, $penulis, $stok) {
        $this->kode = $kode;
        $this->judul = $judul;
        $this->penulis = $penulis;
        $this->stok = $stok;
    }

    public function getKode()    { return $this->kode; }
    public function getJudul()   { return $this->judul; }
    public function getPenulis() { return $this->penulis; }
    public function getStok()    { return $this->stok; }

    public function setStok($stok) {
        if ($stok >= 0) {
            $this->stok = $stok;
        } else {
            echo "Stok $stok tidak valid!<br>";
        }
    }

    public function tampilkanInfo() {
        echo "Kode: $this->kode<br>";
        echo "Judul: $this->judul<br>";
        echo "Penulis: $this->penulis<br>";
        echo "Stok: $this->stok<br>";
    }
}

class Anggota {
    public $nama;
    public $idAnggota;

    public function __construct($nama, $idAnggota) {
        $this->nama = $nama;
        $this->idAnggota = $idAnggota;
    }

    public function getDendaPerHari() {
        return 1000;
    }

    public function tampilkanInfo() {
        echo "ID Anggota: $this->idAnggota<br>";
        echo "Nama: $this->nama<br>";
    }
}

class Mahasiswa extends Anggota {
    public $nim;

    public function __construct($n, $id, $nim) {
        parent::__construct($n, $id);
        $this->nim = $nim;
    }

    // Overriding
    public function getDendaPerHari() {
        return 1000;
    }

    public function tampilkanInfo() {
        parent::tampilkanInfo();
        echo "NIM: $this->nim<br>";
    }
}

class Dosen extends Anggota {
    public $nip;

    public function __construct($n, $id, $nip) {
        parent::__construct($n, $id);
        $this->nip = $nip;
    }

    // Overriding: denda dosen berbeda
    public function getDendaPerHari() {
        return 500;
    }

    public function tampilkanInfo() {
        parent::tampilkanInfo();
        echo "NIP: $this->nip<br>";
    }
}

class Peminjaman {
    private $anggota;
    private $buku;
    private $status;

    public function __construct($anggota, $buku) {
        $this->anggota = $anggota;
        $this->buku = $buku;
        $this->status = "Belum diproses";
    }

    public function pinjam() {
        if ($this->buku->getStok() > 0) {
            $this->buku->setStok($this->buku->getStok() - 1);
            $this->status = "Dipinjam";
            echo "{$this->anggota->nama} berhasil meminjam buku {$this->buku->getJudul()}.<br>";
        } else {
            $this->status = "Ditolak";
            echo "Stok buku {$this->buku->getJudul()} habis.<br>";
        }
    }

    public function hitungDenda($hariTerlambat) {
        return $hariTerlambat * $this->anggota->getDendaPerHari();
    }

    public function kembalikan() {
        $this->buku->setStok($this->buku->getStok() + 1);
        $this->status = "Dikembalikan";
        echo "{$this->anggota->nama} mengembalikan buku {$this->buku->getJudul()}.<br>";
    }

    public function tampilkanInfo() {
        echo "Peminjam: {$this->anggota->nama}<br>";
        echo "Buku: {$this->buku->getJudul()}<br>";
        echo "Status: $this->status<br>";
    }
}

$buku1 = new Buku("B001", "Pemrograman Dasar", "Budi Santoso", 2);
$buku2 = new Buku("B002", "Belajar PHP OOP", "Ani Wijaya", 1);

$mhs   = new Mahasiswa("Wayan", "A001", "25003001");
$dosen = new Dosen("Made", "A002", "198001012005");

echo "<b>Data Buku</b><br>";
$buku1->tampilkanInfo();
echo "<br>";
$buku2->tampilkanInfo();

echo "<br><b>Data Anggota</b><br>";
$mhs->tampilkanInfo();
echo "<br>";
$dosen->tampilkanInfo();

echo "<br><b>Peminjaman</b><br>";
$p1 = new Peminjaman($mhs, $buku1);
$p2 = new Peminjaman($dosen, $buku2);
$p3 = new Peminjaman($mhs, $buku2);   // stok habis
$p1->pinjam();
$p2->pinjam();
$p3->pinjam();

echo "<br>";
$p1->tampilkanInfo();

echo "<br><b>Denda keterlambatan 5 hari</b><br>";
$daftar = [$mhs, $dosen];
foreach ($daftar as $a) {
    $p = new Peminjaman($a, $buku1);
    echo "Denda {$a->nama}: Rp " . $p->hitungDenda(5) . "<br>";
}

echo "<br><b>Pengembalian</b><br>";
$p1->kembalikan();
echo "Stok {$buku1->getJudul()}: " . $buku1->getStok() . "<br>";

$buku1->setStok(-5); 
?>