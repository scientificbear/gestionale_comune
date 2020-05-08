        <aside class="left-sidebar" data-sidebarbg="skin6">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar" data-sidebarbg="skin6">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <li class="sidebar-item"> <a class="sidebar-link sidebar-link" href="../home/index.php"
                                aria-expanded="false"><i data-feather="home" class="feather-icon"></i><span
                                    class="hide-menu">Home</span></a></li>
                                    <li class="list-divider"></li>

                        <li class="nav-small-cap"><span class="hide-menu">Segnalazione interventi</span></li>

                        <li class="sidebar-item">
                            <a class="sidebar-link" href="../interventi_immobili/index.php"aria-expanded="false">
                                <i class="far fa-edit"></i>
                                <span class="hide-menu">Interventi</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link sidebar-link" href="../ditte/index.php" aria-expanded="false">
                                <i class="far fa-address-book"></i>
                                <span class="hide-menu">Ditte</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link sidebar-link" href="../immobili/index.php" aria-expanded="false">
                                <i class="far fa-building"></i>
                                <span class="hide-menu">Immobili</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link has-arrow" href="javascript:void(0)" aria-expanded="false">
                                <i class="far fa-window-maximize"></i>
                                <span class="hide-menu">Tipologie</span>
                            </a>
                            <ul aria-expanded="false" class="collapse  first-level base-level-line">
                                <li class="sidebar-item">
                                    <a href="../categoria_ditte/index.php" class="sidebar-link">
                                        <span class="hide-menu"> Ditte</span>
                                    </a>
                                </li>
                                <li class="sidebar-item">
                                    <a href="../tipo_immobili/index.php" class="sidebar-link">
                                        <span class="hide-menu"> Immobili</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="list-divider"></li>

                        <?php require_once "../general/utils.php";
                                protect_content('
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="../utenti/index.php" aria-expanded="false">
                                <i class="far fa-user"></i>
                                <span class="hide-menu">Utenti</span>
                            </a>
                        </li>
                        ',
                        $_SESSION["role"], array("admin"));
                        ?>
                        
                        <li class="sidebar-item">
                            <a class="sidebar-link sidebar-link" href="../general/logout.php" aria-expanded="false">
                                <i data-feather="log-out" class="feather-icon"></i>
                                <span class="hide-menu">Logout</span>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
