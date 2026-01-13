<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - Heathly</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            sidebar: '#1E293B',
            'sidebar-hover': '#2D3748',
            'doctor-card': '#4ADE80',
            'patient-card': '#FF6B6B',
            'article-card': '#3B82F6',
            'medicine-card': '#F59E0B',
            'process': '#F59E0B',
            'canceled': '#EF4444',
            'finished': '#84CC16',
          },
          fontFamily: { sans: ['Inter', 'sans-serif'] },
        }
      }
    }
  </script>
  <style>
    body { font-family: 'Inter', sans-serif; }
  </style>
</head>
<body class="bg-gray-100">
<div class="flex h-screen">
