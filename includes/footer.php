<?php
// includes/footer.php
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../config/config.php';
}
$base = BASE_URL;
?>

<!-- ===== FOOTER ===== -->
<footer class="footer">
    <div class="container">
        <div class="footer-grid">

            <div class="footer-brand">
                <a href="<?= $base ?>/" class="nav-brand">
                    <?php if (file_exists(__DIR__ . '/../assets/img/logob.png')): ?>
                        <img src="<?= $base ?>/assets/img/logob.png" alt="Logo EMOVTT" class="nav-logo">
                    <?php else: ?>
                        <div class="logo-box">EMOVTT</div>
                    <?php endif; ?>
                    <div class="brand-text">
                        <strong>Empresa de Movilidad</strong>
                        Santa Rosa &ndash; EP
                    </div>
                </a>

                <div class="footer-social">
                    <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" aria-label="Twitter/X"><i class="fab fa-x-twitter"></i></a>
                    <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                </div>
            </div>

            <div class="footer-col">
                <h4>Institucion</h4>
                <ul>
                    <li><a href="<?= $base ?>/nosotros">Quienes Somos?</a></li>
                    <li><a href="<?= $base ?>/directorio">Directorio Institucional</a></li>
                    <li><a href="<?= $base ?>/transparencia">Transparencia LOTAIP</a></li>
                    <li><a href="<?= $base ?>/trabaja">Trabaja con Nosotros</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Servicios Rapidos</h4>
                <ul>
                    <li><a href="<?= $base ?>/agendar-turno">Turnos Matriculacion</a></li>
                    <li><a href="<?= $base ?>/multas">Consulta de Multas</a></li>
                    <li><a href="<?= $base ?>/titulos">Titulos Habilitantes</a></li>
                    <li><a href="<?= $base ?>/pagos">Pagos en Linea</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Contacto</h4>
                <ul class="footer-col-contacto">
                    <li><i class="fas fa-map-marker-alt"></i><span>Av. Joffre Lima y calle Sucre, Santa Rosa, El Oro.</span></li>
                    <li><i class="fas fa-envelope"></i><span>info@emovtt-sr.gob.ec</span></li>
                    <li><i class="fas fa-phone"></i><span>(07) 2123-456</span></li>
                </ul>
            </div>

        </div>

        <div class="footer-bottom">
            <span>&copy; <?= date('Y') ?> EMOVTT Santa Rosa - EP. Todos los derechos reservados.</span>
            <div class="footer-bottom-links">
                <a href="<?= $base ?>/privacidad">Privacidad</a>
                <a href="<?= $base ?>/terminos">Terminos</a>
                <a href="<?= $base ?>/mapa-sitio">Mapa del Sitio</a>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- JS propio -->
<script src="<?= $base ?>/assets/js/main.js"></script>
</body>

</html>