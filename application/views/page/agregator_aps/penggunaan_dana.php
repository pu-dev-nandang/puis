<h3>This is the page : penggunaan_dana.php</h3><br/>Prodi : <span id="viewProdiID"></span> | <span id="viewProdiName"></span>
                    
                    
                    <script>
                $(document).ready(function () {
                    var firstLoad = setInterval(function () {
                        var filterProdi = $('#filterProdi').val();
                        if(filterProdi!='' && filterProdi!=null){
                            loadPage();
                            clearInterval(firstLoad);
                        }
                    },1000);
                    setTimeout(function () {
                        clearInterval(firstLoad);
                    },5000);
            
                });
                $('#filterProdi').change(function () {
                    var filterProdi = $('#filterProdi').val();
                    if(filterProdi!='' && filterProdi!=null){
                        loadPage();
                    }
                });
                function loadPage() {
                    var filterProdi = $('#filterProdi').val();
                    if(filterProdi!='' && filterProdi!=null){
                        $('#viewProdiID').html(filterProdi);
                        $('#viewProdiName').html($('#filterProdi option:selected').text());
                    }
                }
            </script>