<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | LITERIA</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Lora:ital,wght@0,500;0,600;0,700;1,500&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bs-primary: #0B1B3D; /* Deep Navy from brandkit */
            --bs-warning: #C6A664; /* Gold Accent */
            --bs-body-bg: #F9F6F0; /* Ivory Warm Base */
            --bs-body-font-family: 'Inter', sans-serif;
            --bs-heading-color: #0B1B3D;
        }
        
        body { 
            background-color: var(--bs-body-bg); 
            font-family: var(--bs-body-font-family);
            color: #1E1E1E;
        }
        
        h1, h2, h3, h4, h5, h6, .brand-font {
            font-family: 'Lora', serif;
            color: var(--bs-heading-color);
            letter-spacing: -0.2px;
        }

        .bg-primary { background-color: var(--bs-primary) !important; }
        .text-primary { color: var(--bs-primary) !important; }

        /* Status Chips */
        .chip {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 50rem;
            font-size: 0.8rem;
            font-weight: 500;
            border: 1px solid transparent;
        }
        .chip-available {
            background-color: #E6F4EA;
            color: #137333;
            border-color: #CEEAD6;
        }
        .chip-reserved {
            background-color: #E8F0FE;
            color: #1967D2;
            border-color: #D2E3FC;
        }
        .chip-overdue {
            background-color: #FCE8E6;
            color: #C5221F;
            border-color: #FAD2CF;
        }

        /* Data Table Header */
        .table-premium {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.02);
        }
        .table-premium thead th {
            background-color: #FAFAFA;
            color: #6B7280;
            text-transform: uppercase;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #E5E7EB;
            border-top: none;
            padding: 12px 16px;
        }
        .table-premium tbody td {
            padding: 16px;
            vertical-align: middle;
            border-bottom: 1px solid #F3F4F6;
            color: #374151;
            font-size: 0.9rem;
        }
        .table-premium tbody tr:hover {
            background-color: #F9FAFB;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.03) !important;
        }
        
        /* Topnav Styles */
        .topnav-dark .nav-link {
            color: rgba(255,255,255,0.7);
            font-weight: 500;
            font-size: 0.95rem;
            padding: 10px 20px;
            border-bottom: 3px solid transparent;
        }
        .topnav-dark .nav-link:hover, .topnav-dark .nav-link.active {
            color: white;
            border-bottom-color: var(--bs-warning);
        }
    </style>
</head>
<body>

