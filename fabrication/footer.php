</div> </div> </div> <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById("menu-toggle").addEventListener("click", function() {
        document.getElementById("wrapper").classList.toggle("toggled");
    });
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2-search').select2({
            theme: 'bootstrap-5',
            placeholder: '--- Pilih atau Cari ---',
            allowClear: true
        });
    });
</script>
<script>
$(document).ready(function() {
    // Fungsi klik tombol Tambah Detail NG
    $('#add-ng-detail').click(function() {
        let ngRow = `
            <div class="row g-2 mb-2 align-items-end border-start border-danger border-4 ps-2 bg-light py-2 rounded">
                <div class="col-md-4">
                    <label class="small fw-bold">Jenis NG</label>
                    <select name="ng_type[]" class="form-select form-select-sm">
                        <option value="Rough Surface/Kasar">Rough Surface/Kasar</option>
                        <option value="White Rust/Karat Putih">White Rust/Karat Putih</option>
                        <option value="Spikes/Jaruman">Spikes/Jaruman</option>
                        <option value="Peel Off/Terkelupas">Peel Off/Terkelupas</option>
                        <option value="Pin Holes/Bintik">Pin Holes/Bintik</option>
                        <option value="Sagging/Tidak rata">Sagging/Tidak rata</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>
                <div class="col-md-7">
                    <label class="small fw-bold">Keterangan Tambahan</label>
                    <input type="text" name="ng_remark[]" class="form-control form-select-sm" placeholder="Contoh: Area flange kiri">
                </div>
                <div class="col-md-1 text-end">
                    <button type="button" class="btn btn-danger btn-sm remove-ng-row"><i class="fas fa-times"></i></button>
                </div>
            </div>`;
        $('#ng-container').append(ngRow);
    });

    // Fungsi hapus baris NG
    $(document).on('click', '.remove-ng-row', function() {
        $(this).closest('.row').remove();
    });
});
</script>
</body>
</html>