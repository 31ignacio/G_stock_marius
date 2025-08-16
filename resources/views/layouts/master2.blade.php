<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}

        <title>APL TRADING</title>

        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="../../../../AD/plugins/fontawesome-free/css/all.min.css">
        <!-- DataTables -->
        <link rel="stylesheet" href="../../../../AD/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
        <link rel="stylesheet" href="../../../../AD/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
        <link rel="stylesheet" href="../../../../AD/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="../../../../AD/dist/css/adminlte.min.css">

        <!-- Tempusdominus Bootstrap 4 -->
        <link rel="stylesheet" href="../../../../AD/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
        
        <!-- Bootstrap4 Duallistbox -->
        <link rel="stylesheet" href="../../../../AD/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">
        <!-- BS Stepper -->
        <link rel="stylesheet" href="../../../../AD/plugins/bs-stepper/css/bs-stepper.min.css">
        <!-- dropzonejs -->
        <link rel="stylesheet" href="../../../../AD/plugins/dropzone/min/dropzone.min.css">
        
            <!-- Select2 -->
        <link rel="stylesheet" href="../../../../AD/plugins/select2/css/select2.min.css">
        <link rel="stylesheet" href="../../../../AD/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
        <link rel="stylesheet" href="../../../../AD/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
        <link rel="stylesheet" href="../../../../AD/plugins/toastr/toastr.min.css">
        {{-- <link href="https://unpkg.com/toastify-js/src/toastify.css" rel="stylesheet"> --}}
        <link rel="stylesheet" href="../../../../AD/toastify-js-master/src/toastify.css">

        <style>
            body {
                background-image: url('../../../../AD/dist/img/2.jpg');
                background-size: cover; /* Ajuste la taille de l'image pour couvrir tout l'écran */
                background-repeat: no-repeat; /* Empêche la répétition de l'image */
                height: 100vh; /* Assure que la hauteur de la page est égale à la hauteur de l'écran */
                margin: 0; /* Supprime la marge par défaut du corps du navigateur */
            }

            /* Ajoutez ici le reste de votre CSS ou du contenu HTML */
        </style>

    </head>

    <body class="hold-transition sidebar-mini">
        
        <div class="wrapper">
            <!-- Navbar -->
            <nav class="main-header navbar navbar-expand navbar-white navbar-light py-1">
                <ul class="navbar-nav w-100 d-flex flex-wrap align-items-center">
                    <li class="nav-item mr-2">
                        <a class="nav-link p-1 text-sm" data-widget="pushmenu" href="#" role="button">
                            <i class="fas fa-bars"></i>
                        </a>
                    </li>
                    <li class="nav-item mr-3">
                        <a href="{{ route('logout') }}" class="nav-link p-1 text-sm">
                            Me Déconnecter <span class="badge badge-danger">off</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('stockAttente.index') }}" class="nav-link p-1 text-sm">
                            Stocks en attente <span class="badge badge-success">A valider</span>
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
                            <i class="far fa-bell"></i>
                            @php $count = auth()->user()->unreadNotifications->count(); @endphp
                            @if($count > 0)
                                <span class="badge badge-danger navbar-badge rounded-pill">{{ $count }}</span>
                            @endif
                        </a>

                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right p-2" onclick="event.stopPropagation();">
                            <span class="dropdown-item dropdown-header">
                                {{ $count }} Notification{{ $count > 1 ? 's' : '' }}
                            </span>
                            <div class="dropdown-divider"></div>

                            <!-- Liste paginée -->
                            <div id="notification-list">
                                @forelse(auth()->user()->unreadNotifications as $notification)
                                    <div class="dropdown-item notification-item">
                                        <div style="white-space: normal; word-wrap: break-word; font-size: 14px;">
                                            <i class="fas fa-check-circle text-success mr-2"></i>
                                            {{ $notification->data['message'] ?? 'Notification' }}
                                        </div>

                                        @if(auth()->user()->role_id == 2)
                                            <a href="{{ route('stock.index') }}" class="d-block mt-2 text-primary small">
                                                ➤ Voir le stock
                                            </a>
                                        @else
                                            <a href="{{ route('stockAttente.index') }}" class="d-block mt-2 text-primary small">
                                                ➤ Voir le stock en attente
                                            </a>
                                        @endif

                                        <form action="{{ route('notifications.delete', $notification->id) }}" method="POST" class="mt-1">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger btn-block" onclick="event.stopPropagation();">
                                                Supprimer
                                            </button>
                                        </form>
                                    </div>
                                    <div class="dropdown-divider"></div>
                                @empty
                                    <span class="dropdown-item text-center text-muted">Aucune notification</span>
                                @endforelse
                            </div>

                            <!-- Pagination mini -->
                            <div id="pagination" class="text-center pt-2"></div>
                        </div>
                    </li>
                </ul>
            </nav>
            
            <!-- Main Sidebar Container -->
            <aside class="main-sidebar sidebar-dark-primary elevation-4">
                <!-- Brand Logo -->
                <a href="#" class="brand-link">
                    
                    <h4 class="text-center font-weight-light"><i> APL TRADING </i></h4>
                </a>

                <!-- Sidebar -->
                <div class="sidebar">
                    <!-- Sidebar user (optional) -->
                    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                        <div class="image">
                            <img src="../../../../AD/dist/img/logo.png" class="img-circle elevation-2" alt="User Image">
                        </div>
                        <div class="info">
                            {{-- <a href="#" class="d-block">Admin</a> --}}
                            <marquee behavior="scroll" direction="left" scrollamount="8">
                                <span style="color: white"> <b> <i>{{ auth()->user()->name }} </i></b> </span>
                            </marquee>                        
                        </div>
                    </div>

                    <!-- SidebarSearch Form -->
                    <div class="form-inline" hidden>
                        <div class="input-group" data-widget="sidebar-search">
                            <input class="form-control form-control-sidebar" type="hidden" placeholder="Search"
                                aria-label="Search">
                            <div class="input-group-append">
                                <button class="btn btn-sidebar">
                                    <i class="fas fa-search fa-fw"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar Menu -->
                    <nav class="mt-2">
                        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                            data-accordion="false">
                            <!-- Add icons to the links using the .nav-icon class
                                with font-awesome or any other icon font library -->

                            <li class="nav-item">
                                <a href="{{route('accueil.index')}}" class="nav-link">
                                    <i class="nav-icon fas fa-home"></i>
                                    <p>
                                        Accueil
                                    </p>
                                </a>
                            </li>
                            @if(auth()->user()->role_id == 1)
                                {{-- Utilisateurs --}}

                                <li class="nav-item">
                                    <a href="{{route('admin')}}" class="nav-link">
                                        <i class="fas fa-user"></i>
                                        <p>
                                            Utilisateurs
                                        </p>
                                    </a>
                                </li>

                            @endif

                            <li class="nav-item">
                                <a href="{{route('client.index')}}" class="nav-link">
                                    <i class="fas fa-user-friends"></i>
                                    <p>
                                        Clients
                                    </p>
                                </a>
                            </li>
                            
                            @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 3)
                            
                                <li class="nav-item">
                                    <a href="{{route('produit.index')}}" class="nav-link">
                                        <i class="fas fa-shopping-bag"></i>
                                        <p>
                                            Produits
                                        </p>
                                    </a>
                                </li>
                            @endif
                                                
                            {{-- Facture --}}
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="fas fa-file-invoice-dollar"></i>

                                    <p>
                                        Factures
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                                                
                                    <li class="nav-item">
                                        <a href="{{ route('facture.create') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Ajouter une facture</p>
                                        </a>
                                    </li>

                                    @if(auth()->user()->role_id == 1)
                                
                                        <li class="nav-item">
                                            <a href="{{ route('factureAchat.create') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Ajouter facture d'achat</p>
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a href="{{ route('factureAchat.index') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Factures d'Achats</p>
                                            </a>
                                        </li>
                                    @endif

                                    <li class="nav-item">
                                        <a href="{{ route('facture.index') }}" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Factures de ventes</p>
                                        </a>
                                    </li>

                                </ul>
                            </li> 
                            {{-- Stock --}}
            
                            <li class="nav-item">
                                <a href="{{route('stock.index')}}" class="nav-link">
                                    <i class="fas fa-shopping-bag"></i>
                                    <p>
                                        Gestions de stocks
                                    </p>
                                </a>
                            </li>
                                
                            @if(auth()->user()->role_id == 1)              
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fas fa-list"></i>

                                        <p>
                                            Inventaires
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <li class="nav-item">
                                            <a href="{{ route('inventaires.index') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Divers</p>
                                            </a>
                                        </li>
                                    

                                        <li class="nav-item">
                                            <a href="{{ route('inventaires.indexPoissonnerie') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Poissonnerie</p>
                                            </a>
                                        </li>


                                    </ul>
                                </li>
                            
                                    {{-- Confirguration --}}
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fas fa-shopping-bag"></i>
        
                                        <p>
                                            Configurations
                                            <i class="fas fa-angle-left right"></i>
                                            {{-- <span class="badge badge-info right">6</span> --}}
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">

                                        <li class="nav-item">
                                            <a href="{{ route('societe.index') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Société</p>
                                            </a>
                                        </li>
        
                                    </ul>
                                </li>
                            @endif                        

                                {{-- Deconnexion --}}<br>
                            <li class="nav-item">
                                <a href="{{route('logout')}}" class="nav-link">
                                    <i class="fas fa-sign-out-alt"></i>

                                    <p>
                                        Me déconnecter
                                        <span class="right badge badge-danger">off</span>
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </aside>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                            
                <br>
                @yield('content')

            </div>
            <!-- /.content-wrapper -->
            <footer class="main-footer">
                <div class="float-right d-none d-sm-block">
                    <i>Version</i> 1.0.1
                </div>
                <i>Copyright &copy; 2025 <a href="">Ari </a>Expertiz</i>
            </footer>

            <!-- Control Sidebar -->
            <aside class="control-sidebar control-sidebar-dark">
                <!-- Control sidebar content goes here -->
            </aside>
            <!-- /.control-sidebar -->
        </div>
        <!-- ./wrapper -->

        <!-- jQuery -->
        <style>
            .page-loader{
                position:fixed;
                inset:0;                     /* top:0; right:0; bottom:0; left:0 */
                background:rgba(255,255,255,.8);   /* voile blanc semi‐opaque */
                display:flex;
                justify-content:center;
                align-items:center;
                z-index: 2000;               /* au‑dessus d’AdminLTE/Bootstrap */
            }

        </style>
        {{-- script pour le loader --}}
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const loader   = document.getElementById('pageLoader');
                const links    = document.querySelectorAll('.nav-sidebar .nav-link');

                links.forEach(link => {
                    link.addEventListener('click', e => {
                        const href = link.getAttribute('href');
                        /* On évite d’afficher le loader pour les liens qui ouvrent juste
                        un sous‑menu (href="#" ou javascript:void(0))               */
                        if (!href || href === '#' || href.startsWith('javascript')) return;

                        /* Affiche le loader */
                        loader.classList.remove('d-none');
                    });
                });

                /* Option : masque le loader quand la page cible a fini de charger */
                window.addEventListener('load', () => {
                    loader.classList.add('d-none');
                });
            });
        </script>
        {{-- script pour pagination de notification --}}
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const itemsPerPage = 3;
                const items = Array.from(document.querySelectorAll("#notification-list .notification-item"));
                const paginationContainer = document.getElementById("pagination");

                if (items.length <= itemsPerPage) return;

                let currentPage = 1;
                const totalPages = Math.ceil(items.length / itemsPerPage);

                function showPage(page) {
                    const start = (page - 1) * itemsPerPage;
                    const end = start + itemsPerPage;

                    items.forEach((item, index) => {
                        item.style.display = (index >= start && index < end) ? "block" : "none";
                    });

                    renderPagination(page);
                }

                function renderPagination(current) {
                    paginationContainer.innerHTML = "";
                    for (let i = 1; i <= totalPages; i++) {
                        const btn = document.createElement("button");
                        btn.textContent = i;
                        btn.className = "btn btn-xs btn-outline-success mx-1 py-0 px-2" + (i === current ? ' active' : '');
                        btn.style.fontSize = "12px";
                        btn.onclick = function(e) {
                            e.stopPropagation(); // empêche la fermeture
                            showPage(i);
                        };
                        paginationContainer.appendChild(btn);
                    }
                }

                showPage(currentPage);
            });
        </script>


        {{-- Dans votre vue --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>

        <script src="../../../../AD/plugins/jquery/jquery.min.js"></script>
        <!-- Bootstrap 4 -->
        <script src="../../../../AD/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- DataTables  & Plugins -->
        <script src="../../../../AD/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="../../../../AD/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
        <script src="../../../../AD/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
        <script src="../../../../AD/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
        <script src="../../../../AD/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
        <script src="../../../../AD/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
        <script src="../../../../AD/plugins/jszip/jszip.min.js"></script>
        <script src="../../../../AD/plugins/pdfmake/pdfmake.min.js"></script>
        <script src="../../../../AD/plugins/pdfmake/vfs_fonts.js"></script>
        <script src="../../../../AD/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
        <script src="../../../../AD/plugins/datatables-buttons/js/buttons.print.min.js"></script>
        <script src="../../../../AD/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
        <!-- AdminLTE App -->
        <script src="../../../../AD/dist/js/adminlte.min.js"></script>
        <script src="../../../../AD/dist/js/html2pdf.bundle.min.js"></script>

        <!-- AdminLTE for demo purposes -->
        <script src="../../../../AD/dist/js/demo.js"></script>
        <script src="../../../../AD/plugins/sweetalert2/sweetalert2.min.js"></script>
        <script src="../../../../AD/plugins/toastr/toastr.min.js"></script>
        <!-- Select2 -->
        <script src="../../../../AD/plugins/select2/js/select2.full.min.js"></script>
        <!-- Page specific script -->
        <script>
            $(function() {
                $("#example1").DataTable({
                    "responsive": true,
                    "lengthChange": false,
                    "autoWidth": false,
                    "buttons": ["excel", "pdf", "print"],
                    "language": {
                        "sProcessing":     "Traitement en cours...",
                        "sSearch":         "Rechercher :",
                        "sLengthMenu":     "Afficher _MENU_ éléments",
                        "sInfo":           "Affichage de l'élément _START_ à _END_ sur _TOTAL_ éléments",
                        "sInfoEmpty":      "Affichage de l'élément 0 à 0 sur 0 élément",
                        "sInfoFiltered":   "(filtré à partir de _MAX_ éléments au total)",
                        "sInfoPostFix":    "",
                        "sLoadingRecords": "Chargement en cours...",
                        "sZeroRecords":    "Aucun élément à afficher",
                        "sEmptyTable":     "Aucune donnée disponible dans le tableau",
                        "oPaginate": {
                            "sFirst":      "Premier",
                            "sPrevious":   "Précédent",
                            "sNext":       "Suivant",
                            "sLast":       "Dernier"
                        },
                        "oAria": {
                            "sSortAscending":  ": activer pour trier la colonne par ordre croissant",
                            "sSortDescending": ": activer pour trier la colonne par ordre décroissant"
                        }
                    }
                }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

                $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "order": [[1, 'desc']],  // Trier la deuxième colonne (index 1) qui est la date
                "columnDefs": [
                {
                    "targets": 1,  // Index de la colonne de date
                    "orderDataType": "dom-text",  // Utilisation de "dom-text" pour trier les données
                    "render": function(data, type, row) {
                        // Utiliser l'attribut data-date pour le tri
                        return row[1];  // retourne la date dans le format d'affichage
                    }
                }
            ],
                "responsive": true,
                "language": {
                    "sProcessing":     "Traitement en cours...",
                    "sSearch":         "Rechercher :",
                    "sLengthMenu":     "Afficher _MENU_ éléments",
                    "sInfo":           "Affichage de l'élément _START_ à _END_ sur _TOTAL_ éléments",
                    "sInfoEmpty":      "Affichage de l'élément 0 à 0 sur 0 élément",
                    "sInfoFiltered":   "(filtré à partir de _MAX_ éléments au total)",
                    "sInfoPostFix":    "",
                    "sLoadingRecords": "Chargement en cours...",
                    "sZeroRecords":    "Aucun élément à afficher",
                    "sEmptyTable":     "Aucune donnée disponible dans le tableau",
                    "oPaginate": {
                        "sFirst":      "Premier",
                        "sPrevious":   "Précédent",
                        "sNext":       "Suivant",
                        "sLast":       "Dernier"
                    },
                    "oAria": {
                        "sSortAscending":  ": activer pour trier la colonne par ordre croissant",
                        "sSortDescending": ": activer pour trier la colonne par ordre décroissant"
                    }
                }
            });
        
            });
        </script>
        
        <!-- Page specific script -->
        <script>
            $(function () {
            //Initialize Select2 Elements
            $('.select2').select2()

            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })

            //Datemask dd/mm/yyyy
            $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
            //Datemask2 mm/dd/yyyy
            $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
            //Money Euro
            $('[data-mask]').inputmask()

            //Date picker
            $('#reservationdate').datetimepicker({
                format: 'L'
            });

            //Date and time picker
            $('#reservationdatetime').datetimepicker({ icons: { time: 'far fa-clock' } });

            //Date range picker
            $('#reservation').daterangepicker()
            //Date range picker with time picker
            $('#reservationtime').daterangepicker({
                timePicker: true,
                timePickerIncrement: 30,
                locale: {
                format: 'MM/DD/YYYY hh:mm A'
                }
            })
            //Date range as a button
            $('#daterange-btn').daterangepicker(
                {
                ranges   : {
                    'Today'       : [moment(), moment()],
                    'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month'  : [moment().startOf('month'), moment().endOf('month')],
                    'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                startDate: moment().subtract(29, 'days'),
                endDate  : moment()
                },
                function (start, end) {
                $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
                }
            )

            //Timepicker
            $('#timepicker').datetimepicker({
                format: 'LT'
            })

            //Bootstrap Duallistbox
            $('.duallistbox').bootstrapDualListbox()

            //Colorpicker
            $('.my-colorpicker1').colorpicker()
            //color picker with addon
            $('.my-colorpicker2').colorpicker()

            $('.my-colorpicker2').on('colorpickerChange', function(event) {
                $('.my-colorpicker2 .fa-square').css('color', event.color.toString());
            })

            $("input[data-bootstrap-switch]").each(function(){
                $(this).bootstrapSwitch('state', $(this).prop('checked'));
            })

            })
            // BS-Stepper Init
            document.addEventListener('DOMContentLoaded', function () {
            window.stepper = new Stepper(document.querySelector('.bs-stepper'))
            })

            // DropzoneJS Demo Code Start
            // Dropzone.autoDiscover = false

                // Get the template HTML and remove it from the doumenthe template HTML and remove it from the doument
                var previewNode = document.querySelector("#template")
            // previewNode.id = ""
            var previewTemplate = previewNode.parentNode.innerHTML
            previewNode.parentNode.removeChild(previewNode)

            var myDropzone = new Dropzone(document.body, { // Make the whole body a dropzone
            url: "/target-url", // Set the url
            thumbnailWidth: 80,
            thumbnailHeight: 80,
            parallelUploads: 20,
            previewTemplate: previewTemplate,
            autoQueue: false, // Make sure the files aren't queued until manually added
            previewsContainer: "#previews", // Define the container to display the previews
            clickable: ".fileinput-button" // Define the element that should be used as click trigger to select files.
            })

            myDropzone.on("addedfile", function(file) {
            // Hookup the start button
            file.previewElement.querySelector(".start").onclick = function() { myDropzone.enqueueFile(file) }
            })

            // Update the total progress bar
            myDropzone.on("totaluploadprogress", function(progress) {
            document.querySelector("#total-progress .progress-bar").style.width = progress + "%"
            })

            myDropzone.on("sending", function(file) {
            // Show the total progress bar when upload starts
            document.querySelector("#total-progress").style.opacity = "1"
            // And disable the start button
            file.previewElement.querySelector(".start").setAttribute("disabled", "disabled")
            })

            // Hide the total progress bar when nothing's uploading anymore
            myDropzone.on("queuecomplete", function(progress) {
            document.querySelector("#total-progress").style.opacity = "0"
            })

            // Setup the buttons for all transfers
            // The "add files" button doesn't need to be setup because the config
            // `clickable` has already been specified.
            document.querySelector("#actions .start").onclick = function() {
            myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED))
            }
            document.querySelector("#actions .cancel").onclick = function() {
            myDropzone.removeAllFiles(true)
            }
            // DropzoneJS Demo Code End
        </script>

        @if (Session::has('success_message') || Session::has('error_message'))
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    var type = "{{ Session::has('success_message') ? 'success' : 'error' }}";
                    var message = `{!! addslashes(Session::get('success_message') ?? Session::get('error_message')) !!}`;
                    var icon = type === "success" ? "✔️" : "❌";
                    var title = type === "success" ? "Succès" : "Erreur";

                    Swal.fire({
                        icon: type,
                        title: title,
                        text: message,
                        confirmButtonColor: type === "success" ? "#4CAF50" : "#FF5733",
                        background: "#fff",
                        color: "#333",
                        timer: 5000,
                        timerProgressBar: true,
                        showConfirmButton: true,
                        customClass: {
                            popup: "swal-popup-custom",
                            title: "swal-title-custom",
                            content: "swal-content-custom",
                        },
                        didOpen: (toast) => {
                            toast.style.padding = '20px'; // Espacement supplémentaire
                        }
                    });
                });
            </script>

            <style>
                .swal-popup-custom {
                    font-size: 16px;
                    padding: 25px;
                    border-radius: 12px;
                    box-shadow: 0 6px 30px rgba(0, 0, 0, 0.1);
                    width: 400px;
                    height: auto;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                }

                .swal-title-custom {
                    font-size: 20px;
                    font-weight: bold;
                    color: #333;
                }

                .swal-content-custom {
                    font-size: 16px;
                    color: #666;
                    margin-top: 10px;
                    text-align: center;
                }
            </style>
        @endif

        <!-- Loader plein écran -->
        <div id="pageLoader" class="page-loader d-none">
            <div class="spinner-border text-primary" role="status" style="width:4rem;height:4rem;">
                <span class="visually-hidden"></span>
            </div>
        </div>

    </body>
</html>
