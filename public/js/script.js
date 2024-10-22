const hamBurger = document.querySelector(".toggle-btn");

hamBurger.addEventListener("click", function () {
    document.querySelector("#sidebar").classList.toggle("expand");
});

$(document).ready(function () {
    $("#dropdownMenuButton").on("click", function (event) {
        // Menghentikan propagasi ke elemen induk agar dropdown tidak tertutup seketika
        event.stopPropagation();

        // Memunculkan atau menyembunyikan dropdown saat tombol diklik
        $(".dropdown-menu").toggle();
    });

    // Menutup dropdown saat dokumen di-klik di luar dropdown
    $(document).on("click", function (event) {
        if (!$(".dropdown").is(event.target) && $(".dropdown").has(event.target).length === 0) {
            $(".dropdown-menu").hide();
        }
    });
});


function updateTime() {
    var now = new Date();
    var hours = now.getHours();
    var minutes = now.getMinutes();
    var seconds = now.getSeconds();
    var day = now.toLocaleDateString('id-ID', { weekday: 'long' });
    var date = now.toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' });

    // Format waktu
    var formattedTime = hours + ":" + (minutes < 10 ? "0" : "") + minutes + ":" + (seconds < 10 ? "0" : "") + seconds;

    // Tampilkan hari, tanggal, dan jam dalam dropdown
    document.getElementById('realtime-clock').innerText = day + ", " + date + "  |  " + formattedTime;


}

// Panggil fungsi untuk memperbarui waktu setiap detik
setInterval(updateTime, 1000);

// Panggil fungsi sekali agar waktu ditampilkan segera saat halaman dimuat
updateTime();


// Panggil updateTime setiap detik
setInterval(updateTime, 1000);

document.addEventListener("DOMContentLoaded", function () {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.querySelector('.toggle-btn');

    toggleBtn.addEventListener('click', function () {
        sidebar.classList.toggle('collapsed');
    });
});


