<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <script>
    if (localStorage.getItem('theme') === 'dark') {
      document.documentElement.classList.add('dark');
    }
  </script>
  <script>
  tailwind.config = {
      darkMode: 'class',
      theme: {
      extend: {
          colors: {
          customBg: '#171717',
          customText: '#B91C1C',
          },
      }
      }
  }
  </script>
  <style>
    .sidebar-link {
      position: relative;
      display: inline-block;
    }
    .sidebar-link::before,
    .sidebar-link::after {
      content: "";
      position: absolute;
      height: 2px;
      background-color: red;
      width: 0;
      top: 0;
      transition: width 0.3s ease;
    }
    .sidebar-link::before {
      left: 50%;
      transform: translateX(-50%);
    }
    .sidebar-link::after {
      right: 50%;
      transform: translateX(50%);
    }
    .sidebar-link:hover::before,
    .sidebar-link:hover::after {
      width: 50%;
    }
  </style>
</head>
<body class="bg-gray-100 text-black dark:bg-gray-900 dark:text-white transition duration-300">
  <nav class="fixed top-0 left-0 w-full bg-customText text-white z-40 shadow-md flex items-center justify-between px-4 py-3">

    <a href="../admin/login.php" class="ml-auto font-semibold">
      <i class="fa-solid fa-user-gear"></i> Login
    </a>

    <!-- Toggle button for sidebar -->
    <button id="sidebar-toggle" class="md:hidden text-white focus:outline-none ml-4 z-10">
      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M4 6h16M4 12h16M4 18h16" />
      </svg>
    </button>
  </nav>
<main class="p-6">
