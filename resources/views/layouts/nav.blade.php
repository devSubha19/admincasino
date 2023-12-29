<style>
   .logo {
     font-size: 3.5rem;
     font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* Updated font family */
     color: #00ccff; /* Light blue glowing color */
     text-shadow: 0 0 10px #00ccff, 0 0 20px #00ccff, 0 0 30px #00ccff;
     animation: neon-glow 1.5s ease-in-out infinite alternate;
     display: inline-block;
     transition: text-shadow 0.3s ease; /* Added transition for smooth glow effect */
   }

   @keyframes neon-glow {
     to {
       color: #0088cc; /* Slightly darker blue neon color */
       text-shadow: 0 0 10px #0088cc, 0 0 20px #0088cc, 0 0 30px #0088cc;
     }
   }

   .navbar {
     background-color: rgb(0, 0, 0);
     height: 7rem;
     border-bottom: 1px solid transparent; /* Set initial border-bottom to transparent */
     transition: border-bottom 0.3s ease; /* Added transition for smooth border-bottom effect */
     
   }

   .navbar {
     border-bottom: 2px solid #00ccff;  
   }

   .navbar a {
     text-decoration: none;
     color: white;
   }

   .navbar ul {
     display: flex;
     list-style-type: none;
     gap: 30px;
     padding-right: 60px;
     color: white;
   }

   .navbar li {
     display: flex;
     gap: 5px;
     cursor: pointer;
   }
</style>

<div class="navbar">  
  <a href="index"><div class="logo" style="font-family: trebuchet ms">ProjectCanada</div></a>
  
  <ul>
    <li id="menu-btn"><i class="fa-solid fa-bars"></i>Menu</li>
    <li><i class="fa-solid fa-sliders"></i>Theme</li>
    <li><i class="fa-solid fa-key"></i>Change Password</li>
    <a href="logout"><li><i class="fa-solid fa-arrow-right-from-bracket"></i>Logout</li></a>
  </ul>
</div>
