<?PHP
include('./resource/head.php');
?>

<body>
    <div class="container-fluid">
        <div class="row">
            <main class="position-absolute h-100 d-flex flex-column justify-content-center align-items-center">
                <header>
                    <h1 class="display-1 fs-1"><?PHP echo($_SESSION['config']->getTitle()); ?></h1>
                </header>
                <section class="d-flex flex-column col-sm-9 col-md-9 col-lg-6 text-center max-width-inner">
                    <span class="display-6 text-muted fs-6 mb-4"><?PHP echo($_SESSION['config']->getSubTitle()); ?></span>

                    <form id="login_form" method="post" name="form" action="../index.php" autocomplete="off" onSubmit="submitForm();">
                        <input class="w-100 mb-2 mt-2" id="user_name" name="u" type="text" placeholder="User" required="required">
                        <input class="w-100 mt-2" id="user_pass" type="password" name="p" placeholder="Password" required="required">
                        <div class="col-12 d-flex justify-content-center">
                            <button type="submit" id="btn_enter" class="mt-4">Enter</button>
                        </div>
                    </form>
                </section>
            </main>
        </div>
        <!-- END ROW -->
    </div>
    <!-- END CONTAINER FLUID -->

    <!-- TOAST -->        
    <div class="position-absolute d-flex justify-content-center align-items-center h-100" style="z-index: 10000">
        <div id="toast" class="toast align-items-center text-white bg-primary border-0 p-3" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body fs-5 text-center"></div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
    <!-- END OF TOAST -->
    
    <!-- SCRIPTS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="../js/sha256.js"></script>
    <script src="../js/functions.js"></script>
</body>
</html>