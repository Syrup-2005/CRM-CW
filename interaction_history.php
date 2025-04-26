<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interaction History</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles_background.css">
    <link rel="stylesheet" href="styles.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="finisher-header.es5.min.js"></script>
    <script src="functions.js" defer></script>
</head>
<body>
    <div class="header finisher-header" style="width: 100%; height: 100%;">
    
        <!-- Sidebar Navigation -->
        <nav>
            <input type="checkbox" id="sidebar-active">
            <label for="sidebar-active" class="open-sidebar-button">
                <svg xmlns="http://www.w3.org/2000/svg" height="32px" viewBox="0 -960 960 960" width="32px" fill="#e3e3e3"><path d="M120-240v-80h720v80H120Zm0-200v-80h720v80H120Zm0-200v-80h720v80H120Z"/></svg>
            </label>
    
            <label for="sidebar-active" id="overlay"></label>
    
            <div class="links-container">
                <label for="sidebar-active" class="close-sidebar-button">
                    <svg xmlns="http://www.w3.org/2000/svg" height="32px" viewBox="0 -960 960 960" width="32px" fill="#e3e3e3"><path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/></svg>
                </label>
    
                <!-- <h4 class="text-center">CRM Menu</h4> -->
                <a class="home-link" href="dashboard.html">Home</a>
                <a href="profile.html"><i class="fas fa-user"></i><span class="sidebar-text"> My Profile</span></a>
                <a href="customer.html"><i class="fas fa-customer"></i><span class="sidebar-text"> Customers</span></a>
                <a href="meetings.html"><i class="fas fa-calendar-alt"></i><span class="sidebar-text"> Meetings</span></a>
                <a href="calls.html"><i class="fas fa-phone"></i><span class="sidebar-text"> Calls</span></a>
                <a href="interaction_history.php"><i class="fas fa-interactions"></i><span class="sidebar-text"> Interaction History</span></a>
                <a href="logout.html"><i class="fas fa-sign-out-alt"></i><span class="sidebar-text"> Logout</span></a>
    
                <!-- darkmode button -->
                <input type="checkbox" id="darkmode-toggle" class="darkmode-toggle">
                <label for="darkmode-toggle" class="darkmode-btn"></label>
            </div>
        </nav>

        <h1>Search Interaction History</h1>

        <div class="content">
            <h1 class="gradient-abb">ABB Robotics</h1>

            <div class="container">
                <form id="search-form">
                    <label for="query">Search by keyword (optional):</label>
                    <input type="text" id="query" name="query">

                    <label for="type">Select Interaction Type:</label>
                    <select id="type" name="type">
                        <option value="all">All</option>
                        <option value="call">Call</option>
                        <option value="meeting">Meeting</option>
                        <!-- <option value="email">Email</option> -->
                    </select>

                    <label for="purpose">Select Purpose of Interaction:</label>
                    <select id="purpose" name="purpose">
                        <option value="">All</option>
                        <option value="initial">Initial Contact</option>
                        <option value="follow-up">Follow-Up</option>
                        <option value="support">Support</option>
                        <option value="sales">Sales call</option>
                        <option value="progress">Progress Report</option>
                    </select>

                    <input type="submit" value="Search">
                </form>

                <div class="suggestion-container">
                    <?php include 'get_suggestions.php'; ?>
                </div>
            </div> 
            <div class="table-container">
                <h1>Interaction History</h1>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Customer ID</th>
                                <th>Customer Name</th>
                                <th>Email</th>
                                <th>Date</th>
                                <th>Interaction Type</th>
                                <th>Interaction Purpose</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody id="interaction-table-body"></tbody>
                    </table>
                </div>
            </div>
        </div> 
    </div> 


    <script>
        // Autofill input with clicked suggestion
        document.querySelectorAll('.suggestion-bubble').forEach(bubble => {
            bubble.addEventListener('click', () => {
                document.getElementById('query').value = bubble.textContent;
            });
        });
        
        
        document.getElementById('search-form').addEventListener('submit', function(event) {
            event.preventDefault();

            const query = document.getElementById('query').value || '';
            const type = document.getElementById('type').value;
            const purpose = document.getElementById('purpose').value || '';

            // Build the fetch URL with all parameters
            const url = `search_interaction.php?query=${encodeURIComponent(query)}&type=${encodeURIComponent(type)}&purpose=${encodeURIComponent(purpose)}`;

            fetch(url)
                .then(res => res.json())
                .then(data => {
                    const tbody = document.getElementById("interaction-table-body");
                    tbody.innerHTML = ''; // Always clear first

                    if (data.length === 0) {
                        tbody.innerHTML = "<tr><td colspan='7'>No interactions found.</td></tr>";
                    } else {
                        data.forEach(interaction => {
                            const row = document.createElement("tr");
                            row.innerHTML = `
                                <td>${interaction.customer_id}</td>
                                <td>${interaction.customer_name}</td>
                                <td>${interaction.email}</td>
                                <td>${interaction.date}</td>
                                <td>${interaction.type}</td>
                                <td>${interaction.interaction_purpose}</td>
                                <td>${interaction.notes}</td>
                            `;
                            tbody.appendChild(row);
                        });
                    }
                })
                .catch(err => {
                    console.error("Search error:", err);
                });
        });
    </script>
</body>
</html>
