@extends('layouts.app')
@section('title', 'Riwayat Pesan Dosen')
@push('styles')
    <style>
        :root {
            --bs-primary: #1a73e8;
            --bs-danger: #FF5252;
            --bs-success: #27AE60;
            --primary-gradient: linear-gradient(to right, #004AAD, #5DE0E6);
            --primary-hover: linear-gradient(to right, #003c8a, #4bc4c9);
            --text-color: #546E7A;
        }
        
        body {
            background-color: #F5F7FA;
            font-size: 13px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            margin: 0;
            padding: 0;
        }

        .main-content {
            flex: 1;
            padding-top: 20px; 
            padding-bottom: 20px; 
        }
        
        .btn-custom-primary {
            background: var(--bs-primary);
            color: white;
        }
        
        .btn-custom-primary:hover {
            background: #1557b0;
            color: white;
        }

        /* Mobile Navigation Bar */
        .mobile-navbar {
            display: none;
            background: var(--primary-gradient);
            color: white;
            padding: 12px 15px;
            position: sticky;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1025;
            box-shadow: 0 2px 15px rgba(0,0,0,0.15);
            border-radius: 0;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            margin-top: 0;
        }

        /* Enhanced mobile navbar with scroll effect */
        .mobile-navbar.scrolled {
            background: rgba(0, 74, 173, 0.95);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.25);
            position: fixed;
            top: 0;
            z-index: 1030;
        }

        /* Add padding compensation when navbar becomes fixed */
        .mobile-navbar-compensation {
            height: 70px;
            display: none;
        }

        .mobile-navbar-compensation.active {
            display: block;
        }

        .mobile-navbar .navbar-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .mobile-navbar .page-info h6 {
            margin: 0;
            font-size: 1rem;
            font-weight: 600;
        }

        .mobile-navbar .page-info small {
            font-size: 0.75rem;
            opacity: 0.9;
        }

        .mobile-navbar .navbar-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .burger-menu {
            background: none;
            border: none;
            color: white;
            font-size: 1.2rem;
            padding: 8px;
            border-radius: 5px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .burger-menu:hover {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .burger-menu:focus {
            outline: none;
            box-shadow: none;
        }

        /* Mobile Sidebar Overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1040;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-overlay.show {
            opacity: 1;
        }

        .mobile-sidebar {
            position: fixed;
            top: 0;
            left: -100%;
            width: 280px;
            height: 100%;
            background: white;
            z-index: 1050;
            transition: left 0.3s ease;
            overflow-y: auto;
            box-shadow: 2px 0 15px rgba(0,0,0,0.1);
        }

        .mobile-sidebar.show {
            left: 0;
        }

        .mobile-sidebar-header {
            background: var(--primary-gradient);
            color: white;
            padding: 20px 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .mobile-sidebar-header h6 {
            margin: 0;
            font-weight: 600;
        }

        .close-sidebar {
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            padding: 5px;
            cursor: pointer;
            border-radius: 3px;
            transition: all 0.2s ease;
        }

        .close-sidebar:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .close-sidebar:focus {
            outline: none;
        }

        .sidebar {
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 20px; 
            max-height: calc(100vh - 100px);
        }

        .sidebar-buttons {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }

        .sidebar-buttons .btn {
            width: 100%;
            margin-bottom: 10px;
            padding: 10px 15px;
            font-size: 14px;
        }

        .sidebar-menu {
            padding: 15px;
        }
        
        .sidebar-menu .nav-link {
            color: #546E7A;
            border-radius: 0.5rem;
            margin-bottom: 8px;
            padding: 10px 15px;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            width: 100%;
            text-align: left;
        }
        
        .sidebar-menu .nav-link.active {
            background: #E3F2FD;
            color: var(--bs-primary);
        }
        
        .sidebar-menu .nav-link:hover:not(.active) {
            background: #f8f9fa;
        }

        .komunikasi-submenu .nav-link.active,
        .pengaturan-submenu .nav-link.active {
            background: #E3F2FD;
            color: var(--bs-primary);
        }

        .komunikasi-submenu .nav-link:hover:not(.active),
        .pengaturan-submenu .nav-link:hover:not(.active) {
            background: #f8f9fa;
        }

        .badge-notification {
            background: var(--bs-danger);
        }

        .search-filter-card {
            position: sticky;
            top: 76px;
            z-index: 100;
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .message-container {
            max-height: calc(100vh - 200px);
            overflow-y: auto;
            padding-right: 10px; 
        }

        .message-container::-webkit-scrollbar {
            width: 6px;
        }

        .message-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .message-container::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }
        
        .message-card.penting {
            border-left: 4px solid var(--bs-danger);
        }

        .message-container::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        
        .message-card.umum {
            border-left: 4px solid var(--bs-success);
        }
        
        .content-cards {
            margin-top: 0;
        }
        
        .custom-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 15px;
        }
        
        .card {
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        
        .message-list .card {
            margin-bottom: 15px;
        }

        .komunikasi-submenu,
        .pengaturan-submenu {
            margin-left: 15px;
            width: 100%;
        }

        .komunikasi-submenu .nav-link,
        .pengaturan-submenu .nav-link {
            padding: 8px 15px;
            font-size: 13px;
            width: 100%;
            color: #546E7A;
        }

        .profile-image {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #f8f9fa;
        }

        .profile-image-placeholder {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background-color: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            font-size: 20px;
            border: 2px solid #f8f9fa;
        }
        
        .bookmark-icon {
            color: #ffc107;
            cursor: pointer;
        }
        
        .bookmark-icon.active {
            color: #ffc107;
        }
        
        .bookmark-icon:not(.active) {
            color: #dee2e6;
        }
        
        /* Style baru untuk message card clickable */
        .message-card {
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .message-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        /* Memastikan tombol-tombol tetap berfungsi dan tidak mengganggu card clickable */
        .message-card .btn, 
        .message-card .bookmark-icon {
            position: relative;
            z-index: 10;
        }
        
        .action-buttons {
            position: relative;
            z-index: 10;
        }
        
        /* Hide pesan yang tidak sesuai dengan filter */
        .message-card.hidden {
            display: none;
        }
        
        /* Pesan not found */
        .no-messages-found {
            text-align: center;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            display: none;
        }
        
        /* Style untuk tombol filter */
        /* Filter Penting */
        .btn-outline-danger.btn-filter {
            color: #FF5252;
            border-color: #FF5252;
            transition: all 0.2s ease;
        }
        
        .btn-outline-danger.btn-filter:hover,
        .btn-outline-danger.btn-filter.active-filter {
            background-color: #FF5252;
            color: white;
            border-color: #FF5252;
        }
        
        /* Filter Umum */
        .btn-outline-success.btn-filter {
            color: #27AE60;
            border-color: #27AE60;
            transition: all 0.2s ease;
        }
        
        .btn-outline-success.btn-filter:hover,
        .btn-outline-success.btn-filter.active-filter {
            background-color: #27AE60;
            color: white;
            border-color: #27AE60;
        }
        
        /* Filter Semua */
        .btn-outline-primary.btn-filter {
            color: #1a73e8;
            border-color: #1a73e8;
            transition: all 0.2s ease;
        }
        
        .btn-outline-primary.btn-filter:hover,
        .btn-outline-primary.btn-filter.active-filter {
            background-color: #1a73e8;
            color: white;
            border-color: #1a73e8;
        }
        
        /* Style untuk submenu mode peran */
        .pengaturan-submenu .btn-link {
            padding: 8px 15px;
            display: flex;
            align-items: center;
            text-decoration: none;
            border: none;
            background: none;
            font-size: 13px;
            width: 100%;
            text-align: left;
            margin: 0;
            border-radius: 0.5rem;
            color: #546E7A;
        }
        
        .pengaturan-submenu .btn-link:hover {
            background-color: #f8f9fa;
            color: #546E7A;
        }
        
        /* Style untuk mode switcher */
        .mode-switcher-container {
            background: linear-gradient(to right, #0858b2, #53d1e0);
            color: white;
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .mode-switcher-container.kaprodi {
            background: linear-gradient(to right, #53d1e0, #0858b2);
        }
        
        .mode-title {
            font-size: 16px;
            font-weight: 600;
            margin: 0;
        }
        
        .switch {
            position: relative;
            display: inline-block;
            width: 56px;
            height: 28px;
        }
        
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #f0f2f0;
            transition: .4s;
            border-radius: 34px;
        }
        
        /* Style untuk mode Dosen (default) - bulat di kiri */
        .slider:before {
            position: absolute;
            content: "";
            height: 20px;
            width: 20px;
            left: 4px;
            bottom: 4px;
            background-color: #0858b2;
            transition: .4s;
            border-radius: 50%;
        }
        
        /* Style untuk mode Kaprodi - bulat di kanan (dengan !important) */
        .mode-switcher-container.kaprodi .slider:before {
            left: auto !important;
            right: 4px !important;
            background-color: #0cc0df !important;
        }
        
        /* Animasi loading spinner */
        .role-loading-spinner {
            width: 40px;
            height: 40px;
            margin: 0 auto 15px;
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-left-color: var(--bs-primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        /* Modal Role Switcher */
        .role-modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1050;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .role-modal-backdrop.show {
            opacity: 1;
            visibility: visible;
        }
        
        .role-modal {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 400px;
            transform: scale(0.8);
            transition: transform 0.3s ease;
            overflow: hidden;
            text-align: center;
        }
        
        .role-modal-backdrop.show .role-modal {
            transform: scale(1);
        }

        /* Mobile Responsive Styles - ENHANCED */
        @media (max-width: 991.98px) {
            body {
                padding-top: 0;
            }
            
            .row.g-4 {
                --bs-gutter-x: 0;
            }
            
            .col-md-3 {
                display: none;
            }
            
            .col-md-9 {
                padding-left: 0;
                padding-right: 0;
                flex: 0 0 100%;
                max-width: 100%;
            }
            
            .mobile-navbar {
                display: block;
            }
            
            .main-content {
                padding-top: 15px;
            }

            .mode-switcher-container {
                margin: 0 10px 15px;
                padding: 12px 15px;
                border-radius: 8px;
            }

            .mode-title {
                font-size: 14px;
            }

            .switch {
                width: 48px;
                height: 24px;
            }

            .slider:before {
                height: 16px;
                width: 16px;
                left: 3px;
                bottom: 4px;
            }

            .mode-switcher-container.kaprodi .slider:before {
                right: 3px !important;
            }

            .search-filter-card {
                position: static;
                top: auto;
                margin: 0 10px 15px;
                border-radius: 8px;
            }

            .search-filter-card .card-body {
                padding: 15px;
            }

            .btn-group {
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
                justify-content: center;
            }

            .btn-filter {
                flex: 1;
                min-width: 80px;
                margin: 0 !important;
                padding: 8px 12px !important;
                font-size: 12px !important;
            }

            .messages-wrapper {
                padding: 0 10px;
            }

            .message-card {
                margin-bottom: 12px !important;
                border-radius: 8px;
            }

            .message-card .card-body {
                padding: 15px;
            }

            .profile-image,
            .profile-image-placeholder {
                width: 40px;
                height: 40px;
            }

            .profile-image-placeholder {
                font-size: 16px;
            }

            /* Mobile card layout adjustments */
            .message-card .row {
                --bs-gutter-x: 0.5rem;
                flex-direction: column;
                gap: 10px;
            }

            .message-card .col-md-8,
            .message-card .col-md-4 {
                flex: none;
                width: 100%;
                max-width: 100%;
            }

            .message-card .col-md-8 {
                margin-bottom: 0;
            }

            .message-card .col-md-4 {
                text-align: left !important;
                margin-top: 0 !important;
            }

            .action-buttons {
                justify-content: flex-start !important;
                align-items: flex-start;
                margin-top: 10px;
                display: block;
            }

            .message-container {
                max-height: none;
                overflow: visible;
                padding-right: 0;
            }
        }

        /* Tablet Responsive Styles */
        @media (max-width: 768px) {
            body {
                padding-top: 0;
            }
            
            .custom-container {
                padding: 0 10px;
            }
            
            .main-content {
                padding-top: 10px;
                padding-bottom: 15px;
            }
            
            .mobile-navbar {
                padding: 10px 15px;
            }
            
            .mobile-navbar .navbar-content {
                flex-direction: row;
                align-items: center;
            }
            
            .mobile-navbar .page-info h6 {
                font-size: 0.95rem;
            }
            
            .mobile-navbar .page-info small {
                font-size: 0.7rem;
            }

            .mode-switcher-container {
                margin: 0 5px 15px;
                padding: 10px 15px;
            }

            .search-filter-card {
                margin: 0 5px 15px;
            }

            .search-filter-card .row {
                flex-direction: column;
                gap: 15px;
            }

            .btn-group {
                justify-content: center;
                gap: 6px;
            }

            .btn-filter {
                padding: 6px 15px !important;
                font-size: 12px !important;
            }

            .messages-wrapper {
                padding: 0 5px;
            }

            .message-card .card-body {
                padding: 12px;
            }

            .profile-image,
            .profile-image-placeholder {
                width: 35px;
                height: 35px;
            }

            .profile-image-placeholder {
                font-size: 14px;
            }

            .badge {
                font-size: 10px;
                padding: 3px 8px;
            }
        }

        /* Mobile Phone Responsive Styles */
        @media (max-width: 576px) {
            body {
                font-size: 12px;
                padding-top: 0;
            }
            
            .custom-container {
                padding: 0 8px;
            }
            
            .main-content {
                padding-top: 8px;
                padding-bottom: 10px;
            }
            
            .mobile-navbar {
                padding: 8px 12px;
            }
            
            .mobile-navbar .page-info h6 {
                font-size: 0.9rem;
                margin-bottom: 2px;
                line-height: 1.2;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
                max-width: 200px;
            }
            
            .mobile-navbar .page-info small {
                font-size: 0.65rem;
                opacity: 0.9;
            }
            
            .mobile-navbar .navbar-actions {
                gap: 5px;
            }
            
            .burger-menu {
                font-size: 1rem;
                padding: 6px;
            }
            
            .mobile-sidebar {
                width: 260px;
            }
            
            .mobile-sidebar-header {
                padding: 15px 12px;
            }
            
            .mobile-sidebar-header h6 {
                font-size: 0.95rem;
            }

            .mode-switcher-container {
                margin: 0 5px 12px;
                padding: 8px 12px;
                border-radius: 8px;
            }

            .mode-title {
                font-size: 13px;
            }

            .switch {
                width: 44px;
                height: 22px;
            }

            .slider:before {
                height: 14px;
                width: 14px;
                left: 3px;
                bottom: 4px;
            }

            .mode-switcher-container.kaprodi .slider:before {
                right: 3px !important;
            }

            .search-filter-card {
                margin: 0 5px 12px;
                border-radius: 8px;
            }

            .search-filter-card .card-body {
                padding: 12px;
            }

            .form-control {
                padding: 8px 12px;
                font-size: 12px;
                margin-bottom: 10px;
            }

            .btn-group {
                width: 100%;
                justify-content: center;
                gap: 4px;
            }

            .btn-filter {
                flex: 1;
                padding: 5px 8px !important;
                font-size: 10px !important;
                border-radius: 15px !important;
            }

            .messages-wrapper {
                padding: 0 5px;
            }

            .message-card {
                margin-bottom: 10px !important;
                border-radius: 8px;
            }

            .message-card .card-body {
                padding: 10px;
            }

            .d-flex.align-items-center {
                flex-direction: row !important;
                align-items: flex-start !important;
                gap: 8px;
            }

            .profile-image,
            .profile-image-placeholder {
                width: 30px;
                height: 30px;
                margin-right: 8px !important;
                flex-shrink: 0;
            }

            .profile-image-placeholder {
                font-size: 12px;
            }

            .message-card h6 {
                font-size: 12px;
                margin-bottom: 3px;
            }

            .badge {
                font-size: 9px;
                padding: 2px 6px;
                margin-right: 4px;
            }

            .text-muted {
                font-size: 10px;
            }

            .action-buttons {
                width: 100%;
                justify-content: flex-start !important;
                margin-top: 8px;
                align-items: flex-start;
            }

            .btn-custom-primary {
                font-size: 9px !important;
                padding: 4px 8px !important;
            }

            .bookmark-icon {
                font-size: 14px;
            }

            .no-messages-found {
                margin: 0 5px 15px;
                padding: 20px 15px;
            }

            .no-messages-found .fa-3x {
                font-size: 2rem !important;
                margin-bottom: 10px !important;
            }

            .no-messages-found h5 {
                font-size: 1rem;
                margin-bottom: 8px;
            }

            .no-messages-found p {
                font-size: 11px;
            }

            /* Mobile-specific layout for message cards */
            .message-card .row {
                flex-direction: column;
                gap: 10px;
            }

            .message-card .col-md-8 {
                width: 100%;
                margin-bottom: 8px;
            }

            .message-card .col-md-4 {
                width: 100%;
                text-align: left !important;
            }

            /* Compact mobile layout for profile section */
            .message-info-section {
                display: flex;
                align-items: flex-start;
                gap: 10px;
                width: 100%;
            }

            .message-text-info {
                flex: 1;
                min-width: 0;
            }

            .message-text-info h6 {
                font-size: 11px;
                line-height: 1.3;
                margin-bottom: 4px;
            }

            .message-text-info small {
                font-size: 9px;
                line-height: 1.2;
                display: block;
            }
        }

        /* Extra Small Mobile (iPhone SE, etc) */
        @media (max-width: 375px) {
            body {
                padding-top: 0;
            }
            
            .custom-container {
                padding: 0 6px;
            }
            
            .mobile-navbar {
                padding: 6px 10px;
            }
            
            .mobile-navbar .page-info h6 {
                font-size: 0.85rem !important;
                max-width: 150px;
            }
            
            .mobile-navbar .page-info small {
                font-size: 0.6rem !important;
            }
            
            .mobile-sidebar {
                width: 240px;
            }

            .mode-switcher-container {
                margin: 0 3px 10px;
                padding: 6px 10px;
            }

            .mode-title {
                font-size: 12px;
            }

            .switch {
                width: 40px;
                height: 20px;
            }

            .slider:before {
                height: 12px;
                width: 12px;
                left: 3px;
                bottom: 4px;
            }

            .mode-switcher-container.kaprodi .slider:before {
                right: 3px !important;
            }

            .search-filter-card {
                margin: 0 3px 10px;
            }

            .search-filter-card .card-body {
                padding: 10px;
            }

            .form-control {
                padding: 6px 10px;
                font-size: 11px;
            }

            .btn-filter {
                padding: 4px 6px !important;
                font-size: 9px !important;
            }

            .messages-wrapper {
                padding: 0 3px;
            }

            .message-card .card-body {
                padding: 8px;
            }

            .profile-image,
            .profile-image-placeholder {
                width: 25px;
                height: 25px;
            }

            .profile-image-placeholder {
                font-size: 10px;
            }

            .message-card h6 {
                font-size: 11px;
            }

            .badge {
                font-size: 8px;
                padding: 1px 4px;
            }

            .btn-custom-primary {
                font-size: 8px !important;
                padding: 3px 6px !important;
            }

            .message-text-info h6 {
                font-size: 10px;
            }

            .message-text-info small {
                font-size: 8px;
            }
        }

        /* Landscape orientation untuk mobile */
        @media (max-width: 768px) and (orientation: landscape) {
            body {
                padding-top: 0;
            }
            
            .mobile-navbar {
                padding: 6px 12px;
            }
            
            .main-content {
                padding-top: 6px;
                padding-bottom: 8px;
            }
            
            .mode-switcher-container {
                margin: 0 5px 10px;
                padding: 8px 12px;
            }

            .search-filter-card {
                margin: 0 5px 10px;
            }
        }

        /* High DPI / Retina Display Adjustments */
        @media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
            .message-card {
                box-shadow: 0 1px 5px rgba(0, 0, 0, 0.08);
            }
            
            .sidebar {
                box-shadow: 0 1px 5px rgba(0, 0, 0, 0.08);
            }
            
            .mobile-navbar {
                box-shadow: 0 1px 8px rgba(0, 0, 0, 0.12);
            }
        }

        /* Dark mode support (optional) */
        @media (prefers-color-scheme: dark) {
            .sidebar-overlay {
                background-color: rgba(0, 0, 0, 0.7);
            }
        }
    </style>
@endpush

@section('content')
<!-- Mobile Navigation Bar - Fixed at top -->
<div class="mobile-navbar" id="mobileNavbar">
    <div class="navbar-content">
        <div class="page-info">
            <h6>Riwayat Pesan</h6>
            <small>{{ $riwayatPesan->count() }} pesan selesai</small>
        </div>
        <div class="navbar-actions">
            <button class="burger-menu" id="mobileMenuToggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </div>
</div>

<!-- Mobile navbar compensation when it becomes fixed -->
<div class="mobile-navbar-compensation" id="mobileNavbarCompensation"></div>

<div class="main-content">
    <div class="custom-container">
        <!-- Mobile Sidebar Overlay -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Mobile Sidebar -->
        <div class="mobile-sidebar" id="mobileSidebar">
            <div class="mobile-sidebar-header">
                <h6>Menu</h6>
                <button class="close-sidebar" id="closeSidebar">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="sidebar-buttons">
                <a href="{{ route('dosen.pesan.create') }}" class="btn" style="background: var(--primary-gradient); color: white; padding: 10px 20px; border: none; border-radius: 5px;">
                    <i class="fas fa-plus me-2"></i> Pesan Baru
                </a>
            </div>
            <div class="sidebar-menu">
                <div class="nav flex-column">
                    <a href="{{ route('dosen.dashboard.pesan') }}" class="nav-link">
                        <i class="fas fa-home me-2"></i>Daftar Pesan
                    </a>
                    <a href="#" class="nav-link menu-item" id="mobileGrupDropdownToggle">
                        <div class="d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-users me-2"></i>Daftar Grup</span>
                            <i class="fas fa-chevron-down" id="mobileGrupDropdownIcon"></i>
                        </div>
                    </a>
                    <div class="collapse komunikasi-submenu" id="mobileKomunikasiSubmenu">
                        <a href="{{ route('dosen.grup.create') }}" class="nav-link menu-item d-flex align-items-center">
                            <i class="fas fa-plus me-2"></i>Grup Baru
                        </a>
                        
                        @php
                            $activeRole = session('active_role', 'dosen');
                            $grups = App\Models\Grup::where('dosen_id', Auth::user()->nip)
                                                    ->where('dosen_role', $activeRole)
                                                    ->get();
                        @endphp
                        
                        @if($grups && $grups->count() > 0)
                            @foreach($grups as $grupItem)
                            <a href="{{ route('dosen.grup.show', $grupItem->id) }}" class="nav-link menu-item d-flex justify-content-between align-items-center">
                                {{ $grupItem->nama_grup }}
                                @if($unreadCount = $grupItem->unreadMessages ?? 0)
                                <span class="badge bg-danger rounded-pill">{{ $unreadCount }}</span>
                                @endif
                            </a>
                            @endforeach
                        @else
                            <div class="nav-link menu-item text-muted">
                                <small>Belum ada grup</small>
                            </div>
                        @endif
                    </div>
                    <a href="{{ route('dosen.pesan.history') }}" class="nav-link active">
                        <i class="fas fa-history me-2"></i>Riwayat Pesan
                    </a>
                    <a href="{{ url('/faqdosen') }}" class="nav-link menu-item">
                        <i class="fas fa-thumbtack me-2"></i>Pesan Tersematkan
                    </a>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Desktop Sidebar -->
            <div class="col-md-3">
                <div class="sidebar">
                    <div class="sidebar-buttons">
                        <a href="{{ route('dosen.pesan.create') }}" class="btn" style="background: linear-gradient(to right, #004AAD, #5DE0E6); color: white; padding: 10px 20px; border: none; border-radius: 5px;">
                            <i class="fas fa-plus me-2"></i> Pesan Baru
                        </a>
                    </div>                                                    
                    
                    <div class="sidebar-menu">
                        <div class="nav flex-column">
                            <a href="{{ route('dosen.dashboard.pesan') }}" class="nav-link">
                                <i class="fas fa-home me-2"></i>Daftar Pesan
                            </a>
                            <a href="#" class="nav-link menu-item" id="grupDropdownToggle">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-users me-2"></i>Daftar Grup</span>
                                    <i class="fas fa-chevron-down" id="grupDropdownIcon"></i>
                                </div>
                            </a>
                            <div class="collapse komunikasi-submenu" id="komunikasiSubmenu">
                                <a href="{{ route('dosen.grup.create') }}" class="nav-link menu-item d-flex align-items-center">
                                    <i class="fas fa-plus me-2"></i>Grup Baru
                                </a>
                                
                                @php
                                    $activeRole = session('active_role', 'dosen');
                                    $grups = App\Models\Grup::where('dosen_id', Auth::user()->nip)
                                                            ->where('dosen_role', $activeRole)
                                                            ->get();
                                @endphp
                                
                                @if($grups && $grups->count() > 0)
                                    @foreach($grups as $grupItem)
                                    <a href="{{ route('dosen.grup.show', $grupItem->id) }}" class="nav-link menu-item d-flex justify-content-between align-items-center">
                                        {{ $grupItem->nama_grup }}
                                        @if($unreadCount = $grupItem->unreadMessages ?? 0)
                                        <span class="badge bg-danger rounded-pill">{{ $unreadCount }}</span>
                                        @endif
                                    </a>
                                    @endforeach
                                @else
                                    <div class="nav-link menu-item text-muted">
                                        <small>Belum ada grup</small>
                                    </div>
                                @endif
                            </div>
                            
                            <a href="{{ route('dosen.pesan.history') }}" class="nav-link active">
                                <i class="fas fa-history me-2"></i>Riwayat Pesan
                            </a>
                            <a href="{{ url('/faqdosen') }}" class="nav-link menu-item">
                                <i class="fas fa-thumbtack me-2"></i>Pesan Tersematkan
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9">
                <!-- Mode Switcher - Ditambahkan di halaman riwayat pesan -->
                @if(!empty(Auth::guard('dosen')->user()->jabatan_fungsional) && 
                    (stripos(Auth::guard('dosen')->user()->jabatan_fungsional, 'kaprodi') !== false || 
                     stripos(Auth::guard('dosen')->user()->jabatan_fungsional, 'ketua') !== false))
                <div class="mode-switcher-container {{ session('active_role') === 'kaprodi' ? 'kaprodi' : '' }}">
                    <h5 class="mode-title">
                        Mode {{ session('active_role') === 'kaprodi' ? 'Kaprodi' : 'Dosen' }}
                    </h5>
                    <form action="{{ route('dosen.switch-role') }}" method="POST" id="switchRoleForm">
                        @csrf
                        <label class="switch">
                            <input type="checkbox" id="roleSwitcher">
                            <span class="slider"></span>
                        </label>
                    </form>
                </div>
                @endif
                
                <!-- Search and Filters -->
                <div class="card mb-4 search-filter-card">
                    <div class="card-body">
                        <div class="row g-3 align-items-center">
                            <div class="col-md">
                                <input type="text" class="form-control" id="searchInput" placeholder="Cari Pesan..." style="font-size: 14px;">
                            </div>
                            <div class="col-md-auto">
                                <div class="btn-group">
                                    <button class="btn btn-outline-danger rounded-pill px-4 py-2 me-2 btn-filter" id="filterPenting" data-filter="penting" style="font-size: 14px;">Penting</button>
                                    <button class="btn btn-outline-success rounded-pill px-4 py-2 me-2 btn-filter" id="filterUmum" data-filter="umum" style="font-size: 14px;">Umum</button>
                                    <button class="btn btn-outline-primary rounded-pill px-4 py-2 btn-filter active-filter" id="filterSemua" data-filter="semua" style="font-size: 14px;">Semua</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Riwayat Pesan List -->
                <div class="messages-wrapper" id="messagesContainer">
                    @if($riwayatPesan->count() > 0)
                        @foreach($riwayatPesan as $pesan)
                        <div class="card mb-2 message-card {{ strtolower($pesan->prioritas) }}" data-kategori="{{ strtolower($pesan->prioritas) }}" 
                            data-pengirim="{{ $pesan->nip_pengirim == Auth::user()->nip ? ($pesan->mahasiswaPenerima->nama ?? 'Mahasiswa') : ($pesan->mahasiswaPengirim->nama ?? 'Pengirim') }}" 
                            data-judul="{{ $pesan->subjek }}" 
                            onclick="window.location.href='{{ route('dosen.pesan.show', $pesan->id) }}'">
                             <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <div class="message-info-section d-flex align-items-center">
                                            @if($pesan->nip_pengirim == Auth::user()->nip)
                                                <!-- Menampilkan foto mahasiswa penerima -->
                                                @php
                                                    $profilePhoto = $pesan->mahasiswaPenerima && $pesan->mahasiswaPenerima->profile_photo 
                                                        ? asset('storage/profile_photos/'.$pesan->mahasiswaPenerima->profile_photo) 
                                                        : null;
                                                @endphp
                                                @if($profilePhoto)
                                                    <img src="{{ $profilePhoto }}" alt="Foto Profil" class="profile-image me-3">
                                                @else
                                                    <div class="profile-image-placeholder me-3">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                @endif
                                            @else
                                                <!-- Menampilkan foto mahasiswa pengirim -->
                                                @php
                                                    $profilePhoto = $pesan->mahasiswaPengirim && $pesan->mahasiswaPengirim->profile_photo 
                                                        ? asset('storage/profile_photos/'.$pesan->mahasiswaPengirim->profile_photo) 
                                                        : null;
                                                @endphp
                                                @if($profilePhoto)
                                                    <img src="{{ $profilePhoto }}" alt="Foto Profil" class="profile-image me-3">
                                                @else
                                                    <div class="profile-image-placeholder me-3">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                @endif
                                            @endif
                                            <div class="message-text-info">
                                                <span class="badge bg-primary mb-1">{{ $pesan->subjek }}</span>
                                                
                                                @if($pesan->nip_pengirim == Auth::user()->nip)
                                                    <!-- Jika dosen adalah pengirim, tampilkan nama mahasiswa penerima -->
                                                    <h6 class="mb-1" style="font-size: 14px;">
                                                        <span class="badge bg-info me-1" style="font-size: 10px;">Kepada</span>
                                                        {{ $pesan->mahasiswaPenerima ? $pesan->mahasiswaPenerima->nama : 'Mahasiswa' }}
                                                    </h6>
                                                    <small class="text-muted">{{ $pesan->nim_penerima }}</small>
                                                @else
                                                    <!-- Jika dosen adalah penerima, tampilkan nama mahasiswa pengirim -->
                                                    <h6 class="mb-1" style="font-size: 14px;">
                                                        <span class="badge bg-info me-1" style="font-size: 10px;">Dari</span>
                                                        {{ $pesan->mahasiswaPengirim ? $pesan->mahasiswaPengirim->nama : 'Mahasiswa' }}
                                                    </h6>
                                                    <small class="text-muted">{{ $pesan->nim_pengirim }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                        <span class="badge bg-secondary me-1">Diakhiri</span>
                                        <span class="badge {{ $pesan->prioritas == 'Penting' ? 'bg-danger' : 'bg-success' }}">
                                            {{ $pesan->prioritas }}
                                        </span>
                                        
                                        <small class="d-block text-muted my-1">
                                            {{ \Carbon\Carbon::parse($pesan->updated_at)->format('d M Y') }}
                                        </small>
                                        
                                        <div class="action-buttons" onclick="event.stopPropagation();">
                                            <a href="{{ route('dosen.pesan.show', $pesan->id) }}" class="btn btn-custom-primary btn-sm" style="font-size: 10px;">
                                                <i class="fas fa-eye me-1"></i>Lihat
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-5">
                            <div class="p-4 text-center">
                                <i class="fas fa-history fa-3x mb-3 text-muted"></i>
                                <h5>Belum ada riwayat pesan</h5>
                                <p class="text-muted">Riwayat pesan yang telah diakhiri akan muncul di sini</p>
                            </div>
                        </div>
                    @endif
                    
                    <!-- No Messages Found Message -->
                    <div id="noMessagesFound" class="no-messages-found">
                        <i class="fas fa-search fa-3x mb-3 text-muted"></i>
                        <h5>Tidak ada pesan yang ditemukan</h5>
                        <p class="text-muted">Silakan coba kata kunci lain atau pilih filter yang berbeda</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Role Switcher - Hanya menampilkan loading spinner -->
<div class="role-modal-backdrop" id="roleModalBackdrop">
    <div class="role-modal role-modal-success">
        <div class="role-modal-body py-4">
            <div class="role-loading-spinner" id="roleLoadingSpinner"></div>
            <p class="role-modal-message mt-3" id="roleModalMessage">Memuat...</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mobile sidebar functionality
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const mobileSidebar = document.getElementById('mobileSidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const closeSidebar = document.getElementById('closeSidebar');
    const mobileNavbar = document.getElementById('mobileNavbar');
    const mobileNavbarCompensation = document.getElementById('mobileNavbarCompensation');
    
    // Enhanced mobile navbar with scroll effect
    function handleMobileNavbarScroll() {
        if (!mobileNavbar || window.innerWidth > 991) return;
        
        const scrolled = window.scrollY > 50;
        
        if (scrolled) {
            mobileNavbar.classList.add('scrolled');
            mobileNavbarCompensation.classList.add('active');
        } else {
            mobileNavbar.classList.remove('scrolled');
            mobileNavbarCompensation.classList.remove('active');
        }
    }
    
    // Add scroll listener for mobile navbar effect
    window.addEventListener('scroll', handleMobileNavbarScroll);
    
    // Performance optimization: Throttle scroll events
    let ticking = false;
    
    function updateScrollEffects() {
        handleMobileNavbarScroll();
        ticking = false;
    }
    
    window.addEventListener('scroll', function() {
        if (!ticking) {
            requestAnimationFrame(updateScrollEffects);
            ticking = true;
        }
    });
    
    // Initialize scroll effects on load
    handleMobileNavbarScroll();
    
    // Open mobile sidebar
    if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', function() {
            mobileSidebar.classList.add('show');
            sidebarOverlay.style.display = 'block';
            setTimeout(() => {
                sidebarOverlay.classList.add('show');
            }, 10);
            
            // Prevent body scroll when sidebar is open
            document.body.style.overflow = 'hidden';
        });
    }
    
    // Close mobile sidebar
    function closeMobileSidebar() {
        mobileSidebar.classList.remove('show');
        sidebarOverlay.classList.remove('show');
        setTimeout(() => {
            sidebarOverlay.style.display = 'none';
        }, 300);
        
        // Restore body scroll
        document.body.style.overflow = '';
    }
    
    if (closeSidebar) {
        closeSidebar.addEventListener('click', closeMobileSidebar);
    }
    
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', closeMobileSidebar);
    }
    
    // Close sidebar when clicking on a menu item (mobile)
    const mobileMenuItems = document.querySelectorAll('#mobileSidebar .nav-link[href]');
    mobileMenuItems.forEach(item => {
        item.addEventListener('click', function() {
            // Add small delay to allow navigation
            setTimeout(closeMobileSidebar, 100);
        });
    });
    
    // Mobile dropdown functionality
    const mobileGrupDropdownToggle = document.getElementById('mobileGrupDropdownToggle');
    const mobileKomunikasiSubmenu = document.getElementById('mobileKomunikasiSubmenu');
    const mobileGrupDropdownIcon = document.getElementById('mobileGrupDropdownIcon');
    
    if (mobileGrupDropdownToggle) {
        mobileGrupDropdownToggle.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Toggle the collapse
            const isCollapsed = !mobileKomunikasiSubmenu.classList.contains('show');
            
            if (isCollapsed) {
                mobileKomunikasiSubmenu.classList.add('show');
                mobileGrupDropdownIcon.classList.remove('fa-chevron-down');
                mobileGrupDropdownIcon.classList.add('fa-chevron-up');
            } else {
                mobileKomunikasiSubmenu.classList.remove('show');
                mobileGrupDropdownIcon.classList.remove('fa-chevron-up');
                mobileGrupDropdownIcon.classList.add('fa-chevron-down');
            }
        });
    }
    
    // Initialize the grup dropdown manually (desktop)
    const grupDropdownToggle = document.getElementById('grupDropdownToggle');
    const komunikasiSubmenu = document.getElementById('komunikasiSubmenu');
    const grupDropdownIcon = document.getElementById('grupDropdownIcon');
    
    // Buat instance collapse
    const bsCollapse = new bootstrap.Collapse(komunikasiSubmenu, {
        toggle: false
    });
    
    if (grupDropdownToggle) {
        grupDropdownToggle.addEventListener('click', function(e) {
            e.preventDefault();
            // Toggle the collapse
            bsCollapse.toggle();
            
            // Toggle the icon
            grupDropdownIcon.classList.toggle('fa-chevron-up');
            grupDropdownIcon.classList.toggle('fa-chevron-down');
        });
    }
    
    // Fungsi pencarian dan filter pesan
    const searchInput = document.getElementById('searchInput');
    const filterButtons = document.querySelectorAll('.btn-filter');
    const messageCards = document.querySelectorAll('.message-card');
    const noMessagesFound = document.getElementById('noMessagesFound');
    
    // Simpan filter aktif saat ini
    let currentFilter = 'semua';
    
    // Fungsi untuk menerapkan filter dan pencarian
    function applyFilterAndSearch() {
        const searchTerm = searchInput.value.toLowerCase();
        let visibleCount = 0;
        
        messageCards.forEach(card => {
            const kategori = card.getAttribute('data-kategori');
            const pengirim = card.getAttribute('data-pengirim').toLowerCase();
            const judul = card.getAttribute('data-judul').toLowerCase();
            const cardContent = card.textContent.toLowerCase();
            
            // Check filter category
            const matchesFilter = currentFilter === 'semua' || kategori === currentFilter;
            
            // Check search term
            const matchesSearch = searchTerm === '' || 
                                pengirim.includes(searchTerm) || 
                                judul.includes(searchTerm) || 
                                cardContent.includes(searchTerm);
            
            // Show/hide card based on both conditions
            if (matchesFilter && matchesSearch) {
                card.classList.remove('hidden');
                visibleCount++;
            } else {
                card.classList.add('hidden');
            }
        });
        
        // Show/hide no messages found message
        if (visibleCount === 0 && messageCards.length > 0) {
            noMessagesFound.style.display = 'block';
        } else {
            noMessagesFound.style.display = 'none';
        }
    }
    
    // Event untuk input pencarian
    if (searchInput) {
        searchInput.addEventListener('input', applyFilterAndSearch);
    }
    
    // Event untuk tombol filter
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active-filter class from all buttons
            filterButtons.forEach(btn => {
                btn.classList.remove('active-filter');
                
                // Reset semua tombol ke outline style sesuai dengan jenisnya
                if (btn.id === 'filterPenting') {
                    btn.className = 'btn btn-outline-danger rounded-pill px-4 py-2 me-2 btn-filter';
                } else if (btn.id === 'filterUmum') {
                    btn.className = 'btn btn-outline-success rounded-pill px-4 py-2 me-2 btn-filter';
                } else if (btn.id === 'filterSemua') {
                    btn.className = 'btn btn-outline-primary rounded-pill px-4 py-2 btn-filter';
                }
            });
            
            // Add active-filter class to clicked button
            this.classList.add('active-filter');
            
            // Update current filter
            currentFilter = this.getAttribute('data-filter');
            
            // Apply filter and search
            applyFilterAndSearch();
        });
    });
    
    // Role switcher toggle
    const roleSwitcher = document.getElementById('roleSwitcher');
    if (roleSwitcher) {
        roleSwitcher.addEventListener('change', function() {
            // Tampilkan loading spinner
            showRoleModal('Memuat...');
            
            // Submit form langsung setelah delay singkat
            setTimeout(() => {
                document.getElementById('switchRoleForm').submit();
            }, 500);
        });
    }
    
    // Enhanced keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Esc untuk tutup sidebar mobile
        if (e.key === 'Escape') {
            // Close mobile sidebar if open
            if (mobileSidebar && mobileSidebar.classList.contains('show')) {
                closeMobileSidebar();
            }
        }
    });
    
    // Handle window resize to close mobile sidebar on desktop
    window.addEventListener('resize', function() {
        if (window.innerWidth > 991 && mobileSidebar && mobileSidebar.classList.contains('show')) {
            closeMobileSidebar();
        }
    });
    
    // Swipe gesture for mobile sidebar
    let touchStartX = 0;
    let touchEndX = 0;
    
    document.addEventListener('touchstart', function(e) {
        touchStartX = e.changedTouches[0].screenX;
    });
    
    document.addEventListener('touchend', function(e) {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipeGesture();
    });
    
    function handleSwipeGesture() {
        const swipeThreshold = 100;
        const swipeDistance = touchEndX - touchStartX;
        
        // Swipe right to open sidebar (only if not already open)
        if (swipeDistance > swipeThreshold && touchStartX < 50 && mobileSidebar && !mobileSidebar.classList.contains('show')) {
            if (window.innerWidth <= 991) {
                if (mobileMenuToggle) {
                    mobileMenuToggle.click();
                }
            }
        }
        
        // Swipe left to close sidebar (only if open)
        if (swipeDistance < -swipeThreshold && mobileSidebar && mobileSidebar.classList.contains('show')) {
            closeMobileSidebar();
        }
    }
    
    // Add haptic feedback for mobile interactions (if supported)
    function addHapticFeedback() {
        if ('vibrate' in navigator) {
            navigator.vibrate(50); // Short vibration
        }
    }
    
    // Add haptic feedback to button clicks on mobile
    const interactiveElements = [mobileMenuToggle, closeSidebar, ...mobileMenuItems];
    interactiveElements.forEach(element => {
        if (element) {
            element.addEventListener('touchstart', function() {
                if (window.innerWidth <= 991) {
                    addHapticFeedback();
                }
            });
        }
    });
    
    // Accessibility improvements
    function enhanceAccessibility() {
        // Add ARIA labels for better screen reader support
        if (mobileMenuToggle) {
            mobileMenuToggle.setAttribute('aria-label', 'Buka menu navigasi');
            mobileMenuToggle.setAttribute('aria-expanded', 'false');
        }
        
        if (closeSidebar) {
            closeSidebar.setAttribute('aria-label', 'Tutup menu navigasi');
        }
        
        if (mobileSidebar) {
            mobileSidebar.setAttribute('role', 'navigation');
            mobileSidebar.setAttribute('aria-label', 'Menu navigasi utama');
        }
        
        if (searchInput) {
            searchInput.setAttribute('aria-label', 'Cari pesan dalam riwayat');
        }
        
        // Update ARIA states when sidebar opens/closes
        if (mobileMenuToggle) {
            const originalMobileMenuClick = mobileMenuToggle.addEventListener;
            mobileMenuToggle.addEventListener('click', function() {
                this.setAttribute('aria-expanded', 'true');
            });
        }
        
        // Reset ARIA state when closing
        const originalCloseMobileSidebar = closeMobileSidebar;
        closeMobileSidebar = function() {
            originalCloseMobileSidebar();
            if (mobileMenuToggle) {
                mobileMenuToggle.setAttribute('aria-expanded', 'false');
            }
        };
    }
    
    enhanceAccessibility();
    
    // Event listeners untuk action buttons agar tidak memicu click pada card
    document.querySelectorAll('.action-buttons, .action-buttons *').forEach(element => {
        element.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });
    
    console.log('Mobile Riwayat Pesan Dosen initialized successfully');
});

// Fungsi untuk menampilkan modal loading sederhana
function showRoleModal(message) {
    const modal = document.getElementById('roleModalBackdrop');
    if (modal) {
        // Perbarui pesan jika ada
        if (message) {
            const messageElement = document.getElementById('roleModalMessage');
            if (messageElement) {
                messageElement.textContent = message;
            }
        }
        
        // Tampilkan modal
        modal.classList.add('show');
    }
}
</script>
@endpush