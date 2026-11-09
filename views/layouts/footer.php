<?php
// views/layouts/footer.php
?>
    <!-- End App Container / Main Content -->
    
    <!-- Scripts -->
    <script src="assets/js/jquery-3.7.1.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/jquery.dataTables.min.js"></script>
    <script src="assets/js/dataTables.bootstrap5.min.js"></script>
    <script src="assets/js/chart.umd.min.js"></script>
    <script src="assets/js/sweetalert2.all.min.js"></script>
    
    <script>
        // Toggle Sidebar on mobile
        const sidebarToggle = document.getElementById('sidebarToggle');
        const appSidebar = document.getElementById('appSidebar');
        if (sidebarToggle && appSidebar) {
            sidebarToggle.addEventListener('click', () => {
                appSidebar.classList.toggle('d-none');
            });
        }

        // Auto initialize DataTables
        $(document).ready(function() {
            if ($.fn.DataTable) {
                $('.datatable-init').DataTable({
                    language: {
                        search: "Cari Data:",
                        lengthMenu: "Tampilkan _MENU_ baris",
                        info: "Menampilkan _START_ s/d _END_ dari _TOTAL_ data",
                        infoEmpty: "Tidak ada data",
                        infoFiltered: "(disaring dari _MAX_ total data)",
                        zeroRecords: "Data tidak ditemukan",
                        paginate: {
                            first: "Awal",
                            last: "Akhir",
                            next: "Berikutnya",
                            previous: "Sebelumnya"
                        }
                    },
                    pageLength: 10,
                    responsive: true
                });
            }
        });

        // Helper Konfirmasi Hapus dengan SweetAlert2
        function confirmDelete(url, title = 'Hapus Data Ini?') {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: title,
                    text: 'Data yang telah dihapus tidak dapat dipulihkan!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="bi bi-trash me-1"></i> Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = url;
                    }
                });
                return false;
            } else {
                if (confirm(title)) {
                    window.location.href = url;
                }
            }
        }
    </script>
</body>
</html>
