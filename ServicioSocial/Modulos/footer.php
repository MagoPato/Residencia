<div id="layoutAuthentication_footer">
    <footer class="py-4 bg-light mt-auto">
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center small">
                <div class="text-muted">
                    Derechos de autor &copy; TecNL <span id="currentYear"></span>
                </div>
                
<!--recordar quitar el  style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; width: 100px; cursor: pointer;" 
     onclick="toggleText()" -->
                <div id="credits" class="text-muted" 
    >
    Residencia Profesional | Instituto Tecnológico
</div>
            </div>
        </div>
    </footer>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var currentYear = new Date().getFullYear();
        document.getElementById("currentYear").textContent = currentYear;
    });
    
</script>