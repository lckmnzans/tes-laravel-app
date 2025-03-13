import './bootstrap';
import $ from 'jquery';
import 'select2/dist/css/select2.min.css';
import 'select2';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Inisialisasi Select2 setelah AlpineJS dimulai
document.addEventListener('alpine:init', () => {
    $(document).ready(function() {
        // Inisialisasi Select2 pada elemen select yang memiliki class "select2"
        $('.select2').select2({
            placeholder: "Cari...",
            allowClear: true
        });

        // Menambahkan event listener untuk produk tambahan
        $('#products-section').on('click', '.product-row', function () {
            $(this).find('select').select2({
                placeholder: "Cari Produk...",
                allowClear: true
            });
        });
    });
});
