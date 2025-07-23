<?php
require '../includes/session.php';
require '../includes/header.php';
require '../includes/sidebar.php';
?>

<!-- Main content wrapper -->
<div class="container-fluid" style="margin-left: 250px; max-width: calc(100% - 250px); min-height: 100vh; padding: 2rem;">
    
    <!-- Background and content container -->
    <div class="d-flex justify-content-center align-items-center flex-column" 
         style="min-height: 100vh; 
                background-image: url('../assets/images/received_1723703311692796.jpeg');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                position: relative;
                margin: -2rem;">
        
        <!-- Overlay for better text readability -->
        <div style="position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background-color: rgba(0, 0, 0, 0.5);">
        </div>

        <!-- Welcome text -->
        <div class="text-center" style="position: relative; z-index: 1;">
            <h1 class="fw-bold text-white" 
                style="font-size: 4rem; 
                       text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
                       font-family: 'Arial Black', Helvetica, sans-serif;
                       letter-spacing: 2px;
                       text-transform: uppercase;">
                Welcome to MANFAS
            </h1>
        </div>
    </div>
</div>
