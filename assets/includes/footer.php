<?php
/* footer.php
    * This file contains the footer section of the website.
    * It includes links to the home page, recipes, upload page, and legal pages.
    * This is used across all pages of the site.
    * To use this: include this file at the end of your PHP document to display the footer.
 */
 ?>
 <footer>
     <div>
         <div class="footer-logo-container">
             <div class="footer-logo">
                 <img src="assets/img/logo.svg" alt="Recipe Cloud Logo" class="logo" width="24" height="24">
                 <h1>Recipe Cloud</h1>
             </div>
             <p>Share and discover delicious recipes from around the world.</p>
             <div class="footer-heart">
                <p>Made with</p>
                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="red" stroke="red" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"></path>
                </svg>
             </div>
         </div>
         <div class="footer-links-container">
             <h2>Quick Links</h2>
             <div>
                 <a href="/">Home</a>
                 <a href="recipes">Recipes</a>
                 <a href="upload">Upload</a>
             </div>
         </div>
         <div class="footer-links-container">
             <h2>Legal (add if site goes live)</h2> <!-- This section would be legally required if the site goes live. For this example, it's not implemented. -->
             <div>
                 <a href="https://mtzel.com/imprint">Imprint</a>
                 <a href="/">Privacy Policy and ToS</a>
                 <a href="/">Cookie Preferences</a>
             </div>
         </div>
         <div class="footer-links-container">
             <h2>Contact</h2>
             <div>
                 <a href="https://github.com/Edamame04/recipe_cloud" target="_blank">GitHub</a>
                 <a href="https://github.com/Edamame04/recipe_cloud/issues/new?template=bug_report.md" target="_blank">Report a Bug</a> 
                 <a href="https://github.com/Edamame04/recipe_cloud/issues/new?template=feature_request.md" target="_blank">Request a Feature</a>
             </div>
         </div>
     </div>
 </footer>
 </body>

 </html>
