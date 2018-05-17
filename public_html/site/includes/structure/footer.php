<?php
    if (basename($_SERVER['SCRIPT_FILENAME']) == 'xforms_form.php' && defined('XFORMS_PROFESSIONAL_VERSION') === true) {
        $poweredByLink = '<a href="//www.jadu.net/xfp">Jadu XFP Online Forms</a>';
    }
    else {
        $poweredByLink = '<a href="//www.jadu.net">Jadu Content Management</a>';
    }
?>
<!-- googleoff: index -->
        <footer class="footer">
            <div class="row twelve-hun-max">
                <div class="small-12 columns no-lr-pad-large">
                    <div class="small-12 large-9 column no-l-pad">
                        <div class="small-12 large-5 column no-lr-pad logo"><a href="/" title=""><img src="/site/styles/img/logo-white.png" title="" alt="Fordham University"></a> </div>
                        <div class="small-12 large-8 column slogan"><span>New York is my campus. Fordham is my school.<sup>TM</sup></span></div>
                        <ul class="social inline no-list-style small-12 column no-l-pad">
				<li><a href="/socialmedia" title="Twitter"><em class="fa fa-twitter"></em><span class="hidden">Twitter</span></a></li>
                                <li><a href="/socialmedia" title="Facebook"><em class="fa fa-facebook"></em><span class="hidden">Facebook</span></a></li>
                                <li><a href="/socialmedia" title="Linkedin"><em class="fa fa-linkedin"></em><span class="hidden">Linkedin</span></a></li>
                                <li><a href="/socialmedia" title="Youtube"><em class="fa fa-youtube"></em><span class="hidden">Youtube</span></a></li>
                                <li><a href="/socialmedia" title="Instagram"><em class="fa fa-instagram"></em><span class="hidden">Instagram</span></a></li>
                        </ul>
                    </div>
                    <div class="small-12 medium-12 large-3 max-250 column list-links">
                        <a href="/contact_us" title="Contact Fordham University">Contact Us</a>
                        <a href="/maps_and_directions" title="Fordham Maps and Directions">Maps and Directions</a>
                        <a href="/info/22823/discrimination" title="Fordham Nondiscrimination Policy">Nondiscrimination Policy</a>
                    </div>
                </div>

            </div>
            <div class="copyright show-for-small">&copy; <?php print date('Y'); ?> Fordham University</div>
           <!-- <a href="#top" class="back-to-top"><em class="fa fa-angle-up"></em></a> -->
        </footer>
<!-- googleon: index -->
