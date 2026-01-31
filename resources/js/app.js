import 'flowbite';
import { initFlowbite } from 'flowbite';

// Dark mode initialization - run immediately
(function() {
    const theme = localStorage.getItem('theme');
    if (theme === 'dark' || (!theme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
})();

// Initialize Flowbite components
document.addEventListener('DOMContentLoaded', function () {
    initFlowbite();
    
    // Sidebar toggle logic
    const toggleBtn = document.getElementById('sidebar-toggle-btn');
    const sidebar = document.getElementById('logo-sidebar');
    const mainContent = document.getElementById('main-content');

    if (toggleBtn && sidebar && mainContent) {
        toggleBtn.addEventListener('click', () => {
            if (window.innerWidth >= 640) {
                // Desktop: toggle sidebar
                sidebar.classList.toggle('-translate-x-full');
                sidebar.classList.toggle('sm:translate-x-0');
                mainContent.classList.toggle('sm:ml-64');
            } else {
                // Mobile: drawer behavior
                sidebar.classList.toggle('-translate-x-full');
            }
        });
    }
});

// Re-initialize Flowbite after Livewire updates
document.addEventListener('livewire:navigated', () => {
    initFlowbite();
});