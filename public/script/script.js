// Sidebar toggle for desktop
        document.getElementById('sidebarLogo').addEventListener('click', function() {
            if(window.innerWidth >= 768) {
                document.getElementById('sidebar').classList.toggle('collapsed');
                document.getElementById('mainContent').classList.toggle('expanded');
            }
        });
        // Mobile offcanvas menu
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const offcanvasSidebar = document.getElementById('offcanvasSidebar');
        const closeOffcanvasBtn = document.getElementById('closeOffcanvasBtn');
        function showOffcanvas() {
            offcanvasSidebar.classList.add('show');
            document.body.style.overflow = 'hidden';
        }
        function hideOffcanvas() {
            offcanvasSidebar.classList.remove('show');
            document.body.style.overflow = '';
        }
        mobileMenuBtn.addEventListener('click', showOffcanvas);
        closeOffcanvasBtn.addEventListener('click', hideOffcanvas);
        // Show hamburger only on mobile
        function handleMobileMenuBtn() {
            if(window.innerWidth < 768) {
                mobileMenuBtn.style.display = 'inline-flex';
                hideOffcanvas();
            } else {
                mobileMenuBtn.style.display = 'none';
                hideOffcanvas();
            }
        }
        window.addEventListener('resize', handleMobileMenuBtn);
        handleMobileMenuBtn();
       